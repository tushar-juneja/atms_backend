@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="container-fluid mt-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Show Manager</h2>
                    <a href="{{ route('admin.show_managers.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i>
                        Create</a>
                </div>
            </div>

            <div class="container-fluid table-responsive">


                <table class="table table-striped table-hover mt-3">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($showManagers->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center">No show managers found.</td>
                            </tr>
                        @else
                            @foreach ($showManagers as $showManager)
                                <tr>
                                    <td>{{ $showManager->id }}</td>
                                    <td>{{ $showManager->name }}</td>
                                    <td>{{ $showManager->email }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">Edit</a>
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
