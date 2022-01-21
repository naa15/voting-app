<?php

namespace Tests\Feature\Filters;

use App\Http\Livewire\IdeasIndex;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Idea;
use Livewire\Livewire;

class SearchFilterTest extends TestCase
{
	use RefreshDatabase;

	/** @test*/
	public function search_works_for_more_than_3_characters()
	{
		Idea::factory()->create([
			'title'	=> 'My First Idea',
		]);

		Idea::factory()->create([
			'title' => 'My Second Idea',
		]);

		Idea::factory()->create([
			'title'	=> 'My Third Idea',
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
		Idea::factory()->create([
			'title' => 'My First Idea',
		]);

		Idea::factory()->create([
			'title' => 'My Second Idea',
		]);

		Idea::factory()->create([
			'title' => 'My Third Idea',
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
		$categoryOne = Category::factory()->create(['name' => 'Category 1']);
		$categoryTwo = Category::factory()->create(['name' => 'Category 2']);

		Idea::factory()->create([
			'title'       => 'My First Idea',
			'category_id' => $categoryOne->id,
		]);

		Idea::factory()->create([
			'title'       => 'My Second Idea',
			'category_id' => $categoryOne->id,
		]);

		Idea::factory()->create([
			'title'       => 'My Third Idea',
			'category_id' => $categoryTwo->id,
		]);

		Livewire::test(IdeasIndex::class)
			->set('category', 'Category 1')
			->set('search', 'Idea')
			->assertViewHas('ideas', function ($ideas) {
				return $ideas->count() == 2;
			});
	}
}
