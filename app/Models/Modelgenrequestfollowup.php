<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelgenrequestfollowup extends Model
{
    const CREATED_AT = 'rqf_create_time';
    const UPDATED_AT = 'rqf_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gen_request_followup';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'rqf_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['rqf_id','rqf_request_id','rqf_forwarding_dep_id','rqf_forwarded_to_dep_id','rqf_forwarding_date','rqf_received_date','rqf_description','rqf_create_time','rqf_update_time','rqf_delete_time','rqf_created_by','rqf_status',];

    

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

