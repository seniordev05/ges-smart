<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Budgets extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		User::logged_in();

		$this->load->module('layouts');	
		$this->load->library(array('template','form_validation'));
		$this->template->title('time_sheets');
		$this->load->model(array('App'));

		$this->applib->set_locale();
		$this->load->helper('date');
	}

	function index()
	{
		$this->load->module('layouts');

		$this->load->library('template');		 

		$this->template->title(lang('budgets').' - '.config_item('company_name'));
		$data['page'] = lang('budgets');
		$data['datatables'] = TRUE;
		$data['form'] = TRUE;
		$data['budgets'] = $this->db->get('budgets')->result_array();
		$this->template

			 ->set_layout('users')

			 ->build('budgets',isset($data) ? $data : NULL);
	}


	public function add_budgets()
	{
		if($_POST){
			// echo "<pre>"; print_r($_POST); exit;
			$budget_type = $this->input->post('budget_type');

			$result = array(
				'budget_title' => $this->input->post('budget_title'),
				'budget_type' => $this->input->post('budget_type'),
				'budget_start_date' => date('Y-m-d',strtotime($this->input->post('budget_start_date'))),
				'budget_end_date' => date('Y-m-d',strtotime($this->input->post('budget_end_date'))),
				'revenue_title' => json_encode($this->input->post('revenue_title')),
				'revenue_amount' => json_encode($this->input->post('revenue_amount')),
				'overall_revenues' => $this->input->post('overall_revenues'),
				'expenses_title' => json_encode($this->input->post('expenses_title')),
				'expenses_amount' => json_encode($this->input->post('expenses_amount')),
				'overall_expenses' => $this->input->post('overall_expenses'),
				'expected_profit' => $this->input->post('expected_profit'),
				'tax_amount' => $this->input->post('tax_amount'),
				'budget_amount' => $this->input->post('budget_amount'),
				'revenue_quantity'=>json_encode($this->input->post('revenue_quantity')),
				'revenue_created_at'=>json_encode($this->input->post('revenue_create_date')),
				'expense_quantity'=>json_encode($this->input->post('expenses_quantity')),
				'expense_created_at'=>json_encode($this->input->post('expenses_create_date')),
				'expected_profit_rate'=>$this->input->post('expected_profit_rate'),
				'total_amt_exp'=>json_encode($this->input->post('total_amt_exp')),
				'total_amt_rev'=>json_encode($this->input->post('total_amt'))
			);
			if($budget_type == 'project')
			{
				$result['project_id'] = $this->input->post('projects');
			}

			if($budget_type == 'category')
			{
				$result['category_id'] = $this->input->post('category');
				$result['sub_cat_id'] = $this->input->post('sub_category');
			}
			// echo "<pre>";
			// print_r($result); exit;

			$this->db->insert('budgets',$result);
			$this->session->set_flashdata('tokbox_success', 'Budget Added Successfully');
            redirect('budgets');


		}else{
			$this->load->module('layouts');
			$this->load->library('template');		 
			$this->template->title(lang('budgets').' - '.config_item('company_name'));
			$data['page'] = lang('budgets');
			$data['datatables'] = TRUE;
			$data['form'] = TRUE;
			$data['categories'] = $this->db->get('budget_category')->result_array();
			$data['projects'] = $this->db->get('projects')->result_array();
			$this->template
				 ->set_layout('users')
				 ->build('add_budgets',isset($data) ? $data : NULL);
		}
	}


	public function edit_budgets($budget_id)
	{
		if($_POST)
		{
			$budget_type = $this->input->post('budget_type');

			$result = array(
				'budget_title' => $this->input->post('budget_title'),
				'budget_type' => $this->input->post('budget_type'),
				'budget_start_date' => date('Y-m-d',strtotime($this->input->post('budget_start_date'))),
				'budget_end_date' => date('Y-m-d',strtotime($this->input->post('budget_end_date'))),
				'revenue_title' => json_encode($this->input->post('revenue_title')),
				'revenue_amount' => json_encode($this->input->post('revenue_amount')),
				'overall_revenues' => $this->input->post('overall_revenues'),
				'expenses_title' => json_encode($this->input->post('expenses_title')),
				'expenses_amount' => json_encode($this->input->post('expenses_amount')),
				'overall_expenses' => $this->input->post('overall_expenses'),
				'expected_profit' => $this->input->post('expected_profit'),
				'tax_amount' => $this->input->post('tax_amount'),
				'budget_amount' => $this->input->post('budget_amount'),
				'revenue_quantity'=>json_encode($this->input->post('revenue_quantity')),
				'revenue_created_at'=>json_encode($this->input->post('revenue_create_date')),
				'expense_quantity'=>json_encode($this->input->post('expenses_quantity')),
				'expense_created_at'=>json_encode($this->input->post('expenses_create_date')),
				'expected_profit_rate'=>$this->input->post('expected_profit_rate'),
				'total_amt_exp'=>json_encode($this->input->post('total_amt_exp')),
				'total_amt_rev'=>json_encode($this->input->post('total_amt'))
			);
			if($budget_type == 'project')
			{
				$result['project_id'] = $this->input->post('projects');
			}

			if($budget_type == 'category')
			{
				$result['category_id'] = $this->input->post('category');
				$result['sub_cat_id'] = $this->input->post('sub_category');
			}

			$this->db->where('budget_id',$budget_id);
			$this->db->update('budgets',$result);
			$this->session->set_flashdata('tokbox_success', 'Budget Updated Successfully');
            redirect('budgets');
		}else{			
			$this->load->module('layouts');
			$this->load->library('template');		 
			$this->template->title(lang('budgets').' - '.config_item('company_name'));
			$data['page'] = lang('budgets');
			$data['datatables'] = TRUE;
			$data['form'] = TRUE;
			$data['budgets'] = $this->db->get_where('budgets',array('budget_id'=>$budget_id))->row_array();
			$data['categories'] = $this->db->get('budget_category')->result_array();
			$data['projects'] = $this->db->get('projects')->result_array();
			$this->template
				 ->set_layout('users')
				 ->build('edit_budgets',isset($data) ? $data : NULL);
		}
	}

	public function check_subcategories()
	{
		$category_id = $this->input->post('category_id');
		$sub_category = $this->db->get_where('budget_subcategory',array('cat_id'=>$category_id))->result_array();
		echo json_encode($sub_category); exit;
	}

	public function delete_budget($budget_id)
	{
		$this->db->where('budget_id',$budget_id);
		$this->db->delete('budgets');
		$this->session->set_flashdata('tokbox_success', 'Budget Deleted Successfully');
        redirect('budgets');
	}

	

	
}

/* End of file Social_impact.php */