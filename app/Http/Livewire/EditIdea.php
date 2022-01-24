<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Idea;
use Illuminate\Http\Response;

class EditIdea extends Component
{
	public $idea;

	public $title;

	public $category;

	public $description;

    protected $rules = [
		'title'       => 'required|min:4',
		'category'    => 'required|integer|exists:categories,id',
		'description' => 'required|min:4',
	];

	public function mount(Idea $idea)
	{
		$this->idea = $idea;
		$this->title = $idea->title;
		$this->description = $idea->description;
		$this->category = $idea->category_id;
	}

    public function updateIdea()
    {
        if (auth()->guest() || auth()->user()->cannot('update', $this->idea)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $this->validate();

        $this->idea->update([
            'title' => $this->title,
            'description' => $this->description,
            'category_id' => $this->category,
        ]);

        $this->emit('ideaWasUpdated');
    }

	public function render()
	{
		return view('livewire.edit-idea', [
			'categories' => Category::all(),
		]);
	}
}
