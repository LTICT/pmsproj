<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modeltblaccesslog extends Model
{
    const CREATED_AT = 'acl_create_time';
    const UPDATED_AT = 'acl_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_access_log';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'acl_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['acl_id','acl_ip','acl_user_id','acl_role_id','acl_object_name','acl_object_id','acl_remark','acl_detail','acl_object_action','acl_description','acl_create_time','acl_update_time','acl_delete_time','acl_created_by','acl_status',];

    

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

