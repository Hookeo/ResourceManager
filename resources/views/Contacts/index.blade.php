@extends('layouts.dataTables')

@section('content')
    <h1 class="text-center">All Contacts</h1>

    <div class="container">
        <!-- create a new contact (uses the create method found at GET /contact/create -->
        <a class="btn btn-small btn-primary pull-right" href="{{ URL::to('contacts/create') }}" style="margin-bottom: 20px;">Create New Contact</a>
        <div class="row">
            <table class="table table-striped table-bordered" id="ContactTable">
                <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Resources</th>
                    <th>View</th>
                </tr>
                </thead>
                <tbody>
                @foreach($contacts as $key => $contact)
                    <?php $link = false; ?>
                    <tr>
                        <td>{{ $contact->firstName }}</td>
                        <td>{{ $contact->lastName }}</td>
                        <td>{{ $contact->email }}</td>
                        <td>{{ $contact->phoneNumber }}</td>
                        <td>
                            @foreach ($contact->resources as $resource)
                                {{ $resource->name }}
                            @endforeach
                        </td>
                        <td class="text-center">

                            <!-- show the contact (uses the show method found at GET /contacts/{id} -->
                            <a class="btn btn-small btn-success" href="{{ URL::to('contacts/' . $contact->id) }}">View</a>

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
        $('#ContactTable').DataTable();
    });
</script>
@endpush