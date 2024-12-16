<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsprojectbudgetplan extends Model
{
    const CREATED_AT = 'bpl_create_time';
    const UPDATED_AT = 'bpl_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_project_budget_plan';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'bpl_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['bpl_id','bpl_project_id','bpl_budget_year_id','bpl_budget_code_id','bpl_amount','bpl_description','bpl_status','bpl_created_by','bpl_created_date','bpl_create_time','bpl_update_time',];

    

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

