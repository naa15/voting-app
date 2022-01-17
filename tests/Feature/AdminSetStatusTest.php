<?php

namespace Tests\Feature;

use App\Http\Livewire\SetStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Status;
use App\Models\Category;
use App\Models\Idea;
use Livewire\Livewire;

class AdminSetStatusTest extends TestCase
{
	use RefreshDatabase;

	/** @test*/
	public function show_page_contains_set_status_livewire_component_when_user_is_admin()
	{
		$user = User::factory()->create([
			'email' => 'gelatavadze@example.com',
		]);

		$categoryOne = Category::factory()->create(['name' => 'Category 1']);

		$statusImplemented = Status::factory()->create(['id' => 4, 'name' => 'Implemented']);

		$idea = Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusImplemented->id,
			'description' => 'My first idea description',
		]);

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

		$categoryOne = Category::factory()->create(['name' => 'Category 1']);

		$statusImplemented = Status::factory()->create(['id' => 4, 'name' => 'Implemented']);

		$idea = Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusImplemented->id,
			'description' => 'My first idea description',
		]);

		$this->actingAs($user)
			->get(route('idea.show', $idea))
			->assertDontSeeLivewire('set-status');
	}

	/** @test*/
	public function initial_status_is_set_correctly()
	{
		$user = User::factory()->create([
			'email' => 'gelatavadze@example.com',
		]);

		$categoryOne = Category::factory()->create(['name' => 'Category 1']);
		$categoryTwo = Category::factory()->create(['name' => 'Category 2']);

		$statusConsidering = Status::factory()->create(['id' => 2, 'name' => 'Considering']);
		$statusImplemented = Status::factory()->create(['id' => 4, 'name' => 'Implemented']);

		$idea = Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusImplemented->id,
			'description' => 'My first idea description',
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
		$user = User::factory()->create([
			'email' => 'gelatavadze@example.com',
		]);

		$categoryOne = Category::factory()->create(['name' => 'Category 1']);
		$categoryTwo = Category::factory()->create(['name' => 'Category 2']);

		$statusConsidering = Status::factory()->create(['id' => 2, 'name' => 'Considering']);
		$statusImplemented = Status::factory()->create(['id' => 4, 'name' => 'Implemented']);

		$idea = Idea::factory()->create([
			'user_id'     => $user->id,
			'title'       => 'My First Idea',
			'category_id' => $categoryOne->id,
			'status_id'   => $statusConsidering->id,
			'description' => 'My first idea description',
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
}
