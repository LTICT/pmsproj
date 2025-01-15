<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
	public function getSearchParam($request,$query){
    $userInfo=$this->getUserInfo($request);
        if(isset($userInfo) && $userInfo->usr_id !=9){
            $zoneId=$userInfo->usr_zone_id;
            $woredaId=$userInfo->usr_woreda_id;
            $sectorId=$userInfo->usr_sector_id;
            $prjownerzoneid=$request->input('prj_location_zone_id');
            $prjownerworedaid=$request->input('prj_location_woreda_id');
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
            if(isset($sectorId) && !empty($sectorId)){
              $query .=" AND prj_sector_id='".$sectorId."'";   
            }
        }
return $query;
}

public function getPagePermission($request,$pageId){
		$authenticatedUser = $request->authUser;
        $userId=$authenticatedUser->usr_id;
        $query="SELECT tbl_permission.*
     FROM tbl_permission 
     INNER JOIN tbl_user_role ON tbl_permission.pem_role_id=tbl_user_role.url_role_id 
     WHERE url_user_id=".$userId." AND pem_page_id=".$pageId."";
     $data_info=DB::select($query);
     if(isset($data_info) && !empty($data_info)){
     	return $data_info[0];
     }
     return null;
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
     $pagIds = array_map(function ($item) {
    return $item->pag_id;
}, $data_info);
     return $pagIds;
	}
public function ownsProject($request,$projectId){
	$authenticatedUser = $request->authUser;
    $userId=$authenticatedUser->usr_id;
    $query="SELECT usr_id,usr_zone_id,usr_woreda_id,usr_department_id,usr_sector_id
     FROM tbl_users INNER JOIN pms_project ON pms_project.prj_owner_zone_id=usr_zone_id 
      AND prj_id =".$projectId." WHERE usr_id=".$userId." ";
      //AND prj_owner_woreda_id=usr_woreda_id WHERE usr_id=".$userId."
     $data_info=DB::select($query);
      if(isset($data_info) && !empty($data_info)){
     	return $data_info[0];
     }
     return null;
}
	public function getUserInfo(Request $request){
		 $authenticatedUser = $request->authUser;
        $userId=$authenticatedUser->usr_id;
        $query="SELECT usr_id,usr_zone_id,usr_woreda_id,usr_department_id,usr_sector_id
     FROM tbl_users 
     WHERE usr_id=".$userId."";
     $data_info=DB::select($query);
     if(isset($data_info) && !empty($data_info)){
     	return $data_info[0];
     }
     return null;
	}
	function extract_type( $field_type )
	{
		$ret = explode( '(', $field_type );
		return $ret[0];
	}
	function extract_length( $field_type )
	{
		preg_match( '/\((.*)\)/', $field_type, $matches );
		settype( $matches[1], 'int' );
		return ( substr( $field_type, 0, 4 ) == 'enum' ) ? NULL : $matches[1];
	}
	function extract_values( $field_type )
	{
		preg_match( '/\((.*)\)/', $field_type, $matches );
		if( !empty( $matches ) ) 
		{
			$matches[1] = explode( ',', str_replace(  "'", '', $matches[1] ) );
			return $matches[1];
		}
		else
		{
			return array();
		}
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