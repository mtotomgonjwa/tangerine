@extends('layouts.master')

@section('title')
    Dashboard
@endsection
@section('content')
    @include('includes.message-block')
    <section class="row ">
            <!-- Main Content -->
            <div class="container-fluid">
                <div class="side-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">

                                <div class="panel-body">

                                    <div id="map" style="height:400px;">

                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <div class="options-box">

                                        <input type="button" id="show-listings" value="Show Listings">
                                        <input type="button" id="hide-listings" value="Hide Listings">
                                        <input type="button" id="toggle-drawing" value="Drawing Tools">
                                        <input type="text" id="zoom-to-area-text"
                                               placeholder="Enter your favorite area!">
                                        <input type="button" id="zoom-to-area" value="Zoom">

                                    </div>
                                </div>


                            </div>


                        </div>
                        <div class="row">
                            <div class=" col-md-4 col-md-offset-1">
                                <div class="panel panel-primary">
                                    <div class="panel-header">
                                        <h5>Add Site</h5>
                                    </div>
                                    <div class="panel-body">
                                        <form action="{{route('site.create')}}" method="post">
                                            <div class="input-group">
                                                <span class="input-group-addon custom_label"
                                                      id="basic-addon1">Landmark</span>
                                                <input type="text" class="form-control custom_label" name="landmark"
                                                       id="landmark">
                                            </div>
                                            <div class="form-group"></div>

                                            <div class="input-group">
                                                <span class="input-group-addon custom_label"
                                                      id="basic-addon1">Latitude</span>
                                                <input type="text" class="form-control custom_label" name="latitude"
                                                       id="latitude">
                                            </div>
                                            <div class="form-group"></div>
                                            <div class="input-group">
                                                <span class="input-group-addon custom_label"
                                                      id="basic-addon1">Longitude</span>
                                                <input type="text" class="form-control custom_label" name="longitude"
                                                       id="longitude">
                                            </div>
                                            <div class="form-group"></div>
                                            <div class="input-group">
                                                <span class="input-group-addon custom_label"
                                                      id="basic-addon1">Size</span>
                                                <select class="form-control custom_label" id="size" name="size">
                                                    <option value=""></option>
                                                    <option value="StreetLight">Streetlight</option>
                                                    <option value="Billboard">Billboard</option>
                                                    <option value="Medium">Medium</option>

                                                </select>
                                            </div>
                                            <div class="form-group"></div>
                                            <div class="btn-group button-group">
                                                <button type="submit" class="btn btn-default custom_label ">Submit
                                                </button>
                                                <input type="hidden" value="{{Session::token()}}" name="_token">
                                            </div>


                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>

                    <section class="panel panel-default padd">
                        <div class="panel-heading">Site Information</div>
                        <div class="panel-body">


                            <table id="myTable" class="table table-hover table-bordered dataTable">
                                <thead>
                                <th>Site ID</th>
                                <th>Landmark</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Size</th>
                                <th>Edit</th>
                                </thead>
                                <tbody>
                                @foreach($sites as $site)


                                    <tr role="row" class="odd">

                                        <td class="sorting_1">{{$site->id}}</td>
                                        <td>{{$site->landmark}}</td>
                                        <td>{{$site->latitude}}</td>
                                        <td>{{$site->longitude}}</td>
                                        <td>{{$site->size}}</td>

                                        <td><a class="edit" href="#">Update</a></td>


                                    </tr>
                                @endforeach
                                </tbody>

                                <tfoot>
                                <tr>
                                    <th rowspan="1" colspan="1">Site ID</th>
                                    <th rowspan="1" colspan="1">Landmark</th>
                                    <th rowspan="1" colspan="1">Latitude</th>
                                    <th rowspan="1" colspan="1">Longitude</th>
                                    <th rowspan="1" colspan="1">Size</th>
                                    <th rowspan="1" colspan="1">Edit</th>
                                </tr>
                                </tfoot>


                            </table>
                                    </div>


                    </section>

                </div>
            </div>


    </section>
    <div class="modal fade" tabindex="-1" role="dialog" id="edit-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Sites</h4>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <div class="input-group">
                            <span class="input-group-addon custom_label" id="basic-addon1">Landmark</span>
                            <input type="text" class="form-control custom_label"  name="site-landmark" id="site-landmark">
                        </div>
                        <div class="form-group">

                        </div>

                        <div class="input-group">
                            <span class="input-group-addon custom_label" id="basic-addon1">Latitude</span>
                            <input type="text" class="form-control custom_label"  name="site-latitude" id="site-latitude">
                        </div>
                        <div class="form-group">

                        </div>
                        <div class="input-group">
                            <span class="input-group-addon custom_label" id="basic-addon1">Longitude</span>
                            <input type="text" class="form-control custom_label" name="site-longitude" id="site-longitude">
                        </div>
                        <div class="form-group"></div>
                        <div class="input-group">
                            <span class="input-group-addon custom_label" id="basic-addon1">Size</span>
                            <select class="form-control custom_label" id="site-size" name="site-size">
                                <option value=""></option>
                                <option value="Billboard">Billboard</option>
                                <option value="Medium">Medium</option>
                                <option value="StreetLight">Streetlight</option>
                            </select>
                        </div>
                        <div class="form-group"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="site-modal-save">Save changes</button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?libraries=drawing,geometry&key=AIzaSyAHSZHfNanEI64wMO0M6URymVBIGOh3wSA&callback=initMap">
    </script>
    <script src="{{URL::to('src/js/maps.js')}}"></script>

    <script>
        var token = '{{Session::token()}}';
        var urlEdit = '{{route('edit.site')}}';
    </script>
@endsection