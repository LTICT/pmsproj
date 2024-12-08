<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modeltblpermission extends Model
{
    const CREATED_AT = 'pem_create_time';
    const UPDATED_AT = 'pem_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_permission';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'pem_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['pem_id','pem_page_id','pem_role_id','pem_enabled','pem_edit','pem_insert','pem_view','pem_delete','pem_show','pem_search','pem_description','pem_create_time','pem_update_time','pem_delete_time','pem_created_by','pem_status',];

    

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

