<?php
namespace App\Models;
use App\Models\BaseModel;
class Modelgenemailinformation extends BaseModel
{
    const CREATED_AT = 'emi_create_time';
    const UPDATED_AT = 'emi_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gen_email_information';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'emi_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['emi_id','emi_email_template_id','emi_sent_to','emi_sent_date','emi_email_content','emi_description','emi_create_time','emi_update_time','emi_delete_time','emi_created_by','emi_status',];

    

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

