@extends('layouts.app')

@section('title', 'Datum List')

@section('content')
<br><br>
<script>
    $(document).ready(function(){
    	$('.toast').toast('hide');
        get_rnx();
    });
    function get_rnx(){
        $.get('/get_rnx',{load_rnx:1},function(data){
            var data=JSON.parse(data);
            var a='';
            for(var i=0;i<data.length;i++)
                a+='<li class="list-group-item">'+data[i]+'</li>';
            $('#rnx').html(a);
        });
    }
    function new_calc(){
        $.get('/get_rnx',{newcalc:1},function(data){
            get_rnx();
        });
    }
    function calc(){
        var stasiun=$('#stasiun').val();
        var x=$('#x').val();
        var y=$('#y').val();
        var z=$('#z').val();
        var mode=$('#mode').val();
        var data=$('#data').val();
        var elev=$('#elev').val();
        var lengkap=!((stasiun=='')||(x=='')||(y=='')||(z=='')||(mode=='')||(data=='')||(elev==''))
        if(!lengkap){
            $('.toast').toast('show');
        }else{
            $('#spinner').show();
            $.post('/get_rnx',{_token:"{{csrf_token()}}",stasiun:stasiun,x:x,y:y,z:z,mode:mode,data:data,elev:elev,user:"{{Auth::user()->name}}"},function(data){$('#spinner').hide();
            	//alert(data);
                if(data=='ok'){
                    document.location='{{route("get_rnx")}}?download=1';
                    $('#spinner').hide();
                }else{
                	$('.toast-body').html(data);
                    $('.toast').toast('hide');
                }
            });
        }
    }
	function new_upload(){
		$('#filelist_upload').html('');
		$('#console').html('');
	}
</script>
<div class="row">
    <div class="col-md-12">
        <div class="jumbotron" style="background-color: white">
			<div class="row">
				<div class="col-12 mb-4">
					<div class=" d-flex justify-content-center">
						<div class="btn-group" role="group" aria-label="Basic example">
							<label class="btn btn-primary btn-rounded" onclick="new_calc()">Data Baru</label>
	                        <label for="" onclick="new_upload()" data-toggle="modal" data-target="#uploads" class="btn btn-primary btn-rounded">Unggah RNX</label>
	                        <label class="btn btn-primary btn-rounded" onclick="calc()">Hitung</label>
	                    </div>
	                </div>
	                <div class="col-12 d-flex justify-content-center">
	                    <div class="toast" style="position: absolute; z-index: 999999;background-color: red">
							<div class="toast-header">Error!</div>
							<div class="toast-body text-white">Silahkan lengkapi form data yang disajikan!</div>
						</div>
					</div>
	            </div>
	            <div class="col-12">
	            	<div id="spinner" style="display:none">
	                	<div class="d-flex justify-content-center">
	                    	<div class="spinner-border" role="status">
	                        	<span class="sr-only">Loading...</span>
	                        </div>
	                    </div>
	                </div>
	            </div>
	           	<div class="col-lg-6 col-md-6 border-right">
	            	<h5>RNX</h5>
	                <ul class="list-group" id="rnx"></ul>
				</div>
	            <div class="col-lg-6 col-md-6">
	            	<div class="form-group input-group input-group-md my-3">
						<div class="input-group-prepend" style="width: 150px">
							<span class="input-group-text" style="width: 150px">Mode</span>
						</div>
						<select class="form-control" id="mode">
							<option value="STATIK">STATIK</option>
							<option value="KINEMATIK">KINEMATIK</option>
						</select>
					</div>
	                <div class="form-group input-group input-group-md my-3">
						<div class="input-group-prepend" style="width: 150px">
							<span class="input-group-text" style="width: 150px">Data</span>
						</div>
						<select class="form-control" id="data">
							<option value="1" selected>1</option>
							<option value="2">2</option>
							<option value="3">3</option>
						</select>
					</div>
	                <!-- Ref Stasiun -->
	                <div class="input-group input-group-md my-3">
						<div class="input-group-prepend" style="width: 150px">
							<span class="input-group-text" style="width: 150px">Referens Station</span>
						</div>
						<input type="text" class="form-control" id="stasiun" placeholder="Referns Stats" value="DIKB">
					</div>
	                <!-- X -->
	                <div class="input-group input-group-md my-3">
						<div class="input-group-prepend" style="width: 150px">
							<span class="input-group-text" style="width: 150px">X</span>
						</div>
						<input type="text" class="form-control" id="x" placeholder="0" value="-1837744.4218">
					</div>
	                <!-- Y -->
					<div class="input-group input-group-md my-3">
						<div class="input-group-prepend" style="width: 150px">
							<span class="input-group-text" style="width: 150px">Y</span>
						</div>
						<input type="text" class="form-control" id="y" placeholder="0" value="6069936.1191">
					</div>
	                <!-- Z -->
	                <div class="input-group input-group-md my-3">
						<div class="input-group-prepend" style="width: 150px">
							<span class="input-group-text" style="width: 150px">Z</span>
						</div>
						<input type="text" class="form-control" id="z" placeholder="0" value="-675547.7296">
					</div>
	                <!-- ELEV -->
	                <div class="input-group input-group-md my-3">
						<div class="input-group-prepend" style="width: 150px">
							<span class="input-group-text" style="width: 150px">Elevasi</span>
						</div>
						<input type="text" class="form-control" id="elev" placeholder="0" value="15">
					</div>
	            </div>
			</div>
        </div>
    </div>
