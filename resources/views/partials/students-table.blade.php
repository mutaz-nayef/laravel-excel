<table id="import_response" class="">
    <thead>
        <tr>
            <th>Name</th>
            <th>Gender</th>
            <th>Date Of Birth</th>
            <th>Email</th>
            <th>Phone</th>
            <th>City</th>
            <th>Address</th>
            <th>GPA</th>
            <th>Major</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($students as $student)
        <tr>
            <td>{{ $student->name }}</td>
            <td>{{ $student->gender }}</td>
            <td>{{ $student->date_of_birth }}</td>
            <td>{{ $student->email }}</td>
            <td>{{ $student->phone }}</td>
            <td>{{ $student->city }}</td>
            <td>{{ $student->address }}</td>
            <td>{{ $student->gpa }}</td>
            <td>{{ $student->major }}</td>
            <td>Edit</td>
        </tr>
        @endforeach

    </tbody>
</table>

<div>
    {!! $students->links('vendor.pagination.pagination') !!}
</div>