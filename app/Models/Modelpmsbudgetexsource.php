<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsbudgetexsource extends Model
{
    const CREATED_AT = 'bes_create_time';
    const UPDATED_AT = 'bes_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_budget_ex_source';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'bes_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['bes_id','bes_budget_request_id','bes_organ_code','bes_org_name','bes_support_amount','bes_credit_amount','bes_description','bes_create_time','bes_update_time','bes_delete_time','bes_created_by','bes_status',];

    

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

