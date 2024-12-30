<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsbudgetrequesttask extends Model
{
    const CREATED_AT = 'brt_create_time';
    const UPDATED_AT = 'brt_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_budget_request_task';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'brt_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['brt_id','brt_task_name','brt_measurement','brt_budget_request_id','brt_previous_year_physical','brt_previous_year_financial','brt_current_year_physical','brt_current_year_financial','brt_next_year_physical','brt_next_year_financial','brt_description','brt_create_time','brt_update_time','brt_delete_time','brt_created_by','brt_status',];

    

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

