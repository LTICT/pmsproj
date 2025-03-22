<?php
namespace App\Models;
use App\Models\BaseModel;
class Modelgendatesetting extends BaseModel
{
    const CREATED_AT = 'dts_create_time';
    const UPDATED_AT = 'dts_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gen_date_setting';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'dts_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['dts_id','dts_parameter_name','dts_parameter_code','dts_start_date','dts_end_date','dts_description','dts_create_time','dts_update_time','dts_delete_time','dts_created_by','dts_status',];

    

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

