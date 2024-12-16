<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsprojectdocument extends Model
{
    const CREATED_AT = 'prd_create_time';
    const UPDATED_AT = 'prd_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_project_document';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'prd_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['prd_id','prd_project_id','prd_document_type_id','prd_name','prd_file_path','prd_size','prd_file_extension','prd_uploaded_date','prd_description','prd_create_time','prd_update_time','prd_delete_time','prd_created_by','prd_status','prd_owner_type_id', 'prd_owner_id'];

    

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

