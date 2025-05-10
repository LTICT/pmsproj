<?php
namespace App\Models;
use App\Models\BaseModel;
class Modelpmsbudgetrequest extends BaseModel
{
    const CREATED_AT = 'bdr_create_time';
    const UPDATED_AT = 'bdr_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_budget_request';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'bdr_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['bdr_id','bdr_budget_year_id','bdr_requested_amount',
    'bdr_released_amount','bdr_project_id','bdr_requested_date_ec','bdr_requested_date_gc',
    'bdr_released_date_ec','bdr_released_date_gc','bdr_description','bdr_create_time',
    'bdr_update_time','bdr_delete_time','bdr_created_by','bdr_status','bdr_request_status','bdr_action_remark','bdr_request_category_id','bdr_request_type','bdr_physical_baseline','bdr_financial_baseline','bdr_physical_planned','bdr_physical_approved'];
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

