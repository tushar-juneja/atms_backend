@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="container-fluid mt-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Shows</h2>
                    <a href="{{ route('admin.shows.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i>
                        Create</a>
                </div>
            </div>

            <div class="container-fluid table-responsive">


                <table class="table table-striped table-hover mt-3">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Status</th>
                            <th>Name</th>
                            <th>Date Time</th>
                            <th>Performer</th>
                            <th>Show Manager</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($shows->isEmpty())
                            <tr>
                                <td colspan="7" class="text-center">No shows found.</td>
                            </tr>
                        @else
                            @foreach ($shows as $show)
                                <tr>
                                    <td>{{ $show->id }}</td>
                                    <td>{!! $show->published ? "<span class='text-success fw-bold'>Published</span>" : "<span class='text-danger fw-bold'>Unpublished</span>" !!}</td>
                                    <td>{{ $show->name }}</td>
                                    <td>{{ $show->date_time }}</td>
                                    <td>{{ $show->artist }}</td>
                                    <td>{{ $show->showManager->name }}</td>
                                    <td>
                                        <a href="{{ route('admin.shows.edit', ['id' => $show->id]) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="#" class="btn btn-sm btn-danger">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
