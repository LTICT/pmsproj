<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsprocurementinformation extends Model
{
    const CREATED_AT = 'pri_create_time';
    const UPDATED_AT = 'pri_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_procurement_information';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'pri_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['pri_id','pri_total_procurement_amount','pri_bid_announced_date','pri_bid_invitation_date','pri_bid_opening_date','pri_bid_closing_date','pri_bid_evaluation_date','pri_bid_award_date','pri_project_id','pri_procurement_stage_id','pri_procurement_method_id','pri_description','pri_create_time','pri_update_time','pri_delete_time','pri_created_by','pri_status',];

    

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

