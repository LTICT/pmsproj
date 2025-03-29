<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsprocurementmethod extends Model
{
    const CREATED_AT = 'prm_create_time';
    const UPDATED_AT = 'prm_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_procurement_method';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'prm_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['prm_id','prm_name_or','prm_name_en','prm_name_am','prm_description','prm_create_time','prm_update_time','prm_delete_time','prm_created_by','prm_status',];

    

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

