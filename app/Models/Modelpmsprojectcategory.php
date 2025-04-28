<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsprojectcategory extends Model
{
    const CREATED_AT = 'pct_create_time';
    const UPDATED_AT = 'pct_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_project_category';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'pct_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['pct_id','pct_name_or','pct_name_am','pct_name_en','pct_code','pct_description','pct_create_time','pct_update_time','pct_delete_time','pct_created_by','pct_status','pct_owner_type_id'];



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

