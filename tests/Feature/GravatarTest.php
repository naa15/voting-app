<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class GravatarTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function user_can_generate_gravatar_default_image_when_no_email_found_first_character_a()
    {
        $user = User::factory()->create([
            'name' => 'Andre',
            'email' => 'afakeemail@fake.com'
        ]);

        $gravatarUrl = $user->getAvatar();

        $this->assertEquals(
            'https://www.gravatar.com/avatar/'.md5($user->email).'?s=200&d=https://s3.amazonaws.com/'
            .'laracasts/images/forum/avatars/default-avatar-1.png',
            $gravatarUrl
        );

        $response = Http::get($gravatarUrl);

        $this->assertTrue($response->successful());
    }

    /** @test **/
    public function user_can_generate_gravatar_default_image_when_no_email_found_first_character_z()
    {
        $user = User::factory()->create([
            'name' => 'Andre',
            'email' => 'zfakeemail@fake.com'
        ]);

        $gravatarUrl = $user->getAvatar();

        $this->assertEquals(
            'https://www.gravatar.com/avatar/'.md5($user->email).'?s=200&d=https://s3.amazonaws.com/'
            .'laracasts/images/forum/avatars/default-avatar-26.png',
            $gravatarUrl
        );

        $response = Http::get($gravatarUrl);
        
        $this->assertTrue($response->successful());
    }

    /** @test **/
    public function user_can_generate_gravatar_default_image_when_no_email_found_first_character_0()
    {
        $user = User::factory()->create([
                'name' => 'Andre',
                'email' => '0fakeemail@fake.com'
            ]);
    
        $gravatarUrl = $user->getAvatar();
    
        $this->assertEquals(
            'https://www.gravatar.com/avatar/'.md5($user->email).'?s=200&d=https://s3.amazonaws.com/'
                .'laracasts/images/forum/avatars/default-avatar-27.png',
            $gravatarUrl
        );

        $response = Http::get($gravatarUrl);
        
        $this->assertTrue($response->successful());
    }

    /** @test **/
    public function user_can_generate_gravatar_default_image_when_no_email_found_first_character_9()
    {
        $user = User::factory()->create([
            'name' => 'Andre',
            'email' => '9fakeemail@fake.com'
        ]);

        $gravatarUrl = $user->getAvatar();

        $this->assertEquals(
            'https://www.gravatar.com/avatar/'.md5($user->email).'?s=200&d=https://s3.amazonaws.com/'
            .'laracasts/images/forum/avatars/default-avatar-36.png',
            $gravatarUrl
        );

        $response = Http::get($gravatarUrl);
        
        $this->assertTrue($response->successful());
    }
}
