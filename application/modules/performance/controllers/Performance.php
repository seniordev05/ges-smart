<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Performance extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model(array( 'App','Client','Performance360'));
        
        // if (!User::is_admin()) {
        //     $this->session->set_flashdata('message', lang('access_denied'));
        //     redirect('');
        // }
        // App::module_access('menu_policies');
        $this->load->helper(array('inflector'));
        $this->applib->set_locale();
    }
    
    function index()
    {
        if($this->tank_auth->is_logged_in()) { 
                $this->load->module('layouts');
                $this->load->library('template');
                $this->template->title(lang('okr')); 
                $data['datepicker'] = TRUE;
                $data['form']       = TRUE; 
                $data['page']       = lang('okr');
                $data['role']       = $this->tank_auth->get_role_id();
              
                $performance_details = $this->db->get_where('performance',array('user_id'=>$this->session->userdata('user_id')))->row_array();
                 $okr_details = $this->db->get_where('okrdetails',array('user_id'=>$this->session->userdata('user_id')))->row_array();

                  $user = $this->db->where('lead',$this->session->userdata('user_id'))->group_by('user_id')->order_by('id','ASC')->get('okrdetails')->result_array();

                 $is_manager = $this->db->get_where('users',array('teamlead_id '=>$this->session->userdata('user_id')))->row_array();
                  
                // if($performance_details == '' &&  $data['role'] == '3' && $user == Array()) {
                // $this->template
                //      ->set_layout('users')
                //      ->build('performance',isset($data) ? $data : NULL);
                // }
                // else
                  if($data['role'] == '3' && $is_manager != array())
                {
                     $data['datatables'] = TRUE;
                    $this->template
                     ->set_layout('users')
                     ->build('performance_lead',isset($data) ? $data : NULL);
                }
                elseif($data['role'] == '1') {
                $data['datatables'] = TRUE;
                $this->template
                     ->set_layout('users')
                     ->build('performance_manager',isset($data) ? $data : NULL);
                }
                
                elseif($okr_details == '' &&  $data['role'] == '3')
                {
                    $this->template
                     ->set_layout('users')
                     ->build('okr-view',isset($data) ? $data : NULL);
                }
                elseif($okr_details != '' &&  $data['role'] == '3')
                {
                    $this->template
                     ->set_layout('users')
                     ->build('okr-view',isset($data) ? $data : NULL);
                }
                
        }else{
           redirect('');    
        }
     } 
     function manager_performance()
    {
        if($this->tank_auth->is_logged_in()) { 
                $this->load->module('layouts');
                $this->load->library('template');
                $this->template->title(lang('okr')); 
                $data['datepicker'] = TRUE;
                $data['form']       = TRUE; 
                $data['page']       = lang('okr');
                $data['role']       = $this->tank_auth->get_role_id();
              
                $performance_details = $this->db->get_where('performance',array('user_id'=>$this->session->userdata('user_id')))->row_array();
                 $okr_details = $this->db->get_where('okrdetails',array('user_id'=>$this->session->userdata('user_id')))->row_array();

                  $user = $this->db->where('lead',$this->session->userdata('user_id'))->group_by('user_id')->order_by('id','ASC')->get('okrdetails')->result_array();

                 $is_manager = $this->db->get_where('users',array('teamlead_id '=>$this->session->userdata('user_id')))->row_array();
                  
                // if($performance_details == '' &&  $data['role'] == '3' && $user == Array()) {
                // $this->template
                //      ->set_layout('users')
                //      ->build('performance',isset($data) ? $data : NULL);
                // }
                // else
                
                    $this->template
                     ->set_layout('users')
                     ->build('okr-view',isset($data) ? $data : NULL);
               
                
        }else{
           redirect('');    
        }
     } 

     public function performance_dashboard(){

         if($this->tank_auth->is_logged_in()) { 
                $this->load->module('layouts');
                $this->load->library('template');
                $this->template->title('Performance Dashboard'); 
                $data['datepicker'] = TRUE;
                $data['form']       = TRUE; 
                $data['page']       = 'Dashboard';
                 $user_id = $this->session->userdata('user_id'); 
        $data['user_id'] = $user_id;
        
        $data['role']       = $this->tank_auth->get_role_id();
        if($data['role'] == '1') {
             $data['performances_360'] = $this->db->select('IFNULL(ROUND(AVG(self_rating),1),0) as self_ratings, IFNULL(ROUND(AVG(rating),1),0) as your_ratings,user_id,IFNULL(ROUND(AVG(self_rating+rating),1),0) as peer_ratings')
                ->group_by('user_id')
               ->from('performance_360')
               ->get()->result_array();

               $data['completed_performance'] = $this->db->where('status',1)->from('performance_360')->get()->num_rows();
               $data['outstanding_performance'] = $this->db->where('status',0)->from('performance_360')->get()->num_rows();
               } elseif($data['role'] == '3'){

                     $data['performances_360'] = $this->db->select('IFNULL(ROUND(AVG(self_rating),1),0) as self_ratings, IFNULL(ROUND(AVG(rating),1),0) as your_ratings,user_id,IFNULL(ROUND(AVG(self_rating+rating),1),0) as peer_ratings')->where('teamlead_id',$user_id)->group_by('user_id')->order_by('id','ASC')->get('performance_360')->result_array(); 
                }

        // echo "<pre>";print_r($data['performances_360']); exit;
       
                // if($data['role'] == '1') {
                //     $data['performances_360'] = $this->db->select()
                //             ->group_by('user_id')
                //             ->from('performance_360')
                //             ->get()->result_array();                     
                //     $this->template
                //          ->set_layout('users')
                //          ->build('list',isset($data) ? $data : NULL);
                // } elseif($performances_360 != '' &&  $data['role'] == '3'){

                //     $data['performances_360'] = Performance360::get_360_performance_manager($user_id);
                //      $this->template
                //      ->set_layout('users')
                //      ->build('list',isset($data) ? $data : NULL);
                //     // $data['competencies'] = Performance360::get_competencies_manager($user_id);
                // }
        $this->template
                         ->set_layout('users')
                         ->build('performance_dashboard',isset($data) ? $data : NULL);
        }else{
           redirect('');    
        }
     }


     function show_okrdetails()
    { 
         $okr_id = $this->uri->segment(3);

        if($this->tank_auth->is_logged_in()) { 
                $this->load->module('layouts');
                $this->load->library('template');
                $this->template->title('Performance'); 
                $data['datepicker'] = TRUE;
                $data['form']       = TRUE; 
                $data['page']       = 'performance_manager';
                $data['role']       = $this->tank_auth->get_role_id();
                $data['okr_id']     = $okr_id;
              
              
                $this->template
                     ->set_layout('users')
                     ->build('okr-manager',isset($data) ? $data : NULL);
               
               
        }else{
           redirect('');    
        }
     } 



     function add_okr()
     {
       
        $data = array(
        'user_id' => $this->input->post('user_id'),
        'okr_description' => $this->input->post('okr_description')

        );
        
        
        
        $this->db->insert('performance',$data);
        $user_id = $this->db->insert_id();
        $data['user_details'] = $this->db->get_where('users',array('id'=>$user_id))->row_array();
       
        
        $this->load->module('layouts');
                $this->load->library('template');
                $this->template->title(lang('okr')); 
                $data['datepicker'] = TRUE;
                $data['form']       = TRUE; 
                $data['page']       = lang('okr');
                $data['role']       = $this->tank_auth->get_role_id();

        $this->template
                     ->set_layout('users')
                     ->build('okr-view',isset($data) ? $data : NULL);
     }


     function add_goals()
     {      
      // echo "<pre>";print_r($_POST); exit;
      // $okr_id = $this->session->userdata('user_id');
      // $okrdetails = $this->db->get_where('okrdetails',array('user_id'=>$okr_id))->row_array();
      // if(!empty($okrdetails)){
      //     $okrdetailsid= $okrdetails['id'];
      // }else{
        
        $data = array(
          'user_id' => $this->input->post('user_id'),
          'emp_name' => $this->input->post('fullname'),
          'position' => $this->input->post('position'),
          'lead' => $this->input->post('lead'),
          'goal_year' => $this->input->post('goal_year'),
          'goal_duration' => $this->input->post('goal_duration'),
        );


        $this->db->insert('okrdetails',$data);
        $okrdetailsid=$this->db->insert_id();

        $objective_result = array();
        $objective = $this->input->post('objective');
        $obj_status = $this->input->post('okr_status');
        $progress_value = $this->input->post('progress_value');
        $key_results = $this->input->post('key_result');
        $key_status = $this->input->post('key_status');
        $keyres_value = $this->input->post('keyres_value');
       
        foreach($objective as $key => $obj){
            $objectiveData['okrdetailsid']  = $okrdetailsid;
            $objectiveData['objective']  = $obj;
            $objectiveData['okr_status'] = $obj_status[$key];
            $objectiveData['progress_value']  = $progress_value[$key];
            $this->db->insert('okr_key_results',$objectiveData);
            $okr_objective_id = $this->db->insert_id();
            foreach($key_results[$key] as $obj_key => $result_data){
                $resultData['okr_id']  = $okrdetailsid;
                $resultData['objective_id'] = $okr_objective_id;
                $resultData['key_result']  = $result_data;
                $resultData['key_status']  = $key_status[$key][$obj_key];
                $resultData['keyprog_value']  = $keyres_value[$key][$obj_key];
                $this->db->insert('okr_results',$resultData);
            }

        }
        $args = array(
                    'user' => $this->input->post('lead'),
                    'module' => 'performance',
                    'module_field_id' => $okrdetailsid,
                    'activity' => 'OKR Performance By'.$this->input->post('fullname'),
                    'icon' => 'fa-user',
                    'value1' => $okrdetailsid,
                );
        App::Log($args);
       
        $this->session->set_flashdata('tokbox_success', 'Performance Added Successfully');
        redirect('performance');
        
     }

     public function edit_okrdetails()
     {

        $this->db->where('okrdetailsid',$this->input->post('id'));
        $this->db->delete('okr_key_results');

        $objective=array_values($this->input->post('objective'));
        $okr_status=array_values($this->input->post('okr_status'));
        $progress_value=array_values($this->input->post('progress_value'));
        $grade_value=array_values($this->input->post('grade_value'));
        $grade_value_man=array_values($this->input->post('grade_value_man'));
        $feedback=array_values($this->input->post('obj_feedback'));
        $key_result=array_values($this->input->post('key_result'));
        $key_status=array_values($this->input->post('key_status'));
        $keyres_value=array_values($this->input->post('keyres_value'));
        $key_gradeval=array_values($this->input->post('key_gradeval'));
        $key_gradeval_man=array_values($this->input->post('key_gradeval_man'));
        $key_feedback=array_values($this->input->post('key_feedback'));

        
        for($i = 0; $i< count($objective); $i++)
        {

        $data_obj = array(
        'okrdetailsid'=>$this->input->post('id'),  
        'objective' => $objective[$i],
        'okr_status' => $okr_status[$i],
        'progress_value' => $progress_value[$i],
        'grade_value' => $grade_value[$i],
        'grade_value_man' => $grade_value_man[$i],
        'feedback'=>!empty($feedback[$i])?$feedback[$i]:'',
        'key_result' => json_encode($key_result[$i]),
        'key_status' => json_encode($key_status[$i]),
        'keyprog_value' => json_encode($keyres_value[$i]),
        'key_gradeval' => json_encode($key_gradeval[$i]),
        'key_gradeval_man' => json_encode($key_gradeval_man[$i]),
        'key_feedback' => json_encode(!empty($key_feedback[$i])?$key_feedback[$i]:''));

        $this->db->insert('okr_key_results',$data_obj);

        // $this->db->where('id',$this->input->post('id'));
        // $this->db->update('okr_key_results',$data_obj);
        }





       
        // for($i = 0; $i< count($this->input->post('objective')); $i++)
        // {

        // $data_obj = array(
        // 'objective' => $this->input->post('objective')[$i],
        // 'okr_status' => $this->input->post('okr_status')[$i],
        // 'progress_value' => $this->input->post('progress_value')[$i],
        // 'grade_value' => $this->input->post('grade_value')[$i],
        // 'key_result' => $this->input->post('key_result')[$i],
        // 'key_status' => $this->input->post('key_status')[$i],
        // 'keyprog_value' => $this->input->post('keyres_value')[$i],
        // 'key_gradeval' => $this->input->post('key_gradeval')[$i],
        // 'obj_feedback' => $this->input->post('obj_feedback')[$i],
        // 'key_feedback' => $this->input->post('key_feedback')[$i]


        // );

        // $objectives[] = $data_obj;
        //      }

        // $data = array(
        // 'id' => $this->input->post('id'),
        // 'emp_name' => $this->input->post('emp_name'),
        // 'position' => $this->input->post('position'),
        // 'lead' => $this->input->post('lead'),
        // 'goal_year' => $this->input->post('goal_year'),
        // 'goal_duration' => $this->input->post('goal_duration'),
        // 'objective' => json_encode($objectives)
        //  );

       

        //     $this->db->where('id',$this->input->post('id'));
        //     $this->db->update('dgt_okrdetails',$data);
        
         $this->session->set_flashdata('tokbox_success', 'Added Successfully');
         redirect('performance');
     }

      public function objective_status(){
       
        $data = array(        
            'okr_status' => $this->input->post('okr_status')
         );

        $this->db->where('id',$this->input->post('object_id'));
        $this->db->update('okr_key_results',$data);         
        $args = array(
                    'user' => $this->input->post('user_id'),
                    'module' => 'performance',
                    'module_field_id' => $this->input->post('object_id'),
                    'activity' => 'Status '.$_POST['okr_status'],
                    'icon' => 'fa-user',
                    'value1' => $this->input->post('okr_status', true),
                );
        App::Log($args);
        $args = array(
                    'user' => User::get_id(),
                    'module' => 'performance',
                    'module_field_id' => $this->input->post('object_id'),
                    'activity' => 'Status '.$this->input->post('okr_status'),
                    'icon' => 'fa-user',
                    'value1' => $this->input->post('okr_status', true),
                );
        App::Log($args);
        echo 'yes'; exit;

    }

    public function key_status(){
       
        $data = array(        
            'key_status' => $this->input->post('key_status')
         );

        $this->db->where('id',$this->input->post('key_id'));
        $this->db->update('okr_results',$data);         
        $args = array(
                    'user' => $this->input->post('user_id'),
                    'module' => 'performance',
                    'module_field_id' => $this->input->post('key_id'),
                    'activity' => 'Status '.$this->input->post('key_status'),
                    'icon' => 'fa-user',
                    'value1' => $this->input->post('key_status', true),
                );
        App::Log($args);
        $args = array(
                    'user' => User::get_id(),
                    'module' => 'performance',
                    'module_field_id' => $this->input->post('key_id'),
                    'activity' => 'Status '.$this->input->post('key_status'),
                    'icon' => 'fa-user',
                    'value1' => $this->input->post('key_status', true),
                );
        App::Log($args);
        echo 'yes'; exit;

    }

     public function okr_object_rating(){
       
        $data = array(        
            'grade_value' => $this->input->post('grade_value')
         );

        $this->db->where('id',$this->input->post('objective_id'));
        $this->db->update('okr_key_results',$data);         
        $args = array(
                    'user' => $this->input->post('user_id'),
                    'module' => 'performance',
                    'module_field_id' => $this->input->post('objective_id'),
                    'activity' => $this->input->post('grade_value').' Grading',
                    'icon' => 'fa-user',
                    'value1' => $this->input->post('grade_value', true),
                );
        App::Log($args);
        $args = array(
                    'user' => User::get_id(),
                    'module' => 'performance',
                    'module_field_id' => $this->input->post('objective_id'),
                    'activity' => $this->input->post('grade_value').' Grading',
                    'icon' => 'fa-user',
                    'value1' => $this->input->post('grade_value', true),
                );
        App::Log($args);
        echo 'yes'; exit;

    }

     public function okr_result_rating(){

       
        $data = array(        
            'key_gradeval' => $this->input->post('key_gradeval')
         );

        $this->db->where('id',$this->input->post('result_id'));
        $this->db->update('okr_results',$data);   
        $args = array(
                    'user' => $this->input->post('user_id'),
                    'module' => 'performance',
                    'module_field_id' => $this->input->post('result_id'),
                    'activity' => $this->input->post('key_gradeval').' Grading',
                    'icon' => 'fa-user',
                    'value1' => $this->input->post('key_gradeval', true),
                );
        App::Log($args);
        $args = array(
                    'user' => User::get_id(),
                    'module' => 'performance',
                    'module_field_id' => $this->input->post('result_id'),
                    'activity' => $this->input->post('key_gradeval').' Grading',
                    'icon' => 'fa-user',
                    'value1' => $this->input->post('key_gradeval', true),
                );
        App::Log($args);
        echo 'yes'; exit;

    }

    public function objective_feedback(){

            $data = array(        
            'user_id' => $this->session->userdata('user_id'),
            'feed_back' =>  $this->input->post('feed_back'),
            'okr_objective_id ' =>  $this->input->post('objective_id')
         );
          $okr_id = $this->input->post('okr_id');
        // print_r($objective_created_by); exit;
        $this->db->insert('okr_feedback',$data);   
        $args = array(
                    'user' => $this->input->post('user_id'),
                    'module' => 'performance',
                    'module_field_id' => $this->input->post('objective_id'),
                    'activity' => $this->input->post('feed_back'),
                    'icon' => 'fa-user',
                    'value1' => $this->input->post('feed_back', true),
                );
        App::Log($args);
        $args = array(
                    'user' => User::get_id(),
                    'module' => 'performance',
                    'module_field_id' => $this->input->post('objective_id'),
                    'activity' => $this->input->post('feed_back'),
                    'icon' => 'fa-user',
                    'value1' => $this->input->post('feed_back', true),
                );
        App::Log($args);
                $this->session->set_flashdata('tokbox_success', lang('feedback_updated_successfully'));
                redirect('performance/show_okrdetails/'.$okr_id);

    }

     public function result_feedback(){

            $data = array(        
            'user_id' => $this->session->userdata('user_id'),
            'feed_back' =>  $this->input->post('feed_back'),
            'okr_result_id ' =>  $this->input->post('result_id')
         );
          $okr_id = $this->input->post('okr_id');
        // print_r($objective_created_by); exit;
        $this->db->insert('okr_result_feedback',$data);   
        $args = array(
                    'user' => $this->input->post('user_id'),
                    'module' => 'performance',
                    'module_field_id' => $this->input->post('result_id'),
                    'activity' => $this->input->post('feed_back'),
                    'icon' => 'fa-user',
                    'value1' => $this->input->post('feed_back', true),
                );
        App::Log($args);
        $args = array(
                    'user' => User::get_id(),
                    'module' => 'performance',
                    'module_field_id' => $this->input->post('result_id'),
                    'activity' => $this->input->post('feed_back'),
                    'icon' => 'fa-user',
                    'value1' => $this->input->post('feed_back', true),
                );
        App::Log($args);
                $this->session->set_flashdata('tokbox_success', lang('feedback_updated_successfully'));
                redirect('performance/show_okrdetails/'.$okr_id);

    }

    
}
