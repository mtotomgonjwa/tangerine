@extends('layouts.master')

@section('title')
    Add Client
@endsection
@section('content')

    <section class="row ">
        <!-- Main Content -->
        <div class="container-fluid">
            <div class="side-body">
                <div class="row">


                    <div class="col-md-3 col-md-offset-4 ">
                        <div class="panel panel-default">
                            <div class="panel-header">
                                <h5> Add Client</h5>
                            </div>
                            <div class="panel-body">
                                <form method="post" action="{{route('create.client')}}" enctype="multipart/form-data">

                                    <div class="input-group">
                                        <span class="input-group-addon custom_label"
                                              id="sizing-addon2">Client Name</span>
                                        <input type="text" class="form-control custom_label" name="client_name"
                                               id="client_name"
                                               aria-describedby="sizing-addon2">
                                    </div>
                                    <div class="form-group"></div>
                                    <div class="input-group">
                                        <span class="input-group-addon custom_label"
                                              id="sizing-addon2">Client Contact</span>
                                        <input type="text" class="form-control custom_label" name="client_contact"
                                               id="client_contact" aria-describedby="sizing-addon2">
                                    </div>
                                    <div class="form-group"></div>
                                    <div class="input-group">
                                        <span class="input-group-addon custom_label"
                                              id="sizing-addon2">Client Email</span>
                                        <input type="email" class="form-control custom_label" name="client_email"
                                               id="client_email" aria-describedby="sizing-addon2">
                                    </div>

                                    <div class="form-group"></div>
                                    <div class="btn-group">
                                        <button class="btn btn-secondary custom_label" type="submit">Submit</button>
                                    </div>
                                    <input type="hidden" value="{{Session::token()}}" name="_token">
                                </form>
                            </div>
                        </div>

                    </div>

                </div>
                <section class="panel panel-default padd">
                    <div class="panel-heading"><h5>Client Information</h5></div>
                    <div class="panel-body">
                        <table id="myTable" class="table table-hover table-bordered dataTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Client Name</th>
                                <th>Client Contact</th>
                                <th>Client Email</th>
                                <th>Update</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($clients as $client)

                                <tr role="row" class="even">
                                    <td>{{$client->id}}</td>
                                    <td>{{$client->client_name}}</td>
                                    <td>{{$client->client_contact}}</td>
                                    <td>{{$client->client_email}}</td>
                                    <td><a class="update" href="">update</a></td>
                                </tr>


                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>


        </div>
        </div>

    </section>
    <div class="modal fade" tabindex="-1" role="dialog" id="client-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Client Details</h4>
                </div>
                <div class="modal-body">
                    <form action="" class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon custom_label" id="sizing-addon2">Client Name</span>
                            <input type="text" class="form-control custom_label" name="clientname" id="clientname"
                                   aria-describedby="sizing-addon2">
                        </div>
                        <div class="form-group"></div>
                        <div class="input-group">
                            <span class="input-group-addon custom_label" id="sizing-addon2">Client Contact</span>
                            <input type="text" class="form-control custom_label" name="clientcontact"
                                   id="clientcontact" aria-describedby="sizing-addon2">
                        </div>
                        <div class="form-group"></div>
                        <div class="input-group">
                            <span class="input-group-addon custom_label" id="sizing-addon2">Client Email</span>
                            <input type="email" class="form-control custom_label" name="clientemail"
                                   id="clientemail" aria-describedby="sizing-addon2">
                        </div>

                        <div class="form-group"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


@endsection