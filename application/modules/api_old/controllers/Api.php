<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
require_once(APPPATH . '../vendor/autoload.php');

use OpenTok\OpenTok;
use OpenTok\MediaMode;
use OpenTok\ArchiveMode;
use OpenTok\Session;
use OpenTok\Role;

class Api extends REST_Controller  {

    function __construct()
    {
        parent::__construct();
        $this->load->model('api_model','api');
        $this->load->model('user_model','user');

        // $this->load->model(array('Client','Estimate','Invoice','App'));
        $this->load->helper('fopdf/invoicer');        
    
        $this->applib->set_locale();
        $header =  getallheaders(); // Get Header Data

        $token  = '';
        if(!empty($header['token'])){ $token = $header['token'];        }
        
        if(empty($token)){ 
            if(!empty($header['Token'])){ $token = $header['Token']; }
        }
        if (empty($token)) {
            $this->is_valid = FALSE;    
        }if(!empty($token)){

            $valid = $this->user->is_valid_token($token);

            if($valid){
                $this->is_valid = TRUE;    
            }else{
                $this->is_valid = FALSE;
            }
            
        }
        $this->token             = $token;
        $this->success           = 'Success';
        $this->no_result_found   = 'No result were found';
        $this->invalid_token     = 'Invalid token or Token missing';
        $this->required_input    = 'Required input missing';
        $this->permission_denied = 'Permission denied for this action';
        $this->already_exists    = 'Already exists';
        $this->already_applyed   = 'Leave already applied';
        $this->something         = 'Something went wrong, please try again later.';
        $this->password_mismatch = 'New password and confirm password not match';
        $this->no_deviceid       = 'Your deviceId not register';

        $this->apiKey = '46183542';
        $this->apiSecret = '6ce8dcd830d428d202903a9567c460bf7eebc432';

    }
    
