<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Appuser extends REST_Controller  {

    function __construct()
    {
        parent::__construct();
        $this->load->model('user_model','user');
        $header =  getallheaders(); // Get Header Data

        $token  = '';
        if(!empty($header['token'])){ $token = $header['token'];        }
        
        if(empty($token)){ 
            if(!empty($header['Token'])){ $token = $header['Token']; }
        }
        if (empty($token)) {
            $this->is_valid = FALSE;    
        }if(!empty($token)){
            $this->token = $token;
            $valid = $this->user->is_valid_token($token);

            if($valid){
                $this->is_valid = TRUE;    
            }else{
                $this->is_valid = FALSE;
            }
            
        }

    }
  
    public function login_post()
    {

       if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

            if(!empty($inputs['username']) && !empty($inputs['password']) && !empty($inputs['device_id'])){

                $result = $this->user->is_valid_login($inputs); 

                  if(!empty($result)){
                    $check_device = $this->user->check_device($result['id']);
                        if($check_device != '')
                        {
                            $dev_res = $this->user->device_update($check_device['dev_id'],$inputs['device_id'],'update');
                        }else{
                            $dev_res = $this->user->device_update($result['id'],$inputs['device_id'],'insert');
                        }
                        $response['status_code'] = 1;
                        $response['message'] = 'SUCCESS';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid login credential';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }

    }

     public function create_employee_post()
    {

       if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

            if(!empty($inputs['username']) && !empty($inputs['password'])&& !empty($inputs['email'])&& !empty($inputs['fullname'])  && !empty($inputs['phone']) && !empty($inputs['emp_doj'])&& !empty($inputs['reporting_to'])){

                 $result = $this->user->create_user($inputs,$this->token);    

                  if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }elseif($result==2){
                        $response['message'] = 'Employee username or email already exists...';
                   }else{
                        $response['message'] = 'Invalid employee details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }

    }


    public function create_project_post()
    {

       if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            if(!empty($inputs['project_code']) && !empty($inputs['project_title'])&& !empty($inputs['client'])&& !empty($inputs['assign_lead'])  && !empty($inputs['assign_to'])  && !empty($inputs['start_date'])  && !empty($inputs['due_date']) && !empty($inputs['estimate_hours'])  && !empty($inputs['description'])){

                 
                

                 $result = $this->user->create_project($inputs,$this->token);    

                  if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }elseif($result==2){
                        $response['message'] = 'company not set';
                   }else{
                        $response['message'] = 'Invalid project details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }

    }


     public function edit_project_post()
    {

       if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];


            if(!empty($inputs['project_code']) && !empty($inputs['project_title'])&& !empty($inputs['client'])&& !empty($inputs['assign_lead'])  && !empty($inputs['assign_to'])  && !empty($inputs['start_date'])  && !empty($inputs['due_date']) && !empty($inputs['estimate_hours'])  && !empty($inputs['description'])){

                 
                

                 $result = $this->user->edit_project($inputs,$this->token);    

                  if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }elseif($result==2){
                        $response['message'] = 'company not set';
                   }else{
                        $response['message'] = 'Invalid project details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }

    }

   public function delete_project_post()
    {
        if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            $result = $this->user->delete_project($inputs,$this->token);  

               if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }elseif($result==2){
                        $response['message'] = 'Permission Denied';
                   }else{
                        $response['message'] = 'Invalid project details';
                   }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }
    }

    public function task_view_post()
    {
        if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            if(!empty($inputs['task']) && !empty($inputs['project'])){

                 
                

                 $result = $this->user->task_view($inputs,$this->token);    

                  if($result){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid task details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }
    }


    public function create_task_post()
    {
        if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            if(!empty($inputs['task_name']) && !empty($inputs['project'])){

                 
                

                 $result = $this->user->create_task($inputs,$this->token);    

                  if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid task details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }
    }






//     public function delete_project_post()
//     {
//         if($this->is_valid == TRUE)   {

//             $data = array();
//             $response = array();
//             $response['status_code'] = -1;
//             $response['message'] = 'Required input missing';
//             $response['data'] = $data;

//             $inputs = $this->post();

// //             echo $inputs['assign_lead'];

// // exit;

//             $result = $this->user->delete_project($inputs,$this->token);  

//                if($result==1){
//                         $response['status_code'] = 1;
//                         $response['message'] = 'Success';
//                         $response['data'] = $result;
//                    }elseif($result==2){
//                         $response['message'] = 'Permission Denied';
//                    }else{
//                         $response['message'] = 'Invalid project details';
//                    }
//             $this->response($response, REST_Controller::HTTP_OK);
//         }else{

//             $this->token_error();
//         }
//     }


    public function assign_user_post()
    {
        if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            if(!empty($inputs['project']) && !empty($inputs['task']) && !empty($inputs['type'])){

                 
                

                 $result = $this->user->assign_user($inputs,$this->token);    

                  if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid task details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }
    }


    public function edit_task_post()
    {
        if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            if(!empty($inputs['task_name']) && !empty($inputs['assigned_to'])){

                 
                

                 $result = $this->user->edit_task($inputs,$this->token);    

                  if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid task details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }
    }


    public function delete_task_post()
    {
        if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            $result = $this->user->delete_task($inputs,$this->token);  

               if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid task details';
                   }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }
    }

    public function task_completion_post()
    {
        if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            $result = $this->user->task_completion($inputs,$this->token);  

               if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid task details';
                   }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }
    }


    public function create_client_post()
    {

       if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            if(!empty($inputs['company_name']) && !empty($inputs['company_email'])){

                 
                

                 $result = $this->user->create_client($inputs,$this->token);    

                  if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid client details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }

    }


    public function edit_client_post()
    {

       if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            if(!empty($inputs['company_name']) && !empty($inputs['company_email'])){

                 
                

                 $result = $this->user->edit_client($inputs,$this->token);    

                  if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid client details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }

    }


    public function delete_client_post()
    {
        if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            $result = $this->user->delete_client($inputs,$this->token);  

               if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid task details';
                   }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }
    }


    public function create_invoice_post()
    {

       if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;
            


            if(!empty($inputs['reference_no']) && !empty($inputs['client'])){

                 
                

                 $result = $this->user->create_invoice($inputs,$this->token); 

                    

                  if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid invoice details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }

    }


    public function edit_invoice_post()
    {

       if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            if(!empty($inputs['reference_no']) && !empty($inputs['client'])){

                 
                

                 $result = $this->user->edit_invoice($inputs,$this->token);    

                  if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid invoice details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }

    }


    public function delete_invoice_post()
    {
        if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            $result = $this->user->delete_invoice($inputs,$this->token);  

               if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid invoice details';
                   }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }
    }


    public function create_estimate_post()
    {

       if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            if(!empty($inputs['reference_no']) && !empty($inputs['client'])){

                 
                

                 $result = $this->user->create_estimate($inputs,$this->token);    

                  if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid estimate details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }

    }


    public function edit_estimate_post()
    {

       if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            if(!empty($inputs['reference_no']) && !empty($inputs['client'])){

                 
                

                 $result = $this->user->edit_estimate($inputs,$this->token);    

                  if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid estimate details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }

    }


    public function delete_estimate_post()
    {
        if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            $result = $this->user->delete_estimate($inputs,$this->token);  

               if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid estimate details';
                   }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }
    }



    public function create_expense_post()
    {

       if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            if(!empty($inputs['amount']) && !empty($inputs['category'])){

                 
                

                 $result = $this->user->create_expense($inputs,$this->token);    

                  if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }elseif($result==2){
                        $response['status_code'] = -1;
                        $response['message'] = 'File Upload failed';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid expense details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }

    }


    public function edit_expense_post()
    {

       if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            if(!empty($inputs['amount']) && !empty($inputs['category'])){

                 
                

                 $result = $this->user->edit_expense($inputs,$this->token);    

                  if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid expense details';
                   }
            }else{
                $response['message'] = 'Required input missing';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }

    }


    public function delete_expense_post()
    {
        if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

//             echo $inputs['assign_lead'];

// exit;

            $result = $this->user->delete_expense($inputs,$this->token);  

               if($result==1){
                        $response['status_code'] = 1;
                        $response['message'] = 'Success';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'Invalid expense details';
                   }
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }
    }





    

     public function countries_get()
    {

       if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

             $result = $this->user->countries();    
              if(!empty($result)){
                        $response['status_code'] = 1;
                        $response['message'] = 'SUCCESS';
                        $response['data'] = $result;
                   }else{
                        $response['message'] = 'No details found';
                   }
            
            $this->response($response, REST_Controller::HTTP_OK);
        }else{

            $this->token_error();
        }

    }

    public function forget_password_post()
    {
    		
    }

    public function token_error(){
        $this->response([
                'code' => 498,
                'status' => FALSE,
                'message' => 'Invalid token or Token missing'
            ], REST_Controller::HTTP_OK);
    }

    public function punch_in_post()
    {
        if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

                 $strtotime = strtotime(date('Y-m-d H:i'));
                  $a_year    = date('Y',$strtotime);
                  $a_month   = date('m',$strtotime);
                  $a_day     = date('d',$strtotime);
                  $a_cin     = date('H:i',$strtotime); 

                   $record = $this->user->get_attendance_data($this->token);

                                  
                
                if($record){

                $result = array(
                    'punch_in' => $a_cin
                );
                $response['status_code'] = 1;
                $response['message'] = 'SUCCESS';
                $response['data'] = $result;
                }else{
                    $response['status_code'] = 1;
                    $response['message'] = 'Something went wrong, please try again later.';
                }
           
        $this->response($response, REST_Controller::HTTP_OK);
        }else{
            $this->token_error();
        }
    }

    public function punch_out_post()
    {
        if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

                  $strtotime = strtotime(date('Y-m-d H:i'));
                  $a_year    = date('Y',$strtotime);
                  $a_month   = date('m',$strtotime);
                  $a_day     = date('d',$strtotime);
                  $a_cout     = date('H:i',$strtotime); 

                   $record = $this->user->get_punchout_attendance_data($this->token);

                                  
                
                if($record){

                $result = array(
                    'punch_out' => $a_cout
                );
                $response['status_code'] = 1;
                $response['message'] = 'SUCCESS';
                $response['data'] = $result;
                }else{
                    $response['status_code'] = 1;
                    $response['message'] = 'Something went wrong, please try again later.';
                }
           
        $this->response($response, REST_Controller::HTTP_OK);
        }else{
            $this->token_error();
        }
    }

    public function user_punch_in_details_post()
    {
        if($this->is_valid == TRUE)   {

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = 'Required input missing';
            $response['data'] = $data;

            $inputs = $this->post();

            if(!empty($inputs['date']) && !empty($inputs['time'])){
                $date = str_replace('/', '-', $inputs['date']);
                $time=strtotime($date);
                $day=date("d",$time) - 1;
                $month=date("m",$time);
                $month = ltrim($month, '0');
                $year=date("Y",$time);

                $client_datas = $this->user->get_attendance_data($this->token,$inputs,$month,$year);

                $client_data = unserialize($client_datas['month_days']);
               


                $result['punch_in']=(!empty($client_data[$day]['punch_in'])?$client_data[$day]['punch_in']:"0");
                $result['punch_out']=(!empty($client_data[$day]['punch_out'])?$client_data[$day]['punch_out']:"0");
                     
                     
                $response['status_code'] = 1;
                $response['message'] = 'SUCCESS';
                $response['data'] = $result;


            }else{
                $response['status_code'] = 0;
                $response['message'] = 'Required inputs missing';
            }
        $this->response($response, REST_Controller::HTTP_OK);
        }else{
            $this->token_error();
        }
    }

    public function user_profilepic_upload_post()
    {
        if($this->is_valid == TRUE)   {
            $uploaddir = 'assets/uploads/';
            $file_name = time().underscore($_FILES['file']['name']);
            $uploadfile = $uploaddir.$file_name;
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                $result = base_url().$uploadfile;
                $user_details = $this->user->get_role_and_userid($this->token);
                // print_r($user_details); exit;
                $this->user->upload_profilepic($user_details['user_id'],$file_name);
                $response['status_code'] = 1;
                $response['message'] = 'SUCCESS';
                $response['data'] = $result;
             } else {
                $response['status_code'] = 0;
                $response['message'] = 'Image Upload Error.';       
             }
              $this->response($response, REST_Controller::HTTP_OK);
        }else{
            $this->token_error();
        }
    }

    public function app_colorcode_post()
   {
       $result = array(
           'primary_color'     =>  '#44bbec',
           'secondry_color'    =>  '#0163fc',
           'black_logo'        =>  base_url().'assets/images/issam_logo.png',
           'full_white_logo'   =>  base_url().'assets/images/issam_logo.png',
           'white_logo'        =>  base_url().'assets/images/issam_logo.png'
       );
       $response['status_code'] = 1;
       $response['message'] = 'SUCCESS';
       $response['data'] = $result;
       $this->response($response, REST_Controller::HTTP_OK);
   }


}
?>
