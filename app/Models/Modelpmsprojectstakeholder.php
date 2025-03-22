<?php
namespace App\Models;
use App\Models\BaseModel;

class Modelpmsprojectstakeholder extends BaseModel
{
    const CREATED_AT = 'psh_create_time';
    const UPDATED_AT = 'psh_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_project_stakeholder';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'psh_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['psh_id','psh_project_id','psh_name','psh_stakeholder_type','psh_representative_name','psh_representative_phone','psh_role','psh_description','psh_create_time','psh_update_time','psh_delete_time','psh_created_by','psh_status',];

    

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

