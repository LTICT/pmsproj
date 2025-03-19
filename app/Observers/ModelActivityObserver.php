<?php
namespace App\Observers;

//use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use App\Models\Modeltblaccesslog;
class ModelActivityObserver
{
    /**
     * Log model changes dynamically.
     *
     * @param string $operation
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    private function logChange($operation, $model)
    {
        $primaryKey = $model->getKeyName(); // Get the primary key column name dynamically
        $primaryKeyValue = $model->getAttribute($primaryKey); // Get the primary key value

        /*Log::channel('db_changes')->info($operation, [
            'acl_object_id'   => $primaryKeyValue, // Dynamic primary key value
            //'primary_key' => $primaryKey, // Dynamic primary key name
            'acl_object_name'       => get_class($model), // Model name
            'acl_remark'         => Request::fullUrl(), // Request URL
            'acl_ip'  => Request::ip(), // User's IP address
            'acl_user_id'     => auth()->id(), // Authenticated user (if available)
            'acl_create_time'   => now()->toDateTimeString(), // Current timestamp
            'acl_object_action'   => $operation, // INSERT, UPDATE, DELETE
            'acl_description'     => $operation === 'UPDATE' ? $model->getChanges() : null, // Only for updates
        ]);*/
          // Track both old and new values
     if ($operation === 'UPDATE') {
        $changes = [];
        foreach ($model->getChanges() as $field => $newValue) {
            if (strpos($field, 'update_time') === false) { // Exclude fields containing "update_time"
                $changes[$field] = [
                    'old' => $model->getOriginal($field),
                    'new' => $newValue,
                ];
            }
        }
    } else {
        $changes = null;
    }
    // Convert changes array to string format (JSON)
    $changes = !empty($changes) ? json_encode($changes, JSON_PRETTY_PRINT) : "-";

             $requestData['acl_object_id'] = $primaryKeyValue; // Dynamic primary key value
            //'primary_key' => $primaryKey; // Dynamic primary key name
             $requestData['acl_object_name']=class_basename($model);// Remove namespace from class name
             //$requestData['acl_object_name'] = get_class($model); // Model name
             $requestData['acl_remark'] = Request::fullUrl(); // Request URL
             $requestData['acl_ip'] = Request::ip(); // User's IP address
             $requestData['acl_user_id'] = auth()->id(); // Authenticated user (if available)
             $requestData['acl_create_time'] = now()->toDateTimeString(); // Current timestamp
             $requestData['acl_object_action'] = $operation; // INSERT, UPDATE, DELETE
             $requestData['acl_description'] = $changes; // Old and new valuesJSON_PRETTY_PRINT) : "-"; // Only for updates
             $data_info=Modeltblaccesslog::create($requestData);

    }

    public function created($model)
    {
        $this->logChange('INSERT', $model);
    }

    public function updated($model)
    {
        $this->logChange('UPDATE', $model);
    }

    public function deleted($model)
    {
        $this->logChange('DELETE', $model);
    }
}
