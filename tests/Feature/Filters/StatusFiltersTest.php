<?php

namespace Tests\Feature\Filters;

use App\Http\Livewire\StatusFilters;
use Livewire\Livewire;
use App\Http\Livewire\IdeasIndex;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Idea;
use App\Models\Status;

class StatusFiltersTest extends TestCase
{
	use RefreshDatabase;

	/** @test*/
	public function index_page_contains_status_filters_livewire_component()
	{
		Idea::factory()->create();

		$this->get(route('idea.index'))
			->assertSeeLivewire('status-filters');
	}

	/** @test*/
	public function show_page_contains_status_filters_livewire_component()
	{
		$idea = Idea::factory()->create();

		$this->get(route('idea.show', $idea))
				->assertSeeLivewire('status-filters');
	}

	/** @test*/
	public function shows_correct_status_count()
	{
		$statusImplemented = Status::factory()->create(['id' => 4, 'name' => 'Implemented']);

		Idea::factory()->create(['status_id'=> $statusImplemented->id]);

		Idea::factory()->create(['status_id' => $statusImplemented->id]);

		Livewire::test(StatusFilters::class)
			->assertSee('All Ideas (2)')
			->assertSee('Implemented (2)');
	}

	/** @test*/
	public function filtering_works_when_query_string_in_place()
	{
		$statusConsidering = Status::factory()->create(['name' => 'Considering']);
		$statusInProgress = Status::factory()->create(['name' => 'In Progress']);

		Idea::factory()->create(['status_id' => $statusConsidering->id]);
		Idea::factory()->create(['status_id' => $statusConsidering->id]);

		Idea::factory()->create(['status_id' => $statusInProgress->id]);
		Idea::factory()->create(['status_id' => $statusInProgress->id]);
		Idea::factory()->create(['status_id' => $statusInProgress->id]);

		Livewire::withQueryParams(['status' => 'Considering'])
			->test(IdeasIndex::class)
			->assertViewHas('ideas', function ($ideas) {
				return $ideas->count() == 2
					&& $ideas->first()->status->name === 'Considering';
			});
	}

	/** @test*/
	public function show_page_does_not_show_selected_status()
	{
		$idea = Idea::factory()->create();

		$response = $this->get(route('idea.show', $idea));

		$response->assertDontSee('border-blue text-gray-900');
	}

	/** @test*/
	public function index_page_does_shows_selected_status()
	{
		$response = $this->get(route('idea.index'));

		$response->assertSee('border-blue text-gray-900');
	}
}
