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

class CommentControllerTest extends PassportTestCase
{
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

    /** @test api/kakugens/{kakugen_id}/comment */
    function コメントが登録できる()
    {
        $kakugen = Kakugen::first();

        $dbData = [
            'user_id' => $this->user->id,
            'kakugen_id' => $kakugen->id,
            'comment' => 'sample comment'
        ];

        // コメント登録API実行
        $this->post('api/kakugens/' . $kakugen->id . '/comment', $dbData, $this->header)
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

        $this->post('api/kakugens/' . $kakugen->id . '/comment', $editData, $this->header)
            ->assertOk();

        $my_kakugen = MyKakugen::where('user_id', $this->user->id)
            ->where('kakugen_id', $kakugen->id)
            ->get();

        // 登録されているコメントがリクエストしたものであること
        $this->assertEquals($editData['comment'], $my_kakugen[0]->comment);
    }

    /** @test api/kakugens/{kakugen_id}/comment */
    function コメントを編集できる()
    {
        // コメントデータを作成
        $kakugen = Kakugen::first();

        $dbData = [
            'user_id' => $this->user->id,
            'kakugen_id' => $kakugen->id,
            'comment' => 'first comment'
        ];
        MyKakugen::factory()->create($dbData);

        // コメント編集
        $editComment = [
            'comment' => 'edit comment'
        ];
        $this->put('api/kakugens/' . $kakugen->id . '/comment', $editComment, $this->header);

        // コメントが編集されていることを確認
        $response = MyKakugen::where('user_id', $this->user->id)
                        ->where('kakugen_id', $kakugen->id)
                        ->where('comment', $editComment);
        $this->assertEquals(1, $response->count());
    }

    /** @test api/kakugens/{kakugen_id}/delete */
    function コメントを削除できる()
    {
        // コメントデータを作成
        $kakugen = Kakugen::first();

        $dbData = [
            'user_id' => $this->user->id,
            'kakugen_id' => $kakugen->id,
            'comment' => 'save comment'
        ];
        MyKakugen::factory()->create($dbData);

        // コメント削除
        $this->delete('api/kakugens/' . $kakugen->id . '/comment', [], $this->header)
            ->assertOk();

        // コメントデータが存在しないことを確認
        $response = MyKakugen::where('user_id', $this->user->id)
            ->where('kakugen_id', $kakugen->id)
            ->whereNotNull('comment');
        $this->assertEquals(0, $response->count());
    }

    // // @TODO:指定文字数以上のコメントは登録できないケースを追加
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
}
