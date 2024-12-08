<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsprojectcontractor extends Model
{
    const CREATED_AT = 'cni_create_time';
    const UPDATED_AT = 'cni_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_project_contractor';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'cni_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['cni_id','cni_name','cni_tin_num','cni_contractor_type_id','cni_vat_num','cni_total_contract_price','cni_contract_start_date_et','cni_contract_start_date_gc','cni_contract_end_date_et','cni_contract_end_date_gc','cni_contact_person','cni_phone_number','cni_address','cni_email','cni_website','cni_project_id','cni_procrument_method','cni_bid_invitation_date','cni_bid_opening_date','cni_bid_evaluation_date','cni_bid_award_date','cni_bid_contract_signing_date','cni_description','cni_create_time','cni_update_time','cni_delete_time','cni_created_by','cni_status',];

    

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

