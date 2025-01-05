<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelgenemailtemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class GenemailtemplateController extends MyController
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
        $query='SELECT emt_id,emt_template_name,emt_template_content,emt_description,emt_create_time,emt_update_time,emt_delete_time,emt_created_by,emt_status FROM gen_email_template ';       
        
        $query .=' WHERE emt_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['gen_email_template_data']=$data_info[0];
        }
        //$data_info = Modelgenemailtemplate::findOrFail($id);
        //$data['gen_email_template_data']=$data_info;
        $data['page_title']=trans("form_lang.gen_email_template");
        return view('email_template.show_gen_email_template', $data);
    }
    //Get List
    public function listgrid(Request $request){
     $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
     $permissionData=$this->getPagePermission($request,45);
      if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
     }
     $query="SELECT emt_template_content_en,emt_template_content_am,emt_id,emt_template_name,emt_template_content,emt_description,emt_create_time,emt_update_time,emt_delete_time,emt_created_by,emt_status,1 AS is_editable, 1 AS is_deletable ".$permissionIndex." FROM gen_email_template ";
     
     $query .=' WHERE 1=1';
     $emtid=$request->input('emt_id');
if(isset($emtid) && isset($emtid)){
$query .=' AND emt_id="'.$emtid.'"'; 
}
$emttemplatename=$request->input('emt_template_name');
if(isset($emttemplatename) && isset($emttemplatename)){
$query .=" AND emt_template_name LIKE '%".$emttemplatename."%'"; 
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
    "previledge"=>array('is_role_editable'=>$permissionData->pem_edit,'is_role_deletable'=>$permissionData->pem_delete,'is_role_can_add'=>$permissionData->pem_insert));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
//Update Data
public function updategrid(Request $request)
{
    $attributeNames = [
        'emt_template_name'=> trans('form_lang.emt_template_name'), 
'emt_template_content'=> trans('form_lang.emt_template_content'), 
'emt_description'=> trans('form_lang.emt_description'), 
'emt_status'=> trans('form_lang.emt_status'), 

    ];
    $rules= [
        'emt_template_name'=> 'max:200', 
'emt_template_content'=> 'max:200', 
'emt_description'=> 'max:425'

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
        $id=$request->get("emt_id");
        $requestData = $request->all();            
        $status= $request->input('emt_status');
        if($status=="true"){
            $requestData['emt_status']=1;
        }else{
            $requestData['emt_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelgenemailtemplate::findOrFail($id);
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
        $data_info=Modelgenemailtemplate::create($requestData);
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
        'emt_template_name'=> trans('form_lang.emt_template_name'), 
'emt_template_content'=> trans('form_lang.emt_template_content'), 
'emt_description'=> trans('form_lang.emt_description'), 
'emt_status'=> trans('form_lang.emt_status'), 

    ];
    $rules= [
        'emt_template_name'=> 'max:200', 
'emt_template_content'=> 'max:200', 
'emt_description'=> 'max:425', 
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
        //$requestData['emt_created_by']=auth()->user()->usr_Id;
        $status= $request->input('emt_status');
        if($status=="true"){
            $requestData['emt_status']=1;
        }else{
            $requestData['emt_status']=0;
        }
        $requestData['emt_created_by']=1;
        $data_info=Modelgenemailtemplate::create($requestData);
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
    $id=$request->get("emt_id");
    Modelgenemailtemplate::destroy($id);
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