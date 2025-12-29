<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modeltblusers;
use App\Models\ModelUserProfile;
use App\Models\Modeltblupdateusers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
//PROPERTY OF LT ICT SOLUTION PLC
class TblusersController extends MyController
{
 public function __construct()
 {
    parent::__construct();
    //$this->middleware('auth');
}
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $query='SELECT usr_id,usr_email,usr_password,usr_full_name,usr_phone_number,usr_role_id,usr_region_id,gen_address_structure.add_name_or AS usr_zone_id,usr_woreda_id,usr_kebele_id,usr_sector_id,gen_department.dep_name_or AS usr_department_id,usr_is_active,usr_picture,usr_last_logged_in,usr_ip,usr_remember_token,usr_notified,usr_description,usr_create_time,usr_update_time,usr_delete_time,usr_created_by,usr_status FROM tbl_users ';
        $query .= ' INNER JOIN gen_address_structure ON tbl_users.usr_zone_id = gen_address_structure.add_id';
        $query .= ' INNER JOIN gen_department ON tbl_users.usr_department_id = gen_department.dep_id';
        $query .=' WHERE usr_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['tbl_users_data']=$data_info[0];
        }
        //$data_info = Modeltblusers::findOrFail($id);
        //$data['tbl_users_data']=$data_info;
        $data['page_title']=trans("form_lang.tbl_users");
        return view('users.show_tbl_users', $data);
    }
    public function listgrid(Request $request){
    $page    = max((int) $request->input('page', 1), 1);
    $perPage = max((int) $request->input('per_page', 10), 1);
    $offset  = ($page - 1) * $perPage;

    // ============================
    // Base Query (SINGLE SOURCE)
    // ============================
    $baseQuery = DB::table('tbl_users')
        ->leftJoin('gen_address_structure AS zone_data', 'tbl_users.usr_zone_id', '=', 'zone_data.add_id')
        ->leftJoin('gen_address_structure AS woreda_data', 'tbl_users.usr_woreda_id', '=', 'woreda_data.add_id')
        ->leftJoin('gen_department', 'tbl_users.usr_department_id', '=', 'gen_department.dep_id')
        ->leftJoin('pms_sector_information', 'tbl_users.usr_sector_id', '=', 'pms_sector_information.sci_id');

    // ============================
    // Filters (applied once)
    // ============================
    if ($request->filled('usr_email')) {
        $baseQuery->where('usr_email', 'LIKE', '%' . trim($request->usr_email) . '%');
    }

    if ($request->filled('usr_full_name')) {
        $baseQuery->where('usr_full_name', 'LIKE', '%' . trim($request->usr_full_name) . '%');
    }

    if ($request->filled('usr_phone_number')) {
        $baseQuery->where('usr_phone_number', 'LIKE', '%' . $request->usr_phone_number . '%');
    }

    if ($request->filled('usr_role_id')) {
        $baseQuery->where('usr_role_id', $request->usr_role_id);
    }

    if ($request->filled('usr_region_id')) {
        $baseQuery->where('usr_region_id', $request->usr_region_id);
    }

    if ($request->filled('usr_zone_id')) {
        $baseQuery->where('usr_zone_id', $request->usr_zone_id);
    }

    if ($request->filled('usr_woreda_id')) {
        $baseQuery->where('usr_woreda_id', $request->usr_woreda_id);
    }

    if ($request->filled('usr_kebele_id')) {
        $baseQuery->where('usr_kebele_id', $request->usr_kebele_id);
    }

    if ($request->filled('usr_sector_id')) {
        $baseQuery->where('usr_sector_id', $request->usr_sector_id);
    }

    if ($request->filled('usr_department_id')) {
        $baseQuery->where('usr_department_id', $request->usr_department_id);
    }

    if ($request->filled('usr_is_active')) {
        $baseQuery->where('usr_is_active', $request->usr_is_active);
    }

    if ($request->filled('usr_picture')) {
        $baseQuery->where('usr_picture', $request->usr_picture);
    }

    if ($request->filled('usr_last_logged_in')) {
        $baseQuery->where('usr_last_logged_in', $request->usr_last_logged_in);
    }

    if ($request->filled('usr_ip')) {
        $baseQuery->where('usr_ip', $request->usr_ip);
    }

    // ============================
    // COUNT (clone base query)
    // ============================
    $total = (clone $baseQuery)->count('usr_id');
    $totalPages = (int) ceil($total / $perPage);
    // ============================
    // DATA (same base query)
    // ============================
    $data_info = $baseQuery
        ->select([
            'usr_owner_id',
            'usr_user_type',
            'usr_directorate_id',
            'usr_team_id',
            'usr_officer_id',
            'usr_id',
            'usr_email',
            'usr_password',
            'usr_full_name',
            'usr_phone_number',
            'pms_sector_information.sci_name_or AS sector_name',
            'usr_role_id',
            'usr_region_id',
            'usr_zone_id',
            'usr_woreda_id',
            'usr_kebele_id',
            'usr_sector_id',
            'usr_department_id',
            'usr_is_active',
            'usr_picture',
            'usr_last_logged_in',
            'usr_ip',
            'usr_remember_token',
            'usr_notified',
            'usr_description',
            'usr_create_time',
            'usr_update_time',
            'usr_delete_time',
            'usr_created_by',
            'usr_status',
            DB::raw('1 AS is_editable'),
            DB::raw('1 AS is_deletable'),
            'zone_data.add_name_or AS zone_name',
            'woreda_data.add_name_or AS woreda_name',
            'gen_department.dep_name_or AS dep_name',
        ])
        ->orderByDesc('usr_id')
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
        ]);
    return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}

