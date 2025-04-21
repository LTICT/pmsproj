<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsmonitoringevaluationtype extends Model
{
    const CREATED_AT = 'met_create_time';
    const UPDATED_AT = 'met_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_monitoring_evaluation_type';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'met_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['met_id','met_name_or','met_name_am','met_name_en','met_code','met_description','met_create_time','met_update_time','met_delete_time','met_created_by','met_status','met_gov_active','met_cso_active','met_monitoring_active','met_evaluation_active',];

    

    /**
     * Change activity log event description
     *
     * @param string $eventName
     *
     * @return string
     */
    public function getDescriptionForEvent($eventName)
    {
        return __CLASS__ . " model has been {$eventName}";
    }
}

