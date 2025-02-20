<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modeltblusersector extends Model
{
    const CREATED_AT = 'usc_create_time';
    const UPDATED_AT = 'usc_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_user_sector';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'usc_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['usc_id','usc_sector_id','usc_user_id','usc_description','usc_create_time','usc_update_time','usc_delete_time','usc_created_by','usc_status',];

    

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

