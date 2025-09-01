<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsprojectcomponent extends Model
{
    const CREATED_AT = 'pcm_create_time';
    const UPDATED_AT = 'pcm_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_project_component';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'pcm_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['pcm_id','pcm_project_id','pcm_component_name','pcm_unit_measurement','pcm_amount','pcm_description','pcm_create_time','pcm_update_time','pcm_delete_time','pcm_created_by','pcm_status','pcm_budget_amount'];

    

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

