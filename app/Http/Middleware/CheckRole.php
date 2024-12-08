<?php

namespace App\Http\Middleware;
use App\Modeltblpermission;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Modeltblaccesslog;
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      //$model=app($model)->find($id);
      //var_dump($model->emp_id);

      $roleId=auth()->user()->roles()->first()->rol_id;
      $userId=auth()->user()->usr_Id;
      $method = $request->method();
      //var_dump($request->path());
      $fullUrl=request()->route()->getAction()["controller"];
      //var_dump($fullUrl);
      //var_dump($request->fullUrl());
      $controllerFullPath=explode("@", $fullUrl)[0];
      $methodName=explode("@", $fullUrl)[1];
      $controllerArray=explode("\\", $controllerFullPath);
      $controllerName=end($controllerArray);
      $objectId=$request->segment(2);
      //START VIEW ACCESS LOG
      /*$controllerFullPath=explode("@", $fullUrl)[0];
      $methodName=explode("@", $fullUrl)[1];
      $controllerArray=explode("\\", $controllerFullPath);
      $controllerName=end($controllerArray);
      $objectId=$request->segment(2);*/
      session(['currentController' => $controllerName, 'objectId'=>$objectId]);
  //END VIEW ACCSS LOG
      //var_dump($methodName);
      //var_dump($controllerName);
      //var_dump($request->segment(2));
      
      $actionName=$methodName;
      //START ACCESSLOG
      $data['acl_detail']=$request->path();
      $data['acl_object_id']=$objectId;
      $data['acl_object_action']=strtolower($actionName);
      //$data['acl_date']="--";

      $data['acl_ip']=$request->ip();
      $data['acl_user']=$userId;
      $data['acl_object_name']=$controllerName;
      $data['acl_role']=$roleId;

      //$data = $request->all();
      //$data['vcd_created_by']=auth()->user()->usr_Id;
      Modeltblaccesslog::create($data);
      //END ACCESS LOG

      $permission=$res=DB::select(DB::raw("select pm_search,pem_id,pm_enabled,pm_edit,pm_insert,pm_view,pm_delete,pm_show,pm_role,pm_description,
       pg_name, tbl_pages.pag_id as pm_pg_id from tbl_permission INNER JOIN tbl_pages
       ON tbl_pages.pag_id=tbl_permission.pm_pg_id WHERE pm_role='$roleId' AND pg_controller= '$controllerName' "));
      if(1==2){
       if(isset($permission) && !empty($permission)){

        $permissionEnabled=$permission[0]->pm_enabled;
        //var_dump($permissionEnabled);
            //check if permission is enabled  
        //var_dump($permission);
        if($permissionEnabled=='1'){
          $permissionView=$permission[0]->pm_view;
          $permissionEdit=$permission[0]->pm_edit;
          $permissionDelete=$permission[0]->pm_delete;
          $permissionInsert=$permission[0]->pm_insert;
          $permissionShow=$permission[0]->pm_show;
          $permissionSearch=$permission[0]->pm_search;
          $requestType=$methodName;
          if($requestType=='index' || $requestType=='listGrid') {
            if($permissionView=='0'){
             return $next($request);
           }else if($permissionView=='1'){

           }else if($permissionView=='2'){
                        //show all records
           }else if($permissionView=='3'){
           }
         }
       }
     }
   }
   return $next($request);
   /*return response([
    'error' => [
      'code' => 'INSUFFICIENT_ROLE',
      'description' => 'You are not authorized to access this resource.',
    ],
  ], 401);*/
}

private function getRequiredRoleForRoute($route)
{
  $actions = $route->getAction();

  return isset($actions['roles']) ? $actions['roles'] : null;
}
}
