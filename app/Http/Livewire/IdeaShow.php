<?php

namespace App\Http\Livewire;

use App\Exceptions\DuplicateVoteException;
use App\Exceptions\VoteNotFoundException;
use Livewire\Component;
use App\Models\Idea;

class IdeaShow extends Component
{
	public $idea;

	public $votesCount;

	public $hasVoted;

	public function mount(Idea $idea, $votesCount)
	{
		$this->idea = $idea;
		$this->votesCount = $votesCount;
		$this->hasVoted = $idea->isVotedByUser(auth()->user());
	}

	public function vote()
	{
		if (!auth()->check())
		{
			return redirect(route('login'));
		}

		if ($this->hasVoted)
		{
			try
			{
				$this->idea->removeVote(auth()->user());
			}
			catch (VoteNotFoundException $e)
			{
				//do nothing
			}
			$this->hasVoted = false;
			$this->votesCount--;
		}
		else
		{
			try
			{
				$this->idea->vote(auth()->user());
			}
			catch (DuplicateVoteException $e)
			{
				//do nothing
			}
			$this->hasVoted = true;
			$this->votesCount++;
		}
	}

	public function render()
	{
		return view('livewire.idea-show');
	}
}
