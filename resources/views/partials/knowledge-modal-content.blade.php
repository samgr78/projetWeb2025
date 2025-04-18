{{-- content to inject into modals--}}

@foreach($questions as $question)
    <div class="questionDiv border-b p-2 text-1.5xl">
        <p>{{ $question->question }}</p>
    </div>
    @foreach($answers->where('question_id', $question->id) as $answer)
        <p>{{ $answer->answer }}:
            <input type="checkbox" name="answerKnowledge[]" value="{{ $answer->id }}" class="answerCheck">
        </p>
    @endforeach
@endforeach

<x-forms.primary-button>
    {{ __('Terminer le qcm') }}
</x-forms.primary-button>
