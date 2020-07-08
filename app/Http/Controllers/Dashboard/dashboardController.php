<?php

namespace App\Http\Controllers\Dashboard;

use App\Datum;
use App\Ellipsoids;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\NewUser;
use Session;
use Illuminate\Support\Str;
use App\Mail\addUser;
use Illuminate\Support\Facades\Mail;

class dashboardController extends Controller
{
    public function index(){
    	Session::flash('menu', 'dashboard');
    	return view('dashboard.index');
    }

    public function userSetting(){
    	$user = User::paginate(10);
    	Session::flash('menu', 'user-setting');
    	return view('dashboard.user-setting', ['user' => $user]);
    }

    public function adduser(Request $request){
        $rand = Str::random(8);

        $id = NewUser::max('id') + 1;

    	$usr = new NewUser();
        $usr->id = $id;
        $usr->email = $request->email;
        $usr->verification_code = $rand;
        $usr->is_active = 1;
        $usr->save();

        Mail::to($request->email)->send(new addUser($usr));

        Session::flash('alert-success', 'Message has sent!');
        return redirect()->back();
    }

    public function activation($code){
        if(NewUser::where(['verification_code' => $code, 'is_active' => 1])->count() > 0){
            $email = NewUser::where(['verification_code' => $code, 'is_active' => 1])->first()->email;
            $go = 1;
        }else{
            $email = "null";
            $go = 0;
        }

        return view('dashboard.activation', ['email' => $email, 'go' => $go, 'code' => $code]);
    }

    public function newUser(Request $request){
        $u = new User();
        $u->email = $request->email;
        $u->name = $request->name;
        $u->username = $request->username;
        $u->password = bcrypt($request->password);
        $u->position = $request->position;
        $u->save();

        NewUser::where(['verification_code' => $request->code])->update(['is_active' => 0]);

        return redirect()->route('index');
    }

    public function deleteUser(Request $request){
        User::find($request->id)->delete();

        Session::flash('alert-success', 'User has been deleted!');

        return redirect()->back();
    }

    public function ellipsoidList(){
        Session::flash('menu', 'ellipsoid-list');
        $ellipsoids = Ellipsoids::orderBy('ellipsoid_name', 'ASC')->paginate(10);
        return view('dashboard.ellipsoid', ['ellipsoids' => $ellipsoids]);
    }

    public function addEllipsoid(Request $request){
        $d = new Ellipsoids();
        $d->year = $request->year;
        $d->ellipsoid_name = $request->ellipsoid;
        $d->a = $request->a;
        $d->b = $request->b;
        $d->f = $request->f;
        $d->save();

        Session::flash('alert-success', 'An ellipsoid has successfully been added.');
        return redirect()->back();
    }

    public function editEllipsoid(Request $request){
        $d = Ellipsoids::find($request->id);
        $d->year = $request->year;
        $d->ellipsoid_name = $request->ellipsoid;
        $d->a = $request->a;
        $d->b = $request->b;
        $d->f = $request->f;
        $d->save();

        Session::flash('alert-success', 'An ellipsoid has successfully been editted.');
        return redirect()->back();
    }

    public function deleteEllipsoid(Request $request){
        Ellipsoids::find($request->id)->delete();

        Session::flash('alert-success', 'Ellipsoid has been deleted!');

        return redirect()->back();
    }

    public function datumList(){
        Session::flash('menu', 'datum-list');
        $datums = Datum::orderBy('datum_name', 'ASC')->paginate(10);
        $ellipsoids = Ellipsoids::all();
        return view('dashboard.datum', ['ellipsoids' => $ellipsoids, 'datums' => $datums]);
    }

    public function addDatum(Request $request){
        $d = new Datum();
        $d->datum_name = $request->datum;
        $d->id_ellipsoid = $request->ellipsoid;
        $d->save();

        Session::flash('alert-success', 'A datum has successfully been added.');
        return redirect()->back();
    }

    public function editDatum(Request $request){
        $d = Datum::find($request->id);
        $d->datum_name = $request->datum;
        $d->id_ellipsoid = $request->ellipsoid;
        $d->save();

        Session::flash('alert-success', 'A datum has successfully been editted.');
        return redirect()->back();
    }

    public function deleteDatum(Request $request){
        Datum::find($request->id)->delete();

        Session::flash('alert-success', 'Datum has been deleted!');

        return redirect()->back();
    }
}
