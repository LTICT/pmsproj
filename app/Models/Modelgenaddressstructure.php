<?php
namespace App\Models;
use App\Models\BaseModel;
class Modelgenaddressstructure extends BaseModel
{
    const CREATED_AT = 'add_create_time';
    const UPDATED_AT = 'add_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gen_address_structure';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'add_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['add_id','add_name_or','add_name_am','add_name_en','add_type','add_parent_id','add_phone','add_description','add_create_time','add_update_time','add_delete_time','add_created_by','add_status',];

    

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

