@extends('layouts.admin')

@section('main-content')

<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">{{ __('Generate Api Config') }}</h1>

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

<div class="d-flex justify-content-center align-items-center" style="height: 100vh;">

    <form>
        @csrf
        <button id="start-exam-form" type="button" class="btn btn-lg btn-success">Start Exam</button>
    </form>

    <div id="loading-wrapper" class="d-none justify-content-center align-items-center" style="height: 100vh;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden"></span>
        </div>
    </div>

</div>

@endsection