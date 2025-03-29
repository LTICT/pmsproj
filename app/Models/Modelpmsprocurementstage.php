<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsprocurementstage extends Model
{
    const CREATED_AT = 'pst_create_time';
    const UPDATED_AT = 'pst_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_procurement_stage';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'pst_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['pst_id','pst_name_or','pst_name_en','pst_name_am','pst_description','pst_create_time','pst_update_time','pst_delete_time','pst_created_by','pst_status',];

    

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

