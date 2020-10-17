@extends('layouts.app')

@section('title', $data->nama_app)

@section('content')
<br><br>
<h3>{{$data->nama_app}}</h3><br>
<style type="text/css">
    #mapid{
        position:fixed;
        padding:0;
        margin:0;

        top:0;
        left:25%;

        width: 80%;
        height: 100%;
        z-index: 99;
    }
    #legend{
        position: fixed;
        padding: 5px 10px 5px 10px;
        margin: 0;

        top: 20px;
        right: 20px;

        width: 250px;
        height: 80px;
        z-index: 999;

        background-color: white;
        border-radius: 5px;
    }
    #konten{
        position: fixed;
        padding: 10px 20px 10px 20px;
        margin: 0;

        top: 0;
        left: 0;

        width: 25%;
        height: 100%;

        background-color: lightgrey;
        overflow-y: auto;
    }
    #grad{
        height: 10px;
        width: 100%;
        background-image: linear-gradient(to right, #234ecf, #29d946, yellow, red);
    }
</style>
<div id="mapid"></div>
<div id="legend">
    <b>Legenda</b>
    <table style="width: 100%">
        <tr>
            <th style="text-align: left;">{{$min}} m</th>
            <th style="text-align: right;">{{$max}} m</th>
        </tr>
    </table>
    <div id="grad"></div>
</div>
<div id="konten">
    <br>
    <h1 style="display: inline-block; font-family: nunito, arial"><a href="{{ route('index') }}" style="text-decoration: none; color: black"><b>GETOK</b></a></h1>
    <p>Engines for Various Geodetic Computation</p>
    <hr>
    <h4><b>Geoid Undulation (N)</b></h4><br>
    <label>Latitude (decimal degrees)</label>
    <input type="number" class="input" name="lat" placeholder="latitude" id="lat"><br><br>
    <label>Longitude (decimal degrees)</label>
    <input type="number" class="input" name="lon" placeholder="longitude" id="lon"><br><br>
    <label>Geid Value (meters)</label>
    <input type="text" class="input" name="N" placeholder="geoid undulation" id="N" disabled><br><br>
    <hr>
    <h4><b>Geoid Calculation using Files</b></h4><br>
    <form action="{{ route('geoid-file') }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <label>Format Data</label><br>
        <select name="format" id="format" class="input" required>
            <option value="1">index lat lon</option>
            <option value="2">index lon lat</option>
            <option value="3">lat lon</option>
            <option value="4">lon lat</option>
        </select><br><br>
        <label>Data Delimiter</label><br>
        <select name="delimiter" id="delimiter" class="input" required>
            <option value="1">Tab delimiter</option>
            <option value="2">Coma delimiter (,)</option>
            <option value="3">Semicolon delimiter (;)</option>
        </select><br><br>
        <label>Upload File</label><br>
        <input type="file" name="file" id="excelfile"><br><br>
        <input type="hidden" value="" id="lines" name="lines">
        <button type="submit" class="button-default">Calculate</button><br>
        <small><i>Akan ditampilkan sampel data Anda hingga 1000 data.</i></small>
    </form><br>
    <hr>

