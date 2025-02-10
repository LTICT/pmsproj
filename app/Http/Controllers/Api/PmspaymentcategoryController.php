<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmspaymentcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmspaymentcategoryController extends MyController
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
        $query='SELECT pyc_id,pyc_name_or,pyc_name_am,pyc_name_en,pyc_description,pyc_create_time,pyc_update_time,pyc_delete_time,pyc_created_by,pyc_status FROM pms_payment_category ';       
        
        $query .=' WHERE pyc_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_payment_category_data']=$data_info[0];
        }
        //$data_info = Modelpmspaymentcategory::findOrFail($id);
        //$data['pms_payment_category_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_payment_category");
        return view('payment_category.show_pms_payment_category', $data);
    }
    //Get List
    public function listgrid(Request $request){
     $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
     $permissionData=$this->getPagePermission($request,45);
      if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
     }
     $query="SELECT pyc_id,pyc_name_or,pyc_name_am,pyc_name_en,pyc_description,pyc_create_time,pyc_update_time,pyc_delete_time,pyc_created_by,pyc_status,1 AS is_editable, 1 AS is_deletable ".$permissionIndex." FROM pms_payment_category ";
     
     $query .=' WHERE 1=1';
     $pycid=$request->input('pyc_id');
if(isset($pycid) && isset($pycid)){
$query .=' AND pyc_id="'.$pycid.'"'; 
}
$pycnameor=$request->input('pyc_name_or');
if(isset($pycnameor) && isset($pycnameor)){
$query .=' AND pyc_name_or="'.$pycnameor.'"'; 
}
$pycnameam=$request->input('pyc_name_am');
if(isset($pycnameam) && isset($pycnameam)){
$query .=' AND pyc_name_am="'.$pycnameam.'"'; 
}
$pycnameen=$request->input('pyc_name_en');
if(isset($pycnameen) && isset($pycnameen)){
$query .=' AND pyc_name_en="'.$pycnameen.'"'; 
}
$pycStatus=$request->input('pyc_status');
if(isset($pycStatus) && isset($pycStatus)){
$query .=" AND pyc_status='".$pycStatus."'"; 
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
//$query.=' ORDER BY emp_first_name';
$data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>$permissionData->pem_edit ?? 0,'is_role_deletable'=>$permissionData->pem_delete ?? 0,'is_role_can_add'=>$permissionData->pem_insert ?? 0));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
//Update Data
public function updategrid(Request $request)
{
    $attributeNames = [
        'pyc_name_or'=> trans('form_lang.pyc_name_or'), 
'pyc_name_am'=> trans('form_lang.pyc_name_am'), 
'pyc_name_en'=> trans('form_lang.pyc_name_en'), 
'pyc_description'=> trans('form_lang.pyc_description'), 
'pyc_status'=> trans('form_lang.pyc_status'), 

    ];
    $rules= [
        'pyc_name_or'=> 'max:200', 
'pyc_name_am'=> 'max:200', 
'pyc_name_en'=> 'max:200', 
'pyc_description'=> 'max:425'

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
        $id=$request->get("pyc_id");
        $requestData = $request->all();
        if(isset($id) && !empty($id)){
            $data_info = Modelpmspaymentcategory::findOrFail($id);
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
        $data_info=Modelpmspaymentcategory::create($requestData);
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
//Insert Data
public function insertgrid(Request $request)
{
    $attributeNames = [
        'pyc_name_or'=> trans('form_lang.pyc_name_or'), 
'pyc_name_am'=> trans('form_lang.pyc_name_am'), 
'pyc_name_en'=> trans('form_lang.pyc_name_en'), 
'pyc_description'=> trans('form_lang.pyc_description'), 
'pyc_status'=> trans('form_lang.pyc_status'), 

    ];
    $rules= [
        'pyc_name_or'=> 'max:200', 
'pyc_name_am'=> 'max:200', 
'pyc_name_en'=> 'max:200', 
'pyc_description'=> 'max:425',

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
        $requestData['pyc_created_by']=auth()->user()->usr_Id;
        $requestData['pyc_created_by']=1;
        $data_info=Modelpmspaymentcategory::create($requestData);
        $data_info['is_editable']=1;
        $data_info['is_deletable']=1;
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
//Delete Data
public function deletegrid(Request $request)
{
    $id=$request->get("pyc_id");
    Modelpmspaymentcategory::destroy($id);
    $resultObject= array(
        "odata.metadata"=>"",
        "value" =>"",
        "statusCode"=>200,
        "type"=>"delete",
        "deleted_id"=>$id,
        "errorMsg"=>""
    );
    return response()->json($resultObject);
}
}