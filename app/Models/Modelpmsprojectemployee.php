<?php
namespace App\Models;
use App\Models\BaseModel;

class Modelpmsprojectemployee extends BaseModel
{
    const CREATED_AT = 'emp_create_time';
    const UPDATED_AT = 'emp_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_project_employee';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'emp_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['emp_id','emp_id_no','emp_full_name','emp_email','emp_phone_num','emp_role','emp_project_id','emp_start_date_ec','emp_start_date_gc','emp_end_date_ec','emp_end_date_gc','emp_address','emp_description','emp_create_time','emp_update_time','emp_delete_time','emp_created_by','emp_current_status',];

    

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

