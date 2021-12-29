<?php

namespace Tests\Unit;

use App\Exceptions\DuplicateVoteException;
use App\Exceptions\VoteNotFoundException;
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
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusOpen->id,
			'description' => 'Description of my first idea',
		]);

		Vote::factory()->create([
			'user_id' => $user->id,
			'idea_id' => $idea->id,
		]);

		$this->assertTrue($idea->isVotedByUser($user));
		$this->assertFalse($idea->isVotedByUser($userB));
		$this->assertFalse($idea->isVotedByUser(null));
	}

	/** @test*/
	public function user_can_vote_for_idea()
	{
		$user = User::factory()->create();

		$categoryOne = Category::factory()->create(['name' => 'Category 1']);

		$statusOpen = Status::factory()->create(['name' => 'Open', 'class' => 'bg-gray-200']);

		$idea = Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusOpen->id,
			'description' => 'Description of my first idea',
		]);

		$this->assertFalse($idea->isVotedByUser($user));
		$idea->vote($user);
		$this->assertTrue($idea->isVotedByUser($user));
	}

	/** @test*/
	public function voting_for_an_idea_thats_already_voted_for_throws_exception()
	{
		$user = User::factory()->create();

		$categoryOne = Category::factory()->create(['name' => 'Category 1']);

		$statusOpen = Status::factory()->create(['name' => 'Open', 'class' => 'bg-gray-200']);

		$idea = Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusOpen->id,
			'description' => 'Description of my first idea',
		]);

		Vote::factory()->create([
			'user_id' => $user->id,
			'idea_id' => $idea->id,
		]);

		$this->expectException(DuplicateVoteException::class);

		$idea->vote($user);
	}

	/** @test*/
	public function user_can_remove_vote_for_idea()
	{
		$user = User::factory()->create();

		$categoryOne = Category::factory()->create(['name' => 'Category 1']);

		$statusOpen = Status::factory()->create(['name' => 'Open', 'class' => 'bg-gray-200']);

		$idea = Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusOpen->id,
			'description' => 'Description of my first idea',
		]);

		Vote::factory()->create([
			'user_id' => $user->id,
			'idea_id' => $idea->id,
		]);

		$this->assertTrue($idea->isVotedByUser($user));
		$idea->removeVote($user);
		$this->assertFalse($idea->isVotedByUser($user));
	}

	/** @test*/
	public function removing_a_vote_that_doesnt_exists_throws_an_exception()
	{
		$user = User::factory()->create();

		$categoryOne = Category::factory()->create(['name' => 'Category 1']);

		$statusOpen = Status::factory()->create(['name' => 'Open', 'class' => 'bg-gray-200']);

		$idea = Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusOpen->id,
			'description' => 'Description of my first idea',
		]);

		$this->expectException(VoteNotFoundException::class);

		$idea->removeVote($user);
	}
}