public function getUserInfo(Request $request){
       $query='SELECT usr_user_type, usr_id,usr_email,usr_password,usr_full_name,usr_phone_number,sci_name_or AS sector_name,
       usr_role_id,usr_region_id, usr_zone_id,usr_woreda_id,usr_kebele_id,usr_sector_id,
       usr_department_id,usr_is_active,usr_picture,usr_last_logged_in,usr_ip,
       usr_remember_token,
       gen_address_structure.add_name_or AS zone_name, gen_department.dep_name_or as dep_name FROM tbl_users ';
       $query .= ' LEFT JOIN gen_address_structure ON tbl_users.usr_zone_id = gen_address_structure.add_id';
       $query .= ' LEFT JOIN gen_department ON tbl_users.usr_department_id = gen_department.dep_id';
       $query .= ' LEFT JOIN pms_sector_information ON tbl_users.usr_sector_id = pms_sector_information.sci_id';
       $query .=' WHERE 1=1';
       $usrId=$request->input('id');
       if(isset($usrId) && isset($usrId)){
        $query .=" AND usr_id='".$usrId."'";
    }
    $data_info=DB::select($query);
    $resultObject= array(
        "data" =>$data_info,
        "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1));
    return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function getOwnUserInfo(Request $request){
       $query='SELECT usr_user_type, usr_id,usr_email,usr_password,usr_full_name,usr_phone_number,sci_name_or AS sector_name,
       usr_role_id,usr_region_id, usr_zone_id,usr_woreda_id,usr_kebele_id,usr_sector_id,
       usr_department_id,usr_is_active,usr_picture,usr_last_logged_in,usr_ip,
       usr_remember_token,
       gen_address_structure.add_name_or AS zone_name, gen_department.dep_name_or as dep_name FROM tbl_users ';
       $query .= ' LEFT JOIN gen_address_structure ON tbl_users.usr_zone_id = gen_address_structure.add_id';
       $query .= ' LEFT JOIN gen_department ON tbl_users.usr_department_id = gen_department.dep_id';
       $query .= ' LEFT JOIN pms_sector_information ON tbl_users.usr_sector_id = pms_sector_information.sci_id';
       $query .=' WHERE 1=1';
       $authenticatedUser = $request->authUser;
       $userId = $authenticatedUser->usr_id;
       $query .=" AND usr_id='".$userId."'";
    $data_info=DB::select($query);
    $resultObject= array(
        "data" =>$data_info,
        "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1));
    return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updateUserProfile(Request $request)
{
    $attributeNames = [
        'usr_email'=> trans('form_lang.usr_email'),
    ];
    $rules= [
       'usr_email'=> 'max:200',
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
    return response()->json($resultObject,457);
}else{
    $id=$request->get("id");
    $requestData = $request->all();
    if(isset($id) && !empty($id)){
        $data_info = ModelUserProfile::findOrFail($id);
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
}
}
}
public function updateOwnUserProfile(Request $request)
{
    $attributeNames = [
        'usr_email'=> trans('form_lang.usr_email'),
    ];
    $rules= [
       'usr_email'=> 'max:200',
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
    return response()->json($resultObject,457);
}else{
    //$id=$request->get("id");
    $authenticatedUser = $request->authUser;
    $id = $authenticatedUser->usr_id;

    $requestData = $request->all();
    if(isset($id) && !empty($id)){
        $data_info = ModelUserProfile::findOrFail($id);
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
}
}
}
public function updategrid(Request $request)
{
    $attributeNames = [
        'usr_email'=> trans('form_lang.usr_email'),
        'usr_password'=> trans('form_lang.usr_password'),
        'usr_full_name'=> trans('form_lang.usr_full_name'),
        'usr_phone_number'=> trans('form_lang.usr_phone_number'),
        'usr_role_id'=> trans('form_lang.usr_role_id'),
        'usr_region_id'=> trans('form_lang.usr_region_id'),
        'usr_zone_id'=> trans('form_lang.usr_zone_id'),
        'usr_woreda_id'=> trans('form_lang.usr_woreda_id'),
        'usr_kebele_id'=> trans('form_lang.usr_kebele_id'),
        'usr_sector_id'=> trans('form_lang.usr_sector_id'),
        'usr_department_id'=> trans('form_lang.usr_department_id'),
        'usr_is_active'=> trans('form_lang.usr_is_active'),
        'usr_picture'=> trans('form_lang.usr_picture'),
        'usr_last_logged_in'=> trans('form_lang.usr_last_logged_in'),
        'usr_ip'=> trans('form_lang.usr_ip'),
        'usr_remember_token'=> trans('form_lang.usr_remember_token'),
        'usr_notified'=> trans('form_lang.usr_notified'),
        'usr_description'=> trans('form_lang.usr_description'),
        'usr_status'=> trans('form_lang.usr_status'),
    ];
    $rules= [
       'usr_email' => 'required|max:200',
//'usr_email' => 'required|max:200|unique:tbl_users',
       'usr_full_name'=> 'max:128',
       'usr_phone_number'=> 'max:20',
       'usr_region_id'=> 'integer',
       'usr_zone_id'=> 'integer',
       'usr_woreda_id'=> 'integer',
       'usr_kebele_id'=> 'integer',
       'usr_sector_id'=> 'integer',
       'usr_department_id'=> 'integer',
//'usr_picture'=> 'max:100',
       'usr_last_logged_in'=> 'max:30',
       'usr_ip'=> 'max:15',
       'usr_remember_token'=> 'max:100',
       'usr_description'=> 'max:425'
   ];
   $id=$request->get("usr_id");
   $validationResult = $this->handleLaravelException($request, $attributeNames, $rules, "update", $id);
if ($validationResult !== false) {
    return $validationResult;
}
    try{
    
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
    $requestData = $request->all();
  /*  $status= $request->input('usr_status');
    if($status=="true"){
        $requestData['usr_status']=1;
    }else{
        $requestData['usr_status']=0;
    }*/
    if(isset($id) && !empty($id)){
        $data_info = \App\Models\Modeltblupdateusers::findOrFail($id);
        $uploadedFile = $request->file('usr_picture');
        $hasFile=$request->hasFile('usr_picture');
        if($hasFile && $uploadedFile->isValid()){
            $fileName = $uploadedFile->getClientOriginalName();
                    //$fileExtension=$uploadedFile->getClientOriginalExtension();
                    //$fileSize=$uploadedFile->getSize();
            $uploadedFile->move(public_path('uploads/userfiles'), $fileName);
                    //$requestData['prd_file_extension']=$fileExtension;
                    //$requestData['prd_size']=$fileSize;
            $requestData['usr_picture']=$fileName;
        }
        $requestData['email']=strtolower($request->input('usr_email'));
        //$password=$request->get('usr_password');
        //if(isset)
        //$requestData['password']=bcrypt($request->get('usr_password'));
        //$requestData['usr_password']=bcrypt($request->get('usr_password'));
        $data_info->update($requestData);
        unset($data_info['usr_password']);
        unset($data_info['password']);
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
        //$requestData['usr_created_by']=auth()->user()->usr_Id;
    $data_info=Modeltblusers::create($requestData);
    $resultObject= array(
        "odata.metadata"=>"",
        "value" =>$data_info,
        "statusCode"=>200,
        "type"=>"save",
        "errorMsg"=>""
    );
 return response()->json($resultObject);
    }
}catch (QueryException $e) {
  return $this->handleDatabaseException($e,"update");
}
}
public function insertgrid(Request $request)
{
    $attributeNames = [
        'usr_email'=> trans('form_lang.usr_email'),
        'usr_password'=> trans('form_lang.usr_password'),
        'usr_full_name'=> trans('form_lang.usr_full_name'),
        'usr_phone_number'=> trans('form_lang.usr_phone_number'),
        'usr_role_id'=> trans('form_lang.usr_role_id'),
        'usr_region_id'=> trans('form_lang.usr_region_id'),
        'usr_zone_id'=> trans('form_lang.usr_zone_id'),
        'usr_woreda_id'=> trans('form_lang.usr_woreda_id'),
        'usr_kebele_id'=> trans('form_lang.usr_kebele_id'),
        'usr_sector_id'=> trans('form_lang.usr_sector_id'),
        'usr_department_id'=> trans('form_lang.usr_department_id'),
        'usr_is_active'=> trans('form_lang.usr_is_active'),
        'usr_picture'=> trans('form_lang.usr_picture'),
        'usr_last_logged_in'=> trans('form_lang.usr_last_logged_in'),
        'usr_ip'=> trans('form_lang.usr_ip'),
        'usr_remember_token'=> trans('form_lang.usr_remember_token'),
        'usr_notified'=> trans('form_lang.usr_notified'),
        'usr_description'=> trans('form_lang.usr_description'),
        'usr_status'=> trans('form_lang.usr_status')
    ];
    $rules= [
        'usr_email'=> 'max:200',
        'usr_email' => 'required|max:200|unique:tbl_users',
        'usr_password'=> 'max:200',
        'usr_full_name'=> 'max:128',
        'usr_phone_number'=> 'max:20',
        'usr_region_id'=> 'integer',
        'usr_zone_id'=> 'integer',
        'usr_woreda_id'=> 'integer',
        'usr_kebele_id'=> 'integer',
        'usr_sector_id'=> 'integer',
        'usr_department_id'=> 'integer',
//'usr_picture'=> 'max:100',
        'usr_last_logged_in'=> 'max:30',
        'usr_ip'=> 'max:15',
        'usr_remember_token'=> 'max:100',
        'usr_description'=> 'max:425'
    ];
  $validationResult = $this->handleLaravelException($request, $attributeNames, $rules, "save");
if ($validationResult !== false) {
    return $validationResult;
}
try {
        $requestData = $request->all();
        //$requestData['usr_created_by']=auth()->user()->usr_Id;
        $status= $request->input('usr_status');
        $uploadedFile = $request->file('usr_picture');
        $hasFile=$request->hasFile('usr_picture');
        if($hasFile && $uploadedFile->isValid()){
            $fileName = $uploadedFile->getClientOriginalName();
            //$fileExtension=$uploadedFile->getClientOriginalExtension();
            //$fileSize=$uploadedFile->getSize();
            $uploadedFile->move(public_path('uploads/userfiles'), $fileName);
            //$requestData['prd_file_extension']=$fileExtension;
            //$requestData['prd_size']=$fileSize;
            $requestData['usr_picture']=$fileName;
        }
        if($status=="true"){
            $requestData['usr_status']=1;
        }else{
            $requestData['usr_status']=0;
        }
        $requestData['usr_status']=1;
        $createdBy=auth()->user()->usr_id;
        $requestData['email']=strtolower($request->input('usr_email'));
        $requestData['password']=bcrypt($request->get('usr_password'));
        $requestData['usr_password']=bcrypt($request->get('usr_password'));
        $requestData['usr_created_by']=$createdBy;
        $userCopiedFromId=$request->input('usr_copied_from_id');
        $requestData['usr_copied_from']=$userCopiedFromId;
        $data_info=Modeltblusers::create($requestData);
        //START ADD DEFAULT ROLE
        if(isset($data_info) && !empty($data_info)){
            //START COPY ROLE and USER SECTOR
            if(isset($userCopiedFromId) && !empty($userCopiedFromId) && 1==2){
               $assignedRoles= \App\Models\Modeltbluserrole::where('url_user_id','=',$userCopiedFromId)->get();
               $copiedUserId=$data_info->usr_id;
               if(isset($assignedRoles) && !empty($assignedRoles)){
                        $dataToInsert = $assignedRoles->map(function ($assignedRole) {
                return [
                    'url_user_id' => $assignedRole->copiedUserId,
                    'url_role_id' => $assignedRole->url_role_id,
                    'url_created_by' => $createdBy
                ];
            })->toArray();
            \App\Models\Modeltbluserrole::insert($dataToInsert);
               }
            }
        //START COPY ROLE and USER SECTOR
            
            $userType=$request->get('usr_user_type');
            $role_usr_data['url_role_id']=8;
            if($userType==1){
                //Governmental
            }else if($userType==2){
                //CSO
                    $role_usr_data['url_role_id']=66;
            }else if($userType==4){
                //CSO Director

            }else if($userType==3){
                //Citizenship
                $role_usr_data['url_role_id']=66;
            }
            
            $role_usr_data['url_user_id']=$data_info->usr_id;
            //$role_usr_data['usr_role_id']=$data_info
            //\App\Models\Modeltbluserrole::create($role_usr_data);
        
        }
        //START ADD DEFAULT ROLE
        $data_info['is_editable']=1;
        $data_info['is_deletable']=1;
    return response()->json([
        "data" => $data_info,
        "previledge" => [
            'is_role_editable' => 1,
            'is_role_deletable' => 1
        ],
        "status_code" => 200,
        "type" => "save",
        "errorMsg" => ""
    ]);
}catch (QueryException $e) {
  return $this->handleDatabaseException($e,"save");
}
}
public function changeuserstatus(Request $request)
{
    $attributeNames = [
        'usr_email'=> trans('form_lang.usr_email'),
        'usr_status'=> trans('form_lang.usr_status'),
    ];
    $rules= [
       'usr_email'=> 'max:200',
       'usr_id'=> 'max:15',
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
    $id=$request->get("usr_id");
    $requestData = $request->all();
    $status= $request->input('usr_status');
    $requestData['usr_status']=$request->input('usr_status');
    if(isset($id) && !empty($id)){
        $data_info = Modeltblusers::findOrFail($id);
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
        //$requestData['usr_created_by']=auth()->user()->usr_Id;
    $data_info=Modeltblusers::create($requestData);
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
public function deletegrid(Request $request)
{
    $id=$request->get("usr_id");
    Modeltblusers::destroy($id);
    $resultObject= array(
        "odata.metadata"=>"",
        "value" =>"",
        "statusCode"=>200,
        "deleted_id"=>$id,
        "type"=>"delete",
        "errorMsg"=>""
    );
    return response()->json($resultObject);
}
}