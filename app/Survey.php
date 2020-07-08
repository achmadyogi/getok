<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $table = 'surveys';
    protected $primaryKey = 'id_survey';

    public function oldDatum(){
        return $this->belongsTo("App\Datum", "id_old_datum", "id_datum");
    }

    public function newDatum(){
        return $this->belongsTo("App\Datum", "id_new_datum", "id_datum");
    }

    public function user(){
        return $this->belongsTo("App\User", "id_user", "id_user");
    }

    public function surveyPoints(){
        return $this->hasMany("App\SurveyPoint", "id_survey", "id_survey");
    }

    public function bursawolf(){
        return $this->hasOne("App\Bursawolfs", "id_survey", "id_survey");
    }

    public function molobas(){
        return $this->hasOne("App\MolodenskyBadekass", "id_survey", "id_survey");
    }
}
