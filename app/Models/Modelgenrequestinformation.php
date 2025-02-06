<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelgenrequestinformation extends Model
{
    const CREATED_AT = 'rqi_create_time';
    const UPDATED_AT = 'rqi_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gen_request_information';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'rqi_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['rqi_id','rqi_title','rqi_object_id','rqi_request_status_id','rqi_request_category_id','rqi_request_date_et','rqi_request_date_gc','rqi_description','rqi_create_time','rqi_update_time','rqi_delete_time','rqi_created_by','rqi_status',];

    

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

