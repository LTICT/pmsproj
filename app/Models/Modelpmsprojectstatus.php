<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsprojectstatus extends Model
{
    const CREATED_AT = 'prs_create_time';
    const UPDATED_AT = 'prs_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_project_status';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'prs_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['prs_id','prs_status_name_or','prs_status_name_am','prs_status_name_en','prs_color_code','prs_order_number','prs_description','prs_create_time','prs_update_time','prs_delete_time','prs_created_by','prs_status','prs_spare_column',];

    

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

