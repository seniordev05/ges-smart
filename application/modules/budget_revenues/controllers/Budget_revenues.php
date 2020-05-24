<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Budget_revenues extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		User::logged_in();

		$this->load->module('layouts');	
		$this->load->library(array('template','form_validation'));
		$this->template->title('budget_revenues');
		$this->load->model(array('App'));

		$this->applib->set_locale();
		$this->load->helper('date');
	}

	function index()
	{
		
		$this->load->module('layouts');

		$this->load->library('template');		 

		$this->template->title(lang('budget_revenues').' - '.config_item('company_name'));
		$data['page'] = lang('budget_revenues');
		$data['datatables'] = TRUE;
		$data['form'] = TRUE; 
		//$this->session->unset_userdata('budget_title');
		 //$this->session->unset_userdata('budget_start_date');
		// $this->session->unset_userdata('budget_end_date');

		//print_r($_POST); exit;
		$data['categories'] = $this->db->get('budget_category')->result_array();
		if($_POST){
				$this->session->unset_userdata('cat_id');
				$this->session->unset_userdata('budget_start_date');
				$this->session->unset_userdata('budget_end_date');
				
				
				if($_POST['cat_id']!= ''){
					$this->session->unset_userdata('cat_id');
					$this->session->set_userdata('cat_id',$_POST['cat_id']);
					$this->db->where('category_id',$_POST['cat_id']);
				}
				if($_POST['budget_start_date']!= ''){
					$this->session->unset_userdata('budget_start_date');
					$this->session->set_userdata('budget_start_date',$_POST['budget_start_date']);
					$start_date = date("Y-m-d", strtotime($_POST['budget_start_date']));
					$this->db->where('revenue_date >=', $start_date);
				}
				if($_POST['budget_end_date']!= ''){
					$this->session->unset_userdata('budget_end_date');
					$this->session->set_userdata('budget_end_date',$_POST['budget_end_date']);
					$to_date = date("Y-m-d", strtotime($_POST['budget_end_date']));
					$this->db->where('revenue_date <=', $to_date);
				}
			//	return $this->db->get()->result_array();
			
				$data['dgt_payments'] = $this->db->select('*')
								         ->from('dgt_payments U')
								         ->join('dgt_invoices P','U.invoice = P.inv_id')
											->get()->result_array();

				$data['budget_revenues'] = $this->db->get('budget_revenues')->result_array();
					
		} else {

				$data['dgt_payments'] = $this->db->select('*')
								         ->from('dgt_payments U')
								         ->join('dgt_invoices P','U.invoice = P.inv_id')
											->get()->result_array();
				
				$data['budget_revenues'] = $this->db->get('budget_revenues')->result_array();
		}
		
		
		//$data['budget_category'] = $this->db->get('budget_category')->result_array();
		//$data['budget_subcategory'] = $this->db->get('budget_subcategory')->result_array();
		//echo '<pre>';print_r($data); exit;
		$this->template
			 ->set_layout('users')
			 ->build('budget_revenues',isset($data) ? $data : NULL);
	}

	

	


	function create()
	{

		//if($this->_can_add_expense() == FALSE){ App::access_denied('expenses'); }

		if ($this->input->post()) {

		$this->form_validation->set_rules('amount', 'Amount', 'required');
		$this->form_validation->set_rules('category', 'Category', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			Applib::go_to('budget_revenues','error',lang('operation_failed'));
		}else {
			$attached_file = NULL;
			
				if(file_exists($_FILES['receipt']['tmp_name']) || is_uploaded_file($_FILES['receipt']['tmp_name'])) {
					$upload_response = $this->upload_slip($this->input->post());
					if($upload_response){
						$attached_file = $upload_response;
					}else{
						$attached_file = NULL;
						// Applib::go_to('expenses','error',lang('file_upload_failed'));
						$this->session->set_flashdata('tokbox_error', lang('file_upload_failed'));
						redirect('budget_revenues');
					}

				}
				
              	$revenue_date = date('Y-m-d',strtotime($this->input->post('revenue_date',TRUE)));
              	 $this->db->select_max('po_code', 'max');
  				 $query = $this->db->get('budget_expenses');
  				 $max_budget_expenses = $query->row()->max;
  				 // echo $max_budget_expenses; exit;

                if (!empty($max_budget_expenses)) {

  				 	 $this->db->select_max('po_code', 'max');
	  				 $query_1 = $this->db->get('budget_revenues');
	  				 $max_budget_revenues = $query_1->row()->max;
	  				  if ($max_budget_revenues>$max_budget_expenses) {
	  				  	$max=$max_budget_revenues+1;
	  				  }
	  				  elseif ($max_budget_expenses>$max_budget_revenues) {
	  				  	$max=$max_budget_expenses+1;
	  				  }
  				 }
  				 else{
  				 	$max=1;
  				 }



              	$data = array(
              				'added_by'  	=> User::get_id(),
              				'amount'		=> $this->input->post('amount',TRUE),
              				'revenue_date'	=> $revenue_date,
              				'notes'			=> $this->input->post('notes'),
              				'receipt'		=> $attached_file,
              				'category_id'	=> $this->input->post('category'),
              				's_category_id'	=> $this->input->post('sub_category'),
              				'po_code'       =>$max,
              				'transaction_id'=>strtotime("now")
              	);
              
		if($revenue_id = App::save_data('budget_revenues',$data)){
			//$title = ($this->input->post('project') == 'NULL') ? 'N/A' : $p->project_title;
			// Log activity
			// $data = array(
			// 	'module' => 'expenses',
			// 	'module_field_id' => $expense_id,
			// 	'user' => User::get_id(),
			// 	'activity' => 'activity_expense_created',
			// 	'icon' => 'fa-plus',
			// 	'value1' => $cur.' '.$this->input->post('amount'),
			// 	'value2' => $title
			// 	);
			// App::Log($data);

			// Applib::go_to($_SERVER['HTTP_REFERER'],'success',lang('expense_created_successfully'));
			$this->session->set_flashdata('tokbox_success', lang('Revenue_created_successfully'));
			redirect($_SERVER['HTTP_REFERER']);
				}
			}

		}else{
			$auto_select = NULL;
			//if(isset($_GET['project'])){ $auto_select = $_GET['project']; }else{ $auto_select = NULL; }
			$data['categories'] = $this->db->get('budget_category')->result_array();
			$data['sub_categories'] = $this->db->get('budget_subcategory')->result_array();
			//echo '<pre>'; print_r($data); exit;
			//$data['projects'] = $this->get_user_projects();
			//$data['auto_select_project'] = $auto_select;
			$data['form'] = TRUE;
			$this->load->view('modal/create_revenue',$data);

		}
	}

	function edit($id = NULL)
	{
		if ($this->input->post()) {

			
		$revenue_id = $this->input->post('revenue', TRUE);

		$this->form_validation->set_rules('amount', 'Amount', 'required');
		$this->form_validation->set_rules('category', 'Category', 'required');

		if ($this->form_validation->run() == FALSE)
		{
				$_POST = '';
				// Applib::go_to('expenses','error',lang('error_in_form'));
				$this->session->set_flashdata('tokbox_success', lang('error_in_form'));
				redirect('budget_revenues');
		}else{	
			$receipt = NULL;
			if(file_exists($_FILES['receipt']['tmp_name']) || is_uploaded_file($_FILES['receipt']['tmp_name'])) {

					$upload_response = $this->upload_slip($this->input->post());
					if($upload_response){
						$receipt = $upload_response;
						App::update('budget_revenues',array('id'=>$revenue_id),array('receipt' => $receipt));
					}else{
						$receipt = NULL;
						// Applib::go_to('expenses','error',lang('file_upload_failed'));
						$this->session->set_flashdata('tokbox_error', lang('file_upload_failed'));
						redirect('budget_revenues');
					}

				}
			 
              $revenue_date = date('Y-m-d',strtotime($this->input->post('revenue_date',TRUE)));

              $data = array(
                				'added_by'  	=> User::get_id(),
                				'amount'		=> $this->input->post('amount'),
                				'revenue_date'	=> $revenue_date,
                				'notes'			=> $this->input->post('notes'),                				
                				'category_id'		=> $this->input->post('category'),
                				's_category_id'		=> $this->input->post('sub_category')

                				);

             	 $this->db->where('id',$revenue_id);
             	 $result= $this->db->update('budget_revenues',$data);
             	 //print_r($result); exit;
                if($result){

    //             $title = ($this->input->post('project') == 'NULL' || $this->input->post('project') == 0) ? 'N/A' : $p->project_title;
    //             	// Log activity
				// $data = array(
				// 	'module' => 'expenses',
				// 	'module_field_id' => $expense_id,
				// 	'user' => User::get_id(),
				// 	'activity' => 'activity_expense_edited',
				// 	'icon' => 'fa-pencil',
				// 	'value1' => $cur.' '.$this->input->post('amount'),
				// 	'value2' => $title
				// 	);
				// App::Log($data);


				// Applib::go_to($_SERVER['HTTP_REFERER'],'success',lang('expense_edited_successfully'));
				$this->session->set_flashdata('tokbox_success', lang('revenue_edited_successfully'));
				redirect($_SERVER['HTTP_REFERER']);

                }
                else
                {
                	// Applib::go_to($_SERVER['HTTP_REFERER'],'success',lang('expense_edited_successfully'));
                	$this->session->set_flashdata('tokbox_success', lang('revenue_edited_successfully'));
					redirect($_SERVER['HTTP_REFERER']);
                }
			}
		}else{


			$data['form'] = TRUE;
			$data['categories'] = $this->db->get('budget_category')->result_array();
			$data['sub_categories'] = $this->db->get('budget_subcategory')->result_array();
			$data['inf'] = $this->db->get_where('budget_revenues',array('id'=>$id))->row_array();
			$data['id'] = $id;
			//echo '<pre>';print_r($data); exit;
			$this->load->view('modal/edit_revenue',$data);

		}
	}

	public function delete_budget($revenue_id)
	{
		$this->db->where('id',$revenue_id);
		$this->db->delete('budget_revenues');
		$this->session->set_flashdata('tokbox_success', 'Revenue Deleted Successfully');
        redirect('budget_revenues');
	}

	function upload_slip($data){

	Applib::is_demo();

	if ($data) {
		$config['upload_path']   = './assets/uploads/';
		$config['allowed_types'] = config_item('allowed_files');
		$config['remove_spaces'] = TRUE;
		$config['overwrite']  = FALSE;
		$this->load->library('upload', $config);

		if ($this->upload->do_upload('receipt'))
		{
			$filedata = $this->upload->data();
			return $filedata['file_name'];
		}else{
			return FALSE;
		}
	}else{
		return FALSE;
	}
}

}

/* End of file Social_impact.php */