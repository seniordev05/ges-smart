<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		User::logged_in();

		$this->load->module('layouts');
		$this->load->library(array('template','form_validation'));
		$this->template->title(lang('reports').' - '.config_item('company_name'));

		$this->load->model(array('Report','App','Invoice','Client','Expense','Project','User'));

// 		App::module_access('menu_reports');
		if(isset($_GET['setyear'])){ $this->session->set_userdata('chart_year', $_GET['setyear']); }
	}

	function index()
	{
		$data = array(
			'page' => lang('reports'),
		);
		$this->template
		->set_layout('users')
		->build('dashboard',isset($data) ? $data : NULL);
	}


	function view($report_view = NULL){
			switch ($report_view) {
				case 'invoicesreport':
					$this->_invoicesreport();
					break;
				case 'invoicesbyclient':
					$this->_invoicesbyclient();
					break;
				case 'paymentsreport':
					$this->_paymentsreport();
					break;
				case 'expensesreport':
					$this->_expensesreport();
					break;
				case 'expensesbyclient':
					$this->_expensesbyclient();
					break;
				case 'ticketsreport':
					$this->_ticketsreport();
					break;
				case 'projectreport':
					$this->_projectreport();
					break;
				case 'taskreport':
				$this->_taskreport();
				break;
				case 'user_report':
				$this->_user_report();
				break;
				case 'employee_report':
				$this->_employee_report();
				break;
				case 'payslip_report':
				$this->_payslip_report();
				break;
				case 'attendance_report':
				$this->_attendance_report();
				break;
			
				default:
					# code...
					break;
			}
	}

	function _invoicesreport(){
		$data = array('page' => lang('reports'),'form' => TRUE);
		if($this->input->post()){
			$range = explode('-', $this->input->post('range'));
			$start_date = date('Y-m-d', strtotime($range[0]));
			$end_date = date('Y-m-d', strtotime($range[1]));
			$data['report_by'] = $this->input->post('report_by');
			$data['invoices'] = Invoice::by_range($start_date,$end_date,$data['report_by']);
			$data['range'] = array($start_date,$end_date);
		}else{
			$data['invoices'] = Invoice::by_range(date('Y-m').'-01',date('Y-m-d'));
			$data['range'] = array(date('Y-m').'-01',date('Y-m-d'));
		}
		$this->template
			->set_layout('users')
			->build('report/invoicesreport',isset($data) ? $data : NULL);
	}

	function _invoicesbyclient(){
		$data = array('page' => lang('reports'),'form' => TRUE);
		if($this->input->post()){
			$client = $this->input->post('client');
			$data['invoices'] = Invoice::get_client_invoices($client);
			$data['client'] = $client;
		}else{
			$data['invoices'] = array();
			$data['client'] = NULL;
		}
		$this->template
			->set_layout('users')
			->build('report/invoicesbyclient',isset($data) ? $data : NULL);
	}

	function _paymentsreport(){
		$this->load->model('Payment');
		$data = array('page' => lang('reports'),'form' => TRUE);
		if($this->input->post()){
			$range = explode('-', $this->input->post('range'));
			$start_date = date('Y-m-d', strtotime($range[0]));
			$end_date = date('Y-m-d', strtotime($range[1]));
			$data['payments'] = Payment::by_range($start_date,$end_date);
			$data['range'] = array($start_date,$end_date);
		}else{
			$data['payments'] = Payment::by_range(date('Y-m').'-01',date('Y-m-d'));
			$data['range'] = array(date('Y-m').'-01',date('Y-m-d'));
		}
		$this->template
			->set_layout('users')
			->build('report/paymentsreport',isset($data) ? $data : NULL);
	}

	function _expensesreport(){
		$data = array('page' => lang('reports'),'form' => TRUE);
		if($this->input->post()){
			$range = explode('-', $this->input->post('range'));
			$start_date = date('Y-m-d', strtotime($range[0]));
			$end_date = date('Y-m-d', strtotime($range[1]));
			$data['report_by'] = $this->input->post('report_by');
			$data['expenses'] = Expense::by_range($start_date,$end_date,$data['report_by']);
			$data['range'] = array($start_date,$end_date);
		}else{
			$data['expenses'] = Expense::by_range(date('Y-m').'-01',date('Y-m-d'));
			$data['range'] = array(date('Y-m').'-01',date('Y-m-d'));
		}
		$this->template
			->set_layout('users')
			->build('report/expensesreport',isset($data) ? $data : NULL);
	}


	function _expensesbyclient(){
		$data = array('page' => lang('reports'),'form' => TRUE);
		if($this->input->post()){
			$client = $this->input->post('client');
			$data['report_by'] = $this->input->post('report_by');
			$data['expenses'] = Expense::expenses_by_client($client,$data['report_by']);
			$data['client'] = $client;
		}else{
			$data['expenses'] = array();
			$data['client'] = NULL;
		}
		$this->template
			->set_layout('users')
			->build('report/expensesbyclient',isset($data) ? $data : NULL);
	}


	function _projectreport(){
		$data = array('page' => lang('reports'),'form' => TRUE,'datatables' => TRUE);

		if($this->input->post()){
			// $project_id = $this->input->post('project_title');
			$data['project_id'] = $this->input->post('project_title');
			$data['status'] = $this->input->post('status');
			   
			if(!empty($data['project_id']) && !empty($data['status'])){
  // echo "<pre>";print_r($data); exit;
				$data['projects'] = Project::by_where(array('project_id'=>$data['project_id'],'status' => $data['status']));
				 
			} else if (!empty($data['project_id']) && $data['status'] === '') {
   // echo "<pre>";print_r($data); exit;
				$data['projects'] = Project::by_where(array('project_id'=>$data['project_id']));
			} else if ($data['project_id'] ==='' && !empty($data['status'])) {
				  
				$data['projects'] = Project::by_where(array('status' => $data['status']));
			} else {
				  
				$data['projects'] = Project::all();
			}
			
			// echo "<pre>";print_r($data['projects']); exit;
			
		}else{
			$data['projects'] = array();
			$data['project_id'] = NULL;
		}
		$this->template
			->set_layout('users')
			->build('report/projectreport',isset($data) ? $data : NULL);
	}

	function _taskreport(){
		$data = array('page' => lang('reports'),'form' => TRUE,'datatables' => TRUE);

		if($this->input->post()){
			// $project_id = $this->input->post('project_title');
			$data['task_id'] = $this->input->post('task_id');
			$data['task_progress'] = $this->input->post('task_progress');
			  


			if(!empty($data['task_id']) && !empty($data['task_progress'])){
				$data['tasks'] = $this->db->get_where('tasks',array('t_id'=>$data['task_id'],'task_progress' => $data['task_progress']))->result_array(); 
  

			} else if (!empty($data['task_id']) && $data['task_progress'] === '') {
   
				$data['tasks'] = $this->db->get_where('tasks',array('t_id'=>$data['task_id']))->result_array();
				// echo "<pre>";print_r($data['tasks']); exit;
				
			} else if (!empty($data['task_progress']) && $data['task_id'] === '0') {
				  
				$data['tasks'] = $this->db->get_where('tasks',array('task_progress' => $data['task_progress']))->result_array();

			} 
			else if ($data['task_progress'] === '' && $data['task_id'] === '0'){
				  
				$data['tasks'] = $this->db->get_where('tasks')->result_array();
			}

			else if ($data['task_progress'] == '0' && $data['task_id'] === '0') {
				  
				$data['tasks'] = $this->db->get_where('tasks',array('task_progress' => 0))->result_array();

			} 

			else if(!empty($data['task_id']) && $data['task_progress'] == '0') {
				$data['tasks'] = $this->db->get_where('tasks',array('t_id'=>$data['task_id'],'task_progress' => 0))->result_array(); 
  

			}
			
			
		}else{
			$data['tasks'] = array();
			$data['task_id'] = NULL;
		}
		$this->template
			->set_layout('users')
			->build('report/taskreport',isset($data) ? $data : NULL);
	}

	function _user_report(){
		
		$data = array('page' => lang('reports'),'form' => TRUE,'datatables' => TRUE);

		if($this->input->post()){
			
			$data['role_id'] = $this->input->post('role_id');
				  
			// print_r($data['role_id']);exit;

			if(!empty($data['role_id'])){
				$data['users'] = $this->db->get_where('users',array('role_id'=>$data['role_id']))->result_array(); 
  

			} 
			else if ($data['role_id'] == '0'){
				  
				$data['users'] = $this->db->get_where('users')->result_array();
			}

			

			
		}else{
			$data['users'] = array();
			$data['role_id'] = NULL;
		}

		$this->template
			->set_layout('users')
			->build('report/user_report',isset($data) ? $data : NULL);
	}

	function _employee_report(){
		
		$data = array('page' => lang('reports'),'form' => TRUE,'datatables' => TRUE);

		if($this->input->post()){
			
			
			$data['user_id'] = $this->input->post('user_id');
			$data['department_id'] = $this->input->post('department_id');
			$data['designation_id'] = $this->input->post('designation_id');
		
		
		$this->db->select('U.*,DATE_FORMAT(U.created,"%d %M %Y") as created,AD.fullname,AD.phone,AD.avatar,AD.doj,IF(DE.deptname IS NOT NULL,DE.deptname,"-") AS department,IF(D.designation IS NOT NULL,D.designation,"-") AS designation,D.department_id');
		$this->db->from('users U');
		$this->db->join('account_details AD','AD.user_id=U.id','LEFT');
		//self::$db->join('companies C','C.co_id=AD.company','LEFT');
		$this->db->join('designation D','D.id=U.designation_id','LEFT');
		$this->db->join('departments DE','DE.deptid=D.department_id','LEFT');
		if(!empty($data['department_id'])){
			$this->db->where('U.department_id', $data['department_id']);
		}
				
		if(!empty($data['user_id'])){
			$this->db->where('U.id', $data['user_id']);
		}
		if(!empty($data['designation_id'])){
			$this->db->where('U.designation_id', $data['designation_id']);
		}
		$this->db->order_by('U.id', 'ASC');
 	 	$data['employees'] = $this->db->get()->result_array();
			 // echo "<pre>";print_r($this->db->last_query()); exit;		
			
		}else{
			$data['employees'] = array();
			$data['user_id'] = NULL;

		}

		$this->template
			->set_layout('users')
			->build('report/employee_report',isset($data) ? $data : NULL);
	}

	function _payslip_report(){
		$data = array('page' => lang('reports'),'form' => TRUE,'datatables' => TRUE);

		if($this->input->post()){
			
			// $data['company_id'] = $this->input->post('company_id');
			$data['user_id'] = $this->input->post('user_id');
			$data['month'] = $this->input->post('month');
			$data['year'] = $this->input->post('year');

			// print_r($data);exit;

			if($data['user_id'] == '' && $data['month'] =='0' && $data['year'] == '0')
			{
				$data['payslip'] = $this->db->get('payslip')->result_array(); 
  

			} 	

  		
			elseif($data['user_id'] == '' && $data['month'] !='0' && $data['year'] == '0')
			{
																
  				$data['payslip'] = $this->db->get_where('payslip',array('p_month'=>$data['month']))->result_array();
  				 				

			}
			elseif($data['user_id'] =='' && $data['month'] =='0' && $data['year'] != '0')
			{
																
  				$data['payslip'] = $this->db->get_where('payslip',array('p_year'=>$data['year']))->result_array();
  				 				

			}
			elseif($data['user_id'] != ''  && $data['month'] =='0' && $data['year'] == '0')
			{
																
  				$data['payslip'] = $this->db->get_where('payslip',array('user_id'=>$data['user_id']))->result_array();
			}
			elseif($data['user_id'] != ''  && $data['month'] !='0' && $data['year'] == '0')
			{
																
  				$data['payslip'] = $this->db->get_where('payslip',array('user_id'=>$data['user_id'],'p_month'=>$data['month']))->result_array();
			}
			elseif($data['user_id'] != '' && $data['month'] !='0' && $data['year'] != '0')
			{
																
  				$data['payslip'] = $this->db->get_where('payslip',array('user_id'=>$data['user_id'],'p_month'=>$data['month'],'p_year'=>$data['year']))->result_array();
			}
			elseif($data['user_id'] != '' && $data['month'] =='0' && $data['year'] != '0')
			{
																
  				$data['payslip'] = $this->db->get_where('payslip',array('user_id'=>$data['user_id'],'p_year'=>$data['year']))->result_array();
			}
			elseif($data['user_id'] == '' && $data['month'] !='0' && $data['year'] != '0')
			{
																
  				$data['payslip'] = $this->db->get_where('payslip',array('p_month'=>$data['month'],'p_year'=>$data['year']))->result_array();
			}else{

			  $data['payslip'] = $this->db->get_where('payslip',array('p_month'=>$data['month'],'p_year'=>$data['year']))->result_array();
			}
			
			
		}else{
			$data['payslip'] = array();
			$data['company_id'] = NULL;
		}
		$this->template
			->set_layout('users')
			->build('report/payslip_report',isset($data) ? $data : NULL);
	}

	function _attendance_report(){
		
		$data = array('page' => lang('reports'),'form' => TRUE,'datatables' => TRUE);

		if($this->input->post()){
			
			$data['role_id'] = $this->input->post('role_id');
				  
			// print_r($data['role_id']);exit;

			if(!empty($data['role_id'])){
				$data['users'] = $this->db->get_where('users',array('role_id'=>$data['role_id']))->result_array(); 
  

			} 
			else if ($data['role_id'] == '0'){
				  
				$data['users'] = $this->db->get_where('users')->result_array();
			}

			

			
		}else{
			$data['users'] = array();
			$data['role_id'] = NULL;
		}

		$this->template
			->set_layout('users')
			->build('report/attendance_report',isset($data) ? $data : NULL);
	}
	
	function invoicespdf(){
		if($this->uri->segment(4)){

		$start_date = date('Y-m-d',$this->uri->segment(3));
		$end_date = date('Y-m-d',$this->uri->segment(4));
		$data['report_by'] = $this->uri->segment(5);
		$data['invoices'] = Invoice::by_range($start_date,$end_date,$data['report_by']);
		$data['range'] = array($start_date,$end_date);
		$data['page'] = lang('reports');
		$html = $this->load->view('pdf/invoices',$data,true);
		$file_name = lang('reports')."_".$start_date.'To'.$end_date.'.pdf';
	}else{
		$data['client'] = $this->uri->segment(3);
		$data['invoices'] = Invoice::get_client_invoices($data['client']);
		$data['page'] = lang('reports');
		$html = $this->load->view('pdf/clientinvoices',$data,true);
		$file_name = lang('reports')."_".Client::view_by_id($data['client'])->company_name.'.pdf';
	}

		

		$pdf = array(
			"html"      => $html,
			"title"     => lang('invoices_report'),
			"author"    => config_item('company_name'),
			"creator"   => config_item('company_name'),
			"badge"     => 'FALSE',
			"filename"  => $file_name
		);
		$this->applib->create_pdf($pdf);
	}

	function paymentspdf(){
		$this->load->model('Payment');
		$start_date = date('Y-m-d',$this->uri->segment(3));
		$end_date = date('Y-m-d',$this->uri->segment(4));
		$data['payments'] = Payment::by_range($start_date,$end_date);
		$data['range'] = array($start_date,$end_date);
		$data['page'] = lang('reports');
		$html = $this->load->view('pdf/payments',$data,true);
		$file_name = lang('payments')."_".$start_date.'To'.$end_date.'.pdf';
		
		$pdf = array(
			"html"      => $html,
			"title"     => lang('payments_report'),
			"author"    => config_item('company_name'),
			"creator"   => config_item('company_name'),
			"badge"     => 'FALSE',
			"filename"  => $file_name
		);
		$this->applib->create_pdf($pdf);
	}


	function expensespdf(){
	
	if($this->uri->segment(5)){
		$start_date = date('Y-m-d',$this->uri->segment(3));
		$end_date = date('Y-m-d',$this->uri->segment(4));
		$data['report_by'] = $this->uri->segment(5);
		$data['expenses'] = Expense::by_range($start_date,$end_date,$data['report_by']);
		$data['range'] = array($start_date,$end_date);
		$html = $this->load->view('pdf/expenses',$data,true);
		$file_name = lang('expenses_report')."_".$start_date.'To'.$end_date.'.pdf';
	}else{
		$data['client'] = $this->uri->segment(3);
		$data['report_by'] = $this->uri->segment(4);
		$data['expenses'] = Expense::expenses_by_client($data['client'],$data['report_by']);
		$html = $this->load->view('pdf/clientexpenses',$data,true);
		$file_name = lang('expenses_report')."_".Client::view_by_id($data['client'])->company_name.'.pdf';
	}

		$pdf = array(
			"html"      => $html,
			"title"     => lang('expenses_report'),
			"author"    => config_item('company_name'),
			"creator"   => config_item('company_name'),
			"badge"     => 'FALSE',
			"filename"  => $file_name
		);
		$this->applib->create_pdf($pdf);
	}

	function projectpdf(){
		
	
	
		$data['project_id'] = $this->uri->segment(3);
		$data['status'] =$this->uri->segment(4);
 
		if(!empty($data['project_id']) && !empty($data['status'])){

				$data['projects'] = Project::by_where(array('project_id'=>$data['project_id'],'status' => $data['status']));
			
				 
			} else if (!empty($data['project_id']) && $data['status'] === '') {

				$data['projects'] = Project::by_where(array('project_id'=>$data['project_id']));

				
			} else if ($data['project_id'] ==='' && !empty($data['status'])) {
				  
				$data['projects'] = Project::by_where(array('status' => $data['status']));
			
			} else {
				  
				$data['projects'] = Project::all();
				$file_name = lang('project_report').'.pdf';
			}
			
			$html = $this->load->view('pdf/projects',$data,true);
		 	$file_name = lang('project_report').'.pdf';
	

		$pdf = array(
			"html"      => $html,
			"title"     => lang('project_report'),
			"author"    => config_item('company_name'),
			"creator"   => config_item('company_name'),
			"badge"     => 'FALSE',
			"filename"  => $file_name
		);
		$this->applib->create_pdf($pdf);
	}

	function taskpdf(){
		
	
		
		$data['task_id'] = $this->uri->segment(3);
		$data['task_progress'] =$this->uri->segment(4);

		
		 if(!empty($data['task_id']) && !empty($data['task_progress']))
		 {
				$data['tasks'] = $this->db->get_where('tasks',array('t_id'=>$data['task_id'],'task_progress' => $data['task_progress']))->result_array(); 
  

			} else if (!empty($data['task_id']) && $data['task_progress'] == '') {
   
				$data['tasks'] = $this->db->get_where('tasks',array('t_id'=>$data['task_id']))->result_array();
								
			} else if (!empty($data['task_progress']) && $data['task_id'] == '0') {
				  
				$data['tasks'] = $this->db->get_where('tasks',array('task_progress' => $data['task_progress']))->result_array();

			} 
			else if ($data['task_progress'] == '' && $data['task_id'] == '0'){
				  
				$data['tasks'] = $this->db->get_where('tasks')->result_array();
				
			}
			else if(!empty($data['task_id']) && $data['task_progress'] == '0') {
				$data['tasks'] = $this->db->get_where('tasks',array('t_id'=>$data['task_id'],'task_progress' => 0))->result_array(); 
  

			}

			
						
			else {
				  
				$data['tasks'] = $this->db->get_where('tasks',array('task_progress' => 0))->result_array();
				$file_name = lang('task_report').'.pdf';
			} 
		   
		   $html = $this->load->view('pdf/tasks',$data,true);
		   $file_name = lang('task_report').'.pdf';
	

		$pdf = array(
			"html"      => $html,
			"title"     => lang('task_report'),
			"author"    => config_item('company_name'),
			"creator"   => config_item('company_name'),
			"badge"     => 'FALSE',
			"filename"  => $file_name
		);
		$this->applib->create_pdf($pdf);
	}


	function userpdf(){
		
	
		
		$data['role_id'] = $this->uri->segment(3);
		
		
		 	if(!empty($data['role_id']))
		 	{
				$data['users'] = $this->db->get_where('users',array('role_id'=>$data['role_id']))->result_array(); 
  

			} else if ($data['role_id'] == '0') {
   
				$data['users'] = $this->db->get_where('users')->result_array();
								
			} 
									
			else {
				  
				$data['users'] = $this->db->get_where('users')->result_array();
				$file_name = lang('user_report').'.pdf';
			} 
		   
		   $html = $this->load->view('pdf/users',$data,true);
		   $file_name = lang('user_report').'.pdf';
	

		$pdf = array(
			"html"      => $html,
			"title"     => lang('user_report'),
			"author"    => config_item('company_name'),
			"creator"   => config_item('company_name'),
			"badge"     => 'FALSE',
			"filename"  => $file_name
		);
		$this->applib->create_pdf($pdf);
	}

	function payslippdf(){
		
	
		    $data['company_id'] = $this->uri->segment(3);
			$data['user_id'] = $this->uri->segment(4);
			$data['month'] = $this->uri->segment(5);
			$data['year'] = $this->uri->segment(6);

			

			if($data['company_id'] =='0' && $data['user_id'] == '0' && $data['month'] =='0' && $data['year'] == '0')
			{
				$data['payslip'] = $this->db->get('payslip')->result_array(); 
  

			} 
			elseif($data['company_id'] !='0'  && $data['user_id'] == '0' && $data['month'] =='0' && $data['year'] == '0')
			{
				$account_details = $this->db->get_where('account_details',array('company'=>$data['company_id']))->result_array();
				foreach ($account_details as $key => $g) {
					$user = $g['user_id'];
												
  				$data['payslip'] = $this->db->get_where('payslip',array('user_id'=>$user))->result_array();
  				}
  			}

  		
			elseif($data['user_id'] == '0'  && $data['company_id'] == '0' && $data['month'] !='0' && $data['year'] == '0')
			{
																
  				$data['payslip'] = $this->db->get_where('payslip',array('p_month'=>$data['month']))->result_array();
  				 				

			}
			elseif($data['user_id'] =='0'  && $data['company_id'] == '0' && $data['month'] =='0' && $data['year'] != '0')
			{
																
  				$data['payslip'] = $this->db->get_where('payslip',array('p_year'=>$data['year']))->result_array();
  				 				

			}
			elseif($data['user_id'] != '0'  && $data['company_id'] != '0' && $data['month'] =='0' && $data['year'] == '0')
			{
																
  				$data['payslip'] = $this->db->get_where('payslip',array('user_id'=>$data['user_id']))->result_array();
			}
			elseif($data['user_id'] != '0'  && $data['company_id'] != '0' && $data['month'] !='0' && $data['year'] == '0')
			{
																
  				$data['payslip'] = $this->db->get_where('payslip',array('user_id'=>$data['user_id'],'p_month'=>$data['month']))->result_array();
			}
			elseif($data['user_id'] != '0'  && $data['company_id'] != '0' && $data['month'] !='0' && $data['year'] != '0')
			{
																
  				$data['payslip'] = $this->db->get_where('payslip',array('user_id'=>$data['user_id'],'p_month'=>$data['month'],'p_year'=>$data['year']))->result_array();
			}
			elseif($data['user_id'] != '0'  && $data['company_id'] != '0' && $data['month'] =='0' && $data['year'] != '0')
			{
																
  				$data['payslip'] = $this->db->get_where('payslip',array('user_id'=>$data['user_id'],'p_year'=>$data['year']))->result_array();
			}
			elseif($data['user_id'] == '0'  && $data['company_id'] == '0' && $data['month'] !='0' && $data['year'] != '0')
			{
																
  				$data['payslip'] = $this->db->get_where('payslip',array('p_month'=>$data['month'],'p_year'=>$data['year']))->result_array();
			}
			
			
		   $html = $this->load->view('pdf/payslip',$data,true);
		   $file_name = lang('payslip_report').'.pdf';
	

		   $pdf = array(
			"html"      => $html,
			"title"     => lang('user_report'),
			"author"    => config_item('company_name'),
			"creator"   => config_item('company_name'),
			"badge"     => 'FALSE',
			"filename"  => $file_name
		);
		$this->applib->create_pdf($pdf);
	}



	function employeepdf(){
		
	
		
		$data['company_id'] = $this->uri->segment(3);
		// print_r($data['company_id']);exit;
		
		
		 	if(!empty($data['company_id'])){
				$data['employees'] = $this->db->get_where('account_details',array('company'=>$data['company_id']))->result_array(); 
			} 
			elseif($data['company_id'] == '0')
			{
				$data['employees'] = $this->db->get('account_details')->result_array(); 
			}
									
			else {
				  
				$data['users'] = $this->db->get_where('users')->result_array();
				$file_name = lang('user_report').'.pdf';
			} 
		   
		   $html = $this->load->view('pdf/employees',$data,true);
		   $file_name = lang('employee_report').'.pdf';
	

		$pdf = array(
			"html"      => $html,
			"title"     => lang('user_report'),
			"author"    => config_item('company_name'),
			"creator"   => config_item('company_name'),
			"badge"     => 'FALSE',
			"filename"  => $file_name
		);
		$this->applib->create_pdf($pdf);
	}




	function _filter_by(){

		$filter = isset($_GET['view']) ? $_GET['view'] : '';

		return $filter;
	}

	function employees(){
        if($this->input->post()){
            $company_id = $this->input->post('company');
            $this->db->select('company,fullname,user_id');
            $this->db->from('account_details');
            $this->db->where('company', $company_id);
            $records = $this->db->get()->result_array();
            echo json_encode($records);
            die();
        }
    }



}

/* End of file invoices.php */
