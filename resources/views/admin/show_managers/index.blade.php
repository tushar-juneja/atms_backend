@extends('layouts.admin')

@section('content')
    <div class="container-fluid table-responsive">
        <h2>Show Managers</h2>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if($showManagers->isEmpty())
                    <tr>
                        <td colspan="4" class="text-center">No show managers found.</td>
                    </tr>
                @else
                @foreach($showManagers as $showManager)
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
@endsection