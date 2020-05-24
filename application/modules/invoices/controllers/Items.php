<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Items extends MX_Controller {

	function __construct()
	{
		
		parent::__construct();	
		User::logged_in();	

		$this->load->module('layouts');	
		$this->load->library(array('template','form_validation'));
		$this->load->model(array('Invoice','App','Client'));

		$this->applib->set_locale();
	}

	function add(){
		if ($this->input->post()) {

		$invoice_id = $this->input->post('invoice_id',TRUE);
		$this->form_validation->set_rules('invoice_id', 'Invoice ID', 'required');
		$this->form_validation->set_rules('item_name', 'Item Name', 'required');
		$this->form_validation->set_rules('quantity', 'Quantity', 'required');
		$this->form_validation->set_rules('unit_cost', 'Unit Cost', 'required');

		if ($this->form_validation->run() == FALSE)
		{	
				$_POST = '';
				// Applib::go_to('invoices/view/'.$invoice_id,'error',lang('error_in_form'));	
				$this->session->set_flashdata('tokbox_error', lang('error_in_form'));
        		redirect('invoices/view/'.$invoice_id);
		}else{	
			$item_name = $this->input->post('item_name',TRUE);
			$sub_total = $this->input->post('unit_cost') * $this->input->post('quantity');
			$tax_rate = $this->input->post('item_tax_rate');


			if ($tax_rate == '0.00') {
				if($row = $this->db->where('item_name',$item_name)->get('items_saved')->row()) {
					$tax_rate = $row->item_tax_rate;
				}
				
			}

			$item_tax_total = Applib::format_deci(($tax_rate / 100) *  $sub_total);
			$total_cost =  Applib::format_deci($sub_total + $item_tax_total);

			$data = array(
						'invoice_id'	=> $this->input->post('invoice_id',TRUE),
						'item_name'		=> $item_name,
						'item_desc'		=> $this->input->post('item_desc',TRUE),
						'unit_cost'		=> $this->input->post('unit_cost',TRUE),
						'item_order'	=> $this->input->post('item_order',TRUE),
						'item_tax_rate'	=> $tax_rate,
						'item_tax_total'=> $item_tax_total,
						'quantity'		=> $this->input->post('quantity',TRUE),
						'total_cost'	=> $total_cost
						);
			// unset($_POST['tax']);

				if(App::save_data('items',$data)){
					// Applib::go_to('invoices/view/'.$invoice_id,'success',lang('item_added_successfully'));
					$this->session->set_flashdata('tokbox_success', lang('item_added_successfully'));
        			redirect('invoices/view/'.$invoice_id);
				}
			}
		}
	}

	function edit(){
		if ($this->input->post()) {

		$item_id = $this->input->post('item_id',TRUE);
		$invoice_id = Invoice::view_item($item_id)->invoice_id;

		$this->form_validation->set_rules('invoice_id', 'Invoice ID', 'required');
		$this->form_validation->set_rules('item_name', 'Item Name', 'required');
		$this->form_validation->set_rules('quantity', 'Quantity', 'required');
		$this->form_validation->set_rules('unit_cost', 'Unit Cost', 'required');

		if ($this->form_validation->run() == FALSE)
		{	
				$_POST = '';
				// Applib::go_to('invoices/view/'.$invoice_id,'error',lang('error_in_form'));	
				$this->session->set_flashdata('tokbox_error', lang('error_in_form'));
        		redirect('invoices/view/'.$invoice_id);
		}else{	
			
			$sub_total = $this->input->post('unit_cost') * $this->input->post('quantity');
			$tax_rate = $this->input->post('item_tax_rate');
			$item_tax_total = Applib::format_deci(($tax_rate / 100) *  $sub_total);

			$total_cost = Applib::format_deci($sub_total + $item_tax_total);

			$data = array(
						'invoice_id'	=> $this->input->post('invoice_id',TRUE),
						'item_name'		=> $this->input->post('item_name',TRUE),
						'item_desc'		=> $this->input->post('item_desc',TRUE),
						'unit_cost'		=> $this->input->post('unit_cost',TRUE),
						'item_tax_rate'	=> $tax_rate,
						'item_tax_total'=> $item_tax_total,
						'quantity'		=> $this->input->post('quantity',TRUE),
						'total_cost'	=> $total_cost
						);

			if(App::update('items', array('item_id' => $item_id),$data)){
					// Applib::go_to('invoices/view/'.$invoice_id,'success',lang('item_added_successfully'));
					$this->session->set_flashdata('tokbox_success', lang('item_added_successfully'));
        			redirect('invoices/view/'.$invoice_id);
			}
		}
		}else{
			$data['id'] = $this->uri->segment(4);
			$this->load->view('modal/edit_item',$data);
		}
	}

	function insert()
	{
		if ($this->input->post()) {
			$invoice = $this->input->post('invoice',TRUE);

			$this->form_validation->set_rules('item', 'Item Name', 'required');
			$this->form_validation->set_rules('add_qty', 'Add Quantity', 'required');

			if ($this->form_validation->run() == FALSE)
			{
					// Applib::go_to('invoices/view/'.$invoice,'error',lang('operation_failed'));
					$this->session->set_flashdata('tokbox_error', lang('operation_failed'));
        			redirect('invoices/view/'.$invoice);
			}else{	

			$item = $this->input->post('item',TRUE);

			$add_qty = $this->input->post('add_qty',TRUE);
			$find_qty = $this->db->where(array('item_id'=>$item))->get('items_saved')->row();
			if ($find_qty->quantity<$add_qty) {
				$this->session->set_flashdata('tokbox_error','Error - Quantity is Not Available');
        			redirect('invoices/view/'.$invoice);
			}



			$saved_item = $this->db->where(array('item_id'=>$item))->get('items_saved')->row();
			
            $items = Invoice::has_items($invoice);
           
			$unit_cost = $saved_item->unit_cost;
			$unit_quantity=$this->input->post('add_qty');

			$cost_quant=$unit_cost*$unit_quantity;

			$sale_quant=$unit_quantity*$saved_item->selling_price;

			$form_data = array(
			                'invoice_id' 		=> $invoice,
			                'item_name'  		=> $saved_item->item_name,
			                'item_desc' 		=> $saved_item->item_desc,
			                'unit_cost' 		=> $saved_item->selling_price,
			                'item_tax_rate' 	=> $saved_item->item_tax_rate,
			                'item_tax_total' 	=> $saved_item->item_tax_total,
			                'quantity' 			=> $unit_quantity,
			                'total_cost' 		=> $sale_quant,
                            'item_order' 		=> count($items) + 1,
                            'cost_quant'        =>$cost_quant,
                            'sale_quant'		=>$sale_quant
			            );
			if(App::save_data('items',$form_data)){

				$change_qty = $this->db->where(array('item_id'=>$item))->get('items_saved')->row();
				$new_qty=$change_qty->quantity-$unit_quantity;

				$this->db->set('quantity',$new_qty);
				$this->db->where('item_id',$item);
				$this->db->update('items_saved');
					// Applib::go_to('invoices/view/'.$invoice,'success',lang('item_added_successfully'));
					$this->session->set_flashdata('tokbox_success', lang('item_added_successfully'));
        			redirect('invoices/view/'.$invoice);
				}
			}
		}else{
			$data['invoice'] = $this->uri->segment(4);
			$this->load->view('modal/quickadd',$data);
		}
	}

	function delete(){
		if ($this->input->post() ){
					$item_id = $this->input->post('item', TRUE);
					$invoice = $this->input->post('invoice', TRUE);
					echo $add_qnty = $this->input->post('add_qnty', TRUE);
				
					$get = $this->db->where(array('item_id'=>$item_id))->get('items')->row();
					
					$change_qty = $this->db->where(array('item_name'=>$get->item_name))->get('items_saved')->row();
					
				if(App::delete('items',array('item_id' => $item_id))){

					$change_qty = $this->db->where(array('item_name'=>$get->item_name))->get('items_saved')->row();
					$new_qty=$change_qty->quantity+$add_qnty;
					$this->db->set('quantity',$new_qty);
					$this->db->where('item_name',$get->item_name);
					$this->db->update('items_saved');

						// Applib::go_to('invoices/view/'.$invoice,'success',lang('item_deleted_successfully'));
						$this->session->set_flashdata('tokbox_success', lang('item_deleted_successfully'));
        			redirect('invoices/view/'.$invoice);
				}
		}else{
			$data['item_id'] = $this->uri->segment(4);
			$data['invoice'] = $this->uri->segment(5);
			$data['add_qty'] = $this->uri->segment(6);

			$this->load->view('modal/delete_item',$data);
		}
	}

	function reorder(){
                if ($this->input->post() ){
                        $items = $this->input->post('json', TRUE);
                        $items = json_decode($items);
                        foreach ($items[0] as $ix => $item) {
                            App::update('items', array('item_id' => $item->id),array("item_order"=>$ix+1));
                        }
                }
                $data['json'] = $items;
                $this->load->view('json',isset($data) ? $data : NULL);
	}
	
	
	function get_item_data(){
		 if ($this->input->post()) {
			 $item_id=$this->input->post('item_id',TRUE);
			$rest_amt = $this->db->where(array('item_id'=>$item_id))->get('items_saved')->row();
			echo $rest_amt->quantity;
			exit;
		}
	}

	function get_item_unit_cost(){
		if ($this->input->post()) {
			$item_id=$this->input->post('item_id',TRUE);
			$rest_amt = $this->db->where(array('item_id'=>$item_id))->get('items_saved')->row();
			echo $rest_amt->unit_cost;
			exit;
		 
	 }
 }
        
}

/* End of file invoices.php */