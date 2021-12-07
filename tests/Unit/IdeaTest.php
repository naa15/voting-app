<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Idea;
use App\Models\Vote;
use App\Models\User;
use App\Models\Category;
use App\Models\Status;

class IdeaTest extends TestCase
{
    use RefreshDatabase;

    /** @test*/
    public function can_check_if_idea_is_voted_by_specific_user()
    {
        $user = User::factory()->create();
        $userB = User::factory()->create();
    
        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
             
        $statusOpen = Status::factory()->create(['name' => 'Open', 'class' => 'bg-gray-200']);
     
        $idea = Idea::factory()->create([
                 'user_id' => $user->id,
                 'title' => 'My First Idea',
                 'category_id' => $categoryOne->id,
                 'status_id' => $statusOpen->id,
                 'description'=> 'Description of my first idea',
             ]);
        
        Vote::factory()->create([
            'user_id' => $user->id,
            'idea_id' => $idea->id,
        ]);

        $this->assertTrue($idea->isVotedByUser($user));
        $this->assertFalse($idea->isVotedByUser($userB));
        $this->assertFalse($idea->isVotedByUser(null));
    }
}
