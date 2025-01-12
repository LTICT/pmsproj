<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
    use App\Models\Modelpmsprojectbudgetexpenditure;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\Facades\DB;
    //PROPERTY OF LT ICT SOLUTION PLC
    class PmsprojectbudgetexpenditureController extends MyController
    {
       public function __construct()
       {
        parent::__construct();
        //$this->middleware('auth');
    }
     
        public function listgrid(Request $request){
         $query='SELECT prj_name,prj_code,pbe_id,pbe_reason,pms_project.prj_name As pbe_project_id,pms_expenditure_code.pec_name As pbe_budget_code, pms_budget_year.bdy_name As pbe_budget_year,pms_budget_month.bdm_month AS pbe_budget_month,pbe_budget_code_id,pbe_budget_year_id,pbe_budget_month_id,pbe_used_date_ec,pbe_used_date_gc,ppe_amount,pbe_status,pbe_description,pbe_created_by,pbe_created_date,pbe_create_time,pbe_update_time,1 AS is_editable, 1 AS is_deletable FROM pms_project_budget_expenditure ';       
         $query .=' INNER JOIN pms_project ON pms_project.prj_id=pms_project_budget_expenditure.pbe_project_id';
         $query .=' INNER JOIN pms_expenditure_code ON pms_expenditure_code.pec_id=pms_project_budget_expenditure.pbe_budget_code_id';
         $query .=' INNER JOIN pms_budget_year ON pms_budget_year.bdy_id=pms_project_budget_expenditure.pbe_budget_year_id';
         $query .=' INNER JOIN pms_budget_month ON pms_budget_month.bdm_id=pms_project_budget_expenditure.pbe_budget_month_id';

         $query .=' WHERE 1=1';
    $budgetmonth=$request->input('budget_month');
    if(isset($budgetmonth) && isset($budgetmonth)){
    $query .=" AND pbe_budget_month_id='".$budgetmonth."'"; 
    }
    $budgetyear=$request->input('budget_year');
    if(isset($budgetyear) && isset($budgetyear)){
    $query .=" AND pbe_budget_year_id='".$budgetyear."'"; 
    }
    $pbereason=$request->input('pbe_reason');
    if(isset($pbereason) && isset($pbereason)){
    $query .=' AND pbe_reason="'.$pbereason.'"'; 
    }
    $pbeprojectid=$request->input('project_id');
    if(isset($pbeprojectid) && isset($pbeprojectid)){
    $query .=" AND pbe_project_id='".$pbeprojectid."'"; 
    }
    $pbebudgetcodeid=$request->input('pbe_budget_code_id');
    if(isset($pbebudgetcodeid) && isset($pbebudgetcodeid)){
    $query .=' AND pbe_budget_code_id="'.$pbebudgetcodeid.'"'; 
    }
    $pbeuseddateec=$request->input('pbe_used_date_ec');
    if(isset($pbeuseddateec) && isset($pbeuseddateec)){
    $query .=' AND pbe_used_date_ec="'.$pbeuseddateec.'"'; 
    }
    $pbeuseddategc=$request->input('pbe_used_date_gc');
    if(isset($pbeuseddategc) && isset($pbeuseddategc)){
    $query .=' AND pbe_used_date_gc="'.$pbeuseddategc.'"'; 
    }
    $query.=' ORDER BY pbe_id DESC';
    $data_info=DB::select($query);
    $resultObject= array(
        "data" =>$data_info,
        "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1));
    return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
    }
    public function updategrid(Request $request)
    {
        $attributeNames = [
            'pbe_reason'=> trans('form_lang.pbe_reason'), 
    'pbe_project_id'=> trans('form_lang.pbe_project_id'), 
    'pbe_budget_code_id'=> trans('form_lang.pbe_budget_code_id'), 
    'pbe_used_date_ec'=> trans('form_lang.pbe_used_date_ec'), 
    'pbe_used_date_gc'=> trans('form_lang.pbe_used_date_gc'), 
    'ppe_amount'=> trans('form_lang.ppe_amount'), 
    'pbe_status'=> trans('form_lang.pbe_status'), 
    'pbe_description'=> trans('form_lang.pbe_description'), 
    'pbe_created_date'=> trans('form_lang.pbe_created_date'), 

        ];
        $rules= [
    'pbe_reason'=> 'max:100', 
    'pbe_used_date_gc'=> 'max:10', 
    'ppe_amount'=> 'numeric', 
    'pbe_description'=> 'max:425',

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
            $id=$request->get("pbe_id");
            //$requestData['foreign_field_name']=$request->get('master_id');
                //assign data from of foreign key
            $requestData = $request->all();            
            $status= $request->input('pbe_status');
            if($status=="true"){
                $requestData['pbe_status']=1;
            }else{
                $requestData['pbe_status']=0;
            }
            if(isset($id) && !empty($id)){
                $data_info = Modelpmsprojectbudgetexpenditure::findOrFail($id);
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
            //$requestData['pbe_created_by']=auth()->user()->usr_Id;
            $data_info=Modelpmsprojectbudgetexpenditure::create($requestData);
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
            'pbe_reason'=> trans('form_lang.pbe_reason'), 
    'pbe_project_id'=> trans('form_lang.pbe_project_id'), 
    'pbe_budget_code_id'=> trans('form_lang.pbe_budget_code_id'), 
    'pbe_used_date_ec'=> trans('form_lang.pbe_used_date_ec'), 
    'pbe_used_date_gc'=> trans('form_lang.pbe_used_date_gc'), 
    'ppe_amount'=> trans('form_lang.ppe_amount'), 
    'pbe_status'=> trans('form_lang.pbe_status'), 
    'pbe_description'=> trans('form_lang.pbe_description'), 
    'pbe_created_date'=> trans('form_lang.pbe_created_date')
        ];
        $rules= [
    'pbe_reason'=> 'max:100', 
    'pbe_used_date_gc'=> 'max:10', 
    'ppe_amount'=> 'numeric', 
    'pbe_description'=> 'max:425',
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
            //$requestData['pbe_created_by']=auth()->user()->usr_Id;
            $requestData['pbe_created_by']=1;
            $status= $request->input('pbe_status');
            if($status=="true"){
                $requestData['pbe_status']=1;
            }else{
                $requestData['pbe_status']=0;
            }
            $data_info=Modelpmsprojectbudgetexpenditure::create($requestData);
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
    public function deletegrid(Request $request)
    {
        $id=$request->get("pbe_id");
        Modelpmsprojectbudgetexpenditure::destroy($id);
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