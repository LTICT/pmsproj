<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelgenconversationinformation extends Model
{
    const CREATED_AT = 'cvi_create_time';
    const UPDATED_AT = 'cvi_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gen_conversation_information';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'cvi_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['cvi_id','cvi_title','cvi_object_id','cvi_object_type_id','cvi_request_date_et','cvi_request_date_gc','cvi_description','cvi_create_time','cvi_update_time','cvi_delete_time','cvi_created_by','cvi_status',];

    

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

