<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Apps;
use App\Transaction;
use Prj;
use Geo;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Geoid;
use Session;

class geoidController extends Controller
{
    public function index(){
    	$data = Apps::find(3);
    	$json = DB::select("SELECT * FROM geoid WHERE id%50 = 0");
    	$max = (int)Geoid::max('N');
    	$min = (int)Geoid::min('N');
    	return view('apps.geoid', ['data' => $data, 'json' => $json, 'max' => $max, 'min' => $min]);
    }

    public function geoidPerPoint(Request $request){
    	$N = Geo::Bilinear($request->lat, $request->lon);

    	return response()->json(['N' => $N]);
    }

    public function geoidInFile(Request $request){
        // simpan file
        $uploadFile = $request->file('file');
        $path = $uploadFile->store('public/geoid_file');

        if(Transaction::max('id_transaksi') == NULL){
            $id = 1;
        }else{
            $id = Transaction::max('id_transaksi') + 1;
        }
        $d = new Transaction();
        $d->id_transaksi = $id;
        $d->id_app = 3;
        $d->is_active = 1;
        $d->file = $path;
        $d->is_finished = 0;
        $d->save();

        // reading lines
        $amount = $request->lines;
        $file = Storage::url(Transaction::find($id)->file);
        Session::flash('format', $request->format);
        Session::flash('delimiter', $request->delimiter);
        Session::flash('init', true);
        Session::flash('prog', 'Reading data...');
        Session::flash('bar', 0);
        Session::flash('id_transaksi', $id);
        Session::flash('lines', $amount);
        Session::flash('file', $file);
        return redirect()->back();
    }

    public function calcGeoid(Request $request){
        // Reading data
        $read = fopen("../storage/app/".Transaction::find($request->id_transaksi)->file, "r");

        if($request->awal == 1){
            $write = fopen("../storage/app/public/geoid_result/id_".$request->id_transaksi.".txt", "w");
            $header =  "=========================================================================================\n";
            $header .= "                                 GEOID UNDULATION VALUES                                 \n";
            $header .= "=========================================================================================\n\n";
            $header .= "Datum\t\t: WGS84\n";
            $header .= "-----------------------------------------------------------------------------------------\n";
            $header .= " ID \t\t lat \t\t lon \t\t\t N (meter)\n";
            $header .= "-----------------------------------------------------------------------------------------\n";
            fwrite($write, $header);
            fclose($write);
        }

        $write = fopen("../storage/app/public/geoid_result/id_".$request->id_transaksi.".txt", "a");

        // transformation and create new file;
        $c = 1; $b = 1;

        while($b < $request->akhir) {
            if($b>=$request->awal){
                $line = fgets($read);
                if($request->delimiter == 1){
                    $val = preg_split("/\s+/", $line);
                }elseif($request->delimiter == 2){
                    $val = explode(",", preg_split('/\s+/', $line)[0]);
                }else{
                    $val = explode(";", preg_split('/\s+/', $line)[0]);
                }
                if(sizeof($val) >= 3){
                    if(is_numeric($val[0])){
                        if($request->format == 1){ // id lat lon
                            $N = Geo::Bilinear($val[1], $val[2]);
                            fwrite($write, " ".$val[0]."\t\t".$val[1]."\t\t".$val[2]."\t\t".$N."\n");
                        }elseif($request->format == 2){ // id lon lat
                            $N = Geo::Bilinear($val[2], $val[1]);
                            fwrite($write, " ".$val[0]."\t\t".$val[2]."\t\t".$val[1]."\t\t".$N."\n");
                        }elseif($request->format == 3){ // lat lon
                            $N = Geo::Bilinear($val[0], $val[1]);
                            fwrite($write, " ".$c."\t\t".$val[0]."\t\t".$val[1]."\t\t".$N."\n");
                            $c += 1;
                        }else{ // lon lat
                            $N = Geo::Bilinear($val[1], $val[0]);
                            fwrite($write, " ".$c."\t\t".$val[1]."\t\t".$val[0]."\t\t".$N."\n");
                            $c += 1;
                        }
                    }
                    $b += 1;
                }else{
                    $b += 1;
                }
            }else{
                fgets($read);
                $b += 1;
            }
        }

        fclose($write);

        fclose($read);
        return response()->json(['prog' => "Processing geoid...", 'bar' => (int)((($b)/$request->lines)*100)]);
    }
}
