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
          $this->load->helper('string');
        $this->load->helper('fopdf/invoicer');   
        
        $this->load->library('applib');  
    
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

         $this->apiKey = '46235992';
        $this->apiSecret = 'b51abe55806193de7e23a09ed41404fdd5c99f4a';

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

public function basic_info_post(){

   if($this->is_valid == TRUE)   {

    $data = array();
    $response = array();
    $response['status_code'] = -1;
    $response['message'] = $this->no_result_found;
    $response['data'] = $data;

    $inputs = $this->post();

    if(!empty($inputs['fullname']) && !empty($inputs['dob'])&& !empty($inputs['gender'])&& !empty($inputs['address'])&& !empty($inputs['state'])&& !empty($inputs['country'])&& !empty($inputs['pincode'])&& !empty($inputs['phone'])){

        $result = $this->api->basic_info_update($this->token,$inputs);    

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

public function personal_info_post(){

   if($this->is_valid == TRUE)   {

    $data = array();
    $response = array();
    $response['status_code'] = -1;
    $response['message'] = $this->no_result_found;
    $response['data'] = $data;

    $inputs = $this->post();

    if(!empty($inputs['passport_no']) && !empty($inputs['passport_expiry'])&& !empty($inputs['tel_number'])&& !empty($inputs['nationality'])){

        $result = $this->api->personal_info_update($this->token,$inputs);    

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

public function emergency_info_post(){

   if($this->is_valid == TRUE)   {

    $data = array();
    $response = array();
    $response['status_code'] = -1;
    $response['message'] = $this->no_result_found;
    $response['data'] = $data;

    $inputs = $this->post();

    if(!empty($inputs['contact_name1']) && !empty($inputs['relationship1'])&& !empty($inputs['contact1_phone1'])&& !empty($inputs['contact_name2'])&& !empty($inputs['relationship2'])&& !empty($inputs['contact2_phone1'])){

        $result = $this->api->emergency_info_update($this->token,$inputs);    

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

public function bank_info_post(){

   if($this->is_valid == TRUE)   {

    $data = array();
    $response = array();
    $response['status_code'] = -1;
    $response['message'] = $this->no_result_found;
    $response['data'] = $data;

    $inputs = $this->post();

    if(!empty($inputs['bank_name']) && !empty($inputs['bank_ac_no'])&& !empty($inputs['ifsc_code'])&& !empty($inputs['pan_no'])){

        $result = $this->api->bank_info_update($this->token,$inputs);    

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

public function family_info_post(){

   if($this->is_valid == TRUE)   {

    $data = array();
    $response = array();
    $response['status_code'] = -1;
    $response['message'] = $this->no_result_found;
    $response['data'] = $data;

    $inputs = $this->post();

    if(!empty($inputs['member_name']) && !empty($inputs['member_relationship'])&& !empty($inputs['member_dob'])&& !empty($inputs['member_phone'])){

        $result = $this->api->family_info_update($this->token,$inputs);    

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

public function education_info_post(){

   if($this->is_valid == TRUE)   {

    $data = array();
    $response = array();
    $response['status_code'] = -1;
    $response['message'] = $this->no_result_found;
    $response['data'] = $data;

    $inputs = $this->post();

    if(!empty($inputs['institute']) && !empty($inputs['subject'])&& !empty($inputs['start_date'])&& !empty($inputs['end_date'])&& !empty($inputs['degree'])&& !empty($inputs['grade'])){

        $result = $this->api->education_info_update($this->token,$inputs);    

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


public function experience_info_post(){

   if($this->is_valid == TRUE)   {

    $data = array();
    $response = array();
    $response['status_code'] = -1;
    $response['message'] = $this->no_result_found;
    $response['data'] = $data;

    $inputs = $this->post();

    if(!empty($inputs['company_name']) && !empty($inputs['location'])&& !empty($inputs['job_position'])&& !empty($inputs['period_from'])&& !empty($inputs['period_to'])){

        $result = $this->api->experience_info_update($this->token,$inputs);    

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


public function projects_details_get(){

   if($this->is_valid == TRUE)   {

    $data = array();

    $data['projectcode']= config_item('project_prefix').''.random_string('nozero', 5);

     if(config_item('increment_invoice_number') == 'FALSE'){
                                    

            $invoice_no= random_string('nozero', 6);

        }else{

            $invoice_no=$this->generate_invoice_number();

        }

    $data['invoiceno']=config_item('invoice_prefix').''.$invoice_no;
    $data['estimateno']=config_item('estimate_prefix').''.$this->generate_estimate_number();

    $data['saved_item']=$this->db->get('items_saved')->result();

    $data['items']=$this->db->group_by('item_name')->get('items')->result();



    $client=$this->db->where(array('is_lead' => 0,'co_id >'=> 0))->order_by('company_name','ASC')->get('companies')->result_array();
    if(!empty($client))
    {
        foreach ($client as $rows) 
        {
           $a['value']=$rows['co_id'];
           $a['label']=$rows['company_name'];

           $clients[]=$a;
        }

        $data['clients']=$clients;
    }

            
            $this->db->select('AD.fullname,U.id');
            $this->db->from('dgt_users U');
            $this->db->join('dgt_account_details AD', 'AD.user_id = U.id', 'LEFT');
            $lead=$this->db->where(array('U.role_id !='=>2,'U.role_id !='=>1))->get()->result_array();

     if(!empty($lead))
     {
         foreach ($lead as $lrows) 
        {

           $b['value']=$lrows['id'];
           $b['label']=$lrows['fullname'];

           $leads[]=$b;
        }

          $data['leads']=$leads;
     }


      $expense_categories=$this->db->where(array('module' => 'expenses'))->get('categories')->result_array();
    if(!empty($expense_categories))
    {
        foreach ($expense_categories as $erows) 
        {
           $c['value']=$erows['id'];
           $c['label']=$erows['cat_name'];

           $expense_category[]=$c;
        }

        $data['expense_categories']=$expense_category;
    }


    $project=$this->db->get('projects')->result_array();
    if(!empty($project))
    {
        foreach ($project as $prows) 
        {
           $d['value']=$prows['project_id'];
           $d['label']=$prows['project_title'];

           $projects[]=$d;
        }

        $data['projects']=$projects;
    }

    
    $response = array();
    $response['status_code'] = -1;
    $response['message'] = $this->no_result_found;
    $response['data'] = $data;
    $result = $data;

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

private function generate_invoice_number()
    {
        $dbPrefix = $this->db->dbprefix;
        $query = $this->db->query('SELECT reference_no, inv_id FROM '.$dbPrefix.'invoices WHERE inv_id = (SELECT MAX(inv_id) FROM '.$dbPrefix.'invoices)');

        // $query = self::$db->select('reference_no')->select_max('inv_id')->get('invoices');
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $ref_number = intval(substr($row->reference_no, -4));
            $next_number = $ref_number + 1;
            if ($next_number < config_item('invoice_start_no')) {
                $next_number = config_item('invoice_start_no');
            }
            $next_number = $this->invoice_ref_exists($next_number);

            return sprintf('%04d', $next_number);
        } else {
            return sprintf('%04d', config_item('invoice_start_no'));
        }
    }

    private function invoice_ref_exists($next_number)
    {
        $next_number = sprintf('%04d', $next_number);

        $records = $this->db->where('reference_no', config_item('invoice_prefix').$next_number)
            ->get('invoices')->num_rows();
        if ($records > 0) {
            return $this->invoice_ref_exists($next_number + 1);
        } else {
            return $next_number;
        }
    }

    private function generate_estimate_number() {
        $dbPrefix = $this->db->dbprefix;
        $query = $this->db->query('SELECT reference_no, est_id FROM '.$dbPrefix.'estimates WHERE est_id = (SELECT MAX(est_id) FROM '.$dbPrefix.'estimates)');

        // $query = self::$db->select('reference_no')->select_max('est_id')->get('estimates');
        if ($query->num_rows() > 0)
        {
            $row = $query->row();
            $ref_number = intval(substr($row->reference_no, -4));
            $next_number = $ref_number + 1;
            if ($next_number < config_item('estimate_start_no')) { $next_number = config_item('estimate_start_no'); }
            $next_number = $this->estimate_ref_exists($next_number);
            return sprintf('%04d', $next_number);
        }else{
            return sprintf('%04d', config_item('estimate_start_no'));
        }
    }

    private function estimate_ref_exists($next_number){
        $next_number = sprintf('%04d', $next_number);

        $records = $this->db->where('reference_no',config_item('estimate_prefix').$next_number)
                            ->get('estimates')->num_rows();
        if ($records > 0) {
            return $this->estimate_ref_exists($next_number + 1);
        }else{
            return $next_number;
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

       

        $designation = $this->api->designations($this->token,$inputs['dept_id']);

        $i=0;
       

        $designations=array();
        foreach ($designation as $rows) 
        {
               $designations[$i]['designation_id']=$rows->id;
               $designations[$i]['designation']=$rows->designation;
               $i++;
        } 

 
        $teamleads = $this->GetAllTeamleadsByDeptId($inputs['dept_id']);
        $all_teamleads = array();

         $j=0;

      foreach ($teamleads as $teamlead) {

           $lead_name = $this->GetTeamleadNameById($teamlead->id);

           $all_teamleads[$j]['lead_id']=$lead_name->user_id;
           $all_teamleads[$j]['lead_name']=$lead_name->fullname;

          $j++;

       
        }

        
       $result = array('designations' =>$designations,'teamlead'=>$all_teamleads);



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

public function reporting_officer_post()
{
     $result = array();
    $response = array();
    $response['status_code'] = -1;
    $response['message'] = $this->required_input;
    $response['data'] = $result;

    $inputs = $this->post();
    if(!empty($inputs['dept_id']) && !empty($inputs['des_id'])){
       
        $des_id = $inputs['des_id'];
        $dept_id = $inputs['dept_id'];
      
       $r = $this->db->get_where('designation',array('id'=>$des_id))->row_array();
        $grade = $r['grade'];
        $grade_details = $this->db->get_where('grades',array('grade_id'=>$grade))->row_array();
        $all_grades = $this->db->get_where('designation',array('grade <='=>$grade))->result_array();
        $all_users = array();
        foreach ($all_grades as $grades) {
            // $this->db->group_by('designation_id');
            $user_details = $this->db->select('*')
                                     ->from('users U')
                                     ->join('account_details AD','U.id = AD.user_id')
                                     ->where('U.designation_id',$grades['id'])
                                     ->where('U.role_id',3)
                                     ->where('U.activated',1)
                                     ->where('U.banned',0)
                                     ->get()->result_array();
            // $user_details = $this->db->get_where('users',array('designation_id'=>$grades['id'],'role_id'=>3,'activated'=>1,'banned'=>0))->result_array();
            foreach($user_details as $users)
            {
                if(count($user_details) != 0)
                {

                $user = array(
                    'id' => $users['user_id'],
                    'username' => $users['fullname']
                );
                $all_users[] = $user;
                }
            }
        }
       
        
       $result = array('ro'=>$all_users);



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


public function GetAllTeamleadsByDeptId($dept_id)
   {
       return $this->db->where(array('activated'=>1,'role_id'=>3,'department_id'=>$dept_id,'is_teamlead'=>'yes'))->get('users')->result();
   }

   public function GetTeamleadNameById($user_id)
   {
       return $this->db->select('user_id,fullname')->where(array('user_id'=>$user_id))->get('account_details')->row();
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
    $team_next_page='';
$team_page='';
$team_result_list='';

    $user_details = $this->user->get_role_and_userid($this->token);
    $check_teamlead = $this->db->get_where('dgt_users',array('id'=>$user_details['user_id']))->row_array(); 
    if($check_teamlead['is_teamlead'] == 'yes')
    {
        $team_result_count = $this->api->team_leaves($this->token,$inputs,1);    
        $team_result_list = $this->api->team_leaves($this->token,$inputs,2);  
        $team_page = !empty($inputs['page'])?$inputs['page']:1;
        $team_result_count = ceil($team_result_count/$inputs['limit']);
        $team_next_page    = $team_page + 1;
        $team_next_page    = ($team_next_page <=$team_result_count)?$team_next_page:-1;
    }

    $result_count = $this->api->leaves($this->token,$inputs,1);    
    $result_list = $this->api->leaves($this->token,$inputs,2);  
    $page = !empty($inputs['page'])?$inputs['page']:1;
    $result_count = ceil($result_count/$inputs['limit']);
    $next_page    = $page + 1;
    $next_page    = ($next_page <=$result_count)?$next_page:-1;


    $result = array('next_page'=>$next_page,'current_page'=>$page,'list'=>$result_list,'team_next_page'=>$team_next_page,'team_current_page'=>$team_page,'team_list'=>$team_result_list,'is_teamlead'=>$check_teamlead['is_teamlead']);
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
        $this->db->insert("dgt_attendance_details",$insert);

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
    if($res['role_id'] == 1 || $res['role_id'] == 4)
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
        if($res['role_id'] == 1 || $res['role_id'] == 4)
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
                $this->db->insert('dgt_salary',$net);
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
                    $this->db->insert('dgt_payslip',$result);
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
            if($res['role_id'] == 1 || $res['role_id'] == 4)
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
            if($res['role_id'] == 1 || $res['role_id'] == 4)
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
            // if($res['role_id'] == 1 || $res['role_id'] == 4)
            // {
                // $results = $this->api->all_users($res['user_id']);
            $inputs = $this->post();
            if($inputs['page'] == 'salary')
            {   
                $results = $this->api->all_users($res['user_id'],$inputs['page']);
            }else{
                $results = $this->api->all_users($res['user_id'],'page');
            }
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
            $empty_array=array();
            $res = $this->api->get_role_and_userid($this->token);
            if($res['role_id'] == 1 || $res['role_id'] == 4)
            {

                $inputs = $this->post();
                $inputs['limit'] = 10;
                $result_count = $this->api->project_list($this->token,$inputs,1);    
                $result_list = $this->api->project_list($this->token,$inputs,2);  

                $page = !empty($inputs['page'])?$inputs['page']:1;
                $result_count = ceil($result_count/$inputs['limit']);
                $next_page    = $page + 1;
                $next_page    = ($next_page <=$result_count)?$next_page:-1;
               // $results = $this->api->over_all_projects();
                $projects =array();
                $i=0;
                
                foreach ($result_list as $result) {
                    $projects[$i]['project_id'] = $result['project_id'];
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
                    $client_name=$this->api->get_clientById($result['client']);
                    $project_members = $this->api->get_user_detail('members',$result['assign_to']);
                    $projects[$i]['overviews'] = array(
                        'project_id' => $result['project_id'],
                        'project_code' => $result['project_code'],
                        'project_title' => $result['project_title'],
                        'deadline' => $result['due_date'],
                        'project_created' => $result['date_created'],
                        'description' => $result['description'],
                        'progress' => $result['progress'],
                        'fixed_rate' => $result['fixed_rate'],
                        'hourly_rate' => $result['hourly_rate'],
                        'fixed_price' => $result['fixed_price'],
                        'client' => $result['client'],
                        'client_name' => $client_name['company_name'],
                        'leadid' => $result['assign_lead'],
                        'project_lead_name' => !empty($project_lead['fullname'])?$project_lead['fullname']:'',
                        'project_lead_photo' => base_url().'assets/uploads/'.$project_lead['avatar'],
                        'project_team_members' => !empty($project_members)?$project_members:$empty_array,
                    ); 
                    $i++;
                }
                 $result = array('next_page'=>$next_page,'current_page'=>$page,'list'=>$projects);
                if(!empty($projects)){
                        $response['status_code'] = 1;
                        $response['message'] = $this->success;
                        $response['data'] = $result;
                    }else{
                        $response['status_code'] = 0;
                        $response['message'] = $this->no_result_found;
                    }
                 $this->response($response, REST_Controller::HTTP_OK);
            }else{

                 $inputs = $this->post();
                 // print_r($inputs); exit;
                $inputs['limit'] = 10;
                $result_count = $this->api->project_listByUserId($this->token,$inputs,1,$res['user_id']);  
                $result_list = $this->api->project_listByUserId($this->token,$inputs,2,$res['user_id']);    
                // print_r($result_list); exit;  
                $page = !empty($inputs['page'])?$inputs['page']:1;
                $result_count = ceil($result_count/$inputs['limit']);
                $next_page    = $page + 1;
                $next_page    = ($next_page <=$result_count)?$next_page:-1;

                //$results = $this->api->get_projectByUserId($res['user_id']);
                $projects =array();
                $i=0;
                foreach ($result_list as $result) {
                    $projects[$i]['project_id'] = $result['project_id'];
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
                         'project_id' => $result['project_id'],
                        'project_title' => $result['project_title'],
                        'deadline' => $result['due_date'],
                        'project_created' => $result['date_created'],
                        'description' => $result['description'],
                        'progress' => $result['progress'],
                        'project_lead_name' => !empty($project_lead['fullname'])?$project_lead['fullname']:'',
                        'project_lead_photo' => base_url().'assets/uploads/'.$project_lead['avatar'],
                        'project_team_members' => !empty($project_members)?$project_members:$empty_array,
                    ); 
                    $i++; 
                }
               $result = array('next_page'=>$next_page,'current_page'=>$page,'list'=>$projects);
                if(!empty($projects)){
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


    public function client_list_post()
    {
        if($this->is_valid == TRUE)   
         {
            $res = $this->api->get_role_and_userid($this->token);
            if($res['role_id'] == 1 || $res['role_id'] == 4)
            {
                $clients_all = array();
                //$all_clients = $this->api->get_all_clients();
                $i =0;


                $inputs = $this->post();
                $inputs['limit'] = 10;
                $result_count = $this->api->client_list($this->token,$inputs,1);    
                $result_list = $this->api->client_list($this->token,$inputs,2);    
                $page = !empty($inputs['page'])?$inputs['page']:1;
                $result_count = ceil($result_count/$inputs['limit']);
                $next_page    = $page + 1;
                $next_page    = ($next_page <=$result_count)?$next_page:-1;
               
                // $total_cost = [];
                // $payment_total = [];
                // $invoices = [];
                foreach ($result_list as $clients) {
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

                 $result = array('next_page'=>$next_page,'current_page'=>$page,'list'=>$clients_all);
                if(!empty($clients_all)){
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
        }else{
            $this->token_error();
        }
    }

    public function estimate_list_post()
    {
        if($this->is_valid == TRUE)   
         {
            $res = $this->api->get_role_and_userid($this->token);
            if($res['role_id'] == 1 || $res['role_id'] == 4)
            {
                //$estimate_lists = $this->api->get_all_estimate();

                $inputs = $this->post();
                $inputs['limit'] = 10;
                $result_count = $this->api->get_all_estimate($this->token,$inputs,1);    
                $result_list = $this->api->get_all_estimate($this->token,$inputs,2);    
                $page = !empty($inputs['page'])?$inputs['page']:1;
                $result_count = ceil($result_count/$inputs['limit']);
                $next_page    = $page + 1;
                $next_page    = ($next_page <=$result_count)?$next_page:-1;

                $estimate_list = array();
                $i=0;
                $total_cost = [];
                foreach ($result_list as $estimate) {

                    $total_cost[$i] = 0;
                    $estimate_cost= $this->api->get_estimate_cost($estimate['est_id']);
                    foreach($estimate_cost as $items)
                    {
                        $total_cost[$i] += $items['total_cost']; 
                    }
                    $estimate_list[$i]['estimate_id']= $estimate['est_id'];
                    $estimate_list[$i]['client']= $estimate['client'];
                    $estimate_list[$i]['due_date']= $estimate['due_date'];
                    $estimate_list[$i]['reference_no']= $estimate['reference_no'];
                    $estimate_list[$i]['client_name']= $estimate['fullname'];
                    $estimate_list[$i]['status']= $estimate['est_status'];
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

                 $result = array('next_page'=>$next_page,'current_page'=>$page,'list'=>$estimate_list);
                if(!empty($estimate_list)){
                        $response['status_code'] = 1;
                        $response['message'] = $this->success;
                        $response['data'] = $result;
                    }else{
                        $response['status_code'] = 0;
                        $response['message'] = $this->no_result_found;
                    }
                
            }elseif($res['role_id'] == 2){


                //$estimate_lists = $this->api->get_estimateByClient($res['user_id']);

                $inputs = $this->post();
                $inputs['limit'] = 10;
                $result_count = $this->api->get_estimateByClient($this->token,$inputs,1,$res['user_id']);    
                $result_list = $this->api->get_estimateByClient($this->token,$inputs,2,$res['user_id']);    
                $page = !empty($inputs['page'])?$inputs['page']:1;
                $result_count = ceil($result_count/$inputs['limit']);
                $next_page    = $page + 1;
                $next_page    = ($next_page <=$result_count)?$next_page:-1;

                $estimate_list = array();
                $i=0;
                $total_cost = [];
                foreach ($result_list as $estimate) {

                    $total_cost[$i] = 0;
                    $estimate_cost= $this->api->get_estimate_cost($estimate['est_id']);
                    foreach($estimate_cost as $items)
                    {
                        $total_cost[$i] += $items['total_cost']; 
                    }
                    $estimate_list[$i]['estimate_id']= $estimate['est_id'];
                    $estimate_list[$i]['client']= $estimate['client'];
                    $estimate_list[$i]['due_date']= $estimate['due_date'];
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

                 $result = array('next_page'=>$next_page,'current_page'=>$page,'list'=>$estimate_list);
                if(!empty($estimate_list)){
                        $response['status_code'] = 1;
                        $response['message'] = $this->success;
                        $response['data'] = $result;
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
            if($res['role_id'] == 1 || $res['role_id'] == 4)
            {
                //$all_invoices = $this->api->get_all_invoices();
                // print_r($all_invoices); exit;


                $inputs = $this->post();
                $inputs['limit'] = 10;
                $result_count = $this->api->get_all_invoices($this->token,$inputs,1);    
                $result_list = $this->api->get_all_invoices($this->token,$inputs,2);    
                $page = !empty($inputs['page'])?$inputs['page']:1;
                $result_count = ceil($result_count/$inputs['limit']);
                $next_page    = $page + 1;
                $next_page    = ($next_page <=$result_count)?$next_page:-1;



                $invoice_list = array();
                $i=0;
                $total_cost = [];
                $payment_total = [];
                foreach($result_list as $invoice)
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
                    $invoice_list[$i]['client']= $invoice['client'];
                    $invoice_list[$i]['allow_stripe']= $invoice['allow_stripe'];
                    $invoice_list[$i]['extra_fee']= $invoice['extra_fee'];
                    $invoice_list[$i]['reference_no']= $invoice['reference_no'];
                    $invoice_list[$i]['company_name']= $company['company_name'];
                    $invoice_list[$i]['status']= $invoice['inv_status'];
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

                $result = array('next_page'=>$next_page,'current_page'=>$page,'list'=>$invoice_list);

                if(!empty($invoice_list)){
                        $response['status_code'] = 1;
                        $response['message'] = $this->success;
                        $response['data'] = $result;
                    }else{
                        $response['status_code'] = 0;
                        $response['message'] = $this->no_result_found;
                    }

            }elseif($res['role_id'] == 2){
                //$all_invoices = $this->api->get_invoicesbyClient($res['user_id']);
                // print_r($all_invoices); exit;


                $inputs = $this->post();
                $inputs['limit'] = 10;
                $result_count = $this->api->get_invoicesbyClient($this->token,$inputs,1,$res['user_id']);    
                $result_list = $this->api->get_invoicesbyClient($this->token,$inputs,2,$res['user_id']);    
                $page = !empty($inputs['page'])?$inputs['page']:1;
                $result_count = ceil($result_count/$inputs['limit']);
                $next_page    = $page + 1;
                $next_page    = ($next_page <=$result_count)?$next_page:-1;

                $invoice_list = array();
                $i=0;
                $total_cost = [];
                $payment_total = [];
                foreach($result_list as $invoice)
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
                    $invoice_list[$i]['client']= $invoice['client'];
                    $invoice_list[$i]['allow_stripe']= $invoice['allow_stripe'];
                    $invoice_list[$i]['extra_fee']= $invoice['extra_fee'];
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

                $result = array('next_page'=>$next_page,'current_page'=>$page,'list'=>$invoice_list);

                if(!empty($invoice_list)){
                        $response['status_code'] = 1;
                        $response['message'] = $this->success;
                        $response['data'] = $result;
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




     public function expense_list_post()
    {
        if($this->is_valid == TRUE)   
        {
            $res = $this->api->get_role_and_userid($this->token);
            if($res['role_id'] == 1 || $res['role_id'] == 4)
            {
                // $all_expenses = $this->api->get_all_expenses();


                $inputs = $this->post();
                $inputs['limit'] = 10;
                $result_count = $this->api->get_all_expenses($this->token,$inputs,1);    
                $result_list = $this->api->get_all_expenses($this->token,$inputs,2);  



                
                $page = !empty($inputs['page'])?$inputs['page']:1;
                $result_count = ceil($result_count/$inputs['limit']);
                $next_page    = $page + 1;
                $next_page    = ($next_page <=$result_count)?$next_page:-1;

                $expenses_list = array();
                $i=0;
                foreach($result_list as $expense)
                {
                    $company = $this->api->get_company_details($expense['client']);                 
                    $expenses_list[$i]['expense_id']= $expense['id'];
                    $expenses_list[$i]['added_by']= $expense['added_by'];
                    $expenses_list[$i]['billable']= $expense['billable'];
                    $expenses_list[$i]['notes']= $expense['notes'];
                    $expenses_list[$i]['project']= $expense['project'];
                    $expenses_list[$i]['client']= $expense['client'];
                    $expenses_list[$i]['expense_date']= $expense['expense_date'];
                    $expenses_list[$i]['receipt']= $expense['receipt'];
                    $expenses_list[$i]['invoiced']= $expense['invoiced'];
                    $expenses_list[$i]['category_id']= $expense['category'];
                    $expenses_list[$i]['company_name']= $company['company_name'];
                    $expenses_list[$i]['project_title']= $expense['project_title'];
                    $expenses_list[$i]['category']= $expense['cat_name'];
                    $expenses_list[$i]['amount']= $expense['amount'];
                    $expenses_list[$i]['show_client']= $expense['show_client'];
                   
                    $i++;

                }

                 $result = array('next_page'=>$next_page,'current_page'=>$page,'list'=>$expenses_list);

                   
                if(!empty($expenses_list)){
                        $response['status_code'] = 1;
                        $response['message'] = $this->success;
                        $response['data'] = $result;
                    }else{
                        $response['status_code'] = 0;
                        $response['message'] = $this->no_result_found;
                    }

            }elseif($res['role_id'] == 2){
                // $all_expenses = $this->api->get_expensesbyClient($res['user_id']);

                $inputs = $this->post();
                $inputs['limit'] = 10;
                $result_count = $this->api->get_expensesbyClient($this->token,$inputs,1,$res['user_id']);    
                $result_list = $this->api->get_expensesbyClient($this->token,$inputs,2,$res['user_id']);    
                $page = !empty($inputs['page'])?$inputs['page']:1;
                $result_count = ceil($result_count/$inputs['limit']);
                $next_page    = $page + 1;
                $next_page    = ($next_page <=$result_count)?$next_page:-1;


                $expenses_list = array();
                $i=0;
                foreach($result_list as $expense)
                {
                    $company = $this->api->get_company_details($expense['client']);                 
                    $expenses_list[$i]['expense_id']= $expense['id'];
                    $expenses_list[$i]['added_by']= $expense['added_by'];
                    $expenses_list[$i]['billable']= $expense['billable'];
                    $expenses_list[$i]['notes']= $expense['notes'];
                    $expenses_list[$i]['project']= $expense['project'];
                    $expenses_list[$i]['client']= $expense['client'];
                    $expenses_list[$i]['expense_date']= $expense['expense_date'];
                    $expenses_list[$i]['receipt']= $expense['receipt'];
                    $expenses_list[$i]['invoiced']= $expense['invoiced'];
                    $expenses_list[$i]['category_id']= $expense['category'];
                    $expenses_list[$i]['company_name']= $company['company_name'];
                    $expenses_list[$i]['project_title']= $expense['project_title'];
                    $expenses_list[$i]['category']= $expense['cat_name'];
                    $expenses_list[$i]['amount']= $expense['amount'];
                    $expenses_list[$i]['show_client']= $expense['show_client'];
                   
                    $i++;

                }

                $result = array('next_page'=>$next_page,'current_page'=>$page,'list'=>$expenses_list);

                if(!empty($expenses_list)){
                        $response['status_code'] = 1;
                        $response['message'] = $this->success;
                        $response['data'] = $result;
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
            $empty_array=array();
            $res = $this->api->get_role_and_userid($this->token);
            if($res['role_id'] == 1 || $res['role_id'] == 4)
            {
                $user_id = $this->input->post('co_id');
                if($user_id != ''){
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
                    $clients_all['bank'] = $clients['bank']?$clients['bank']:'-';
                    $clients_all['bic'] = $clients['bic']?$clients['bic']:'-';
                    $clients_all['sortcode'] = $clients['sortcode']?$clients['sortcode']:'-';
                    $clients_all['account_holder'] = $clients['account_holder']?$clients['account_holder']:'-';
                    $clients_all['account'] = $clients['account']?$clients['account']:'-';
                    $clients_all['iban'] = $clients['iban']?$clients['iban']:'-';
                    $clients_all['hosting_company'] = $clients['hosting_company']?$clients['hosting_company']:'-';
                    $clients_all['hostname'] = $clients['hostname']?$clients['hostname']:'-';
                    $clients_all['account_username'] = $clients['account_username']?$clients['account_username']:'-';
                    $clients_all['port'] = $clients['port']?$clients['port']:'-';
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
                        $all_invoices = $this->api->get_invoicesbyClients($clients['co_id']);
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
                         $all_invoices = $invoice_list;
                    }else{
                        $all_invoices = $empty_array;
                    }
                    // $all_projects = $this->api->get_allProjectsByClient($clients['co_id']);
                    // if($all_projects != '')
                    // {
                    //     $projects = $all_projects;
                    // }else{
                    //     $projects = '-';
                    // }
                    $results = $this->api->get_projectByUserId($clients['co_id']);
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
                            'project_lead_name' => !empty($project_lead['fullname'])?$project_lead['fullname']:'',
                            'project_lead_photo' => base_url().'assets/uploads/'.$project_lead['avatar'],
                            'project_team_
                        $i++;members' => !empty($project_members)?$project_members:$empty_array,
                        ); 
                    }
                    $clients_all['projects'] = $projects;
                    $clients_all['invoices'] = $all_invoices;
                     $estimate_lists = $this->api->get_estimateByClients($clients['co_id']);
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
                    $clients_all['estimates'] = $estimate_list;
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
                    $all_invoices = $this->api->get_invoicesbyClients($clients['co_id']);
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
                        $all_invoices = $invoice_list;
                }else{
                    $all_invoices = $empty_array;
                }
                 $results = $this->api->get_projectByUserId($clients['co_id']);
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
                            'project_lead_name' => !empty($project_lead['fullname'])?$project_lead['fullname']:'',
                            'project_lead_photo' => base_url().'assets/uploads/'.$project_lead['avatar'],
                            'project_team_
                        $i++;members' => !empty($project_members)?$project_members:$empty_array,
                        ); 
                        $i++;
                    }
                $clients_all['projects'] = $projects;
                $clients_all['invoices'] = $all_invoices;
                // $all_estimate = $this->api->get_allEstimateByClient($clients['co_id']);
                // if($all_estimate != '')
                // {
                //     $estimate = $all_estimate;
                // }else{
                //     $estimate = $empty_array;
                // }
                // $clients_all['estimates'] = $estimate;
                        $estimate_lists = $this->api->get_estimateByClients($clients['co_id']);
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
                    $clients_all['estimates'] = $estimate_list;
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
                $count = $this->db->get_where('dgt_chat_group_details',$data)->num_rows();
                if($count!=0){
                    $result = array('error'=>'Group name already taken!');      
                    
                }else{

                    $this->db->insert('dgt_chat_group_details',$data);
                    $group_id = $this->db->insert_id();

                    $member = explode(',',$user_ids);
                    for ($i=0; $i <count($member) ; $i++) { 

                        $user = $this->db
                        ->select('username,id')
                        ->get_where('dgt_users',array('id'=>$member[$i],'activated'=>1))
                        ->row_array();
                        $group_members[]=$user;
                        if(!empty($user)){
                            $usernames[]=$user['username'];
                            $datas = array(
                                'group_id' => $group_id,
                                'login_id' => $user['id']
                            );
                            $this->db->insert('dgt_chat_group_members',$datas);
                            $this->db->insert('dgt_chat_seen_details',$datas);   
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
                    $this->db->insert('dgt_chat_conversations',$msg_details);

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
                        $resu = curl_exec($ch);
                        curl_close($ch);
                        echo "success"; exit;

                }elseif($inputs['msg_type'] == 'group'){
                    $user_ids = explode(',', $inputs['to_user']);
                    for ($i=0; $i < count($user_ids); $i++) { 
                        // echo $res['user_id'];
                        $msg_details[$i] = array(
                        'from_id' => $res['user_id'],
                        'to_id'   => $user_ids[$i],
                        'message' => $inputs['user_msg'],
                        'msg_type'=> 'group',
                        'group_id'=> $inputs['group_id'],
                        'msg_date'=> date('Y-m-d H:i:s')
                    );
                        // print_r($msg_details[$i]);
                    $this->db->insert('dgt_chat_conversations',$msg_details[$i]);
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
                            $resu = curl_exec($ch);
                            curl_close($ch);
                        }else{
                            $response['status_code'] = 0;
                            $response['message'] = $this->no_deviceid;
                        }

                    }
                    // exit;
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
                    // print_r($group_chat_message); exit; 
                    // foreach($group_chat_message as $group_chat_msg)
                    // {
                    //     if(($group_chat_msg['from_id'] != 0) && ($group_chat_msg['to_id'] != 0))
                    //     {
                    //         $all_chats[] = $group_chat_msg;
                    //     }
                    // }
                    $result = array(
                        'all_members' => $all_members,
                        'chat_messages' => $group_chat_message
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
                $all_group_members =array();
                $group_members = $this->api->get_all_groupmembers($from_user['user_id']);
                // print_r($group_members); exit;
                $i =0;
                foreach($group_members as $group_member)
                {
                    $group_name = $this->api->get_groupname($group_member['group_id']);
                    $group_all_members = $this->api->get_all_members($group_member['group_id']);
                    // print_r($group_all_members);
                    foreach($group_all_members as $all_member){
                        $all_group_members[$i][] = $all_member['login_id'];
                    }
                    // print_r($all_group_members); exit;
                    $last_msg = $this->api->get_last_msg($group_name['group_id']);
                    // print_r($last_msg); 
                    $last_msg_user = $this->api->get_userById($last_msg['from_id']);
                    // print_r($group_name);
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
                    if($group_name != NULL)
                    {
                        // // print_r($group_name); exit;
                        // for($i=0;$i<count($group_name);$i++)
                        // {                            
                            // $uniq = array_keys(array_flip($all_group_members)); 
                            // print_r($uniq);
                            $group_name['user_ids'] = array_keys(array_flip($all_group_members[$i]));
                            $all_group_chat[] =$group_name;
                        // }
                    }
                    // print_r($all_group_chat);
                    if(!empty($last_user_msg_detail)){
                        $all_group_chat_msg[] =$last_user_msg_detail; 
                    }else{
                        $all_group_chat_msg = '';
                    }
                    $group_detail = array(
                        'group_name' => $group_name['group_name'],
                        'group_id'   => $group_name['group_id']
                    );
                    $i++;
                }
                // exit;
                $common_session = $this->api->get_common_session();
                $sessionId = $common_session['common_session_id'];
                $opentok = new OpenTok($this->apiKey, $this->apiSecret);
                $token = $opentok->generateToken($sessionId);
                $result = array(
                    'group_names' => $all_group_chat?$all_group_chat:'0',
                    'group_chat_last_msg' => $all_group_chat_msg?$all_group_chat_msg:'0',
                    'sessionId' => $sessionId,
                    'token' => $token
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


    public function hour_calculation($key, $data) {
        $result = array();
        $r = array();
        foreach($data as $val) {
            if(array_key_exists($key, $val)){
                $time    = explode(':', $val['hours']);
                $minutes = ($time[0] * 60.0 + $time[1] * 1.0);
                $result[$val[$key]] = $result[$val[$key]] + $minutes;
            }else{
                $time2    = explode(':', $val['hours']);
                $minutes2 = ($time2[0] * 60.0 + $time2[1] * 1.0);
                $result[""] = $result[""] + $minutes2;
            }
        }
        foreach ($result as $key => $value) {
            $hours = array(
                'date' => $key,
                'minutes' => $value
            );
            $r[] = $hours;
        }
        return $r;
    }


    public function timesheet_list_post()
    {
        if($this->is_valid == TRUE)   
        {
            $res = $this->api->get_role_and_userid($this->token);
            if($res['role_id'] == 1 || $res['role_id'] == 4)
            {
                $inputs = $this->post();
                if($inputs['user_id'] !='')
                {
                    $token =0;
                    $inputs = $this->post();
                    $inputs['limit'] = 10;
                    $result_count = $this->api->get_all_timesheetById($token,$inputs,1);    
                    $result_list = $this->api->get_all_timesheetById($token,$inputs,2); 
                    $over_all_hours = $this->hour_calculation("timeline_date", $result_list);   
                    $page = !empty($inputs['page'])?$inputs['page']:1;
                    $result_count = ceil($result_count/$inputs['limit']);
                    $next_page    = $page + 1;
                    $next_page    = ($next_page <=$result_count)?$next_page:-1;
                    $result = array('next_page'=>$next_page,'current_page'=>$page,'list'=>$result_list,'overall_hours'=>$over_all_hours);
                    if($result != '')
                    {                    
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
            }elseif($res['role_id'] == 3){
                    $token =$this->token;
                    $inputs = $this->post();
                    $inputs['limit'] = 10;
                    $result_count = $this->api->get_all_timesheetById($token,$inputs,1);    
                    $result_list = $this->api->get_all_timesheetById($token,$inputs,2); 
                    $over_all_hours = $this->hour_calculation("timeline_date", $result_list);   
                    $page = !empty($inputs['page'])?$inputs['page']:1;
                    $result_count = ceil($result_count/$inputs['limit']);
                    $next_page    = $page + 1;
                    $next_page    = ($next_page <=$result_count)?$next_page:-1;
                    $result = array('next_page'=>$next_page,'current_page'=>$page,'list'=>$result_list,'overall_hours'=>$over_all_hours);
                    if($result != '')
                    {                    
                        $response['status_code'] = 1;
                        $response['message'] = $this->success;
                        $response['data'] = $result;
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

    public function add_timesheet_post()
    {
        if($this->is_valid == TRUE)   
        {
            $res = $this->api->get_role_and_userid($this->token);
            if($res['role_id'] == 3)
            {
                $inputs = $this->post();
                if(($inputs['project_id'] != '') && ($inputs['hours'] != '') && ($inputs['timeline_date'] != '') && ($inputs['timeline_desc'] != ''))
                {
                    $resi= array(
                        'user_id' => $res['user_id'],
                        'project_id' =>$inputs['project_id'],
                        'hours'  => $inputs['hours'],
                        'timeline_date' => $inputs['timeline_date'],
                        'timeline_desc' => $inputs['timeline_desc']
                    );
                    $this->db->insert('dgt_timesheet',$resi);
                    $insert_id = $this->db->insert_id();
                    $last_timesheet = $this->api->get_timesheet($insert_id);
                    $result = array(
                        'last_timesheet' => $last_timesheet
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

    public function edit_timesheet_post()
    {
        if($this->is_valid == TRUE)   
        {
            $res = $this->api->get_role_and_userid($this->token);
            if($res['role_id'] == 3)
            {
                $inputs = $this->post();
                $results = array(
                    'user_id' => $res['user_id'],
                    'project_id' =>$inputs['project_id'],
                    'hours'  => $inputs['hours'],
                    'timeline_date' => $inputs['timeline_date'],
                    'timeline_desc' => $inputs['timeline_desc']
                );
                $this->db->where('time_id',$inputs['time_id']);
                $this->db->update('dgt_timesheet',$results);
                // $all_timesheet = $this->api->get_all_timesheetById($res['user_id']);
                // $result = array(
                //     'all_timesheets' => $all_timesheet
                // );
                $last_timesheet = $this->api->get_timesheet($inputs['time_id']);
                $result = array(
                    'last_timesheet' => $last_timesheet
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
                $this->response($response, REST_Controller::HTTP_OK);
            }else{
                $this->token_error();
            }
        }else{
            $this->token_error();
        }
    }


    public function delete_timesheet_post()
    {
        if($this->is_valid == TRUE)   
        {
            $res = $this->api->get_role_and_userid($this->token);
            if($res['role_id'] == 3)
            {
                $inputs = $this->post();
                $this->db->where('time_id',$inputs['time_id']);
                $this->db->delete('dgt_timesheet');
                $all_timesheet = $this->api->get_all_timesheetById($res['user_id']);
                $result = array(
                    'all_timesheets' => $all_timesheet
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
                $this->response($response, REST_Controller::HTTP_OK);
            }else{
                $this->token_error();
            }
        }else{
            $this->token_error();
        }
    }

    public function all_payments_post()
    {
        if($this->is_valid == TRUE)    
        {
            $res = $this->api->get_role_and_userid($this->token);
            if(($res['role_id'] == 4) || ($res['role_id'] == 1) || ($res['role_id'] == 2))
            {
                $payments_all = array();
                $all_payments = $this->api->getAllPayments();
                foreach($all_payments as $all_payment)
                {
                    $res = array(
                        'payment_id' => $all_payment['p_id'],
                        'payment_date' => $all_payment['created_date'],
                        'transaction_id' => $all_payment['trans_id'],
                        'received_from' => $all_payment['company_name'],
                        'payment_made' => $all_payment['method_name'],
                        'currency' => $all_payment['currency'],
                        'notes' => $all_payment['notes'],
                        'amount' => $all_payment['amount'],
                        'notes' => $all_payment['notes'],
                        'invoice_no' => $all_payment['reference_no'],
                         'invoice_date' => $all_payment['date_saved'],
                        'invoice_id' => $all_payment['inv_id'],
                    );
                    $payments_all[] = $res;
                }
                
                if($payments_all != '')
                {                    
                    $response['status_code'] = 1;
                    $response['message'] = $this->success;
                    $response['data'] = $payments_all;
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



    public function dashboard_count_post()
    {
        if($this->is_valid == TRUE)    
        {
            $res = $this->api->get_role_and_userid($this->token);
            if(($res['role_id'] == 4) || ($res['role_id'] == 1)) // Superadmin / Admin
            {
                $all_projects_admin = $this->api->get_project_counts('admin','');
                $all_clients_admin = $this->api->get_clients_counts(2);
                $all_staff_admin = $this->api->get_clients_counts(3);
                $all_invoice_admin = $this->api->get_invoice_counts('admin','');
                $result = array(
                    'project_counts' => count($all_projects_admin),
                    'client_counts' => count($all_clients_admin),
                    'staff_counts' => count($all_staff_admin),
                    'invoice_counts' => count($all_invoice_admin)
                );
            }else if($res['role_id'] == 2){   // client
                $all_projects_client = $this->api->get_project_counts('client',$res['user_id']);
                $all_invoice_client = $this->api->get_invoice_counts('client',$res['user_id']);
                $all_estimate_client = $this->api->get_estimate_counts($res['user_id']);
                $result = array(
                    'project_counts' => count($all_projects_client),
                    'invoice_counts' => count($all_invoice_client),
                    'estimate_counts' => count($all_estimate_client)
                );
            }else if($res['role_id'] == 3){ // Staff
                $all_projects_staff = $this->api->get_project_counts('staff',$res['user_id']);
                $all_tasks_staff = $this->api->get_tasks_counts($res['user_id']);
                $result =array(
                    'project_counts' => count($all_projects_staff),
                    'task_counts' => count($all_tasks_staff),
                );

            }else{
                $response['status_code'] = 0;
                $response['message'] = $this->something;
            }
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

    public function currency_convert_post()
    {
        if($this->is_valid == TRUE)    
        {
            $inputs = $this->post();
            $convert_amount = Applib::format_currency_mobile($inputs['code'],$inputs['amount']);
            $response['status_code'] = 1;
            $response['message'] = $this->success;
            $response['data'] = $convert_amount;
            $this->response($response, REST_Controller::HTTP_OK);
        }else{
            $this->token_error();
        }
        
    }

    public function attendance_list_post()
    {
            if($this->is_valid == TRUE)   {

                
            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = $this->required_input;
            $response['data'] = $data;

            $inputs = $this->post();
            $month = $inputs['attendance_month'];
            $year  = $inputs['attendance_year'];
            $last_day = $year.'-'.$month.'-1';
            
            $attendance_list = $this->api->attendance_list($inputs); 

            $result = array('attendance_list'=>$attendance_list[1],'total_page'=>$attendance_list[0],'last_day'=>date('t',strtotime($last_day)));

            if(!empty($attendance_list)){
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

     public function attendance_info_post()
    {


            if($this->is_valid == TRUE)   {

                $this->load->helper('date');

              
            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = $this->required_input;
            $response['data'] = $data;

            $inputs = $this->post();

            $user_id = $inputs['user_id'];
            $atten_day = $inputs['day'];
            $atten_month = $inputs['month'];
            $atten_year = $inputs['year'];
             $where     = array('user_id'=>$user_id,'a_month'=>$atten_month,'a_year'=>$atten_year);
             $this->db->select('month_days,month_days_in_out');
             $record = $this->db->get_where('dgt_attendance_details',$where)->row_array();

              
             if($record){

              $a_day     = $atten_day;
              $a_days     = $atten_day;
              $a_dayss     = $atten_day;

              if(!empty($record['month_days']))
              {
     
                  $month_days =  unserialize($record['month_days']);
                  $month_days_in_out =  unserialize($record['month_days_in_out']);
                  $a_day -=1;

                 if(!empty($month_days[$a_day])  && !empty($month_days_in_out[$a_day])){  

                  $day = $month_days[$a_day];
                  $day_in_out = $month_days_in_out[$a_day];


                  $latest_inout = end($day_in_out);

                
                    if($day['day'] == '' || !empty($latest_inout['punch_out'])){ 
                      $punch_in = $day['punch_in'];
                      $punch_in_out = $latest_inout['punch_in'];
                      $punch_out_in = $latest_inout['punch_out'];
                      $punchin_id = 1;
                    }else{
                       $punch_in = $day['punch_in'];
                      $punch_in_out = $latest_inout['punch_in'];
                      $punch_out_in = $latest_inout['punch_out'];
                      $punchin_id = 0;
                    }
                 }
                     
                        
                 

                 $punchin_time = date("g:i a", strtotime($day['punch_in']));
                 $punchout_time = date("g:i a", strtotime($day['punch_out']));
             }

             $a_dayss -=1;
             $production_hour=0;
             $break_hour=0;

             if(!empty($record['month_days_in_out'])){

             $month_days_in_outss =  unserialize($record['month_days_in_out']);

                                  
              foreach ($month_days_in_outss[$a_dayss] as $punch_detailss) 
              {

                  if(!empty($punch_detailss['punch_in']) && !empty($punch_detailss['punch_out']))
                  {
                    
                      $production_hour += time_difference(date('H:i',strtotime($punch_detailss['punch_in'])),date('H:i',strtotime($punch_detailss['punch_out'])));
                  }
                            
                                              
                   
              }

               for ($i=0; $i <count($month_days_in_outss[$a_dayss]) ; $i++) { 

                          if(!empty($month_days_in_outss[$a_dayss][$i]['punch_out']) && $month_days_in_outss[$a_dayss][ $i+1 ]['punch_in'])
                          {
                              
                              $break_hour += time_difference(date('H:i',strtotime($month_days_in_outss[$a_dayss][$i]['punch_out'])),date('H:i',strtotime($month_days_in_outss[$a_dayss][ $i+1 ]['punch_in'])));
                          }

                          
                }
            }


            $punch_in_time='';
            $punch_out_time='';
            if(!empty($punch_in))
            {
                $punch_in_time=date('l',strtotime($atten_year.'-'.$atten_month.'-'.$atten_day)).', '.date('d M Y',strtotime($atten_year.'-'.$atten_month.'-'.$atten_day)).' '. date("g:i a", strtotime($punch_in));
            }
            if(!empty($punch_out_in))
            {
                $punch_out_time=date('l',strtotime($atten_year.'-'.$atten_month.'-'.$atten_day)).', '.date('d M Y',strtotime($atten_year.'-'.$atten_month.'-'.$atten_day)).' '.date("g:i a", strtotime($punch_out_in));
            }

              $overtimes=9-($production_hour+$break_hour);
              if($overtimes > 0)
              {
                $overtime=$overtimes;
              }
              else
              {
                $overtime=0;
              }

              $a_days -=1;

              $punch_in_out_details=array();
             
             if(!empty($record['month_days_in_out'])){

             $month_days_in_outs =  unserialize($record['month_days_in_out']);

                                  
              foreach ($month_days_in_outs[$a_days] as $punch_details) 
              {


                $punch_in_out_detail['punch_in']='';
                $punch_in_out_detail['punch_out']='';
                if(!empty($punch_details['punch_in']))
                {
                  $punch_in_out_detail['punch_in']=date("g:i a", strtotime($punch_details['punch_in']));
                }
                if(!empty($punch_details['punch_out']))
                {
                  $punch_in_out_detail['punch_out']=date("g:i a", strtotime($punch_details['punch_out']));
                }

                 $punch_in_out_details[]=$punch_in_out_detail;


              }

            }

            

            $result = array('punch_in_time'=>$punch_in_time,'punch_out_time'=>$punch_out_time,'production_hour'=>intdiv($production_hour, 60).'.'. ($production_hour % 60),'break_hour'=>intdiv($break_hour, 60).'.'. ($break_hour % 60),'overtime'=>intdiv($overtime, 60).'.'. ($overtime % 60),'activity'=>$punch_in_out_details);

          }

            if(!empty($record)){
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


    public function attendance_details_post()
    {


            if($this->is_valid == TRUE)   {

                $this->load->helper('date');

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = $this->required_input;
            $response['data'] = $data;

            $inputs = $this->post();

            $user_id = $inputs['user_id'];

            date_default_timezone_set('Asia/Kolkata');
                  $punch_in_date = date('Y-m-d');
                  $punch_in_time = date('H:i');
                  $punch_in_date_time = date('Y-m-d H:i');


                   $strtotime = strtotime($punch_in_date_time);
                   $a_year    = date('Y',$strtotime);
                   $a_month   = date('m',$strtotime);
                   $a_day     = date('d',$strtotime);
                   $a_days     = date('d',$strtotime);
                   $a_dayss     = date('d',$strtotime);
                   $a_cin     = date('H:i',$strtotime);
                   $where     = array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
                   $this->db->select('month_days,month_days_in_out');
                   $record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();

                   $punchin_id = 1;
                   if(!empty($record['month_days'])){
                     
                    
                      $month_days =  unserialize($record['month_days']);
                      $month_days_in_out =  unserialize($record['month_days_in_out']);
                     
                     $a_day -=1;

                     if(!empty($month_days[$a_day])  && !empty($month_days_in_out[$a_day])){  

                      $day = $month_days[$a_day];
                      $day_in_out = $month_days_in_out[$a_day];


                      $latest_inout = end($day_in_out);

                    
                        if($day['day'] == '' || !empty($latest_inout['punch_out'])){ 
                          $punch_in = $day['punch_in'];
                          $punch_in_out = $latest_inout['punch_in'];
                          $punch_out_in = $latest_inout['punch_out'];
                          $punchin_id = 1;
                        }else{
                           $punch_in = $day['punch_in'];
                          $punch_in_out = $latest_inout['punch_in'];
                          $punch_out_in = $latest_inout['punch_out'];
                          $punchin_id = 0;
                        }
                     }
                         
                            
                     

                     $punchin_time = date("g:i a", strtotime($day['punch_in']));
                     $punchout_time = date("g:i a", strtotime($day['punch_out']));
                   }

                    $a_dayss -=1;
                    $production_hour=0;
                    $break_hour=0;

                     if(!empty($record['month_days_in_out'])){

                     $month_days_in_outss =  unserialize($record['month_days_in_out']);

                                          
                      foreach ($month_days_in_outss[$a_dayss] as $punch_detailss) 
                      {

                          if(!empty($punch_detailss['punch_in']) && !empty($punch_detailss['punch_out']))
                          {
                            
                              $production_hour += time_difference(date('H:i',strtotime($punch_detailss['punch_in'])),date('H:i',strtotime($punch_detailss['punch_out'])));
                          }
                                    
                                                      
                           
                      }

                       for ($i=0; $i <count($month_days_in_outss[$a_dayss]) ; $i++) { 

                                  if(!empty($month_days_in_outss[$a_dayss][$i]['punch_out']) && $month_days_in_outss[$a_dayss][ $i+1 ]['punch_in'])
                                  {
                                      
                                      $break_hour += time_difference(date('H:i',strtotime($month_days_in_outss[$a_dayss][$i]['punch_out'])),date('H:i',strtotime($month_days_in_outss[$a_dayss][ $i+1 ]['punch_in'])));
                                  }

                                  
                        }
                    }


                   

                             $maxTime = (8*3600);
                             $today_percentage = (($production_hour*60) / $maxTime) * 100;
                             

                              $week_production_hour=0;
                              $month_production_hour=0;

                         if(!empty($record['month_days_in_out'])){

                             $month_days_in_out_week =  unserialize($record['month_days_in_out']);

                              $week_start_date = date("d",strtotime('monday this week'));
                              $week_end_date=date("d",strtotime("friday this week"));

                             for ($week=$week_start_date-1; $week <= $week_end_date-1 ; $week++) { 
                                                                          
                              foreach ($month_days_in_out_week[$week] as $punch_detail_week) 
                              {

                                  if(!empty($punch_detail_week['punch_in']) && !empty($punch_detail_week['punch_out']))
                                  {
                                    
                                      $week_production_hour += time_difference(date('H:i',strtotime($punch_detail_week['punch_in'])),date('H:i',strtotime($punch_detail_week['punch_out'])));


                                  }
                              }

                             }
      
                        }
                  
                                    
                         $week_maxTime = (40*3600);
                         $week_percentage = (($week_production_hour*60) / $week_maxTime) * 100;

                         $working_hours=working_days(date('n'), date('Y'))*8;
   

                     
                      if(!empty($record['month_days_in_out'])){

                           $month_days_in_out_month =  unserialize($record['month_days_in_out']);

                             $month_start_date = date('01', strtotime(date('Y-m-d')));
                             $month_end_date=date('t', strtotime(date('Y-m-d')));

                           for ($month=$month_start_date-1; $month <= $month_end_date-1 ; $month++) { 
                                                                        
                            foreach ($month_days_in_out_month[$month] as $punch_detail_month) 
                            {

                                if(!empty($punch_detail_month['punch_in']) && !empty($punch_detail_month['punch_out']))
                                {
                                  
                                    $month_production_hour += time_difference(date('H:i',strtotime($punch_detail_month['punch_in'])),date('H:i',strtotime($punch_detail_month['punch_out'])));


                                }
                            }

                           }
      
                        }
                  
                                    
                         $month_maxTime = ($working_hours*3600);
                         $month_percentage = (($month_production_hour*60) / $month_maxTime) * 100;


                         $remaining_hour=($working_hours*60)-$month_production_hour;



                           $month_overtimes=($month_production_hour)-($working_hours*60);
                          if($month_overtimes > 0)
                          {
                            $month_overtime=$month_overtimes;
                          }
                          else
                          {
                            $month_overtime=0;
                          }

                        $overtime_percentage = (($month_overtime*60) / $month_maxTime) * 100;

                       

                     if(isset($inputs['attendance_month']) && !empty($inputs['attendance_month']))
                    {
                      $a_month=$inputs['attendance_month'];
                    }

                     if(isset($inputs['attendance_year']) && !empty($inputs['attendance_year']))
                    {
                      $a_year=$inputs['attendance_year'];
                    }





                     $where     = array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
                     $this->db->select('month_days,month_days_in_out');
                     $results  = $this->db->get_where('dgt_attendance_details',$where)->result_array();
                     
                     
                     foreach ($results as $rows) {

                          $list=array();


                          $month = $a_month;
                          $year = $a_year;

                          $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);


                            for($d=1; $d<=$number; $d++)
                           {
                              $time=mktime(12, 0, 0, $month, $d, $year);          
                              if (date('m', $time)==$month)       
                                  $date=date('d M Y', $time);

                                  $a_day =date('d', $time);

                                if(!empty($rows['month_days'])){
     
    
                                $month_days =  unserialize($rows['month_days']);
                                $month_days_in_out =  unserialize($rows['month_days_in_out']);
                                $day = $month_days[$a_day-1];
                                $day_in_out = $month_days_in_out[$a_day-1];
                                $latest_inout = end($day_in_out);

                                 $production_hour1=0;
                                 $break_hour1=0;

                                foreach ($month_days_in_out[$a_day-1] as $punch_detail) 
                                {

                                    if(!empty($punch_detail['punch_in']) && !empty($punch_detail['punch_out']))
                                    {
                                      
                                        $production_hour1 += time_difference(date('H:i',strtotime($punch_detail['punch_in'])),date('H:i',strtotime($punch_detail['punch_out'])));
                                    }
                                              
                                                                
                                     
                                }

                             for ($i=0; $i <count($month_days_in_out[$a_day-1]) ; $i++) { 

                                        if(!empty($month_days_in_out[$a_day-1][$i]['punch_out']) && $month_days_in_out[$a_day-1][ $i+1 ]['punch_in'])
                                        {
                                            
                                            $break_hour1 += time_difference(date('H:i',strtotime($month_days_in_out[$a_day-1][$i]['punch_out'])),date('H:i',strtotime($month_days_in_out[$a_day-1][ $i+1 ]['punch_in'])));
                                        }

                                        
                              }

                              $overtimes1=($production_hour1+$break_hour1)-(9*60);
                              if($overtimes1 > 0)
                              {
                                $overtime1=$overtimes1;
                              }
                              else
                              {
                                $overtime1=0;
                              }



                      
                      
                      if(date('D', $time)=='Sat' || date('D', $time)=='Sun')
                      {
                            if(!empty($day['punch_in']))
                            {
                            
                               $row['date']= $date;
                               $row['punch_in']= !empty($day['punch_in'])?date("g:i a", strtotime($day['punch_in'])):'-';
                               $row['punch_out']= !empty($latest_inout['punch_out'])?date("g:i a", strtotime($latest_inout['punch_out'])):'-';
                               $row['production_hour']= !empty($production_hour1)?intdiv($production_hour1, 60).'.'. ($production_hour1 % 60).' hrs':'-';
                               $row['break_hour']= !empty($break_hour1)?intdiv($break_hour1, 60).'.'. ($break_hour1 % 60).' hrs':'-';
                               $row['overtime']= !empty($overtime1)?intdiv($overtime1, 60).'.'. ($overtime1 % 60).' hrs':'-';
                               $row['week_off']= '';


                               
                           
                           
                            }
                            else
                            {
                               $row['date']= $date; 
                               $row['punch_in']=''; 
                                $row['punch_out']= '';
                                 $row['production_hour']='';
                                $row['break_hour']='';
                                $row['overtime']='';
                               $row['week_off']= 'Week Off';

                              
                            }



                      }
                      else
                      {
                               $row['date']= $date;
                               $row['punch_in']= !empty($day['punch_in'])?date("g:i a", strtotime($day['punch_in'])):'-';
                               $row['punch_out']= !empty($latest_inout['punch_out'])?date("g:i a", strtotime($latest_inout['punch_out'])):'-';
                               $row['production_hour']= !empty($production_hour1)?intdiv($production_hour1, 60).'.'. ($production_hour1 % 60).' hrs':'-';
                               $row['break_hour']= !empty($break_hour1)?intdiv($break_hour1, 60).'.'. ($break_hour1 % 60).' hrs':'-';
                               $row['overtime']= !empty($overtime1)?intdiv($overtime1, 60).'.'. ($overtime1 % 60).' hrs':'-';
                               $row['week_off']= '';

                            
                      }


                      $month_attendance_Details[]=$row;


                      } } 



                  } 


                                               

            $result = array('today_production_hour'=>intdiv($production_hour, 60).'.'. ($production_hour % 60),'week_production_hour'=>intdiv($week_production_hour, 60).'.'. ($week_production_hour % 60),'month_production_hour'=>intdiv($month_production_hour, 60).'.'. ($month_production_hour % 60),'remaining_hour'=>intdiv($remaining_hour, 60).'.'. ($remaining_hour % 60),'month_overtime'=>intdiv($month_overtime, 60).'.'. ($month_overtime % 60),'today_percentage'=>round($today_percentage),'week_percentage'=>round($week_percentage),'month_percentage'=>round($month_percentage),'overtime_percentage'=>round($overtime_percentage),'working_hours'=>$working_hours,'month_attendance_Details'=>$month_attendance_Details);

          

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


    public function create_attendance_post()
    {


            if($this->is_valid == TRUE)   {

                $recordss =  $this->get_role_and_userid($this->token);
                                 
                $user_id = $recordss['user_id'];

                $this->load->helper('date');

            $data = array();
            $response = array();
            $response['status_code'] = -1;
            $response['message'] = $this->required_input;
            $response['data'] = $data;

            $inputs = $this->post();

            

                 date_default_timezone_set('Asia/Kolkata');
                  $punch_in_date = date('Y-m-d');
                  $punch_in_time = date('H:i');
                  $punch_in_date_time = date('Y-m-d H:i');


                   $strtotime = strtotime($punch_in_date_time);
                   $a_year    = date('Y',$strtotime);
                   $a_month   = date('m',$strtotime);
                   $a_day     = date('d',$strtotime);
                   $a_days     = date('d',$strtotime);
                   $a_dayss     = date('d',$strtotime);
                   $a_cin     = date('H:i',$strtotime);
                   $where     = array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
                   $this->db->select('month_days,month_days_in_out');
                   $record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();

                   $punchin_id = 1;
                   if(!empty($record['month_days'])){
                     
                    
                      $month_days =  unserialize($record['month_days']);
                      $month_days_in_out =  unserialize($record['month_days_in_out']);
                     
                     $a_day -=1;

                     if(!empty($month_days[$a_day])  && !empty($month_days_in_out[$a_day])){  

                      $day = $month_days[$a_day];
                      $day_in_out = $month_days_in_out[$a_day];


                      $latest_inout = end($day_in_out);

                    
                        if($day['day'] == '' || !empty($latest_inout['punch_out'])){ 
                          $punch_in = $day['punch_in'];
                          $punch_in_out = $latest_inout['punch_in'];
                          $punch_out_in = $latest_inout['punch_out'];
                          $punchin_id = 1;
                        }else{
                           $punch_in = $day['punch_in'];
                          $punch_in_out = $latest_inout['punch_in'];
                          $punch_out_in = $latest_inout['punch_out'];
                          $punchin_id = 0;
                        }
                     }
                         
                            
                     

                     $punchin_time = date("g:i a", strtotime($day['punch_in']));
                     $punchout_time = date("g:i a", strtotime($day['punch_out']));
                   }

                    $a_dayss -=1;
                    $production_hour=0;
                    $break_hour=0;

                     if(!empty($record['month_days_in_out'])){

                     $month_days_in_outss =  unserialize($record['month_days_in_out']);

                                          
                      foreach ($month_days_in_outss[$a_dayss] as $punch_detailss) 
                      {

                          if(!empty($punch_detailss['punch_in']) && !empty($punch_detailss['punch_out']))
                          {
                            
                              $production_hour += time_difference(date('H:i',strtotime($punch_detailss['punch_in'])),date('H:i',strtotime($punch_detailss['punch_out'])));
                          }
                                    
                                                      
                           
                      }

                       for ($i=0; $i <count($month_days_in_outss[$a_dayss]) ; $i++) { 

                                  if(!empty($month_days_in_outss[$a_dayss][$i]['punch_out']) && $month_days_in_outss[$a_dayss][ $i+1 ]['punch_in'])
                                  {
                                      
                                      $break_hour += time_difference(date('H:i',strtotime($month_days_in_outss[$a_dayss][$i]['punch_out'])),date('H:i',strtotime($month_days_in_outss[$a_dayss][ $i+1 ]['punch_in'])));
                                  }

                                  
                        }
                    }


                    

                             $maxTime = (8*3600);
                             $today_percentage = (($production_hour*60) / $maxTime) * 100;
                             

                              $week_production_hour=0;
                              $month_production_hour=0;

                         if(!empty($record['month_days_in_out'])){

                             $month_days_in_out_week =  unserialize($record['month_days_in_out']);

                              $week_start_date = date("d",strtotime('monday this week'));
                              $week_end_date=date("d",strtotime("friday this week"));

                             for ($week=$week_start_date-1; $week <= $week_end_date-1 ; $week++) { 
                                                                          
                              foreach ($month_days_in_out_week[$week] as $punch_detail_week) 
                              {

                                  if(!empty($punch_detail_week['punch_in']) && !empty($punch_detail_week['punch_out']))
                                  {
                                    
                                      $week_production_hour += time_difference(date('H:i',strtotime($punch_detail_week['punch_in'])),date('H:i',strtotime($punch_detail_week['punch_out'])));


                                  }
                              }

                             }
      
                        }
                  
                                    
                         $week_maxTime = (40*3600);
                         $week_percentage = (($week_production_hour*60) / $week_maxTime) * 100;

                         $working_hours=working_days(date('n'), date('Y'))*8;
   

                     
                      if(!empty($record['month_days_in_out'])){

                           $month_days_in_out_month =  unserialize($record['month_days_in_out']);

                             $month_start_date = date('01', strtotime(date('Y-m-d')));
                             $month_end_date=date('t', strtotime(date('Y-m-d')));

                           for ($month=$month_start_date-1; $month <= $month_end_date-1 ; $month++) { 
                                                                        
                            foreach ($month_days_in_out_month[$month] as $punch_detail_month) 
                            {

                                if(!empty($punch_detail_month['punch_in']) && !empty($punch_detail_month['punch_out']))
                                {
                                  
                                    $month_production_hour += time_difference(date('H:i',strtotime($punch_detail_month['punch_in'])),date('H:i',strtotime($punch_detail_month['punch_out'])));


                                }
                            }

                           }
      
                        }
                  
                                    
                         $month_maxTime = ($working_hours*3600);
                         $month_percentage = (($month_production_hour*60) / $month_maxTime) * 100;


                         $remaining_hour=($working_hours*60)-$month_production_hour;



                           $month_overtimes=($month_production_hour)-($working_hours*60);
                          if($month_overtimes > 0)
                          {
                            $month_overtime=$month_overtimes;
                          }
                          else
                          {
                            $month_overtime=0;
                          }

                        $overtime_percentage = (($month_overtime*60) / $month_maxTime) * 100;



                         $overtimes=($production_hour+$break_hour)-(9*60);
                          if($overtimes > 0)
                          {
                            $overtime=$overtimes;
                          }
                          else
                          {
                            $overtime=0;
                          }



                          $a_days -=1;

              $punch_in_out_details=array();
             
             if(!empty($record['month_days_in_out'])){

             $month_days_in_outs =  unserialize($record['month_days_in_out']);

                                  
              foreach ($month_days_in_outs[$a_days] as $punch_details) 
              {


                $punch_in_out_detail['punch_in']='';
                $punch_in_out_detail['punch_out']='';
                if(!empty($punch_details['punch_in']))
                {
                  $punch_in_out_detail['punch_in']=date("g:i a", strtotime($punch_details['punch_in']));
                }
                if(!empty($punch_details['punch_out']))
                {
                  $punch_in_out_detail['punch_out']=date("g:i a", strtotime($punch_details['punch_out']));
                }

                 $punch_in_out_details[]=$punch_in_out_detail;


              }

            }



                       

                     if(isset($inputs['attendance_month']) && !empty($inputs['attendance_month']))
                    {
                      $a_month=$inputs['attendance_month'];
                    }

                     if(isset($inputs['attendance_year']) && !empty($inputs['attendance_year']))
                    {
                      $a_year=$inputs['attendance_year'];
                    }





                     $where     = array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
                     $this->db->select('month_days,month_days_in_out');
                     $results  = $this->db->get_where('dgt_attendance_details',$where)->result_array();
                     
                     
                     foreach ($results as $rows) {

                          $list=array();


                          $month = $a_month;
                          $year = $a_year;

                          $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);


                            for($d=1; $d<=$number; $d++)
                           {
                              $time=mktime(12, 0, 0, $month, $d, $year);          
                              if (date('m', $time)==$month)       
                                  $date=date('d M Y', $time);

                                  $a_day =date('d', $time);

                                if(!empty($rows['month_days'])){
     
    
                                $month_days =  unserialize($rows['month_days']);
                                $month_days_in_out =  unserialize($rows['month_days_in_out']);
                                $day = $month_days[$a_day-1];
                                $day_in_out = $month_days_in_out[$a_day-1];
                                $latest_inout = end($day_in_out);

                                 $production_hour1=0;
                                 $break_hour1=0;

                                foreach ($month_days_in_out[$a_day-1] as $punch_detail) 
                                {

                                    if(!empty($punch_detail['punch_in']) && !empty($punch_detail['punch_out']))
                                    {
                                      
                                        $production_hour1 += time_difference(date('H:i',strtotime($punch_detail['punch_in'])),date('H:i',strtotime($punch_detail['punch_out'])));
                                    }
                                              
                                                                
                                     
                                }

                             for ($i=0; $i <count($month_days_in_out[$a_day-1]) ; $i++) { 

                                        if(!empty($month_days_in_out[$a_day-1][$i]['punch_out']) && $month_days_in_out[$a_day-1][ $i+1 ]['punch_in'])
                                        {
                                            
                                            $break_hour1 += time_difference(date('H:i',strtotime($month_days_in_out[$a_day-1][$i]['punch_out'])),date('H:i',strtotime($month_days_in_out[$a_day-1][ $i+1 ]['punch_in'])));
                                        }

                                        
                              }

                              $overtimes1=($production_hour1+$break_hour1)-(9*60);
                              if($overtimes1 > 0)
                              {
                                $overtime1=$overtimes1;
                              }
                              else
                              {
                                $overtime1=0;
                              }



                      
                      
                      if(date('D', $time)=='Sat' || date('D', $time)=='Sun')
                      {
                            if(!empty($day['punch_in']))
                            {
                            
                               $row['date']= $date;
                               $row['punch_in']= !empty($day['punch_in'])?date("g:i a", strtotime($day['punch_in'])):'-';
                               $row['punch_out']= !empty($latest_inout['punch_out'])?date("g:i a", strtotime($latest_inout['punch_out'])):'-';
                               $row['production_hour']= !empty($production_hour1)?intdiv($production_hour1, 60).'.'. ($production_hour1 % 60).' hrs':'-';
                               $row['break_hour']= !empty($break_hour1)?intdiv($break_hour1, 60).'.'. ($break_hour1 % 60).' hrs':'-';
                               $row['overtime']= !empty($overtime1)?intdiv($overtime1, 60).'.'. ($overtime1 % 60).' hrs':'-';
                               $row['week_off']= '';


                               
                           
                           
                            }
                            else
                            {
                               $row['date']= $date; 
                               $row['punch_in']=''; 
                                $row['punch_out']= '';
                                 $row['production_hour']='';
                                $row['break_hour']='';
                                $row['overtime']='';
                               $row['week_off']= 'Week Off';

                              
                            }



                      }
                      else
                      {
                               $row['date']= $date;
                               $row['punch_in']= !empty($day['punch_in'])?date("g:i a", strtotime($day['punch_in'])):'-';
                               $row['punch_out']= !empty($latest_inout['punch_out'])?date("g:i a", strtotime($latest_inout['punch_out'])):'-';
                               $row['production_hour']= !empty($production_hour1)?intdiv($production_hour1, 60).'.'. ($production_hour1 % 60).' hrs':'-';
                               $row['break_hour']= !empty($break_hour1)?intdiv($break_hour1, 60).'.'. ($break_hour1 % 60).' hrs':'-';
                               $row['overtime']= !empty($overtime1)?intdiv($overtime1, 60).'.'. ($overtime1 % 60).' hrs':'-';
                               $row['week_off']= '';

                            
                      }


                      $month_attendance_Details[]=$row;


                      } } 



                  } 


                                               

            $result = array('punch_in_time'=>date("g:i a", strtotime($punch_in)),'today_production_hour'=>intdiv($production_hour, 60).'.'. ($production_hour % 60),'week_production_hour'=>intdiv($week_production_hour, 60).'.'. ($week_production_hour % 60),'month_production_hour'=>intdiv($month_production_hour, 60).'.'. ($month_production_hour % 60),'remaining_hour'=>intdiv($remaining_hour, 60).'.'. ($remaining_hour % 60),'month_overtime'=>intdiv($month_overtime, 60).'.'. ($month_overtime % 60),'today_percentage'=>round($today_percentage),'week_percentage'=>round($week_percentage),'month_percentage'=>round($month_percentage),'overtime_percentage'=>round($overtime_percentage),'working_hours'=>$working_hours,'production_hour'=>intdiv($production_hour, 60).'.'. ($production_hour % 60),'break_hour'=>intdiv($break_hour, 60).'.'. ($break_hour % 60),'overtime'=>intdiv($overtime, 60).'.'. ($overtime % 60),'punch_in_out'=>$punchin_id,'month_attendance_Details'=>$month_attendance_Details,'activity'=>$punch_in_out_details);

          

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



    public function get_role_and_userid($token)
    {
        $this->db->select('id as user_id, role_id');
        return $this->db->get_where('dgt_users U',array('unique_code'=>$token,'activated'=>1))->row_array();
    }

    public function run_payroll_get()
    {

         if($this->is_valid == TRUE)   {
            $users_list = $this->db->query("SELECT u.*
                                    FROM `dgt_users` u  
                                    inner join dgt_bank_statutory bs on bs.user_id = u.id 
                                    where u.activated = 1  and u.status=1 order by u.created desc")->result_array();

            

            foreach ($users_list as $user_rows) 
            {

                $payroll_user_id[]=$user_rows['id'];

            }

            
            for ($p=0; $p <count($payroll_user_id) ; $p++) { 
                $pay_slip_details = array();
                
                

                $payslip_year=date('Y');
                $payslip_month=date('m');

                

                $pay_slip_details['payslip_year']=$payslip_year;
                $pay_slip_details['payslip_month']=$payslip_month;


                 $all_statutorys = $this->db->get_where('bank_statutory',array('user_id'=>$payroll_user_id[$p]))->result_array(); 

                 foreach ($all_statutorys as $all_statutory) {

                     $addtional_ar = json_decode($all_statutory['pf_addtional'],TRUE);
                     $deduction_ar = json_decode($all_statutory['pf_deduction'],TRUE);

                     $pay_slip_details['addtion||basic_pay']=$all_statutory['salary'];

                     if(is_array($addtional_ar))
                     {
                        foreach ($addtional_ar as $key => $value) 
                        {
                            $pay_slip_details['addtion||'.$value['addtion_name']]=$value['unit_amount'];
                        }
                     }

                     if(is_array($deduction_ar))
                     {
                        foreach ($deduction_ar as $key => $values) 
                        {
                            $pay_slip_details['deduction||'.$values['model_name']]=$values['unit_amount'];
                        }
                     }
                    
                 }
                


                 $bank_details = $this->db->get_where('bank_statutory',array('user_id'=>$payroll_user_id[$p]))->row_array(); 

                        $pf_details = json_decode($bank_details['bank_statutory'],TRUE);
                        if($pf_details['pf_contribution'] == 'yes')
                        {
                            $pf_amount = $pf_details['pf_total_rate'];
                        }else{
                            $pf_amount = '';
                        }

                        if($pf_details['esi_contribution'] == 'yes')
                        {
                            $esi_amount = $pf_details['esi_total_rate'];
                        }else{
                            $esi_amount = '';
                        } 

                $total_leaves =  $this->db->get_where('user_leaves',array('user_id'=>$payroll_user_id[$p],'status'=>1))->row_array();
                        $lop = ($total_leaves['leave_days'] - 12);
                        if($lop > 0)
                        {
                            $lop_leaves = $lop;
                        }else{
                            $lop_leaves = '';
                        }

                        $total_salary = $all_statutory['salary'];
                        $one_day = ($total_salary / 22);
                        $total_lop = ($one_day * $lop_leaves);


             $over_time = $this->db->query('SELECT * FROM  dgt_overtime WHERE user_id ='.$payroll_user_id[$p].' AND status =1 AND  Month(ot_date)='.$payslip_month.' && YEAR(ot_date)='.$payslip_year.'')->result_array(); 

             if(!empty( $over_time))
             {
                $overtime=array();
                 foreach ($over_time as $o_row)
                  {
                     $overtime[]=$o_row['ot_hours'];
                  }

                  $time = 0;
                    //$time_arr =  array("00:30","01:15");
                     foreach ($overtime as $time_val) {
                        $time +=explode_time($time_val); // this fucntion will convert all hh:mm to seconds
                    }

                     
                     $pay_slip_details['addtion||over_time']=second_to_hhmm($time)*round($one_day/8);
                      

              }


              $tds=$this->db->query('SELECT salary_percentage FROM dgt_tds_settings WHERE `salary_from` <= '.$all_statutory['salary'].' AND `salary_to` >= '.$all_statutory['salary'].'')->row_array();


                $pay_slip_details['deduction||TDS']=(($all_statutory['salary']*$tds['salary_percentage'])/100);
                $pay_slip_details['deduction||ESI']=$esi_amount;
                $pay_slip_details['deduction||PF']=$pf_amount;
                $pay_slip_details['deduction||leave']=round($total_lop);




                $pay_slip_details['payslip_user_id']=$payroll_user_id[$p];



                $array = array();
                $array['user_id'] = $pay_slip_details['payslip_user_id'];

                $array['p_year'] = $pay_slip_details['payslip_year'];

                $array['p_month'] = $pay_slip_details['payslip_month'];



                $this->db->where($array);

                $payslip_count = $this->db->count_all_results('payslip');

                

                if($payslip_count == 0){

                    $array['payslip_details'] = json_encode($pay_slip_details);

                    $this->db->insert('payslip', $array);

                      $result=($this->db->affected_rows()!= 1)? false:true;


                }else{

                    $array1['payslip_details'] = json_encode($pay_slip_details);

                    $this->db->where($array);

                    $this->db->update('payslip', $array1);

                      $result=($this->db->affected_rows()!= 1)? false:true;

                }
                



            }


            if(!empty($result==true)){
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
    
     public function language_list_get()
    {   
   
    $result = $this->api->get_languages();
    
    
    foreach($result as $res)
    {
        $temp = [];
        $temp['code'] = $res->code;
        $temp['name'] = ucfirst($res->name);
        $temp['icon'] = $res->icon;
        $temp['active'] = $res->active;
        $results[] = $temp;
       
    
    }

    if($result){ 
      $response['status_code'] = 1;
      $response['message'] = $this->success;
      $response['data'] = $results;
    } else{ 
      $response['status_code'] = 0;
      $response['message'] = $this->no_result_found; 
    }
    $this->response($response, REST_Controller::HTTP_OK);
    }



     public function translate_list_post()
    {
         

         //Get the selected language
        
         $lang_code = $this->input->post();
         $language = $lang_code['code'];

         $lang = $this->api->get_lang($language);
         foreach($lang as $lang) {
         $code_lang = $lang->name;
         }
         
         $file_data = $this->lang->load('fx_lang',$code_lang);

         if($file_data != '1' && $language != "en")
         {
          
          $response['status_code'] = 0;
          $response['message'] = 'File has not been updated.Please update'; 
          $response['data'] = [];
          
          $this->response($response, REST_Controller::HTTP_OK);
         }
        
         else 
         {
         if($language == "aa") {
            $this->lang->load('fx_lang','afar');
         }
         else if($language == "cs") {
            $this->lang->load('fx_lang','czech');
           
         }
         else if($language == "da") {

            $this->lang->load('fx_lang','danish');
         }
         else if($language == "de") {
            $this->lang->load('fx_lang','german');
         }
         else if($language == "el") {
            $this->lang->load('fx_lang','greek');
         }
         else if($language == "es") {
            $this->lang->load('fx_lang','spanish');
         }
         else if($language == "fr") {
            $this->lang->load('fx_lang','french');
         }
         else if($language == "hr") {
            $this->lang->load('fx_lang','croatian');
         }
         else if($language == "it") {
            $this->lang->load('fx_lang','italian');
         }
         else if($language == "nl") {
            $this->lang->load('fx_lang','dutch');
         }
         else if($language == "no") {
            $this->lang->load('fx_lang','norwegian');
         }
         else if($language == "pt") {
            $this->lang->load('fx_lang','portuguese');
         }
         else if($language == "pt-br") {
            $this->lang->load('fx_lang','portuguese-brazilian');
         }
         else if($language == "ro") {
            $this->lang->load('fx_lang','romanian');
         }
         else if($language == "ru") {
            $this->lang->load('fx_lang','russian');
         }
         else if($language == "tr") {
            $this->lang->load('fx_lang','turkish');
         }
         else if($language == "en") {
         $this->lang->load('fx_lang','english'); 
         } 
          }
         
        //  $doj_date = $this->lang->line('doj_date');
        //  $clients = $this->lang->line('clients');
        //  $projects = $this->lang->line('projects');
        //  $chat = $this->lang->line('chat');
        //  $call = $this->lang->line('call');
        //  $deadline = $this->lang->line('deadline');
        //  $project_leader = $this->lang->line('project_leader');
        //  $team = $this->lang->line('team');
        //  $project_code = $this->lang->line('project_code');
        //  $linkedin = $this->lang->line('linkedin');
        //  $skype = $this->lang->line('skype');
        //  $facebook = $this->lang->line('facebook');
        //  $twitter = $this->lang->line('twitter');
        //  $designation_name = $this->lang->line('designation_name');
        //  $create_designation = $this->lang->line('create_designation');
        //  $add_holiday = $this->lang->line('add_holiday');
        //  $holiday_name = $this->lang->line('holiday_name');
        //  $holiday_date = $this->lang->line('holiday_date');
        //  $create_holiday = $this->lang->line('create_holiday');
        //  $add_leave_request = $this->lang->line('add_leave_request');
        //  $annual_leave = $this->lang->line('annual_leave');
        //  $total_leaves = $this->lang->line('total_leaves');
        //  $select_leave_type = $this->lang->line('select_leave_type');
        //  $leave_from = $this->lang->line('leave_from');
        //  $leave_to = $this->lang->line('leave_to');
        //  $leave_reason = $this->lang->line('leave_reason');
        //  $add_timesheet = $this->lang->line('add_timesheet');
        //  $basic_information = $this->lang->line('basic_information');
        //  $contact_information = $this->lang->line('contact_information');
        //  $company_mobile = $this->lang->line('company_mobile');
        //  $back = $this->lang->line('back');
        //  $web_information = $this->lang->line('web_information');
        //  $bank_information = $this->lang->line('bank_information');
        //  $swift_bic = $this->lang->line('swift_bic');
        //  $sort_code = $this->lang->line('sort_code');
         
         
        //  if($doj_date == '')
        //  {
        //     $doj_date = 'Doj date';
        //  }
        //  else
        //  {
        //     $doj_date = $this->lang->line('doj_date');
        //  }

        //  if($clients == '')
        //  {
        //     $clients = 'Clients';
        //  }
        //  else
        //  {
        //     $clients = $this->lang->line('clients');
        //  }

        //  if($chat == '')
        //  {
        //     $chat = 'Chat';
        //  }
        //  else
        //  {
        //     $chat;
        //  }

        //  if($call == '')
        //  {
        //     $call = 'Call';
        //  }
        //  else
        //  {
        //     $call;
        //  }
        //  if($deadline == '')
        //  {
        //     $deadline = 'Deadline';
        //  }
        //  else
        //  {
        //     $deadline = $deadline;
        //  }

        //  if($project_leader == '')
        //  {
        //     $project_leader = 'Project leader';
        //  }
        //  else
        //  {
        //     $project_leader = $project_leader;
        //  }

        //  if($team == '')
        //  {
        //     $team = 'Team';
        //  }
        //  else
        //  {
        //     $team = $team;
        //  }
        //  if($linkedin == '')
        //  {
        //     $linkedin = 'Linkedin';
        //  }
        //  else
        //  {
        //     $linkedin = $linkedin;
        //  }
        //  if($skype == '')
        //  {
        //     $skype = 'Skype';
        //  }
        //  else
        //  {
        //     $skype = $skype;
        //  }
        //  if($facebook == '')
        //  {
        //     $facebook = 'facebook';
        //  }
        //  else
        //  {
        //     $facebook = $facebook;
        //  }
        //  if($twitter == '')
        //  {
        //     $twitter = 'Twitter';
        //  }
        //  else
        //  { 
        //     $twitter = $twitter;
        //  }
        //   if($create_designation == '')
        //  {
        //     $create_designation = 'Create Designation';
        //  }
        //  else
        //  { 
        //     $create_designation = $create_designation;
        //  }
        //  if($designation_name == '')
        //  {
        //     $designation_name = 'Designation Name';
        //  }
        //  else
        //  { 
        //     $designation_name = $designation_name;
        //  }
        //  if($add_holiday == '')
        //  {
        //     $add_holiday = 'Add Holiday';
        //  }
        //  else
        //  { 
        //     $add_holiday = $add_holiday;
        //  }
        //  if($holiday_name == '')
        //  {
        //     $holiday_name = 'Holiday Name';
        //  }
        //  else
        //  { 
        //     $holiday_name = $holiday_name;
        //  }
        //  if($holiday_date == '')
        //  {
        //     $holiday_date = 'Holiday Date';
        //  }
        //  else
        //  { 
        //     $holiday_date = $holiday_date;
        //  }
        //   if($create_holiday == '')
        //  {
        //     $create_holiday = 'Create Holiday';
        //  }
        //  else
        //  { 
        //     $create_holiday = $create_holiday;
        //  }
        //   if($add_leave_request == '')
        //  {
        //     $add_leave_request = 'Add Leave Request';
        //  }
        //  else
        //  { 
        //     $add_leave_request = $add_leave_request;
        //  }
        //   if($annual_leave == '')
        //  {
        //     $annual_leave = 'Annual Leave';
        //  }
        //  else
        //  { 
        //     $annual_leave = $annual_leave;
        //  }
        //   if($total_leaves == '')
        //  {
        //     $total_leaves = 'Total Leaves';
        //  }
        //  else
        //  { 
        //     $total_leaves = $total_leaves;
        //  }
        //  if($select_leave_type == '')
        //  {
        //     $select_leave_type = 'Select Leave Type';
        //  }
        //  else
        //  { 
        //     $select_leave_type = $select_leave_type;
        //  }
        //   if($leave_from == '')
        //  {
        //     $leave_from = 'Leave From';
        //  }
        //  else
        //  { 
        //     $leave_from = $leave_from;
        //  }
        //  if($leave_to == '')
        //  {
        //     $leave_to = 'Leave To';
        //  }
        //  else
        //  { 
        //     $leave_to = $leave_to;
        //  }
        //  if($leave_reason == '')
        //  {
        //     $leave_reason = 'Leave Reason';
        //  }
        //  else
        //  { 
        //     $leave_reason = $leave_reason;
        //  }
        //  if($add_timesheet == '')
        //  {
        //     $add_timesheet = 'Add Timesheet';
        //  }
        //  else
        //  { 
        //     $add_timesheet = $add_timesheet;
        //  }
        //  if($basic_information == '')
        //  {
        //     $basic_information = 'Basic Information';
        //  }
        //  else
        //  { 
        //     $basic_information = $basic_information;
        //  }
        //  if($contact_information == '')
        //  {
        //     $contact_information = 'Contact Information';
        //  }
        //  else
        //  { 
        //     $contact_information = $contact_information;
        //  }
        //  if($company_mobile == '')
        //  {
        //     $company_mobile = 'Company Mobile';
        //  }
        //  else
        //  { 
        //     $company_mobile = $company_mobile;
        //  }
        //   if($back == '')
        //  {
        //     $back = 'Back';
        //  }
        //  else
        //  { 
        //     $back = $back;
        //  }
        //   if($web_information == '')
        //  {
        //     $web_information = 'Web Information';
        //  }
        //  else
        //  { 
        //     $web_information = $web_information;
        //  }
        //   if($bank_information == '')
        //  {
        //     $bank_information = 'Bank Information';
        //  }
        //  else
        //  { 
        //     $bank_information = $bank_information;
        //  }
        //   if($swift_bic == '')
        //  {
        //     $swift_bic = 'SWIFT/BIC';
        //  }
        //  else
        //  { 
        //     $swift_bic = $swift_bic;
        //  }
        //  if($sort_code == '')
        //  {
        //     $sort_code = 'Sort Code';
        //  }
        //  else
        //  { 
        //     $sort_code = $sort_code;
        //  }



         
         $result = array(

         'create_project'=> $this->lang->line('create_project'),
         'delete_project'=> $this->lang->line('delete_project'),
         'company_name' => $this->lang->line('company_name'),
         'company_email' => $this->lang->line('company_email'),
         'vat' => $this->lang->line('vat'),
         'notes' => $this->lang->line('notes'),
         'next' => $this->lang->line('next'),
         'email' => $this->lang->line('email'),
         'filter' => $this->lang->line('filter'),
         'full_name' => $this->lang->line('full_name'),
         'username' => $this->lang->line('username'),
         'password' => $this->lang->line('password'),
         'phone' => $this->lang->line('phone'),
         'select_department' => $this->lang->line('select_department'),
         'doj_date' =>  $this->lang->line('doj_date'),
         'clients' =>   $this->lang->line('clients'),
         'projects' =>  $this->lang->line('projects'),
         'tasks' => $this->lang->line('tasks'),
         'chat' => $this->lang->line('chat'),
         'call' => $this->lang->line('call'),
         'account' => $this->lang->line('account'),
         'payroll' => $this->lang->line('payroll'),
         'settings' => $this->lang->line('settings'),
         'timesheets' => $this->lang->line('timesheets'),
         'overview' => $this->lang->line('overview'),
         'calendar' => $this->lang->line('calendar'),
         'description' => $this->lang->line('description'),
         'deadline' => $this->lang->line('deadline'),
         'project_leader' =>  $this->lang->line('project_leader'),
         'team' => $this->lang->line('team'),
         'progress' => $this->lang->line('progress'),
         'project_title' => $this->lang->line('project_title'),
         'project_code' => $this->lang->line('project_code'),
         'client' => $this->lang->line('client'),
         'fixed_rate' => $this->lang->line('fixed_rate'),
         'hourly_rate' => $this->lang->line('hourly_rate'),
         'estimated_hours' => $this->lang->line('estimated_hours'),
         'estimated_hours' => $this->lang->line('estimated_hours'),
         'company_phone' => $this->lang->line('company_phone'),
         'fax' => $this->lang->line('fax'),
         'address' => $this->lang->line('address'),
         'city' => $this->lang->line('city'),
         'zip_code' => $this->lang->line('zip_code'),
         'state_province' => $this->lang->line('state_province'),
         'country' => $this->lang->line('country'),
         'website' => $this->lang->line('website'),
         'linkedin' => $this->lang->line('linkedin'),
         'skype' => $this->lang->line('skype'),
         'facebook' => $this->lang->line('facebook'),
         'twitter' => $this->lang->line('twitter'),
         'bank' => $this->lang->line('bank'),
         'account_holder' => $this->lang->line('account_holder'),
         'hosting_company' => $this->lang->line('hosting_company'),
         'hosting' => $this->lang->line('hosting'),
         'account_username' => $this->lang->line('account_username'),
         'port' => $this->lang->line('port'),
         'submit' => $this->lang->line('submit'),
         'item' => $this->lang->line('item'),
         'quantity' => $this->lang->line('quantity'),
         'unit_price' => $this->lang->line('unit_price'),
         'tax' => $this->lang->line('tax'),
         'add_item' => $this->lang->line('add_item'),
         'sub_total' => $this->lang->line('sub_total'),
         'discount' => $this->lang->line('discount'),
         'total' => $this->lang->line('total'),
         'create_invoice' => $this->lang->line('create_invoice'),
         'start_date' => $this->lang->line('start_date'),
         'end_date' => $this->lang->line('end_date'),
         'status' => $this->lang->line('status'),
         'search_invoice' => $this->lang->line('search_invoice'),
         'employee_id' => $this->lang->line('employee_id'),
         'cancel' => $this->lang->line('cancel'),
         'expenses' => $this->lang->line('expenses'),
         'create_expense' => $this->lang->line('create_expense'),
         'amount' => $this->lang->line('amount'),
         'show_to_client' => $this->lang->line('show_to_client'),
         'project' => $this->lang->line('project'),
         'category' => $this->lang->line('category'),
         'due_date' => $this->lang->line('due_date'),
         'billable' => $this->lang->line('billable'),
         'invoiced' => $this->lang->line('invoiced'),
         'change_password' => $this->lang->line('change_password'),
         'old_password' => $this->lang->line('old_password'),
         'new_password' => $this->lang->line('new_password'),
         'confirm_password' => $this->lang->line('confirm_password'),
         'total_earnings' => $this->lang->line('total_earnings'),
         'expiry_date' => $this->lang->line('expiry_date'),
         'received_from' => $this->lang->line('received_from'),
         'estimate_status' => $this->lang->line('estimate_status'),
         'price' => $this->lang->line('price'),
         'payment_made' => $this->lang->line('payment_made'),
         'due_amount' => $this->lang->line('due_amount'),
         'assigned_to' => $this->lang->line('assigned_to'),
         'department' => $this->lang->line('department'),
         'department_name' => $this->lang->line('department_name'),
         'add_designation' => 'Add Designation',
         'designation_name' => $this->lang->line('designation_name'),
         'create_designation' =>  $this->lang->line('create_designation'),
         'add_holiday' => $this->lang->line('add_holiday'),
         'holiday_name' => $this->lang->line('holiday_name'),
         'holiday_date' => $this->lang->line('holiday_date'),
         'create_holiday' => $this->lang->line('create_holiday'),
         'add_leave_request' => $this->lang->line('add_leave_request'),
         'annual_leave' => $this->lang->line('annual_leave'),
         'total_leaves' => $this->lang->line('total_leaves'),
         'select_leave_type' => $this->lang->line('select_leave_type'),
         'leave_from' => $this->lang->line('leave_from'),
         'leave_to' => $this->lang->line('leave_to'),
         'leave_reason' => $this->lang->line('leave_reason'),
         'add_timesheet' => $this->lang->line('add_timesheet'),
         'date' => $this->lang->line('date'),
         'hours' => $this->lang->line('hours'),
         'basic_information' => $this->lang->line('basic_information'),
         'contact_information' => $this->lang->line('contact_information'),
         'company_mobile' => $this->lang->line('company_mobile'),
         'back' => $this->lang->line('back'),
         'web_information' => $this->lang->line('web_information'),
         'bank_information' => $this->lang->line('bank_information'),
         'swift_bic' => $this->lang->line('swift_bic'),
         'sort_code' => $this->lang->line('sort_code'),
         'iban' => $this->lang->line('iban'),
         'host_informations' => $this->lang->line('host_informations'),
         'add_employee' => $this->lang->line('add_employee'),
         'select_designation' => $this->lang->line('select_designation'),
         'reporting_to' => $this->lang->line('reporting_to'),
         'create_employee' => $this->lang->line('create_employee'),
         'attendance_info' => $this->lang->line('attendance_info'),
         'attendance' => $this->lang->line('attendance'),
         'timesheet' => $this->lang->line('timesheet'),
         'punch_in_at' => $this->lang->line('punch_in_at'),
         'punch_in' => $this->lang->line('punch_in'),
         'punch_out' => $this->lang->line('punch_out'),
         'production' => $this->lang->line('production'),
         'break' => $this->lang->line('break'),
         'overtime' => $this->lang->line('overtime'),
         'statistics' => $this->lang->line('statistics'),
         'today' => $this->lang->line('today'),         
         'this_week' => $this->lang->line('this_week'),   
         'this_month' => $this->lang->line('this_month'),  
         'remaining_hours' => $this->lang->line('remaining_hours'),  
         'activity' => $this->lang->line('activity'),  
         'punch_out_at' => $this->lang->line('punch_out_at'),  
         'employee' => $this->lang->line('employee'),
         'change_language' => $this->lang->line('change_language'),
         'select_language' => $this->lang->line('select_language'),
         'update_password' => $this->lang->line('update_password'),
         'no_message' => $this->lang->line('no_message'),
         'send' => $this->lang->line('send'),
         'chats' => $this->lang->line('chats'),
         'group_chats' => $this->lang->line('group_chats'),
         'individual_chats' => $this->lang->line('individual_chats'),
         'profile' => $this->lang->line('profile'),
         'invoices' => $this->lang->line('invoices'),
         'estimates' => $this->lang->line('estimates'),
         'client_id' => $this->lang->line('client_id'),
         'contact_person' => $this->lang->line('contact_person'),
         'additional_fields' => $this->lang->line('additional_fields'),
         'paid' => $this->lang->line('paid'),
         'unpaid' => $this->lang->line('unpaid'),
         'pending' => $this->lang->line('pending'),
         'accepted' => $this->lang->line('accepted'),
         'rejected' => $this->lang->line('rejected'),
         'open_tasks' => $this->lang->line('open_tasks'),
         'tasks_completed' => $this->lang->line('tasks_completed'),
         'filter_clients' => $this->lang->line('filter_clients'),
         'duedate' => $this->lang->line('duedate'),
         'tax_total_cost' => $this->lang->line('tax_total_cost'),
         'create_estimate' => $this->lang->line('create_estimate'),
         'select_image_any' => $this->lang->line('select_image_any'),
         'select_image' => $this->lang->line('select_image'),
         'invoice_code' => $this->lang->line('invoice_code'),
         'extra_fee' => $this->lang->line('extra_fee'),
         'allow_stripe' => $this->lang->line('allow_stripe'),
         'assign_lead' => $this->lang->line('assign_lead'),
         'fixed_price' => $this->lang->line('fixed_price'),
         'create_task' => $this->lang->line('create_task'),
         'task_title' => $this->lang->line('task_title'),
         'departments' => $this->lang->line('departments'),
         'designations' => $this->lang->line('designations'),
         'edit_profile' => $this->lang->line('edit_profile'),
         'birth_date' => $this->lang->line('birth_date'),
         'gender' => $this->lang->line('gender'),
         'education_information' => $this->lang->line('education_information'),
         'information' => $this->lang->line('information'),
         'institution' => $this->lang->line('institution'),
         'subject' => $this->lang->line('subject'),
         'starting_year' => $this->lang->line('starting_year'),
         'complete_year' => $this->lang->line('complete_year'),
         'degree' => $this->lang->line('degree'),
         'grade' => $this->lang->line('grade'),
         'add_more_education' => $this->lang->line('add_more_education'),
         'experience_information' => $this->lang->line('experience_information'),
         'location' => $this->lang->line('location'),
         'job_position' => $this->lang->line('job_position'),
         'period_from' => $this->lang->line('period_from'),
         'period_to' => $this->lang->line('period_to'),
         'add_experience' => $this->lang->line('add_experience'),
         'employees' => $this->lang->line('employees'),
         'edit_employee' => $this->lang->line('edit_employee'),
         'salary' => $this->lang->line('salary'),
         'pay_run' => $this->lang->line('pay_run'),
         'created_date' => $this->lang->line('created_date'),
         'estimate_to' => $this->lang->line('estimate_to'),
         'estimate_date' => $this->lang->line('estimate_date'),
         'edit_timesheet' => $this->lang->line('edit_timesheet'),
         'completed' => $this->lang->line('completed'),
         'add_new_task' => $this->lang->line('add_new_task'),
         'salary_month' => $this->lang->line('salary_month'),
         'earnings' => $this->lang->line('earnings'),
         'basic_salary' => $this->lang->line('basic_salary'),
         'house_rent_da' => $this->lang->line('house_rent_da'),
         'house_rent_hra' => $this->lang->line('house_rent_hra'),
         'conveyance' => $this->lang->line('conveyance'),
         'allowance' => $this->lang->line('allowance'),
         'medical_allowance' => $this->lang->line('medical_allowance'),
         'other_allowance' => $this->lang->line('other_allowance'),
         'deductions' => $this->lang->line('deductions'),
         'tds' => $this->lang->line('tds'),
         'pf' => $this->lang->line('pf'),
         'esi' => $this->lang->line('esi'),
         'leave' => $this->lang->line('leave'),
         'welfare' => $this->lang->line('welfare'),
         'professional_tax' => $this->lang->line('professional_tax'),
         'fund' => $this->lang->line('fund'),
         'other_deductions' => $this->lang->line('other_deductions'),
         'total_deductions' => $this->lang->line('total_deductions'),
         'net_salary' => $this->lang->line('net_salary'),
         'payment_received_subject' => $this->lang->line('payment_received_subject'),
         'payment_date' => $this->lang->line('payment_date'),
         'transaction_id' => $this->lang->line('transaction_id'),
         'payment_mode' => $this->lang->line('payment_mode'),
         'currency' => $this->lang->line('currency'),
         'invoice_to' => $this->lang->line('invoice_to'),
         'sick_leave' => $this->lang->line('sick_leave'),
         'delete' => $this->lang->line('delete'),
         'edit' => $this->lang->line('edit'),
         'holiday' => $this->lang->line('holiday'),
         'cancelled' => $this->lang->line('cancelled'),
         'payslip' => $this->lang->line('payslip'),
         'payments' => $this->lang->line('payments'),
         'payrun' => $this->lang->line('payrun'),
         
         
         );





          if($result){ 
          $response['status_code'] = 1;
          $response['message'] = $this->success;
          $response['data'] = array($result);
          } else{ 
          $response['status_code'] = 0;
          $response['message'] = $this->no_result_found; 
          }
          $this->response($response, REST_Controller::HTTP_OK);
         
      
   }






    




}
?>
