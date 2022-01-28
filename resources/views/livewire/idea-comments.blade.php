<div>
    @if ($comments->isNotEmpty())
        <div class="comments-container relative space-y-6 md:ml-22 pt-4 my-8 mt-1">
            @forelse ($comments as $comment)
                <livewire:idea-comment :key="$comment->id" :comment="$comment" />
            @endforeach
        </div>
        <!--- end comments-container --->
    @else
        <div class="mx-auto w-70 mt-2">
            <img src="{{ asset('img/no-ideas.svg') }}" alt="No Ideas" class="mx-auto mix-blend-luminosity">
            <div class="text-gray-400 text-center font-bold mt-6">No ideas found...</div>
        </div>
    @endif
</div>
