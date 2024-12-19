<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modeltblusers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class TblusersController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}
 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
 public function index(Request $request)
 {
    $selectedLanguage=app()->getLocale();
    if($selectedLanguage=="or"){
        $filepath = base_path() .'\resources\lang\or\ag_grid.php';
    }else if($selectedLanguage=="en"){
        $filepath = base_path() .'\resources\lang\en\ag_grid.php';
    }else if($selectedLanguage=="am"){
        $filepath = base_path() .'\resources\lang\am\ag_grid.php';
    }
    $filepath = base_path() .'\resources\lang\en\ag_grid.php';
    $txt = file_get_contents($filepath);
    $data['ag_grid_lang']=$txt;
    $searchParams= $this->getSearchSetting('tbl_users');
    $dataInfo = Modeltblusers::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['tbl_users_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.tbl_users");
    return view('users.list_tbl_users', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    $gen_address_structure_set=\App\Modelgenaddressstructure::latest()->get();
$gen_department_set=\App\Modelgendepartment::latest()->get();

    $data['related_gen_address_structure']= $gen_address_structure_set ;
$data['related_gen_department']= $gen_department_set ;

    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modeltblusers::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="TblusersController";
        $data= $this->validateEdit($data, $data_info['usr_create_time'], $controllerName);
        $data['tbl_users_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.tbl_users");
$form= view('users.form_popup_tbl_users', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.tbl_users'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('users.editable_list_tbl_users', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.tbl_users'));
    return response()->json($resultObject);
    //echo json_encode($resultObject, JSON_NUMERIC_CHECK);
}
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $gen_address_structure_set=\App\Modelgenaddressstructure::latest()->get();
$gen_department_set=\App\Modelgendepartment::latest()->get();

        $data['related_gen_address_structure']= $gen_address_structure_set ;
$data['related_gen_department']= $gen_department_set ;

        $data['page_title']=trans("form_lang.tbl_users");
        $data['action_mode']="create";
        return view('users.form_tbl_users', $data);
    }
    /**`
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
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
        'usr_email'=> 'max:200', 
'usr_password'=> 'max:200', 
'usr_full_name'=> 'max:128', 
'usr_phone_number'=> 'max:20', 
'usr_role_id'=> 'integer', 
'usr_region_id'=> 'integer', 
'usr_zone_id'=> 'integer', 
'usr_woreda_id'=> 'integer', 
'usr_kebele_id'=> 'integer', 
'usr_sector_id'=> 'integer', 
'usr_department_id'=> 'integer', 
'usr_is_active'=> 'integer', 
'usr_picture'=> 'max:100', 
'usr_last_logged_in'=> 'max:30', 
'usr_ip'=> 'max:15', 
'usr_remember_token'=> 'max:100', 
'usr_notified'=> 'integer', 
'usr_description'=> 'max:425', 
'usr_status'=> 'integer', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['usr_created_by']=auth()->user()->usr_Id;
        Modeltblusers::create($requestData);
        return redirect('users')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('users/create')
        ->withErrors($validator)
        ->withInput();
    }
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
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $gen_address_structure_set=\App\Modelgenaddressstructure::latest()->get();
$gen_department_set=\App\Modelgendepartment::latest()->get();

        $data['related_gen_address_structure']= $gen_address_structure_set ;
$data['related_gen_department']= $gen_department_set ;

        $data_info = Modeltblusers::find($id);
        $data['tbl_users_data']=$data_info;
        $data['page_title']=trans("form_lang.tbl_users");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('users.form_tbl_users', $data);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
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
        'usr_email'=> 'max:200', 
'usr_password'=> 'max:200', 
'usr_full_name'=> 'max:128', 
'usr_phone_number'=> 'max:20', 
'usr_role_id'=> 'integer', 
'usr_region_id'=> 'integer', 
'usr_zone_id'=> 'integer', 
'usr_woreda_id'=> 'integer', 
'usr_kebele_id'=> 'integer', 
'usr_sector_id'=> 'integer', 
'usr_department_id'=> 'integer', 
'usr_is_active'=> 'integer', 
'usr_picture'=> 'max:100', 
'usr_last_logged_in'=> 'max:30', 
'usr_ip'=> 'max:15', 
'usr_remember_token'=> 'max:100', 
'usr_notified'=> 'integer', 
'usr_description'=> 'max:425', 
'usr_status'=> 'integer', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modeltblusers::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('users')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('users/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('users/'.$id.'/edit')
    ->withErrors($validator)
    ->withInput();
}
}
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Modeltblusers::destroy($id);
        return redirect('users')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
     $query='SELECT usr_id,usr_email,usr_password,usr_full_name,usr_phone_number,sci_name_or AS sector_name,
     usr_role_id,usr_region_id, usr_zone_id,usr_woreda_id,usr_kebele_id,usr_sector_id,
     usr_department_id,usr_is_active,usr_picture,usr_last_logged_in,usr_ip,
     usr_remember_token,usr_notified,usr_description,usr_create_time,
     usr_update_time,usr_delete_time,usr_created_by,usr_status,1 AS is_editable, 1 AS is_deletable FROM tbl_users ';       
     //$query .= ' INNER JOIN gen_address_structure ON tbl_users.usr_zone_id = gen_address_structure.add_id'; 
//$query .= ' INNER JOIN gen_department ON tbl_users.usr_department_id = gen_department.dep_id';
$query .= ' INNER JOIN pms_sector_information ON tbl_users.usr_sector_id = pms_sector_information.sci_id';
     $query .=' WHERE 1=1';
     $usrid=$request->input('usr_id');
if(isset($usrid) && isset($usrid)){
$query .=' AND usr_id="'.$usrid.'"'; 
}
$usremail=$request->input('usr_email');
if(isset($usremail) && isset($usremail)){
$query .=" AND usr_email='".$usremail."'"; 
}
$usrpassword=$request->input('usr_password');
if(isset($usrpassword) && isset($usrpassword)){
$query .=' AND usr_password="'.$usrpassword.'"'; 
}
$usrfullname=$request->input('usr_full_name');
if(isset($usrfullname) && isset($usrfullname)){
$query .=" AND usr_full_name='".$usrfullname."'"; 
}
$usrphonenumber=$request->input('usr_phone_number');
if(isset($usrphonenumber) && isset($usrphonenumber)){
$query .=" AND usr_phone_number='".$usrphonenumber."'"; 
}
$usrroleid=$request->input('usr_role_id');
if(isset($usrroleid) && isset($usrroleid)){
$query .=' AND usr_role_id="'.$usrroleid.'"'; 
}
$usrregionid=$request->input('usr_region_id');
if(isset($usrregionid) && isset($usrregionid)){
$query .=" AND usr_region_id='".$usrregionid."'"; 
}
$usrzoneid=$request->input('usr_zone_id');
if(isset($usrzoneid) && isset($usrzoneid)){
$query .=" AND usr_zone_id='".$usrzoneid."'"; 
}
$usrworedaid=$request->input('usr_woreda_id');
if(isset($usrworedaid) && isset($usrworedaid)){
$query .=" AND usr_woreda_id='".$usrworedaid."'"; 
}
$usrkebeleid=$request->input('usr_kebele_id');
if(isset($usrkebeleid) && isset($usrkebeleid)){
$query .=' AND usr_kebele_id="'.$usrkebeleid.'"'; 
}
$usrsectorid=$request->input('usr_sector_id');
if(isset($usrsectorid) && isset($usrsectorid)){
$query .=' AND usr_sector_id="'.$usrsectorid.'"'; 
}
$usrdepartmentid=$request->input('usr_department_id');
if(isset($usrdepartmentid) && isset($usrdepartmentid)){
$query .=' AND usr_department_id="'.$usrdepartmentid.'"'; 
}
$usrisactive=$request->input('usr_is_active');
if(isset($usrisactive) && isset($usrisactive)){
$query .=' AND usr_is_active="'.$usrisactive.'"'; 
}
$usrpicture=$request->input('usr_picture');
if(isset($usrpicture) && isset($usrpicture)){
$query .=' AND usr_picture="'.$usrpicture.'"'; 
}
$usrlastloggedin=$request->input('usr_last_logged_in');
if(isset($usrlastloggedin) && isset($usrlastloggedin)){
$query .=' AND usr_last_logged_in="'.$usrlastloggedin.'"'; 
}
$usrip=$request->input('usr_ip');
if(isset($usrip) && isset($usrip)){
$query .=' AND usr_ip="'.$usrip.'"'; 
}
$usrremembertoken=$request->input('usr_remember_token');
if(isset($usrremembertoken) && isset($usrremembertoken)){
$query .=' AND usr_remember_token="'.$usrremembertoken.'"'; 
}
$usrnotified=$request->input('usr_notified');
if(isset($usrnotified) && isset($usrnotified)){
$query .=' AND usr_notified="'.$usrnotified.'"'; 
}
$usrdescription=$request->input('usr_description');
if(isset($usrdescription) && isset($usrdescription)){
$query .=' AND usr_description="'.$usrdescription.'"'; 
}
$usrcreatetime=$request->input('usr_create_time');
if(isset($usrcreatetime) && isset($usrcreatetime)){
$query .=' AND usr_create_time="'.$usrcreatetime.'"'; 
}
$usrupdatetime=$request->input('usr_update_time');
if(isset($usrupdatetime) && isset($usrupdatetime)){
$query .=' AND usr_update_time="'.$usrupdatetime.'"'; 
}
$usrdeletetime=$request->input('usr_delete_time');
if(isset($usrdeletetime) && isset($usrdeletetime)){
$query .=' AND usr_delete_time="'.$usrdeletetime.'"'; 
}
$usrcreatedby=$request->input('usr_created_by');
if(isset($usrcreatedby) && isset($usrcreatedby)){
$query .=' AND usr_created_by="'.$usrcreatedby.'"'; 
}
$usrstatus=$request->input('usr_status');
if(isset($usrstatus) && isset($usrstatus)){
$query .=' AND usr_status="'.$usrstatus.'"'; 
}

     $masterId=$request->input('master_id');
     if(isset($masterId) && !empty($masterId)){
        //set foreign key field name
        //$query .=' AND add_name="'.$masterId.'"'; 
     }
     $search=$request->input('search');
     if(isset($search) && !empty($search)){
       $advanced= $request->input('adva-search');
       if(isset($advanced) && $advanced =='on'){
           $query.=' AND (add_name SOUNDS LIKE "%'.$search.'%" )  ';
       }else{
        $query.=' AND (add_name LIKE "%'.$search.'%")  ';
    }
}
//$query.=' ORDER BY emp_first_name, emp_middle_name, emp_last_name';
$data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
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
 'usr_email'=> 'max:200', 
'usr_email' => 'required|max:200',
//'usr_email' => 'required|max:200|unique:tbl_users',
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
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('usr_status');
        if($status=="true"){
            $requestData['usr_status']=1;
        }else{
            $requestData['usr_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modeltblusers::findOrFail($id);
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
'usr_status'=> trans('form_lang.usr_status'), 

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
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    
    if($validator->fails()) {
        $errorString = implode(",",$validator->messages()->all());
        $resultObject= array(
            "errorMsg"=>$errorString,
            "data" =>array(),
            "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1),
            "status_code"=>200,
            "type"=>"duplicate"
        );
        return response()->json($resultObject);
    }else{
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
        $requestData['email']=$request->input('usr_email');
        $requestData['password']=bcrypt($request->get('usr_password'));
        $data_info=Modeltblusers::create($requestData);
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
    $id=$request->get("usr_id");
    Modeltblusers::destroy($id);
    $resultObject= array(
        "odata.metadata"=>"",
        "value" =>"",
        "statusCode"=>200,
        "type"=>"delete",
        "errorMsg"=>""
    );
    return response()->json($resultObject);
}
function listRoutes(){
    Route::resource('users', 'TblusersController');
    Route::post('users/listgrid', 'Api\TblusersController@listgrid');
    Route::post('users/insertgrid', 'Api\TblusersController@insertgrid');
    Route::post('users/updategrid', 'Api\TblusersController@updategrid');
    Route::post('users/deletegrid', 'Api\TblusersController@deletegrid');
    Route::post('users/search', 'TblusersController@search');
    Route::post('users/getform', 'TblusersController@getForm');
    Route::post('users/getlistform', 'TblusersController@getListForm');

}
}