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

    /** @test api/register */
    function ユーザー登録できる()
    {
        $this->withoutExceptionHandling();

        // validData
        $validData = User::factory()->validData();

        // post:register
        $this->post('api/register', $validData)
            ->assertOk();

        unset($validData['password']);
        unset($validData['password_confirmation']);

        // usersテーブルにユーザーが登録されているか
        $this->assertDatabaseHas('users', $validData);

        // パスワード検証
        $user = User::firstWhere($validData);
        $this->assertNotNull($user);

        $this->assertTrue(\Hash::check('abcd1234', $user->password));

        // 指定されたユーザーが認証されているか
        $this->assertAuthenticatedAs($user);

    }

    /** @test api/register */
    function 不正なデータではユーザー登録できない()
    {
        $url = 'api/register';

        // name
        $this->post($url, ['name' => ''])->assertSessionHasErrors(['name' => 'ユーザー名は必ず指定してください。']);
        $this->post($url, ['name' => str_repeat('a', 21)])->assertSessionHasErrors(['name' => 'ユーザー名は、20文字以下で指定してください。']);
        $this->post($url, ['name' => str_repeat('a', 20)])->assertSessionDoesntHaveErrors('name');

        // email
        $this->post($url, ['email' => ''])->assertSessionHasErrors(['email' => 'メールアドレスは必ず指定してください。']);
        $this->post($url, ['email' => 'aa@bb@'])->assertSessionHasErrors(['email' => 'メールアドレスには、有効なメールアドレスを指定してください。']);
        $this->post($url, ['email' => 'aa@あああb@'])->assertSessionHasErrors(['email' => 'メールアドレスには、有効なメールアドレスを指定してください。']);

        User::factory()->create(['email' => 'aa@bbb.net']);
        $this->post($url, ['email' => 'aa@bbb.net'])->assertSessionHasErrors(['email' => 'メールアドレスの値は既に存在しています。']);

        // password
        $this->post($url, ['password' => ''])->assertSessionHasErrors(['password' => 'パスワードは必ず指定してください。']);
        $this->post($url, ['password' => 'abcd123'])->assertSessionHasErrors(['password' => 'パスワードは、8文字以上で指定してください。']);
        $this->post($url, ['password' => 'abcdefgh123567890'])->assertSessionHasErrors(['password' => 'パスワードは、16文字以下で指定してください。']);
//        $this->post($url, ['password' => 'abcd1234', 'password_confirmed' => 'abcd1234'])->assertSessionDoesntHaveErrors('password');

    }

}
