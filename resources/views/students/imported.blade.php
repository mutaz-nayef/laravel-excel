@extends('layouts.app')

@section('title', 'Students')

@push('scripts')
@endpush

@section('content')
<h1 class="section-title">Imported Students</h1>

<div class="imported-students-file">
    <table>

        <thead>
            <tr>
                <th>#</th>
                <th>File Name</th>
                <th>Imported Row Count</th>
                <th>Skipped Row Count</th>
                <th>Error Rows</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($imported as $import )
            <tr>
                <td>{{ $import->id}}</td>
                <td>{{ $import->filename }}</td>
                <td>{{ $import->imported_count }}</td>
                <td>{{ $import->skipped_count }}</td>
                <td>{{ $import->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    @if ($import->skipped_count > 0)
                    <a href="{{ route('students.failed-download', $import->id) }}">Download Failed</a>
                    @endif
                </td>
                <td>
                    Delete
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection