<?php

namespace App\Http\Controllers;

use App\Apps;
use App\CoordinateSystem;
use App\Datum;
use App\Engines\Coordinate\Simple3D;
use App\Survey;
use App\SurveyPoint;
use App\Transaction;
use App\Usecase\DatumTransformation;
use Illuminate\Http\Request;
use App\Engines\DatumTransformation\BursaWolf;
use App\Engines\Coordinate\Geocentric;
use Illuminate\Support\Facades\Auth;
use Session;

class transformasiDatumController extends Controller
{
    public function index(){
        $data = Apps::find(5);
        $survey = Survey::paginate(10);
        $datum = Datum::all();
    	return view('apps.datumTransformation.SurveyList', ['data' => $data, 'survey' => $survey, 'datum' => $datum]);
    }

    public function addSurvey(Request $request){
        if($request->old_datum == $request->new_datum){
            Session::flash('alert-failed', 'Old datum and new datum cannot be the same.');
            return redirect()->back();
        }

        $s = new Survey();
        $s->survey_name = $request->survey_name;
        $s->survey_date = $request->survey_date;
        $s->id_old_datum = $request->old_datum;
        $s->id_new_datum = $request->new_datum;
        $s->id_user = Auth::user()->id_user;
        $s->description = $request->description;
        $s->save();

        Session::flash('alert-success', 'A new survey has successfully been created.');
        return redirect()->back();
    }

    public function datumCalculation($idSurvey){
        $data = Apps::find(5);
        $points = Survey::find($idSurvey)->surveyPoints;
        $bursawolf = Survey::find($idSurvey)->bursawolf;
        $molobas = Survey::find($idSurvey)->molobas;
        $coordinate = CoordinateSystem::all();
        if(Survey::where(['id_survey' => $idSurvey])->count() == 0){
            Session::flash('alert-failed', 'Survey is not available');
            return redirect()->back();
        }
        $survey = Survey::find($idSurvey);
        return view("apps.datumTransformation.DatumCalculation", ['points' => $points,
                                                                        'data' => $data,
                                                                        'survey' => $survey,
                                                                        'coordinate' => $coordinate,
                                                                        'bursawolf' => $bursawolf,
                                                                        'molobas' => $molobas]);
    }

    public function uploadPoints(Request $request){
        $uploadFile = $request->file('file');
        $path = $uploadFile->store('public/transformasi_datum');

        $survey = Survey::find($request->idSurvey);
        $survey->file = $path;
        $survey->save();

        $savePoint = DatumTransformation::ReadDataPoints($request->idSurvey, $request->coordinate, $request->structure,
                                                        $request->delimiter, $request->hemi, $request->zone, $request->meridianCentral);

        if($savePoint == false){
            Session::flash('alert-failed', 'Please insert at minimum three points.');
            return redirect()->back();
        }

        Session::flash('alert-success', 'Upload is successfull.');
        return redirect()->back();
    }

    public function pointUseEdit(Request $request){
        $p = SurveyPoint::find($request->idPoint);
        $p->is_used = $request->isUsed;
        $p->save();

        $idSurvey = SurveyPoint::find($request->idPoint)->id_survey;
        $points = Survey::find($idSurvey)->surveyPoints;

        return view("apps.datumTransformation.ajax.SurveyPoints", ['points' => $points]);

    }

    public function calculateDatumNow(Request $request){
        $points = Survey::find($request->idSurvey)->surveyPoints;
        $amount = 0;
        foreach ($points as $p){
            if($p->is_used == 1){
                $amount += 1;
            }
        }

        if($amount < 3){
            Session::flash("alert-failed", "You need at minimum three points to continue.");
            return redirect()->back();
        }

        DatumTransformation::calculateBursaWolf($request->idSurvey);
        DatumTransformation::calculateMolodenskyBadekas($request->idSurvey);

        $setFinish = Survey::find($request->idSurvey);
        $setFinish->is_calculated = 1;
        $setFinish->save();

        Session::flash("alert-success", "Datum calculation is completed. See the detail list below the data points.");
        return redirect()->back();

    }

    public function methodSelection(Request $request){
        return view('apps.ajax.datumTransMethodSelection', ['stat' => $request->statistic]);
    }

