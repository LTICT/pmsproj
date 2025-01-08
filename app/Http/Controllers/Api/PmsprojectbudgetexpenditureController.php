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
        $searchParams= $this->getSearchSetting('pms_project_budget_expenditure');
        $dataInfo = Modelpmsprojectbudgetexpenditure::latest();
        $this->searchQuery($searchParams, $request,$dataInfo);
        $perPage = 20;
        $dataInfo =$dataInfo->paginate($perPage);
        $data['pms_project_budget_expenditure_data']=$dataInfo;
        $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
        $generatedSearchInfo=explode("@", $generatedSearchInfo);
        $generatedSearchForm=$generatedSearchInfo[0];
        $generatedSearchTitle=$generatedSearchInfo[1];
        $data['searchForm']=$generatedSearchForm;
        $data['searchTitle']=$generatedSearchTitle;
        $data['page_title']=trans("form_lang.pms_project_budget_expenditure");
        return view('project_budget_expenditure.list_pms_project_budget_expenditure', $data);
    }
    function getForm(Request $request)
    {
        $id=$request->get('id');
        
        
        $data['is_editable']=1;
        if(isset($id) && !empty($id)){
           $data_info = Modelpmsprojectbudgetexpenditure::findOrFail($id);                
           if(isset($data_info) && !empty($data_info)){
            $controllerName="PmsprojectbudgetexpenditureController";
            $data= $this->validateEdit($data, $data_info['pbe_create_time'], $controllerName);
            $data['pms_project_budget_expenditure_data']=$data_info;
        }
    }
    $data['page_title']=trans("form_lang.pms_project_budget_expenditure");
    $form= view('project_budget_expenditure.form_popup_pms_project_budget_expenditure', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.pms_project_budget_expenditure'));
    return response()->json($resultObject);
    }
    function getListForm(Request $request)
    {
        $id=$request->get('id');
        $data['page_title']='';
        $form= view('project_budget_expenditure.editable_list_pms_project_budget_expenditure', $data)->render();
        $resultObject = array(
            "" => "", "form" => $form, 'page_info'=>trans('form_lang.pms_project_budget_expenditure'));
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
            
            
            $data['page_title']=trans("form_lang.pms_project_budget_expenditure");
            $data['action_mode']="create";
            return view('project_budget_expenditure.form_pms_project_budget_expenditure', $data);
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
            'pbe_reason'=> 'max:200', 
    'pbe_project_id'=> 'max:200', 
    'pbe_budget_code_id'=> 'max:200', 
    'pbe_used_date_ec'=> 'max:200', 
    'pbe_used_date_gc'=> 'max:200', 
    'ppe_amount'=> 'numeric', 
    'pbe_status'=> 'integer', 
    'pbe_description'=> 'max:100', 
    'pbe_created_date'=> 'integer', 

        ]; 
        $validator = Validator::make ( $request->all(), $rules );
        $validator->setAttributeNames($attributeNames);
        if (!$validator->fails()) {
            $requestData = $request->all();
            $requestData['pbe_created_by']=auth()->user()->usr_Id;
            Modelpmsprojectbudgetexpenditure::create($requestData);
            return redirect('project_budget_expenditure')->with('flash_message',  trans('form_lang.insert_success'));
        }else{
            return redirect('project_budget_expenditure/create')
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
            $query='SELECT pbe_id,pbe_reason,pbe_project_id,pbe_budget_code_id,pbe_used_date_ec,pbe_used_date_gc,ppe_amount,pbe_status,pbe_description,pbe_created_by,pbe_created_date,pbe_create_time,pbe_update_time FROM pms_project_budget_expenditure ';       
            
            $query .=' WHERE pbe_id='.$id.' ';
            $data_info=DB::select(DB::raw($query));
            if(isset($data_info) && !empty($data_info)){
                $data['pms_project_budget_expenditure_data']=$data_info[0];
            }
            //$data_info = Modelpmsprojectbudgetexpenditure::findOrFail($id);
            //$data['pms_project_budget_expenditure_data']=$data_info;
            $data['page_title']=trans("form_lang.pms_project_budget_expenditure");
            return view('project_budget_expenditure.show_pms_project_budget_expenditure', $data);
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
            
            
            $data_info = Modelpmsprojectbudgetexpenditure::find($id);
            $data['pms_project_budget_expenditure_data']=$data_info;
            $data['page_title']=trans("form_lang.pms_project_budget_expenditure");
            $data['action_mode']="edit";
            $data['record_id']=$id;
            return view('project_budget_expenditure.form_pms_project_budget_expenditure', $data);
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
            'pbe_reason'=> 'max:200', 
    'pbe_project_id'=> 'max:200', 
    'pbe_budget_code_id'=> 'max:200', 
    'pbe_used_date_ec'=> 'max:200', 
    'pbe_used_date_gc'=> 'max:200', 
    'ppe_amount'=> 'numeric', 
    'pbe_status'=> 'integer', 
    'pbe_description'=> 'max:100', 
    'pbe_created_date'=> 'integer', 

        ];     
        $validator = Validator::make ( $request->all(), $rules );
        $validator->setAttributeNames($attributeNames);
        if (!$validator->fails()) {
         $requestData = $request->all();
         $data_info = Modelpmsprojectbudgetexpenditure::findOrFail($id);
         $data_info->update($requestData);
         $ischanged=$data_info->wasChanged();
         if($ischanged){
             return redirect('project_budget_expenditure')->with('flash_message',  trans('form_lang.update_success'));
         }else{
            return redirect('project_budget_expenditure/'.$id.'/edit')
            ->with('flash_message',trans('form_lang.not_changed') )
            ->withInput();
        }
    }else{
        return redirect('project_budget_expenditure/'.$id.'/edit')
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
            Modelpmsprojectbudgetexpenditure::destroy($id);
            return redirect('project_budget_expenditure')->with('flash_message',  trans('form_lang.delete_success'));
        }
        public function listgrid(Request $request){
         $query='SELECT prj_name,prj_code,pbe_id,pbe_reason,pms_project.prj_name As pbe_project_id,pms_expenditure_code.pec_name As pbe_budget_code, pms_budget_year.bdy_name As pbe_budget_year,pms_budget_month.bdm_month AS pbe_budget_month,pbe_budget_code_id,pbe_budget_year_id,pbe_budget_month_id,pbe_used_date_ec,pbe_used_date_gc,ppe_amount,pbe_status,pbe_description,pbe_created_by,pbe_created_date,pbe_create_time,pbe_update_time,1 AS is_editable, 1 AS is_deletable FROM pms_project_budget_expenditure ';       
         $query .=' INNER JOIN pms_project ON pms_project.prj_id=pms_project_budget_expenditure.pbe_project_id';
         $query .=' INNER JOIN pms_expenditure_code ON pms_expenditure_code.pec_id=pms_project_budget_expenditure.pbe_budget_code_id';
         $query .=' INNER JOIN pms_budget_year ON pms_budget_year.bdy_id=pms_project_budget_expenditure.pbe_budget_year_id';
         $query .=' INNER JOIN pms_budget_month ON pms_budget_month.bdm_id=pms_project_budget_expenditure.pbe_budget_month_id';

         $query .=' WHERE 1=1';
         $pbeid=$request->input('pbe_id');
    if(isset($pbeid) && isset($pbeid)){
    $query .=' AND pbe_id="'.$pbeid.'"'; 
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