    public function employee_list_post(){

     if($this->is_valid == TRUE)   {

        $data = array();
        $response = array();
        $response['status_code'] = -1;
        $response['message'] = $this->required_input;
        $response['data'] = $data;

        $inputs = $this->post();
        $inputs['limit'] = 10;
        $result_count = $this->api->employee_list($this->token,$inputs,1);    
        $result_list = $this->api->employee_list($this->token,$inputs,2);    
        $page = !empty($inputs['page'])?$inputs['page']:1;
        $result_count = ceil($result_count/$inputs['limit']);
        $next_page    = $page + 1;
        $next_page    = ($next_page <=$result_count)?$next_page:-1;
        $result = array('next_page'=>$next_page,'current_page'=>$page,'list'=>$result_list);
        if(!empty($result_count)){
            $response['status_code'] = 1;
            $response['message'] = $this->success;
            $response['data'] = $result;
        }else{
            $response['status_code'] = 0;
            $response['message'] = $this->no_result_found;
        }

        $this->response($response, REST_Controller::HTTP_OK);
    }else{

        $this->token_error();
    }

}

public function departments_get(){

   if($this->is_valid == TRUE)   {

    $data = array();
    $response = array();
    $response['status_code'] = -1;
    $response['message'] = $this->no_result_found;
    $response['data'] = $data;
    $result = $this->api->departments($this->token);    

    if(!empty($result)){
        $response['status_code'] = 1;
        $response['message'] = $this->success;
        $response['data'] = $result;
    }else{
        $response['status_code'] = 0;
        $response['message'] = $this->no_result_found;
    }

    $this->response($response, REST_Controller::HTTP_OK);
}else{

    $this->token_error();
}
}

public function profile_post(){

   if($this->is_valid == TRUE)   {

    $data = array();
    $response = array();
    $response['status_code'] = -1;
    $response['message'] = $this->no_result_found;
    $response['data'] = $data;
    $input = $this->post();
    $result = $this->api->view_profile($this->token,$input);    
    $result['avatar'] = base_url().'assets/uploads/'.$result['avatar'];

    if(!empty($result)){
        $response['status_code'] = 1;
        $response['message'] = $this->success;
        $response['data'] = $result;
    }else{
        $response['status_code'] = 0;
        $response['message'] = $this->no_result_found;
    }

    $this->response($response, REST_Controller::HTTP_OK);
}else{

    $this->token_error();
}
}

public function remove_profile_post(){

   if($this->is_valid == TRUE)   {

    $data = array();
    $response = array();
    $response['status_code'] = -1;
    $response['message'] = $this->no_result_found;
    $response['data'] = $data;
    $inputs = $this->post();
    if(!empty($inputs['user_id'])){
        $result = $this->api->remove_profile($this->token,$inputs);    

        if(!empty($result)){
            $response['status_code'] = 1;
            $response['message'] = $this->success;
            $response['data'] = $data;
        }else{
            $response['status_code'] = 0;
            $response['message'] = $this->no_result_found;
        }
    }else{
        $response['status_code'] = 0;
        $response['message'] = $this->required_input;
    }   
    $this->response($response, REST_Controller::HTTP_OK);
}else{

    $this->token_error();
}
}

public function view_profile_post(){

   if($this->is_valid == TRUE)   {

    $data = array();
    $response = array();
    $response['status_code'] = -1;
    $response['message'] = $this->no_result_found;
    $response['data'] = $data;

    $inputs = $this->post();

    if(!empty($inputs['email']) && !empty($inputs['fullname'])&& !empty($inputs['phone'])){

        $result = $this->api->profile_update($this->token,$inputs);    

        if(!empty($result)){
            $response['status_code'] = 1;
            $response['message'] = $this->success;
            $response['data'] = $data;
        }else{
            $response['status_code'] = 0;
            $response['message'] = $this->no_result_found;
        }
    }else{
        $response['status_code'] = 0;
        $response['message'] = $this->required_input;
    }
    $this->response($response, REST_Controller::HTTP_OK);
}else{

    $this->token_error();
}
}

public function forgot_password_post(){

         // if($this->is_valid == TRUE)   {

    $data = array();
    $response = array();
    $response['status_code'] = -1;
    $response['message'] = $this->no_result_found;
    $response['data'] = $data;

    $inputs = $this->post();

    if(!empty($inputs['username'])) {

        $result = $this->api->forgot_password('',$inputs);    

        if($result){
            $response['status_code'] = 1;
            $response['message'] = $this->success;
            $response['data'] = $data;
        }else{
            $response['status_code'] = 0;
            $response['message'] = $this->something;
        }

    }else{
        $response['status_code'] = 0;
        $response['message'] = $this->required_input;
    }
    $this->response($response, REST_Controller::HTTP_OK);
        // }else{

        //     $this->token_error();
        // }
}

public function change_password_post(){

   if($this->is_valid == TRUE)   {

    $data = array();
    $response = array();
    $response['status_code'] = -1;
    $response['message'] = $this->no_result_found;
    $response['data'] = $data;

    $inputs = $this->post();

    if(!empty($inputs['new_password']) && !empty($inputs['confirm_password'])) {
        if($inputs['new_password'] ===$inputs['confirm_password']){
            $result = $this->api->change_password($this->token,$inputs);    

            if($result){
                $response['status_code'] = 1;
                $response['message'] = $this->success;
                $response['data'] = $data;
            }else{
                $response['status_code'] = 0;
                $response['message'] = $this->something;
            }
        }else{
            $response['status_code'] = 0;
            $response['message'] = $this->password_mismatch;  
        }
    }else{
        $response['status_code'] = 0;
        $response['message'] = $this->required_input;
    }
    $this->response($response, REST_Controller::HTTP_OK);
}else{

    $this->token_error();
}
}

public function leave_type_get(){

   if($this->is_valid == TRUE)   {

    $data = array();
    $response = array();
    $response['status_code'] = -1;
    $response['message'] = $this->no_result_found;
    $response['data'] = $data;
    $result = $this->api->leave_type($this->token);    

    if(!empty($result)){
        $response['status_code'] = 1;
        $response['message'] = $this->success;
        $response['data'] = $result;
    }else{
        $response['status_code'] = 0;
        $response['message'] = $this->no_result_found;
    }

    $this->response($response, REST_Controller::HTTP_OK);
}else{

    $this->token_error();
}
}


public function create_department_post(){

   if($this->is_valid == TRUE)   {

    $data = array();
    $response = array();
    $response['status_code'] = -1;
    $response['message'] = $this->required_input;
    $response['data'] = $data;
    $inputs = $this->post();

    if(!empty($inputs['department'])){

        $result = $this->api->create_department($this->token,$inputs);    

        if($result ==1 ){
            $response['status_code'] = 1;
            $response['message'] = $this->success;
            $response['data'] = $data;
        }elseif($result ==2 ){
            $response['status_code'] = 0;
            $response['message'] = $this->already_exists;
            $response['data'] = $data;
        }else{
            $response['status_code'] = 0;
            $response['message'] = $this->permission_denied;
        }
    }else{
        $response['status_code'] = 0;
        $response['message'] = $this->required_input;
    }
    $this->response($response, REST_Controller::HTTP_OK);
}else{

    $this->token_error();
}
}

public function leave_cancel_post(){


   if($this->is_valid == TRUE)   {

    $data = array();
    $response = array();
    $response['status_code'] = -1;
    $response['message'] = $this->required_input;
    $response['data'] = $data;
    $inputs = $this->post();

    if(!empty($inputs['leave_id']) && !empty($inputs['leave_status'])){

        $result = $this->api->leave_cancel($this->token,$inputs);    

        if($result){
            $response['status_code'] = 1;
            $response['message'] = $this->success;
            $response['data'] = $data;
        }else{
            $response['status_code'] = 0;
            $response['message'] = $this->permission_denied;
        }
    }else{
        $response['status_code'] = 0;
        $response['message'] = $this->required_input;
    }
    $this->response($response, REST_Controller::HTTP_OK);
}else{

    $this->token_error();
}

}     public function leave_approve_reject_post(){


   if($this->is_valid == TRUE)   {

    $data = array();
    $response = array();
    $response['status_code'] = -1;
    $response['message'] = $this->required_input;
    $response['data'] = $data;
    $inputs = $this->post();

    if(!empty($inputs['leave_id']) && !empty($inputs['leave_status'])){

        $result = $this->api->leave_approve_reject($this->token,$inputs);    

        if($result){
            $response['status_code'] = 1;
            $response['message'] = $this->success;
            $response['data'] = $data;
        }else{
            $response['status_code'] = 0;
            $response['message'] = $this->permission_denied;
        }
    }else{
        $response['status_code'] = 0;
        $response['message'] = $this->required_input;
    }
    $this->response($response, REST_Controller::HTTP_OK);
}else{

    $this->token_error();
}

}    

public function leave_apply_post(){

   if($this->is_valid == TRUE)   {

    $data = array();
    $response = array();
    $response['status_code'] = -1;
    $response['message'] = $this->required_input;
    $response['data'] = $data;
    $inputs = $this->post();

    if(!empty($inputs['leave_from']) && !empty($inputs['leave_to']) && !empty($inputs['leave_type']) && !empty($inputs['leave_reason'])){

        $result = $this->api->leave_apply($this->token,$inputs);    

        if($result ==1 ){
            $response['status_code'] = 1;
            $response['message'] = $this->success;
            $response['data'] = $data;
        }elseif($result ==2 ){
            $response['status_code'] = 0;
            $response['message'] = $this->already_applyed;
            $response['data'] = $data;
        }else{
            $response['status_code'] = 0;
            $response['message'] = $this->permission_denied;
        }
    }else{
        $response['status_code'] = 0;
        $response['message'] = $this->required_input;
    }
    $this->response($response, REST_Controller::HTTP_OK);
}else{

    $this->token_error();
}
}

public function designations_post(){

    $result = array();
    $response = array();
    $response['status_code'] = -1;
    $response['message'] = $this->required_input;
    $response['data'] = $result;

    $inputs = $this->post();
    if(!empty($inputs['dept_id'])){

        $result = $this->api->designations($this->token,$inputs['dept_id']);    

        if(!empty($result)){
            $response['status_code'] = 1;
            $response['message'] = $this->success;
            $response['data'] = $result;
        }else{
            $response['status_code'] = 0;
            $response['message'] = $this->no_result_found;
        }
    }else{
        $response['status_code'] = 0;
        $response['message'] = $this->required_input;
    }

    $this->response($response, REST_Controller::HTTP_OK);
}


public function create_designations_post(){

   if($this->is_valid == TRUE)   {

    $data = array();
    $response = array();
    $response['status_code'] = -1;
    $response['message'] = $this->required_input;
    $response['data'] = $data;
    $inputs = $this->post();

    if(!empty($inputs['designation']) && !empty($inputs['department_id'])){

        $result = $this->api->create_designation($this->token,$inputs);    

        if($result ==1 ){
            $response['status_code'] = 1;
            $response['message'] = $this->success;
            $response['data'] = $data;
        }elseif($result ==2 ){
            $response['status_code'] = 0;
            $response['message'] = $this->already_exists;
            $response['data'] = $data;
        }else{
            $response['status_code'] = 0;
            $response['message'] = $this->permission_denied;
        }
    }else{
        $response['status_code'] = 0;
        $response['message'] = $this->required_input;
    }
    $this->response($response, REST_Controller::HTTP_OK);
}else{

    $this->token_error();
}
}

public function create_holiday_post(){

   if($this->is_valid == TRUE)   {

    $data = array();
    $response = array();
    $response['status_code'] = -1;
    $response['message'] = $this->required_input;
    $response['data'] = $data;
    $inputs = $this->post();

    if(!empty($inputs['title']) && !empty($inputs['holiday_date'])){

        $result = $this->api->create_holiday($this->token,$inputs);    

        if($result ==1 ){
            $response['status_code'] = 1;
            $response['message'] = $this->success;
            $response['data'] = $data;
        }elseif($result ==2 ){
            $response['status_code'] = 0;
            $response['message'] = $this->already_exists;
            $response['data'] = $data;
        }else{
            $response['status_code'] = 0;
            $response['message'] = $this->permission_denied;
        }
    }else{
        $response['status_code'] = 0;
        $response['message'] = $this->required_input;
    }
    $this->response($response, REST_Controller::HTTP_OK);
}else{

    $this->token_error();
}
}

public function remove_holiday_post(){

   if($this->is_valid == TRUE)   {

    $data = array();
    $response = array();
    $response['status_code'] = -1;
    $response['message'] = $this->required_input;
    $response['data'] = $data;
    $inputs = $this->post();

    if(!empty($inputs['id'])){

        $result = $this->api->remove_holiday($this->token,$inputs);    

        if($result){
            $response['status_code'] = 1;
            $response['message'] = $this->success;
            $response['data'] = $data;
        }else{
            $response['status_code'] = 0;
            $response['message'] = $this->permission_denied;
        }
    }else{
        $response['status_code'] = 0;
        $response['message'] = $this->required_input;
    }
    $this->response($response, REST_Controller::HTTP_OK);
}else{

    $this->token_error();
}
}

public function edit_holiday_post(){

   if($this->is_valid == TRUE)   {

    $data = array();
    $response = array();
    $response['status_code'] = -1;
    $response['message'] = $this->required_input;
    $response['data'] = $data;
    $inputs = $this->post();

    if(!empty($inputs['title']) && !empty($inputs['holiday_date']) && !empty($inputs['id'])){

        $result = $this->api->edit_holiday($this->token,$inputs);    

        if($result ==1 ){
            $response['status_code'] = 1;
            $response['message'] = $this->success;
            $response['data'] = $data;
        }elseif($result ==2 ){
            $response['status_code'] = 0;
            $response['message'] = $this->already_exists;
            $response['data'] = $data;
        }else{
            $response['status_code'] = 0;
            $response['message'] = $this->permission_denied;
        }
    }else{
        $response['status_code'] = 0;
        $response['message'] = $this->required_input;
    }
    $this->response($response, REST_Controller::HTTP_OK);
}else{

    $this->token_error();
}
}



public function holidays_post(){

   if($this->is_valid == TRUE)   {

    $data = array();
    $response = array();
    $response['status_code'] = -1;
    $response['message'] = $this->no_result_found;
    $response['data'] = $data;
    $inputs = $this->post();
    $result = $this->api->holidays($this->token,$inputs);    

    if(!empty($result)){
        $response['status_code'] = 1;
        $response['message'] = $this->success;
        $response['data'] = $result;
    }else{
        $response['status_code'] = 0;
        $response['message'] = $this->no_result_found;
    }

    $this->response($response, REST_Controller::HTTP_OK);
}else{

    $this->token_error();
}
}   
public function leaves_post(){

   if($this->is_valid == TRUE)   {

    $data = array();
    $response = array();
    $response['status_code'] = -1;
    $response['message'] = $this->no_result_found;
    $response['data'] = $data;
    $inputs = $this->post();
    $inputs['limit'] = 10;
    $result_count = $this->api->leaves($this->token,$inputs,1);    
    $result_list = $this->api->leaves($this->token,$inputs,2);  
    $page = !empty($inputs['page'])?$inputs['page']:1;
    $result_count = ceil($result_count/$inputs['limit']);
    $next_page    = $page + 1;
    $next_page    = ($next_page <=$result_count)?$next_page:-1;
    $result = array('next_page'=>$next_page,'current_page'=>$page,'list'=>$result_list);
    if(!empty($result)){
        $response['status_code'] = 1;
        $response['message'] = $this->success;
        $response['data'] = $result;
    }else{
        $response['status_code'] = 0;
        $response['message'] = $this->no_result_found;
    }

    $this->response($response, REST_Controller::HTTP_OK);
}else{

    $this->token_error();
}
}   

public function token_error(){

    $this->response([
        'code' => 498,
        'status' => FALSE,
        'message' => $this->invalid_token
    ], REST_Controller::HTTP_OK);
}   

public function get_attendance_list_post()
{
   if($this->is_valid == TRUE)   {
    $inputs  = $this->post();
    if(!empty($inputs['user_id'])){
        $user_id = $inputs['user_id'];
    }else{
        $user_details = $this->user->get_role_and_userid($this->token);
        $user_id = $user_details['user_id'];
    }
    // echo $user_id; exit;
    if(!empty($inputs['month']) && !empty($inputs['year'])){
        $mon = $inputs['month'];
        $yr = $inputs['year'];
    }else{
        $d = date("Y-m-d");
        $time=strtotime($d);
        $mon=date("m",$time);
        $mon = ltrim($mon, '0');
        $yr=date("Y",$time);
    }
    $lists = $this->user->get_all_attendance($user_id,$mon,$yr);
    $all_list =array();
    if(!empty($lists)){

        foreach($lists as $list){
            $result = unserialize($list['month_days']);
            for($i=0;$i<count($result);$i++)
            {
                // print_r($result); exit;
                $result[$i]['day'] = $result[$i]['day'];
                $result[$i]['punch_in'] = $result[$i]['punch_in'];
                $result[$i]['punch_out'] = $result[$i]['punch_out'];

                if($result[$i]['punch_in'] != ''){

                    // $time1 = strtotime($result[$i]['punch_in']);
                    // $time2 = strtotime($result[$i]['punch_out']);
                    // $time2 = date('H:i',$time2);
                    // $hours = round(abs(strtotime($time2) - $time1) / 3600,2);
                    $hours = (!empty($result[$i]['hours'])?$result[$i]['hours']:'0');

                    // $time1 = date_create($date.' '.$result[$i]['punch_in']);
                    // $time2 = date_create($date.' '.$result[$i]['punch_out']);

                    // $hours = date_diff($time2,$time1);
                    // $hours = $hours->format("%h.%i");
                }else{
                    $hours = 0;
                }

                $result[$i]['date'] = ($i + 1).'-'.$list['a_month'].'-'.$list['a_year'];

                $result[$i]['hours'] = $hours;
            }
            $list['month_days'] = $result;

            $all_list []= $list; 
        }
        $response['status_code'] = 1;
        $response['message'] = $this->success;
        $response['data'] = $all_list;
        
    }else{

        $a_month = $mon;
        $a_year =  $yr;
        $days = array();
        $lat_day = date('t',strtotime($a_year.'-'.$a_month.'-'.'1'));
        for($i=1;$i<=$lat_day;$i++){
            $day = date('D',strtotime($a_year.'-'.$a_month.'-'.$i));
            $day = (strtolower($day)=='sun')?0:'';
            $day_details = array('day'=>$day,'punch_in'=>'','punch_out'=>'');
            $days[] = $day_details;
        }
        $insert = array('user_id'=>$user_id,'month_days'=>serialize($days),'a_month'=>$a_month,'a_year'=>$a_year);
        $this->db->insert("fx_attendance_details",$insert);

        $atten_result = $this->user->get_all_attendance($user_id,$mon,$yr);

        foreach($atten_result as $listing){
            $resul = unserialize($listing['month_days']);
            for($i=0;$i<count($resul);$i++)
            {
                $resul[$i]['day'] = $resul[$i]['day'];
                $resul[$i]['punch_in'] = $resul[$i]['punch_in'];
                $resul[$i]['punch_out'] = $resul[$i]['punch_out'];

                if($resul[$i]['punch_in'] != ''){

                    $time1 = strtotime($resul[$i]['punch_in']);
                    $time2 = strtotime($resul[$i]['punch_out']);
                    $time2 = date('H:i',$time2);
                    $hours = round(abs(strtotime($time2) - $time1) / 3600,2);
                }else{
                    $hours = 0;
                }

                $resul[$i]['date'] = ($i + 1).'-'.$listing['a_month'].'-'.$listing['a_year'];

                $resul[$i]['hours'] = $hours;
            }
            $listing['month_days'] = $resul;

            $all_list []= $listing; 
            }   
            $response['status_code'] = 1;
            $response['message'] = $this->success;
            $response['data'] = $all_list;  
        }
        $this->response($response, REST_Controller::HTTP_OK);

    }else{
        $this->token_error();
    }
}

public function payslip_users_list_post()
{
   if($this->is_valid == TRUE)   {
    $res = $this->api->get_role_and_userid($this->token);
    if($res['role_id'] == 1)
    {
         $inputs  = $this->input->post();
        
         $user_id = (!empty($inputs['user_id'])?$inputs['user_id']:"");
         $year =  (!empty($inputs['year'])?$inputs['year']:"");
         $month = (!empty($inputs['month'])?$inputs['month']:"");

         $inputs['limit'] = 10;
         $payslips_count = $this->api->get_payslips($user_id,$year,$month,$inputs,1); 
         $payslips = $this->api->get_payslips($user_id,$year,$month,$inputs,2);

          $page = (!empty($inputs['page'])?$inputs['page']:1);
          $payslips_count = ceil($payslips_count/$inputs['limit']);
          $next_page    = $page + 1;
          $next_page    = ($next_page <=$payslips_count)?$next_page:-1;

          

        $users_all = array();
        foreach($payslips as $payslip){

                $result['year'] = $payslip['p_year'];
                $result['month'] = $payslip['p_month'];
                $pay = json_decode($payslip['payslip_details'],TRUE);


                $pay['payslip_user_id'] = (!empty($pay['payslip_user_id'])?$pay['payslip_user_id']:"0");
                $pay['payslip_year'] = (!empty($pay['payslip_year'])?$pay['payslip_year']:"0");
                $pay['payslip_month'] = (!empty($pay['payslip_month'])?$pay['payslip_month']:"0");
                $pay['payslip_basic'] = (!empty($pay['payslip_basic'])?$pay['payslip_basic']:"0");
                $pay['payslip_da'] = (!empty($pay['payslip_da'])?$pay['payslip_da']:"0");
                $pay['payslip_hra'] = (!empty($pay['payslip_hra'])?$pay['payslip_hra']:"0");
                $pay['payslip_conveyance'] = (!empty($pay['payslip_conveyance'])?$pay['payslip_conveyance']:"0");
                $pay['payslip_allowance'] = (!empty($pay['payslip_allowance'])?$pay['payslip_allowance']:"0");
                $pay['payslip_medical_allowance'] = (!empty($pay['payslip_medical_allowance'])?$pay['payslip_medical_allowance']:"0");
                $pay['payslip_others'] = (!empty($pay['payslip_others'])?$pay['payslip_others']:"0");
                $pay['payslip_ded_tds'] = (!empty($pay['payslip_ded_tds'])?$pay['payslip_ded_tds']:"0");
                $pay['payslip_ded_esi'] = (!empty($pay['payslip_ded_esi'])?$pay['payslip_ded_esi']:"0");
                $pay['payslip_ded_pf'] = (!empty($pay['payslip_ded_pf'])?$pay['payslip_ded_pf']:"0");
                $pay['payslip_ded_leave'] = (!empty($pay['payslip_ded_leave'])?$pay['payslip_ded_leave']:"0");
                $pay['payslip_ded_prof'] = (!empty($pay['payslip_ded_prof'])?$pay['payslip_ded_prof']:"0");
                $pay['payslip_ded_welfare'] = (!empty($pay['payslip_ded_welfare'])?$pay['payslip_ded_welfare']:"0");
                $pay['payslip_ded_fund'] = (!empty($pay['payslip_ded_fund'])?$pay['payslip_ded_fund']:"0");
                $pay['payslip_ded_others'] = (!empty($pay['payslip_ded_others'])?$pay['payslip_ded_others']:"0");

                $result['payslip_details'] = $pay;

            
            $salaries = $this->api->get_salary($payslip['user_id'],$payslip['p_salary_id']);
            if(!empty($salaries)){
                $result['salary'] = $salaries['amount'];
            }else{
                $result['salary'] = '';
            }

            $users_details = $this->api->users_list_payslip($payslip['user_id']);

                 if(!empty($users_details)){
                        $result['user_id'] = $users_details['user_id'];
                        $result['email'] = $users_details['email'];
                        $result['role_id'] = $users_details['role_id'];
                        $result['designation'] = $users_details['designation'];
                        $result['avatar'] = $users_details['avatar'];
                        $result['fullname'] = $users_details['fullname'];
                }else{
                    $result['user_id'] = '';
                    $result['email'] = '';
                    $result['role_id'] ='';
                    $result['designation'] = '';
                    $result['avatar'] = '';
                    $result['fullname'] = '';
                }

                 if(!empty($users_details)){             
                $users_all [] = $result;
        }  
             }
             
        $final_result = array('next_page'=>$next_page,'current_page'=>$page,'list'=>$users_all);
        if(!empty($final_result)){
            $response['status_code'] = 1;
            $response['message'] = $this->success;
            $response['data'] = $final_result;
        }else{
            $response['status_code'] = 0;
            $response['message'] = $this->no_result_found;
        }
        $this->response($response, REST_Controller::HTTP_OK);
    }else{
        
        $inputs  = $this->input->post();
        
         $user_id = $res['user_id'];
         $year =  (!empty($inputs['year'])?$inputs['year']:"");
         $month = (!empty($inputs['month'])?$inputs['month']:"");

         $inputs['limit'] = 10;
         $payslips_count = $this->api->get_payslips($user_id,$year,$month,$inputs,1); 
         $payslips = $this->api->get_payslips($user_id,$year,$month,$inputs,2);

          $page = (!empty($inputs['page'])?$inputs['page']:1);
          $payslips_count = ceil($payslips_count/$inputs['limit']);
          $next_page    = $page + 1;
          $next_page    = ($next_page <=$payslips_count)?$next_page:-1;

          

        $users_all = array();
        foreach($payslips as $payslip){

                $result['year'] = $payslip['p_year'];
                $result['month'] = $payslip['p_month'];
                $pay = json_decode($payslip['payslip_details'],TRUE);


                $pay['payslip_user_id'] = (!empty($pay['payslip_user_id'])?$pay['payslip_user_id']:"0");
                $pay['payslip_year'] = (!empty($pay['payslip_year'])?$pay['payslip_year']:"0");
                $pay['payslip_month'] = (!empty($pay['payslip_month'])?$pay['payslip_month']:"0");
                $pay['payslip_basic'] = (!empty($pay['payslip_basic'])?$pay['payslip_basic']:"0");
                $pay['payslip_da'] = (!empty($pay['payslip_da'])?$pay['payslip_da']:"0");
                $pay['payslip_hra'] = (!empty($pay['payslip_hra'])?$pay['payslip_hra']:"0");
                $pay['payslip_conveyance'] = (!empty($pay['payslip_conveyance'])?$pay['payslip_conveyance']:"0");
                $pay['payslip_allowance'] = (!empty($pay['payslip_allowance'])?$pay['payslip_allowance']:"0");
                $pay['payslip_medical_allowance'] = (!empty($pay['payslip_medical_allowance'])?$pay['payslip_medical_allowance']:"0");
                $pay['payslip_others'] = (!empty($pay['payslip_others'])?$pay['payslip_others']:"0");
                $pay['payslip_ded_tds'] = (!empty($pay['payslip_ded_tds'])?$pay['payslip_ded_tds']:"0");
                $pay['payslip_ded_esi'] = (!empty($pay['payslip_ded_esi'])?$pay['payslip_ded_esi']:"0");
                $pay['payslip_ded_pf'] = (!empty($pay['payslip_ded_pf'])?$pay['payslip_ded_pf']:"0");
                $pay['payslip_ded_leave'] = (!empty($pay['payslip_ded_leave'])?$pay['payslip_ded_leave']:"0");
                $pay['payslip_ded_prof'] = (!empty($pay['payslip_ded_prof'])?$pay['payslip_ded_prof']:"0");
                $pay['payslip_ded_welfare'] = (!empty($pay['payslip_ded_welfare'])?$pay['payslip_ded_welfare']:"0");
                $pay['payslip_ded_fund'] = (!empty($pay['payslip_ded_fund'])?$pay['payslip_ded_fund']:"0");
                $pay['payslip_ded_others'] = (!empty($pay['payslip_ded_others'])?$pay['payslip_ded_others']:"0");

                $result['payslip_details'] = $pay;

            
            $salaries = $this->api->get_salary($payslip['user_id'],$payslip['p_salary_id']);
            if(!empty($salaries)){
                $result['salary'] = $salaries['amount'];
            }else{
                $result['salary'] = '';
            }

            $users_details = $this->api->users_list_payslip($payslip['user_id']);

                 if(!empty($users_details)){
                        $result['user_id'] = $users_details['user_id'];
                        $result['email'] = $users_details['email'];
                        $result['role_id'] = $users_details['role_id'];
                        $result['designation'] = $users_details['designation'];
                        $result['avatar'] = $users_details['avatar'];
                        $result['fullname'] = $users_details['fullname'];
                }else{
                    $result['user_id'] = '';
                    $result['email'] = '';
                    $result['role_id'] ='';
                    $result['designation'] = '';
                    $result['avatar'] = '';
                    $result['fullname'] = '';
                }

                 if(!empty($users_details)){             
                $users_all [] = $result;
        }  
             }
             
        $final_result = array('next_page'=>$next_page,'current_page'=>$page,'list'=>$users_all);
        if(!empty($final_result)){
            $response['status_code'] = 1;
            $response['message'] = $this->success;
            $response['data'] = $final_result;
        }else{
            $response['status_code'] = 0;
            $response['message'] = $this->no_result_found;
        }
        $this->response($response, REST_Controller::HTTP_OK);
    }
   
}else{
    $this->token_error();
}
}

// public function payslip_users_list_post()
// {
//    if($this->is_valid == TRUE)   {
//     $res = $this->api->get_role_and_userid($this->token);
//     if($res['role_id'] == 1)
//     {
//         $all_users = $this->api->users_list_payslip();
        
//         $users_all = array();
//         foreach($all_users as $users){
//             $result = array(
//                 'user_id' =>$users['user_id'],
//                 'email'   =>$users['email'],
//                 'role_id' =>$users['role_id'],
//                 'designation' =>$users['designation'],
//                 'avatar' =>$users['avatar'],
//                 'fullname' =>$users['fullname'],
//             );
//             $salaries = $this->api->get_salary($users['user_id']);
//             if(!empty($salaries)){
//                 $result['salary'] = $salaries['amount'];
//             }else{
//                 $result['salary'] = '';
//             }

//             $payslips = $this->api->get_payslips($users['user_id']);
//             if(!empty($payslips)){
//                 $result['year'] = $payslips['p_year'];
//                 $result['month'] = $payslips['p_month'];
//                 $pays = json_decode($payslips['payslip_details'],TRUE);
//                 $result['payslip_details'] = $pays;
//             }else{
//                 $result['year'] = '';
//                 $result['month'] = '';
//                 $result['payslip_details'] = '';
//             }

//             $users_all [] = $result;
//         }
//         if(!empty($users_all)){
//             $response['status_code'] = 1;
//             $response['message'] = $this->success;
//             $response['data'] = $users_all;
//         }else{
//             $response['status_code'] = 0;
//             $response['message'] = $this->no_result_found;
//         }
//         $this->response($response, REST_Controller::HTTP_OK);
//     }else{
//         $this->token_error();
//     }
// }else{
//     $this->token_error();
// }
// }

public function add_salary_post()
{
    if($this->is_valid == TRUE)   {
        $res = $this->api->get_role_and_userid($this->token);
        if($res['role_id'] == 1)
        {
            $inputs = $this->post();
            if(!empty($inputs['net_salary']) && !empty($inputs['month']) && !empty($inputs['year']) && !empty($inputs['basic_pay']) && !empty($inputs['da']) && !empty($inputs['hra']) && !empty($inputs['user_id'])){
                $user_id = $inputs['user_id'];
                // $check_net = $this->api->check_net_exist($user_id,$inputs['net_salary']);
                // // echo $check_net; exit;
                // if($check_net <= 0)
                // {
                $net = array(
                    'user_id' =>$user_id,
                    'amount' => $inputs['net_salary'],
                    'date_created' =>date('Y-m-d H:i:s')
                );
                $this->db->insert('fx_salary',$net);
                $p_salary_id=$this->db->insert_id();
                // }
                $payslip = array(
                    'payslip_user_id' => $user_id,
                    'payslip_year'    => $inputs['year'],
                    'payslip_month'    => $inputs['month'],
                    'payslip_basic'    => $inputs['basic_pay'],
                    'payslip_da'    => $inputs['da'],
                    'payslip_hra'    => $inputs['hra'],
                    'payslip_conveyance'    => $inputs['conveyance']? $inputs['conveyance'] : 0,
                    'payslip_allowance'    => $inputs['allowance']? $inputs['allowance'] : 0,
                    'payslip_medical_allowance'    => $inputs['medical_allowance']? $inputs['medical_allowance'] : 0,
                    'payslip_others'    => $inputs['earning_others']? $inputs['earning_others'] : 0,
                    'payslip_ded_tds'    => $inputs['tds']? $inputs['tds'] : 0,
                    'payslip_ded_esi'    => $inputs['esi']? $inputs['esi'] : 0,
                    'payslip_ded_pf'    => $inputs['pf']? $inputs['pf'] : 0,
                    'payslip_ded_leave'    => $inputs['leaves']? $inputs['leaves'] : 0,
                    'payslip_ded_prof'    => $inputs['prof_tax']? $inputs['prof_tax'] : 0,
                    'payslip_ded_welfare'    => $inputs['labour_welfare']? $inputs['labour_welfare'] : 0,
                    'payslip_ded_fund'    => $inputs['fund']? $inputs['fund'] : 0,
                    'payslip_ded_others'    => $inputs['ded_others']? $inputs['ded_others'] : 0,
                );
                // echo json_encode($payslip); exit;
                $check_exist = $this->api->check_payslip_exist($user_id,$inputs['year'],$inputs['month']);
                if($check_exist == 0)
                {
                    $result = array(
                        'user_id'         => $user_id,
                        'p_year'          => $inputs['year'],
                        'p_month'         => $inputs['month'],
                        'p_salary_id'     => $p_salary_id,
                        'payslip_details' => json_encode($payslip)
                    );
                    $this->db->insert('fx_payslip',$result);
                }else{
                    $result = array(
                        'p_salary_id'     => $p_salary_id,
                        'payslip_details' => json_encode($payslip)
                    );
                    $this->api->update_user_payslip($user_id,$inputs['year'],$inputs['month'],$result);
                }
                $response['status_code'] = 1;
                $response['message'] = $this->success;

            }else{
                $response['status_code'] = 0;
                $response['message'] = $this->required_input;
            }

            $this->response($response, REST_Controller::HTTP_OK);
        }else{
            $this->token_error();
        }
    }else{
        $this->token_error();
    }
}

