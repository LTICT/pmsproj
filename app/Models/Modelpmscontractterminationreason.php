<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmscontractterminationreason extends Model
{
    const CREATED_AT = 'ctr_create_time';
    const UPDATED_AT = 'ctr_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_contract_termination_reason';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'ctr_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['ctr_id','ctr_reason_name_or','ctr_reason_name_am','ctr_reason_name_en','ctr_description','ctr_create_time','ctr_update_time','ctr_delete_time','ctr_created_by','ctr_status',];

    

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

