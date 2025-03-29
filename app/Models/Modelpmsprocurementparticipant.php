<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsprocurementparticipant extends Model
{
    const CREATED_AT = 'ppp_create_time';
    const UPDATED_AT = 'ppp_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_procurement_participant';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'ppp_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['ppp_id','ppp_name_or','ppp_name_en','ppp_name_am','ppp_tin_number','ppp_participant_phone_number','ppp_participant_email','ppp_participant_address','ppp_description','ppp_create_time','ppp_update_time','ppp_delete_time','ppp_created_by','ppp_status','ppp_procurement_id'];

    

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

