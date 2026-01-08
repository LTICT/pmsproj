<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmspaymentcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\QueryException;
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
        // Use Eloquent to safely fetch the record
        $data_info = Modelpmspaymentcategory::find($id);
        if ($data_info) {
            $data['pms_payment_category_data'] = $data_info;
        } else {
            $data['pms_payment_category_data'] = null;
        }
        $data['page_title']=trans("form_lang.pms_payment_category");
        return view('payment_category.show_pms_payment_category', $data);
    }

    //Get List
    public function listgrid(Request $request){
    $canListData=$this->getSinglePagePermission($request,45,'list',"");
    if(!$canListData){
        return $this->cannotOperate("list");
    }

     $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
     $permissionData=$this->getPagePermission($request,45);
      if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
     }
$filters = [];

    // Build query using Eloquent / Query Builder to avoid SQL injection
    $qb = Modelpmspaymentcategory::query();
    // select explicit columns
    $qb->select(['pyc_id','pyc_name_or','pyc_name_am','pyc_name_en','pyc_description','pyc_create_time','pyc_update_time','pyc_delete_time','pyc_created_by','pyc_status']);

    if ($request->filled('pyc_id')) {
        $qb->where('pyc_id', $request->input('pyc_id'));
        $filters['pyc_id'] = $request->input('pyc_id');
    }
    if ($request->filled('pyc_name_or')) {
        $qb->where('pyc_name_or', $request->input('pyc_name_or'));
        $filters['pyc_name_or'] = $request->input('pyc_name_or');
    }
    if ($request->filled('pyc_name_am')) {
        $qb->where('pyc_name_am', $request->input('pyc_name_am'));
        $filters['pyc_name_am'] = $request->input('pyc_name_am');
    }
    if ($request->filled('pyc_name_en')) {
        $qb->where('pyc_name_en', $request->input('pyc_name_en'));
        $filters['pyc_name_en'] = $request->input('pyc_name_en');
    }
    if ($request->filled('pyc_status')) {
        $qb->where('pyc_status', $request->input('pyc_status'));
        $filters['pyc_status'] = $request->input('pyc_status');
    }

    if ($request->filled('search')) {
        $search = $request->input('search');
        $advanced = $request->input('adva-search');
        if ($advanced === 'on') {
            // 'SOUNDS LIKE' is not supported by query builder; fallback to like
            $qb->where('pyc_name_or', 'like', "%{$search}%");
        } else {
            $qb->where('pyc_name_or', 'like', "%{$search}%");
        }
        $filters['search'] = $search;
    }

    // prepare permission flags per row
    $isEditable = $permissionData->pem_edit ?? 0;
    $isDeletable = $permissionData->pem_delete ?? 0;

    // only cache when there are no filters to avoid returning the wrong dataset
    $cacheKey = 'payment_category:all:perm:' . $isEditable . ':' . $isDeletable;
    if (empty($filters)) {
        $data_info = Cache::remember($cacheKey, 3600, function () use ($qb) {
            return $qb->get();
        });
    } else {
        $data_info = $qb->get();
    }
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>$permissionData->pem_edit ?? 0,'is_role_deletable'=>$permissionData->pem_delete ?? 0,'is_role_can_add'=>$permissionData->pem_insert ?? 0));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
//Update Data
public function updategrid(Request $request)
{
    $id=$request->get("pyc_id");
    $canEditData=$this->getSinglePagePermission($request,45,'update',$id);
    if(!$canEditData){
        return $this->cannotOperate("update");
    }
    $attributeNames = [
        'pyc_name_or'=> trans('form_lang.pyc_name_or'),
'pyc_name_am'=> trans('form_lang.pyc_name_am'),
'pyc_name_en'=> trans('form_lang.pyc_name_en'),
'pyc_description'=> trans('form_lang.pyc_description'),
'pyc_status'=> trans('form_lang.pyc_status'),
    ];
    $rules= [
'pyc_name_or'=> 'required|max:15',
'pyc_name_am'=> 'required|max:15',
'pyc_name_en'=> 'required|max:15',
'pyc_description'=> 'max:425'
    ];
$validationResult = $this->handleLaravelException($request, $attributeNames, $rules, "update", $id);
if ($validationResult !== false) {
    return $validationResult;
}
    try{
        $requestData = $request->all();
        if(isset($id) && !empty($id)){
            $data_info = Modelpmspaymentcategory::find($id);
            if(!isset($data_info) || empty($data_info)){
             return $this->handleUpdateDataException();
            }
            $data_info->update($requestData);
            $ischanged=$data_info->wasChanged();
            if($ischanged){
                //Cache::forget('payment_category');
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
}catch (QueryException $e) {
  return $this->handleDatabaseException($e,"update");
}
}
//Insert Data
public function insertgrid(Request $request)
{
    $canAddData=$this->getSinglePagePermission($request,45,'save',"");
    if(!$canAddData){
        return $this->cannotOperate("save");
    }
$attributeNames = [
    'pyc_name_or' => trans('form_lang.pyc_name_or'),
    'pyc_name_am' => trans('form_lang.pyc_name_am'),
    'pyc_name_en' => trans('form_lang.pyc_name_en'),
    'pyc_description' => trans('form_lang.pyc_description'),
    'pyc_status' => trans('form_lang.pyc_status'),
];
$rules = [
    'pyc_name_or' => 'required|max:15',
    'pyc_name_am' => 'required|max:15',
    'pyc_name_en' => 'required|max:15',
    'pyc_description' => 'max:425',
];
$validationResult = $this->handleLaravelException($request, $attributeNames, $rules, "save");
if ($validationResult !== false) {
    return $validationResult;
}
try {
    $requestData = $request->all();
    $requestData['pyc_created_by'] = auth()->user()->usr_Id;
    $requestData['pyc_created_by'] = 1;
    //dd($requestData);
    $data_info = Modelpmspaymentcategory::create($requestData);
    //Cache::forget('payment_category');
    $data_info['is_editable'] = 1;
    $data_info['is_deletable'] = 1;
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