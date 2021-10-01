<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\PassportTestCase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\RegisterController
 */
class RegisterControllerTest extends PassportTestCase
{
    use RefreshDatabase;

//    /** @test */
//    function ユーザー登録画面を開ける()
//    {
//        $this->get('/register')
//            ->assertOk();
//    }

    /** @test api/register */
    function ユーザー登録できる()
    {
        // validData
        $validData = User::factory()->validData();

        // post:register
        $this->post('api/register', $validData)
            ->assertOk();

        // usersテーブルにユーザーが登録されているか

        // パスワード検証

        // 指定のユーザーが認証されているか


    }

    // @TODO:不正なデータではユーザー登録できない
    // @TODO:登録後ログインさせたい

}
