<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class MyController extends Controller
{
	public function __construct()
	{		
	}
	var $userId="";
	var $companyId="";
	function test(){
		echo "something";
	}
	public function getQueryInfo($query){
		DB::listen(function ($query) {
    // Display the executed query
    dump($query->sql);
    // Display the query bindings (parameters)
    //dump($query->bindings);
    // Display the query execution time
    dump($query->time);
});
}
public function handleLaravelException($request, $attributeNames,$rules, $actionType, $updateId=false){
	if($actionType=='update'){
	if(!isset($updateId) || empty($updateId)){
		return response()->json([
        "value" => "",
        "status_code" => 457,
        "type" => "",
        "errorMsg" => "",
        "column"=>""
    ],457);
	}
}
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if($validator->fails()) {
	$errorString = implode(",",$validator->messages()->all());
        $resultObject= array(
            "value" =>"",
            "status_code"=>455,
            "type"=>$actionType,
            "errorMsg"=>$errorString
        );
        return response()->json($resultObject,455);
}
return false;
}
public function getDateParameter($objectType){
	$query="SELECT dts_id FROM gen_date_setting
WHERE CURRENT_DATE BETWEEN dts_start_date::DATE AND dts_end_date::DATE 
AND dts_id=".$objectType." ";
		$data_info=DB::select($query);
		if(isset($data_info) && !empty($data_info)){
				return true;
			}
		return false;

}
public function handleDatabaseException($e, $actionType){
    $errorMsg = "database_error";
    $statusCode=$e->errorInfo[0];
    $column="";
    if ($e->errorInfo[0] == '23505') { // Unique key constraint violation in PostgreSQL
        preg_match('/Key \((.*?)\)=/', $e->errorInfo[2], $matches);
        $column = $matches[1] ?? 'not_known';
        //$errorMsg = "Duplicate entry found in column '$column'. Please use a unique value.";
        $statusCode=452;
    } elseif ($e->errorInfo[0] == '23502') { // Not-null constraint violation in PostgreSQL
        preg_match('/null value in column "(.*?)"/', $e->errorInfo[2], $matches);
        $column = $matches[1] ?? 'not_known';
        //$errorMsg = "Column '$column' cannot be null. Please provide a valid value.";
        $statusCode=453;
    }elseif ($statusCode == '23503') { // Foreign key constraint violation in PostgreSQL
        //$errorMsg = "Foreign key constraint violation. Please check related records.";
         $column = $matches[1] ?? 'not_known';
         $statusCode=454;
    }
    elseif ($statusCode == '22001') { // value too long validation violation in PostgreSQL
        //$errorMsg = "Foreign key constraint violation. Please check related records.";
         //$column = $e;
         $statusCode=456;
    }
    
    return response()->json([
        "value" => "",
        "status_code" => $statusCode,
        "type" => $actionType,
        "errorMsg" => $errorMsg,
        "column"=>$column
    ],$statusCode);
	}


	public function handleUpdateIdException($updateId){
    $errorMsg = "database_error";
    return response()->json([
        "value" => "",
        "status_code" => 457,
        "type" => "",
        "errorMsg" => "",
        "column"=>""
    ],457);
	}

	public function handleUpdateDataException(){
    $errorMsg = "database_error";
    //$statusCode=$e->errorInfo[0];    
    return response()->json([
        "value" => "",
        "status_code" => 458,
        "type" => "",
        "errorMsg" => "",
        "column"=>""
    ],458);
	}

	public function getSearchParam($request,$query){
		$userInfo=$this->getUserInfo($request);
    //&& $userInfo->usr_id !=9
		if(isset($userInfo)){
			$userId=$userInfo->usr_id;
			$zoneId=$userInfo->usr_zone_id;
			$woredaId=$userInfo->usr_woreda_id;
			$sectorId=$userInfo->usr_sector_id;
			$departmentId=$userInfo->usr_department_id;
			$prjownerzoneid=$request->input('prj_location_zone_id');
			$prjownerworedaid=$request->input('prj_location_woreda_id');
			$prjName=$request->input('prj_name');
			$prjCode=$request->input('prj_code');
			$include=$request->input('include');
			$userTypeId=$userInfo->usr_user_type;
			$projectDepartmentID=$request->input('prj_department_id');
			$projectSectorID=$request->input('prj_sector_id');
			$query .=" AND prj_owner_type='".$userTypeId."'";

			if(isset($prjName) && isset($prjName)){
				$query .=" AND prj_name LIKE '%".$prjName."%'"; 
			}
			if(isset($prjCode) && isset($prjCode)){
				$query .=" AND prj_code LIKE '%".$prjCode."%'"; 
			}
			if(1==2){
			if(isset($zoneId) && !empty($zoneId) && $zoneId > 0){
				$query .=" AND prj_owner_zone_id='".$zoneId."'";
			}else if(isset($prjownerzoneid) && isset($prjownerzoneid) && $prjownerzoneid>0){
				$query .=" AND prj_owner_zone_id='".$prjownerzoneid."'";   
			}else if($include ==0){
				$query .=" AND prj_owner_zone_id=0"; 
				$query .=" AND prj_owner_woreda_id=0"; 
			}
			if(isset($woredaId) && !empty($woredaId) && $woredaId > 0){
				$query .=" AND prj_owner_woreda_id='".$woredaId."'"; 
			}else if(isset($prjownerworedaid) && isset($prjownerworedaid) && $prjownerworedaid>0){
				$query .=" AND prj_owner_woreda_id='".$prjownerworedaid."'";   
			}else if(isset($prjownerzoneid) && isset($prjownerzoneid) && $include ==0 && $prjownerzoneid > 0){
				$query .=" AND prj_owner_woreda_id=0"; 
			}
		}
			if($userTypeId ==1 ){
			$query .=" AND prj_sector_id IN (SELECT usc_sector_id FROM tbl_user_sector WHERE usc_status=1 AND  usc_user_id=".$userId." )";
		}
			if(isset($sectorId) && !empty($sectorId) && $sectorId > 1){
				//$query .=" AND prj_sector_id='".$sectorId."'";   
			}else if(isset($projectSectorID) && isset($projectSectorID) && $projectSectorID>1){
				//$query .=" AND prj_sector_id='".$projectSectorID."'";   
			}
			if(isset($departmentId) && !empty($departmentId) && $departmentId > 1){
				//$query .=" AND prj_department_id='".$departmentId."'";   
			}else if(isset($projectDepartmentID) && isset($projectDepartmentID) && $projectDepartmentID>1){
				//$query .=" AND prj_department_id='".$projectSectorID."'";   
			}
		}
		 //$this->getQueryInfo($query);
		return $query;
	}
	public function getSearchParamCSO($request,$query){
		$userInfo=$this->getUserInfo($request);
    //&& $userInfo->usr_id !=9
		if(isset($userInfo)){
			$userId=$userInfo->usr_id;
			$zoneId=$userInfo->usr_zone_id;
			$woredaId=$userInfo->usr_woreda_id;
			$sectorId=$userInfo->usr_sector_id;
			$departmentId=$userInfo->usr_department_id;
			$prjownerzoneid=$request->input('prj_location_zone_id');
			$prjownerworedaid=$request->input('prj_location_woreda_id');
			$prjName=$request->input('prj_name');
			$prjCode=$request->input('prj_code');
			$include=$request->input('include');
			$userTypeId=$userInfo->usr_user_type;
			$projectDepartmentID=$request->input('prj_department_id');
			$projectSectorID=$request->input('prj_sector_id');
			$query .=" AND prj_owner_type='".$userTypeId."'";
			if(isset($prjName) && isset($prjName)){
				$query .=" AND prj_name LIKE '%".$prjName."%'"; 
			}
			if(isset($prjCode) && isset($prjCode)){
				$query .=" AND prj_code LIKE '%".$prjCode."%'"; 
			}
			if(isset($zoneId) && !empty($zoneId) && $zoneId > 0){
				$query .=" AND prj_owner_zone_id='".$zoneId."'";
			}else if(isset($prjownerzoneid) && isset($prjownerzoneid) && $prjownerzoneid>0){
				$query .=" AND prj_owner_zone_id='".$prjownerzoneid."'";   
			}else if($include ==0){
				$query .=" AND prj_owner_zone_id=0"; 
				$query .=" AND prj_owner_woreda_id=0"; 
			}

			if(isset($woredaId) && !empty($woredaId) && $woredaId > 0){
				$query .=" AND prj_owner_woreda_id='".$woredaId."'"; 
			}else if(isset($prjownerworedaid) && isset($prjownerworedaid) && $prjownerworedaid>0){
				$query .=" AND prj_owner_woreda_id='".$prjownerworedaid."'";   
			}else if(isset($prjownerzoneid) && isset($prjownerzoneid) && $include ==0 && $prjownerzoneid > 0){
				$query .=" AND prj_owner_woreda_id=0"; 
			}
			//$query .=" AND prj_sector_id IN (SELECT usc_sector_id FROM tbl_user_sector WHERE usc_user_id=".$userId." )";
			if(isset($sectorId) && !empty($sectorId) && $sectorId > 1){
				//$query .=" AND prj_sector_id='".$sectorId."'";   
			}else if(isset($projectSectorID) && isset($projectSectorID) && $projectSectorID>1){
				//$query .=" AND prj_sector_id='".$projectSectorID."'";   
			}
			if(isset($departmentId) && !empty($departmentId) && $departmentId > 1){
				//$query .=" AND prj_department_id='".$departmentId."'";   
			}else if(isset($projectDepartmentID) && isset($projectDepartmentID) && $projectDepartmentID>1){
				//$query .=" AND prj_department_id='".$projectSectorID."'";   
			}
		}
		return $query;
	}

		public function getSearchParamCitizenship($request,$query){
		$userInfo=$this->getUserInfo($request);
    //&& $userInfo->usr_id !=9
		if(isset($userInfo)){
			$userId=$userInfo->usr_id;
			$zoneId=$userInfo->usr_zone_id;
			$woredaId=$userInfo->usr_woreda_id;
			$sectorId=$userInfo->usr_sector_id;
			$departmentId=$userInfo->usr_department_id;
			$userTypeId=$userInfo->usr_user_type;
			$prjownerzoneid=$request->input('prj_location_zone_id');
			$prjownerworedaid=$request->input('prj_location_woreda_id');
			$prjName=$request->input('prj_name');
			$prjCode=$request->input('prj_code');
			$include=$request->input('include');

			$projectDepartmentID=$request->input('prj_department_id');
			$projectSectorID=$request->input('prj_sector_id');
			$query .=" AND prj_owner_type='".$userTypeId."'";
			if(isset($prjName) && isset($prjName)){
				$query .=" AND prj_name LIKE '%".$prjName."%'"; 
			}
			if(isset($prjCode) && isset($prjCode)){
				$query .=" AND prj_code LIKE '%".$prjCode."%'"; 
			}
			if(isset($zoneId) && !empty($zoneId) && $zoneId > 0){
				$query .=" AND prj_owner_zone_id='".$zoneId."'";
			}else if(isset($prjownerzoneid) && isset($prjownerzoneid) && $prjownerzoneid>0){
				$query .=" AND prj_owner_zone_id='".$prjownerzoneid."'";   
			}else if($include ==0){
				$query .=" AND prj_owner_zone_id=0"; 
				$query .=" AND prj_owner_woreda_id=0"; 
			}

			if(isset($woredaId) && !empty($woredaId) && $woredaId > 0){
				$query .=" AND prj_owner_woreda_id='".$woredaId."'"; 
			}else if(isset($prjownerworedaid) && isset($prjownerworedaid) && $prjownerworedaid>0){
				$query .=" AND prj_owner_woreda_id='".$prjownerworedaid."'";   
			}else if(isset($prjownerzoneid) && isset($prjownerzoneid) && $include ==0 && $prjownerzoneid > 0){
				$query .=" AND prj_owner_woreda_id=0"; 
			}
			//$query .=" AND prj_sector_id IN (SELECT usc_sector_id FROM tbl_user_sector WHERE usc_user_id=".$userId." )";
			if(isset($sectorId) && !empty($sectorId) && $sectorId > 1){
				//$query .=" AND prj_sector_id='".$sectorId."'";   
			}else if(isset($projectSectorID) && isset($projectSectorID) && $projectSectorID>1){
				//$query .=" AND prj_sector_id='".$projectSectorID."'";   
			}
			if(isset($departmentId) && !empty($departmentId) && $departmentId > 1){
				//$query .=" AND prj_department_id='".$departmentId."'";   
			}else if(isset($projectDepartmentID) && isset($projectDepartmentID) && $projectDepartmentID>1){
				//$query .=" AND prj_department_id='".$projectSectorID."'";   
			}
		}
		return $query;
	}
	public function getSearchParamOld($request,$query){
		$userInfo=$this->getUserInfo($request);
    //&& $userInfo->usr_id !=9
		if(isset($userInfo)){
			$zoneId=$userInfo->usr_zone_id;
			$woredaId=$userInfo->usr_woreda_id;
			$sectorId=$userInfo->usr_sector_id;
			$departmentId=$userInfo->usr_department_id;
			$prjownerzoneid=$request->input('prj_location_zone_id');
			$prjownerworedaid=$request->input('prj_location_woreda_id');
			$prjName=$request->input('prj_name');
			$prjCode=$request->input('prj_code');
			$include=$request->input('include');
			$departmentID=$request->input('prj_department_id');
			if(isset($prjName) && isset($prjName)){
				$query .=" AND prj_name LIKE '%".$prjName."%'"; 
			}
			if(isset($prjCode) && isset($prjCode)){
				$query .=" AND prj_code LIKE '%".$prjCode."%'"; 
			}

			if(isset($zoneId) && !empty($zoneId) && $zoneId > 0){
				$query .=" AND prj_owner_zone_id='".$zoneId."'";
			}else if(isset($prjownerzoneid) && isset($prjownerzoneid) && $prjownerzoneid>0){
				$query .=" AND prj_owner_zone_id='".$prjownerzoneid."'";   
			}

			if(isset($woredaId) && !empty($woredaId) && $woredaId > 0){
				$query .=" AND prj_owner_woreda_id='".$woredaId."'"; 
			}else if(isset($prjownerworedaid) && isset($prjownerworedaid) && $prjownerworedaid>0){
				$query .=" AND prj_owner_woreda_id='".$prjownerworedaid."'";   
			}

			if(isset($sectorId) && !empty($sectorId) && $sectorId > 0){
				$query .=" AND prj_sector_id='".$sectorId."'";   
			}else if(isset($prjownerworedaid) && isset($prjownerworedaid) && $prjownerworedaid>0){
				$query .=" AND prj_owner_woreda_id='".$prjownerworedaid."'";   
			}

			if(isset($departmentId) && !empty($departmentId) && $departmentId > 0){
				//$query .=" AND prj_department_id='".$departmentId."'";   
			}
		}
		return $query;
	}
	public function getAllTabPermission($request){
		$authenticatedUser = $request->authUser;
		$userId=$authenticatedUser->usr_id;
		$query="SELECT pag_id
		FROM tbl_permission 
		INNER JOIN tbl_pages ON tbl_pages.pag_id=tbl_permission.pem_page_id
		INNER JOIN tbl_user_role ON tbl_permission.pem_role_id=tbl_user_role.url_role_id 
		WHERE url_user_id=".$userId." AND pag_appear_tab=1 AND pem_view='1' ORDER BY pag_order_number ASC";
		$data_info=DB::select($query);
    $allowedTabs = [];

    foreach ($data_info as $item) {
            // Add to allowedTabs if page_id does not match the specified values
            $allowedTabs[] = $item->pag_id;
    }
    // Return both arrays
    return [
        'allowedTabs' => $allowedTabs,
    ];
		//END TEST
		/*$pagIds = array_map(function ($item) {
			return $item->pag_id;
		}, $data_info);
		return $pagIds;*/
	}
	public function getPagePermission($request,$pageId, $PageInfo=false){
		$authenticatedUser = $request->authUser;
		$userId=$authenticatedUser->usr_id;
		$sectorId=$authenticatedUser->usr_sector_id;
		$departmentId=$authenticatedUser->usr_department_id;

		$zoneId=$authenticatedUser->usr_zone_id;
		$woredaId=$authenticatedUser->usr_woreda_id;

		//dd($sectorId);
	/*if($zoneId==0 && $woredaId==0 && $sectorId==1){
		if($PageInfo=="project_info"){
			return null;
		}
	}else if($zoneId==0 && $woredaId==0){

	}*/
		/*if($PageInfo=="project_info" && ($sectorId==1 || $departmentId==1) && $zoneId==0 && $woredaId==0){
			return null;
		}*/
		$query="SELECT MIN(pem_role_id) AS pem_role_id, MIN(pem_page_id) AS pem_page_id,
    MIN(tbl_permission.pem_insert) AS pem_edit,  
    MIN(tbl_permission.pem_delete) AS pem_delete,
    MIN(tbl_permission.pem_insert) AS pem_insert,  
    MIN(tbl_permission.pem_enabled) AS pem_enabled
		FROM tbl_permission 
		INNER JOIN tbl_user_role ON tbl_permission.pem_role_id=tbl_user_role.url_role_id 
		WHERE url_user_id=".$userId." AND pem_page_id=".$pageId." GROUP BY url_user_id";
		$data_info=DB::select($query);
		// $this->getQueryInfo($query);
		if(isset($data_info) && !empty($data_info)){
			return $data_info[0];
		}
		return null;
	}
	public function cannotOperate($operation){
    $errorMsg = "database_error";
    $statusCode=0;
    if($operation=='save'){
    	$statusCode=459;
    }else if($operation=='update'){
    	$statusCode=460;
    }else if($operation=='list'){
    	$statusCode=461;
    }
    return response()->json([
        "value" => "",
        "status_code" => $statusCode,
        "type" => $operation,
        "errorMsg" => "",
        "column"=>""
    ],$statusCode);
	}
public function getSinglePagePermissionProject($request,$pageId, $operation, $singleDataId=false, $projectId=false){
	$result=false;
	$insertPermission=$this->getSinglePagePermission($request,$pageId, $operation,"");	
	if($insertPermission){
	$userInfo=$this->getUserInfo($request);
		if(isset($userInfo)){
			$userId=$userInfo->usr_id;			
			$userTypeId=$userInfo->usr_user_type;
			if($userTypeId ==1 ){
					$query =" SELECT prj_id FROM pms_project WHERE prj_id=".$projectId." AND prj_sector_id IN (SELECT usc_sector_id FROM tbl_user_sector WHERE usc_status=1 AND  usc_user_id=".$userId." )";
			$data_info=DB::select($query);
		if(isset($data_info) && !empty($data_info)){
			$result=true;
					}
	}
}
}
return $result;
}
	public function getSinglePagePermission($request,$pageId, $operation, $singleDataId=false){
		if($operation=='list'){
			return true;
		}
		$authenticatedUser = $request->authUser;
		$userId=$authenticatedUser->usr_id;
		$sectorId=$authenticatedUser->usr_sector_id;
		$departmentId=$authenticatedUser->usr_department_id;

		$zoneId=$authenticatedUser->usr_zone_id;
		$woredaId=$authenticatedUser->usr_woreda_id;
		$query="SELECT MIN(pem_role_id) AS pem_role_id, MIN(pem_page_id) AS pem_page_id,
    MIN(tbl_permission.pem_edit) AS pem_edit,  
    MIN(tbl_permission.pem_delete) AS pem_delete,
    MIN(tbl_permission.pem_insert) AS pem_insert,  
    MIN(tbl_permission.pem_enabled) AS pem_enabled,
    MIN(tbl_permission.pem_show) AS pem_view
		FROM tbl_permission 
		INNER JOIN tbl_user_role ON tbl_permission.pem_role_id=tbl_user_role.url_role_id 
		WHERE url_user_id=".$userId." AND pem_page_id=".$pageId." GROUP BY url_user_id";
		$data_info=DB::select($query);
		//dd($data_info);
		// $this->getQueryInfo($query);
		if(isset($data_info) && !empty($data_info)){
			if($operation=='update'){
				if($data_info[0]->pem_edit == 1){
					return true;
				}
			}else if($operation=='save'){
				if($data_info[0]->pem_insert == 1){
					return true;
				}
			}else if($operation=='list'){
				if($data_info[0]->pem_view == 1){
					return true;
				}
			}
		}
		return false;
	}

	public function getTabPermission($request){
		$authenticatedUser = $request->authUser;
		$userId=$authenticatedUser->usr_id;
		$query="SELECT pag_id
		FROM tbl_permission 
		INNER JOIN tbl_pages ON tbl_pages.pag_id=tbl_permission.pem_page_id
		INNER JOIN tbl_user_role ON tbl_permission.pem_role_id=tbl_user_role.url_role_id 
		WHERE url_user_id=".$userId." AND pag_appear_tab=1 AND pem_view='1'";
		$data_info=DB::select($query);
		//STAR TEST
	  $allowedLinks = [];
    $allowedTabs = [];

    foreach ($data_info as $item) {
        if (in_array($item->pag_id, [34, 39, 61])) {
            // Add to allowedLinks if page_id matches the specified values
            $allowedLinks[] = $item->pag_id;
        } else {
            // Add to allowedTabs if page_id does not match the specified values
            $allowedTabs[] = $item->pag_id;
        }
    }
    // Return both arrays
    return [
        'allowedLinks' => $allowedLinks,
        'allowedTabs' => $allowedTabs,
    ];
		//END TEST
		/*$pagIds = array_map(function ($item) {
			return $item->pag_id;
		}, $data_info);
		return $pagIds;*/
	}
	public function ownsProject($request,$projectId){
		return 1;
		if(isset($projectId)){
			$userInfo = $request->authUser;
			$zoneId=$userInfo->usr_zone_id;
			$woredaId=$userInfo->usr_woreda_id;
			$sectorId=$userInfo->usr_sector_id;
			$departmentId=$userInfo->usr_department_id;
			$userId=$userInfo->usr_id;
			$userType=$userInfo->usr_user_type;
			$query="SELECT prj_id FROM pms_project ";
			$query .="WHERE prj_id=".$projectId." ";
			$query .=" AND prj_owner_zone_id='".$zoneId."'"; 
			$query .=" AND prj_owner_woreda_id='".$woredaId."'";

			//user sector should be set
			if($userType==1){
			$query .=" AND prj_sector_id IN (SELECT usc_sector_id FROM tbl_user_sector WHERE usc_status=1 AND usc_user_id=".$userId." )";
		    }
			if(isset($zoneId) && isset($zoneId) && $zoneId > 0){
				
			}
			if(isset($woredaId) && isset($woredaId) && $woredaId > 0){
				
			}
      //AND prj_owner_woreda_id=usr_woreda_id WHERE usr_id=".$userId."
			//AND prj_department_id=
			//AND prj_sector_id=".$sectorId." 
			//$this->getQueryInfo($query);
			$data_info=DB::select($query);
			//dd($data_info);
			if(isset($data_info) && !empty($data_info)){
				return $data_info[0];
			}
			return null;
		}

	}

	public function getUserInfo(Request $request){
		$authenticatedUser = $request->authUser;
		$userId=$authenticatedUser->usr_id;
		$query="SELECT usr_full_name,usr_user_type,usr_owner_id, usr_directorate_id,usr_team_id,usr_officer_id,usr_id,usr_zone_id,usr_woreda_id,usr_department_id,usr_sector_id
		FROM tbl_users 
		WHERE usr_id=".$userId."";
		$data_info=DB::select($query);
		if(isset($data_info) && !empty($data_info)){
			return $data_info[0];
		}
		return null;
	}
	public function receipents(){
		$users = \App\Modeltblusers::where('usr_notified','=',1)->latest()->pluck('mobile')->implode(', ');	
		return $users;
	}
	public function sendSms($message, $objectId=false){
 //START SMS
		$recipients ="+251912000013";
		//if(isset($recipients) && !empty($recipients)){
		require_once('AfricasTalkingGateway.php');
// Specify your authentication credentials
		$username   = "hrmsumrep";
		$apikey     = "f8eca2836e9e39a3b5090ecd0758a491c78eec9990e8600938edf481b0dd9048";
// Specify the numbers that you want to send to in a comma-separated list
// Please ensure you include the country code (+254 for Kenya in this case)
        //$recipients = $_POST["from"];
// And of course we want our recipients to know what we really do
// Create a new instance of our awesome gateway class
		$gateway    = new AfricasTalkingGateway($username, $apikey);
		try 
		{ 
  // Thats it, hit send and we'll take care of the rest.
			$responses = $gateway->sendMessage($recipients, $message, "LTICT");
  //    $responses = "";
			$receivedUsers="";
			foreach($responses as $response) {
    // status is either "Success" or "error message"
				$receivedUsers .=",". $response->number;
				echo " Number: " .$response->number;
				echo " Status: " .$response->status;
				echo " StatusCode: " .$response->statusCode;
				echo " MessageId: " .$response->messageId;
				echo " Cost: "   .$response->cost."\n";
			}
		}
		catch ( AfricasTalkingGatewayException $e )
		{
			echo "Encountered an error while sending: ".$e->getMessage();
		}
		//}
	}
}