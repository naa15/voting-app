<?php

namespace Tests\Feature;

use App\Http\Livewire\StatusFilters;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Idea;
use App\Models\Category;
use App\Models\Status;

class StatusFiltersTest extends TestCase
{
	use RefreshDatabase;

	/** @test*/
	public function index_page_contains_status_filters_livewire_component()
	{
		$user = User::factory()->create();

		$categoryOne = Category::factory()->create(['name' => 'Category 1']);

		$statusOpen = Status::factory()->create(['name' => 'Open']);

		Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusOpen->id,
			'description' => 'My first idea description',
		]);

		$this->get(route('idea.index'))
			->assertSeeLivewire('status-filters');
	}

	/** @test*/
	public function show_page_contains_status_filters_livewire_component()
	{
		$user = User::factory()->create();

		$categoryOne = Category::factory()->create(['name' => 'Category 1']);

		$statusOpen = Status::factory()->create(['name' => 'Open']);

		$idea = Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusOpen->id,
			'description' => 'My first idea description',
		]);

		$this->get(route('idea.show', $idea))
				->assertSeeLivewire('status-filters');
	}

	/** @test*/
	public function shows_correct_status_count()
	{
		$user = User::factory()->create();

		$categoryOne = Category::factory()->create(['name' => 'Category 1']);

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

		Livewire::test(StatusFilters::class)
			->assertSee('All Ideas (2)')
			->assertSee('Implemented (2)');
	}

	/** @test*/
	public function filtering_works_when_query_string_in_place()
	{
		$user = User::factory()->create();

		$categoryOne = Category::factory()->create(['name' => 'Category 1']);

		$statusOpen = Status::factory()->create(['name' => 'Open']);
		$statusConsidering = Status::factory()->create(['name' => 'Considering', 'class' => 'bg-purple text-white']);
		$statusInProgress = Status::factory()->create(['name' => 'In Progress', 'class' => 'bg-yellow text-white']);
		$statusImplemented = Status::factory()->create(['name' => 'Implemented']);
		$statusClosed = Status::factory()->create(['name' => 'Closed']);

		Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusConsidering->id,
			'description' => 'My first idea description',
		]);

		Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My Second Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusConsidering->id,
			'description' => 'My second idea description',
		]);

		Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My Third Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusInProgress->id,
			'description' => 'My first idea description',
		]);

		Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My fourth Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusInProgress->id,
			'description' => 'My first idea description',
		]);

		Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My fifth Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusInProgress->id,
			'description' => 'My first idea description',
		]);

		$response = $this->get(route('idea.index', ['status' => 'In Progress']));
		$response->assertSuccessful();
		$response->assertDontSee('<div class="bg-purple text-white text-xxs font-bold uppercase leading-none rounded-full text-center w-28 h-7 py-2 px-4">Considering</div>', false);
		$response->assertSee('<div class="bg-yellow text-white text-xxs font-bold uppercase leading-none rounded-full text-center w-28 h-7 py-2 px-4">In Progress</div>', false);
	}

	/** @test*/
	public function show_page_does_not_show_selected_status()
	{
		$user = User::factory()->create();

		$categoryOne = Category::factory()->create(['name' => 'Category 1']);

		$statusImplemented = Status::factory()->create(['id' => 4, 'name' => 'Implemented']);

		Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusImplemented->id,
			'description' => 'My first idea description',
		]);

		$idea = Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusImplemented->id,
			'description' => 'My first idea description',
		]);

		$response = $this->get(route('idea.show', $idea));

		$response->assertDontSee('border-blue text-gray-900');
	}

	/** @test*/
	public function index_page_does_shows_selected_status()
	{
		$user = User::factory()->create();

		$categoryOne = Category::factory()->create(['name' => 'Category 1']);

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

		$response = $this->get(route('idea.index'));

		$response->assertSee('border-blue text-gray-900');
	}
}
