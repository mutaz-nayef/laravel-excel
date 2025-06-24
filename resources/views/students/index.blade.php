@extends('layouts.app')

@section('title', 'Students')

@push('scripts')
<script type="module" src="{{ asset('js/importStudent.js') }}"></script>
@endpush

@section('content')
<h1 class="section-title">Students</h1>

<div class="">
    <a class="primary-link" href="{{route('students.create')}}" data-ajax-link>Import</a>
    <a class="secondary-link" href="{{route('students.export')}}">Export</a>
</div>

@include('partials.students-table', ['students' => $students])

@endsection