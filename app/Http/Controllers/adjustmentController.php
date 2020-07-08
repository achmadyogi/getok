<?php

namespace App\Http\Controllers;

use App\Apps;
use App\Engines\Coordinate\Geocentric;
use App\Engines\DatumTransformation\BursaWolf;
use App\Engines\DatumTransformation\DirectBursaWolf;
use App\Engines\DatumTransformation\MolodenskyBadekas;

class adjustmentController extends Controller
{
    public function index(){

        $dataSet1 = [new Geocentric(-1568774.144, 6170990.681, -371238.249, "93PB-BM.027"),
                     new Geocentric(-1568346.073, 6171177.578, -369946.597, "93PB-BM.028"),
                     new Geocentric(-1577453.001, 6167373.306, -393736.797, "93PB-BM.032")];

        $std1 = [new Geocentric(0.022, 0.002, 0.032, "93PB-BM.027"),
                 new Geocentric(0.012, 0.071, 0.023, "93PB-BM.028"),
                 new Geocentric(0.011, 0.046, 0.177, "93PB-BM.032")];

        $dataSet2 = [new Geocentric(-1568927.100, 6170925.580, -371278.599, "93PB-BM.027"),
                     new Geocentric(-1568499.301, 6171112.432, -369986.900, "93PB-BM.028"),
                     new Geocentric(-1577612.702, 6167305.623, -393784.235, "93PB-BM.032")];

        $std2 = [new Geocentric(0.101, 0.080, 0.099, "93PB-BM.027"),
                 new Geocentric(0.031, 0.232, 0.054, "93PB-BM.028"),
                 new Geocentric(0.042, 0.023, 0.035, "93PB-BM.032")];

        $direct = BursaWolf::Direct($dataSet1, $dataSet2)->combined();
        //$inverse = BursaWolf::Inverse($dataSet1, $direct)->get();

        /*
        for ($i=0; $i<sizeof($inverse); $i++){
            echo json_encode($inverse[$i]->toJsonObject());
        }
        */

        $data = Apps::find(4);
        return view("apps.adjustment", ["data" => $data, "result" => $direct]);
    }
}
