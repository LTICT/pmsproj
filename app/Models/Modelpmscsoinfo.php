<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmscsoinfo extends Model
{
    const CREATED_AT = 'cso_create_time';
    const UPDATED_AT = 'cso_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_cso_info';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'cso_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['cso_id','cso_name','cso_code','cso_address','cso_phone','cso_email','cso_website','cso_description','cso_create_time','cso_update_time','cso_delete_time','cso_created_by','cso_status','cso_contact_person'];



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
