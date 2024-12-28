<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class MyController extends Controller
{
	public function __construct()
	{		
		//$this->middleware('SetLanguage');	
		//$this->$userId=auth()->user()->usr_Id;
		//dd(config('companyId'));
    //dd($companyId);
	}
	var $userId="";
	var $companyId="";
	function test(){
		echo "something";
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

	public function getSearchSetting($fileName)
	{
		$filePath='public/search/'.$fileName.'.json';
		//$filePath='..\public\search\\'.$fileName.'.json';
		$arrData = array();
		if (is_file($filePath)) {
			$jsonData = file_get_contents($filePath);
			if ($jsonData) {
				$arrData = json_decode($jsonData, true);
			}
		}
		return $arrData;
	}
	public function createNotification($notParameters, $type=false)
	{
		$when = now()->addMinutes(10);
		$user=auth()->user();
		try{
			if($type=="FIREARM"){
				$user->notify((new \App\Notifications\FirearmNot($notParameters))->delay($when));
			}else if($type=="DATABASE"){
				$user->notify((new \App\Notifications\DatabaseBackupNot($notParameters))->delay($when));
			}else{
				$user->notify((new \App\Notifications\UserCreatedNot($notParameters))->delay($when));
			}
		}catch(\Illuminate\Database\QueryException $ex){
		}
	}
	public function getChildObject($fileName, $data)
	{
		if(isset($fileName) && !empty($fileName)){
			$filePath='..\resources\views\\'.$fileName.'.blade.php';
			$data['childObject']=file_get_contents($filePath);
		}
		return $data;
	}

	function displaySearchForm($searchParams=false, Request $request, $objectType=false, $searchComType=false, $showAdvanced=false){
		//var_dump($request->get("mv_name"));
//dd(session()->all());
		//dd(session()->get('pay_month'));
		$searchparamCount=count($searchParams);
		$searchTitle='';
		$searchForm='<div class="input-group" id="search_container" style="float:right;margin-right:-5px">';
		if($showAdvanced){
			$searchForm .= '<div class="input-group print-hide">
			<span class="input-group-btn" style="">
			<input type="text" id="search" name="search" style="width:130px" class="form-control" placeholder=""/>
			</span>
			<span class="input-group-btn" style="padding-right:0">            
			<div class="">
			<label class="form-control">'.trans("form_lang.adv_search").'
			<input type="checkbox" id="adva-search" name="adva-search"></label>
			</div>
			</span>
			</div>';
		}
		if(isset($searchParams) && !empty($searchParams) && $searchparamCount > 0){
			foreach ($searchParams as $key => $row) {
				$columnName=$searchParams[$key]['col_id'];
				$compoType=$searchParams[$key]['type'];
				$inputValue=$request->get("".$columnName.""); //$this->input->post("".$fieldName."");
				if($compoType=='text'){
					$searchForm .= '<span class="input-group-btn"><label class="search-label" for ="'.$columnName.'">'.trans("form_lang.".$columnName."").' </label><input value="'.$inputValue.'" type="text" id="'.$columnName.'" name="'.$columnName.'" class="form-control" placeholder="'.trans("form_lang.".$columnName."").'"></span>';
					if($inputValue){
						$searchTitle .="<span>".trans("form_lang.".$columnName."")." : ". $inputValue."<span><br>";
					}
				}else if($compoType=='number'){
					$searchForm .= '<span class="input-group-btn"><label class="search-label" for ="'.$columnName.'">'.ucfirst($columnName).' </label><input style="width:100px;" value="'.$inputValue.'" type="number" id="'.$columnName.'" name="'.$columnName.'" class="form-control" placeholder="'.ucfirst($columnName).'"></span>';
					if($inputValue){
						$searchTitle .="<span>".trans("form_lang.".$columnName."")." : ". $inputValue."<span><br>";
					}					
				}else if($compoType=='number_range'){
					$startPoint=$request->get("".$columnName."start");
					$endPoint=$request->get("".$columnName."end");
					$searchForm .= '<span class="input-group-btn"><label class="search-label">'.$columnName.' </label><input style="width:100px" value="'.$startPoint.'" type="number" id="'.$columnName.'start" name="'.$columnName.'start" class="form-control" step="0.001" placeholder="'.ucfirst($columnName).'start" ></span><span class="input-group-btn"><label class="search-label">'.$columnName.' </label><input style="width:100px" value="'.$endPoint.'" type="number" id="'.$columnName.'end" name="'.$columnName.'end" class="form-control" step="0.001" 
					placeholder="'.ucfirst($columnName).'end"></span>';
					if($startPoint || $endPoint){
						$searchTitle .="<span>".trans("form_lang.".$columnName."")." : from ". $startPoint ." to ". $endPoint."<span><br>";
					}
				}else if($compoType=='date'){
					$startPoint=$request->get("".$columnName."start");
					$endPoint=$request->get("".$columnName."end");
					$searchForm .= '<span class="input-group-btn"><input style="width:140px" value="'.$startPoint.'" type="text" id="'.$columnName.'start" name="'.$columnName.'start" class="date-picker form-control"  placeholder="From :'.trans("form_lang.".$columnName."").'" ></span><span class="input-group-btn"><input style="width:140px" value="'.$endPoint.'" type="text" id="'.$columnName.'end" name="'.$columnName.'end" class="date-picker form-control" placeholder="To :'.trans("form_lang.".$columnName."").'"></span>';
					if($startPoint || $endPoint){
						$searchTitle .="<span>".trans("form_lang.".$columnName."")." : from ". $startPoint ." to ". $endPoint."<span><br>";
					}					
				}else if($compoType=='dropdown'){
					$tableName=$searchParams[$key]['table_name'];
					$primaryKey=$searchParams[$key]['key_name'];
					$fieldLabel= $searchParams[$key]['field_label'];
					$objectType=$searchParams[$key]['foreign_object'];
					//$ownerfield=$searchParams[$key]['owner_id'];
					//$companyId=session()->get('currentCompany');
					if (array_key_exists('param_one', $searchParams[$key])) {
						$paramOne=$searchParams[$key]['param_one'];
						$paramOneValue=$searchParams[$key]['param_one_value'];
						$dropdownList = $objectType::where(''.$paramOne.'','=', $paramOneValue)->latest()->get();
					}else{						
						$dropdownList = $objectType::latest()->get();
						//dd($dropdownList);
					}
					$searchForm .= '<span class="input-group-btn"><label class="search-label" for ="'.$columnName.'">
					'.trans("form_lang.".$columnName."").'</label><select style="width:190px" data-live-search="true" id="'.$columnName.'" name="'.$columnName.'" class="selectpicker form-control"><option value="">'.trans("form_lang.".$fieldLabel."").'</option>';
					foreach ($dropdownList as $key1 => $value){
						if(($inputValue==$value[''.$primaryKey.''])){
							$searchForm .= '<option selected="true" value="'.$value[''.$primaryKey.''].'">'.$value[''.$fieldLabel.''].'</option>';
							$searchTitle .="<span>".trans("form_lang.".$columnName."")." : ". $value[''.$fieldLabel.'']."<span><br>";
						}else{
							$searchForm .= '<option value="'.$value[''.$primaryKey.''].'">'.$value[''.$fieldLabel.''].'</option>';  
						}
					}
					$searchForm .= '</select></span>';
				}else if($compoType=='foreign-text'){
					$searchForm .= '<span class="input-group-btn"><label class="search-label" for ="'.$columnName.'">'.trans("form_lang.".$columnName."").' </label><input value="'.$inputValue.'" type="text" id="'.$columnName.'" name="'.$columnName.'" class="form-control" placeholder="'.trans("form_lang.".$columnName."").'"></span>';
					if($inputValue){
						$searchTitle .="<span>".trans("form_lang.".$columnName."")." : ". $inputValue."<span><br>";
					}
				}else if($compoType=='boolean'){
					$searchForm .= '<span class="input-group-btn"><label class="search-label" for ="'.$columnName.'">'.ucfirst($columnName).' </label><select style="width:130px" data-live-search="true" id="'.$columnName.'" name="'.$columnName.'" class="selectpicker form-control"><option value="-1">'.trans('form_lang.'.$columnName.'').'</option>';
					if($inputValue==="1"){
						$searchForm .= '<option selected="true" value="1">'.trans('form_lang.yes').'</option>';
						$searchForm .= '<option  Value="0">'.trans('form_lang.no').'</option>';
						$searchTitle .="<span>".trans('form_lang.'.$columnName.'')." : ".trans('form_lang.yes')."<span><br>";
					}else if($inputValue==="0"){
						$searchForm .= '<option  value="1">'.trans('form_lang.yes').'</option>';
						$searchForm .= '<option  selected="true" Value="0">'.trans('form_lang.no').'</option>';
						$searchTitle .="<span>".trans('form_lang.'.$columnName.'')." : ".trans('form_lang.no')."<span><br>";  
					}else{
						$searchForm .= '<option  value="1">'.trans('form_lang.yes').'</option>';
						$searchForm .= '<option  Value="0">'.trans('form_lang.no').'</option>';
					}					
					$searchForm .= '</select></span>';
				}else if($compoType=='custom_enum'){
					$dropdownList=explode(",", $searchParams[$key]['values']);
					$searchForm .= '<span class="input-group-btn"><label class="search-label" for ="'.$columnName.'">'.ucfirst($columnName).' </label><select data-live-search="true" id="'.$columnName.'" name="'.$columnName.'" class="selectpicker form-control" ><option value="-1">'.trans('form_lang.select_one').'</option>';
					foreach ($dropdownList as $key1 => $value){
						if(($inputValue==$value)){
							$searchForm .= '<option selected="true" value="'.$value.'">'.trans("form_lang.".$value."").'</option>';
							$searchTitle .="<span>".trans("form_lang.".$columnName."")." : ".$value."<span><br>";
						}else{
							$searchForm .= '<option value="'.$value.'">'.trans("form_lang.".$value."").'</option>';
						}
					}
					$searchForm .= '</select></span>';
				}else if($compoType=='enum'){
					$tableName=$searchParams[$key]['table_name'];
					$primaryKey=$searchParams[$key]['key_name'];
					$fieldLabel= $searchParams[$key]['field_label'];
					$dropdownList=$this->enumValues(''.$tableName.'', ''.$primaryKey.'');
					$searchForm .= '<span class="input-group-btn"><label class="search-label" for ="'.$columnName.'">
					'.trans("form_lang.".$columnName."").'</label><select style="width:120px" data-live-search="true" id="'.$columnName.'" name="'.$columnName.'" class="selectpicker form-control"><option value="">'.trans("form_lang.all").' '.trans("form_lang.".$fieldLabel."").'</option>';
					foreach ($dropdownList as $key1 => $value){
						if($inputValue==$value){
							$searchForm .= '<option selected="true" value="'.$value.'">'.trans("form_lang.".$value."").'</option>';
							$searchTitle .="<span>".trans("form_lang.".$columnName."")." : ". $value."<span><br>";
						}else if(empty($inputValue) && ((str_contains($fieldLabel,'year') && $value==session()->get('pay_year'))) || (						
							str_contains($fieldLabel,'month') && $value==config()->get('constants.pay_period.PAY_MONTH'))){
							//$this->searchTitle .='<span>'.lang(''.$fieldLabel.'').' : '.lang(''.$value.'').'</span><br>';
							$searchForm .= '<option selected="true" value="'.$value.'">'.$value.'</option>';
						}else{
							$searchForm .= '<option value="'.$value.'">'.trans("form_lang.".$value."").'</option>';  
						}
					}
					$searchForm .= '</select></span>';
				}
			}
		}
		if($searchComType==1){
			$searchForm .='<span class="input-group-btn"><label>&nbsp;</label>
			<a class="btn btn-primary form-control text-white" id="searchButton" name="searchButton">
			<i class="fa fa-search"></i>
			</a>
			</span><span class="input-group-btn">
			<label>&nbsp;</label>
			<a class="btn btn-info pull-right  text-white" id="exportToExcell" name="exportToExcell" title="Buusi" style="height:38px">
			<i class="fa fa-download"></i></a></span>
			<span class="input-group-btn">
			<label>&nbsp;</label>
			<a class="btn btn-warning pull-right text-white" id="print" name="print" title="Print" style="height:38px">
			<i class="fa fa-print"></i></a></span></div>';
		}else{
			$searchForm .='<span class="input-group-btn"><label>&nbsp;</label>
			<button class="btn btn-light form-control" type="submit">
			<i class="fa fa-search"></i>
			</button>
			</span><span class="input-group-btn">
			<label>&nbsp;</label>
			<a class="btn btn-light pull-right" id="exportToExcell" name="exportToExcell" title="Buusi" style="height:38px">
			<i class="fa fa-download"></i></a></span>
			<span class="input-group-btn">
			<label>&nbsp;</label>
			<a class="btn btn-light pull-right" id="print" name="print" title="Print" style="height:38px">
			<i class="fa fa-print"></i></a></span></div>';
		}
		return $searchForm."@".$searchTitle;
	}
	//SEARCH QUERY START
	public function searchQuery($searchParams,$request,$objectName){
		$searchparamCount=count($searchParams);
		if(isset($searchParams) && !empty($searchParams) && $searchparamCount > 0){
			foreach ($searchParams as $key => $row) {
				$columnName=$searchParams[$key]['col_id'];
				$fieldName=$searchParams[$key]['name'];
				$compoType=$searchParams[$key]['type'];
				$inputValue=$request->get("".$columnName."");
				if($compoType=='text' && $inputValue){
					$objectName->where(''.$fieldName.'','like','%'.$inputValue.'%');
				}else if($compoType=='number' && $inputValue){
					$objectName->where(''.$fieldName.'','=',''.$inputValue.'');
				}else if($compoType=='number_range'){
					$startPoint=$request->get("".$columnName."start");
					$endPoint=$request->get("".$columnName."end");
					if($startPoint){
						$objectName->where(''.$fieldName.'','>=',''.$startPoint.'');
					}
					if($endPoint){
						$objectName->where(''.$fieldName.'','<=',''.$endPoint.'');
					}  
				}else if($compoType=='dropdown' && $inputValue){
					$objectName->where(''.$fieldName.'','=',''.$inputValue.'');
				}else if($compoType=='boolean' && $inputValue >- 1){
					$objectName->where(''.$fieldName.'','=',''.$inputValue.'');
				}else if($compoType=='custom_enum' && $inputValue >- 1){
					$objectName->where(''.$fieldName.'','=',''.$inputValue.'');
				}else if($compoType=='enum' && $inputValue >- 1){
					$objectName->where(''.$fieldName.'','=',''.$inputValue.'');
				}
			}
		}
	}
	//SEARCH QUERY END
	//START DROPDOWN
	public function populateDropdown($whereParameters, $request,$objectName){
		/*$label=$dropdownParam['label'];
		$value=$dropdownParam['value'];	*/	
		if(isset($whereParameters) && !empty($whereParameters)){
			foreach ($whereParameters as $key => $row) {
				$columnName=$searchParams[$key]['column_name'];
				$columnValue=$searchParams[$key]['value'];
				$objectName->where(''.$columnName.'','=',''.$columnValue.'');
			}
		}
		return $objectName;
	}
	//END DROPDOWN
	//START EXPLAIN TABLE
	function parse( $table )
	{
		$returnARR = array();
		//$res = $this->ci->db->query( "EXPLAIN `$table`" );
		$res=DB::select(DB::raw("EXPLAIN `$table`"));
		foreach( $res as $field )
		{
			$enum = $this->extract_values( $field->Type );
			$returnARR[ $field->Field ] = array(
				'type'        => ( strpos( $field->Type, '(' ) !== FALSE ) ? $this->extract_type( $field->Type ) : $field->Type,
				'null'        => strtolower( $field->Null ),
				'default'     => $field->Default,
				'max_length'  => $this->extract_length( $field->Type ),
				'enum_values' => ( count( $enum ) == 1 ) ? NULL : $enum,
			);
		}
		return $returnARR;
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
	public function enumValues($tableName=false, $enumField=false)
	{
		$metadata = $this->parse($tableName);
		foreach( $metadata as $k => $md )
		{
			if( !empty( $md['enum_values'] ) )
			{
				$metadata[ $k ]['enum_names'] = $md['enum_values'];                
			} 
		}
		//var_dump($metadata[''.$enumField.'']['enum_values']);
		return $metadata[''.$enumField.'']['enum_values'];
	}
	public function receipents(){
		$users = \App\Modeltblusers::where('usr_notified','=',1)->latest()->pluck('mobile')->implode(', ');	
		return $users;
	}
	//END EXPLAIN TABLE
	//STAR TCHANGE LANMGUAGE
	function switchLanguage(Request $request){
		$selectedLanguage=$request->get("selectedlan");
		$currentLan='oromifa';
		if($selectedLanguage=="Am"){
			$currentLan="amharic";
		}else if($selectedLanguage=="En"){
			$currentLan="english";
		}
		session(['selectedLanguage' => strtolower($selectedLanguage)]);
		$selectedLocale=session()->get('selectedLanguage');	
		//$selectedLocale=session()->get('selectedLanguage');		
		app()->setLocale($selectedLocale);
		//$this->session->set_userdata('selected_lan', $currentLan);
	}
	//END CHANGE LANGUAGE
	public function sendSms($message, $objectId=false){
 //START SMS
		//$recipients = $this->receipents();
		$recipients ="+251912000013";
		//if(isset($recipients) && !empty($recipients)){
		require_once('AfricasTalkingGateway.php');
// Specify your authentication credentials
		$username   = "hrmsumrep";
		$apikey     = "f8eca2836e9e39a3b5090ecd0758a491c78eec9990e8600938edf481b0dd9048";
// Specify the numbers that you want to send to in a comma-separated list
// Please ensure you include the country code (+254 for Kenya in this case)
		//$recipients = "+251912000013";
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
			//START UPDATE DATABASE
				/*$data_info = \App\Modeltbldatabase::find($objectId);
				if(isset($data_info) && !empty($data_info)){
					$updateData['dbb_sms_notified']=$receivedUsers;
					$data_info->update($updateData);
				}*/
			//END UPDATE DATABASE
			}
			catch ( AfricasTalkingGatewayException $e )
			{
				echo "Encountered an error while sending: ".$e->getMessage();
			}
		//}
		}
//START VALIDATE EDIT
		function validateEdit($data,$createdDate, $controllerName){		
			$data_info = \App\Modeltblpages::where('pg_controller','=', $controllerName)->get();
			$result=1;
			if(count($data_info) > 0 && isset($data_info) && !empty($data_info)){

				$daysLength=$data_info[0]['pg_modifying_days'];
				$date1 = new \DateTime($createdDate);
				$date2 = new \DateTime(date('y-m-d'));
				$diff = $date1->diff($date2);
				$daysDiff= $diff->format('%a');
				if($daysDiff < $daysLength){
					$result=1;
				}else{
					$result=0;
				}
			}
			$data['is_editable']=$result;
			return $data;
		}
	//END VALIDATE EDIT
	}