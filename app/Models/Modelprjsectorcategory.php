<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelprjsectorcategory extends Model
{
    const CREATED_AT = 'psc_create_time';
    const UPDATED_AT = 'psc_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'prj_sector_category';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'psc_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['psc_id','psc_created_by','psc_status','psc_name','psc_code','psc_sector_id','psc_description','psc_create_time','psc_update_time',];

    

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

