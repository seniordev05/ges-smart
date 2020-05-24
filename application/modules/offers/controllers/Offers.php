<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Offers extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        User::logged_in();

        $this->load->library(array('form_validation'));
        $this->load->model(array('Client', 'App', 'Invoice', 'Expense', 'Project', 'Payment', 'Estimate','Offer'));
        $all_routes = $this->session->userdata('all_routes');
        //echo '<pre>'; print_r($all_routes); exit;
        foreach($all_routes as $key => $route){
            if($route == 'offers'){
                $routname = offers;
            } 
            
        }
        // if (!User::is_admin())
        
        if(empty($routname)){
            // $this->session->set_flashdata('message', lang('access_denied'));
            // $this->session->set_flashdata('tokbox_error', lang('access_denied'));
            // redirect('');
        }
        $this->load->helper(array('inflector'));
        $this->applib->set_locale();
    }

    public function index()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('offer_page_title'));
        $data['page'] = lang('offer_dashboard');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();

        // $data['companies'] = Client::get_all_clients();

        $data['countries'] = App::countries();

 
        $applicant_status= Offer::get_CandStatus($inprogress); 

        foreach ($applicant_status as $apk => $apv) 
        {
                // $offer_approvers_status   = $this->db->get_where('offer_approvers',array('status'=>1,'offer'=>$apv['id']))->num_rows();
                //             if($offer_approvers_status != 0){
                //               $status = 1;
                //             }else {

                //                 $offer_status   = $this->db->get_where('offers',array('id'=>$apv['id']))->row_array();
                //                 $status = $offer_status['offer_status'];
                                
                //             }


            if($apv['offer_status']==2){
                 $data['ready'][]= array(
                    'title' => $apv['title'], 
                    'email' => $apv['email'], 
                    'job_type' => $apv['job_type'], 
                    'name' => $apv['candidate'],
                    'id' => $apv['id'],
                    // 'caid' => $apv['caid'],
                    'offer_id' => $apv['id']

                );

            }
            elseif($apv['offer_status']==3){
                $data['send'][]=array(
                    'title' => $apv['title'], 
                    'email' => $apv['email'], 
                    'job_type' => $apv['job_type'], 
                    'name' => $apv['candidate'],
                    'id' => $apv['id'],
                    // 'caid' => $apv['caid'],
                    'offer_id' => $apv['id']
                );
            }
            elseif($apv['offer_status']==4){
               $data['accept'][]= array(
                    'title' => $apv['title'], 
                    'email' => $apv['email'], 
                    'job_type' => $apv['job_type'], 
                    'name' => $apv['candidate'],
                    'id' => $apv['id'],
                     // 'caid' => $apv['caid'],
                    'offer_id' => $apv['id']
                );
            } 
            elseif($apv['offer_status']==5){
               $data['declined'][]= array(
                    'title' => $apv['title'], 
                    'email' => $apv['email'], 
                    'job_type' => $apv['job_type'], 
                    'name' => $apv['candidate'],
                    'id' => $apv['id'],
                     // 'caid' => $apv['caid'],
                    'offer_id' => $apv['id']
                );
            }
            elseif($apv['offer_status']==6){
                $data['archived'][]=array(
                    'title' => $apv['title'], 
                    'email' => $apv['email'], 
                    'job_type' => $apv['job_type'], 
                    'name' => $apv['candidate'],
                    'id' => $apv['id'],
                     // 'caid' => $apv['caid'],
                    'offer_id' => $apv['id']
                );
            } 
            else{
                 $data['inprogress'][]=array(
                    'title' => $apv['title'], 
                    'email' => $apv['email'], 
                    'job_type' => $apv['job_type'], 
                    'name' => $apv['candidate'],
                    'id' => $apv['id'],
                    // 'caid' => $apv['caid'],
                    'offer_id' => $apv['id']
                );
            }
            
        }
         
        // echo "<pre>";print_r($data); exit;
        $this->template
                ->set_layout('users')
                ->build('offers', isset($data) ? $data : null);
    }

    public function view($company = null)
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('companies').' - '.config_item('company_name'));
        $data['page'] = lang('companies');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['editor'] = true;
        $data['tab'] = ($this->uri->segment(4) == '') ? 'dashboard' : $this->uri->segment(4);
        $data['company'] = $company;

        $this->template
        ->set_layout('users')
        ->build('view', isset($data) ? $data : null);
    }

    public function create()
    {
        if ($this->input->post()) {
            // print_r($this->input->post());exit;
            // print_r($this->session->all_userdata()); exit;
            // $custom_fields = array();
            // foreach ($_POST as $key => &$value) {
            //     if (strpos($key, 'cust_') === 0) {
            //         $custom_fields[$key] = $value;
            //         unset($_POST[$key]);
            //     }
            // }
            // $this->form_validation->set_rules('title', 'title', 'required');
            // $this->form_validation->set_rules('job_type', 'Job Type', 'required');
            // $this->form_validation->set_rules('Salary   ', 'Salary', 'required');

            // if ($this->form_validation->run() == false) {
            //     $_POST = '';
            //     // echo "<pre>";print_r("df"); exit;
            //             // $errors = validation_errors();
            //             // Applib::go_to('companies', 'error', lang('error_in_form'));
            //             $this->session->set_flashdata('tokbox_error', lang('error_in_form'));
            //             redirect('offers');
            // } else {
                $_POST['user_id'] = $this->session->userdata('user_id');
                $offer_approvers = serialize($this->input->post('offer_approvers'));
                $_POST['offer_approvers'] = $offer_approvers;

                  

                $offer_id = Offer::save($this->input->post(null, true));

                $approvers = unserialize($offer_approvers);
                $user_mail= Offer::usersMailid($boardsid);
                if (count($approvers) > 0) {
                    foreach ($approvers as $key => $value) {
                        $approvers_details = array(
                            'approvers' => $value,
                            'offer' => $offer_id,
                            'status' => $this->input->post('status'),
                            'created_by'=>$this->session->userdata('user_id'),
                            //'lt_incentive_plan' => ($this->input->post('long_term_incentive_plan')?1:0),

                            );//print_r($approvers_details);exit;
                        Offer::save_offer_approvers($approvers_details);

                        $args = array(
                            'user' => $value,
                            'module' => 'Offers',
                            'module_field_id' => $offer_id,
                            'activity' => 'Created new offer',
                            'icon' => 'fa-user',
                            'value1' => $this->input->post('title', true),
                        );
                App::Log($args);


                    $subject = 'Offer approval';
                    $message = 'Offer approval Request';
                    foreach ($user_mail as $key => $u) 
                    {
                        
                        $params['recipient'] = $u['email'];
                        $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
                        $params['message'] = $message;
                        $params['attached_file'] = '';
                        modules::run('fomailer/send_email',$params);
                    }

                    }
                }
                  // echo $this->db->last_query(); exit;

                // foreach ($custom_fields as $key => $f) {
                //     $key = str_replace('cust_', '', $key);
                //     $r = $this->db->where(array('client_id'=>$company_id,'meta_key'=>$key))->get('formmeta');
                //     $cf = $this->db->where('name',$key)->get('fields');
                //     $data = array(
                //         'module'    => 'clients',
                //         'field_id'  => $cf->row()->id,
                //         'client_id' => $company_id,
                //         'meta_key'  => $cf->row()->name,
                //         'meta_value'    => is_array($f) ? json_encode($f) : $f
                //     );
                //     ($r->num_rows() == 0) ? $this->db->insert('formmeta',$data) : $this->db->where(array('client_id'=>$company_id,'meta_key'=>$cf->row()->name))->update('formmeta',$data);
                // }

                $args = array(
                            'user' => User::get_id(),
                            'module' => 'Offers',
                            'module_field_id' => $offer_id,
                            'activity' => 'Created new offer',
                            'icon' => 'fa-user',
                            'value1' => $this->input->post('title', true),
                        );
                App::Log($args);

                // $this->session->set_flashdata('response_status', 'success');
                // $this->session->set_flashdata('message', lang('client_registered_successfully'));
                $this->session->set_flashdata('tokbox_success', lang('offer_created_successfully'));
                redirect('offers');
            // }
        } else {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('create_offer'));
        $data['page'] = lang('create_offer');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();

        // $data['companies'] = Client::get_all_clients();

        $data['countries'] = App::countries();
        $this->template
                ->set_layout('users')
                ->build('create', isset($data) ? $data : null);
        }
    }

    public function check_candidate()
    {
        $candidate = $this->input->post('candidate');
        $check_candidate = Offer::check_candidate($candidate);
        if($check_candidate > 0)
        {
            echo "yes";
        }else{
            echo "no";
        }
        exit;
    }
    

    public function update($company = null)
    {
        if ($this->input->post()) {

            $custom_fields = array();
            foreach ($_POST as $key => &$value) {
                if (strpos($key, 'cust_') === 0) {
                    $custom_fields[$key] = $value;
                    unset($_POST[$key]);
                }
            }
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<span style="color:red">', '</span><br>');
            $this->form_validation->set_rules('company_ref', 'Company ID', 'required');
            $this->form_validation->set_rules('company_name', 'Company Name', 'required');
            $this->form_validation->set_rules('company_email', 'Company Email', 'required|valid_email');

            if ($this->form_validation->run() == false) {
                // $this->session->set_flashdata('response_status', 'error');
                // $this->session->set_flashdata('message', lang('error_in_form'));
                $this->session->set_flashdata('tokbox_error', lang('error_in_form'));
                $company_id = $_POST['co_id'];
                $_POST = '';
                redirect('companies/view/'.$company_id);
            } else {
                $company_id = $_POST['co_id'];

                foreach ($custom_fields as $key => $f) {
                    $key = str_replace('cust_', '', $key);
                    $r = $this->db->where(array('client_id'=>$company_id,'meta_key'=>$key))->get('formmeta');
                    $cf = $this->db->where('name',$key)->get('fields');
                    $data = array(
                        'module'    => 'clients',
                        'field_id'  => $cf->row()->id,
                        'client_id' => $company_id,
                        'meta_key'  => $cf->row()->name,
                        'meta_value'    => is_array($f) ? json_encode($f) : $f
                    );
                    ($r->num_rows() == 0) ? $this->db->insert('formmeta',$data) : $this->db->where(array('client_id'=>$company_id,'meta_key'=>$cf->row()->name))->update('formmeta',$data);
                }

                $_POST['company_website'] = prep_url($_POST['company_website']);
                Client::update($company_id, $this->input->post());

                $args = array(
                            'user' => User::get_id(),
                            'module' => 'Clients',
                            'module_field_id' => $company_id,
                            'activity' => 'activity_updated_company',
                            'icon' => 'fa-edit',
                            'value1' => $this->input->post('company_name', true),
                        );
                App::Log($args);

                // $this->session->set_flashdata('response_status', 'success');
                // $this->session->set_flashdata('message', lang('client_updated'));
                $this->session->set_flashdata('tokbox_success', lang('client_updated'));
                redirect('companies/view/'.$company_id);
            }
        } else {
            $data['company'] = $company;
            $this->load->view('modal/edit', $data);
        }
    }


    
            // Delete Company
    public function delete()
    {
        if ($this->input->post()) {
            $company = $this->input->post('company', true);

            Client::delete($company);

            // $this->session->set_flashdata('response_status', 'success');
            // $this->session->set_flashdata('message', lang('company_deleted_successfully'));
            $this->session->set_flashdata('tokbox_success', lang('company_deleted_successfully'));
            redirect('companies');
        } else {
            $data['company_id'] = $this->uri->segment(3);
            $this->load->view('modal/delete', $data);
        }
    }

    public function get_approvers()
    {
        $approvers = User::team();

        

         $data=array();
            foreach($approvers as $r)
            {
                $data['value']=$r->id;
                $data['label']=ucfirst($r->username);
                $json[]=$data;
                
                
            }
        echo json_encode($json);
        exit;
    }


    public function offers_list()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        // $this->template->title(lang('offer_approval_process'));
        $this->template->title('Offers List');
        $data['page'] = lang('offer_list');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
       // $data['offer_list'] = $this->_getOfferlist();
        $data['offer_list'] = Offer::get_CandStatus();
        // echo " <pre>"; print_r($data['offer_list']); exit;
         $data['offer_jobtype'] = $this->_getOfferjob();


        $data['countries'] = App::countries();
        $this->template
                ->set_layout('users')
                ->build('lists', isset($data) ? $data : null);
    }
    public function onboarding()
    {
        if ($this->input->post()) {
            $_POST['user_id'] = $this->session->userdata('user_id');
            $boardsid = $this->input->post('boarders_id');
            $boarders_id = serialize($boardsid);

            $_POST['boarders_id'] = $boarders_id;

            $user_mail= Offer::usersMailid($boardsid);
               
            $this->session->set_flashdata('tokbox_success', lang('mail_sent_successfully'));

            $subject = 'Onboarding';
            $message = 'Onboarding test mail';
            foreach ($user_mail as $key => $u) 
            {
                
                $params['recipient'] = $u['email'];
                $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
                $params['message'] = $message;
                $params['attached_file'] = '';
                modules::run('fomailer/send_email',$params);
            }
            redirect('offers');
        }else {
        $this->load->module('layouts');
        $this->load->library('template');
        // $this->template->title(lang('offer_approval_process'));
        $this->template->title('Onboarding');
        $data['page'] = lang('offers');
        $data['datatables'] = true;
        $data['form'] = $this->uri->segment(3);
        $data['offer_id'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        $data['offer_list'] = $this->_getOfferlist();
        $data['offer_jobtype'] = $this->_getOfferjob();


        $data['countries'] = App::countries();
        $this->template
                ->set_layout('users')
                ->build('onboarding', isset($data) ? $data : null);
            }
    }

     function _getOfferlist()
     {
         return Offer::to_where(array('user_id'=>'1'));
     }

     function _getOfferjob()
     {
         return Offer::job_where(array('user_id'=>'1'));
     }

    public function joblist()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        // $this->template->title(lang('offer_approval_process'));
        $this->template->title('Offers List');
        $data['page'] = lang('offers');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        $data['offer_list'] = $this->_getOfferlist();
        $data['offer_jobtype'] = $this->_getOfferjob();


        $data['countries'] = App::countries();
        $this->template
             ->set_layout('users')
             ->build('joblists', isset($data) ? $data : null);
        // $this->load->view('joblists');
    }

  /*   public function offers_status()
    {
        $data['offer_list'] = $this->_getOfferlist();
        $data['offer_jobtype'] = $this->_getOfferjob();


        $data['countries'] = App::countries();
        $this->template
                ->set_layout('users')
                ->build('lists', isset($data) ? $data : null);
    }*/

    public function offer_approvals()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $user_id = $this->session->userdata['user_id'];
        $data['candi_list'] = Offer::approve_candidate($user_id);
        $data['offer_jobtype'] = $this->_getOfferjob();
