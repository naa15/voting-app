<x-modal-confirm 
    livewire-event-to-open-modal="markAsNotSpamCommentWasSet"
    event-to-close-modal="commentWasMarkedAsNotSpam"
    modal-title="Reset the Spam Counter"
    modal-description="Are you sure you want to reset the spam counter for this comment?"
    modal-confirm-button-text="Reset the Spam Counter"
    wire-click="markAsNotSpam"
/>