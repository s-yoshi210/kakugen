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

class MyKakugenControllerTest extends PassportTestCase
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

    /** @test api/mykakugens */
    function My名言一覧が取得できる()
    {
        // My名言データを２件作成
        $kakugens = Kakugen::take(2)->get();

        $firstData = [
            'user_id' => $this->user->id,
            'kakugen_id' => $kakugens[0]->id,
            'favorite' => true,
            'comment' => 'save comment'
        ];
        MyKakugen::factory()->create($firstData);

        $secondData = [
            'user_id' => $this->user->id,
            'kakugen_id' => $kakugens[1]->id,
            'favorite' => true,
            'comment' => 'save comment'
        ];
        MyKakugen::factory()->create($secondData);

        // ログインユーザーのMy名言が２件登録されていること
        $this->get('api/mykakugens', $this->header)
            ->assertJsonCount(2);
    }
}