    public function generate_payslip_post()
    {
        if($this->is_valid == TRUE)   {
            $res = $this->api->get_role_and_userid($this->token);
            if($res['role_id'] == 1)
            {
                $inputs = $this->post();
                if(!empty($inputs['user_id']) && !empty($inputs['month']) && !empty($inputs['year'])){
                    $inputs = $this->post();
                    $user_pay = $this->api->get_user_payslip($inputs['user_id']);
                   
                    $payslips = $this->api->get_payslip_user($inputs['user_id'],$inputs['year'],$inputs['month']);
                    $salaries = $this->api->get_salary($inputs['user_id'],$payslips['p_salary_id']);
                    $pays = json_decode($payslips['payslip_details'],TRUE);
                    $result = array(
                        'user_id'                    => $inputs['user_id'],
                        'email'                      => $user_pay['email'],
                        'fullname'                   => $user_pay['fullname']?$user_pay['fullname']:'0',
                        'company'                    => $user_pay['company']?$user_pay['company']:'0',
                        'city'                       => $user_pay['city']?$user_pay['city']:'0',
                        'country'                    => $user_pay['country']?$user_pay['country']:'0',
                        'address'                    => $user_pay['address']?$user_pay['address']:'',
                        'phone'                      => $user_pay['phone']?$user_pay['phone']:'0',
                        'avatar'                     => base_url().'assets/uploads/'.$user_pay['avatar'],
                        'designation'                => $user_pay['designation']?$user_pay['designation']:'0',
                        'amount'                     => $salaries['amount']?$salaries['amount']:'0',
                        'payslip_year'               => $pays['payslip_year']?$pays['payslip_year']:'0',
                        'payslip_month'              => $pays['payslip_month']?$pays['payslip_month']:'0',
                        'payslip_basic'              => $pays['payslip_basic']?$pays['payslip_basic']:'0',
                        'payslip_da'                 => $pays['payslip_da']?$pays['payslip_da']:'0',
                        'payslip_hra'                => $pays['payslip_hra']?$pays['payslip_hra']:'0',
                        'payslip_conveyance'         => $pays['payslip_conveyance']?$pays['payslip_conveyance']:'0',
                        'payslip_allowance'          => $pays['payslip_allowance']?$pays['payslip_allowance']:'0',
                        'payslip_medical_allowance'  => $pays['payslip_medical_allowance']?$pays['payslip_medical_allowance']:'0',
                        'payslip_others'             => $pays['payslip_others']?$pays['payslip_others']:'0',
                        'payslip_ded_tds'            => $pays['payslip_ded_tds']?$pays['payslip_ded_tds']:'0',
                        'payslip_ded_esi'            => $pays['payslip_ded_esi']?$pays['payslip_ded_esi']:'0',
                        'payslip_ded_pf'             => $pays['payslip_ded_pf']?$pays['payslip_ded_pf']:'0',
                        'payslip_ded_leave'          => $pays['payslip_ded_leave']?$pays['payslip_ded_leave']:'0',
                        'payslip_ded_prof'           => $pays['payslip_ded_prof']?$pays['payslip_ded_prof']:'0',
                        'payslip_ded_welfare'        => $pays['payslip_ded_welfare']?$pays['payslip_ded_welfare']:'0',
                        'payslip_ded_fund'           => $pays['payslip_ded_fund']?$pays['payslip_ded_fund']:'0',
                        'payslip_ded_others'         => $pays['payslip_ded_others']?$pays['payslip_ded_others']:'0'
                    );
                    if(!empty($result)){
                        $response['status_code'] = 1;
                        $response['message'] = $this->success;
                        $response['data'] = $result;
                    }else{
                        $response['status_code'] = 0;
                        $response['message'] = $this->no_result_found;
                    }
                }else{
                    $response['status_code'] = 0;
                    $response['message'] = $this->required_input;
                }
                $this->response($response, REST_Controller::HTTP_OK);
            }else if($res['role_id'] == 3){
                $inputs = $this->post();
                if(!empty($inputs['month']) && !empty($inputs['year'])){
                    $inputs = $this->post();
                    $user_pay = $this->api->get_user_payslip($res['user_id']);
                    $payslips = $this->api->get_payslip_user($res['user_id'],$inputs['year'],$inputs['month']);
                    $salaries = $this->api->get_salary($res['user_id'],$payslips['p_salary_id']);
                    $pays = json_decode($payslips['payslip_details'],TRUE);
                    $result = array(
                        'user_id'                    => $res['user_id'],
                        'email'                      => $user_pay['email'],
                        'fullname'                   => $user_pay['fullname']?$user_pay['fullname']:'0',
                        'company'                    => $user_pay['company']?$user_pay['company']:'0',
                        'city'                       => $user_pay['city']?$user_pay['city']:'0',
                        'country'                    => $user_pay['country']?$user_pay['country']:'0',
                        'address'                    => $user_pay['address']?$user_pay['address']:'',
                        'phone'                      => $user_pay['phone']?$user_pay['phone']:'0',
                        'avatar'                     => base_url().'assets/uploads/'.$user_pay['avatar'],
                        'designation'                => $user_pay['designation']?$user_pay['designation']:'0',
                        'amount'                     => $salaries['amount']?$salaries['amount']:'0',
                        'payslip_year'               => $pays['payslip_year']?$pays['payslip_year']:'0',
                        'payslip_month'              => $pays['payslip_month']?$pays['payslip_month']:'0',
                        'payslip_basic'              => $pays['payslip_basic']?$pays['payslip_basic']:'0',
                        'payslip_da'                 => $pays['payslip_da']?$pays['payslip_da']:'0',
                        'payslip_hra'                => $pays['payslip_hra']?$pays['payslip_hra']:'0',
                        'payslip_conveyance'         => $pays['payslip_conveyance']?$pays['payslip_conveyance']:'0',
                        'payslip_allowance'          => $pays['payslip_allowance']?$pays['payslip_allowance']:'0',
                        'payslip_medical_allowance'  => $pays['payslip_medical_allowance']?$pays['payslip_medical_allowance']:'0',
                        'payslip_others'             => $pays['payslip_others']?$pays['payslip_others']:'0',
                        'payslip_ded_tds'            => $pays['payslip_ded_tds']?$pays['payslip_ded_tds']:'0',
                        'payslip_ded_esi'            => $pays['payslip_ded_esi']?$pays['payslip_ded_esi']:'0',
                        'payslip_ded_pf'             => $pays['payslip_ded_pf']?$pays['payslip_ded_pf']:'0',
                        'payslip_ded_leave'          => $pays['payslip_ded_leave']?$pays['payslip_ded_leave']:'0',
                        'payslip_ded_prof'           => $pays['payslip_ded_prof']?$pays['payslip_ded_prof']:'0',
                        'payslip_ded_welfare'        => $pays['payslip_ded_welfare']?$pays['payslip_ded_welfare']:'0',
                        'payslip_ded_fund'           => $pays['payslip_ded_fund']?$pays['payslip_ded_fund']:'0',
                        'payslip_ded_others'         => $pays['payslip_ded_others']?$pays['payslip_ded_others']:'0'
                    );
                    if(!empty($result)){
                        $response['status_code'] = 1;
                        $response['message'] = $this->success;
                        $response['data'] = $result;
                    }else{
                        $response['status_code'] = 0;
                        $response['message'] = $this->no_result_found;
                    }
                }else{
                    $response['status_code'] = 0;
                    $response['message'] = $this->required_input;
                }
                $this->response($response, REST_Controller::HTTP_OK);
            }else{
                $this->token_error();
            }
        }else{
            $this->token_error();
        }
    }

