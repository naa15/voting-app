<?php

namespace Tests\Feature;

use App\Http\Livewire\SetStatus;
use App\Jobs\NotifyAllVoters;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Status;
use App\Models\Idea;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;

class AdminSetStatusTest extends TestCase
{
	use RefreshDatabase;

	/** @test*/
	public function show_page_contains_set_status_livewire_component_when_user_is_admin()
	{
		$user = User::factory()->admin()->create();

		$idea = Idea::factory()->create();

		/** @var mixed $user */
		$this->actingAs($user)
			->get(route('idea.show', $idea))
			->assertSeeLivewire('set-status');
	}

	/** @test*/
	public function show_page_does_not_contain_set_status_livewire_component_when_user_is_not_admin()
	{
		$user = User::factory()->create([
			'email' => 'user@example.com',
		]);

		$idea = Idea::factory()->create();

		/** @var mixed $user */
		$this->actingAs($user)
			->get(route('idea.show', $idea))
			->assertDontSeeLivewire('set-status');
	}

	/** @test*/
	public function initial_status_is_set_correctly()
	{
		$user = User::factory()->admin()->create();

		$statusImplemented = Status::factory()->create(['id' => 4, 'name' => 'Implemented']);

		$idea = Idea::factory()->create([
			'status_id'   => $statusImplemented->id,
		]);

		Livewire::actingAs($user)
			->test(SetStatus::class, [
				'idea' => $idea,
			])
			->assertSet('status', $statusImplemented->id);
	}

	/** @test*/
	public function can_set_status_correctly()
	{
		$user = User::factory()->admin()->create();

		$statusConsidering = Status::factory()->create(['id' => 2, 'name' => 'Considering']);
		$statusImplemented = Status::factory()->create(['id' => 4, 'name' => 'Implemented']);

		$idea = Idea::factory()->create([
			'status_id'   => $statusConsidering->id,
		]);

		Livewire::actingAs($user)
			->test(SetStatus::class, [
				'idea' => $idea,
			])
			->set('status', $statusImplemented->id)
			->call('setStatus')
			->assertEmitted('statusWasUpdated');

		$this->assertDatabaseHas('ideas', [
			'id'        => $idea->id,
			'status_id' => $statusImplemented->id,
		]);
	}

	/** @test*/
	public function can_set_status_correctly_while_notifying_all_voters()
	{
		$user = User::factory()->admin()->create();

		$statusConsidering = Status::factory()->create(['id' => 2, 'name' => 'Considering']);
		$statusImplemented = Status::factory()->create(['id' => 4, 'name' => 'Implemented']);

		$idea = Idea::factory()->create([
			'status_id'   => $statusConsidering->id,
		]);

		Queue::fake();

		Queue::assertNothingPushed();

		Livewire::actingAs($user)
			->test(SetStatus::class, [
				'idea' => $idea,
			])
			->set('status', $statusImplemented->id)
			->set('notifyAllVoters', true)
			->call('setStatus')
			->assertEmitted('statusWasUpdated');

		Queue::assertPushed(NotifyAllVoters::class);
	}
}
