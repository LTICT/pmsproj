<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelgendepartment extends Model
{
    const CREATED_AT = 'dep_create_time';
    const UPDATED_AT = 'dep_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gen_department';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'dep_id';

/**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['dep_id','dep_name_or','dep_name_am','dep_name_en','dep_code','dep_available_at_region','dep_available_at_zone','dep_available_at_woreda','dep_description','dep_create_time','dep_update_time','dep_delete_time','dep_created_by','dep_status','dep_parent_id','dep_category'];

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

