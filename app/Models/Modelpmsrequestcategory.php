<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsrequestcategory extends Model
{
    const CREATED_AT = 'rqc_create_time';
    const UPDATED_AT = 'rqc_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_request_category';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'rqc_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['rqc_id','rqc_name_or','rqc_name_am','rqc_name_en','rqc_description','rqc_create_time',
    'rqc_update_time','rqc_delete_time','rqc_created_by','rqc_status','rqc_gov_active','rqc_cso_active'];



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