</div>
<div id="wait" style="position: fixed; z-index: 9999; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); left: 0; top: 0; padding-top: 15%; display: none;">
    <div id="iffine" style="background-color: #fefefe; margin: auto;padding: 20px;border: 1px solid #888;width: 40%;">
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
        while(i < lines){
            awal = i;
            i = i + 50;
            akhir = i;
            if(akhir > lines){
                akhir = lines;
            }
            $.get('{{ route("calc-geoid") }}',{
                'format': <?php echo session('format'); ?>,
                'delimiter': <?php echo session('delimiter'); ?>,
                'id_transaction': <?php echo session('id_transaction'); ?>,
                'lines': lines,
                'awal': awal,
                'akhir': akhir,
                '_token':$('input[name=_token]').val()
            }, function(data, status){
                setTimeout(function(){
                    prog = data['prog'];
                    bar = data['bar'];
                    setContent(data['prog'], data['bar']);
                },10);
            });
        }

        function setContent(prog, bar){
            $('#prog').html(prog);
            $('#bar').html('<div style="width: '+bar+'%; background-color: #0769B0; border:none; border-radius: 5px; height: 15px;"></div>');
            $('#bar_val').html("<i>"+bar+"%</i>");

            if(bar >= 100){
                setTimeout(function(){
                    $("#iffine").html("<h4><b>Data selesai dibuat</b></h4>\
                        <p>Download hasil transformasi disini. <br> <a href=\'../storage/app/public/geoid_result/id_{{session('id_transaction')}}.txt\' style='text-decoration: none; color:white' download><button type='button' class='button-default'>Unduh</button></a> </p><hr><p style='text-align: center'><button type=\"button\" class=\"button-default\" onclick=\"cancelMe()\">Selesai</button></p>");
                }, 1000);
            }
        }

        function cancelMe(){
            $("#wait").css("display", "none");
            throw new Error("Execution has been aborted!");
        }
    </script>
@endif

