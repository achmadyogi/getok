@extends('layouts.app')

@section('title', $data->app_name)

@section('content')
    <br>
    <div class="container">
        @include("alerts")
        <h3>{{$data->app_name}}</h3><br>
        <h4><b>Survey Details</b></h4><br>
        <div class="row">
            <div class="col-md-6">
                <div id="mapid" style="height: 450px; width: 100%;"></div>
            </div>
            <div class="col-md-6">
                <span style="display: inline-block; width: 150px">Survey name</span>: <b>{{$survey->survey_name}}</b><br>
                <span style="display: inline-block; width: 150px">Survey date</span>: {{$survey->survey_date}}<br>
                <span style="display: inline-block; width: 150px">Old Datum</span>: {{$survey->oldDatum->datum_name}}<br>
                <span style="display: inline-block; width: 150px">New Datum</span>: {{$survey->newDatum->datum_name}}<br>
                <span style="display: inline-block; width: 150px">Creator</span>: {{$survey->user->name}}<br>
                <span style="display: inline-block; width: 150px">Description</span>: {{$survey->description}}<br>
                <span style="display: inline-block; width: 150px">Date Created</span>: {{$survey->created_at}}<br>

                <br>

                <b>Upload Points</b><br>
                <form action="{{route('upload-datum-points')}}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <label>Choose A System Coordinate</label>
                    <input type="hidden" name="idSurvey" value="{{$survey->id_survey}}">
                    <select name="coordinate" id="coordinate" class="input" required>
                        <option value="">~~Choose One~~</option>
                        @foreach($coordinate as $d)
                            <option value="{{$d->id_sys}}">{{$d->sys_name}}</option>
                        @endforeach
                    </select><br><br>
                    <label>Data Delimiter</label><br>
                    <select name="delimiter" id="delimiter" class="input" required>
                        <option value="1">Tab delimiter</option>
                        <option value="2">Coma delimiter (,)</option>
                        <option value="3">Semicolon delimiter (;)</option>
                    </select><br><br>
                    <div id="detail"></div>
                    <label>Upload File</label><br>
                    <input type="file" name="file" id="excelfile" required>
                    <button type="submit" class="button-default">Submit</button><br>
                    <small style="color: #ff0000">NOTE: Re-uploading data will replace the current data points.</small>
                </form><br>
            </div>
        </div>

        <hr>
        <h4><b>Survey Points</b></h4><br>
        @if($points->count() == 0)
            You do not have any survey points. Upload now!
        @else
            <div style="text-align: center">
                <form action="{{route('calculate-datum-now')}}" method="post">
                    {{csrf_field()}}
                    <input type="hidden" name="idSurvey" value="{{$survey->id_survey}}">
                    <button type="submit" class="button-default">Calculate NOW</button>
                </form><br>
            </div>

            <div id="dataPoints">
                <b>Data In Use</b><br>
                <div class="table">
                    <table>
                        <tr>
                            <th>Point Name</th>
                            <th>X1</th>
                            <th>Y1</th>
                            <th>Z1</th>
                            <th>X2</th>
                            <th>Y2</th>
                            <th>Z2</th>
                            <th></th>
                        </tr>
                        @foreach($points as $p)
                            @if($p->is_used == 1)
                                @if($p->bursawolf_passing_status == 0)
                                    <tr>
                                @elseif($p->bursawolf_passing_status == 1 && $p->molobas_passing_status == 1)
                                    <tr style="background-color: #c2ffce">
                                @else
                                    <tr style="background-color: #ffcac7">
                                @endif
                                    <td>{{$p->point_name}}</td>
                                    <td>{{$p->X1}}</td>
                                    <td>{{$p->Y1}}</td>
                                    <td>{{$p->Z1}}</td>
                                    <td>{{$p->X2}}</td>
                                    <td>{{$p->Y2}}</td>
                                    <td>{{$p->Z2}}</td>
                                    <td><button type="button" onclick="setStash('{{$p->id_point}}')" class="button-close">throw</button></td>
                                </tr>
                            @endif
                        @endforeach
                        <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                    </table>
                </div>
                <br>
                <b>Data In Stash</b><br>
                <div class="table">
                    <table>
                        <tr>
                            <th>Point Name</th>
                            <th>X1</th>
                            <th>Y1</th>
                            <th>Z1</th>
                            <th>X2</th>
                            <th>Y2</th>
                            <th>Z2</th>
                            <th></th>
                        </tr>
                        @foreach($points as $p)
                            @if($p->is_used == 0)
                                @if($p->bursawolf_passing_status == 0)
                                    <tr>
                                @elseif($p->bursawolf_passing_status == 1 && $p->molobas_passing_status == 1)
                                    <tr style="background-color: #c2ffce">
                                @else
                                    <tr style="background-color: #ffcac7">
                                @endif
                                    <td>{{$p->point_name}}</td>
                                    <td>{{$p->X1}}</td>
                                    <td>{{$p->Y1}}</td>
                                    <td>{{$p->Z1}}</td>
                                    <td>{{$p->X2}}</td>
                                    <td>{{$p->Y2}}</td>
                                    <td>{{$p->Z2}}</td>
                                    <td><button type="button" onclick="reuse('{{$p->id_point}}')" class="button-default">reuse</button></td>
                                </tr>
                            @endif
                        @endforeach
                        <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                    </table>
                </div>
            </div>
        @endif
        <hr>

        <h4><b>Bursawolf Details</b></h4><br>
        @if($bursawolf != null)
            <span style="display: inline-block; width: 50px">Tx</span>: <span style="display: inline-block; width: 200px">{{$bursawolf->dx}} m</span> <u>+</u> {{abs($bursawolf->dx_uncertainty)}} m<br>
            <span style="display: inline-block; width: 50px">Ty</span>: <span style="display: inline-block; width: 200px">{{$bursawolf->dy}} m</span> <u>+</u> {{abs($bursawolf->dy_uncertainty)}} m<br>
            <span style="display: inline-block; width: 50px">Tz</span>: <span style="display: inline-block; width: 200px">{{$bursawolf->dz}} m</span> <u>+</u> {{abs($bursawolf->dz_uncertainty)}} m<br>
            <span style="display: inline-block; width: 50px">εx</span>: <span style="display: inline-block; width: 200px">{{$bursawolf->ex}} rad</span> <u>+</u> {{abs($bursawolf->ex_uncertainty)*pow(10,6)}} μrad<br>
            <span style="display: inline-block; width: 50px">εy</span>: <span style="display: inline-block; width: 200px">{{$bursawolf->ey}} rad</span> <u>+</u> {{abs($bursawolf->ey_uncertainty)*pow(10,6)}} μrad<br>
            <span style="display: inline-block; width: 50px">εz</span>: <span style="display: inline-block; width: 200px">{{$bursawolf->ex}} rad</span> <u>+</u> {{abs($bursawolf->ez_uncertainty)*pow(10,6)}} μrad<br>
            <span style="display: inline-block; width: 50px">ds</span>: <span style="display: inline-block; width: 200px">{{$bursawolf->ds}}</span> <u>+</u> {{abs($bursawolf->ds_uncertainty)*pow(10,6)}} ppm<br>
        @endif
        <hr>
        <h4><b>Molodensky Badekas Details</b></h4><br>
        @if($molobas != null)
            <span style="display: inline-block; width: 50px">Tx</span>: <span style="display: inline-block; width: 200px">{{$molobas->dx}} m</span> <u>+</u> {{abs($molobas->dx_uncertainty)}} m<br>
            <span style="display: inline-block; width: 50px">Ty</span>: <span style="display: inline-block; width: 200px">{{$molobas->dy}} m</span> <u>+</u> {{abs($molobas->dy_uncertainty)}} m<br>
            <span style="display: inline-block; width: 50px">Tz</span>: <span style="display: inline-block; width: 200px">{{$molobas->dz}} m</span> <u>+</u> {{abs($molobas->dz_uncertainty)}} m<br>
            <span style="display: inline-block; width: 50px">εx</span>: <span style="display: inline-block; width: 200px">{{$molobas->ex}} rad</span> <u>+</u> {{abs($molobas->ex_uncertainty)*pow(10,6)}} μrad<br>
            <span style="display: inline-block; width: 50px">εy</span>: <span style="display: inline-block; width: 200px">{{$molobas->ey}} rad</span> <u>+</u> {{abs($molobas->ey_uncertainty)*pow(10,6)}} μrad<br>
            <span style="display: inline-block; width: 50px">εz</span>: <span style="display: inline-block; width: 200px">{{$molobas->ex}} rad</span> <u>+</u> {{abs($molobas->ez_uncertainty)*pow(10,6)}} μrad<br>
            <span style="display: inline-block; width: 50px">ds</span>: <span style="display: inline-block; width: 200px">{{$molobas->ds}}</span> <u>+</u> {{abs($molobas->ds_uncertainty)*pow(10,6)}} ppm<br>
        @endif
        <hr>
    </div>
    <script type="text/javascript">
        var grup = L.layerGroup();
        var mymap = L.map('mapid', {
            gestureHandling: true
        }).setView([-1.978455, 114.855697], 5);
        mymap.addLayer(grup);

        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 18,
            id: 'mapbox/streets-v11',
            accessToken: 'pk.eyJ1IjoiYWNobWFkeW9naSIsImEiOiJja2dkMnR0a2swdGVmMnlxYXA2eXNnbXNxIn0.zRgg5AZXShJtOq-daasDNA'
        }).addTo(mymap);

        var marker;
        function onMapClick(e) {
            var a = e.latlng.toString().split("(");
            var b = a[1].split(")");
            var c = b[0].split(", ");
            var lat = c[0]; var lon = c[1];
            if(marker){
                mymap.removeLayer(marker);
            }
            marker = L.marker([lat, lon]).addTo(mymap);
            marker.bindPopup(lat+", "+lon).openPopup();

            document.getElementById('lat').value = lat;
            document.getElementById('lon').value = lon;

            $('#result').empty();
        }

        mymap.on('click', onMapClick);

        var min_lat = 100, max_lat = -100, min_lon = 200, max_lon = -200;
        var dotting, latlng;
        function plotme(lat, lon){
            if(isNaN(lat) == false && isNaN(lon) == false){
                if(lat > -90 && lat < 90 && lon > -180 && lon < 180){
                    if(lat < min_lat){
                        min_lat = lat;
                    }
                    if(lat > max_lat){
                        max_lat = lat;
                    }
                    if(lon < min_lon){
                        min_lon = lon;
                    }
                    if(lon > max_lon){
                        max_lon = lon;
                    }
                    $('#result').empty();
                    var markerOpt = {
                        radius: 4,
                        fillColor: "#ff7800",
                        color: "#000",
                        weight: 1,
                        opacity: 1,
                        fillOpacity: 0.8
                    };

                    latlng = L.latLng(lat, lon);
                    dotting = L.circleMarker(latlng, markerOpt);
                    grup.addLayer(dotting);
                }
            }
        }

        function plotme2(lat, lon){
            if(isNaN(lat) == false && isNaN(lon) == false){
                if(lat > -90 && lat < 90 && lon > -180 && lon < 180){
                    if(lat < min_lat){
                        min_lat = lat;
                    }
                    if(lat > max_lat){
                        max_lat = lat;
                    }
                    if(lon < min_lon){
                        min_lon = lon;
                    }
                    if(lon > max_lon){
                        max_lon = lon;
                    }
                    $('#result').empty();
                    var markerOpt = {
                        radius: 4,
                        fillColor: "#9124b5",
                        color: "#000",
                        weight: 1,
                        opacity: 1,
                        fillOpacity: 0.8
                    };

                    latlng = L.latLng(lat, lon);
                    dotting = L.circleMarker(latlng, markerOpt);
                    grup.addLayer(dotting);
                }
            }
        }

        function addBounds(){
            mymap.fitBounds([
                [min_lat, min_lon],
                [max_lat, max_lon]
            ]);
            min_lat = 100, max_lat = -100, min_lon = 200, max_lon = -200;
        }
    </script>
    <script type="text/javascript">
        var coord;
        var gd = "<label>Structure Data</label><br>" +
            "        <select name=\"structure\" id=\"structure\" class=\"input\" required>" +
            "            <option value=\"1\">index lat1 lon1 h1 lat2 lon2 h2</option>" +
            "            <option value=\"2\">index lat1 dlat1 lon1 dlon1 h1 dh1 lat2 dlat2 lon2 dlon2 h2 dh2</option>" +
            "        </select><br><br>";
        var gs = "<label>Structure Data</label><br>" +
            "        <select name=\"structure\" id=\"structure\" class=\"input\" required>" +
            "            <option value=\"3\">index X1 Y1 Z1 X2 Y2 Z2</option>" +
            "            <option value=\"4\">index X1 dX1 Y1 dY1 Z1 dZ1</option>" +
            "        </select><br><br>";
        var utm = "<label>Structure Data</label><br>" +
            "        <select name=\"structure\" id=\"structure\" class=\"input\" required>" +
            "            <option value=\"5\">index x1 y1 h1 x2 y2 h2</option>" +
            "            <option value=\"6\">index x1 dx1 y1 dy1 z1 dz1 x2 dx2 y2 dy2 z2 dz2</option>" +
            "        </select><br><br>" +
            "        <label>Hemisphere</label><br>" +
            "        <select name=\"hemi\" class=\"input\" required>" +
            "           <option value='N'>N</option>" +
            "           <option value='S'>S</option>" +
            "        </select><br><br>" +
            "        <select name='zone' class=\"input\" required>" +
            "           @for($i=1; $i<=60; $i++)" +
            "               <option value='{{$i}}'>{{$i}}</option>" +
            "           @endfor" +
            "        </select><br><br>";
        var merc = "<label>Structure Data</label><br>" +
            "        <select name=\"structure\" id=\"structure\" class=\"input\" required>" +
            "            <option value=\"5\">index x1 y1 h1 x2 y2 h2</option>" +
            "            <option value=\"6\">index x1 dx1 y1 dy1 z1 dz1 x2 dx2 y2 dy2 z2 dz2</option>" +
            "        </select><br><br>" +
            "       <label>Meridian Central</label><br>" +
            "       <input type='number' step='any' name='meridianCentral' class='form-control'><br>";
        $(document).ready(function(){
            $('#coordinate').change(function(){
                coord = $(this).val();
                if (coord == null){
                    $("#detail").empty();
                }else if (coord == 1){
                    $("#detail").empty();
                    $("#detail").html(gd);
                }else if(coord == 2){
                    $("#detail").empty();
                    $("#detail").html(gs);
                }else if (coord == 3){
                    $("#detail").empty();
                    $("#detail").html(utm);
                }else{
                    $("#detail").empty();
                    $("#detail").html(merc);
                }
            });
        });
    </script>
    <script type="text/javascript">
        function reuse(idPoint){
            $.get('{{ route("point-use-edit") }}', {
                'idPoint': idPoint,
                'isUsed': 1,
                '_token': $('input[name=_token]').val()
            }, function (data) {
                $('#dataPoints').html(data);
            });
        }

        function setStash(idPoint){
            $.get('{{ route("point-use-edit") }}', {
                'idPoint': idPoint,
                'isUsed': 0,
                '_token': $('input[name=_token]').val()
            }, function (data) {
                $('#dataPoints').html(data);
            });
        }
    </script>
    <script type="text/javascript">
        <?php
            foreach ($points as $p){
                echo "var gd1 = gs2gd(".$p->X1.",".$p->Y1.",".$p->Z1.");";
                echo "plotme(gd1.lat,gd1.lon);";
                echo "var gd2 = gs2gd(".$p->X2.",".$p->Y2.",".$p->Z2.");";
                echo "plotme2(gd2.lat,gd2.lon);";
            }
            echo "addBounds();"
        ?>
    </script>
@endsection