    public function submitFile(Request $request){
        // simpan file
        $uploadFile = $request->file('file');
        $path = $uploadFile->store('public/transformasi_datum');

        if(Transaction::max('id_transaksi') == NULL){
            $id = 1;
        }else{
            $id = Transaction::max('id_transaksi') + 1;
        }
        $d = new Transaction();
        $d->id_transaksi = $id;
        $d->id_app = 5;
        $d->is_active = 1;
        $d->file = $path;
        $d->is_finished = 0;
        $d->save();

        Session::flash('datum_lama', $request->datum_lama);
        Session::flash('datum_baru', $request->datum_baru);
        Session::flash('statistic', $request->statistic);
        Session::flash('cat', $request->cat);
        Session::flash('stat', $request->stat);
        Session::flash('delimiter', $request->delimiter);
        Session::flash('id_transaksi', $id);
        Session::flash('init', true);
        Session::flash('lines', $request->lines);
        Session::flash('dx', $request->dx);
        Session::flash('dy', $request->dy);
        Session::flash('dz', $request->dz);
        Session::flash('ex', $request->ex);
        Session::flash('ey', $request->ey);
        Session::flash('ez', $request->ez);
        Session::flash('ds', $request->ds);
        return redirect()->back();
    }

    public function calculate(Request $request){
        $lama = [];
        $baru = [];

        // Reading data
        $read = fopen("../storage/app/".Transaction::find($request->id_transaksi)->file, "r");

        // Writing header
        $write = fopen("../storage/app/public/transformasi_datum_result/id_".$request->id_transaksi.".txt", "w");
        $header =  "=========================================================================================\n";
        $header .= "                                   TRANSFORMASI DATUM                                    \n";
        $header .= "=========================================================================================\n\n";
        $header .= "CONFIGURATION\n";
        $header .= "Datum Lama\t\t: ".Datum::find($request->datum_lama)->datum_name."\n";
        $header .= "Datum Baru\t\t: ".Datum::find($request->datum_baru)->datum_name."\n";
        if($request->statistic == 1){
            $header .= "Metode Transformasi\t: Bursa Wolf\n";
            if($request->cat == 1){
                $header .= "Jenis Perhitungan\t: Direct\n";
            }else{
                $header .= "Jenis Perhitungan\t: Inverse\n";
            }
            if($request->stat == 1){
                $header .= "Metode Statistik\t: Parametric Least Square\n";
            }else{
                $header .= "Metode Statistik\t: Combined Least Square\n";
            }
        }elseif($request->statistic == 2){
            $header .= "Metode Transformasi\t: Molodensky\n";
            if($request->cat == 1){
                $header .= "Jenis Perhitungan\t: Standard Molodensky\n";
            }else{
                $header .= "Jenis Perhitungan\t: Abridge Molodensky\n";
            }
        }elseif($request->statistic == 3){
            $header .= "Metode Transformasi\t: Molodensky Badekas\n";
            if($request->cat == 1){
                $header .= "Jenis Perhitungan\t: Direct\n";
            }else{
                $header .= "Jenis Perhitungan\t: Inverse\n";
            }
            if($request->stat == 1){
                $header .= "Metode Statistik\t: Parametric Least Square\n";
            }else{
                $header .= "Metode Statistik\t: Combined Least Square\n";
            }
            $header .= "Centroid Model\t: ".$request->centroid."\n\n\n";
        }

        $header .= "Daftar titik yang akan ditransformasi\n";
        $header .= "-----------------------------------------------------------------------------------------\n";
        $header .= " ID \t\t\t X1 \t\t\t Y1 \t\t\t Z1 \t\t\t X2 \t\t\t Y2 \t\t\t Z2 \n";
        $header .= "-----------------------------------------------------------------------------------------\n";

        $message = ""; $counter = 0;
        for($i=0; $i<$request->lines; $i++){
            $line = fgets($read);
            if($request->delimiter == 1){
                $val = preg_split("/\s+/", $line);
            }elseif($request->delimiter == 2){
                $val = explode(",", preg_split('/\s+/', $line)[0]);
            }else{
                $val = explode(";", preg_split('/\s+/', $line)[0]);
            }
            if(sizeof($val) >= 3) {
                $nulltest = true;
                $c = 0;
                while ($nulltest == true) {
                    if ($val[$c] == null) {
                        $c += 1;
                    } else {
                        $nulltest = false;
                    }
                }
                if(is_numeric($val[$c+1]) && is_numeric($val[$c+2]) && is_numeric($val[$c+3])){
                    if($request->cat == 1){
                        $header .= " " . $val[$c] . "\t\t" . $val[$c+1] . "\t\t" . $val[$c+2] . "\t\t" . $val[$c+3] . "\t\t" . $val[$c+4] . "\t\t" . $val[$c+5] . "\t\t" . $val[$c+6] . "\n";
                    }else{
                        $header .= " " . $val[$c] . "\t\t" . $val[$c+1] . "\t\t" . $val[$c+2] . "\t\t" . $val[$c+3] . "\n";
                    }
                    $gd_lama = new Geocentric($request->datum_lama);
                    $gd_lama->setCoordinateWithId($val[$c], $val[$c+1], $val[$c+2], $val[$c+3]);
                    if($request->cat == 1){
                        $gd_baru = new Geocentric($request->datum_baru);
                        $gd_baru->setCoordinateWithId($val[$c], $val[$c+4], $val[$c+5], $val[$c+6]);
                        $baru[$counter] = $gd_baru;
                    }
                    $lama[$counter] = $gd_lama;
                    $counter += 1;
                }
            }
        }

        $header .= "-----------------------------------------------------------------------------------------\n\n";
        $header .= "RESULTS\n";
        // Calculate transformation
        if($request->statistic == 1){
            if($counter >= 2){
                $message = "Calculation is successful";
                if($request->cat == 1){
                    if($request->stat == 1){
                        $bf = BursaWolf::Direct($lama, $baru)->directParametric();
                    }else{
                        $bf = BursaWolf::Direct($lama, $baru)->directQuadratic();
                    }
                    $header .= "Parameter Transformasi: \n";
                    $header .= "dx = ".$bf['translationParams']->getX()."\n";
                    $header .= "dy = ".$bf['translationParams']->getY()."\n";
                    $header .= "dz = ".$bf['translationParams']->getZ()."\n";
                    $header .= "ex = ".$bf['rotationParams']->getX()."\n";
                    $header .= "ey = ".$bf['rotationParams']->getY()."\n";
                    $header .= "ez = ".$bf['rotationParams']->getZ()."\n";
                    $header .= "ds = ".$bf['scaleParam']."\n";
                    $header .= "-----------------------------------------------------------------------------------------\n\n";
                }else{
                    $translationParams = new Simple3D($request->dx, $request->dy, $request->dz);
                    $rotationParams = new Simple3D($request->ex, $request->ey, $request->ez);
                    $scaleParam = $request->ds;
                    if($request->stat == 1){
                        $bf = BursaWolf::Inverse($lama, $translationParams, $rotationParams, $scaleParam)->inverseParametric();
                    }else{
                        $bf = BursaWolf::Inverse($lama, $translationParams, $rotationParams, $scaleParam)->inverseQuadratic();
                    }
                    $header .= "List Koordinat Baru: \n";
                    $header .= "-----------------------------------------------------------------------------------------\n";
                    $header .= " ID \t\t\t X2 \t\t\t Y2 \t\t\t Z2 \n";
                    $header .= "-----------------------------------------------------------------------------------------\n";
                    for($i=0;$i<count($bf); $i++){
                        $header .= " " . $bf[$i]->getId() . "\t\t" . $bf[$i]->getX() . "\t\t" . $bf[$i]->getY() . "\t\t" . $bf[$i]->getZ() . "\n";
                    }
                }
            }else{
                $message = "Your points are not enough to perform matrix operations.";
            }
        }elseif($request->statistic == 2){
            $message = "Calculation is successful";
        }elseif($request->statistic == 3){
            if($counter >= 2){
                $message = "Calculation is successful";
            }else{
                $message = "Your points are not enough to perform matrix operations.";
            }
        }
        fwrite($write, $header);
        fclose($write);

        return response()->json(['message' => $message]);
    }
}
