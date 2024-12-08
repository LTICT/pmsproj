<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmssectorinformation extends Model
{
    const CREATED_AT = 'sci_create_time';
    const UPDATED_AT = 'sci_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_sector_information';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'sci_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['sci_id','sci_name_or','sci_name_am','sci_name_en','sci_code','sci_sector_category_id','sci_available_at_region','sci_available_at_zone','sci_available_at_woreda','sci_description','sci_create_time','sci_update_time','sci_delete_time','sci_created_by','sci_status',];

    

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

