<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsprojectbudgetsource extends Model
{
    const CREATED_AT = 'bsr_create_time';
    const UPDATED_AT = 'bsr_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_project_budget_source';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'bsr_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['bsr_id','bsr_name','bsr_project_id','bsr_budget_source_id','bsr_amount','bsr_status','bsr_description','bsr_created_by','bsr_created_date','bsr_create_time','bsr_update_time',];

    

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

