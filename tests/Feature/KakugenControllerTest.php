<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Kakugen;
use App\Models\MyKakugen;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\PassportTestCase;
use Tests\TestCase;

class KakugenControllerTest extends PassportTestCase
{
    // @TODO:共通処理にまとめる
    use RefreshDatabase;

    private $accessToken = null;

    private $header = null;

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

        $this->header = [
            'Authorization' => 'Bearer '.$this->accessToken,
            'Accept' => 'application/json'
        ];
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
}
