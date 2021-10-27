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

    /** @test api/kakugens/{kakugen_id}/comment */
    function コメントが登録できる()
    {
        $kakugen = Kakugen::first();

        $dbData = [
            'user_id' => $this->user->id,
            'kakugen_id' => $kakugen->id,
            'comment' => 'sample comment'
        ];

        $header = [
            'Authorization' => 'Bearer '.$this->accessToken,
            'Accept' => 'application/json'
        ];

        // コメント登録API実行
        $this->post('api/kakugens/' . $kakugen->id . '/comment', $dbData, $header)
            ->assertOk();

        // 作成したコメントデータを取得
        $my_kakugen = MyKakugen::where('user_id', $this->user->id)
            ->where('kakugen_id', $kakugen->id)
            ->whereNotNull('comment')
            ->get();

        // 取得したコメントデータの件数が１件であること
        $this->assertEquals(1, $my_kakugen->count());

        // 登録されている内容がリクエストしたものであること
        $this->assertEquals($dbData['comment'], $my_kakugen[0]->comment);

        // 新規登録データである場合、favoriteはfalseであること
        $this->assertEquals(false, $my_kakugen[0]->favorite);

        // 上記で新規登録したコメントデータを編集
        $editData = [
            'comment' => 'edit comment'
        ];

        $this->post('api/kakugens/' . $kakugen->id . '/comment', $editData, $header)
            ->assertOk();

        $my_kakugen = MyKakugen::where('user_id', $this->user->id)
            ->where('kakugen_id', $kakugen->id)
            ->get();

        // 登録されているコメントがリクエストしたものであること
        $this->assertEquals($editData['comment'], $my_kakugen[0]->comment);
    }

//    /** @test api/kakugens/{kakugen_id}/comment */
////    function 指定文字数以上のコメントは登録できない()
////    {
////        $this->withoutExceptionHandling();
////        $kakugen = Kakugen::first();
////
////        $dbData = [
////            'comment' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'
////        ];
////
////        $header = [
////            'Authorization' => 'Bearer '.$this->accessToken,
////            'Accept' => 'application/json'
////        ];
////
////        $this->post('api/kakugens/' . $kakugen->id . '/comment', $dbData, $header)
////            ->assertSessionHasErrors(['comment' => 'コメントは、1000文字以下で指定してください。']);
////    }

//    /** @test api/kakugens/{kakugen_id}/edit */
//    function コメントを編集できる()
//    {
//        // コメント登録
//        $kakugen = Kakugen::first();
//
//        $dbData = [
//            'user_id' => $this->user->id,
//            'kakugen_id' => $kakugen->id,
//            'comment' => 'sample comment'
//        ];
//
//        $header = [
//            'Authorization' => 'Bearer '.$this->accessToken,
//            'Accept' => 'application/json'
//        ];
//
//        $this->post('api/kakugens/' . $kakugen->id . '/comment', $dbData, $header);
//
//        // コメント編集API実行
//        $editData = [
//            'user_id' => $this->user->id,
//            'kakugen_id' => $kakugen->id,
//            'comment' => 'edit comment'
//        ];
//        $this->put('api/kakugens/' . $kakugen->id . '/edit', $editData, $header)
//            ->assertOk();
//
//        // 変更したコメントデータ取得
//        $response = MyKakugen::where('user_id', $this->user->id)
//            ->where('kakugen_id', $kakugen->id)
//            ->get();
//
//        // 変更した内容と一致することを確認
//        $this->assertEquals($editData->comment, $response[0]->comment);
//
//    }

//    // コメント削除できる
//    /** @test api/kakugens/{kakugen_id}/delete */
//    function コメントを削除できる()
//    {
//
//    }

}
