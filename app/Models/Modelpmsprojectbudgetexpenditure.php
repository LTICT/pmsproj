<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsprojectbudgetexpenditure extends Model
{
    const CREATED_AT = 'pbe_create_time';
    const UPDATED_AT = 'pbe_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_project_budget_expenditure';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'pbe_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['pbe_id','pbe_reason','pbe_project_id','pbe_budget_code_id','pbe_used_date_ec','pbe_used_date_gc','ppe_amount','pbe_status','pbe_description','pbe_created_by','pbe_created_date','pbe_create_time','pbe_update_time','pbe_budget_year_id','pbe_budget_month_id'];

    

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

