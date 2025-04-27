@extends('layouts.admin')

@section('main-content')

<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">{{ __('Import Student using CSV file') }}</h1>

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

    <div class="col-lg-12 order-lg-1">

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <a href="{{ Route('student.csvSample') }}" class="btn btn-primary btn-icon-split">
                    <span class="text">Download CSV Sample File</span>
                </a>
            </div>
            <div class="card-body">

                <form method="POST" action="{{ route('student.csvImport') }}" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group focused">
                                    <label class="form-control-label" for="name">Upload CSV File<span class="small text-danger">*</span></label>
                                    <input type="file" id="student_import" class="form-control" name="student_import">
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Button -->
                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col text-start">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>

        </div>

    </div>

</div>

@endsection