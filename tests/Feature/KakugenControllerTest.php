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

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    /** @test api/kakugens */
    function 格言が3件取得できる()
    {
        $this->get('api/kakugens')
            ->assertJsonCount(3);
    }

    /** @test api/kakugens */
    function 取得した3件の格言の中にMy格言は含まれていない()
    {
        $userData = [
            'name' => 'Jon Daniel',
            'email' => 'jon@example.com'
        ];
        $user = User::factory()->create($userData);

        $kakugenData = [
            'content' => 'aaaaaa',
            'person_name' => 'ryou'
        ];
        $kakugen = Kakugen::factory()->create($kakugenData);

        $dbData = [
            'user_id' => $user->id,
            'kakugen_id' => $kakugen->id,
            'favorite' => true,
            'comment' => 'aaa'
        ];

        $my_kakugen = MyKakugen::factory()->create($dbData)->toArray();

        $this->get('api/kakugens')
            ->assertJsonMissing($my_kakugen);
    }
}
