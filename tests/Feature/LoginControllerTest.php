<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\PassportTestCase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\LoginController
 */
class LoginControllerTest extends PassportTestCase
{
    use RefreshDatabase;

    /** @test api/login */
    function ログイン時の入力チェック()
    {
        $url = 'api/login';

        app()->setLocale('testing');

        $this->post($url, ['email' => ''])->assertSessionHasErrors(['email' => 'required']);
        $this->post($url, ['email' => 'aa@bb@cc'])->assertSessionHasErrors(['email' => 'email']);
        $this->post($url, ['email' => 'aa@ああ.いい'])->assertSessionHasErrors(['email' => 'email']);
        $this->post($url, ['password' => ''])->assertSessionHasErrors(['password' => 'required']);

    }

    /** @test api/login */
    function ログインできる()
    {
        $postData = [
            'email' => 'aaa@bbb.net',
            'password' => 'abcd1234',
        ];

        $dbData = [
            'email' => 'aaa@bbb.net',
            'password' => bcrypt('abcd1234'),
        ];

        $user = User::factory()->create($dbData);

        $this->post('api/login', $postData)
            ->assertOk();

        // 指定したユーザーが認証されているか
        $this->assertAuthenticatedAs($user);
    }

    /** @test api/login */
    function IDを間違えているのでログインできない()
    {
        $postData = [
            'email' => 'aaa@bbb.net',
            'password' => 'abcd1234',
        ];

        $dbData = [
            'email' => 'ccc@bbb.net',
            'password' => bcrypt('abcd1234'),
        ];

        $user = User::factory()->create($dbData);

        $this->post('api/login', $postData)
            ->assertStatus(302)
            ->assertSessionHasErrors(['email' => 'メールアドレスまたはパスワードが間違っています。']);

        // ログインに失敗しているので認証されていないこと
        $this->assertGuest();
    }

    /** @test api/login */
    function パスワードを間違えいているのでログインできない()
    {
        $postData = [
            'email' => 'aaa@bbb.net',
            'password' => 'abcd1234',
        ];

        $dbData = [
            'email' => 'aaa@bbb.net',
            'password' => bcrypt('abcd5678'),
        ];

        $user = User::factory()->create($dbData);

        $this->post('api/login', $postData)
            ->assertStatus(302)
            ->assertSessionHasErrors(['email' => 'メールアドレスまたはパスワードが間違っています。']);

        // ログインに失敗しているので認証されていないこと
        $this->assertGuest();
    }

    /** @test api/login */
    function 認証エラーなのでvalidationExceptionの例外が発生する()
    {
        $this->withoutExceptionHandling();

        $postData = [
            'email' => 'aaa@bbb.net@',
            'password' => 'abcd1234',
        ];

        $dbData = [
            'email' => 'aaa@bbb.net',
            'password' => bcrypt('abcd1234'),
        ];

        $user = User::factory()->create($dbData);

        try {
            $this->post('api/login', $postData);
            $this->fail('validationExceptionの例外が発生しませんでした。');
        } catch (ValidationException $e) {
            $this->assertEquals('メールアドレスには、有効なメールアドレスを指定してください。',
                $e->errors()['email'][0] ?? '');
        }
    }

    /** @test api/login */
    function 認証OKなのでvalidationExceptionの例外が出ない()
    {
        $this->withoutExceptionHandling();

        $postData = [
            'email' => 'aaa@bbb.net',
            'password' => 'abcd1234',
        ];

        $dbData = [
            'email' => 'aaa@bbb.net',
            'password' => bcrypt('abcd1234'),
        ];

        $user = User::factory()->create($dbData);

        try {
            $this->post('api/login', $postData);
            $this->assertTrue(true);
        } catch (ValidationException $e) {
            $this->fail('validationExceptionの例外が発生しました。');
        }
    }

    /** @test api/logout */
    function ログアウトできる()
    {
        // @TODO：ログアウトテスト実装
        $dbData = [
            'email' => 'aaa@bbb.net',
            'password' => bcrypt('abcd1234'),
        ];

        $user = User::factory()->create($dbData);

        $this->actingAs($user);

        $this->assertAuthenticatedAs($user);

//        $this->post('api/logout');
//
//        $this->assertGuest();

//        $user = $this->login();
//
//        $this->assertAuthenticatedAs($user);
//
//        $this->post('api/logout')
//            ->assertStatus(401);

//
//        $this->post('api/logout')
//            ->assertStatus(401);

    }
}