    public function payslip_pdf_post()
    {
        if($this->is_valid == TRUE)   {
            $res = $this->api->get_role_and_userid($this->token);
            $inputs = $this->post();
            if($res['role_id'] == 1)
            {
                if($inputs['user_id'] == '')
                {
                    $response['status_code'] = 0;
                    $response['message'] = $this->required_input;
                }else{
                    $user_id = $inputs['user_id'];
                    $user_pay = $this->api->get_user_payslip($user_id);
                    
                        $payslips = $this->api->get_payslip_user($user_id,$inputs['year'],$inputs['month']);
                        if($payslips != ''){
                            $salaries = $this->api->get_salary($user_id,$payslips['p_salary_id']);
                          if($salaries != ''){

                            $pays = json_decode($payslips['payslip_details'],TRUE);

                            $data['attach'] = TRUE;          

                            $form_data = array();

                            if ($payslips['id']) {

                                $form_data['user_id']           = $user_id;

                                $form_data['year']              = $pays['payslip_year'];

                                $form_data['month']             = $pays['payslip_month'];

                                $form_data['basic']             = $pays['payslip_basic'];

                                $form_data['da']                = $pays['payslip_da'];

                                $form_data['hra']               = $pays['payslip_hra'];

                                $form_data['conveyance']        = $pays['payslip_conveyance'];

                                $form_data['allowance']         = $pays['payslip_allowance'];

                                $form_data['medical_allowance'] = $pays['payslip_medical_allowance'];

                                $form_data['others']            = $pays['payslip_others'];

                                $form_data['deduction_tds']     = $pays['payslip_ded_tds'];

                                $form_data['deduction_esi']     = $pays['payslip_ded_esi'];

                                $form_data['deduction_pf']      = $pays['payslip_ded_pf'];

                                $form_data['deduction_leave']   = $pays['payslip_ded_leave'];

                                $form_data['deduction_prof']    = $pays['payslip_ded_prof'];

                                $form_data['deduction_welfare'] = $pays['payslip_ded_welfare'];

                                $form_data['deduction_fund']    = $pays['payslip_ded_fund'];

                                $form_data['deduction_others']  = $pays['payslip_ded_others'];
                                $form_data['payslipid']  = $payslips['id'];

                             } 

                            $data['form_data'] = $form_data;      

                            $this->load->view('payslip',isset($data) ? $data : NULL,TRUE);  
                            $est =  $this->session->userdata('payslip_pdf'); 
                            $results =array(
                                'file_name' => base_url().$est
                            );
                            $response['status_code'] = 1;
                            $response['message'] = $this->success;
                            $response['data'] = $results;
                            }else{
                            $response['status_code'] = 0;
                            $response['message'] = 'Please provide employee salary';
                        }
                        }else{
                        $response['status_code'] = 0;
                        $response['message'] = 'Please Create Employee Payslip';
                    }
                
                }                
            }else{
                    $user_id = $res['user_id'];
                    $user_pay = $this->api->get_user_payslip($user_id);
                   
                        $payslips = $this->api->get_payslip_user($user_id,$inputs['year'],$inputs['month']);
                        if($payslips != ''){
                             $salaries = $this->api->get_salary($user_id,$payslips['p_salary_id']);
                    if($salaries != ''){
                            $pays = json_decode($payslips['payslip_details'],TRUE);

                            $data['attach'] = TRUE;          

                            $form_data = array();

                            if ($payslips['id']) {

                                $form_data['user_id']           = $user_id;

                                $form_data['year']              = $pays['payslip_year'];

                                $form_data['month']             = $pays['payslip_month'];

                                $form_data['basic']             = $pays['payslip_basic'];

                                $form_data['da']                = $pays['payslip_da'];

                                $form_data['hra']               = $pays['payslip_hra'];

                                $form_data['conveyance']        = $pays['payslip_conveyance'];

                                $form_data['allowance']         = $pays['payslip_allowance'];

                                $form_data['medical_allowance'] = $pays['payslip_medical_allowance'];

                                $form_data['others']            = $pays['payslip_others'];

                                $form_data['deduction_tds']     = $pays['payslip_ded_tds'];

                                $form_data['deduction_esi']     = $pays['payslip_ded_esi'];

                                $form_data['deduction_pf']      = $pays['payslip_ded_pf'];

                                $form_data['deduction_leave']   = $pays['payslip_ded_leave'];

                                $form_data['deduction_prof']    = $pays['payslip_ded_prof'];

                                $form_data['deduction_welfare'] = $pays['payslip_ded_welfare'];

                                $form_data['deduction_fund']    = $pays['payslip_ded_fund'];

                                $form_data['deduction_others']  = $pays['payslip_ded_others'];
                                $form_data['payslipid']  = $payslips['id'];

                             } 

                            $data['form_data'] = $form_data;      

                             $this->load->view('payslip',isset($data) ? $data : NULL,TRUE);  
                            $est =  $this->session->userdata('payslip_pdf'); 
                            $results =array(
                                'file_name' => base_url().$est
                            );
                            $response['status_code'] = 1;
                            $response['message'] = $this->success;
                            $response['data'] = $results;

                            }else{
                    $response['status_code'] = 0;
                    $response['message'] = 'Please provide employee salary';
                }
                        }else{
                        $response['status_code'] = 0;
                        $response['message'] = 'Please Create Employee Payslip';
                    }
                
            }
                $this->response($response, REST_Controller::HTTP_OK);
        }else{
            $this->token_error();
        }
    }

    public function all_users_post()
    {
         if($this->is_valid == TRUE)   
         {
            $res = $this->api->get_role_and_userid($this->token);
            // if($res['role_id'] == 1)
            // {
                $results = $this->api->all_users();
                if(!empty($results)){
                        $response['status_code'] = 1;
                        $response['message'] = $this->success;
                        $response['data'] = $results;
                    }else{
                        $response['status_code'] = 0;
                        $response['message'] = $this->no_result_found;
                    }
                $this->response($response, REST_Controller::HTTP_OK);
            // }else{
            // $this->token_error();
            // }
        }else{
            $this->token_error();
        }
    }


    public function all_projects_post()
    {
        if($this->is_valid == TRUE)   
         {
            $res = $this->api->get_role_and_userid($this->token);
            // print_r($res); exit;
            if($res['role_id'] == 1)
            {
                $results = $this->api->over_all_projects();
                $projects =array();
                $i=0;
                foreach ($results as $result) {
                    $projects[$i]['project_title'] = $result['project_title'];
                    $projects[$i]['start_date'] = $result['start_date'];
                    $projects[$i]['due_date'] = $result['due_date'];
                    $projects[$i]['progress'] = $result['progress'];
                    $projects[$i]['assign_lead'] = $result['assign_lead'];
                    $projects[$i]['assign_to'] = $result['assign_to'];
                    $projects[$i]['estimate_hours'] = $result['estimate_hours'];
                    $projects[$i]['project_created'] = $result['date_created'];
                    $projects[$i]['tasks'] = $this->api->task_by_project($result['project_id']);
                    $projects[$i]['tasks_open'] = $this->api->get_task_status($result['project_id'],'open');
                    $projects[$i]['tasks_completed'] = $this->api->get_task_status($result['project_id'],'complete');
                    $projects[$i]['tasks_files_count'] = $this->api->get_task_files($projects['tasks']);
                    $comment = $this->api->get_comment_project($result['project_id']);
                    $projects[$i]['comment_count'] = count($comment);
                    $project_lead = $this->api->get_user_detail('lead',$result['assign_lead']);
                    $project_members = $this->api->get_user_detail('members',$result['assign_to']);
                    $projects[$i]['overviews'] = array(
                        'project_title' => $result['project_title'],
                        'deadline' => $result['due_date'],
                        'project_created' => $result['date_created'],
                        'description' => $result['description'],
                        'progress' => $result['progress'],
                        'project_lead_name' => $project_lead['fullname'],
                        'project_lead_photo' => base_url().'assets/uploads/'.$project_lead['avatar'],
                        'project_team_members' => $project_members
                    ); 
                    $i++;
                }
                if(!empty($projects)){
                        $response['status_code'] = 1;
                        $response['message'] = $this->success;
                        $response['data'] = $projects;
                    }else{
                        $response['status_code'] = 0;
                        $response['message'] = $this->no_result_found;
                    }
                // $this->response($response, REST_Controller::HTTP_OK);
            }else{
                $results = $this->api->get_projectByUserId($res['user_id']);
                $projects =array();
                $i=0;
                foreach ($results as $result) {
                    $projects[$i]['project_title'] = $result['project_title'];
                    $projects[$i]['start_date'] = $result['start_date'];
                    $projects[$i]['due_date'] = $result['due_date'];
                    $projects[$i]['progress'] = $result['progress'];
                    $projects[$i]['assign_lead'] = $result['assign_lead'];
                    $projects[$i]['assign_to'] = $result['assign_to'];
                    $projects[$i]['estimate_hours'] = $result['estimate_hours'];
                    $projects[$i]['project_created'] = $result['date_created'];
                    $projects[$i]['tasks'] = $this->api->task_by_project($result['project_id']);
                    $projects[$i]['tasks_open'] = $this->api->get_task_status($result['project_id'],'open');
                    $projects[$i]['tasks_completed'] = $this->api->get_task_status($result['project_id'],'complete');
                    $projects[$i]['tasks_files_count'] = $this->api->get_task_files($projects['tasks']);
                    $comment = $this->api->get_comment_project($result['project_id']);
                    $projects[$i]['comment_count'] = count($comment);
                    $project_lead = $this->api->get_user_detail('lead',$result['assign_lead']);
                    $project_members = $this->api->get_user_detail('members',$result['assign_to']);
                    $projects[$i]['overviews'] = array(
                        'project_title' => $result['project_title'],
                        'deadline' => $result['due_date'],
                        'project_created' => $result['date_created'],
                        'description' => $result['description'],
                        'progress' => $result['progress'],
                        'project_lead_name' => $project_lead['fullname'],
                        'project_lead_photo' => base_url().'assets/uploads/'.$project_lead['avatar'],
                        'project_team_members' => $project_members
                    ); 
                    $i++;
                }
                if(!empty($projects)){
                        $response['status_code'] = 1;
                        $response['message'] = $this->success;
                        $response['data'] = $projects;
                    }else{
                        $response['status_code'] = 0;
                        $response['message'] = $this->no_result_found;
                    }
            }
             $this->response($response, REST_Controller::HTTP_OK);
        }else{
            $this->token_error();
        }
    }


