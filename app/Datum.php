<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Datum extends Model
{
    protected $table = 'datums';
    protected $primaryKey = 'id_datum';

    public function surveys(){
        return $this->hasMany("App\Survey", "id_old_datum", "id_datum");
    }

    public function ellipsoid(){
        return $this->belongsTo("App\Ellipsoids", "id_ellipsoid", "id_ellipsoid");
    }
}
