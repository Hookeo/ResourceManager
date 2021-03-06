@extends('layouts.general')

@section('content')
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-primary">
                <div class="panel-heading">View Flag</div>
                <div class="panel-body">
                    <div class="col-md-offset-2"><br/><br/>
                        <div>
                            <a href="{{'/flags'}}">Back to Flags</a></br></br>
                        </div>
                    </div>
                    <dl class="dl-horizontal">

                        @if(isset($flag->resource))
                            <dt>Resource Name</dt>
                            <dd>{{ $flag->resource->Name }}</dd>
                        @elseif(isset($flag->contact))
                            <dt>Contact Name</dt>
                            <dd>{{ $flag->contact->full_name }}</dd>
                        @elseif(isset($flag->user))
                            <dt>User Email</dt>
                            <dd>{{ $flag->user->email }}</dd>
                        @elseif(isset($flag->provider))
                            <dt>Provider</dt>
                            <dd>{{ $flag->provider->name }}</dd>
                        @elseif(isset($flag->event))
                            <dt>Event Name</dt>
                            <dd>{{ $flag->event->name }}</dd>
                        @endif
                        <dt>Submitted By</dt>
                        <dd>{{ $flag->submitter->name }}</dd>
                        <dt>Level</dt>
                        <dd>{{ $flag->level }}</dd>
                        <dt>Submitted At</dt>
                        <dd>{{ $flag->created_at }}</dd>
                            <dt>Status</dt>
                            @if($flag->resolved)
                                <dd>Resolved</dd>
                            @else
                                <dd>Unresolved</dd>
                            @endif

                        <dt>Description of Issue</dt>
                        <dd>{{ $flag->comments }}</dd>
                    </dl>
                    <div class="col-md-offset-3">
                        <br/>
                        @if (Auth::user()->role == 'GA' || Auth::user()->role == 'Admin')
                            <br>
                            <br>
                            <!-- edit this event (uses the edit method found at GET /event/edit/{id} -->
                            <a class="btn btn-lg btn-info" href="{{ URL::to('archive_flags/showrestore/' . $flag->id) }}">Restore</a>
                            <a class="btn btn-lg btn-danger" href="{{ URL::to('archive_flags') }}">Cancel</a>
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

    <!-- Delete Modal -->
    @include('Flags._deleteModal')

    @if(!$flag->resolved)
        <!-- Resolve Modal -->
        @include('flags._resolveModal')
    @endif
@endsection