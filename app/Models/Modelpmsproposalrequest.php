<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsproposalrequest extends Model
{
    const CREATED_AT = 'prr_create_time';
    const UPDATED_AT = 'prr_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_proposal_request';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'prr_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['prr_id','prr_title','prr_project_id','prr_request_status_id','prr_request_category_id','prr_request_date_et','prr_request_date_gc','prr_description','prr_create_time','prr_update_time','prr_delete_time','prr_created_by','prr_status',];

    

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

