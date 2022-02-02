<?php

namespace App\Http\Livewire;

use App\Jobs\NotifyAllVoters;
use App\Mail\IdeaStatusUpdatedMailable;
use App\Models\Comment;
use Livewire\Component;
use App\Models\Idea;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Response;

class SetStatus extends Component
{
	public $idea;

	public $status;

	public $comment;

	public $notifyAllVoters;

	public function mount(Idea $idea)
	{
		$this->idea = $idea;
		$this->status = $this->idea->status_id;
	}

	public function setStatus()
	{
		if (!auth()->check() || !auth()->user()->isAdmin())
		{
			abort(Response::HTTP_FORBIDDEN);
		}

		$this->idea->status_id = $this->status;
		$this->idea->save();

		if ($this->notifyAllVoters)
		{
			NotifyAllVoters::dispatch($this->idea);
		}

		Comment::create([
			'user_id'   => auth()->id(),
			'idea_id'   => $this->idea->id,
			'body'      => $this->comment ?? 'No comment was added.',
			'status_id' => $this->status,
			'is_status_update' => true,
		]);

		$this->reset('comment');
		
		$this->emit('statusWasUpdated', 'Status was updated successfully!');
	}

	public function notifyAllVoters()
	{
		$this->idea->votes()
			->select('name', 'email')
			->chunk(100, function ($voters) {
				foreach ($voters as $user)
				{
					Mail::to($user)
						->queue(new IdeaStatusUpdatedMailable($this->idea));
				}
			});
	}

	public function render()
	{
		return view('livewire.set-status');
	}
}
