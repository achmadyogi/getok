    <input type="hidden" value="" id="lines" name="lines">
    @if($stat == 1)
        <div class="row">
            <div class="col-md-6">
                <label>Jenis Perhitungan</label><br>
                <select name="cat" id="cat" class="input">
                    <option value="1">Direct</option>
                    <option value="2">Inverse</option>
                </select><br><br>
                <div id="cat_details"></div>
                <script type="text/javascript">
                    $('#cat').change(function(){
                        if($(this).val() == "1"){
                            $('#cat_details').empty();
                        }else{
                            $('#cat_details').html('\
                            <label>Bursa Wolf Parameter</label><br>\
                            dx: <input type="number" name="dx" step="any" class="input" placeholder="dx" style="width: 150px;" required><br><br>\
                            dy: <input type="number" name="dy" step="any" class="input" placeholder="dy" style="width: 150px;" required><br><br>\
                            dz: <input type="number" name="dz" step="any" class="input" placeholder="dz" style="width: 150px;" required><br><br>\
                            ex: <input type="number" name="ex" step="any" class="input" placeholder="ex" style="width: 150px;" required><br><br>\
                            ey: <input type="number" name="ey" step="any" class="input" placeholder="ey" style="width: 150px;" required><br><br>\
                            ez: <input type="number" name="ez" step="any" class="input" placeholder="ez" style="width: 150px;" required><br><br>\
                            ds: <input type="number" name="ds" step="any" class="input" placeholder="ds" style="width: 150px;" required><br><br>\
                            ')
                        }
                    })
                </script>
                <label>File Delimiter</label><br>
                <select name="delimiter" id="delimiter" class="input">
                    <option value="1">Tab</option>
                    <option value="2">Koma (,)</option>
                    <option value="3">Titik koma (;)</option>
                </select><br><br>
            </div>
            <div class="col-md-6">
                <label>Metode Statistik</label><br>
                <select name="stat" id="stat" class="input" required>
                    <option value="1">Parametric Least Square</option>
                </select><br><br>
            </div>
        </div>
        <label>Upload File</label><br>
        <input type="file" name="file" id="excelfile">
        <button type="submit" class="button-default">Calculate</button><br>
        <small><i>format: ID|X1|Y1|Z1|X2|Y2|Z2</i></small>
    @elseif($stat == 2)
        <div class="row">
            <div class="col-md-6">
                <label>Jenis Perhitungan</label><br>
                <select name="cat" id="cat" class="input">
                    <option value="1">Standard</option>
                    <option value="2">Abridge</option>
                </select><br><br>
            </div>
            <div class="col-md-6">
                <label>File Delimiter</label><br>
                <select name="delimiter" id="delimiter" class="input">
                    <option value="1">Tab</option>
                    <option value="2">Koma (,)</option>
                    <option value="3">Titik koma (;)</option>
                </select><br><br>
            </div>
        </div>
        <label>Upload File</label><br>
        <input type="file" name="file" id="excelfile">
        <button type="submit" class="button-default">Calculate</button><br>
        <small><i>format: ID|X1|Y1|Z1|X2|Y2|Z2</i></small>
    @else
        <div class="row">
            <div class="col-md-6">
                <label>Jenis Perhitungan</label><br>
                <select name="cat" id="cat" class="input">
                    <option value="1">Direct</option>
                    <option value="2">Inverse</option>
                </select><br><br>
                <label>File Delimiter</label><br>
                <select name="delimiter" id="delimiter" class="input">
                    <option value="1">Tab</option>
                    <option value="2">Koma (,)</option>
                    <option value="3">Titik koma (;)</option>
                </select><br><br>
            </div>
            <div class="col-md-6">
                <label>Metode Statistik</label><br>
                <select name="stat" id="stat" class="input">
                    <option value="1">Parametric Least Square</option>
                </select><br><br>
                <label>Centroid Model</label><br>
                <select name="centroid" id="centroid" class="input">
                    <option value="ARITHMETIC">Arithmetic Mean</option>
                    <option value="GEOMETRIC">Geometric Mean</option>
                    <option value="HARMONIC">Harmonic Mean</option>
                    <option value="QUADRATIC">Quadratic Mean</option>
                    <option value="MEDIAN">Median</option>
                </select><br><br>
            </div>
        </div>
        <label>Upload File</label><br>
        <input type="file" name="file" id="excelfile">
        <button type="submit" class="button-default">Calculate</button><br>
        <small><i>format: ID|X1|Y1|Z1|X2|Y2|Z2</i></small>
    @endif

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
                var xyz, xyz2;

                // If we use onloadend, we need to check the readyState.
                reader.onloadend = function(evt) {
                    if (evt.target.readyState == FileReader.DONE) {
                        var konten = evt.target.result;
                        var lines = konten.split('\n');
                        document.getElementById('lines').value = lines.length;
                        var myobj;
                        var tes, c = 0, i;
                        var delimiter = document.getElementById("delimiter").value;
                        for(i=0; i<lines.length; i++){
                            c = 0;
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
                                xyz = gs2gd(myobj[c+1], myobj[c+2], myobj[c+3]);
                                xyz2 = gs2gd(myobj[c+4], myobj[c+5], myobj[c+6]);
                                plotme(xyz.lat, xyz.lon);
                                plotme2(xyz2.lat, xyz2.lon);
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
    </script>
