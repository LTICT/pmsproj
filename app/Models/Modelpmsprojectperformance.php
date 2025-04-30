<?php
namespace App\Models;
use App\Models\BaseModel;

class Modelpmsprojectperformance extends BaseModel
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
    protected $fillable = ['prp_id','prp_project_id','prp_project_status_id','prp_record_date_ec','prp_record_date_gc','prp_total_budget_used','prp_physical_performance','prp_description','prp_status','prp_created_by','prp_created_date','prp_create_time','prp_update_time','prp_termination_reason_id','prp_budget_year_id','prp_budget_month_id',
        'prp_physical_planned','prp_budget_planned','prp_quarter_id','prp_budget_by_region','prp_physical_by_region','prp_budget_baseline', 'prp_physical_baseline','prp_region_approved',
    'prp_pyhsical_planned_month_1',
    'prp_pyhsical_actual_month_1',
    'prp_pyhsical_planned_month_2',
    'prp_pyhsical_actual_month_2',
    'prp_pyhsical_planned_month_3',
    'prp_pyhsical_actual_month_3',
    'prp_pyhsical_planned_month_4',
    'prp_pyhsical_actual_month_4',
    'prp_pyhsical_planned_month_5',
    'prp_pyhsical_actual_month_5',
    'prp_pyhsical_planned_month_6',
    'prp_pyhsical_actual_month_6',
    'prp_pyhsical_planned_month_7',
    'prp_pyhsical_actual_month_7',
    'prp_pyhsical_planned_month_8',
    'prp_pyhsical_actual_month_8',
    'prp_pyhsical_planned_month_9',
    'prp_pyhsical_actual_month_9',
    'prp_pyhsical_planned_month_10',
    'prp_pyhsical_actual_month_10',
    'prp_pyhsical_planned_month_11',
    'prp_pyhsical_actual_month_11',
    'prp_pyhsical_planned_month_12',
    'prp_pyhsical_actual_month_12',
    'prp_finan_planned_month_1',
    'prp_finan_actual_month_1',
    'prp_finan_planned_month_2',
    'prp_finan_actual_month_2',
    'prp_finan_planned_month_3',
    'prp_finan_actual_month_3',
    'prp_finan_planned_month_4',
    'prp_finan_actual_month_4',
    'prp_finan_planned_month_5',
    'prp_finan_actual_month_5',
    'prp_finan_planned_month_6',
    'prp_finan_actual_month_6',
    'prp_finan_planned_month_7',
    'prp_finan_actual_month_7',
    'prp_finan_planned_month_8',
    'prp_finan_actual_month_8',
    'prp_finan_planned_month_9',
    'prp_finan_actual_month_9',
    'prp_finan_planned_month_10',
    'prp_finan_actual_month_10',
    'prp_finan_planned_month_11',
    'prp_finan_actual_month_11',
    'prp_finan_planned_month_12',
    'prp_finan_actual_month_12',
'prp_status_month_1',
'prp_status_month_2',
'prp_status_month_3',
'prp_status_month_4',
'prp_status_month_5',
'prp_status_month_6',
'prp_status_month_7',
'prp_status_month_8',
'prp_status_month_9',
'prp_status_month_10',
'prp_status_month_11',
'prp_status_month_12'];
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