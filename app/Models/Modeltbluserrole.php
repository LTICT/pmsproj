<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modeltbluserrole extends Model
{
    const CREATED_AT = 'url_create_time';
    const UPDATED_AT = 'url_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_user_role';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'url_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['url_id','url_role_id','url_user_id','url_description','url_create_time','url_update_time','url_delete_time','url_created_by','url_status',];

    

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

