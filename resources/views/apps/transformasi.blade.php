@extends('layouts.app')

@section('title', $data->nama_app)

@section('content')
    <div id="mapid" style="height: 250px; width: 100%;"></div>
    <br>
    <h4><b>{{$data->app_name}}</b></h4>
    <div class="row">
        <div class="col-md-6">
            <button class="addButton" id="toggleMe"><b><span class="fa fa-gear"></span> Settings</b></button>
            <div id="setting" style="display: none;">
                <hr>
                <label>Reference System Configuration</label><br>
                <select name="config" id="config" class="input">
                    <option value="1">Use The Same Ellipsoid</option>
                    @if($surveyAmount > 0)
                        <option value="2">Use Survey Datums</option>
                    @endif
                </select><br><br>
                <div id="useEllipsoid">
                    <label>Ellipsoid Model</label><br>
                    <select name="ellipsoid" id="ellipsoid" class="input">
                        @foreach($ellipsoids as $l)
                            <option value="{{$l->id_ellipsoid}}">{{$l->ellipsoid_name}}</option>
                        @endforeach
                    </select><br><br>
                </div>
                <div id="useSurvey" style="display: none">
                    <label>Survey Name</label><br>
                    <select name="survey" id="survey" class="input">
                        @foreach($surveys as $s)
                            <option value="{{$s->id_survey}}">{{$s->survey_name}} ({{$s->survey_date}}) [{{$s->oldDatum->datum_name}} to {{$s->newDatum->datum_name}}]</option>
                        @endforeach
                    </select><br><br>
                    <label>Transformation Method</label><br>
                    <select name="transformationMethod" id="transformationMethod" class="input">
                        <option value="1">Bursa Wolf</option>
                        <option value="2">Molodensky Badekas</option>
                        <option value="3">Standard Molodensky</option>
                        <option value="4">Abridge Molodensky</option>
                        <option value="5">LAUF</option>
                    </select><br><br>
                </div>
                <hr>
            </div>
        </div>
        <script type="text/javascript">
            $('#toggleMe').click(function(){
               $('#setting').toggle(500);
            });
            $('#config').change(function(){
               if($(this).val() == 1){
                   $('#useEllipsoid').show();
                   $('#useSurvey').hide();
               } else{
                   $('#useEllipsoid').hide();
                   $('#useSurvey').show();
               }
            });
        </script>
    </div>
    <div style="text-align: center;">
        <nav>
            <ul>
                <li class="active"><a href="{{ route('trans-index') }}" style="text-decoration:none; color: black">Per Titik</a></li>
                <li ><a href="{{ route('trans-index-banyak-titik') }}" style="text-decoration:none; color: black">Kumpulan Titik</a></li>
            </ul>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-5">
            <label>Sistem Koordinat Awal</label><br>
            <select name="sys_in" id="sys_in" class="input">
                <option value="">~~Pilih Sistem Koordinat~~</option>
                @foreach($system as $s)
                    <option value="{{$s->id_sys}}">{{$s->sys_name}} [{{$s->sys_format}}]</option>
                @endforeach
            </select><br>
            <br>
            <div id="detail_in"></div>
        </div>
        <div class="col-md-2">
            <div style="text-align: center">
                <br><br>
                <button class="button-default" style="width: 120px;" title="exchange" onclick="changeMe()"><span class="fa fa-exchange"></span> </button><br><br>
                <button class="button-default" style="width: 120px;" title="calculate" onclick="calculate()">Calculate</button><br><br>
            </div>
        </div>
        <div class="col-md-5">
            <label>Sistem Koordinat Baru</label><br>
            <select name="sys_out" id="sys_out" class="input">
                <option value="">~~Pilih Sistem Koordinat~~</option>
                @foreach($system as $s)
                    <option value="{{$s->id_sys}}">{{$s->sys_name}}  [{{$s->sys_format}}]</option>
                @endforeach
            </select><br>
            <br>
            <div id="detail_out"></div>
        </div>
    </div>
    <script type="text/javascript">
        function getChange(div){
            if(div == 1){
                if($('#sys_in').val() == ""){
                    $('#detail_in').empty();
                }else{
                    if($('#sys_in').val() == $('#sys_out').val()){
                        $('#detail_out').html("Cannot convert to the same system.");
                    }
                    $.get('{{ route("detail-titik") }}', {
                        'system': $('#sys_in').val(),
                        '_token': $('input[name=_token]').val()
                    }, function (data, status) {
                        $('#detail_in').html(data);
                    });
                }
            }else{
                if($('#sys_out').val() == ""){
                    $('#detail_out').empty();
                }else{
                    if($('#sys_in').val() == $('#sys_out').val()){
                        $('#detail_out').html("Cannot convert to the same system.");
                    }else{
                        $.get('{{ route("detail-titik") }}', {
                            'system': $('#sys_out').val(),
                            '_token': $('input[name=_token]').val()
                        }, function (data, status) {
                            $('#detail_out').html(data);
                        });
                    }
                }
            }

        }
        $(document).ready(function(){
            $('#sys_in').change(function(){
                getChange(1);
            });
            $('#sys_out').change(function(){
                getChange(2);
            });
        });

        function calculate(){
            let system1, system2, lon_init;
            system1 = $('#sys_in').val();
            system2 = $('#sys_out').val();
            if(system1 == "" || system2 == ""){
                $('#detail_out').html("System one and system two must both filled.");
            }
            if(system2 == 4){
                lon_init = $('#lon_init').val();
            }else{
                lon_init = null;
            }
            switch (system1) {
                case "1": // geodetic
                    $.get('{{ route("convert") }}', {
                        'system1': system1,
                        'system2': system2,
                        'config': $('#config').val(),
                        'ellipsoid': $('#ellipsoid').val(),
                        'survey': $('#survey').val(),
                        'transformationMethod': $('#transformationMethod').val(),
                        'lat': ($('#lat').val() == "") ? 0 : $('#lat').val(),
                        'lon': ($('#lon').val() == "") ? 0 : $('#lon').val(),
                        'h': ($('#h').val() == "") ? 0 : $('#h').val(),
                        'lon_init': (lon_init == "") ? 0 : lon_init,
                        'id_datum': $('#datum').val(),
                        '_token': $('input[name=_token]').val()
                    }, function (data, status) {
                        finalization(system2, data);
                    });
                    break;
                case "2": // geocentric
                    $.get('{{ route("convert") }}', {
                        'system1': system1,
                        'system2': system2,
                        'config': $('#config').val(),
                        'ellipsoid': $('#ellipsoid').val(),
                        'survey': $('#survey').val(),
                        'transformationMethod': $('#transformationMethod').val(),
                        'X': ($('#X').val() == "") ? 0 : $('#X').val(),
                        'Y': ($('#Y').val() == "") ? 0 : $('#Y').val(),
                        'Z': ($('#Z').val() == "") ? 0 : $('#Z').val(),
                        'lon_init': (lon_init == "") ? 0 : lon_init,
                        'id_datum': $('#datum').val(),
                        '_token': $('input[name=_token]').val()
                    }, function (data, status) {
                        finalization(system2, data);
                    });
                    break;
                case "3": // UTM
                    $.get('{{ route("convert") }}', {
                        'system1': system1,
                        'system2': system2,
                        'config': $('#config').val(),
                        'ellipsoid': $('#ellipsoid').val(),
                        'survey': $('#survey').val(),
                        'transformationMethod': $('#transformationMethod').val(),
                        'x': ($('#x').val() == "") ? 0 : $('#x').val(),
                        'y': ($('#y').val() == "") ? 0 : $('#y').val(),
                        'h': ($('#h_utm').val() == "") ? 0 : $('#h_utm').val(),
                        'hemi': $('#hemi').val(),
                        'zone': $('#zone').val(),
                        'lon_init': (lon_init == "") ? 0 : lon_init,
                        'id_datum': $('#datum').val(),
                        '_token': $('input[name=_token]').val()
                    }, function (data, status) {
                        finalization(system2, data);
                    });
                    break;
                case "4": // Mercator
                    $.get('{{ route("convert") }}', {
                        'system1': system1,
                        'system2': system2,
                        'config': $('#config').val(),
                        'ellipsoid': $('#ellipsoid').val(),
                        'survey': $('#survey').val(),
                        'transformationMethod': $('#transformationMethod').val(),
                        'x': ($('#x_merc').val() == "") ? 0 : $('#x_merc').val(),
                        'y': ($('#y_merc').val() == "") ? 0 : $('#y_merc').val(),
                        'h': ($('#h_merc').val() == "") ? 0 : $('#h_merc').val(),
                        'lon_init': ($('#lon_init').val() == "") ? 0 : $('#lon_init').val(),
                        'id_datum': $('#datum').val(),
                        '_token': $('input[name=_token]').val()
                    }, function (data, status) {
                        finalization(system2, data);
                    });
                    break;
            }
        }

        function finalization(system2, data){
            let lat, lon;
            switch (system2) {
                case "1":
                    lat = data['result'].lat;
                    lon = data['result'].lon;
                    $('#lat').val(lat);
                    $('#lon').val(lon);
                    $('#ldeg').val(Math.trunc(lat));$('#lmin').val(Math.trunc((lat - Math.trunc(lat))*60));$('#lsec').val((((lat - Math.trunc(lat))*60 - Math.trunc((lat - Math.trunc(lat))*60))*60).toFixed(2));
                    $('#bdeg').val(Math.trunc(lon));$('#bmin').val(Math.trunc((lon - Math.trunc(lon))*60));$('#bsec').val((((lon - Math.trunc(lon))*60 - Math.trunc((lon - Math.trunc(lon))*60))*60).toFixed(2));
                    $('#h').val(data['result'].h);
                    plotme(data['gd'].lat, data['gd'].lon);
                    break;
                case "2":
                    $('#X').val(data['result'].X);
                    $('#Y').val(data['result'].Y);
                    $('#Z').val(data['result'].Z);
                    plotme(data['gd'].lat, data['gd'].lon);
                    break;
                case "3":
                    $('#x').val(data['result'].x);
                    $('#y').val(data['result'].y);
                    $('#h_utm').val(data['result'].h);
                    $('#hemi').val(data['result'].hemi);
                    $('#zone').val(data['result'].zone);
                    plotme(data['gd'].lat, data['gd'].lon);
                    break;
                case "4":
                    $('#x_merc').val(data['result'].x);
                    $('#y_merc').val(data['result'].y);
                    $('#h_merc').val(data['result'].h);
                    $('#lon_init').val(data['result'].meridianCentral);
                    plotme(data['gd'].lat, data['gd'].lon);
                    break;
            }
        }

        function changeMe(){
            system1 = $('#sys_in').val();
            system2 = $('#sys_out').val();
            $('#sys_in').val(system2);
            getChange(1);
            $('#sys_out').val(system1);
            getChange(2);
        }
    </script>
    <script type="text/javascript">
        const mymap = L.map('mapid', {
          gestureHandling: true
        }).setView([-1.978455, 114.855697], 5);

        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 18,
            id: 'mapbox/streets-v11',
            accessToken: 'pk.eyJ1IjoiYWNobWFkeW9naSIsImEiOiJja2dkMnR0a2swdGVmMnlxYXA2eXNnbXNxIn0.zRgg5AZXShJtOq-daasDNA'
        }).addTo(mymap);

        var marker;
        function onMapClick(e) {
            var ldeg, lmin, lsec, bdeg, bmin, bsec;
            var a = e.latlng.toString().split("(");
            var b = a[1].split(")");
            var c = b[0].split(", ");
            var lat = c[0]; var lon = c[1];
            if(marker){
                mymap.removeLayer(marker);
            }
            marker = L.marker([lat, lon]).addTo(mymap);
            marker.bindPopup(lat+", "+lon).openPopup();

            try{
                document.getElementById('lat').value = lat;
                document.getElementById('lon').value = lon;
            }catch(err){}

            try{
                document.getElementById('ldeg').value = parseInt(lat);
                document.getElementById('lmin').value = parseInt((lat - parseInt(lat))*60);
                document.getElementById('lsec').value = (((lat - parseInt(lat))*60 - parseInt((lat - parseInt(lat))*60))*60).toFixed(3);

                document.getElementById('bdeg').value = parseInt(lon);
                document.getElementById('bmin').value = parseInt((lon - parseInt(lon))*60);
                document.getElementById('bsec').value = (((lon - parseInt(lon))*60 - parseInt((lon - parseInt(lon))*60))*60).toFixed(3);
            }catch(err){}
        }

        mymap.on('click', onMapClick);

        function plotme(lat, lon){
            if(lon == ""){
                lon = 0;
            }
            if(lat == ""){
                lat = 0;
            }
            if(lat > -90 && lat < 90 && lon > -180 && lon < 180){
                if(marker){
                    mymap.removeLayer(marker);
                }
                marker = L.marker([lat, lon]).addTo(mymap);
                marker.bindPopup(lat+", "+lon).openPopup();
            }
        }
    </script>
@endsection