    public function client_list_post()
    {
        if($this->is_valid == TRUE)   
         {
            $res = $this->api->get_role_and_userid($this->token);
            if($res['role_id'] == 1)
            {
                $clients_all = array();
                $all_clients = $this->api->get_all_clients();
                $i =0;
                // $total_cost = [];
                // $payment_total = [];
                // $invoices = [];
                foreach ($all_clients as $clients) {
                    $invoices[$i] = 0;
                    $clients_all[$i]['co_id'] = $clients['co_id']?$clients['co_id']:'- ';
                    $clients_all[$i]['company_name'] = $clients['company_name'];
                    $clients_all[$i]['fullname'] = $clients['fullname']?$clients['fullname']:'-';
                    $clients_all[$i]['company_email'] = $clients['company_email']?$clients['company_email']:'-';
                    $clients_all[$i]['company_website'] = $clients['company_website']?$clients['company_website']:'-';
                    $clients_all[$i]['company_phone'] = $clients['company_phone']?$clients['company_phone']:'-';
                    $clients_all[$i]['company_mobile'] = $clients['company_mobile']?$clients['company_mobile']:'-';
                    $clients_all[$i]['company_fax'] = $clients['company_fax']?$clients['company_fax']:'-';
                    $clients_all[$i]['company_address'] = $clients['company_address']?$clients['company_address']:'-';
                    $clients_all[$i]['city'] = $clients['city']?$clients['city']:'-';
                    $clients_all[$i]['state'] = $clients['state']?$clients['state']:'-';
                    $clients_all[$i]['country'] = $clients['country']?$clients['country']:'-';
                    $clients_all[$i]['zip'] = $clients['zip']?$clients['zip']:'-';
                    // $clients_all[$i]['skype'] = $clients['skype']?$clients['skype']:'-';
                    // $clients_all[$i]['linkedin'] = $clients['linkedin']?$clients['linkedin']:'-';
                    // $clients_all[$i]['facebook'] = $clients['facebook']?$clients['facebook']:'-';
                    // $clients_all[$i]['twitter'] = $clients['twitter']?$clients['twitter']:'-';
                    // if($clients['primary_contact'] != '')
                    // {
                    //     $contact_person = $this->api->get_userById($clients['primary_contact']);
                    //     $c_person = $contact_person['fullname'];
                    // }else{
                    //     $c_person = '-';
                    // }
                    // $clients_all[$i]['contact_person'] = $c_person;
                    // if($clients['user_id'] != '')
                    // {
                    //     $invoices[$i] = $this->api->get_invoiceByClientId($clients['user_id']);
                    //     // print_r($invoices[$i]); exit;
                    //     $total_cost[$i] = 0;
                    //     $payment_total[$i] = 0;
                    //     $invoice_items = @$this->api->get_invoice_items($invoices[$i]['inv_id']);
                    //     // foreach($invoice_items as $items)
                    //     // {
                    //     //     $total_cost[$i] += $items['total_cost']; 
                    //     // }
                    //     $company_details = array(
                    //     'company_address' => $clients['company_address']?$clients['company_address']:'-',
                    //     'city' =>$clients['city']?$clients['city']:'-',
                    //     'state' =>$clients['state']?$clients['state']:'-',
                    //     'country' =>$clients['country']?$clients['country']:0,
                    //     'zip_code' =>$clients['zip']?$clients['zip']:0,
                    //     'company_phone' =>$clients['company_phone']?$clients['company_phone']:0,
                    //     'company_vat'  =>$clients['VAT']?$clients['VAT']:0,
                    //     'company_fax'  =>$clients['company_fax']?$clients['company_fax']:0
                    //     );

                    //     $receiver = array(
                    //         'company_name' =>'DGT',
                    //         'company_address' => 'Coimbatore',
                    //         'city' =>'Coimbatore',
                    //         'state' =>'Tamilnadu',
                    //         'country' =>'India',
                    //         'zip_code' =>640135,
                    //         'company_phone' =>'9087654321 , 9876543210'
                    //     );
                    //     $clients_all[$i]['bill_to'] = $company_details;
                    //     $clients_all[$i]['receive_from'] = $receiver;
                    //     $clients_all[$i]['items'] = $invoice_items?$invoice_items:0;

                    //     $payment_mades = @$this->api->get_invoice_payment($invoices[$i]['inv_id']);
                    //     foreach($payment_mades as $payment_made){
                    //             $payment_total[$i] += $payment_made['amount']; 
                    //     }
                    //     $clients_all[$i]['payment_made'] = $payment_total[$i]?$payment_total[$i]:0;
                    //     $clients_all[$i]['payment_made_details'] = $payment_mades?$payment_mades:0;
                    //     $all_invoices = $clients_all[$i];
                    // }else{
                    //     $all_invoices = '-';
                    // }
                    // $all_projects = $this->api->get_allProjectsByClient($clients['co_id']);
                    // if($all_projects != '')
                    // {
                    //     $projects = $all_projects;
                    // }else{
                    //     $projects = '-';
                    // }
                    // $clients_all[$i]['projects'] = $projects;
                    // $clients_all[$i]['invoices'] = $all_invoices;
                    // $all_estimate = $this->api->get_allEstimateByClient($clients['co_id']);
                    // if($all_estimate != '')
                    // {
                    //     $estimate = $all_estimate;
                    // }else{
                    //     $estimate = '-';
                    // }
                    // $clients_all[$i]['estimates'] = $estimate;

                    $i++;
                }
                if(!empty($clients_all)){
                        $response['status_code'] = 1;
                        $response['message'] = $this->success;
                        $response['data'] = $clients_all;
                    }else{
                        $response['status_code'] = 0;
                        $response['message'] = $this->no_result_found;
                    }
                $this->response($response, REST_Controller::HTTP_OK);

            }else{
            $this->token_error();
            }
        }else{
            $this->token_error();
        }
    }

    public function estimate_list_post()
    {
        if($this->is_valid == TRUE)   
         {
            $res = $this->api->get_role_and_userid($this->token);
            if($res['role_id'] == 1)
            {
                $estimate_lists = $this->api->get_all_estimate();
                $estimate_list = array();
                $i=0;
                $total_cost = [];
                foreach ($estimate_lists as $estimate) {

                    $total_cost[$i] = 0;
                    $estimate_cost= $this->api->get_estimate_cost($estimate['est_id']);
                    foreach($estimate_cost as $items)
                    {
                        $total_cost[$i] += $items['total_cost']; 
                    }
                    $estimate_list[$i]['estimate_id']= $estimate['est_id'];
                    $estimate_list[$i]['reference_no']= $estimate['reference_no'];
                    $estimate_list[$i]['client_name']= $estimate['fullname'];
                    $estimate_list[$i]['status']= $estimate['status'];
                    $estimate_list[$i]['created_date']= $estimate['date_saved'];
                    $estimate_list[$i]['tax1']= $estimate['tax'];
                    $estimate_list[$i]['tax2']= $estimate['tax2'];
                    $estimate_list[$i]['discount']= $estimate['discount'];
                    if($estimate['tax'] != 0.00)
                    {
                        $tax_amount1 = ($total_cost[$i] * $estimate['tax']) / 100 ;
                    }else{
                        $tax_amount1 = 0;
                    }
                    if($estimate['tax2'] != 0)
                    {
                        $tax_amount2 = ($total_cost[$i] * $estimate['tax2']) / 100 ;
                    }else{
                        $tax_amount2 = 0;
                    }
                    if($estimate['discount'] != 0)
                    {
                        $discount = ($total_cost[$i] * $estimate['discount']) / 100 ;
                    }else{
                        $discount = 0;
                    }
                    $total_amount = ($total_cost[$i] + $tax_amount1 + $tax_amount2) - $discount;
                    $estimate_list[$i]['amount']= $total_amount;
                    $company = $this->api->get_company_details($estimate['client']);
                    $company_details = array(
                        'company_name' =>$company['company_name']?$company['company_name']:0,
                        'company_address' => $company['company_address']?$company['company_address']:0,
                        'city' =>$company['city']?$company['city']:0,
                        'state' =>$company['state']?$company['state']:0,
                        'country' =>$company['country']?$company['country']:0,
                        'zip_code' =>$company['zip']?$company['zip']:0,
                        'company_phone' =>$company['company_phone']?$company['company_phone']:0,
                        'company_vat'  =>$company['VAT']?$company['VAT']:0,
                        'company_fax'  =>$company['company_fax']?$company['company_fax']:0
                    );

                    $receiver = array(
                        'company_name' =>'DGT',
                        'company_address' => 'Coimbatore',
                        'city' =>'Coimbatore',
                        'state' =>'Tamilnadu',
                        'country' =>'India',
                        'zip_code' =>640135,
                        'company_phone' =>'9087654321 , 9876543210'
                    );
                    $estimate_list[$i]['bill_to'] = $company_details;
                    $estimate_list[$i]['receive_from'] = $receiver;
                    $estimate_list[$i]['items'] = $estimate_cost?$estimate_cost:0;

                    $i++;
                }
                if(!empty($estimate_list)){
                        $response['status_code'] = 1;
                        $response['message'] = $this->success;
                        $response['data'] = $estimate_list;
                    }else{
                        $response['status_code'] = 0;
                        $response['message'] = $this->no_result_found;
                    }
                
            }elseif($res['role_id'] == 2){


                $estimate_lists = $this->api->get_estimateByClient($res['user_id']);
                $estimate_list = array();
                $i=0;
                $total_cost = [];
                foreach ($estimate_lists as $estimate) {

                    $total_cost[$i] = 0;
                    $estimate_cost= $this->api->get_estimate_cost($estimate['est_id']);
                    foreach($estimate_cost as $items)
                    {
                        $total_cost[$i] += $items['total_cost']; 
                    }
                    $estimate_list[$i]['estimate_id']= $estimate['est_id'];
                    $estimate_list[$i]['reference_no']= $estimate['reference_no'];
                    $estimate_list[$i]['client_name']= $estimate['fullname'];
                    $estimate_list[$i]['status']= $estimate['status'];
                    $estimate_list[$i]['created_date']= $estimate['date_saved'];
                    $estimate_list[$i]['tax1']= $estimate['tax'];
                    $estimate_list[$i]['tax2']= $estimate['tax2'];
                    $estimate_list[$i]['discount']= $estimate['discount'];
                    if($estimate['tax'] != 0.00)
                    {
                        $tax_amount1 = ($total_cost[$i] * $estimate['tax']) / 100 ;
                    }else{
                        $tax_amount1 = 0;
                    }
                    if($estimate['tax2'] != 0)
                    {
                        $tax_amount2 = ($total_cost[$i] * $estimate['tax2']) / 100 ;
                    }else{
                        $tax_amount2 = 0;
                    }
                    if($estimate['discount'] != 0)
                    {
                        $discount = ($total_cost[$i] * $estimate['discount']) / 100 ;
                    }else{
                        $discount = 0;
                    }
                    $total_amount = ($total_cost[$i] + $tax_amount1 + $tax_amount2) - $discount;
                    $estimate_list[$i]['amount']= $total_amount;
                    $company = $this->api->get_company_details($estimate['client']);
                    $company_details = array(
                        'company_name' =>$company['company_name']?$company['company_name']:0,
                        'company_address' => $company['company_address']?$company['company_address']:0,
                        'city' =>$company['city']?$company['city']:0,
                        'state' =>$company['state']?$company['state']:0,
                        'country' =>$company['country']?$company['country']:0,
                        'zip_code' =>$company['zip']?$company['zip']:0,
                        'company_phone' =>$company['company_phone']?$company['company_phone']:0,
                        'company_vat'  =>$company['VAT']?$company['VAT']:0,
                        'company_fax'  =>$company['company_fax']?$company['company_fax']:0
                    );

                    $receiver = array(
                        'company_name' =>'DGT',
                        'company_address' => 'Coimbatore',
                        'city' =>'Coimbatore',
                        'state' =>'Tamilnadu',
                        'country' =>'India',
                        'zip_code' =>640135,
                        'company_phone' =>'9087654321 , 9876543210'
                    );
                    $estimate_list[$i]['bill_to'] = $company_details;
                    $estimate_list[$i]['receive_from'] = $receiver;
                    $estimate_list[$i]['items'] = $estimate_cost?$estimate_cost:0;

                    $i++;
                }
                if(!empty($estimate_list)){
                        $response['status_code'] = 1;
                        $response['message'] = $this->success;
                        $response['data'] = $estimate_list;
                    }else{
                        $response['status_code'] = 0;
                        $response['message'] = $this->no_result_found;
                    }
            }else{
                $this->token_error();
                
            }

            $this->response($response, REST_Controller::HTTP_OK);
        }else{
            $this->token_error();
        }
    }


