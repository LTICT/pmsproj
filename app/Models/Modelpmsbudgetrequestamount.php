<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsbudgetrequestamount extends Model
{
    const CREATED_AT = 'bra_create_time';
    const UPDATED_AT = 'bra_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_budget_request_amount';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'bra_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['bra_id','bra_expenditure_code_id','bra_budget_request_id','bra_current_year_expense','bra_requested_amount','bra_approved_amount','bra_source_government_requested','bra_source_government_approved','bra_source_internal_requested','bra_source_internal_approved','bra_source_support_requested','bra_source_support_approved','bra_source_support_code','bra_source_credit_requested','bra_source_credit_approved','bra_source_credit_code','bra_source_other_requested','bra_source_other_approved','bra_source_other_code','bra_requested_date','bra_approved_date','bra_description','bra_create_time','bra_update_time','bra_delete_time','bra_created_by','bra_status',];

    

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

