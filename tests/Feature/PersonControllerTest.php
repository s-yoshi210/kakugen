<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\PassportTestCase;
use Tests\TestCase;

class PersonControllerTest extends PassportTestCase
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

    /** @test api/person */
    function wikipediaから人物情報を取得できる()
    {
        $person = [
            'person_name' => 'ヘンリー・フォード'
        ];
        $this->json('GET', 'api/person', $person, $this->header)
            ->assertOk()
            ->assertJsonFragment(['title' => $person['person_name']]);
    }
}
