@extends('layouts.general')

@section('content')
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-primary">
                <div class="panel-heading">View User</div>
                <div class="panel-body">
                    
                    <dl class="dl-horizontal">
                        <dt>Name</dt>
                        <dd>{{ $user->name }}</dd>
                        <dt>Email</dt>
                        <dd>{{ $user->email }}</dd>
                        <dt>Role</dt>
                        <dd>{{ $user->role }}</dd>
                        <dt>Reported Problems</dt>
                        @if(isset($user->flags))
                            @foreach($user->flags as $flag)
                                @if(!$flag->resolved)
                                    <dd>{{ $flag->comments }}</dd>
                                @endif
                            @endforeach
                        @else
                            <dd>No problems reported</dd>
                        @endif
                    </dl>
                    <div class="col-md-offset-2">
                        <br/>
                        <br/>
                        <!-- Flag this user as incorrect -->
                        <a class="btn btn-lg btn-link" href="{{ URL::to('users/' . $user->id. '/flag') }}">Report a problem with this user.</a>
                        <br/>
                        <br/>
                        <div class="col-md-offset-2">
                        @if (Auth::user()->role == 'GA' || Auth::user()->role == 'Admin')

                            <!-- edit this event (uses the edit method found at GET /event/edit/{id} -->
                                <a class="btn btn-lg btn-info" href="{{ URL::to('archive_users/showrestore/' . $user->id) }}">Restore</a>
                                <a class="btn btn-lg btn-danger" href="{{ URL::to('archive_users') }}">Cancel</a>
                                <!-- delete the event -->
                                <!-- Trigger the modal with a button -->
                            @endif
                        <br/>
                        <br/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    @include('users._deleteModal')
@endsection