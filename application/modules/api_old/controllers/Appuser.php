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
                        $response['message'] = 'Success';
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

            if(!empty($inputs['username']) && !empty($inputs['password'])&& !empty($inputs['email'])&& !empty($inputs['fullname'])  && !empty($inputs['phone'])){

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

            $inputs = $this->post();

            if(!empty($inputs['date']) && !empty($inputs['time']) && !empty($inputs['latitude']) && !empty($inputs['longitude']) ){

                $date = str_replace('/', '-', $inputs['date']);
                $time=strtotime($date);
                $day=date("d",$time) - 1;
                $month=date("m",$time);
                $month = ltrim($month, '0');
                $year=date("Y",$time);
                $client_datas = $this->user->get_attendance_data($this->token,$inputs,$month,$year);
                $client_data = unserialize($client_datas['month_days']);
                $client_data[$day]['day'] = 1;
                $client_data[$day]['punch_in'] = $inputs['time'];
                $client_data[$day]['punch_out'] = '';
                $client_data[$day]['in_lat'] = $inputs['latitude'];
                $client_data[$day]['in_long'] = $inputs['longitude'];
                $result_client = array(
                    'month_days' => serialize($client_data)
                );
                $req = $this->user->update_attendance_data($client_datas['user_id'],$result_client,$month,$year);
                if($req == 1){

                $result = array(
                    'punch_in' => $inputs['time']
                );
                $response['status_code'] = 1;
                $response['message'] = 'SUCCESS';
                $response['data'] = $result;
                }else{
                    $response['status_code'] = 1;
                    $response['message'] = 'Something went wrong, please try again later.';
                }
            }else{
                $response['status_code'] = 0;
                $response['message'] = 'Required inputs missing';
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

            $inputs = $this->post();

            if(!empty($inputs['date']) && !empty($inputs['time'])  && !empty($inputs['latitude']) && !empty($inputs['longitude'])){
                $date = str_replace('/', '-', $inputs['date']);
                $time=strtotime($date);
                $day=date("d",$time) - 1;
                $month=date("m",$time);
                $month = ltrim($month, '0');
                $year=date("Y",$time);

                $client_datas = $this->user->get_attendance_data($this->token,$inputs,$month,$year);

                $client_data = unserialize($client_datas['month_days']);
                $time1 = date_create($date.' '.$client_data[$day]['punch_in']);
                $time2 = date_create($date.' '.$inputs['time']);

                $hours = date_diff($time2,$time1);
                $hours = $hours->format("%h.%i");
                // $time1 = strtotime($client_data[$day]['punch_in']);
                // $time2 = strtotime($inputs['time']);
                // $time2 = date('H:i',$time2);
                // $hours = round(abs(strtotime($time2) - $time1) / 3600,2);

                $client_data[$day]['day'] = 1;
                $client_data[$day]['punch_out'] = $inputs['time'];
                $client_data[$day]['out_lat'] = $inputs['latitude'];
                $client_data[$day]['out_long'] = $inputs['longitude'];
                $client_data[$day]['hours'] = $hours;
                $result_client = array(
                    'month_days' => serialize($client_data)
                );
                $req = $this->user->update_attendance_data($client_datas['user_id'],$result_client,$month,$year);
                if($req == TRUE){

                $result = array(
                    'punch_out' => $inputs['time'],
                    'hours'     => $hours
                );
                $response['status_code'] = 1;
                $response['message'] = 'SUCCESS';
                $response['data'] = $result;
            }else{
                $response['status_code'] = 1;
                $response['message'] = 'Something went wrong, please try again later.';
            }

            }else{
                $response['status_code'] = 0;
                $response['message'] = 'Required inputs missing';
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
                // print_r($client_data[$day]); exit; 


                $result['punch_in']=(!empty($client_data[$day]['punch_in'])?$client_data[$day]['punch_in']:"0");
                $result['punch_out']=(!empty($client_data[$day]['punch_out'])?$client_data[$day]['punch_out']:"0");
                $result['hours'] = (!empty($client_data[$day]['hours'])?$client_data[$day]['hours']:"0");
                     
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
            'color_code' => '#3A57C4'
        );
        $response['status_code'] = 1;
        $response['message'] = 'SUCCESS';
        $response['data'] = $result;
        $this->response($response, REST_Controller::HTTP_OK);
    }


}
?>
