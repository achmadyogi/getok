@extends('layouts.app')

@section('title', $data->nama_app)

@section('content')
<br><br>
<h3>{{$data->nama_app}}</h3><br>
<div class="row">
    <div class="col-md-6">
        <div id="mapid" style="height: 450px; width: 100%;"></div>
    </div>
    <div class="col-md-6">
        <nav>
            <ul>
                <li ><a href="{{ route('trans-index') }}" style="text-decoration:none; color: black">Per Titik</a></li>
                <li class="active"><a href="{{ route('trans-index-banyak-titik') }}" style="text-decoration:none; color: black">Kumpulan Titik</a></li>
            </ul>
        </nav>
        <br>
        <h4>Transformasi Titik</h4>
        <form action="{{ route('exe_trans') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <label>Datum Geodetik</label><br>
            <select name="datum" id="datum" class="input" required>
                @foreach($datum as $d)
                    <option value="{{$d->id_datum}}">{{$d->datum_name}}</option>
                @endforeach
            </select><br><br>
            <div class="row">
                <div class="col-md-6">
                    <label>Sistem Koordinat Awal</label><br>
                    <select name="sys_in" id="sys_in" class="input" required>
                        <option value="">~~Pilih Sistem Koordinat~~</option>
                        @foreach($system as $s)
                            <option value="{{$s->id_sys}}">{{$s->sys_name}} [{{$s->sys_format}}]</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Sistem Koordinat Baru</label><br>
                    <select name="sys_out" id="sys_out" class="input" required>
                        <option value="">~~Pilih Sistem Koordinat~~</option>
                        @foreach($system as $s)
                            <option value="{{$s->id_sys}}">{{$s->sys_name}} [{{$s->sys_format}}]</option>
                        @endforeach
                    </select>
                </div>
            </div><br>
            <div id="lanjutan"></div>
        </form>
        <br>
        <hr>
        <!--
            <table id="exceltable">
            </table>
        -->
        <div id="result"></div>
    </div>
</div>
<div id="wait" style="position: fixed; z-index: 9999; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); left: 0; top: 0; padding-top: 15%; display: none;">
    <div style="background-color: #fefefe; margin: auto;padding: 20px;border: 1px solid #888;width: 40%;">
        <img src="{{asset('img/loading.gif') }}" width="50px;"> <span style="display: inline-block;" id="prog">Reading data...</span>
        <div style="width: 100%; background-color: lightgrey; border:none; border-radius: 5px; height: 15px;" id="bar">
            <div style="width: 0%; background-color: #0769B0; border:none; border-radius: 5px; height: 15px;"></div>
        </div>
        <small id="bar_val"><i>0%</i></small><br><br>
        <button type="button" class="button-default" onclick="cancelMe()">Cancel</button>
    </div>
</div>
@if(session()->has('init') && session('init') == true)
    <script type="text/javascript">
        var prog = "Reading data...";
        var bar = 0;
            $('#prog').html('{{session("prog")}}');
            $('#bar').html('<div style="width: '+'{{session("bar")}}'+'%; background-color: #0769B0; border:none; border-radius: 5px; height: 15px;"></div>');
            $('#bar_val').html("<i>{{session('bar')}}%</i>");
            $("#wait").css("display", "block");
            var lines = <?php echo session('lines'); ?>;
            var i = 1; var awal = 1; var akhir = 50;
            var sysi = <?php echo session('sys_in'); ?>;
            while(i < lines){
                awal = i;
                i = i + 50;
                akhir = i;
                if(akhir > lines){
                    akhir = lines;
                }
                if(sysi == 3){
                    $.get('{{ route("calc-trans") }}',{
                        'sys_in': <?php echo session('sys_in'); ?>,
                        'sys_out': <?php echo session('sys_out'); ?>,
                        'structure': <?php echo session('structure'); ?>,
                        'delimiter': <?php echo session('delimiter'); ?>,
                        'hemi': "<?php echo session('hemi'); ?>",
                        'zone': "<?php echo session('zone'); ?>",
                        'id_transaction': <?php echo session('id_transaction'); ?>,
                        'lines': lines,
                        'awal': awal,
                        'akhir': akhir,
                        'lon_init': <?php echo session('lon_init'); ?>,
                        'id_datum': <?php echo session('id_datum'); ?>,
                        '_token':$('input[name=_token]').val()
                    }, function(data, status){
                        setTimeout(function(){
                            prog = data['prog'];
                            bar = data['bar'];
                            setContent(data['prog'], data['bar']);
                        },10);
                    });
                }else{
                    $.get('{{ route("calc-trans") }}',{
                        'sys_in': <?php echo session('sys_in'); ?>,
                        'sys_out': <?php echo session('sys_out'); ?>,
                        'structure': <?php echo session('structure'); ?>,
                        'delimiter': <?php echo session('delimiter'); ?>,
                        'id_transaction': <?php echo session('id_transaction'); ?>,
                        'lines': lines,
                        'awal': awal,
                        'akhir': akhir,
                        'lon_init': <?php echo session('lon_init'); ?>,
                        'id_datum': <?php echo session('id_datum'); ?>,
                        '_token':$('input[name=_token]').val()
                    }, function(data, status){
                        setTimeout(function(){
                            prog = data['prog'];
                            bar = data['bar'];
                            setContent(data['prog'], data['bar']);
                        },10);
                    });
                };
            }

        function setContent(prog, bar){
            $('#prog').html(prog);
            $('#bar').html('<div style="width: '+bar+'%; background-color: #0769B0; border:none; border-radius: 5px; height: 15px;"></div>');
            $('#bar_val').html("<i>"+bar+"%</i>");

            if(bar >= 100){
                setTimeout(function(){
                    $("#result").html("<p>Download hasil transformasi disini. <br> <a href=\'" + "{{asset("storage/convert_result/id_".session('id_transaction').".txt")}}" + "\' style='text-decoration: none; color:white' download><button type='button' class='button-default'>Unduh</button></a> </p>");
                    $("#wait").css("display", "none");
                }, 1000);
            }
        }

        function cancelMe(){
            $("#wait").css("display", "none");
            throw new Error("Execution has been aborted!");
        }
    </script>
@endif
<script type="text/javascript">
$(document).ready(function(){
    $('#sys_in').change(function(){
        $.get('{{ route("detail-file") }}',{
            'sys_in': $(this).val(),
            'sys_out': $('#sys_out').val(),
            '_token':$('input[name=_token]').val()
        }, function(data, status){
            $('#lanjutan').html(data);
        });
    });
    $('#sys_out').change(function(){
        $.get('{{ route("detail-file") }}',{
            'sys_in': $('#sys_in').val(),
            'sys_out': $(this).val(),
            '_token':$('input[name=_token]').val()
        }, function(data, status){
            $('#lanjutan').html(data);
        });
    });
});
</script>
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

    function addBounds(){
        mymap.fitBounds([
            [min_lat, min_lon],
            [max_lat, max_lon]
        ]);
        min_lat = 100, max_lat = -100, min_lon = 200, max_lon = -200;
    }
</script>
@endsection