<script type="text/javascript" src="{{ asset('js/heatmap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/leaflet-heatmap.js') }}"></script>
<script type="text/javascript">


    // don't forget to include leaflet-heatmap.js
    var data = {
      max: <?php echo $max; ?>, min: <?php echo $min; ?>,
      data: [

        <?php
            $first = true;
            foreach ($json as $j) {
                if($first == true){
                    $first = false;
                    echo "{lat:".$j->lat.", lng:".$j->lon.", count:".$j->N."}";
                }else{
                    echo ",{lat:".$j->lat.", lng:".$j->lon.", count:".$j->N."}";
                }
            }
        ?>

        ]
    };

    var cfg = {
        // radius should be small ONLY if scaleRadius is true (or small radius is intended)
        // if scaleRadius is false it will be the constant radius used in pixels
        "radius": 0.75,
        "maxOpacity": .6,
        // scales the radius based on map zoom
        "scaleRadius": true,
        // if set to false the heatmap uses the global maximum for colorization
        // if activated: uses the data maximum within the current map boundaries
        //   (there will always be a red spot with useLocalExtremas true)
        "useLocalExtrema": true,
        // which field name in your data represents the latitude - default "lat"
        latField: 'lat',
        // which field name in your data represents the longitude - default "lng"
        lngField: 'lng',
        // which field name in your data represents the data value - default "value"
        valueField: 'count'
    };


    var heatmapLayer = new HeatmapOverlay(cfg);

    var grup = L.layerGroup();
    var mymap = L.map('mapid', {
      gestureHandling: true,
      layers: [grup, heatmapLayer],
    }).setView([-1.978455, 114.855697], 6);

    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        accessToken: 'pk.eyJ1IjoiYWNobWFkeW9naSIsImEiOiJja2dkMnR0a2swdGVmMnlxYXA2eXNnbXNxIn0.zRgg5AZXShJtOq-daasDNA'
    }).addTo(mymap);

    heatmapLayer.setData(data);

    var marker;
    function onMapClick(e) {
        var a = e.latlng.toString().split("(");
        var b = a[1].split(")");
        var c = b[0].split(", ");
        var lat = c[0]; var lon = c[1];
        if(marker){
            mymap.removeLayer(marker);
        }
        $.ajax({
            url:"{{ route('geoid-per-point') }}",
            method:"GET",
            data:{lat: lat, lon: lon, _token: $('input[name=_token]').val()},
            beforeSend:function()
            {
                marker = L.marker([lat, lon]).addTo(mymap);
                marker.bindPopup("<img src=\"{{asset('img/loading.gif') }}\" width=\"50px;\"> <span style=\"display: inline-block;\">Loading data...</span>").openPopup();
            },
            success:function(data, status)
            {
                mymap.removeLayer(marker);
                marker = L.marker([lat, lon]).addTo(mymap);
                marker.bindPopup("lat: "+lat+"<br>lon: "+lon+"<br>N: "+data['N']).openPopup();

                document.getElementById('lat').value = lat;
                document.getElementById('lon').value = lon;
                document.getElementById('N').value = data['N'];
            }
        });
    }

    mymap.on('click', onMapClick);

    $('#lat').keyup(function(){
        var lat = $(this).val();
        var lon = $('#lon').val();

        mymap.removeLayer(marker);

        $.ajax({
            url:"{{ route('geoid-per-point') }}",
            method:"GET",
            data:{lat: lat, lon: lon, _token: $('input[name=_token]').val()},
            beforeSend:function()
            {
                marker = L.marker([lat, lon]).addTo(mymap);
                marker.bindPopup("<img src=\"{{asset('img/loading.gif') }}\" width=\"50px;\"> <span style=\"display: inline-block;\">Loading data...</span>").openPopup();
            },
            success:function(data, status)
            {
                mymap.removeLayer(marker);
                marker = L.marker([lat, lon]).addTo(mymap);
                marker.bindPopup("lat: "+lat+"<br>lon: "+lon+"<br>N: "+data['N']).openPopup();

                document.getElementById('lat').value = lat;
                document.getElementById('lon').value = lon;
                document.getElementById('N').value = data['N'];
            }
        });

    });

    $('#lon').keyup(function(){
        var lat = $('#lat').val();
        var lon = $(this).val();

        mymap.removeLayer(marker);

        $.ajax({
            url:"{{ route('geoid-per-point') }}",
            method:"GET",
            data:{lat: lat, lon: lon, _token: $('input[name=_token]').val()},
            beforeSend:function()
            {
                marker = L.marker([lat, lon]).addTo(mymap);
                marker.bindPopup("<img src=\"{{asset('img/loading.gif') }}\" width=\"50px;\"> <span style=\"display: inline-block;\">Loading data...</span>").openPopup();
            },
            success:function(data, status)
            {
                mymap.removeLayer(marker);
                marker = L.marker([lat, lon]).addTo(mymap);
                marker.bindPopup("lat: "+lat+"<br>lon: "+lon+"<br>N: "+data['N']).openPopup();

                document.getElementById('lat').value = lat;
                document.getElementById('lon').value = lon;
                document.getElementById('N').value = data['N'];
            }
        });

    });

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
                    fillColor: "#7712a6",
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
    function eksekusi(){
        grup.clearLayers();
        var files = document.getElementById("excelfile").files;
        if (!files.length) {
            setTimeout(function() {
                addBounds();
            }, 10);
        }else{
            var file = files[0];
            var reader = new FileReader();

            // If we use onloadend, we need to check the readyState.
            reader.onloadend = function(evt) {
                if (evt.target.readyState == FileReader.DONE) {
                    var konten = evt.target.result;
                    var lines = konten.split('\n');
                    document.getElementById('lines').value = lines.length;
                    var myobj;
                    var delimiter = document.getElementById("delimiter").value;
                    var format = document.getElementById("format").value;
                    for(i=0; i<lines.length; i++){
                        if(i <= 1000){
                            if(delimiter == 1){
                                myobj = lines[i].split('\t');
                            }else if(delimiter == 2){
                                myobj = lines[i].split(',');
                            }else{
                                myobj = lines[i].split(';');
                            }
                            if(format == 1){
                                plotme(myobj[1],myobj[2]);
                            }else if(format == 3){
                                plotme(myobj[0],myobj[1]);
                            }else if(format == 2){
                                plotme(myobj[2],myobj[1]);
                            }else if(format == 4){
                                plotme(myobj[1],myobj[0]);
                            }
                        }
                    }
                }
            };
            reader.readAsText(file);
            setTimeout(function() {
                addBounds();
            }, 10);
        }
    }
    $('#delimiter').change(function(){
        eksekusi();
    });
    $('#excelfile').change(function () {
        eksekusi();
    });
    $('#format').change(function(){
        eksekusi();
    });
</script>
@endsection
