@extends('layouts.dataTables')
<style>
    .addReport {
        color: #ffffff !important;
        background-color: #041E42 !important;
        border: 2px solid #FFC72C !important;

    }

    .removeReport {
        color: #041E42 !important;
        background-color: #FFC72C !important;
        border: 2px solid #041E42 !important;
        font-weight: bold !important;
    }

    @media only screen and (min-width: 768px) {


        .removeReport:hover, .removeReport:active {
            color: #ffffff !important;
            background-color: #041E42 !important;
            border: 2px solid #FFC72C !important;
        }
        .addReport:hover, .addReport:active {
            color: #041E42 !important;
            background-color: #FFC72C !important;
            border-color: #041E42 !important;
            font-weight: bold !important;
        }
    }
    @media only screen and (max-width: 767px) {
        .removeReport:active {
            color: #ffffff !important;
            background-color: #041E42 !important;
            border: 2px solid #FFC72C !important;
        }
        .addReport:active {
            color: #041E42 !important;
            background-color: #FFC72C !important;
            border-color: #041E42 !important;
            font-weight: bold !important;
        }
        .removeReport:hover {
            color: #041E42 !important;
            background-color: #FFC72C !important;
            border-color: #041E42 !important;
            font-weight: bold !important;
        }
        .addReport:hover {
            color: #ffffff !important;
            background-color: #041E42 !important;
            border: 2px solid #FFC72C !important;
        }
    }</style>
