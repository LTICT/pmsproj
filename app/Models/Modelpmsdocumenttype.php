<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;
class Modelpmsdocumenttype extends BaseModel
{
    const CREATED_AT = 'pdt_create_time';
    const UPDATED_AT = 'pdt_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_document_type';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'pdt_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['pdt_id','pdt_doc_name_or','pdt_doc_name_am','pdt_doc_name_en','pdt_code','pdt_description','pdt_create_time','pdt_update_time','pdt_delete_time','pdt_created_by','pdt_status',];

    

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

