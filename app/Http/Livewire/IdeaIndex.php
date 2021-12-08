<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Idea;

class IdeaIndex extends Component
{
    public $idea;
    public $votesCount;
    public $hasVoted;

    public function mount(Idea $idea, $votesCount)
    {
        $this->idea = $idea;
        $this->hasVoted = $idea->voted_by_user;
    }
    
    public function vote()
    {
        if (!auth()->check()) {
            return redirect(route('login'));
        }

        if ($this->hasVoted) {
            $this->idea->removeVote(auth()->user());
            $this->hasVoted = false;
            $this->votesCount--;
        } else {
            $this->idea->vote(auth()->user());
            $this->hasVoted = true;
            $this->votesCount++;
        }
    }

    public function render()
    {
        return view('livewire.idea-index');
    }
}
