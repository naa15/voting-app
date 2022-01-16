<?php

namespace Tests\Feature;

use App\Http\Livewire\IdeasIndex;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Vote;
use App\Models\Idea;
use App\Models\Status;
use Livewire\Livewire;

class SearchFilterTest extends TestCase
{
	use RefreshDatabase;

	/** @test*/
	public function search_works_for_more_than_3_characters()
	{
		$user = User::factory()->create();

		$categoryOne = Category::factory()->create(['name' => 'Category 1']);

		$statusImplemented = Status::factory()->create(['id' => 4, 'name' => 'Implemented']);

		$ideaOne = Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusImplemented->id,
			'description' => 'My first idea description',
		]);

		$ideaTwo = Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My Second Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusImplemented->id,
			'description' => 'My first idea description',
		]);

		$ideaThree = Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My Third Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusImplemented->id,
			'description' => 'My first idea description',
		]);

		Vote::factory()->create([
			'idea_id' => $ideaOne->id,
			'user_id' => $user->id,
		]);

		Livewire::test(IdeasIndex::class)
			->set('search', 'Second')
			->assertViewHas('ideas', function ($ideas) {
				return $ideas->count() == 1
					&& $ideas->first()->title === 'My Second Idea';
			});
	}

	/** @test*/
	public function does_not_perform_search_if_less_than_3_characters()
	{
		$user = User::factory()->create();

		$categoryOne = Category::factory()->create(['name' => 'Category 1']);

		$statusImplemented = Status::factory()->create(['id' => 4, 'name' => 'Implemented']);

		$ideaOne = Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusImplemented->id,
			'description' => 'My first idea description',
		]);

		$ideaTwo = Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My Second Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusImplemented->id,
			'description' => 'My first idea description',
		]);

		$ideaThree = Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My Third Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusImplemented->id,
			'description' => 'My first idea description',
		]);

		Vote::factory()->create([
			'idea_id' => $ideaOne->id,
			'user_id' => $user->id,
		]);

		Livewire::test(IdeasIndex::class)
			->set('search', 'ab')
			->assertViewHas('ideas', function ($ideas) {
				return $ideas->count() == 3;
			});
	}

	/** @test*/
	public function search_works_correctly_with_category_filters()
	{
		$user = User::factory()->create();

		$categoryOne = Category::factory()->create(['name' => 'Category 1']);
		$categoryTwo = Category::factory()->create(['name' => 'Category 2']);

		$statusImplemented = Status::factory()->create(['id' => 4, 'name' => 'Implemented']);

		$ideaOne = Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusImplemented->id,
			'description' => 'My first idea description',
		]);

		$ideaTwo = Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My Second Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusImplemented->id,
			'description' => 'My first idea description',
		]);

		$ideaThree = Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My Third Idea',
			'category_id' => $categoryTwo->id,
			'status_id'   => $statusImplemented->id,
			'description' => 'My first idea description',
		]);

		Livewire::test(IdeasIndex::class)
			->set('category', 'Category 1')
			->set('search', 'Idea')
			->assertViewHas('ideas', function ($ideas) {
				return $ideas->count() == 2;
			});
	}
}
