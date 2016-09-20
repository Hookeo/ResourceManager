@extends('layouts.dataTables')

@section('content')
    <h1 class="text-center">All Users</h1>

    <div class="container">
        <!-- create a new user (uses the create method found at GET /contact/create -->
        <a class="btn btn-small btn-primary pull-right" href="{{ URL::to('users/create') }}" style="margin-bottom: 20px;">Create New User</a>
        <div class="row">
            <table class="display dt-responsive nowrap" cellspacing="0" width="100%" id="UsersTable">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>User Since</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $key => $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td class="text-center">

                            <!-- show the user (uses the show method found at GET /users/{id} -->
                            <a class="btn btn-small btn-success" href="{{ URL::to('users/' . $user->id) }}">View</a>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>

@stop
@push('scripts')
<script>
    $(document).ready(function() {
        $('#UsersTable').DataTable();
    });
</script>
@endpush