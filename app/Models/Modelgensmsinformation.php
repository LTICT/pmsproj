<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelgensmsinformation extends Model
{
    const CREATED_AT = 'smi_create_time';
    const UPDATED_AT = 'smi_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gen_sms_information';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'smi_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['smi_id','smi_sms_template_id','smi_sent_to','smi_sent_date','smi_sms_content','smi_description','smi_create_time','smi_update_time','smi_delete_time','smi_created_by','smi_status',];

    

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

