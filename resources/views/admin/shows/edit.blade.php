@extends('layouts.admin')

@section('content')
    <div class="card container pt-2 pb-5">
        <div class="card-body">
            <div class="container mt-4">
                <h2>Update Show</h2>

                @include('admin.shows.form', ['edit' => true])

            </div>
        </div>
    </div>
@endsection
