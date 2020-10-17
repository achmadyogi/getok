<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Apps;
use App\Geoid;

class HomeController extends Controller
{
    public function index()
    {
        $apps = Apps::all();
        return view('home', ['apps' => $apps]);
    }

    public function migration(){
        $read = fopen("egm5.csv", "r");
        for($i=0; $i<346802; $i++){
            $line = fgets($read);
            $val = explode(",", preg_split('/\s+/', $line)[0]);
            if($i>=346800 && sizeof($val) >= 3){
                if(Geoid::where(['id'=>$val[0]])->count() == 0){
                    $ha = new Geoid();
                    $ha->id = $val[0];
                    $ha->lat = $val[1];
                    $ha->lon = $val[2];
                    $ha->N = $val[3];
                    $ha->save();
                }
            }
        }
        fclose($read);
    }
}
