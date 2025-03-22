<?php
namespace App\Models;
use App\Models\BaseModel;

class Modelpmsstakeholdertype extends BaseModel
{
    const CREATED_AT = 'sht_create_time';
    const UPDATED_AT = 'sht_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_stakeholder_type';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'sht_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['sht_id','sht_type_name_or','sht_type_name_am','sht_type_name_en','sht_description','sht_create_time','sht_update_time','sht_delete_time','sht_created_by','sht_status',];

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

