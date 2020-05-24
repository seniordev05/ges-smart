<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Collaborator extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		User::logged_in(); 

		$this->load->model(array('Project','App','Welcome'));

		if (User::is_admin()) {
			redirect('welcome');
		}
		if (User::is_client()) {
			redirect('clients');
		}

		$this->applib->set_locale();
	}

	function index()
	{
	$this->load->module('layouts');
	$this->load->library('template');
	$this->template->title(lang('welcome').' - '.config_item('company_name'));
	$data['page'] = lang('home');
	$data['task_checkbox'] = TRUE;


	$this->template
	->set_layout('users')
	->build('welcome',isset($data) ? $data : NULL);
	}

	public function save_punch_details(){

   if($this->input->post()){

   $params = $this->input->post();
   if(!empty($params['punch_in_date_time'])){

      $strtotime = strtotime(date('Y-m-d H:i'));
      $user_id   = $params['user_id'];

      $a_year    = date('Y',$strtotime);
      $a_month   = date('m',$strtotime);
      $a_day     = date('d',$strtotime);
      $a_cin     = date('H:i',$strtotime);
      $where     = array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
      $this->db->select('month_days,month_days_in_out');
      $record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();

      if(empty($record)){
        $inputs['attendance_month'] =$a_month;
        $inputs['attendance_year'] = $a_year;
        Attendance_model::attendance($user_id,$inputs);
        $this->db->select('month_days,month_days_in_out');
        $record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
      }
      
      if(!empty($record['month_days'])){
        $record_day = unserialize($record['month_days']);
        $month_days_in_out_record = unserialize($record['month_days_in_out']);

        $a_day -=1;
        
         if(!empty($record_day[$a_day]) && !empty($month_days_in_out_record[$a_day])){
          $current_days = $month_days_in_out_record[$a_day];
          $total_records = count($current_days);
          $current_day = end($current_days);
          
  

          if($record_day[$a_day]['punch_in'] ==''){
            $record_day[$a_day]['punch_in'] = $a_cin;
            $record_day[$a_day]['day'] = 1;
          }
          
          if($total_records == 1 && empty($current_day['punch_out'])){
            
            $current_days = array('day'=>1,'punch_in'=>$a_cin,'punch_out'=>'');
            $month_days_in_out_record[$a_day][0] = $current_days;
          }else{
            
            if(!empty($current_day['punch_in']) && !empty($current_day['punch_out']))
            {
              $current_days[$total_records] =array('day'=>1,'punch_in'=>$a_cin,'punch_out'=>'');
              $month_days_in_out_record[$a_day] = $current_days;
            } 
          }
          

        }
      }
 
      $this->db->where($where);
      $this->db->update('dgt_attendance_details', array('month_days'=>serialize($record_day),'month_days_in_out'=>serialize($month_days_in_out_record)));
   }

   $this->session->set_flashdata('tokbox_success', 'Punch in successfully saved');
   return redirect('collaborator');
   }

   }

   public function save_punch_details_out(){

   if($this->input->post()){

   $params = $this->input->post();

   if(!empty($params['punch_out_date_time'])){

      $strtotime = strtotime(date('Y-m-d H:i'));
      $user_id   = $params['user_id'];

      $a_year    = date('Y',$strtotime);
      $a_month   = date('m',$strtotime);
      $a_day     = date('d',$strtotime);
      $a_cout     = date('H:i',$strtotime);
      $where     = array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
      $this->db->select('month_days,month_days_in_out');
      $record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
     
      if(empty($record)){
        $inputs['attendance_month'] =$a_month;
        $inputs['attendance_year'] = $a_year;
        Attendance_model::attendance($user_id,$inputs);
        $this->db->select('month_days,month_days_in_out');
        $record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
      }
      
      if(!empty($record['month_days'])){
         
        $record_day = unserialize($record['month_days']);
        $month_days_in_out_record = unserialize($record['month_days_in_out']);
         
          $a_day -=1;
          
          $current_days = $month_days_in_out_record[$a_day];
          $total_records = count($current_days);
          $current_day = end($current_days);

      
        if(!empty($record_day[$a_day])){
            $record_day[$a_day]['punch_out'] = $a_cout;
            $record_day[$a_day]['day'] = 1;
        }
        if($total_records == 1 && empty($current_day['punch_out'])){
           
            $month_days_in_out_record[$a_day][0]['punch_out'] = $a_cout;
          }else{
              
            if(!empty($current_day['punch_in']) && empty($current_day['punch_out']))
            {
              
               $current_days[$total_records-1]['punch_out'] = $a_cout;
               $month_days_in_out_record[$a_day] = $current_days;
            } 
          }
        
      }
      
      $this->db->where($where);
      $this->db->update('dgt_attendance_details', array('month_days'=>serialize($record_day),'month_days_in_out'=>serialize($month_days_in_out_record)));
   }
   $this->session->set_flashdata('tokbox_success', 'Punch out successfully saved');
   // $this->session->set_flashdata('message', 'Punch out successfully saved.');
   return redirect('collaborator');
   }

   }
	
}

/* End of file collaborator.php */