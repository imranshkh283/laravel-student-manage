@extends('layouts.admin')

@section('main-content')

<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">{{ __('U have completed all the questions') }}</h1>

@if (session('success'))
<div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if (session('status'))
<div class="alert alert-success border-left-success" role="alert">
    {{ session('status') }}
</div>
@endif

<div class="row">
    <div class="container">
        <!-- Question Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Question</h6>
            </div>

            <!-- Start Loop for Questions -->
            <form action="{{ route('admin.submit_answer') }}" method="POST">
                @csrf
                @foreach($questions as $question)
                <div class="card-body">
                    <!-- Question Text -->
                    <div class="mb-4">
                        <h5 class="font-weight-bold">{{ $question['question'] }}</h5>
                        <p><small class="text-muted">Category: {{ $question['category'] }} | Difficulty: {{ $question['difficulty'] }}</small></p>
                    </div>

                    <!-- Answer Choices -->
                    @foreach($question['answers'] as $key => $answer)
                    @if($answer) <!-- Only display non-null answers -->
                    <div class="form-check">
                        <input class="form-check-input"
                            type="radio" name="answer[{{ $question['id'] }}]"
                            id="answer_{{ $question['id'] }}_{{ $key }}"
                            value="{{ $key }}">
                        <label class="form-check-label" for="{{ $key }}">
                            {{ $answer }}
                        </label>
                    </div>
                    @endif
                    @endforeach
                </div>
                @endforeach
                <!-- End Loop for Questions -->

                <!-- Submit Button (once at the bottom of the form) -->
                <div class="card-body">
                    <div class="mt-4 text-center">
                        <button type="submit" class="btn btn-primary" name="submit">Submit Answer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection