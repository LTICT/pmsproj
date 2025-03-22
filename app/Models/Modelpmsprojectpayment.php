<?php
namespace App\Models;
use App\Models\BaseModel;

class Modelpmsprojectpayment extends BaseModel
{
    const CREATED_AT = 'prp_create_time';
    const UPDATED_AT = 'prp_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_project_payment';

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
    protected $fillable = ['prp_id','prp_project_id','prp_type','prp_payment_date_et','prp_payment_date_gc','prp_payment_amount','prp_payment_percentage','prp_description','prp_create_time','prp_update_time','prp_delete_time','prp_created_by','prp_status','prp_budget_year_id'];

    

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

