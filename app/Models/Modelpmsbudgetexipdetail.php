<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsbudgetexipdetail extends Model
{
    const CREATED_AT = 'bed_create_time';
    const UPDATED_AT = 'bed_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_budget_exip_detail';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'bed_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['bed_id','bed_budget_expenditure_id','bed_budget_expenditure_code_id','bed_amount','bed_description','bed_create_time','bed_update_time','bed_delete_time','bed_created_by','bed_status',];

    

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

