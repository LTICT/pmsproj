<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsimplementingarea extends Model
{
    const CREATED_AT = 'pia_create_time';
    const UPDATED_AT = 'pia_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_implementing_area';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'pia_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['pia_id','pia_project_id','pia_region_id','pia_zone_id_id','pia_woreda_id','pia_sector_id','pia_budget_amount','pia_site','pia_geo_location','pia_description','pia_create_time','pia_update_time','pia_delete_time','pia_created_by','pia_status',];

    

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

