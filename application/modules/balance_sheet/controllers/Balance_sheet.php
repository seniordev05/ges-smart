<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Balance_sheet extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		User::logged_in();

		$this->load->module('layouts');	
		$this->load->library(array('template','form_validation'));
		$this->template->title('balance_sheet');
		$this->load->model(array('App', 'Invoice'));

		$this->applib->set_locale();
		$this->load->helper('date');
	}

	function index()
	{
		$this->load->module('layouts');

		$this->load->library('template');		 

		$this->template->title(lang('balance_sheet'));
		$data['page'] = lang('balance_sheet');
		$data['datatables'] = TRUE;
		$data['form'] = TRUE; 	
	
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

				$data['budget_expenses'] = $this->db->get('budget_expenses')->result_array();
				$data['budget_revenues'] = $this->db->get('budget_revenues')->result_array();
					
		} else {

				$data['dgt_payments'] = $this->db->select('*')
								         ->from('dgt_payments U')
								         ->join('dgt_invoices P','U.invoice = P.inv_id')
											->get()->result_array();
				
				$data['budget_expenses'] = $this->db->get('budget_expenses')->result_array();
				$data['budget_revenues'] = $this->db->get('budget_revenues')->result_array();
		}
      	
		$this->template
			 ->set_layout('users')
			 ->build('balance_sheet',isset($data) ? $data : NULL);
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

	public function pdf(){
		$this->load->module('layouts');
		$this->load->library('template');
		$this->template->title('balance_sheet');
		$data['page'] = lang('balance_sheet');
		$data['stripe'] = true;
		$data['twocheckout'] = true;
		$data['sortable'] = true;
		$data['typeahead'] = true;
		$data['rates'] = Invoice::get_tax_rates();
		$data['id'] = $invoice_id;

		$page = 'balance_sheet_pdf';
		
		$html = $this->load->view($page,$data,true);
		$pdf = array(
			'html' => $html,
			'title' => lang('balance_sheet'),
			'author' => config_item('company_name'),
			'creator' => config_item('company_name'),
			'filename' => lang('balance_sheet').'.pdf',
			'badge' => config_item('display_invoice_badge'),
		);
		$this->applib->create_pdf($pdf);
	}

	public function excel(){

		// load excel library
		$this->load->library('excel');

		$fileName = lang('balance_sheet').'.xlsx'; 
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$table_columns = array("Transaction ID", "Invoice Code", "PO Code", "Category Name", "SubCategory Name", "Expense amount", "Expense date", "Selling Price", "Budget Revenue", "Revenue Date");
		$column = 0;
		foreach($table_columns as $field) {
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
			$column++;
		}

		$budget_expenses = $this->db->get('budget_expenses')->result_array();
		$dgt_payments = $this->db->select('*')->from('dgt_payments U')->join('dgt_invoices P','U.invoice = P.inv_id')->get()->result_array();
		$budget_revenues = $this->db->get('budget_revenues')->result_array();      

		// set Row
		$excel_row = 2;
		foreach($budget_expenses as $budget) {
			if($budget['project_id'] != 0){
				$project = $this->db->get_where('projects',array('project_id'=>$budget['project_id']))->row_array();
				$project_name = $project['project_title'];
			}else{
				$project_name = '-';
			}

			if(($budget['category_id'] != 0) || ($budget['s_category_id'] != 0)){
				$category = $this->db->get_where('budget_category',array('cat_id'=>$budget['category_id']))->row_array();
				$subcategory = $this->db->get_where('budget_subcategory',array('sub_id'=>$budget['s_category_id']))->row_array();
				$category_name = $category['category_name'];
				$subcategory_name = $subcategory['sub_category'];
			}else{
				$category_name = '-';
				$subcategory_name = '-';
			}

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $budget['transaction_id']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, "--");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, "PO00". $budget['po_code']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $category_name);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $subcategory_name);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, Applib::format_currency($inv->currency, $budget['amount']));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, date('d-M-Y',strtotime($budget['expense_date'])));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, Applib::format_currency($inv->currency, "0"));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, Applib::format_currency($inv->currency, -$budget['amount']));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, date('d-M-Y',strtotime($budget['expense_date'])));
			$excel_row++;

		}

		foreach($budget_revenues as $budget) {
			if($budget['project_id'] != 0){
				$project = $this->db->get_where('projects',array('project_id'=>$budget['project_id']))->row_array();
				$project_name = $project['project_title'];
			}else{
				$project_name = '-';
			}

			if(($budget['category_id'] != 0) || ($budget['s_category_id'] != 0)){
				$category = $this->db->get_where('budget_category',array('cat_id'=>$budget['category_id']))->row_array();
				$subcategory = $this->db->get_where('budget_subcategory',array('sub_id'=>$budget['s_category_id']))->row_array();
				$category_name = $category['category_name'];
				$subcategory_name = $subcategory['sub_category'];
			}else{
				$category_name = '-';
				$subcategory_name = '-';
			}

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $budget['transaction_id']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, "--");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, "PO00". $budget['po_code']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $category_name);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $subcategory_name);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, "--");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, "--");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, Applib::format_currency($inv->currency, $budget['amount']));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, "--");
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, date('d-M-Y',strtotime($budget['revenue_date'])));
			$excel_row++;

		}

		if(count($dgt_payments)>0){
			foreach ($dgt_payments as $dgt_payment) {
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $dgt_payment['trans_id']);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $dgt_payment['reference_no']);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, "--");
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, "--");
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, "--");
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, Applib::format_currency($inv->currency, $dgt_payment['total_cost_quantity']));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, date("d-M-Y", strtotime($dgt_payment['date_saved'])));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, Applib::format_currency($inv->currency, $dgt_payment['total_sale_quantity']));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, Applib::format_currency($inv->currency, $dgt_payment['total_sale_quantity']-$dgt_payment['total_cost_quantity']));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, date("d-M-Y", strtotime($dgt_payment['payment_date'])));
				$excel_row++;
			}
		}

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$fileName.'"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
		$objWriter->save('php://output');
		exit;
	}
}

/* End of file Social_impact.php */