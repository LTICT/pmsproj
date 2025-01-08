<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelgenemailtemplate extends Model
{
    const CREATED_AT = 'emt_create_time';
    const UPDATED_AT = 'emt_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gen_email_template';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'emt_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['emt_id','emt_template_name','emt_template_content','emt_description','emt_create_time','emt_update_time','emt_delete_time','emt_created_by','emt_status','emt_template_content_am','emt_template_content_en'];
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

