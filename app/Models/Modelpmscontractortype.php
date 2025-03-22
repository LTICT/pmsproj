<?php
namespace App\Models;
use App\Models\BaseModel;

class Modelpmscontractortype extends BaseModel
{
    const CREATED_AT = 'cnt_create_time';
    const UPDATED_AT = 'cnt_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_contractor_type';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'cnt_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['cnt_id','cnt_type_name_or','cnt_type_name_am','cnt_type_name_en','cnt_description','cnt_create_time','cnt_update_time','cnt_delete_time','cnt_created_by','cnt_status',];

    

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

