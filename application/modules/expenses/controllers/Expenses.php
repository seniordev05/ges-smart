<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Expenses extends MX_Controller {

	

	function __construct()
	{
		parent::__construct();

		$this->load->module('layouts');	
		User::logged_in();

		$this->load->library(array('template','form_validation'));
		$this->template->title(lang('expenses').' - '.config_item('company_name'));
		$this->load->model(array('App','Client','Expense','Project'));

		App::module_access('menu_expenses');

		if (User::is_admin() || User::perm_allowed(User::get_id(),'view_all_expenses')) 
		{
			if(isset($_GET['project']))
			{
        		$this->expenses = App::get_by_where('expenses',array('id !='=>'0','project' => $_GET['project']));        	
			}
			else
			{
        		$this->expenses = App::get_by_where('expenses',array('id !='=>'0'));
        	}
		}
		elseif (User::is_staff()) 
		{
        	$this->expenses = $this->staff_expenses();
		}
		else
		{      

        	$this->expenses = App::get_by_where('expenses',
        						array('client'=>User::profile_info(User::get_id())->company,'show_client'=>'Yes'));
        }
        
        $this->applib->set_locale();
	}

	
	function index()
	{
	$data['page'] = lang('expenses');

	$data['datatables'] = TRUE;
	$data['datepicker'] = TRUE;
	$data['form'] = TRUE;
	// $check_teamlead = $this->db->get_where('dgt_users',array('id'=>$this->session->userdata('user_id')))->row_array(); 
	// if($check_teamlead['is_teamlead'] == 'no'){
	// 	$data['expenses'] = $this->db->query("SELECT * FROM `dgt_expenses` where added_by =".$this->tank_auth->get_user_id()." order by id  ASC ")->result();
	// }
	// if($check_teamlead['is_teamlead'] == 'yes'){
	// 	$data['expenses'] = $this->db->query("SELECT * FROM `dgt_expenses` where FIND_IN_SET(".$this->session->userdata('user_id').", expense_approvers) order by id  ASC ")->result();
	// }
	$data['attach_slip'] = TRUE;
	
	
	$user_details = $this->db->get_where('users',array('id'=>$this->session->userdata('user_id')))->row_array();

	 
	

		$this->template
		->set_layout('users')
		->build('expenses',isset($data) ? $data : NULL);
	
	
	
	}

	

	function view($id = NULL)
	{	
// 		if(!$this->_can_view_expense($id)){ redirect(); }
		$this->load->model('Invoice');

		$data['page'] = lang('expenses');
        $data['show_links'] = TRUE;
        $data['datepicker'] = TRUE;
        $data['id'] = $id;
		$data['expenses'] = $this->expenses; // GET a list of the Expenses
		$this->template
		->set_layout('users')
		->build('view',isset($data) ? $data : NULL);
	}


	function _can_view_expense($expense){
		if(User::is_admin()){ return TRUE; }
		$info = Expense::view_by_id($expense);

		if($info->show_client == 'No' && User::is_client()){ return FALSE; }
		
		if($info->client == User::profile_info(User::get_id())->company) { return TRUE; }

		if(User::perm_allowed(User::get_id(),'view_all_expenses')) { return TRUE; }

		if(User::is_staff()){
			return (Project::is_assigned(User::get_id(),$info->project)) ? TRUE : FALSE;
		}else{
				return FALSE;
		}
	}

	function create()
	{

		if($this->_can_add_expense() == FALSE){ App::access_denied('expenses'); } 

		if ($this->input->post()) 
		{
			$this->form_validation->set_rules('amount', 'Amount', 'required');
			$this->form_validation->set_rules('category', 'Category', 'required');

			if ($this->form_validation->run() == FALSE)
			{
				Applib::go_to('expenses','error',lang('operation_failed'));
			}
			else
			{
				$attached_file = NULL;				
				if(file_exists($_FILES['receipt']['tmp_name']) || is_uploaded_file($_FILES['receipt']['tmp_name'])) 
				{
					$upload_response = $this->upload_slip($this->input->post());
					if($upload_response)
					{
						$attached_file = $upload_response;
					}
					else
					{
						$attached_file = NULL;
						// Applib::go_to('expenses','error',lang('file_upload_failed'));
						$this->session->set_flashdata('tokbox_error', lang('file_upload_failed'));
						redirect('expenses');
					}

				}
			$expense_approvers = $this->db->get('expense_approver_settings')->result_array();

	 	 	foreach ($expense_approvers as $key => $value) {
	 	 		$approvers_id[] = $value['approvers'];
	 	 	}
	 	 	$approver_det  = $this->db->query("SELECT * FROM `dgt_account_details` where user_id = ".$this->session->userdata('user_id')." ")->row_array();
	 	 	$teamlead_id = $this->db->get_where('users',array('id'=>$this->session->userdata('user_id')))->row_array();

	 	 	if(!empty($approvers_id)){
	 	 		if (in_array(9, $approvers_id))
			  	{
				  	$approvers[] = $teamlead_id['teamlead_id'];
				  	$teamlead_id_details = $this->db->get_where('users',array('id'=>$teamlead_id['teamlead_id']))->row_array();
				  	$recipient[] = $teamlead_id_details['email'];
				  	if (($key = array_search(9, $approvers_id)) !== false) {
						    unset($approvers_id[$key]);
						}
						if(!empty($approvers_id)){
							foreach ($approvers_id as $key => $value) {
								$user_details = $this->db->get_where('users',array('designation_id'=>$value))->row_array();
								if(!empty($user_details)){
									$approvers[] = $user_details['id'];	
									$recipient[] = $user_details['email'];

								}
								
							}
						}
						
				  }
				else
			  	{
				  	if(!empty($approvers_id)){
							foreach ($approvers_id as $key => $value) {
								$user_details = $this->db->get_where('users',array('designation_id'=>$value))->row_array();
								if(!empty($user_details)){
									$approvers[] = $user_details['id'];	
									$recipient[] = $user_details['email'];
								}
								
							}
						}
			  	}
	 	 	} else {
	 	 		$approvers[] = $teamlead_id['teamlead_id'];;
	 	 		$teamlead_id_details = $this->db->get_where('users',array('id'=>$teamlead_id['teamlead_id']))->row_array();
			  	$recipient[] = $teamlead_id_details['email'];
	 	 	}
				$project_id = $this->input->post('project',TRUE);
				$p = Project::by_id($project_id);

				$billable = ($this->input->post('billable') == 'on') ? '1' : '0';
				$show_client = ($this->input->post('show_client') == 'on') ? 'Yes' : 'No';
				$invoiced = ($this->input->post('invoiced') == 'on') ? '1' : '0';
				$client = ($this->input->post('project') == 'NULL') ? $this->input->post('client') : $p->client ;
				$cur = Client::client_currency($client)->code;
				$currency_symbol = ($this->input->post('currency_symbol') != '') ? $this->input->post('currency_symbol') : $cur ;
				$expense_date = date('Y-m-d',strtotime($this->input->post('expense_date',TRUE)));
				// $exp_approvers = serialize($this->input->post('expense_approvers'));
				// $exp_approvers = serialize($approvers);
				$approvers_string = implode(',', $approvers);
				$data = array(
							'added_by'  	=> User::get_id(),
							'billable'		=> $billable,
							'amount'		=> $this->input->post('amount',TRUE),
							'expense_date'	=> $expense_date,
							'notes'			=> $this->input->post('notes'),
							'project'		=> $this->input->post('project',TRUE),
							'client'		=> $client,
							'receipt'		=> $attached_file,
							'invoiced'		=> $invoiced,
							'show_client'	=> $show_client,
							'category'		=> $this->input->post('category'),
							// 'reports_to'	=> $this->input->post('reports_to'),
					        // 'default_expense_approval' => $this->input->post('default_expense_approval'),
					        'expense_approvers' => $approvers_string,
					        'currency_symbol' => $currency_symbol,
					        'message_to_approvers' => $this->input->post('message_to_approvers')
				);

				 // echo "<pre>"; print_r($data); exit;
				 $login_user_name = $this->tank_auth->get_username();  
						
				$expense_id = App::save_data('expenses',$data);

				// $approvers = unserialize($exp_approvers);
				
                if (count($approvers) > 0) {
                    foreach ($approvers as $key => $value) {
                        $approvers_details = array(
                            'approvers' => $value,
                            'expense' => $expense_id,
                            'status' => 0
                           

                            );
                        
                        $result = $this->db->insert('expense_approvers',$approvers_details);
                        $data = array(
						'module' => 'expenses',
						'module_field_id' => $expense_id,
						'user' => $value,
						'activity' => 'Expense Requested',
						'icon' => 'fa-plus',
						'value1' => $cur.' '.$this->input->post('amount'),
						'value2' => $title
						);
					App::Log($data);
                    }

                    $subject         = "New Expense Request ";
				$message         = '<div style="height: 7px; background-color: #535353;"></div>
										<div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
											<div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">New Expense Request</div>
											<div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
												<p> Hi,</p>
												<p>You have a New Expense Request from  '.$approver_det['fullname'].' </p>											
												<br> 
												<a style="text-decoration:none" href="'.$base_url.'expenses"> 
												<button style="background:#00CC33; border-radius: 5px;; cursor:pointer"> Approve </button> 
												</a>
												<a style="text-decoration:none; margin-left:15px" href="'.$base_url.'expenses/"> 
												<button style="background:#FF0033; border-radius: 5px;; cursor:pointer"> Reject </button> 
												</a>  
												&nbsp;&nbsp;  
												OR 
												<a style="text-decoration:none; margin-left:15px" href="'.$base_url.'expenses/"> 
												<button style="background: #CCCC00; border-radius: 5px;; cursor:pointer"> Just Login </button> 
												</a>
												<br>
												</big><br><br>Regards<br>The '.config_item('company_name').' Team
											</div>
									 </div>'; 		
                    foreach ($recipient as $key => $u) 
                    {
                        
                        $params['recipient'] = $u['email'];
                        $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
                        $params['message'] = $message;
                        $params['attached_file'] = '';
                        modules::run('fomailer/send_email',$params);
                    }


                }
				
					$title = ($this->input->post('project') == 'NULL') ? 'N/A' : $p->project_title;
					// Log activity
					$data = array(
						'module' => 'expenses',
						'module_field_id' => $expense_id,
						'user' => User::get_id(),
						'activity' => 'activity_expense_created',
						'icon' => 'fa-plus',
						'value1' => $cur.' '.$this->input->post('amount'),
						'value2' => $title
						);
					App::Log($data);

					// Applib::go_to($_SERVER['HTTP_REFERER'],'success',lang('expense_created_successfully'));
					$this->session->set_flashdata('tokbox_success', lang('expense_created_successfully'));
					redirect($_SERVER['HTTP_REFERER']);
				


			}
		}
		else
		{
			$data['currencies'] = App::currencies();
			$auto_select = NULL;
			if(isset($_GET['project'])){ $auto_select = $_GET['project']; }else{ $auto_select = NULL; }
			
			$data['categories'] = App::get_by_where('categories',array('module'=>'expenses'));
			$data['projects'] = $this->get_staff_projects(User::get_id()); 
			$data['auto_select_project'] = $auto_select;
			$data['form'] = TRUE;
			$this->load->view('modal/create_expense',$data);

		}
	}

	function _can_add_expense(){
		if (User::is_staff()) 
		{
			return TRUE;
		}else{
			return FALSE;	
		}
	}

	function edit($id = NULL)
	{
		if ($this->input->post()) {

			
		$expense_id = $this->input->post('expense', TRUE);

		$this->form_validation->set_rules('amount', 'Amount', 'required');
		$this->form_validation->set_rules('category', 'Category', 'required');

		if ($this->form_validation->run() == FALSE)
		{
				$_POST = '';
				// Applib::go_to('expenses','error',lang('error_in_form'));
				$this->session->set_flashdata('tokbox_success', lang('error_in_form'));
				redirect('expenses');
		}else{	
			$receipt = NULL;
			if(file_exists($_FILES['receipt']['tmp_name']) || is_uploaded_file($_FILES['receipt']['tmp_name'])) {

					$upload_response = $this->upload_slip($this->input->post());
					if($upload_response){
						$receipt = $upload_response;
						App::update('expenses',array('id'=>$expense_id),array('receipt' => $receipt));
					}else{
						$receipt = NULL;
						// Applib::go_to('expenses','error',lang('file_upload_failed'));
						$this->session->set_flashdata('tokbox_error', lang('file_upload_failed'));
						redirect('expenses');
					}

				}
			  $project_id = $this->input->post('project',TRUE);
			  $p = Project::by_id($project_id);
              $billable = ($this->input->post('billable') == 'on') ? '1' : '0';
              $show_client = ($this->input->post('show_client') == 'on') ? 'Yes' : 'No';
              $invoiced = ($this->input->post('invoiced') == 'on') ? '1' : '0';

              $client = ($this->input->post('project') == 0 || $this->input->post('project') == 'NULL') 
              			? $this->input->post('client') : $p->client ;
              $cur = Client::client_currency($client)->code;
              $cur = Client::client_currency($client)->code;
				$currency_symbol = ($this->input->post('currency_symbol') != '') ? $this->input->post('currency_symbol') : $cur ;
              $expense_date = date('Y-m-d',strtotime($this->input->post('expense_date',TRUE)));

              $data = array(
                				'billable'		=> $billable,
                				'amount'		=> $this->input->post('amount'),
                				'expense_date'	=> $expense_date,
                				'notes'			=> $this->input->post('notes'),
                				'project'		=> $project_id,
                				'client'		=> $client,
                				'invoiced'		=> $invoiced,
                				'show_client'	=> $show_client,
                				'currency_symbol'	=> $currency_symbol,
                				'category'		=> $this->input->post('category')
                				);
                if(App::update('expenses',array('id'=>$expense_id),$data)){

                $title = ($this->input->post('project') == 'NULL' || $this->input->post('project') == 0) ? 'N/A' : $p->project_title;
                	// Log activity
				$data = array(
					'module' => 'expenses',
					'module_field_id' => $expense_id,
					'user' => User::get_id(),
					'activity' => 'activity_expense_edited',
					'icon' => 'fa-pencil',
					'value1' => $cur.' '.$this->input->post('amount'),
					'value2' => $title
					);
				App::Log($data);


				// Applib::go_to($_SERVER['HTTP_REFERER'],'success',lang('expense_edited_successfully'));
				$this->session->set_flashdata('tokbox_success', lang('expense_edited_successfully'));
				redirect($_SERVER['HTTP_REFERER']);

                }
                else
                {
                	// Applib::go_to($_SERVER['HTTP_REFERER'],'success',lang('expense_edited_successfully'));
                	$this->session->set_flashdata('tokbox_success', lang('expense_edited_successfully'));
					redirect($_SERVER['HTTP_REFERER']);
                }
			}
		}else{

				$data['currencies'] = App::currencies();
			$data['form'] = TRUE;
			$data['categories'] = App::get_by_where('categories',array('module'=>'expenses'));
			$data['projects'] = $this->get_user_projects();
			$data['id'] = $id;
			$this->load->view('modal/edit_expense',$data);

		}
	}
	function show($id = NULL){
		$data = array('show_client' => 'Yes');
		App::update('expenses',array('id'=>$id),$data);
		// Applib::go_to($_SERVER['HTTP_REFERER'],'success',lang('expense_edited_successfully'));
		$this->session->set_flashdata('tokbox_success', lang('expense_edited_successfully'));
				redirect($_SERVER['HTTP_REFERER']);
	}

	

	function delete($id = NULL)
	{
		if ($this->input->post()) {

			$expense = $this->input->post('expense', TRUE);
			App::delete('expenses',array('id'=>$expense));

			// Applib::go_to('expenses','success',lang('expense_deleted_successfully'));
			$this->session->set_flashdata('tokbox_success', lang('expense_deleted_successfully'));
				redirect('expenses');
		}else{
			$data['expense'] = $id;
			$this->load->view('modal/delete_expense',$data);

		}
	}

	/**
	 * get_user_projects
	 *
	 * Get user projects
	 *
	 * @access	public
	 * @param	type	name
	 * @return	array	
	 */
	 
	function get_user_projects()
	{
		if (!User::is_client()) {
			if (User::is_admin() || (User::is_staff() && User::perm_allowed(User::get_id(),'view_all_projects'))) {
				return Project::all();
			}else{
				return array();
			}
		}else{
			return Project::by_client(User::profile_info(User::get_id())->company);
		}
	}

	function get_staff_projects($staff_id)
	{
		return Project::staff_project($staff_id);
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



	private function staff_expenses(){

		// $projects = $this->db->select('project_assigned')
		// 					 ->where('assigned_user',User::get_id())
		// 					 ->get('assign_projects')->result_array();
		// $pro = array();
		// foreach ($projects as $key => $p) {
		// 	$pro[] = $p['project_assigned'];
			
		// }

		// $expenses = array();
		// if(count($pro) > 0){
			// $expenses = $this->db->where_in('project', $pro)->get('expenses')->result();
		$expenses =  $this->db->get_where('expenses',array('added_by'=> $this->session->userdata('user_id')))->result();

		// }
		
		return $expenses;
	}

	public function update_expense_status()
	{		

		$approver = $this->session->userdata('user_id');
		// $login_user_name = $this->tank_auth->get_username(); 
			
		if( (isset($_POST['id'])&&isset($_POST['status'])) && (!empty($_POST['id'])&&!empty($_POST['status'])) )
		{
			$expense_det = $this->db->query("SELECT * FROM dgt_expenses where id = ".$_POST['id']." ")->result_array();
			$acc_det   = $this->db->query("SELECT * FROM `dgt_account_details` where user_id = ".$expense_det[0]['added_by']." ")->result_array();
			$user_det  = $this->db->query("SELECT * FROM `dgt_users` where id = ".$expense_det[0]['added_by']." ")->result_array();
			$approver_det  = $this->db->query("SELECT * FROM `dgt_account_details` where user_id = ".$approver." ")->row_array();

			$approvers_status['status'] = $_POST['status'];
			$this->db->update('dgt_expense_approvers',$approvers_status,array('expense'=>$_POST['id'],'approvers'=>$approver)); 

			// print_r($this->db->last_query()); exit;
			$afftectedRows = 1;	

			$expense_approvers_status   = $this->db->get_where('dgt_expense_approvers',array('status !='=>1,'expense'=>$_POST['id']))->num_rows();
			// print_r($expense_approvers_status); exit;
			$expense_reject_status   = $this->db->get_where('dgt_expense_approvers',array('status'=>2,'expense'=>$_POST['id']))->num_rows();

			$expense_pending_status   = $this->db->get_where('dgt_expense_approvers',array('status'=>0,'expense'=>$_POST['id']))->num_rows();
		 	if($expense_approvers_status == 0){
		 		$expense_status['expense_status'] = 1;
	         	$this->db->update('dgt_expenses',$expense_status,array('id'=>$_POST['id'])); 
	         	 // print_r($this->db->last_query()); exit;
	         	$status = 1;
	         	$email_status = "Approved";
	         	$data = array(
						'module' => 'expenses',
						'module_field_id' => $_POST['id'],
						'user' => $expense_det[0]['added_by'],
						'activity' => 'Expense Approved by '.$approver_det['fullname'],
						'icon' => 'fa-plus',
						'value1' => $cur.' '.$this->input->post('amount'),
						'value2' => $expense_det[0]['added_by']
						);
					App::Log($data);

	        } 
	        if($expense_pending_status != 0){
	        	$expense_status['expense_status'] = 0;
	         	$this->db->update('dgt_expenses',$expense_status,array('id'=>$_POST['id'])); 

	        }
	        if($expense_reject_status != 0){
	        	$expense_status['expense_status'] = 2;
	         	$this->db->update('dgt_expenses',$expense_status,array('id'=>$_POST['id'])); 

	        }

	        
	        	
	         	
         	if($_POST['status'] == 1){
         		
     		$status = 1;
         	$email_status = "Approved";

         	$data = array(
					'module' => 'expenses',
					'module_field_id' => $_POST['id'],
					'user' => $expense_det[0]['added_by'],
					'activity' => 'Expense Approved by '.$approver_det['fullname'],
					'icon' => 'fa-plus',
					'value1' => $cur.' '.$this->input->post('amount'),
					'value2' => $expense_det[0]['added_by']
					);
				App::Log($data);
         	} 
         	if($_POST['status'] == 2) {

         		
 			$status = 2;
         	$email_status = "Rejected";

         	$data = array(
					'module' => 'expenses',
					'module_field_id' => $_POST['id'],
					'user' => $expense_det[0]['added_by'],
					'activity' => 'Expense Rejected	by '.$approver_det['fullname'],
					'icon' => 'fa-plus',
					'value1' => $cur.' '.$this->input->post('amount'),
					'value2' => $expense_det[0]['added_by']
					);
				App::Log($data);
         	}
	              
        			
 			if(!empty($acc_det) && !empty($user_det)){
				$recipient       = array();
				if($user_det[0]['email'] != '') $recipient[] = $user_det[0]['email'];
				$subject         = " Expense Request ".$email_status;
				$message         = '<div style="height: 7px; background-color: #535353;"></div>
										<div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
											<div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">Expense Request '.$email_status.'</div>
											<div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
												<p> Hi '.$acc_det[0]['fullname'].',</p>
												<p> Your Expense Request for '.date('d-m-Y',strtotime($expense_det[0]['expense_date'])).' Amount '.$expense_det[0]['amount'].' has been '.$email_status.' by '.$approver_det['fullname'].' </p> 
												<br>  
												<a style="text-decoration:none;" href="http://dreamguystech.com/hrm/"> 
													<button style="background: #CCCC00; border-radius: 5px;; cursor:pointer"> Click to Login </button> 
												</a>
												<br>
												</big><br><br>Regards<br>The '.config_item('company_name').' Team
											</div>
									 </div>';  
				if(!empty($recipient) && count($recipient) > 0){		 
					$params      = array(
											'recipient' => $recipient,
											'subject'   => $subject,
											'message'   => $message
										);   
					$succ = Modules::run('fomailer/send_email',$params); 	
				}
 			} 
			
			// $update_array = array('status'=>$status);
			// $this->db->where('expense',$_POST['id']);
// 			$this->db->where('approvers',$this->session->userdata('user_id'));
			// $this->db->update('expense_approvers',$update_array);			 
			
			
			print_r(json_encode(array('status'=>$status,'updated'=>$afftectedRows,'expense'=>$_POST['id'])));
			exit;

			
		} 
	}

	public function get_approvers()
    {
        $approvers = $this->db->where(array('role_id !='=>2,'role_id !='=>1,'activated'=>1,'banned'=>0,'id !=' => $this->session->userdata('user_id'))) -> get('users')->result();

        

         $data=array();
            foreach($approvers as $r)
            {
                $data['value']=$r->id;
                // $data['label']=ucfirst($r->username);
                $data['label']=ucfirst(User::displayName($r->id));
                $json[]=$data;
                
                
            }
        echo json_encode($json);
        exit;
    }

    public function get_designation()
    {
         $designations = $this->db->order_by('designation','ASC')->get('designation')->result();

        

         $data=array();
            foreach($designations as $r)
            {
                $data['value']=$r->id;
                // $data['label']=ucfirst($r->username);
                $data['label']=ucfirst($r->designation);
                $json[]=$data;
                
                
            }
        echo json_encode($json);
        exit;
    }
    public function show_expense()
  	{
  		 $user_id = $this->uri->segment(3);
              
  		$this->load->module('layouts');
                $this->load->library('template');
                $this->template->title('expenses'); 
 				$data['datepicker'] = TRUE;
				$data['form']       = TRUE; 
				$data['datatables'] = TRUE;
                $data['page']       = lang('expenses');
                $data['user_id']    = $user_id;
               
                $this->template
					 ->set_layout('users')
					 ->build('show_expenses',isset($data) ? $data : NULL);
  	}

}

/* End of file expenses.php */