<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsprojecthandover extends Model
{
    const CREATED_AT = 'prh_create_time';
    const UPDATED_AT = 'prh_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_project_handover';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'prh_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['prh_id','prh_project_id','prh_handover_date_ec','prh_handover_date_gc','prh_description','prh_create_time','prh_update_time','prh_delete_time','prh_created_by','prh_status','prh_budget_year_id'];

    

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

