<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modeltblaccesslog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class TblaccesslogController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}
    public function listgrid(Request $request){
     // ===============================
        // Pagination params
        // ===============================
        $page    = max((int) $request->input('page', 1), 1);
        $perPage = max((int) $request->input('per_page', 20), 1);
        $offset  = ($page - 1) * $perPage;

        // ===============================
        // Base query
        // ===============================
        $query = DB::table('tbl_access_log')
            ->select([
                'tbl_access_log.acl_user_id AS user_id',
                'acl_id',
                'acl_ip',
                'tbl_users.usr_email AS acl_user_id',
                'acl_role_id',
                'acl_object_name',
                'acl_object_id',
                'acl_remark',
                'acl_detail',
                'acl_object_action',
                'acl_description',
                'acl_create_time',
                'acl_update_time',
                'acl_delete_time',
                'acl_created_by',
                'acl_status',
                DB::raw('1 AS is_editable'),
                DB::raw('1 AS is_deletable'),
            ])
            ->leftJoin('tbl_users', 'tbl_users.usr_id', '=', 'tbl_access_log.acl_user_id')
            ->leftJoin('tbl_pages', 'tbl_pages.pag_id', '=', 'tbl_access_log.acl_role_id');

        // ===============================
        // Filters
        // ===============================
        if ($request->filled('log_timeStart')) {
            $query->where('acl_create_time', '>=', $request->log_timeStart . ' 00:00:00');
        }

        if ($request->filled('log_timeEnd')) {
            $query->where('acl_create_time', '<=', $request->log_timeEnd . ' 23:59:59');
        }

        if ($request->filled('acl_object_action')) {
            $query->where('acl_object_action', $request->acl_object_action);
        }

        if ($request->filled('acl_ip')) {
            $query->where('acl_ip', $request->acl_ip);
        }

        if ($request->filled('acl_user_id')) {
            $query->where('tbl_users.usr_email', $request->acl_user_id);
        }

        if ($request->filled('acl_role_id')) {
            $query->where('acl_role_id', $request->acl_role_id);
        }

        if ($request->filled('acl_object_name')) {
            $query->where('acl_object_name', $request->acl_object_name);
        }

        if ($request->filled('user_id')) {
            $query->where('tbl_access_log.acl_user_id', $request->user_id);
        }

        // ===============================
        // Total count (for pagination UI)
        // ===============================
        $total = $query->count();
        /*if(isset($total) && !empty($total) && $total > 0){
            $total=$data_info[0]->total_count;                
            }*/
            $totalPages = (int) ceil($total / $perPage);
        // ===============================
        // Paginated data
        // ===============================
        $data_info = $query
            ->orderByDesc('acl_id')
            ->offset($offset)
            ->limit($perPage)
            ->get();
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1),
    'pagination' => [
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'total_pages' => $totalPages,
            'has_next' => $page < $totalPages,
            'has_prev' => $page > 1,
        ]
);
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $attributeNames = [
        'acl_ip'=> trans('form_lang.acl_ip'), 
'acl_user_id'=> trans('form_lang.acl_user_id'), 
'acl_role_id'=> trans('form_lang.acl_role_id'), 
'acl_object_name'=> trans('form_lang.acl_object_name'), 
'acl_object_id'=> trans('form_lang.acl_object_id'), 
'acl_remark'=> trans('form_lang.acl_remark'), 
'acl_detail'=> trans('form_lang.acl_detail'), 
'acl_object_action'=> trans('form_lang.acl_object_action'), 
'acl_description'=> trans('form_lang.acl_description'), 
'acl_status'=> trans('form_lang.acl_status'), 

    ];
    $rules= [
        'acl_ip'=> 'max:200', 
'acl_user_id'=> 'max:200', 
'acl_role_id'=> 'max:200', 
'acl_object_name'=> 'max:200', 
'acl_object_id'=> 'max:15', 
'acl_remark'=> 'max:45', 
'acl_detail'=> 'max:45', 
'acl_object_action'=> 'max:200', 
'acl_description'=> 'max:425', 
'acl_status'=> 'integer', 

    ];
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if($validator->fails()) {
        $errorString = implode(",",$validator->messages()->all());
        $resultObject= array(
            "odata.metadata"=>"",
            "value" =>"",
            "statusCode"=>"error",
            "type"=>"update",
            "errorMsg"=>$errorString
        );
        return response()->json($resultObject);
    }else{
        $id=$request->get("acl_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('acl_status');
        if($status=="true"){
            $requestData['acl_status']=1;
        }else{
            $requestData['acl_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modeltblaccesslog::findOrFail($id);
            $data_info->update($requestData);
            $ischanged=$data_info->wasChanged();
            if($ischanged){
               $resultObject= array(
                "data" =>$data_info,
            "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1),
            "is_updated"=>true,
                "status_code"=>200,
                "type"=>"update",
                "errorMsg"=>""
            );
           }else{
            $resultObject= array(
                "data" =>$data_info,
            "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1),
            "is_updated"=>true,
                "status_code"=>200,
                "type"=>"update",
                "errorMsg"=>""
            );
        }
        return response()->json($resultObject);
    }else{
        //Parent Id Assigment
        //$requestData['ins_vehicle_id']=$request->get('master_id');
        //$requestData['acl_created_by']=auth()->user()->usr_Id;
        $data_info=Modeltblaccesslog::create($requestData);
        $resultObject= array(
            "odata.metadata"=>"",
            "value" =>$data_info,
            "statusCode"=>200,
            "type"=>"save",
            "errorMsg"=>""
        );
        return response()->json($resultObject);
    }        
}
}
public function insertgrid(Request $request)
{
    $attributeNames = [
        'acl_ip'=> trans('form_lang.acl_ip'), 
'acl_user_id'=> trans('form_lang.acl_user_id'), 
'acl_role_id'=> trans('form_lang.acl_role_id'), 
'acl_object_name'=> trans('form_lang.acl_object_name'), 
'acl_object_id'=> trans('form_lang.acl_object_id'), 
'acl_remark'=> trans('form_lang.acl_remark'), 
'acl_detail'=> trans('form_lang.acl_detail'), 
'acl_object_action'=> trans('form_lang.acl_object_action'), 
'acl_description'=> trans('form_lang.acl_description'), 
'acl_status'=> trans('form_lang.acl_status'), 

    ];
    $rules= [
        'acl_ip'=> 'max:200', 
'acl_user_id'=> 'max:200', 
'acl_role_id'=> 'max:200', 
'acl_object_name'=> 'max:200', 
'acl_object_id'=> 'max:15', 
'acl_remark'=> 'max:45', 
'acl_detail'=> 'max:45', 
'acl_object_action'=> 'max:200', 
'acl_description'=> 'max:425', 
'acl_status'=> 'integer', 

    ];
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if($validator->fails()) {
        $errorString = implode(",",$validator->messages()->all());
        $resultObject= array(
            "odata.metadata"=>"",
            "value" =>"",
            "statusCode"=>"error",
            "type"=>"update",
            "errorMsg"=>$errorString
        );
        return response()->json($resultObject);
    }else{
        $requestData = $request->all();
        //$requestData['acl_created_by']=auth()->user()->usr_Id;
        $status= $request->input('acl_status');
        if($status=="true"){
            $requestData['acl_status']=1;
        }else{
            $requestData['acl_status']=0;
        }
        $data_info=Modeltblaccesslog::create($requestData);
        $resultObject= array(
            "data" =>$data_info,
            "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1),
            "status_code"=>200,
            "type"=>"save",
            "errorMsg"=>""
        );
    }  
    return response()->json($resultObject);
}
public function deletegrid(Request $request)
{
    $id=$request->get("acl_id");
    Modeltblaccesslog::destroy($id);
    $resultObject= array(
        "odata.metadata"=>"",
        "value" =>"",
        "statusCode"=>200,
        "type"=>"delete",
        "errorMsg"=>""
    );
    return response()->json($resultObject);
}
}