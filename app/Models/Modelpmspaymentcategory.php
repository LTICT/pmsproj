<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmspaymentcategory extends Model
{
    const CREATED_AT = 'pyc_create_time';
    const UPDATED_AT = 'pyc_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_payment_category';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'pyc_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['pyc_id','pyc_name_or','pyc_name_am','pyc_name_en','pyc_description','pyc_create_time','pyc_update_time','pyc_delete_time','pyc_created_by','pyc_status',];

    

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

