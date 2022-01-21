<?php

namespace Tests\Unit\Jobs;

use App\Jobs\NotifyAllVoters;
use App\Mail\IdeaStatusUpdatedMailable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Status;
use App\Models\Category;
use App\Models\Idea;
use App\Models\Vote;
use Illuminate\Support\Facades\Mail;

class NotifyAllVotersTest extends TestCase
{
	use RefreshDatabase;

	/** @test*/
	public function it_sends_an_email_to_all_voters()
	{
		$user = User::factory()->create([
			'email' => 'gelatavadze@example.com',
		]);

		$userB = User::factory()->create([
			'email' => 'kotiko@example.com',
		]);

		$statusConsidering = Status::factory()->create(['id' => 2, 'name' => 'Considering']);

		$idea = Idea::factory()->create([
			'status_id'   => $statusConsidering->id,
		]);

		Vote::create([
			'idea_id' => $idea->id,
			'user_id' => $user->id,
		]);

		Vote::create([
			'idea_id' => $idea->id,
			'user_id' => $userB->id,
		]);

		Mail::fake();

		NotifyAllVoters::dispatch($idea);

		Mail::assertQueued(IdeaStatusUpdatedMailable::class, function ($mail) {
			return $mail->hasTo('gelatavadze@example.com')
				&& $mail->build()->subject === 'An idea you voted for has a new status';
		});

		Mail::assertQueued(IdeaStatusUpdatedMailable::class, function ($mail) {
			return $mail->hasTo('kotiko@example.com')
				&& $mail->build()->subject === 'An idea you voted for has a new status';
		});
	}
}
