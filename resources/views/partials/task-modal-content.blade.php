<div>
    <div class="modal-header">
        {{ $task->title }}
    </div>
    <div class="modal-body">
        {{ $task->description }}
    </div>

    <form method="POST" action="{{ route('commonLifeCheckStudent.check', $task->id) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="idStudent" value="{{ auth()->user()->id }}">
        <input type="hidden" name="isCompleted" value="1">

        <x-forms.input label="Commentaire" name="studentCommentTask" type="text" :placeholder="__('Facultatif')" />
        <x-forms.input label="Date de la finalisation de la tache" name="studentDateTask" type="date" />

        <x-forms.primary-button>
            Enregistrer
        </x-forms.primary-button>
    </form>
</div>
