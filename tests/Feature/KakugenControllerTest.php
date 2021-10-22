<?php

namespace Tests\Feature;

use App\Models\Kakugen;
use App\Models\MyKakugen;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\PassportTestCase;
use Tests\TestCase;

class KakugenControllerTest extends PassportTestCase
{
    use RefreshDatabase;

    private $accessToken = null;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');

        // 事前ログイン
        $this->user = User::factory()->create([
            'name' => 'test',
            'email' => 'sample@example.com',
            'password' =>bcrypt('abcd1234')
        ]);

        $response = $this->post('api/login', [
            'email' => 'sample@example.com',
            'password' => 'abcd1234'
        ]);

        $this->accessToken = $response->decodeResponseJson()['token'];
    }

    /** @test api/kakugens */
    function 格言が3件取得できる()
    {
        $response = $this->get('api/kakugens', [
            'Authorization' => 'Bearer '.$this->accessToken,
            'Accept' => 'application/json'
        ]);

        $response->assertJsonCount(3);
    }

    /** @test api/kakugens */
    function 取得した3件の格言の中にMy格言は含まれていない()
    {
        $kakugenData = [
            'content' => 'aaaaaa',
            'person_name' => 'ryou'
        ];
        $kakugen = Kakugen::factory()->create($kakugenData);

        $dbData = [
            'user_id' => $this->user->id,
            'kakugen_id' => $kakugen->id,
            'favorite' => true,
            'comment' => 'aaa'
        ];

        $my_kakugen = MyKakugen::factory()->create($dbData)->toArray();

        $this->get('api/kakugens', [
            'Authorization' => 'Bearer '.$this->accessToken,
            'Accept' => 'application/json'
        ])
            ->assertJsonMissing($my_kakugen);
    }

    /** @test api/{kakugen_id}/favorite */
    function お気に入り登録できる()
    {
        $kakugen = Kakugen::first();

        $header = [
            'Authorization' => 'Bearer '.$this->accessToken,
            'Accept' => 'application/json'
        ];

        $dbData = [
            'user_id' => $this->user->id,
            'kakugen_id' => $kakugen->id
        ];
        MyKakugen::factory()->create($dbData);

        $this->post('api/kakugens/'. $kakugen->id . '/favorite', $dbData, $header)
            ->assertOk();

        // 登録されていることを確認
        $response = MyKakugen::where('user_id', $this->user->id)
                        ->where('kakugen_id', $kakugen->id);
        $this->assertEquals(1, $response->count());
    }

    /** @test api/{kakugen_id}/favorite */
    function 既にデータが存在する場合でもお気に入り登録できる()
    {
        // My格言データを作成(favorite:false)
        $kakugen = Kakugen::first();

        $dbData = [
            'user_id' => $this->user->id,
            'kakugen_id' => $kakugen->id,
            'favorite' => false
        ];

        MyKakugen::factory()->create($dbData);

        $header = [
            'Authorization' => 'Bearer '.$this->accessToken,
            'Accept' => 'application/json'
        ];

        // お気に入り登録
        $this->post('api/kakugens/'. $kakugen->id . '/favorite', $dbData, $header)
            ->assertOk();

        // favorite:trueになっていることを確認
        $response = MyKakugen::where('user_id', $this->user->id)
            ->where('kakugen_id', $kakugen->id)
            ->first()
            ->toArray();

        $this->assertSame(1, $response['favorite']);

    }

    /** @test api/{kakugen_id}/unfavorite */
    function お気に入り解除できる()
    {
        // 事前にMy名言データを作成する
        $kakugen = Kakugen::first();

        $dbData = [
            'user_id' => $this->user->id,
            'kakugen_id' => $kakugen->id
        ];
        MyKakugen::factory()->create($dbData);

        $header = [
            'Authorization' => 'Bearer '.$this->accessToken,
            'Accept' => 'application/json'
        ];

        // お気に入り解除する
        $this->delete('api/kakugens/'. $kakugen->id . '/unfavorite', $dbData, $header)
            ->assertOk();

        // 解除されていることを確認
        $response = MyKakugen::where('user_id', $this->user->id)
            ->where('kakugen_id', $kakugen->id)
            ->where('favorite', true);
        $this->assertEquals(0, $response->count());
    }
}
