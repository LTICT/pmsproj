<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsbudgetsource extends Model
{
    const CREATED_AT = 'pbs_create_time';
    const UPDATED_AT = 'pbs_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_budget_source';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'pbs_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['pbs_id','pbs_name_or','pbs_name_am','pbs_name_en','pbs_code','pbs_description','pbs_create_time','pbs_update_time','pbs_delete_time','pbs_created_by','pbs_status',];

    

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

