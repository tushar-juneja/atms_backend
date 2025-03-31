@extends('layouts.admin')

@section('content')
    <div class="card container pt-2 pb-5">
        <div class="card-body">
            <div class="container mt-4">
                <h2>Update Show Manager</h2>

                @include('admin.show_managers.form', ['edit' => true])

            </div>
        </div>
    </div>
@endsection