</div>

<div class="modal fade" id="uploads" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-notify modal-info" role="document">
    	<!--Content-->
        <div class="modal-content">
            <!--Body-->
            <div class="modal-body">
				<script type="text/javascript" src="/plupload/js/plupload.full.min.js"></script>
				<div id="container">
					<button class="btn btn-success" id="pickfiles" >Browse File</button>
				</div>
				<div id="filelist_upload">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
				<pre id="console"></pre>
				<script type="text/javascript">
					// Custom example logic
                    function pre_upload(){
                    	$.get(DT,{upload:1},function(data){
                    		new_upload();
                    	})
                    }
					var uploader = new plupload.Uploader({
						runtimes : 'html5,flash,silverlight,html4',
						browse_button : 'pickfiles', // you can pass an id...
						container: document.getElementById('container'), // ... or DOM Element itself
						url : '/get_rnx',//'uploads.php',
						flash_swf_url : '/plupload/js/Moxie.swf',
						silverlight_xap_url : '/plupload/js/Moxie.xap',
						multipart_params:{
							_token:"{{csrf_token()}}"
						},
						filters : {
							max_file_size : '200mb',
							mime_types: [{title : "RNX", extensions : "20o"}]
						},

						init: {
							PostInit: function() {
								document.getElementById('filelist_upload').innerHTML = '';
							},

							FilesAdded: function(up, files) {
								plupload.each(files, function(file) {
									document.getElementById('filelist_upload').innerHTML += '<h5>' + file.name + ' <span>(' + plupload.formatSize(file.size) + ')</span><b style="float:right" id="a' + file.id + '">0%</b></h5><div class="progress"> <div id="b' + file.id + '" class="progress-bar bg grey darken-3" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></div>';
									uploader.start();
								});
							},

                            FileUploaded: function(up, file, info) {
                            	// Called when file has finished uploading
                                //console.log('[FileUploaded] File:', file, "Info:", info);
                            },
        
                            UploadComplete: function(up, files) {
                            	//$('#uploads').hide();
                                get_rnx();
                                $('#uploads').modal('hide');
                            },

							UploadProgress: function(up, file) {
								//document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
								$('#a'+file.id).html(file.percent + "%");
								$('#b'+file.id).attr("aria-valuenow",file.percent).css({width:file.percent+'%'});
							},

							Error: function(up, err) {
								document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
								}
							}
						});

						uploader.init();

				</script>

			</div>
		</div>
        <!--/.Content-->
    </div>
</div>

@endsection
