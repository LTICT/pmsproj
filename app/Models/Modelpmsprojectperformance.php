<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsprojectperformance extends Model
{
    const CREATED_AT = 'prp_create_time';
    const UPDATED_AT = 'prp_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_project_performance';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'prp_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['prp_id','prp_project_id','prp_project_status_id','prp_record_date_ec','prp_record_date_gc','prp_total_budget_used','prp_physical_performance','prp_description','prp_status','prp_created_by','prp_created_date','prp_create_time','prp_update_time','prp_termination_reason_id',];

    

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

