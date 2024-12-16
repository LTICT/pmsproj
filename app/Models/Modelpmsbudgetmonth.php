<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsbudgetmonth extends Model
{
    const CREATED_AT = 'bdm_create_time';
    const UPDATED_AT = 'bdm_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_budget_month';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'bdm_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['bdm_id','bdm_month','bdm_name_or','bdm_name_am','bdm_name_en','bdm_code','bdm_description','bdm_create_time','bdm_update_time','bdm_delete_time','bdm_created_by','bdm_status',];

    

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

