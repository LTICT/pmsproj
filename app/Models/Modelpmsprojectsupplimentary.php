<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsprojectsupplimentary extends Model
{
    const CREATED_AT = 'prs_create_time';
    const UPDATED_AT = 'prs_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_project_supplimentary';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'prs_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['prs_id','prs_requested_amount','prs_released_amount','prs_project_id','prs_requested_date_ec','prs_requested_date_gc','prs_released_date_ec','prs_released_date_gc','prs_description','prs_create_time','prs_update_time','prs_delete_time','prs_created_by','prs_status','prs_budget_year_id'];

    

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

