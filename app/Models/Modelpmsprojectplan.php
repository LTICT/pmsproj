<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsprojectplan extends Model
{
    const CREATED_AT = 'pld_create_time';
    const UPDATED_AT = 'pld_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_project_plan';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'pld_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['pld_id','pld_name','pld_project_id','pld_budget_year_id','pld_start_date_ec','pld_start_date_gc','pld_end_date_ec','pld_end_date_gc','pld_description','pld_create_time','pld_update_time','pld_delete_time','pld_created_by','pld_status',];

    

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

