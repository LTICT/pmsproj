<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Modelpmsprojectmonitoringevaluation extends Model
{
    const CREATED_AT = 'mne_create_time';
    const UPDATED_AT = 'mne_update_time';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pms_project_monitoring_evaluation';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'mne_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['mne_id','mne_transaction_type_id','mne_visit_type','mne_project_id','mne_type_id','mne_physical','mne_financial','mne_physical_region','mne_financial_region','mne_team_members','mne_feedback','mne_weakness','mne_challenges','mne_recommendations','mne_purpose','mne_record_date','mne_start_date','mne_end_date','mne_description','mne_create_time','mne_update_time','mne_delete_time','mne_created_by','mne_status',
    'mne_physical_zone','mne_financial_zone','mne_strength'];
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