    public function invoice_list_post()
    {
        if($this->is_valid == TRUE)   
        {
            $res = $this->api->get_role_and_userid($this->token);
            if($res['role_id'] == 1)
            {
                $all_invoices = $this->api->get_all_invoices();
                // print_r($all_invoices); exit;
                $invoice_list = array();
                $i=0;
                $total_cost = [];
                $payment_total = [];
                foreach($all_invoices as $invoice)
                {
                    $total_cost[$i] = 0;
                    $payment_total[$i] = 0;
                    $invoice_items = $this->api->get_invoice_items($invoice['inv_id']);
                    foreach($invoice_items as $items)
                    {
                        $total_cost[$i] += $items['total_cost']; 
                    }

                    $company = $this->api->get_company_details($invoice['client']);
                    $invoice_list[$i]['invoice_id']= $invoice['inv_id'];
                    $invoice_list[$i]['reference_no']= $invoice['reference_no'];
                    $invoice_list[$i]['company_name']= $company['company_name'];
                    $invoice_list[$i]['status']= $invoice['status'];
                    $invoice_list[$i]['created_date']= $invoice['date_saved'];
                    $invoice_list[$i]['due_date']= $invoice['due_date'];
                    $invoice_list[$i]['tax1']= $invoice['tax'];
                    $invoice_list[$i]['tax2']= $invoice['tax2'];
                    $invoice_list[$i]['discount']= $invoice['discount'];
                    $invoice_list[$i]['total_cost']= $total_cost[$i];
                    $company_details = array(
                        'company_address' => $company['company_address']?$company['company_address']:0,
                        'city' =>$company['city']?$company['city']:0,
                        'state' =>$company['state']?$company['state']:0,
                        'country' =>$company['country']?$company['country']:0,
                        'zip_code' =>$company['zip']?$company['zip']:0,
                        'company_phone' =>$company['company_phone']?$company['company_phone']:0,
                        'company_vat'  =>$company['VAT']?$company['VAT']:0,
                        'company_fax'  =>$company['company_fax']?$company['company_fax']:0
                    );

                    $receiver = array(
                        'company_name' =>'DGT',
                        'company_address' => 'Coimbatore',
                        'city' =>'Coimbatore',
                        'state' =>'Tamilnadu',
                        'country' =>'India',
                        'zip_code' =>640135,
                        'company_phone' =>'9087654321 , 9876543210'
                    );
                    $invoice_list[$i]['bill_to'] = $company_details;
                    $invoice_list[$i]['receive_from'] = $receiver;
                    $invoice_list[$i]['items'] = $invoice_items?$invoice_items:0;

                    $payment_mades = $this->api->get_invoice_payment($invoice['inv_id']);
                    foreach($payment_mades as $payment_made){
                            $payment_total[$i] += $payment_made['amount']; 
                    }
                    $invoice_list[$i]['payment_made'] = $payment_total[$i]?$payment_total[$i]:0;
                    $invoice_list[$i]['payment_made_details'] = $payment_mades?$payment_mades:0;

                    $i++;

                }

                if(!empty($invoice_list)){
                        $response['status_code'] = 1;
                        $response['message'] = $this->success;
                        $response['data'] = $invoice_list;
                    }else{
                        $response['status_code'] = 0;
                        $response['message'] = $this->no_result_found;
                    }

            }elseif($res['role_id'] == 2){
                $all_invoices = $this->api->get_invoicesbyClient($res['user_id']);
                // print_r($all_invoices); exit;
                $invoice_list = array();
                $i=0;
                $total_cost = [];
                $payment_total = [];
                foreach($all_invoices as $invoice)
                {
                    $total_cost[$i] = 0;
                    $payment_total[$i] = 0;
                    $invoice_items = $this->api->get_invoice_items($invoice['inv_id']);
                    foreach($invoice_items as $items)
                    {
                        $total_cost[$i] += $items['total_cost']; 
                    }

                    $company = $this->api->get_company_details($invoice['client']);
                    $invoice_list[$i]['invoice_id']= $invoice['inv_id'];
                    $invoice_list[$i]['reference_no']= $invoice['reference_no'];
                    $invoice_list[$i]['company_name']= $company['company_name'];
                    $invoice_list[$i]['status']= $invoice['status'];
                    $invoice_list[$i]['created_date']= $invoice['date_saved'];
                    $invoice_list[$i]['due_date']= $invoice['due_date'];
                    $invoice_list[$i]['tax1']= $invoice['tax'];
                    $invoice_list[$i]['tax2']= $invoice['tax2'];
                    $invoice_list[$i]['discount']= $invoice['discount'];
                    $invoice_list[$i]['total_cost']= $total_cost[$i];
                    $company_details = array(
                        'company_address' => $company['company_address']?$company['company_address']:0,
                        'city' =>$company['city']?$company['city']:0,
                        'state' =>$company['state']?$company['state']:0,
                        'country' =>$company['country']?$company['country']:0,
                        'zip_code' =>$company['zip']?$company['zip']:0,
                        'company_phone' =>$company['company_phone']?$company['company_phone']:0,
                        'company_vat'  =>$company['VAT']?$company['VAT']:0,
                        'company_fax'  =>$company['company_fax']?$company['company_fax']:0
                    );

                    $receiver = array(
                        'company_name' =>'DGT',
                        'company_address' => 'Coimbatore',
                        'city' =>'Coimbatore',
                        'state' =>'Tamilnadu',
                        'country' =>'India',
                        'zip_code' =>640135,
                        'company_phone' =>'9087654321 , 9876543210'
                    );
                    $invoice_list[$i]['bill_to'] = $company_details;
                    $invoice_list[$i]['receive_from'] = $receiver;
                    $invoice_list[$i]['items'] = $invoice_items?$invoice_items:0;

                    $payment_mades = $this->api->get_invoice_payment($invoice['inv_id']);
                    foreach($payment_mades as $payment_made){
                            $payment_total[$i] += $payment_made['amount']; 
                    }
                    $invoice_list[$i]['payment_made'] = $payment_total[$i]?$payment_total[$i]:0;
                    $invoice_list[$i]['payment_made_details'] = $payment_mades?$payment_mades:0;

                    $i++;

                }

                if(!empty($invoice_list)){
                        $response['status_code'] = 1;
                        $response['message'] = $this->success;
                        $response['data'] = $invoice_list;
                    }else{
                        $response['status_code'] = 0;
                        $response['message'] = $this->no_result_found;
                    }
            // $this->token_error();
            }else{
                $this->token_error();
                
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{
            $this->token_error();
        }
    }



     public function one_to_one_call_post()
    {
        if($this->is_valid == TRUE)   
        {
            $res = $this->api->get_role_and_userid($this->token);
            $from_user_details = $this->api->get_userById($res['user_id']);
            $inputs = $this->post();
            if((!empty($inputs['user_id'])) && (!empty($inputs['call_type']))){
                $user_details = $this->api->get_userById($inputs['user_id']);
                if($user_details['avatar'] == '')
                {
                    $user_profile = 'default_avatar.jpg';
                }else{
                    $user_profile = $user_details['avatar'];
                }

                $opentok = new OpenTok($this->apiKey, $this->apiSecret);
                // An automatically archived session:
                $sessionOptions = array(
                    // 'archiveMode' => ArchiveMode::ALWAYS,
                    'mediaMode' => MediaMode::ROUTED
                );
                $new_session = $opentok->createSession($sessionOptions);



                // Store this sessionId in the database for later use
                $sessionId = $new_session->getSessionId();

                $from_token = $new_session->generateToken(array(
                    'role'       => Role::MODERATOR,
                    'expireTime' => time()+(7 * 24 * 60 * 60)
                ));

                $to_token = $new_session->generateToken(array(
                    'role'       => Role::MODERATOR,
                    'expireTime' => time()+(7 * 24 * 60 * 60)
                ));

                $additional_data =array();
                $additional_data['name'] = $user_details['fullname'];
                $additional_data['user_id'] = $user_details['user_id'];
                $additional_data['profile_image'] = base_url().'assets/avatar/'.$user_profile;
                $additional_data['call_type'] = $inputs['call_type'];   
                $additional_data['to_token'] = $to_token;                
                $additional_data['session_id'] = $sessionId;


                $result = array(
                    'name' => $user_details['fullname'],
                    'profile_image' => base_url().'assets/avatar/'.$user_profile,
                    'call_type' =>$inputs['call_type'],
                    'from_token' => $from_token,
                    // 'to_token' => $to_token,
                    'session_id' => $sessionId
                );


                    $one_signal_app_id = '4b3604d1-e319-4b3e-8f00-d9e0997320b0';    
                    $one_signal_reset_key = 'ZGMxNTdlYmEtYjBjMC00MzZjLWI5NTAtMWNmMGExYzJjNWU4';

                    $device = $this->api->get_deviceIdByUser($inputs['user_id']);

                    $data = array(
                        'message' => 'Calling...',
                        'device_id'=>$device['device_id'],
                        'additional_data' => $additional_data
                    );
                    $message = $data['message'];
                    $device_ids = $data['device_id'];
                    $device_id =  array($device_ids);  

                     $heading = array(
                       "en" => $from_user_details['fullname']
                    );

                    $button = array(
                        array('id'=>'answer','title'=>'Call From '.$additional_data['from_name'].'','text'=>'Answer','icon'=>""),
                        array('id'=>'decline','title'=>'Call From '.$additional_data['from_name'].'','text'=>'Decline','icon'=>"")
                    );  
                    $content = array("en" => "$message");       
                    $fields = array(
                        'app_id' => $one_signal_app_id,
                        'data' => $data['additional_data'],                        
                        'include_player_ids' => $device_id,
                        'contents' => $content,
                        'headings' => $heading,
                        'buttons' => $button,
                        'action' => "like-button",
                        'priority' => '10'
                    );
                    if(empty($device_ids)){
                        unset($fields['include_player_ids']);
                    }      
                    
                    $fields = json_encode($fields);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                        'Authorization: Basic '.$one_signal_reset_key));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($ch, CURLOPT_HEADER, FALSE);
                    curl_setopt($ch, CURLOPT_POST, TRUE);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                    $res = curl_exec($ch);
                    curl_close($ch);
                   // return $res;

                if(!empty($result)){
                        $response['status_code'] = 1;
                        $response['message'] = $this->success;
                        $response['data'] = $result;
                   }else{
                        $response['status_code'] = 0;
                        $response['message'] = $this->no_result_found;
                   }

            }else{
                $response['status_code'] = 0;
                $response['message'] = $this->required_input;
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{
            $this->token_error();
        }
    }


    public function client_profile_post()
    {
         if($this->is_valid == TRUE)   
        {
            $res = $this->api->get_role_and_userid($this->token);
            if($res['role_id'] == 1)
            {
                $user_id = $this->input->post('co_id');
                    $clients_all = array();
                if($user_id != ''){
                    $total_cost = [];
                    $payment_total = [];
                    $invoices = [];
                    $invoices_all = array();
                    // $all_invoices = [];
                    $clients = $this->api->get_clientById($user_id);
                    $clients_all['co_id'] = $clients['co_id']?$clients['co_id']:'- ';
                    $clients_all['company_name'] = $clients['company_name'];
                    $clients_all['fullname'] = $clients['fullname']?$clients['fullname']:'-';
                    $clients_all['company_email'] = $clients['company_email']?$clients['company_email']:'-';
                    $clients_all['company_website'] = $clients['company_website']?$clients['company_website']:'-';
                    $clients_all['company_phone'] = $clients['company_phone']?$clients['company_phone']:'-';
                    $clients_all['company_mobile'] = $clients['company_mobile']?$clients['company_mobile']:'-';
                    $clients_all['company_fax'] = $clients['company_fax']?$clients['company_fax']:'-';
                    $clients_all['company_address'] = $clients['company_address']?$clients['company_address']:'-';
                    $clients_all['city'] = $clients['city']?$clients['city']:'-';
                    $clients_all['state'] = $clients['state']?$clients['state']:'-';
                    $clients_all['country'] = $clients['country']?$clients['country']:'-';
                    $clients_all['zip'] = $clients['zip']?$clients['zip']:'-';
                    $clients_all['skype'] = $clients['skype']?$clients['skype']:'-';
                    $clients_all['linkedin'] = $clients['linkedin']?$clients['linkedin']:'-';
                    $clients_all['facebook'] = $clients['facebook']?$clients['facebook']:'-';
                    $clients_all['twitter'] = $clients['twitter']?$clients['twitter']:'-';
                    if($clients['primary_contact'] != '')
                    {
                        $contact_person = $this->api->get_userById($clients['primary_contact']);
                        $c_person = $contact_person['fullname'];
                    }else{
                        $c_person = '-';
                    }
                    $clients_all['contact_person'] = $c_person;
                    if($clients['user_id'] != '')
                    {
                        $invoices = $this->api->get_invoiceByClientId($clients['user_id']);
                        $total_cost = 0;
                        $payment_total = 0;
                        $invoice_items = $this->api->get_invoice_items($invoices['inv_id']);
                        foreach($invoice_items as $items)
                        {
                            $total_cost += $items['total_cost']; 
                        }
                        $company_details = array(
                        'company_address' => $clients['company_address']?$clients['company_address']:'-',
                        'city' =>$clients['city']?$clients['city']:'-',
                        'state' =>$clients['state']?$clients['state']:'-',
                        'country' =>$clients['country']?$clients['country']:0,
                        'zip_code' =>$clients['zip']?$clients['zip']:0,
                        'company_phone' =>$clients['company_phone']?$clients['company_phone']:0,
                        'company_vat'  =>$clients['VAT']?$clients['VAT']:0,
                        'company_fax'  =>$clients['company_fax']?$clients['company_fax']:0
                        );

                        $receiver = array(
                            'company_name' =>'DGT',
                            'company_address' => 'Coimbatore',
                            'city' =>'Coimbatore',
                            'state' =>'Tamilnadu',
                            'country' =>'India',
                            'zip_code' =>640135,
                            'company_phone' =>'9087654321 , 9876543210'
                        );
                        $invoices_all['bill_to'] = $company_details;
                        $invoices_all['receive_from'] = $receiver;
                        $invoices_all['items'] = $invoice_items?$invoice_items:0;

                        $payment_mades = $this->api->get_invoice_payment($invoices['inv_id']);
                        // $i=0;
                        foreach($payment_mades as $payment_made){
                                $payment_total += $payment_made['amount']; 
                                // $i++;
                        }
                        $invoices_all['payment_made'] = $payment_total?$payment_total:0;
                        $invoices_all['payment_made_details'] = $payment_mades?$payment_mades:0;
                        // $clients_all['invoices'] = $invoices_all;
                        // $all_invoices = $invoices_all;
                    }else{
                        $invoices_all = '';
                        // $clients_all['invoices'] = '';
                        // $all_invoices = '-';
                    }
                    $all_projects = $this->api->get_allProjectsByClient($clients['co_id']);
                    if($all_projects != '')
                    {
                        $projects = $all_projects;
                    }else{
                        $projects = '-';
                    }
                    $clients_all['projects'] = $projects;
                    // $clients_all['invoices'] = $all_invoices;
                    $all_estimate = $this->api->get_allEstimateByClient($clients['co_id']);
                    if($all_estimate != '')
                    {
                        $estimate = $all_estimate;
                    }else{
                        $estimate = '-';
                    }
                    $clients_all['estimates'] = $estimate;
                    $clients_all['invoices'] = $invoices_all;
                    // $clients_all['estimates']['bill_to'] = $company_details;
                    // $clients_all['estimates']['receive_from'] = $receiver;
                    if(!empty($clients_all)){
                        $response['status_code'] = 1;
                        $response['message'] = $this->success;
                        $response['data'] = $clients_all;
                    }else{
                        $response['status_code'] = 0;
                        $response['message'] = $this->no_result_found;
                    }
                }else{
                    $response['status_code'] = 0;
                    $response['message'] = $this->required_input;
                }
            }elseif($res['role_id'] != 1){

                $user_id = $res['user_id'];
                $clients_all = array();
                $total_cost = [];
                $payment_total = [];
                $invoices = [];
                $clients = $this->api->get_clientById($user_id);
                $clients_all['co_id'] = $clients['co_id']?$clients['co_id']:'- ';
                $clients_all['company_name'] = $clients['company_name'];
                $clients_all['fullname'] = $clients['fullname']?$clients['fullname']:'-';
                $clients_all['company_email'] = $clients['company_email']?$clients['company_email']:'-';
                $clients_all['company_website'] = $clients['company_website']?$clients['company_website']:'-';
                $clients_all['company_phone'] = $clients['company_phone']?$clients['company_phone']:'-';
                $clients_all['company_mobile'] = $clients['company_mobile']?$clients['company_mobile']:'-';
                $clients_all['company_fax'] = $clients['company_fax']?$clients['company_fax']:'-';
                $clients_all['company_address'] = $clients['company_address']?$clients['company_address']:'-';
                $clients_all['city'] = $clients['city']?$clients['city']:'-';
                $clients_all['state'] = $clients['state']?$clients['state']:'-';
                $clients_all['country'] = $clients['country']?$clients['country']:'-';
                $clients_all['zip'] = $clients['zip']?$clients['zip']:'-';
                $clients_all['skype'] = $clients['skype']?$clients['skype']:'-';
                $clients_all['linkedin'] = $clients['linkedin']?$clients['linkedin']:'-';
                $clients_all['facebook'] = $clients['facebook']?$clients['facebook']:'-';
                $clients_all['twitter'] = $clients['twitter']?$clients['twitter']:'-';
                if($clients['primary_contact'] != '')
                {
                    $contact_person = $this->api->get_userById($clients['primary_contact']);
                    $c_person = $contact_person['fullname'];
                }else{
                    $c_person = '-';
                }
                $clients_all['contact_person'] = $c_person;
                if($clients['user_id'] != '')
                {
                    $invoices = $this->api->get_invoiceByClientId($clients['user_id']);
                    $total_cost = 0;
                    $payment_total = 0;
                    $invoice_items = $this->api->get_invoice_items($invoices['inv_id']);
                    foreach($invoice_items as $items)
                    {
                        $total_cost += $items['total_cost']; 
                    }
                    $company_details = array(
                    'company_address' => $clients['company_address']?$clients['company_address']:'-',
                    'city' =>$clients['city']?$clients['city']:'-',
                    'state' =>$clients['state']?$clients['state']:'-',
                    'country' =>$clients['country']?$clients['country']:0,
                    'zip_code' =>$clients['zip']?$clients['zip']:0,
                    'company_phone' =>$clients['company_phone']?$clients['company_phone']:0,
                    'company_vat'  =>$clients['VAT']?$clients['VAT']:0,
                    'company_fax'  =>$clients['company_fax']?$clients['company_fax']:0
                    );

                    $receiver = array(
                        'company_name' =>'DGT',
                        'company_address' => 'Coimbatore',
                        'city' =>'Coimbatore',
                        'state' =>'Tamilnadu',
                        'country' =>'India',
                        'zip_code' =>640135,
                        'company_phone' =>'9087654321 , 9876543210'
                    );
                    $clients_all['bill_to'] = $company_details;
                    $clients_all['receive_from'] = $receiver;
                    $clients_all['items'] = $invoice_items?$invoice_items:0;

                    $payment_mades = $this->api->get_invoice_payment($invoices['inv_id']);
                    // $i=0;
                    foreach($payment_mades as $payment_made){
                            $payment_total += $payment_made['amount']; 
                            // $i++;
                    }
                    $clients_all['payment_made'] = $payment_total?$payment_total:0;
                    $clients_all['payment_made_details'] = $payment_mades?$payment_mades:0;
                    $all_invoices = $clients_all;
                }else{
                    $all_invoices = '-';
                }
                $all_projects = $this->api->get_allProjectsByClient($clients['co_id']);
                if($all_projects != '')
                {
                    $projects = $all_projects;
                }else{
                    $projects = '-';
                }
                $clients_all['projects'] = $projects;
                $clients_all['invoices'] = $all_invoices;
                $all_estimate = $this->api->get_allEstimateByClient($clients['co_id']);
                if($all_estimate != '')
                {
                    $estimate = $all_estimate;
                }else{
                    $estimate = '-';
                }
                $clients_all['estimates'] = $estimate;
                $clients_all['estimates']['bill_to'] = $company_details;
                $clients_all['estimates']['receive_from'] = $receiver;
                if(!empty($clients_all)){
                    $response['status_code'] = 1;
                    $response['message'] = $this->success;
                    $response['data'] = $clients_all;
                }else{
                    $response['status_code'] = 0;
                    $response['message'] = $this->no_result_found;
                }
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{
            $this->token_error();
        }
    }


    public function task_details_post()
    {
        if($this->is_valid == TRUE)   
        {
            $task_id = $this->input->post('task_id');
            if($task_id != '')
            {   
                $task = $this->api->get_taskDetails($task_id);
                // print_r($task); exit;
                // print_r(unserialize($task['assign_to'])); exit;
                // $project_lead = $this->api->get_user_detail('lead',$task['assign_lead']);
                // $project_members = $this->api->get_user_detail('members',$task['assign_to']);
                // $task['project_lead'] = $project_lead['fullname'];
                // $task['project_members'] = $project_members;
                $task_files = $this->api->get_taskfileById($task_id);
                // print_r($task_files); exit;
                if(count($task_files) > 0)
                {
                    $t_files = $task_files;
                }else{
                    $t_files = 0;
                }
                $task['task_files'] = $t_files;
                if(!empty($task)){
                    $response['status_code'] = 1;
                    $response['message'] = $this->success;
                    $response['data'] = $task;
                }else{
                    $response['status_code'] = 0;
                    $response['message'] = $this->no_result_found;
                }
            }else{
                $response['status_code'] = 0;
                $response['message'] = $this->required_input;
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{
            $this->token_error();
        }

    }

    public function call_decline_post()
    {

        if($this->is_valid == TRUE)   
        {
            $response = array();
            $res = $this->api->get_role_and_userid($this->token);
            $from_user_details = $this->api->get_userById($res['user_id']);
            $inputs = $this->post();
            $to_id = $inputs['user_id'];
            $one_signal_app_id = '4b3604d1-e319-4b3e-8f00-d9e0997320b0';    
            $one_signal_reset_key = 'ZGMxNTdlYmEtYjBjMC00MzZjLWI5NTAtMWNmMGExYzJjNWU4';

            $device = $this->api->get_deviceIdByUser($to_id);

            $data = array(
                'message' => 'Call Rejected...',
                'device_id'=>$device['device_id']
            );
            $message = $data['message'];
            $device_ids = $data['device_id'];
            $device_id =  array($device_ids);  

             $heading = array(
               "en" => $from_user_details['fullname']
            );
            $content = array("en" => "$message");       
            $fields = array(
                'app_id' => $one_signal_app_id,
                'include_player_ids' => $device_id,
                'contents' => $content,
                'headings' => $heading,
                'priority' => '10'
            );
            if(empty($device_ids)){
                unset($fields['include_player_ids']);
            }      
            
            $fields = json_encode($fields);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                'Authorization: Basic '.$one_signal_reset_key));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            $res = curl_exec($ch);
            curl_close($ch);
            $response['status_code'] = 1;
            $response['message'] = $this->success;
            $this->response($response, REST_Controller::HTTP_OK);
        }else{
            $this->token_error();
        }
    }

    public function group_call_post()
    {
        if($this->is_valid == TRUE)   
        {
            $inputs = $this->post();
            $group_name = $inputs['group_name'];
            $user_ids = $inputs['user_ids'];
            $type_call = $inputs['call_type'];
            $res = $this->api->get_role_and_userid($this->token);
            $from_user = $this->api->get_userById($res['user_id']);
            if(($group_name != '') && ($user_ids != '') && ($type_call != ''))
            {
            $all_user_ids = explode(',', $user_ids);


                $opentok = new OpenTok($this->apiKey, $this->apiSecret);
                    // An automatically archived session:
                $sessionOptions = array(
                    // 'archiveMode' => ArchiveMode::ALWAYS,
                    'mediaMode' => MediaMode::ROUTED
                );
                $new_session = $opentok->createSession($sessionOptions);
                    // Store this sessionId in the database for later use
                $sessionId = $new_session->getSessionId();



                $data = array('group_name' => $group_name,'session_id' => $sessionId);
                $count = $this->db->get_where('fx_chat_group_details',$data)->num_rows();
                if($count!=0){
                    $result = array('error'=>'Group name already taken!');      
                    
                }else{

                    $this->db->insert('fx_chat_group_details',$data);
                    $group_id = $this->db->insert_id();

                    $member = explode(',',$user_ids);
                    for ($i=0; $i <count($member) ; $i++) { 

                        $user = $this->db
                        ->select('username,id')
                        ->get_where('fx_users',array('id'=>$member[$i],'activated'=>1))
                        ->row_array();
                        $group_members[]=$user;
                        if(!empty($user)){
                            $usernames[]=$user['username'];
                            $datas = array(
                                'group_id' => $group_id,
                                'login_id' => $user['id']
                            );
                            $this->db->insert('fx_chat_group_members',$datas);
                            $this->db->insert('fx_chat_seen_details',$datas);   
                        }
                        
                    }
                    // print_r($group_members); exit;
                    $all_username = implode(',',$usernames);

                    if($from_user['avatar'] == '')
                    {
                        $user_profile = 'default_avatar.jpg';
                    }else{
                        $user_profile = $from_user['avatar'];
                    }

                    $opentok = new OpenTok($this->apiKey, $this->apiSecret);
                    // An automatically archived session:
                    $sessionOptions = array(
                        // 'archiveMode' => ArchiveMode::ALWAYS,
                        'mediaMode' => MediaMode::ROUTED
                    );
                    $new_session = $opentok->createSession($sessionOptions);



                    // Store this sessionId in the database for later use
                    $sessionId_from = $new_session->getSessionId();

                    $token_from = $new_session->generateToken(array(
                        'role'       => Role::MODERATOR,
                        'expireTime' => time()+(7 * 24 * 60 * 60)
                    ));


                    $result = array(
                        'name' => $from_user['fullname'],
                        'user_id' => $from_user['user_id'],
                        'profile_image' => base_url().'assets/avatar/'.$user_profile,
                        'call_type' =>$type_call,
                        'from_token' => $token_from,
                        'session_id' => $sessionId_from,
                        'group_id'   => $group_id
                    );


                    foreach($all_user_ids as $u_id)
                    {
                        $user_details = $this->api->get_userById($u_id);
                        if($user_details['avatar'] == '')
                        {
                            $user_profile = 'default_avatar.jpg';
                        }else{
                            $user_profile = $user_details['avatar'];
                        }

                        $opentok = new OpenTok($this->apiKey, $this->apiSecret);
                        // An automatically archived session:
                        $sessionOptions = array(
                            // 'archiveMode' => ArchiveMode::ALWAYS,
                            'mediaMode' => MediaMode::ROUTED
                        );
                        $new_session = $opentok->createSession($sessionOptions);



                        // Store this sessionId in the database for later use
                        $sessionId = $new_session->getSessionId();

                        $from_token = $new_session->generateToken(array(
                            'role'       => Role::MODERATOR,
                            'expireTime' => time()+(7 * 24 * 60 * 60)
                        ));

                        $to_token = $new_session->generateToken(array(
                            'role'       => Role::MODERATOR,
                            'expireTime' => time()+(7 * 24 * 60 * 60)
                        ));

                        $additional_data =array();
                        $additional_data['name'] = $user_details['fullname'];
                        
                        $additional_data['profile_image'] = base_url().'assets/avatar/'.$user_profile;
                        $additional_data['call_type'] = $type_call;   
                        $additional_data['to_token'] = $to_token;                
                        $additional_data['session_id'] = $sessionId;
                        $one_signal_app_id = '4b3604d1-e319-4b3e-8f00-d9e0997320b0';    
                        $one_signal_reset_key = 'ZGMxNTdlYmEtYjBjMC00MzZjLWI5NTAtMWNmMGExYzJjNWU4';

                        $device = $this->api->get_deviceIdByUser($u_id);

                        $data = array(
                            'message' => 'Calling...',
                            'device_id'=>$device['device_id'],
                            'additional_data' => $additional_data
                        );
                        $message = $data['message'];
                        $device_ids = $data['device_id'];
                        $device_id =  array($device_ids);
                         $heading = array(
                           "en" => $from_user['fullname']
                        );
                        $button = array(
                            array('id'=>'answer','title'=>'Call From '.$additional_data['name'].'','text'=>'Answer','icon'=>""),
                            array('id'=>'decline','title'=>'Call From '.$additional_data['name'].'','text'=>'Decline','icon'=>"")
                        );  
                        $content = array("en" => "$message");       
                        $fields = array(
                            'app_id' => $one_signal_app_id,
                            'data' => $data['additional_data'],                        
                            'include_player_ids' => $device_id,
                            'contents' => $content,
                            'headings' => $heading,
                            'buttons' => $button,
                            'action' => "like-button",
                            'priority' => '10'
                        );
                        if(empty($device_ids)){
                            unset($fields['include_player_ids']);
                        }      
                        
                            $fields = json_encode($fields);
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Authorization: Basic '.$one_signal_reset_key));
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                            curl_setopt($ch, CURLOPT_HEADER, FALSE);
                            curl_setopt($ch, CURLOPT_POST, TRUE);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                            $res = curl_exec($ch);
                            curl_close($ch);
                        }
                        $result['user_names'] = $all_username;
                }
                $response['status_code'] = 1;
                $response['message'] = $this->success;
                $response['data'] = $result;

            }else{
                $response['status_code'] = 0;
                $response['message'] = $this->required_input;
            }

            $this->response($response, REST_Controller::HTTP_OK);




        }else{
            $this->token_error();
        }
    }

    public function user_chat_post() 
    {
        if($this->is_valid == TRUE)   
        {
            $res = $this->api->get_role_and_userid($this->token);
            $from_user = $this->api->get_userById($res['user_id']);
            $from_user_details = array(
                'user_name' => $from_user['fullname'],
                'profile_image' => base_url().'assets/avatar/'.$from_user['avatar']
            );
            $inputs = $this->post();
            if(($inputs['chat_type'] != '') && ($inputs['to_user'] != ''))
            {
                $common_session = $this->api->get_common_session();
                $sessionId = $common_session['common_session_id'];
                $opentok = new OpenTok($this->apiKey, $this->apiSecret);
                $from_token = $opentok->generateToken($sessionId);
                if($inputs['chat_type'] == 'one')
                {
                    $to_user = $this->api->get_userById($inputs['to_user']);
                    $to_user_details = array(
                        'user_name' => $to_user['fullname'],
                        'profile_image' => base_url().'assets/avatar/'.$to_user['avatar']
                    );
                    $to_token = $opentok->generateToken($sessionId);
                    $result = array(
                        'sessionId'         => $sessionId,
                        'from_token'        => $from_token,
                        'to_token'          => $to_token,
                        'from_user_details' => $from_user_details,
                        'to_user_details'   => $to_user_details
                    );
                }elseif($inputs['chat_type'] == 'group'){
                    $to_users = array();
                    $to_user_ids = explode(',', $inputs['to_user']);

                    for ($i=0; $i < count($to_user_ids); $i++) { 
                        $to_user[$i] = $this->api->get_userById($to_user_ids[$i]);
                        $to_user_details[$i] = array(
                            'user_name' => $to_user[$i]['fullname'],
                            'profile_image' => base_url().'assets/avatar/'.$to_user[$i]['avatar']
                        );
                        $to_token[$i] = $opentok->generateToken($sessionId);
                        $res[$i] = array(
                            'to_token'          => $to_token[$i],
                            'to_user_details'   => $to_user_details[$i]
                        );
                        $to_users[] = $res[$i];
                    }
                    $result = array(
                        'sessionId'         => $sessionId,
                        'from_token'        => $from_token,
                        'from_user_details' => $from_user_details,
                        'to_users'          => $to_users
                    );

                }else{
                    $response['status_code'] = 0;
                    $response['message'] = $this->something;
                }
                $response['status_code'] = 1;
                $response['message'] = $this->success;
                $response['data'] = $result;
            }else{
                $response['status_code'] = 0;
                $response['message'] = $this->required_input;
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{
            $this->token_error();
        }
    }



    public function chat_message_post()
    {
        if($this->is_valid == TRUE)   
        {
            $inputs = $this->post();
            $res = $this->api->get_role_and_userid($this->token);
            $from_user = $this->api->get_userById($res['user_id']);
            if(($inputs['msg_type'] !='') && ($inputs['user_msg'] != '') && ($inputs['from_connectionid'] != '') && ($inputs['to_user'] != ''))
            {
                $check_connectionid = $this->api->check_connectionidByUser($res['user_id']);
                if($check_connectionid != '')
                {
                    $connection_status = $this->api->connectionid_status($res['user_id'], $inputs['from_connectionid'] ,'update');
                }else{
                    $connection_status = $this->api->connectionid_status($res['user_id'], $inputs['from_connectionid'],'insert');
                }
                $common_session = $this->api->get_common_session();
                $sessionId = $common_session['common_session_id'];
                $opentok = new OpenTok($this->apiKey, $this->apiSecret);
                if($inputs['msg_type'] == 'one')
                {
                    $msg_details = array(
                        'from_id' => $res['user_id'],
                        'to_id'   => $inputs['to_user'],
                        'message' => $inputs['user_msg'],
                        'msg_type'=> 'one',
                        'msg_date'=> date('Y-m-d H:i:s')
                    );
                    $this->db->insert('fx_chat_conversations',$msg_details);

                     if($from_user['avatar'] == '')
                        {
                            $user_profile = 'default_avatar.jpg';
                        }else{
                            $user_profile = $from_user['avatar'];
                        }
                    
                    $to_token = $opentok->generateToken($sessionId);
                    $additional_data =array();
                    $additional_data['name'] = $from_user['fullname'];
                    
                    $additional_data['profile_image'] = base_url().'assets/avatar/'.$user_profile;
                    $additional_data['call_type']  = 'text';
                    $additional_data['from_id']    = $from_user['user_id'];
                    $additional_data['to_id']      = $inputs['to_user'];
                    $additional_data['session_id'] = $sessionId;
                    $additional_data['to_token']   = $to_token;
                    $one_signal_app_id = '4b3604d1-e319-4b3e-8f00-d9e0997320b0';    
                    $one_signal_reset_key = 'ZGMxNTdlYmEtYjBjMC00MzZjLWI5NTAtMWNmMGExYzJjNWU4';

                    $device = $this->api->get_deviceIdByUser($inputs['to_user']);

                    $data = array(
                        'message' => 'New Message',
                        'device_id'=>$device['device_id'],
                        'additional_data' => $additional_data
                    );
                    $message = $data['message'];
                    $device_ids = $data['device_id'];
                    $device_id =  array($device_ids);
                     $heading = array(
                       "en" => $from_user['fullname']
                    );
                    $button = array(
                        array('id'=>'answer','title'=>'Message From '.$additional_data['name'].'','text'=>'View','icon'=>"")
                    );  
                    $content = array("en" => "$message");       
                    $fields = array(
                        'app_id' => $one_signal_app_id,
                        'data' => $data['additional_data'],                        
                        'include_player_ids' => $device_id,
                        'contents' => $content,
                        'headings' => $heading,
                        'buttons' => $button,
                        'action' => "like-button",
                        'priority' => '10'
                    );
                    if(empty($device_ids)){
                        unset($fields['include_player_ids']);
                    }      
                    
                        $fields = json_encode($fields);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Authorization: Basic '.$one_signal_reset_key));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                        curl_setopt($ch, CURLOPT_HEADER, FALSE);
                        curl_setopt($ch, CURLOPT_POST, TRUE);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                        $res = curl_exec($ch);
                        curl_close($ch);
                        echo "success"; exit;

                }elseif($inputs['msg_type'] == 'group'){
                    $user_ids = explode(',', $inputs['to_user']);
                    for ($i=0; $i < count($user_ids); $i++) { 
                        $msg_details = array(
                        'from_id' => $res['user_id'],
                        'to_id'   => $user_ids[$i],
                        'message' => $inputs['user_msg'],
                        'msg_type'=> 'group',
                        'group_id'=> $inputs['group_id'],
                        'msg_date'=> date('Y-m-d H:i:s')
                    );
                    $this->db->insert('fx_chat_conversations',$msg_details);
                    $to_token = $opentok->generateToken($sessionId);
                    $additional_data =array();
                    $additional_data['name'] = $from_user['fullname'];
                    
                    $additional_data['profile_image'] = base_url().'assets/avatar/'.$user_profile;
                    $additional_data['call_type'] = 'text';   
                    $additional_data['from_id'] = $from_user['user_id'];   
                    $additional_data['to_id'] = $user_ids[$i]; 
                    $additional_data['session_id'] = $sessionId;
                    $additional_data['to_token']   = $to_token;  
                    $additional_data['group_id']   = $inputs['group_id'];  
                    $one_signal_app_id = '4b3604d1-e319-4b3e-8f00-d9e0997320b0';    
                    $one_signal_reset_key = 'ZGMxNTdlYmEtYjBjMC00MzZjLWI5NTAtMWNmMGExYzJjNWU4';

                    $device = $this->api->get_deviceIdByUser($user_ids[$i]);
                    if($device != '')
                    {
                        $data = array(
                            'message' => 'New Message',
                            'device_id'=>$device['device_id'],
                            'additional_data' => $additional_data
                        );
                        $message = $data['message'];
                        $device_ids = $data['device_id'];
                        $device_id =  array($device_ids);
                         $heading = array(
                           "en" => $from_user['fullname']
                        );
                        $button = array(
                            array('id'=>'answer','title'=>'Message From '.$additional_data['name'].'','text'=>'View','icon'=>"")
                        );  
                        $content = array("en" => "$message");       
                        $fields = array(
                            'app_id' => $one_signal_app_id,
                            'data' => $data['additional_data'],                        
                            'include_player_ids' => $device_id,
                            'contents' => $content,
                            'headings' => $heading,
                            'buttons' => $button,
                            'action' => "like-button",
                            'priority' => '10'
                        );
                        // print_r($fields);
                        if(empty($device_ids)){
                            unset($fields['include_player_ids']);
                        }      
                        
                            $fields = json_encode($fields);
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Authorization: Basic '.$one_signal_reset_key));
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                            curl_setopt($ch, CURLOPT_HEADER, FALSE);
                            curl_setopt($ch, CURLOPT_POST, TRUE);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                            $res = curl_exec($ch);
                            curl_close($ch);
                        }else{
                            $response['status_code'] = 0;
                            $response['message'] = $this->no_deviceid;
                        }

                    }
                    $response['status_code'] = 1;
                    $response['message'] = $this->success;
                }

            }else{
                $response['status_code'] = 0;
                $response['message'] = $this->required_input;
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{
            $this->token_error();
        }
    }


    public function all_chat_message_post()
    {
        if($this->is_valid == TRUE)   
        {
            $inputs = $this->post();
            $res = $this->api->get_role_and_userid($this->token);
            $from_user = $this->api->get_userById($res['user_id']);
            $to_user_id = $inputs['user_id'];
            $call_type = $inputs['call_type'];
            $from_id = $from_user['user_id'];
            // echo $call_type; exit;
            if($call_type == 'one')
            {
                $to_user = $this->api->get_userById($to_user);
                $chat_messages = $this->api->get_all_chat_messagesByUserId($from_id,$to_user_id);
                // print_r($chat_messages); exit;
                $result = array(
                    'from_user'     => $from_user,
                    '$to_user'      =>  $to_user,
                    'chat_messages' => $chat_messages
                );
                $response['status_code'] = 1;
                $response['message'] = $this->success;
                $response['data'] = $result;
            }elseif($call_type == 'group')
            {
                if($inputs['group_id'] !='')
                {
                    $all_members =array();
                    $all_chats =array();
                    $group_id = $inputs['group_id'];
                    $get_group_members = $this->api->get_group_members($group_id);
                    $group_chat_message = $this->api->get_group_message($group_id);
                    // print_r($group_chat_message); exit;
                    foreach ($get_group_members as $members) {
                        $member_details = $this->api->get_userById($members['login_id']);
                        $all_members [] = $member_details;
                    }
                    foreach($group_chat_message as $group_chat_msg)
                    {
                        if(($group_chat_msg['from_id'] != 0) && ($group_chat_msg['to_id'] != 0))
                        {
                            $all_chats[] = $group_chat_msg;
                        }
                    }
                    $result = array(
                        'all_members' => $all_members,
                        'chat_messages' => $all_chats
                    );
                }else{
                    $response['status_code'] = 0;
                    $response['message'] = $this->required_input;
                }
                $response['status_code'] = 1;
                $response['message'] = $this->success;
                $response['data'] = $result;
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{
            $this->token_error();
        }
    }



public function one_to_one_chat_messages_post()
    {
        if($this->is_valid == TRUE)   
        {
            $inputs = $this->post();
            $res = $this->api->get_role_and_userid($this->token);
            $from_user = $this->api->get_userById($res['user_id']);
            $call_type = $inputs['call_type'];
            $all_chats = array();
            $last_msg = array();
            $all_ids = array();
            $all_chat = array();
            if($call_type == 'one')
            {
                $chat_details = $this->api->get_all_chat_detailsByUserId($from_user['user_id']);
                // print_r($chat_details); exit; 
                foreach($chat_details as $chat_detail){
                    
                    if(count($all_chat) != 0)
                    {
                        if($chat_detail['from_id'] == $from_user['user_id'])
                        {
                            if(!in_array($chat_detail['to_id'], $all_chat)){
                                $all_chat[] = $chat_detail['to_id'];
                                $one_chat = $this->api->get_userById($chat_detail['to_id']);
                                $user = array(
                                'fullname' =>$one_chat['fullname'],
                                'user_id'  =>$one_chat['user_id'],
                                'profile_image'  =>base_url().'assets/uploads/'.$one_chat['avatar']
                                );
                                $all_chats[] = $user;
                            }
                        }
                        if($chat_detail['to_id'] == $from_user['user_id'])
                        {
                            if(!in_array($chat_detail['from_id'], $all_chat)){
                                $all_chat[] = $chat_detail['from_id'];
                                $one_chat = $this->api->get_userById($chat_detail['from_id']);
                                $user = array(
                                'fullname' =>$one_chat['fullname'],
                                'user_id'  =>$one_chat['user_id'],
                                'profile_image'  =>base_url().'assets/uploads/'.$one_chat['avatar']
                                );
                                $all_chats[] = $user;
                            }
                        }
                        
                    }else{
                        if($chat_detail['from_id'] == $from_user['user_id'])
                        {
                            $all_chat[] = $chat_detail['to_id'];
                            $one_chat = $this->api->get_userById($chat_detail['to_id']);
                        }
                        if($chat_detail['to_id'] == $from_user['user_id'])
                        {
                            $all_chat[] = $chat_detail['from_id'];
                            $one_chat = $this->api->get_userById($chat_detail['from_id']);
                        }
                            $user = array(
                            'fullname' =>$one_chat['fullname'],
                            'user_id'  =>$one_chat['user_id'],
                            'profile_image'  =>base_url().'assets/uploads/'.$one_chat['avatar']
                        );
                        $all_chats[] = $user;
                    }
                    if(count($all_ids) != 0){
                        if(!in_array($one_chat['user_id'], $all_ids)){
                            $all_ids[] = $one_chat['user_id'];
                            $c = array(
                                'user_id' => $one_chat['user_id'],
                                'last_message'      => $chat_detail['message'],
                                'msg_time'  => $chat_detail['msg_date']
                            );
                            $last_msg[] = $c;
                        }
                    }else{
                        $all_ids[] = $one_chat['user_id']; 
                        $c = array(
                                'user_id' => $one_chat['user_id'],
                                'last_message'      => $chat_detail['message'],
                                'msg_time'  => $chat_detail['msg_date']
                        );
                        $last_msg[] = $c;
                    }
                    
                }
                $result = array(
                    'all_users' => $all_chats,
                    'all_chats' => $last_msg
                );
                if($result != '')
                {                    
                    $response['status_code'] = 1;
                    $response['message'] = $this->success;
                    $response['data'] = $result;
                }else{
                    $response['status_code'] = 0;
                    $response['message'] = $this->no_result_found;
                }
            }elseif($call_type == 'group')
            {
                $all_group_chat =array();
                $all_group_chat_msg =array();
                $group_members = $this->api->get_all_groupmembers($from_user['user_id']);
                foreach($group_members as $group_member)
                {
                    $group_name = $this->api->get_groupname($group_member['group_id']);
                    $last_msg = $this->api->get_last_msg($group_name['group_id']);
                    $last_msg_user = $this->api->get_userById($last_msg['from_id']);
                    if(!empty($last_msg_user)){
                        $last_user_msg_detail = array(
                            'last_chat_user' => $last_msg_user['fullname']?$last_msg_user['fullname']:'-',
                            'last_chat_user_id' => $last_msg_user['user_id']?$last_msg_user['user_id']:'-',
                            'last_chat_user_image' => $last_msg_user['avatar']?base_url().'assets/uploads/'.$last_msg_user['avatar']:'-',
                            'last_chat_msg' => $last_msg['message']?$last_msg['message']:'-'
                        );
                    }else{
                        $last_user_msg_detail = '';
                    }
                    $all_group_chat[] =$group_name;
                    if(!empty($last_user_msg_detail)){
                        $all_group_chat_msg[] =$last_user_msg_detail; 
                    }else{
                        $all_group_chat_msg = '';
                    }
                    $group_detail = array(
                        'group_name' => $group_name['group_name'],
                        'group_id'   => $group_name['group_id']
                    );
                }
                $result = array(
                    'group_names' => $all_group_chat?$all_group_chat:'0',
                    'group_chat_last_msg' => $all_group_chat_msg?$all_group_chat_msg:'0'
                );
                if($result != '')
                {                    
                    $response['status_code'] = 1;
                    $response['message'] = $this->success;
                    $response['data'] = $result;
                }else{
                    $response['status_code'] = 0;
                    $response['message'] = $this->no_result_found;
                }
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{
            $this->token_error();
        }
    }




}
?>
