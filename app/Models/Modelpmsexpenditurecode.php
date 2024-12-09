<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsexpenditurecode extends Model
{
    const CREATED_AT = 'pec_create_time';
    const UPDATED_AT = 'pec_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_expenditure_code';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'pec_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['pec_id','pec_name','pec_code','pec_status','pec_description','pec_created_by','pec_created_date','pec_create_time','pec_update_time',];

    

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

