<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelgenrequeststatus extends Model
{
    const CREATED_AT = 'rqs_create_time';
    const UPDATED_AT = 'rqs_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gen_request_status';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'rqs_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['rqs_id','rqs_name_or','rqs_name_am','rqs_name_en','rqs_description','rqs_create_time','rqs_update_time','rqs_delete_time','rqs_created_by','rqs_status',];

    

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

