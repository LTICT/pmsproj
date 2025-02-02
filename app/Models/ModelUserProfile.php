<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ModelUserProfile extends Model
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
    protected $fillable = ['usr_id','usr_full_name','usr_phone_number','usr_picture',
    'usr_description','usr_update_time','usr_delete_time'];

    

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

