<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Modeltblpages extends Model
{
    const CREATED_AT = 'pag_create_time';
    const UPDATED_AT = 'pag_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_pages';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'pag_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['pag_id','pag_name','pag_controller','pag_modifying_days','pag_is_deletable','pag_display_record_no','pag_system_module','pag_header','pag_footer','pag_rule','pag_description','pag_create_time','pag_update_time','pag_delete_time','pag_created_by','pag_status','pag_order_number'];    

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

