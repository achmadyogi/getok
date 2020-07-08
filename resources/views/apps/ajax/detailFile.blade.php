@if($sys_in == "")

@else
	@if($sys_in == 3)
		<div class="row">
            <div class="col-md-6">
                <label>Zone</label><br>
                <select name="zone" id="zone" class="input">
                    @for($i=1;$i<=60;$i++)
                        <option value="{{$i}}">Zone {{$i}}</option>
                    @endfor
                </select><br><br>
            </div>
            <div class="col-md-6">
                <label>Hemisphere</label><br>
                <select name="hemi" id="hemi" class="input">
                    <option value="N">(N) North Hemisphere</option>
                    <option value="S">(S) South Hemisphere</option>
                </select><br><br>
            </div>
        </div>
	@endif
<div class="row">
    <div class="col-md-6">
        <label>Structure Data</label><br>
        <select name="structure" id="structure" class="input" required>
        	@if($sys_in == 1)
            <option value="1">index lat lon height</option>
            <option value="2">index lon lat height</option>
            <option value="3">index lat lon</option>
            <option value="4">index lon lat</option>
            @elseif($sys_in == 2)
            <option value="5">index X Y Z</option>
            <option value="6">index Y X Z</option>
            @else
            <option value="7">index easting northing height</option>
            <option value="8">index northing easting height</option>
            <option value="9">index easting northing</option>
            <option value="10">index northing easting</option>
            @endif
        </select>
    </div>
    <div class="col-md-6">
        <label>Data Delimiter</label><br>
        <select name="delimiter" id="delimiter" class="input" required>
            <option value="1">Tab delimiter</option>
            <option value="2">Coma delimiter (,)</option>
            <option value="3">Semicolon delimiter (;)</option>
        </select>
    </div>
</div><br>
<input type="hidden" value="" id="lines" name="lines">
	@if($sys_out == 4)
		<div class="row">
			<div class="col-md-6">
				<label>Mercator Meridian Central</label><br>
				<input type="number" name="lon_init" step="any" class="input" value="0" placeholder="default: 0" required><br><br>
			</div>
		</div>
	@else
		<input type="hidden" name="lon_init" value="0">
	@endif

<label>Upload File</label><br>
<input type="file" name="file" id="excelfile">
<button type="submit" class="button-default">Calculate</button><br>
<small><i>Akan ditampilkan sampel data Anda hingga 1000 data.</i></small>
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
		        	var tes, c = 0, i;
		        	document.getElementById('lines').value = lines.length;
			    	var myobj;
			    	var delimiter = document.getElementById("delimiter").value;
			    	var format = document.getElementById("structure").value;
			    	for(i=0; i<lines.length; i++){
			    		c = 0;
			    		if(i <= 1000){
				    		if(delimiter == 1){
				    			myobj = lines[i].match(/\S+/g);
				    			tes = true;
				    			while(tes == true){
				    				if(myobj != null){
				    					if(myobj[c] == null){
					    					c += 1;
					    				}else{
					    					tes = false;
					    				}
				    				}else{
				    					myobj = [0]; // change to array
				    				}
				    			}
				    		}else if(delimiter == 2){
				    			myobj = lines[i].split(',');
				    		}else{
				    			myobj = lines[i].split(';');
				    		}
				    		if(myobj.length >= 3){
					    		if(format == 1 || format == 3){
					    			plotme(myobj[c+1],myobj[c+2]);
					    		}else if(format == 2 || format == 4){
					    			plotme(myobj[c+2],myobj[c+1]);
					    		}else if(format == 5){
					    			var xyz = gs2gd(myobj[c+1], myobj[c+2], myobj[c+3]);
					    			plotme(xyz.lat, xyz.lon);
					    		}else if(format == 6){
					    			var xyz = gs2gd(myobj[c+2], myobj[c+1], myobj[c+3]);
					    			plotme(xyz.lat, xyz.lon);
					    		}else if(format == 7 || format == 9){
					    			var utm = utm2gd(myobj[c+1], myobj[c+2], 0, document.getElementById("zone").value, document.getElementById("hemi").value);
					    			plotme(utm.lat, utm.lon);
					    		}else if(format == 8 || format == 10){
					    			var utm = utm2gd(myobj[c+2], myobj[c+1], 0, document.getElementById("zone").value, document.getElementById("hemi").value);
					    			plotme(utm.lat, utm.lon);
					    		}
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
    $('#structure').change(function(){
    	eksekusi();
    });
    $('#zone').change(function(){
    	eksekusi();
    });
    $('#hemi').change(function(){
    	eksekusi();
    });
</script>

@endif
