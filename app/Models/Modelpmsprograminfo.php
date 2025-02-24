<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsprograminfo extends Model
{
    const CREATED_AT = 'pri_create_time';
    const UPDATED_AT = 'pri_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_program_info';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'pri_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['pri_id','pri_owner_region_id','pri_owner_zone_id','pri_owner_woreda_id','pri_sector_id','pri_name_or','pri_name_am','pri_name_en','pri_program_code','pri_description','pri_create_time','pri_update_time','pri_delete_time','pri_created_by','pri_status',];

    

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

