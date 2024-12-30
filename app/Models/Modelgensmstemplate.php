<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelgensmstemplate extends Model
{
    const CREATED_AT = 'smt_create_time';
    const UPDATED_AT = 'smt_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gen_sms_template';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'smt_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['smt_id','smt_template_name','smt_template_content','smt_description','smt_create_time','smt_update_time','smt_delete_time','smt_created_by','smt_status',];

    

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

