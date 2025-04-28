<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsprojectkpi extends Model
{
    const CREATED_AT = 'kpi_create_time';
    const UPDATED_AT = 'kpi_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_project_kpi';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'kpi_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['kpi_id','kpi_name_or','kpi_name_am','kpi_name_en','kpi_unit_measurement','kpi_description','kpi_create_time','kpi_update_time','kpi_delete_time','kpi_created_by','kpi_status',];
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