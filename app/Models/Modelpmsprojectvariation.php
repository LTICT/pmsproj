<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsprojectvariation extends Model
{
    const CREATED_AT = 'prv_create_time';
    const UPDATED_AT = 'prv_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_project_variation';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'prv_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['prv_id','prv_requested_amount','prv_released_amount','prv_project_id','prv_requested_date_ec','prv_requested_date_gc','prv_released_date_ec','prv_released_date_gc','prv_description','prv_create_time','prv_update_time','prv_delete_time','prv_created_by','prv_status'];

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

