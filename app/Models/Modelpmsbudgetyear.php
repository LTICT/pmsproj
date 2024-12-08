<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsbudgetyear extends Model
{
    const CREATED_AT = 'bdy_create_time';
    const UPDATED_AT = 'bdy_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_budget_year';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'bdy_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['bdy_id','bdy_name','bdy_code','bdy_description','bdy_create_time','bdy_update_time','bdy_delete_time','bdy_created_by','bdy_status',];

    

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

