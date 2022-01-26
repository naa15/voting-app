<x-modal-confirm 
    event-to-open-modal="mark-idea-as-spam-modal"
    event-to-close-modal="ideaWasMarkedAsSpam"
    modal-title="Mark as Spam"
    modal-description="Are you sure you want to mark this idea as spam?"
    modal-confirm-button-text="Mark as Spam"
    wire-click="markAsSpam"
/>