//$data['countries'] = App::countries();
      // echo "<pre>"; print_r($data['candi_list']);exit();
          $this->template
                ->set_layout('users')
                ->build('offer_approval', isset($data) ? $data : null);


    }
    
    public function candidate_approve()
    {
        

        $status = $this->input->post('status');
        $offer_tab_id = $this->input->post('offer_tab_id');
        $offer_id = $this->input->post('offer_id');

        if($status==1)
        {
            $new_status = 2;
        }
        else
        {
            $new_status = 1;
        }

        $status_change = Offer::candidate_status($offer_tab_id,$new_status);

        $offer_approvers_status   = $this->db->get_where('offer_approvers',array('status'=>1,'offer'=>$offer_id))->num_rows();
       
        if($offer_approvers_status != 0){
         $this->db->set(array('offer_status'=>1))->where('id',$offer_id)->update('offers');
        } else {
             $this->db->set(array('offer_status'=>2))->where('id',$offer_id)->update('offers');
        }


        print_r($status_change);exit();
                  

        exit();
    }
    public function send_applicantmail()
    {
        $new_status = 3;
        $offer_id = $this->input->post('assoc_id');
        $status_change = $this->db->set(array('offer_status'=>3))->where('id',$offer_id)->update('offers');
        // $status_change =Offer::applicantStatus($app_id,$new_status);
        if($status_change)
        {   

            $message = App::email_template('offer_mail','template_body');
            $subject = App::email_template('offer_mail','subject');
            $signature = App::email_template('email_signature','template_body');
            $user_mail= Offer::applicantMail($offer_id);

            $logo_link = create_email_logo();
            $username_repl = str_replace("{USERNAME}",strtoupper($user_mail[0]['name']),$message);            
            $logo = str_replace("{INVOICE_LOGO}",$logo_link,$username_repl);
            $message = str_replace("{SITE_NAME}",config_item('company_name'),$logo);
    
            $params['recipient'] = $user_mail[0]['email'];
            $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
            $params['message'] = $message;
            $params['attached_file'] = '';
            modules::run('fomailer/send_email',$params);
            $this->session->set_flashdata('tokbox_success', lang('mail_sent_successfully'));
            
            echo json_encode( array('msg' =>'success' ,'response'=>'ok') );exit();

        }
        else{
            $this->session->set_flashdata('tokbox_error', 'Some thing went wrong');exit();
         
        }

    }

    public function offer_decline()
    {
        $new_status = 5;
        $offer_id = $this->input->post('assoc_id');
        $status_change =  $this->db->set(array('offer_status'=>5))->where('id',$offer_id)->update('offers');
        // $status_change =Offer::applicantStatus($app_id,$new_status);
        if($status_change)
        {   

            $message = App::email_template('offer_mail_cancel','template_body');
            $subject = App::email_template('offer_mail_cancel','subject');
            $signature = App::email_template('email_signature','template_body');
            $user_mail= Offer::applicantMail($offer_id);
            $logo_link = create_email_logo();
            $username_repl = str_replace("{USERNAME}",strtoupper($user_mail[0]['name']),$message);            
            $logo = str_replace("{INVOICE_LOGO}",$logo_link,$username_repl);
            $message = str_replace("{SITE_NAME}",config_item('company_name'),$logo);
            
            $params['recipient'] = $user_mail[0]['email'];
            $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
            $params['message'] = $message;
            $params['attached_file'] = '';
            modules::run('fomailer/send_email',$params);
            $this->session->set_flashdata('tokbox_success', lang('mail_sent_successfully'));
            
            echo json_encode( array('msg' =>'success' ,'response'=>'ok') );exit();

        }
        else{
            $this->session->set_flashdata('tokbox_error', 'Some thing went wrong');exit();
         
        }

    }

    public function to_archive()
    {
        $new_status = 6;
        $offer_id = $this->input->post('assoc_id');
        $old_state = $this->input->post('current');
        $status_change = $this->db->set(array('offer_status'=>6,'old_status'=>$old_state))->where('id',$offer_id)->update('offers');
        // $status_change =Offer::applicantStatus($app_id,$new_status,$old_state);
        if($status_change)
        {
            $this->session->set_flashdata('tokbox_success', 'Application has archived successfully');
            
            echo json_encode( array('msg' =>'success' ,'response'=>'ok') );exit();    
        }
        else
        {
            $this->session->set_flashdata('tokbox_success', 'Oops! Job application archive failed');
            
            echo json_encode( array('msg' =>'Failed' ,'response'=>'error') );exit();
        }
        
    }
    public function app_retrieve()
    {
        
        $offer_id = $this->input->post('assoc_id');
        $prev_state=Offer::applicantStatus_old($offer_id);

        if($prev_state[0]['old_status'] )
        {
         $status_change = $this->db->set(array('offer_status'=>$prev_state[0]['old_status']))->where('id',$offer_id)->update('offers');
            if($status_change)
            {
                $this->session->set_flashdata('tokbox_success', 'Application has retrieved successfully');
                
                echo json_encode( array('msg' =>'success' ,'response'=>'ok') );exit();    
            }
            else
            {
                $this->session->set_flashdata('tokbox_success', 'Oops! process failed');
                
                echo json_encode( array('msg' =>'Failed' ,'response'=>'error') );exit();
            }
        }
           else
            {
                $this->session->set_flashdata('tokbox_success', 'Oops! process failed');
                
                echo json_encode( array('msg' =>'Failed' ,'response'=>'error') );exit();
            }
        
    }
    public function app_accepts()
    {
        $new_status = 4;
        $offer_id = $this->input->post('assoc_id');
        
        $status_change = $status_change = $this->db->set(array('offer_status'=>4))->where('id',$offer_id)->update('offers');

        if($status_change)
        {
            $this->session->set_flashdata('tokbox_success', 'Application has accept successfully');
            
            echo json_encode( array('msg' =>'success' ,'response'=>'ok') );exit();    
        }
        else
        {
            $this->session->set_flashdata('tokbox_success', 'Oops! Job application accept has failed');
            
            echo json_encode( array('msg' =>'Failed' ,'response'=>'error') );exit();
        }
    }

    public function offer_view($offer_id){

        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('offers'));
        $data['page'] = lang('offers');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();

        $data['offer_details'] = Offer::getjobbyid($offer_id);
       
        $offer_status   = $this->db->get_where('offers',array('id'=>$offer_id))->row_array();
        $data['candiate_offer_status'] = $offer_status['offer_status'];
        // echo $data['candiate_offer_status']; exit
        // $data['candiate_offer_status'] = Offer::getCandidateOfferById($offer_id,$candidate_id);

        $data['countries'] = App::countries();
        $this->template
                ->set_layout('users')
                ->build('offer_view', isset($data) ? $data : null);
    }

}
/* End of file contacts.php */
