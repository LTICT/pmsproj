<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modeltblusers extends Model
{
    const CREATED_AT = 'usr_create_time';
    const UPDATED_AT = 'usr_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_users';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'usr_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['usr_id','usr_email','usr_password','usr_full_name','usr_phone_number','usr_role_id','usr_region_id','usr_zone_id','usr_woreda_id',
    'usr_kebele_id','usr_sector_id','usr_department_id','usr_is_active','usr_picture','usr_last_logged_in','usr_ip','usr_remember_token','usr_notified',
    'usr_description','usr_create_time','usr_update_time','usr_delete_time','usr_created_by','usr_status','email','password'];

    

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

