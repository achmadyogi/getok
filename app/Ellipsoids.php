<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ellipsoids extends Model
{
    protected $table = 'ellipsoids';
    protected $primaryKey = 'id_ellipsoid';

    public function datum(){
        return $this->hasMany("App\Datum", "id_ellipsoid", "id_ellipsoid");
    }
}
