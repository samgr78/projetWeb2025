@foreach($questions as $question)
    <p>{{ $question->question }}</p>
    @foreach($answers->where('question_id', $question->id) as $answer)
        <p>{{ $answer->answer }}:
            <input type="checkbox" name="answerKnowledge[]" value="{{ $answer->id }}" class="answerCheck">
        </p>
    @endforeach
@endforeach

<x-forms.primary-button>
    {{ __('Terminer le qcm') }}
</x-forms.primary-button>
