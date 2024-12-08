<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modeltblroles extends Model
{
    const CREATED_AT = 'rol_create_time';
    const UPDATED_AT = 'rol_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_roles';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'rol_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['rol_id','rol_name','rol_description','rol_create_time','rol_update_time','rol_delete_time','rol_created_by','rol_status',];

    

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

