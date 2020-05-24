<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Payroll extends MX_Controller {
    function __construct()
    {
        parent::__construct();
		$this->load->library(array('tank_auth'));	
        $this->load->model(array( 'App'));     
        $salary_setting = App::salary_setting();
			  $settingsalray = array();
                        if(!empty($salary_setting)){
                            foreach ($salary_setting as  $value) {
                                $settingsalray[$value->config_key] = $value->value;
                            }
                        }
			 $this->da_percentage = (!empty($settingsalray['salary_da']))?$settingsalray['salary_da']:'1';
			 $this->hra_percentage = (!empty($settingsalray['salary_hra']))?$settingsalray['salary_hra']:'1';  
    }
    function index()
    {
		
		$this->load->module('layouts');
		$this->load->library('template');
		$this->template->title('Payroll');
		 
		$data['datepicker'] = TRUE;
		$data['form']       = TRUE; 
		$data['page']       = lang('payrun');
		$data['role']       = $this->tank_auth->get_role_id();
		$this->template
			 ->set_layout('users')
			 ->build('create_payroll',isset($data) ? $data : NULL);
    }
	function create()
	{
		$data['da_percentage'] = $this->da_percentage;
		$data['hra_percentage'] = $this->hra_percentage;
		$data['user_id'] = $this->uri->segment(3);
		$this->load->view('modal/pay_slip',$data);
	}
	function edit_salary()
	{
		$data['user_id'] = $this->uri->segment(3);
		$this->load->view('modal/edit_salary',$data);
	}
	function save_salary()
	{
		if ($this->input->post()) {
  			$det['user_id']       = $this->input->post('salary_user_id');  
 			$det['amount']        = $this->input->post('user_salary_amount');
			$det['date_created']  = date('Y-m-d H:i:s');
			$this->db->insert('dgt_salary',$det);  
			//$this->session->set_flashdata('alert_message', 'error');
			redirect('payroll');
 		}else{
			$data['user_id'] = $this->uri->segment(3);
		    $this->load->view('modal/edit_salary',$data);
		}
		
	}
	function update_salary()
	{ 
  			$user_id         = $this->input->post('user_id');  
 			$det['amount']   = $this->input->post('amount');
 			$det['date_created'] = date('Y-m-d H:i:s');
			$id              = $this->input->post('type');
 			$this->db->update('dgt_salary',$det,array('id'=>$id));  
			echo 1;
			//$this->session->set_flashdata('alert_message', 'error');
			//redirect('payroll');
 		    exit;
	}


	function view_salary_slip(){

		if($this->input->post()){
			
			
			$this->load->module('layouts');
			$this->load->library('template');
			$this->template->title('Payroll');
			$data['datepicker'] = TRUE;
			$data['form']       = TRUE; 
			$data['page']       = 'payroll';
			$data['role']       = $this->tank_auth->get_role_id();
			$data['pay_slip_details'] = $this->input->post();
			
			$this->template->set_layout('users')
			 ->build('salary_detail',isset($data) ? $data : NULL);
		}
	}

	function payslip($employeeid)
	{
		    $this->load->module('layouts');
			$this->load->library('template');
			$this->template->title('Payroll');
			$data['datepicker'] = TRUE;
			$data['form']       = TRUE; 
			$data['page']       = 'payroll';
			$data['role']       = $this->tank_auth->get_role_id();
			$data['user_id']  = $employeeid;
			
			
			$this->template->set_layout('users')
			 ->build('employee_salary_detail',isset($data) ? $data : NULL);
	}
	
	
	function employee(){

			$this->load->module('layouts');
			$this->load->library('template');
			$this->template->title('Payroll');
			$data['datepicker'] = TRUE;
			$data['form']       = TRUE; 
			$data['page']       = 'payroll';
			$data['role']       = $this->tank_auth->get_role_id();
			$data['user_id']  = $this->session->userdata('user_id');
			$this->template->set_layout('users')
			 ->build('employee_salary_detail',isset($data) ? $data : NULL);
	}

	function salary_detail()
	{ 
  			$user_id   = $this->input->post('user_id');  
 			$year      = $this->input->post('year');
			$month     = $this->input->post('month');
			$this->db->where('user_id', $user_id);
			$this->db->where('p_year', $year);
			$this->db->where('p_month', $month);
			$details = $this->db->get('payslip')->row();

			if(!empty($details)){

				$details = json_decode($details->payslip_details,TRUE);
				print_r($details); exit;
				$bs = $details['payslip_basic'];
				$da = $details['payslip_da'];
				$hra = $details['payslip_hra'];
			echo json_encode(array('basic'=>$bs,'da'=>$da,'hra'=>$hra,'payment_details'=>$details));
			exit;	
			}else{

			$date      = $year."-".$month."-31";
  			$qry       = "select * from dgt_salary where user_id = ".$user_id."";
			$s_qry     = '';
			if($year != ''){
				$s_qry = " and date_created <= '".$date." 23:59:59' order by date_created desc";
			}
			if($year == date('Y') && $month > date('m')){
 				$s_qry = " order by date_created desc ";
			} 
			$qry .= $s_qry. " limit 1";
			  // echo $qry; exit;
 			$res      = $this->db->query($qry)->result_array();
			$bs       = $da = $hra = '';
			if(!empty($res)){
			    $bass  = $res[0]['amount'];
			    $da  = ($this->da_percentage*$res[0]['amount']/100);
				$hra = ($this->hra_percentage*$res[0]['amount']/100);
				// $bs  = ($bs-($da+$hra));
				$bs  = $da;
				$other = ($bass - ($da + $hra));

 			echo json_encode(array('basic'=>$bs,'hra'=>$hra,'other'=>$other, 'payment_details'=>array()));
 			exit;
			}
			}
 	} 


 	function payroll_items()
 	{
 		$this->load->module('layouts');
		$this->load->library('template');
		$this->template->title(lang('payroll_items'));
		$data['datepicker'] = TRUE;
		$data['form']       = TRUE; 
		$data['page']       = lang('payroll_items');
		$data['role']       = $this->tank_auth->get_role_id();
			
			
			$this->template->set_layout('users')
			 ->build('payroll_items',isset($data) ? $data : NULL);
 	} 


 	function overtime()
 	{
 		$this->load->module('layouts');
		$this->load->library('template');
		$this->template->title(lang('overtime'));
		$data['datepicker'] = TRUE;
		$data['form']       = TRUE; 
		$data['page']       = lang('overtime');
		$data['role']       = $this->tank_auth->get_role_id();
			
			
			$this->template->set_layout('users')
			 ->build('overtime',isset($data) ? $data : NULL);
 	}


 	function status_change()
 	{
		$payroll_total = 0;
		$this->db->where('id',$this->input->post('employeeid'));
		if($this->db->update('users',array('status' =>$this->input->post('status'))))
		{
			$users_list = $this->db->query("SELECT u.*
				FROM `dgt_users` u  
				inner join dgt_bank_statutory bs on bs.user_id = u.id 
				where u.activated = 1  and u.status=1 order by u.created desc")->result_array();

			foreach ($users_list as $user_rows) 
			{
				$payroll_user_id[]=$user_rows['id'];
			}
			for ($p=0; $p <count($payroll_user_id) ; $p++) { 
				$all_statutorys = $this->db->get_where('bank_statutory',array('user_id'=>$payroll_user_id[$p]))->result_array(); 
				foreach ($all_statutorys as $all_statutory) {
					$payroll_total += $all_statutory['salary'];
				}
			}

			$obj = array(
				'status' => 1,
				'payroll_total' => $payroll_total
			);
			echo json_encode($obj);
		}
		else
		{
			$obj = array(
				'status' => 0,
				'payroll_total' => $payroll_total
			);
			echo json_encode($obj);
		}
		exit;
 	}


 	function run_payroll()
 	{
 		$salary_expenses = array();
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

                 	$salary_expenses[] = $all_statutory['salary'];

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
                 	
                //  }
				


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
							$lop_leaves = 0;
						}

						$total_salary = $all_statutory['salary'];
						$one_day = (round($total_salary) / 22);
						$total_lop = ($one_day * $lop_leaves);
				// 		echo $total_lop; exit;


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


			 // $tds=$this->db->query('SELECT `salary_percentage` FROM dgt_tds_settings WHERE `salary_from` <= '.$all_statutory['salary'].' AND `salary_to` >= '.$all_statutory['salary'].'')->row_array();
			  $tds = $this->db->select('salary_percentage')
			         //  ->from('')
			           ->where('salary_from <=', $all_statutory['salary'])
			           ->where('salary_to >=',$all_statutory['salary'])
			           ->get('tds_settings')->row_array();


				$pay_slip_details['deduction||TDS']=(($all_statutory['salary']*$tds['salary_percentage'])/100);
				$pay_slip_details['deduction||ESI']=$esi_amount;
				$pay_slip_details['deduction||PF']=$pf_amount;
				$pay_slip_details['deduction||leave']=round($total_lop);




				$pay_slip_details['payslip_user_id']=$payroll_user_id[$p];



				$array = array();
				$array['user_id'] = $pay_slip_details['payslip_user_id'];

	            $array['p_year'] = $pay_slip_details['payslip_year'];

	            $array['p_month'] = $pay_slip_details['payslip_month'];

	            $currenttotal_salary = array_sum($salary_expenses);



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
				



 			}

 			$cur_salary = date('M Y').' Salary';
			$check_expenses = $this->db->get_where('budget_expenses',array('notes'=>$cur_salary))->num_rows();
 			// echo "Count : ".$check_expenses; exit;
			if($check_expenses == 0){
	 			$expense_array = array(
			 			'category_id' => 1,
			 			's_category_id' => 1,
			 			'notes' => date('M Y').' Salary',
			 			'amount' => $currenttotal_salary,
			 			'expense_date' => date('Y-m-d')
			 		);

	 			$this->db->insert('budget_expenses',$expense_array);
			}




 			 if($result==true) 
            {
                $this->session->set_flashdata('tokbox_success', 'Payslip updated successfully');
                redirect(base_url().'payroll');
            }   
            else
            {
                $this->session->set_flashdata('tokbox_error', 'No changes');
                redirect(base_url().'payroll');
            }


 			
 	}


 	function settings()
	{
		if ($this->input->post()) {
  			$salary_from=$this->input->post('salary_from');
        $salary_to=$this->input->post('salary_to');
        $salary_percentage=$this->input->post('salary_percentage');



        $this->db->empty_table('tds_settings');

        for ($i=0; $i <count($salary_from) ; $i++) 
        { 
            $data=array('salary_from' =>$salary_from[$i],
                        'salary_to' =>$salary_to[$i],
                        'salary_percentage' =>$salary_percentage[$i],
                        );
            $this->db->insert('tds_settings',$data);

        }

        $this->session->set_flashdata('tokbox_success', 'Settings Update Successfully');
        redirect('payroll/settings');
 		}else{
			$this->load->module('layouts');
		$this->load->library('template');
		$this->template->title(lang('payrollsettings'));
		$data['datepicker'] = TRUE;
		$data['form']       = TRUE; 
		$data['page']       = lang('payrollsettings');
		$data['role']       = $this->tank_auth->get_role_id();
		$data['salary_setting'] = App::salary_setting();
			
			
			$this->template->set_layout('users')
			 ->build('payroll_settings',isset($data) ? $data : NULL);
		}
		
	}


	
	
	
}
