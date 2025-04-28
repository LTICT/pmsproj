<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsprojectkpiresult extends Model
{
    const CREATED_AT = 'kpr_create_time';
    const UPDATED_AT = 'kpr_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_project_kpi_result';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'kpr_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['kpr_id','kpr_project_id','kpr_project_kpi_id','kpr_year_id','kpr_planned_month_1','kpr_actual_month_1','kpr_planned_month_2','kpr_actual_month_2','kpr_planned_month_3','kpr_actual_month_3','kpr_planned_month_4','kpr_actual_month_4','kpr_planned_month_5','kpr_actual_month_5','kpr_planned_month_6','kpr_actual_month_6','kpr_planned_month_7','kpr_actual_month_7','kpr_planned_month_8','kpr_actual_month_8','kpr_planned_month_9','kpr_actual_month_9','kpr_planned_month_10','kpr_actual_month_10','kpr_planned_month_11','kpr_actual_month_11','kpr_planned_month_12','kpr_actual_month_12','kpr_description','kpr_create_time','kpr_update_time','kpr_delete_time','kpr_created_by','kpr_status',];

    

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

