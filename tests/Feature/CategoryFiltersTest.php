<?php

namespace Tests\Feature;

use App\Http\Livewire\IdeasIndex;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use Livewire\Livewire;

class CategoryFiltersTest extends TestCase
{
	use RefreshDatabase;

	/** @test*/
	public function selecting_category_filters_correctly()
	{
		$user = User::factory()->create();

		$categoryOne = Category::factory()->create(['name' => 'Category 1']);
		$categoryTwo = Category::factory()->create(['name' => 'Category 2']);

		$statusImplemented = Status::factory()->create(['id' => 4, 'name' => 'Implemented']);

		Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusImplemented->id,
			'description' => 'My first idea description',
		]);

		Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusImplemented->id,
			'description' => 'My first idea description',
		]);

		Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryTwo->id,
			'status_id'   => $statusImplemented->id,
			'description' => 'My first idea description',
		]);

		Livewire::test(IdeasIndex::class)
			->set('category', 'Category 1')
			->assertViewHas('ideas', function ($ideas) {
				return $ideas->count() == 2
					&& $ideas->first()->category->name === 'Category 1';
			});
	}

	/** @test*/
	public function the_category_query_string_filters_correctly()
	{
		$user = User::factory()->create();

		$categoryOne = Category::factory()->create(['name' => 'Category 1']);
		$categoryTwo = Category::factory()->create(['name' => 'Category 2']);

		$statusImplemented = Status::factory()->create(['id' => 4, 'name' => 'Implemented']);

		Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusImplemented->id,
			'description' => 'My first idea description',
		]);

		Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusImplemented->id,
			'description' => 'My first idea description',
		]);

		Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryTwo->id,
			'status_id'   => $statusImplemented->id,
			'description' => 'My first idea description',
		]);

		Livewire::withQueryParams(['category' => 'Category 1'])
			->test(IdeasIndex::class)
			->assertViewHas('ideas', function ($ideas) {
				return $ideas->count() == 2
					&& $ideas->first()->category->name === 'Category 1';
			});
	}

	/** @test*/
	public function selecting_a_status_and_a_category_filters_correctly()
	{
		$user = User::factory()->create();

		$categoryOne = Category::factory()->create(['name' => 'Category 1']);
		$categoryTwo = Category::factory()->create(['name' => 'Category 2']);

		$statusImplemented = Status::factory()->create(['name' => 'Implemented']);
		$statusConsidering = Status::factory()->create(['name' => 'Considering']);

		Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusImplemented->id,
			'description' => 'My first idea description',
		]);

		Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusConsidering->id,
			'description' => 'My first idea description',
		]);

		Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryTwo->id,
			'status_id'   => $statusImplemented->id,
			'description' => 'My first idea description',
		]);

		Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryTwo->id,
			'status_id'   => $statusConsidering->id,
			'description' => 'My first idea description',
		]);

		Livewire::test(IdeasIndex::class)
			->set('status', 'Implemented')
			->set('category', 'Category 1')
			->assertViewHas('ideas', function ($ideas) {
				return $ideas->count() == 1
					&& $ideas->first()->category->name === 'Category 1'
					&& $ideas->first()->status->name === 'Implemented';
			});
	}

	/** @test*/
	public function the_query_string_filters_correctly_with_status_and_category()
	{
		$user = User::factory()->create();

		$categoryOne = Category::factory()->create(['name' => 'Category 1']);
		$categoryTwo = Category::factory()->create(['name' => 'Category 2']);

		$statusImplemented = Status::factory()->create(['name' => 'Implemented']);
		$statusConsidering = Status::factory()->create(['name' => 'Considering']);

		Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusImplemented->id,
			'description' => 'My first idea description',
		]);

		Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusConsidering->id,
			'description' => 'My first idea description',
		]);

		Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryTwo->id,
			'status_id'   => $statusImplemented->id,
			'description' => 'My first idea description',
		]);

		Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryTwo->id,
			'status_id'   => $statusConsidering->id,
			'description' => 'My first idea description',
		]);

		Livewire::withQueryParams(['category' => 'Category 1', 'status' => 'Implemented'])
			->test(IdeasIndex::class)
			->assertViewHas('ideas', function ($ideas) {
				return $ideas->count() == 1
					&& $ideas->first()->category->name === 'Category 1'
					&& $ideas->first()->status->name === 'Implemented';
			});
	}

	/** @test*/
	public function selecting_all_categories_filters_correctly()
	{
		$user = User::factory()->create();

		$categoryOne = Category::factory()->create(['name' => 'Category 1']);
		$categoryTwo = Category::factory()->create(['name' => 'Category 2']);

		$statusImplemented = Status::factory()->create(['id' => 4, 'name' => 'Implemented']);

		Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusImplemented->id,
			'description' => 'My first idea description',
		]);

		Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusImplemented->id,
			'description' => 'My first idea description',
		]);

		Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryTwo->id,
			'status_id'   => $statusImplemented->id,
			'description' => 'My first idea description',
		]);

		Livewire::test(IdeasIndex::class)
			->set('category', 'All Categories')
			->assertViewHas('ideas', function ($ideas) {
				return $ideas->count() == 3;
			});
	}
}
