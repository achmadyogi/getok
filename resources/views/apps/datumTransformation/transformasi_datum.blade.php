@extends('layouts.app')

@section('title', $data->nama_app)

@section('content')
    <br>
    @include("alerts")
    <div class="row">
        <div class="col-md-6">
            <div id="mapid" style="height: 450px; width: 100%;"></div>
        </div>
        <div class="col-md-6">
            <h3>{{$data->nama_app}}</h3>
            <form method="POST" action="{{route('trans-datum-submit-file')}}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-6">
                        <label>Pilih Datum Lama</label>
                        <select name="datum_lama" id="datum_lama" class="input">
                            @foreach($datum as $d)
                                <option value="{{$d->id_datum}}">{{$d->datum_name}}</option>
                            @endforeach
                        </select><br><br>
                    </div>
                    <div class="col-md-6">
                        <label>Pilih Datum Baru</label>
                        <select name="datum_baru" id="datum_baru" class="input">
                            @foreach($datum as $d)
                                <option value="{{$d->id_datum}}">{{$d->datum_name}}</option>
                            @endforeach
                        </select><br><br>
                    </div>
                </div>
                <label>Pilih Metode Transformasi</label><br>
                <select name="statistic" id="statistic" class="input" required>
                    <option value="">~~Pilih Metode~~</option>
                    <option value="1">Bursa Wolf</option>
                    <option value="2">Molodensky</option>
                    <option value="3">Molodensky Badekas</option>
                </select><br><br>
                <div id="lanjutan"></div>
            </form>
            <hr>
            <div id="result"></div>
        </div>
    </div>

    <div id="wait" style="position: fixed; z-index: 9999; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); left: 0; top: 0; padding-top: 15%; display: none;">
        <div style="background-color: #fefefe; margin: auto;padding: 20px;border: 1px solid #888;width: 40%; text-align: center">
            <img src="{{asset('img/loading.gif') }}" width="100px;"> <span style="display: inline-block; font-size: 20px" id="prog">Reading data...</span>
            <br><button type="button" class="button-default" onclick="cancelMe()">Cancel</button>
        </div>
    </div>

    @if(session()->has('init') && session('init') == true)
        <script type="text/javascript">
            $(document).ajaxStart(function(){
                $("#wait").css("display", "block");
            });

            $(document).ajaxComplete(function(){
                $("#wait").css("display", "none");
            });

            $.get('{{ route("calc-trans-datum") }}',{
                'datum_lama': <?php echo session('datum_lama'); ?>,
                'datum_baru': <?php echo session('datum_baru'); ?>,
                'statistic': <?php echo session('statistic'); ?>,
                'cat': <?php echo session('cat'); ?>,
                'stat': <?php echo session('stat'); ?>,
                'delimiter': <?php echo session('delimiter'); ?>,
                'id_transaksi': <?php echo session('id_transaksi'); ?>,
                'lines': <?php echo session('lines'); ?>,
                '_token':$('input[name=_token]').val()
            }, function(data, status){
                $("#result").html("<p>Message:"+ data['message'] +"<br>Download hasil transformasi disini. <br> " +
                    "<a href=\'../storage/app/public/transformasi_datum/id_{{session('id_transaksi')}}.txt\' " +
                        "style='text-decoration: none; color:white' download>" +
                    "<button type='button' class='button-default'>Unduh</button></a> </p>");
            });

            function cancelMe(){
                $("#wait").css("display", "none");
                throw new Error("Execution has been aborted!");
            }
        </script>
    @endif

    <script type="text/javascript">
        $(document).ready(function(){
            $('#statistic').change(function(){
                if($(this).val() == ""){
                    $('#lanjutan').html("Pilih metode transformasi untuk memulai");
                }else {
                    $.get('{{ route("datum-trans-method-selection") }}', {
                        'statistic': $(this).val(),
                        '_token': $('input[name=_token]').val()
                    }, function (data, status) {
                        $('#lanjutan').html(data);
                    });
                }
            });
        });
    </script>
    <script type="text/javascript">
        var grup = L.layerGroup();
        var mymap = L.map('mapid', {
            gestureHandling: true
        }).setView([-1.978455, 114.855697], 5);
        mymap.addLayer(grup);

        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 18,
            id: 'mapbox.streets',
            accessToken: 'pk.eyJ1IjoiYWNobWFkeW9naSIsImEiOiJjamdxZHlobmQwdXU0MzFsa2t3Z2k4dmV3In0.K2Ri-W53I7_etKnOo5Fy0Q'
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
@endsection