@section('content')
    <div class="text-center"><h1 class="page-header">All Resources</h1></div>
    <div id="successOrFailure"></div>
    @if (Auth::user()->role->create_update == '1')
        <!-- create a new resource (uses the create method found at GET /resource/create -->
        <a class="btn btn-md btn-primary pull-right" href="{{ URL::to('resources/create') }}" style="margin-bottom: 20px;">Create New Resource</a>
        @endif
        <br>
        <br>
        <div>
            <table style="display:none;" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" id="ResourceTable">
                <thead>

                    <tr>
                        <!-- class all for always show, lower data priority numbers stay longer-->
                        <th class="all" >Name</th> {{--0--}}
                        <th data-priority="1">County</th> {{--1--}}
                        <th data-priority="1">Category</th> {{--2--}}
                        <th data-priority="2">Hours of Operation</th> {{--3--}}
                        <th data-priority="2">Phone</th> {{--4--}}
                        <th data-priority="2">Email</th> {{--5--}}
                        <th data-priority="2">Website</th> {{--6--}}
                        <th data-priority="3">Street Address</th> {{--7--}}
                        <th data-priority="4"></th> {{--7--}}
                        <th data-priority="2">City</th> {{--8--}}
                        <th data-priority="1">State</th> {{--9--}}
                        <th data-priority="2">Zip Code</th> {{--10--}}
                        <th data-priority="3">Description</th> {{--11 Formerly 12--}}
                        <th data-priority="3">Comments</th> {{--12--}}
                        <th class="all">Action</th> {{--13--}}
                        <th data-priority="4">View Resource:</th>{{--14--}}
                    </tr>
                </thead>
                <tfoot>

                    <tr>
                        <th><label>Name:&nbsp;&nbsp; </label> </th>
                        <th><label>County:&nbsp;&nbsp;</label> </th>
                        <th><label>Category:&nbsp;&nbsp; </label></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th><label>State:&nbsp;&nbsp; </label></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
                <tbody>
                @foreach($resources as $key => $resource)
                    <?php
                        $link = false;
                    ?>
                    <tr>
                        <td><div width="50%"><span class="wrapcell">{{ $resource->name }}
                        <?php
                            $count = 0;
                            foreach ($resource->flags as $key=>$value) {
                                if ($value ['resolved'] == '0') {
                                    $count++;
                                }
                            }
                            if($count != 0)
                            {
                            ?>
                                <a class="btn btn-xs btn-danger" style="border-radius: 12px;" href="{{ URL::to('resources/' . $resource->id) }}">{{ $count }}</a>
                            <?php
                            }
                        ?>
                       </span></div></td>
                        <td class="alignRight">{{ $resource->county }}</td>
                        <td>
                            @foreach ($resource->categories as $category)
                                {{ $category->name }}
                            @endforeach
                        </td>
                        <td>
                            <ul>
                                <?php
                                    $tempDay = array();
                                    $tempNextDay = '';
                                    $days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
                                    $tempOpen = '';
                                    $tempClose = '';
                                    $dayArr = array();
                                    $openTimeArr = array();
                                    $closeTimeArr = array();
                                ?>
                                @foreach($resource->hours as $day)

                                    <?php

                                        if (empty($tempDay))
                                            {
                                                $tempDay[] = $day->day;
                                                $key = array_search($day->day, $days); // returns key of matching day in array
                                                if($key < 6)
                                                    $tempNextDay = $days[$key + 1];
                                                $tempOpen = $day->openTime;
                                                $tempClose = $day->closeTime;
                                            }
                                        elseif(($tempOpen == $day->openTime) && ($tempClose == $day->closeTime) && ($tempNextDay == $day->day))
                                            {
                                                $tempDay[] = $day->day;
                                                $key = array_search($tempNextDay, $days); // returns key of matching day in array
                                                if($key < 6)
                                                    $tempNextDay = $days[$key + 1];
                                            }
                                        else
                                            {
                                                $dayArr[] = $tempDay;
                                                unset($tempDay);
                                                $tempDay[] = $day->day;
                                                $openTimeArr[] = $tempOpen;
                                                $closeTimeArr[] = $tempClose;
                                                $tempOpen = $day->openTime;
                                                $tempClose = $day->closeTime;
                                                $key = array_search($day->day, $days); // returns key of matching day in array
                                                if($key < 6)
                                                    $tempNextDay = $days[$key + 1];
                                            }

                                ?>
                                @endforeach

                            <?php
                                $dayArr[] = $tempDay;
                                $openTimeArr[] = $tempOpen;
                                $closeTimeArr[] = $tempClose;
                                foreach($dayArr as $key => $item)
                                    {
                                        if(empty($item))
                                            {
                                                echo '';
                                            }
                                        elseif (count($item) < 2)
                                            {
                                                echo '<li>' . $item[0] . ':<br>' . date('g:i A',strtotime($openTimeArr[$key])) . ' - ' . date('g:i A',strtotime($closeTimeArr[$key])) . '</li>';
                                            }
                                        else
                                            {
                                                echo '<li>' . $item[0] . ' - ' . end($item) . ':<br>' . date('g:i A',strtotime($openTimeArr[$key])) . ' - ' . date('g:i A',strtotime($closeTimeArr[$key])) . '</li>';
                                            }
                                    }
                            ?>
                           </ul>
                        </td>
                        <td><?php
                                    if(strlen($resource->publicPhoneNumber) > 0)
                                    {
                                        $tempPhoneNumber = $resource->publicPhoneNumber;
                                        $tempPhoneNumber = preg_replace("/[^0-9,x]/", "", $tempPhoneNumber );
                                            if(strlen($tempPhoneNumber) > 10)
                                            {
                                                $tempPhoneNumber = preg_replace("/^[1]/", "", $tempPhoneNumber );
                                            }
                                        $tempPhoneNumber = '(' . substr($tempPhoneNumber,0, 3) . ') '
                                                            . substr($tempPhoneNumber, 3, 3) . '-'
                                                            . substr($tempPhoneNumber, 6, 4) . ' '
                                                            . substr($tempPhoneNumber, 10, (strlen($tempPhoneNumber) - 10));
                                        echo $tempPhoneNumber;
                                    }
                                    else
                                    {
                                        echo "Not Provided";
                                    }

                            ?></td>
                        <td>{{ $resource->publicEmail }}</td>
                        <td>{{ $resource->website }}</td>
                        <td>{{ $resource->streetAddress }}</td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $resource->streetAddress2 }}</td>
                        <td>{{ $resource->city }}</td>
                        <td>{{ $resource->state }}</td>
                        <td>{{ $resource->zipCode }}</td>
                        <td><div width="50%"><span style="white-space: normal;">{{ $resource->description }}</span></div></td>
                        <td><div width="50%"><span style="white-space: normal;">{{ $resource->comments }}</span></div></td>
                        <td class="text-center">


                            <!-- show the resource (uses the show method found at GET /resource/view/{id} -->
                            {{--<a class="btn btn-sm btn-success" href="{{ URL::to('resources/' . $resource->id) }}">View</a>--}}
                            <button type="button" class="btn btn-sm report
                                    @if(Auth::user()->resources->contains($resource))
                                    removeReport" name="{{$resource->id}}">Remove Resource</button>
                                    @else
                                    addReport" name="{{$resource->id}}">Add Resource</button>
                                    @endif
                           {{-- <a class="btn btn-sm btn-primary" href="{{ URL::to('resources/addAjax/'. $resource->id) }}">Add to Report</a>--}}

                        </td>
                        <td class="text-center col-md-3">
                            <a class="btn btn-sm btn-success" href="{{ URL::to('resources/' . $resource->id) }}">View</a>
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
@stop

@push('scripts')
<script>
    @if (session()->has('flash_notification.message'))
        @if(session('flash_notification.level') == 'success')
            toastr.success('{{session('flash_notification.message')}}');
        @elseif(session('flash_notification.level') == 'danger')
            toastr.error('{{session('flash_notification.message')}}');
        @elseif(session('flash_notification.level') == 'info')
            toastr.info('{{session('flash_notification.message')}}');
        @endif
    @endif

    function stopPropagation(evt) {
        if (evt.stopPropagation !== undefined) {
            evt.stopPropagation();
        } else {
            evt.cancelBubble = true;
        }
    }
    $(document).ready(function() {

        //Apply DataTables
        $('#ResourceTable').dataTable( {
            orderCellsTop: true,
            initComplete: function () {
                this.api().columns([1,8,9,10]).every( function () {
                    var column = this;
                    var select = $('<select><option value=""></option></select>')
                            .appendTo( $(column.footer()))
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                );

                                column
                                        .search( val ? '^'+val+'$' : '', true, false )
                                        .draw();
                            });

                    column.data().unique().sort().each( function ( d, j ) {
                        select.append( '<option value="'+d+'">'+d+'</option>' )
                    } );
                } );
                this.api().columns([0]).every( function() {
                    var column = this;
                    var select = $('<select><option value=""></option></select>')
                            .appendTo($(column.footer()))
                            .on('change', function () {
                                var val = $(this).val();
                                column //Only the name column
                                        .search(val ? '^' + $(this).val() : val, true, false)
                                        .draw();
                            });
                    var letter = 'A';
                    for(y = 0; y < 26; y ++)
                    {
                        letter = String.fromCharCode('A'.charCodeAt() + y);
                        select.append('<option value="' + letter + '">' + letter + '</option>');
                    }

                });
                this.api().columns([2]).every( function() {
                    var column = this;
                    var select = $('<select><option value=""></option></select>')
                            .appendTo($(column.footer()))
                            .on('change', function () {
                                var val = $(this).val();
                                column //Only the name column
                                        .search(val ? $(this).val() : val, true, false)
                                        .draw();
                            });
                    var categories = <?php echo json_encode($categories); ?>;
                    for(y = 0; y < categories.length; y++)
                    {
                        select.append('<option value="' + categories[y] + '">' + categories[y] + '</option>');
                    }
                });

            },
            "fnDrawCallback":function(){
                $(this).show();
            }
        } );



    //Ajax for add to report button
        $('#ResourceTable').on('click', '.report', function(){
            var button = $(this);
            var index = button.attr("name");
            var remove = $(this).hasClass("removeReport");
            var add = $(this).hasClass("addReport");
                    <?php
                    $resourceNames = array('empty');
                    foreach($resources as $resource)
                    {
                        $resourceNames[$resource->id] = $resource->name;
                    }
                    ?>
            var resourceNames = <?php echo json_encode($resourceNames); ?>;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            if (add) {
                $.ajax({

                    type: "GET",
                    url: 'resources/add/' + $(this).attr("name"),
                    dataType: 'json',
                    success: function (data) {
                        //alerts users to successful button pushing.
                       /* html = '<div class="alert alert-success">' + resourceNames[index] + ' Added to Report!<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                        $('#successOrFailure').html(html);*/
                        toastr["success"]( resourceNames[index] + " successfully added to the report", "Resource Added to Report");
                        button.css({"background-color": "#c9302c", "color": "white", "border-color": "#ac2925"});
                        button.addClass('removeReport').removeClass('addReport');
                        button.text(function (i, text) {
                            return "Remove Resource";
                        })

                    },
                    error: function (data) {
                        if (data.status === 401) //redirect if not authenticated user.
                            $(location).prop('pathname', 'auth/login');
                        if (data.status === 422) {
                            //process validation errors here.
                            var errors = data.responseJSON; //this will get the errors response data.
                            //show them somewhere in the modal
                            errorsHtml = '<div class="alert alert-danger"><ul>';

                            $.each(errors, function (key, value) {
                                errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
                            });
                            errorsHtml += '</ul><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';

                            $('#successOrFailure').html(errorsHtml); //appending to a <div id="form-errors"></div> inside form
                        } else {
                            html = '<div class="alert alert-danger"><ul><li>There was a problem processing your request. ' +
                                    'Please try again later.</li></ul><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                            $('#successOrFailure').html(html);
                        }
                    }
                });
            }
            else if (remove) {
                $.ajax({

                    type: "GET",
                    url: 'resources/removeReport/' + $(this).attr("name"),
                    dataType: 'json',
                    success: function (data) {
                        //alerts users to successful button pushing.
                       /* html = '<div class="alert alert-danger">' + resourceNames[index] + ' Removed from Report!<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                        $('#successOrFailure').html(html);*/
                        toastr["success"](resourceNames[index] +" successfully removed from the report", "Resource Removed from Report");
                        button.css({"background-color": "#337ab7", "color": "white", "border-color": "#2e6da4"});
                        button.addClass('addReport').removeClass('removeReport');
                        button.text(function (i, text) {
                            return "Add Resource";
                        })

                    },
                    error: function (data) {
                        if (data.status === 401) //redirect if not authenticated user.
                            $(location).prop('pathname', 'auth/login');
                        if (data.status === 422) {
                            //process validation errors here.
                            var errors = data.responseJSON; //this will get the errors response data.
                            //show them somewhere in the modal
                            errorsHtml = '<div class="alert alert-danger"><ul>';

                            $.each(errors, function (key, value) {
                                errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
                            });
                            errorsHtml += '</ul><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';

                            $('#successOrFailure').html(errorsHtml); //appending to a <div id="form-errors"></div> inside form
                        } else {
                            html = '<div class="alert alert-danger"><ul><li>There was a problem processing your request. ' +
                                    'Please try again later.</li></ul><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>';
                            $('#successOrFailure').html(html);
                        }
                    }
                });
            }
        });
    } );
</script>

@endpush