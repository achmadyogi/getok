@if($system == 1)
	<input type="radio" name="format" value="1"> DMS
	<input type="radio" name="format" value="2" checked> Decimal Degrees
	<br><br>
	<div id="yes">
		<div class="row">
			<div class="col-md-6">
		        <label>Lintang</label><br>
		        <input type="number" name="lat" id="lat" min="-90" max="90" class="input" placeholder="values from -90 to 90"><br><br>
		        <label>Tinggi</label><br>
		        <input type="number" name="h" id="h" class="input" placeholder="ketinggian"><br><br>
		    </div>
		    <div class="col-md-6">
		        <label>Bujur</label><br>
		        <input type="number" name="lon" id="lon" min="-180" max="180" class="input" placeholder="values from -180 to 180"><br><br>
		    </div>
		</div>
	</div>
	<script type="text/javascript">
		var content1 = '\
		<div class="row">\
			<div class="col-md-6">\
				<label>Lintang</label><br>\
				<input type="hidden" name="lat" id="lat">\
				<input type="number" id="ldeg" name="ldeg" class="input" style="width: 70px;" placeholder="DD">\
				<input type="number" id="lmin" name="lmin" class="input" style="width: 70px;" placeholder="MM">\
				<input type="number" id="lsec" name="lsec" class="input" style="width: 100px;" placeholder="SS">\
				<br><br>\
		        <label>Tinggi</label><br>\
		        <input type="number" name="h" id="h" class="input" placeholder="ketinggian"><br><br>\
		    </div>\
		    <div class="col-md-6">\
		    	<label>Bujur</label><br>\
		    	<input type="hidden" name="lon" id="lon">\
				<input type="number" id="bdeg" name="bdeg" class="input" style="width: 70px;" placeholder="DD">\
				<input type="number" id="bmin" name="bmin" class="input" style="width: 70px;" placeholder="MM">\
				<input type="number" id="bsec" name="bsec" class="input" style="width: 100px;" placeholder="SS">\
				<br><br>\
		    </div>\
		</div>\
		';
		var content2 = '\
		<div class="row">\
			<div class="col-md-6">\
		        <label>Lintang</label><br>\
		        <input type="number" name="lat" id="lat" min="-90" max="90" class="input" placeholder="values from -90 to 90"><br><br>\
		        <label>Tinggi</label><br>\
		        <input type="number" name="h" id="h" class="input" placeholder="ketinggian"><br><br>\
		    </div>\
		    <div class="col-md-6">\
		        <label>Bujur</label><br>\
		        <input type="number" name="lon" id="lon" min="-180" max="180" class="input" placeholder="values from -180 to 180"><br><br>\
		    </div>\
		</div>\
		';
		$('input[type=radio][name=format]').change(function() {
		    if (this.value == 1) {
		        $('#yes').html(content1);
		    }
		    else{
		        $('#yes').html(content2);
		    }
		});
		function dms2deg(deg, min, sec){
			if(deg == null){
				deg = 0;
			}
			if(min == null){
				min = 0;
			}
			if(sec == null){
				sec = 0;
			}
			var degrees = deg+(min+(sec/60))/60;
			return degrees;
		}
		$('#ldeg').keyup(function(){
			$('#lat').val(dms2deg($('#ldeg').val(), $('#lmin').val(), $('#lsec').val()));
		});
		$('#lmin').keyup(function(){
			$('#lat').val(dms2deg($('#ldeg').val(), $('#lmin').val(), $('#lsec').val()));
		});
		$('#lsec').keyup(function(){
			$('#lat').val(dms2deg($('#ldeg').val(), $('#lmin').val(), $('#lsec').val()));
		});

		$('#bdeg').keyup(function(){
			$('#lon').val(dms2deg($('#bdeg').val(), $('#bmin').val(), $('#bsec').val()));
		});
		$('#bmin').keyup(function(){
			$('#lon').val(dms2deg($('#bdeg').val(), $('#bmin').val(), $('#bsec').val()));
		});
		$('#bsec').keyup(function(){
			$('#lon').val(dms2deg($('#bdeg').val(), $('#bmin').val(), $('#bsec').val()));
		});
	</script>
	<script type="text/javascript">
		// change position on edit
	    $('#lat').keyup(function(){
	        var lat = $(this).val();
	        var lon = $('#lon').val();

	        plotme(lat, lon);

	    });

	    $('#lon').keyup(function(){
	        var lat = $('#lat').val();
	        var lon = $(this).val();

	        plotme(lat, lon);

	    });
	</script>
@elseif($system == 2)
	<div class="row">
		<div class="col-md-6">
	        <label>X</label><br>
	        <input type="number" name="X" id="X" class="input" placeholder="input value X"><br><br>
	        <label>Z</label><br>
	        <input type="number" name="Z" id="Z" class="input" placeholder="input value Z"><br><br>
	    </div>
	    <div class="col-md-6">
	        <label>Y</label><br>
	        <input type="number" name="Y" id="Y" class="input" placeholder="input value Y"><br><br>
	    </div>
	</div>
@elseif($system == 3)
	<div class="row">
		<div class="col-md-6">
			<label>Zone</label><br>
			<select name="zone" id="zone" class="input">
				@for($i=1;$i<=60;$i++)
					<option value="{{$i}}">Zone {{$i}}</option>
				@endfor
			</select><br><br>
	        <label>Easting (x)</label><br>
	        <input type="number" name="x" id="x" class="input" placeholder="input value easting (x)"><br><br>
	        <label>Height</label><br>
	        <input type="number" name="h_utm" id="h_utm" class="input" placeholder="input value height"><br><br>
	    </div>
	    <div class="col-md-6">
	    	<label>Hemisphere</label><br>
			<select name="hemi" id="hemi" class="input">
				<option value="N">(N) North Hemisphere</option>
				<option value="S">(S) South Hemisphere</option>
			</select><br><br>
	        <label>Northing (y)</label><br>
	        <input type="number" name="y" id="y" class="input" placeholder="input value northing (y)"><br><br>
	    </div>
	</div>
@else
	<div class="row">
		<div class="col-md-6">
	        <label>Easting (x)</label><br>
	        <input type="number" name="x_merc" id="x_merc" class="input" placeholder="input value easting (x)"><br><br>
	        <label>Height</label><br>
	        <input type="number" name="h_merc" id="h_merc" class="input" placeholder="input value height"><br><br>
	    </div>
	    <div class="col-md-6">
	        <label>Northing (y)</label><br>
	        <input type="number" name="y_merc" id="y_merc" class="input" placeholder="input value northing (y)"><br><br>
        	<label>Mercator Meridian Central</label><br>
        	<input type="number" id="lon_init" name="lon_init" min="-180" max="180" class="input" placeholder="default: 0" value="0"><br><br>
	    </div>
	</div>
@endif
