<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/phpass-0.1/PasswordHash.php');

class Api_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();

		$this->user = 'fx_users U';
		$this->users = 'fx_users';
		$this->account_details = 'fx_account_details AD';
		$this->account_detail = 'fx_account_details';
		$this->companies = 'fx_companies C';
		$this->countries = 'fx_countries COU';
		$this->designation = 'fx_designation D';
		$this->designation_chk = 'fx_designation';
		$this->departments = 'fx_departments DE';
		$this->department = 'fx_departments';
		$this->holidays = 'fx_holidays H';
		$this->holiday = 'fx_holidays';
		$this->user_leaves = 'fx_user_leaves UL';
		$this->user_leave = 'fx_user_leaves';
		$this->leave_types = 'fx_leave_types LT';
		$this->payslips = 'fx_payslip PS';
		$this->salary = 'fx_salary SA';
		$this->projects = 'fx_projects PJ';
		$this->tasks = 'fx_tasks TK';
		$this->taskfiles = 'fx_task_files TKF';
		$this->comments = 'fx_comments CM ';
		$this->estimate = 'fx_estimates ES ';
		$this->estimate_items = 'fx_estimate_items ESI ';
		$this->invoice = 'fx_invoices IN';
		$this->items = 'fx_items IT';
		$this->payments = 'fx_payments PY';

	 
	}

	public function employee_list($token,$inputs,$type=1)
	{
		
		$record =  $this->get_role_and_userid($token);
		if(!empty($record)){
			
			$role    = $record['role_id'];
		 if($role == 1){	
			$user_id = $record['user_id'];
			$page    = !(empty($inputs['page']))?$inputs['page']:1;
			// $search  = !(empty($inputs['search']))?$inputs['search']:'';

		$this->db->select('U.id as user_id,username,U.email,U.role_id,U.designation_id,U.activated,DATE_FORMAT(U.created,"%d %M %Y") as created,AD.fullname,IF(AD.dob !="0000-00-00",AD.dob,"-") as dob,AD.gender,AD.phone,IF(DE.deptname IS NOT NULL,DE.deptname,"-") AS department,IF(D.designation IS NOT NULL,D.designation,"-") AS designation,IF(DE.deptid IS NOT NULL,DE.deptid,0) as department_id,IF(COU.value IS NOT NULL,COU.value,"-")as countryname');

		$this->db->from($this->user);
		$this->db->join($this->account_details,'AD.user_id=U.id','LEFT');
		//$this->db->join($this->companies,'C.co_id=AD.company','LEFT');
		$this->db->join($this->designation,'D.id=U.designation_id','LEFT');
		$this->db->join($this->departments,'DE.deptid=U.department_id','LEFT');
		$this->db->join($this->countries,'COU.id=AD.country','LEFT');
		$this->db->where('U.role_id',3);
		$this->db->where('U.activated',1);
		$this->db->where('U.banned',0);
 		if(!empty($inputs['email'])){
			$this->db->like('U.email', $inputs['email'], 'BOTH');
		} 
		if(!empty($inputs['fullname'])){
			$this->db->like('AD.fullname', $inputs['fullname'], 'BOTH');
		} 
		if(!empty($inputs['designation'])){
			$this->db->like('U.designation_id', $inputs['designation'], 'BOTH');
		}
		if(!empty($inputs['department'])){
			$this->db->like('D.department_id', $inputs['department'], 'BOTH');
		} 
		if(!empty($inputs['employee_id'])){
			$id =  $inputs['employee_id'];
			$id = str_replace('FP-', '', $id);
			$this->db->like('U.id', $id, 'BOTH');
		} 

		if($type == 1){
			  return $this->db->count_all_results();	
		}else{
			$page = !empty($inputs['page'])?$inputs['page']:'';
			$limit = $inputs['limit'];
			if($page>=1){
				$page = $page - 1 ;
			}
			$page =  ($page * $limit);	
		 	$this->db->order_by('U.id', 'ASC');
		 	$this->db->limit($limit,$page);
			return $this->db->get()->result();
		 }
		  }else{
		  	if($type==1){
		  		return 0;
		  	}else{
		  		return array();
		  	}
		  }
		} 
	}

	public function remove_profile($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
			$user_id = $input['user_id'];
		 	if($role == 1){
				$this->db->where('role_id',3);
				$this->db->where('id',$user_id);
				return $this->db->update($this->user, array('banned'=>1,'activated'=>0));
		 	}
		}
		return $records;
	}

	public function view_profile($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
			$user_id = $record['user_id'];

		 	if($role == 3 || $role == 1){
		 		if($role == 1){
		 			$user_id = $input['user_id'];
		 		}
		 		$this->db->select('U.id as user_id,username,U.email,U.role_id,U.designation_id,U.activated,DATE_FORMAT(U.created,"%d %M %Y") as created,AD.fullname,IF(AD.dob !="0000-00-00",AD.dob,"-") as dob,AD.gender,AD.avatar,AD.phone,IF(DE.deptname IS NOT NULL,DE.deptname,"-") AS department,IF(D.designation IS NOT NULL,D.designation,"-") AS designation,IF(D.department_id IS NOT NULL,D.department_id,0) as department_id,IF(AD.city IS NOT NULL,AD.city,"-") as city,IF(AD.country IS NOT NULL,AD.country,"-") as country,address,IF(COU.value IS NOT NULL,COU.value,"-") as countryname');

				$this->db->from($this->user);
				$this->db->join($this->account_details,'AD.user_id=U.id','LEFT');
				//$this->db->join($this->companies,'C.co_id=AD.company','LEFT');
				$this->db->join($this->designation,'D.id=U.designation_id','LEFT');
				$this->db->join($this->departments,'DE.deptid=D.department_id','LEFT');
				$this->db->join($this->countries,'COU.id=AD.country','LEFT');
				$this->db->where('U.role_id',3);
				$this->db->where('U.id',$user_id);
				$records = $this->db->get()->row_array();
				$records['education_details'] = json_encode($this->education_details($user_id));
				$records['experience_information'] = json_encode($this->experience_information($user_id));
		 	}
		}
		return $records;
	}

	public function education_details($user_id)
	{
		$this->db->where('user_id', $user_id);
		return $this->db->get('fx_profile_education_details')->result();
	}

	public function experience_information($user_id)
	{
		$this->db->where('user_id', $user_id);
		return $this->db->get('fx_profile_experience_informations')->result();
	}

	public function profile_update($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			$user_id    = $record['user_id'];

			if($role == 3 || $role == 1){
		 		if($role == 1){
		 			$user_id = $input['user_id'];
		 		}

		 		$account_details = array();
				
		 		if(!empty($input['city'])){
		 			$account_details['city'] = $input['city'];  
		 		}
		 		if(!empty($input['country'])){
		 			$account_details['country'] = $input['country'];  
		 		}
		 		if(!empty($input['address'])){
		 			$account_details['address'] = $input['address'];  
		 		}
				$account_details = array('fullname'=>$input['fullname'],'phone'=>$input['phone']);

				if(!empty($input['gender'])){
					$account_details['gender'] = $input['gender'];	
				}
				if(!empty($input['dob']) && $input['dob'] !='0000-00-00'){
					$account_details['dob'] = date('y-m-d',strtotime($input['dob']));	
				}

				$user = array('email'=>$input['email']);

				
					$user['designation_id'] = !empty($input['designation_id'])?$input['designation_id']:"0";	
				

				
					$user['department_id'] = !empty($input['department_id'])?$input['department_id']:"0";	
				




				$this->db->where('user_id', $user_id);
				$this->db->update($this->account_detail, $account_details);
 				
 				$this->db->where('id', $user_id);
				$this->db->update($this->users, $user);
					if($role == 3){
					$this->db->where('user_id', $user_id);
					$this->db->delete('fx_profile_education_details');
					
					$education_details  = !empty($input['education_details'])?$input['education_details']:'';
					$education_new = array();
					if(!empty($education_details)){
						$education_new = json_decode($education_details,true);
						$final = array();
						foreach ($education_new as $keyvalue) {
							$keyvalue['user_id'] = $user_id;
							$final[] = $keyvalue;
						}
						$education_new = $final;
					}
					 
					
					if(count($education_new)>0){
						$this->db->insert_batch('fx_profile_education_details', $education_new);
						 
					}

					$this->db->where('user_id', $user_id);
					$this->db->delete('fx_profile_experience_informations');

					$experience_information  = !empty($input['experience_information'])?$input['experience_information']:'';
					$experience_information_new = array();
					if(!empty($experience_information)){
						$experience_information_new = json_decode($experience_information,true);
						$final = array();
						foreach ($experience_information_new as $keyvalue) {
							$keyvalue['user_id'] = $user_id;
							$final[] = $keyvalue;
						}
						$experience_information_new = $final;
					}
				 	 
			 
					if(count($experience_information_new) > 0){
						$this->db->insert_batch('fx_profile_experience_informations', $experience_information_new);	
						 
					}
				}
				 
				return 1;
			}
		}		
	}

	public function forgot_password($token,$input)
	{
		$username = $input['username'];
		// $record =  $this->get_role_and_userid($token);
		$record =  $this->get_role_and_username($username);
	    $records = array();
		if(!empty($record)){
			$role     = $record['role_id'];
			$user_id  = $record['user_id'];
		 	if($role == 3){
		 		
		 		$this->db->select('U.id as user_id,U.username,AD.fullname,U.unique_code,U.email,U.role_id');
		 		$this->db->from($this->user);
		 		$this->db->join($this->account_details, 'AD.user_id = U.id', 'left');
			 	$where = array('activated'=>1,'banned'=>0);
			 	$this->db->where($where);
			 	$this->db->where('(role_id = 3)');
		 	
		 		if (count($this->check_username_email($username)) >1) {
		 				$this->db->where('email', $username);
		 			}else{
		 		 		$this->db->where('username',$username);	
		 			}
			 		$records = $this->db->get('')->row_array();
			 		if(!empty($records)){
			 			$new_pass_key = md5(rand().microtime());
			 			$data = array(
							'id'		=> $records['user_id'],
							'username'		=> $records['username'],
							'email'			=> $records['email'],
							'new_password_key'	=> $new_pass_key,
							'new_password_requested'	=> date('Y-m-d h:i:s'),
						);
						$this->db->where('id', $records['user_id']);
						$result = $this->db->update($this->users, $data);
						$auth = modules::load('auth/auth/');
						
						$data['user_id'] =  $records['user_id'];
						$data['new_pass_key'] =  $new_pass_key;

						 $auth->_send_email('forgot_password', $data['email'], $data);

						 return $result;
			 		}
		 	}
		}
		return FALSE;
	}

		 public function check_username_email($username){

	 	if(!empty($username)){
	 		return $user_or_email = explode('@', $username);
	 	}else{
	 		return FALSE;
	 	}
	 }


	public function change_password($token,$input)
	{
	    $record =  $this->get_role_and_userid($token);
	    $password = !empty($input['current_password'])?$input['current_password']:'';
	    $new_password = $input['confirm_password'];
		$records = array();
		if(!empty($record)){
			$role     = $record['role_id'];
			$user_id  = $record['user_id'];

		 	if($role == 1){
		 			if(empty($input['user_id'])){
		 				return FALSE;
		 			}
		 			$user_id  = $input['user_id'];
		 			$hasher = new PasswordHash(
					$this->config->item('phpass_hash_strength', 'tank_auth'),
					$this->config->item('phpass_hash_portable', 'tank_auth'));
					$hashed_password = $hasher->HashPassword($new_password);
					$this->db->where('id', $user_id);
	 				return $this->db->update($this->users,array('password'=>$hashed_password));

		 	}elseif($role == 3){

		 		$hasher = new PasswordHash($this->config->item('phpass_hash_strength', 'tank_auth'),
			    $this->config->item('phpass_hash_portable', 'tank_auth'));
	 			
	 			$this->db->where('id', $user_id);
	 			$user_details = $this->db->get($this->users)->row_array();
	 			if(!empty($user_details['password'])){
			 		$check_password = $user_details['password'];	
	 			}else{
	 				$check_password  = '';
	 			}

	 			if($hasher->CheckPassword($password, $check_password)){
	 				$hasher = new PasswordHash(
					$this->config->item('phpass_hash_strength', 'tank_auth'),
					$this->config->item('phpass_hash_portable', 'tank_auth'));
					$hashed_password = $hasher->HashPassword($new_password);
					$this->db->where('id', $user_id);
	 				return $this->db->update($this->users,array('password'=>$hashed_password));
	 			}

		 	}
		}
		return FALSE;	
	}


	public function departments($token)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
		 	if($role == 1 || $role == 3){
		 		$this->db->order_by('deptname', 'ASC');
		 		$this->db->select('deptid,deptname');
		 		$records = $this->db->get($this->departments)->result();
		 	}
		}
		return $records;
	}

		public function leave_type($token)
		{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
		 	if($role == 1 || $role == 3){
		 		$this->db->order_by('leave_type', 'ASC');
		 		$this->db->select('id,leave_type,leave_days');
		 		$this->db->where('status',0);
		 		$records = $this->db->get($this->leave_types)->result();
		 	}
		}
		return $records;
	}

	public function create_department($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			if($role == 1){
				$where['deptname'] = $input['department'];

				$alreay = $this->check_input_exists($this->departments,$where);
				if($alreay==0){
					$this->db->insert($this->department, array('deptname'=>$input['department']));
					return 1;// Insert Successfully..
				}else{
					return 2;// Already exists
				} 
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
		
	}

		public function leave_apply($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			
			$role    = $record['role_id'];
			$user_id = $record['user_id'];

			if($role == 3){
				$where['leave_from'] = date('Y-m-d',strtotime($input['leave_from']));
				$where['leave_to'] = date('Y-m-d',strtotime($input['leave_to']));
				$where['leave_type'] = $input['leave_type'];
				$where['user_id'] = $user_id;

				$alreay = $this->check_input_exists($this->user_leave,$where);

				if($alreay==0){
					$earlier = new DateTime($where['leave_from']);
					$later = new DateTime($where['leave_to']);	
					$diff = $later->diff($earlier)->format("%a");

					$input['leave_days'] = $diff +1;
					$input['user_id'] = $user_id;
					$this->db->insert($this->user_leave, $input);
					return 1;// Insert Successfully..
				}else{
					return 2;// Already exists
				} 
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
		
	}

	public function check_input_exists($table,$where)
	{
		$this->db->where($where);
		return $this->db->count_all_results($table);
	}

	public function holidays($token,$input)
	{
		$hyear  = !empty($input['hyear'])?$input['hyear']:date('Y');
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
		 	if($role == 1 || $role == 3){

		 		$this->db->select('id,title,description,holiday_date');
		 		$this->db->order_by('title', 'ASC');
		 		$this->db->where('status',0);
		 		$this->db->like('holiday_date', $hyear, 'BOTH');
		 		$records = $this->db->get($this->holidays)->result();
		 	}
		}
		return $records;
	}

	public function remove_holiday($token,$input)
	{
		
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
		 	if($role == 1){
				$this->db->where('id',$input['id']);
				return $this->db->update($this->holidays,array('status'=>1));
		 	}
		}
		return $records;
	}

		public function leaves($token,$input,$type)
	{
		
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
			$user_id    = $record['user_id'];
			$leave_type    = !empty($input['leave_type'])?$input['leave_type']:'';
			$leave_status    = ($input['leave_status'] != '')?$input['leave_status']:'';

		 		$this->db->select('UL.*,LT.leave_type as l_type,AD.fullname');
		 		$this->db->from($this->user_leaves);
		 		$this->db->join($this->leave_types, 'LT.id = UL.leave_type', 'left');
		 		$this->db->join($this->account_details, 'AD.user_id = UL.user_id', 'left');
		 		$this->db->where('UL.status != 4');
		 		if(!empty($leave_type)){
		 			$this->db->where('UL.leave_type',$leave_type);
		 		}
		 		if($leave_status != ''){
		 			$this->db->where('UL.status',$leave_status);
		 		}
		 		$this->db->group_by('UL.id');
		 		if($role == 3){	
		 			$this->db->where('UL.user_id',$user_id);
		 		}
		 		$this->db->where("DATE_FORMAT(UL.leave_from,'%Y')",date('Y'));
		 			
		 			
		 		if($type == 1){
		 			 
		 			$records = $this->db->count_all_results($this->holidays);
		 			
		 		}elseif($type == 2){

		 			$this->db->order_by('UL.id', 'DESC');
		 			$limit = $input['limit'];
		 			$page  = !empty($input['page'])?$input['page']:1;
		 			$start = 0;
		 			if($page > 1){
		 				$page -=1;
		 				$start = ($page * $limit);
		 			} 

		 			$this->db->limit($limit,$start);
		 			$records = $this->db->get($this->holidays)->result();
		 			
		 		}
	
		}
		return $records;
	}

	public function designations($token,$id)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
		 	if($role == 1 || $role == 3){
		 		
		 		$this->db->select('id,designation');
		 		$this->db->where('department_id', $id);
		 		$this->db->order_by('designation', 'ASC');
		 		$records = $this->db->get($this->designation)->result();
		 	}
		}
		return $records;
	}


		public function create_designation($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			if($role == 1){
				$where['designation'] = $input['designation'];
				$where['department_id'] = $input['department_id'];

				$alreay = $this->check_input_exists($this->designation_chk,$where);
				if($alreay==0){
					$this->db->insert($this->designation_chk,$where);
					return 1;// Insert Successfully..
				}else{
					return 2;// Already exists
				}
 
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
		
	}
	
	public function create_holiday($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			if($role == 1){

				$where['title'] = $input['title'];
				
				$where['holiday_date'] = date('Y-m-d',strtotime($input['holiday_date']));

				$alreay = $this->check_input_exists($this->holiday,$where);
				if($alreay==0){
					$where['description'] = $input['description'];
					$this->db->insert($this->holiday,$where);
					return 1;// Insert Successfully..
				}else{
					return 2;// Already exists
				}
 
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
		
	}

	public function edit_holiday($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			if($role == 1){

				$where['title'] = $input['title'];
				$where['holiday_date'] = date('Y-m-d',strtotime($input['holiday_date']));

				$alreay = $this->check_input_exists($this->holiday,$where);
				if($alreay==0){
					$where['description'] = $input['description'];
					$this->db->where('id',$input['id']);
					$this->db->update($this->holiday,$where);
					 
					return 1;// Insert Successfully..
				}else{
					return 2;// Already exists
				}
 
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
		
	}



	public function leave_cancel($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			$user_id = $record['user_id'];

			if($role == 3){

				$where['id'] = $input['leave_id'];
				$this->db->where($where);
				return $this->db->update($this->user_leave,array('status'=>$input['leave_status']));
 
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
		
	}

	public function leave_approve_reject($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			$user_id = $record['user_id'];

			if($role == 1){

				$where['id'] = $input['leave_id'];
				$this->db->where($where);
				return $this->db->update($this->user_leave,array('status'=>$input['leave_status']));
 
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
		
	}

	public function get_role_and_userid($token)
	{
		$this->db->select('id as user_id, role_id');
		return $this->db->get_where($this->user,array('unique_code'=>$token,'activated'=>1))->row_array();
	}

	public function get_role_and_username($username)
	{
		$this->db->select('id as user_id, role_id');
		return $this->db->get_where($this->user,array('username'=>$username,'activated'=>1))->row_array();
	}


	public function users_list_payslip($user_id)
	{
		$this->db->select('*');
		$this->db->from($this->user);
		$this->db->join($this->account_details,'AD.user_id = U.id');
		$this->db->join($this->designation,'D.id = U.designation_id');
		$this->db->where('U.id',$user_id);
		$this->db->where('U.role_id !=',1);
		return $this->db->get()->row_array();
	}

	public function get_salary($user_id,$p_salary_id)
	{
		
		return $this->db->get_where($this->salary,array('user_id'=>$user_id,'id'=>$p_salary_id))->row_array();
	}

	public function get_payslips($user_id,$year,$month,$input,$type)
	{	
			   if(!empty($user_id))
				{
					$this->db->where('user_id',$user_id);
				}
				if(!empty($year))
				{
					$this->db->where('p_year',$year);
				}
				if(!empty($month))
				{
					$this->db->where('p_month',$month);
				}

				if($type == 1){
		 			 
		 			$records = $this->db->count_all_results($this->payslips);
		 			
		 		}elseif($type == 2){

		 			$limit = $input['limit'];
		 			$page  = !empty($input['page'])?$input['page']:1;
		 			$start = 0;
		 			if($page > 1){
		 				$page -=1;
		 				$start = ($page * $limit);
		 			} 

		 			$this->db->limit($limit,$start);
		 			$records =  $this->db->get($this->payslips)->result_array();
		 			
		 		}



		return $records;
	}

	public function check_payslip_exist($user_id,$year,$month)
	{
		return $this->db->get_where('fx_payslip',array('user_id'=>$user_id,'p_year'=>$year,'p_month'=>$month))->num_rows();
	}

	public function check_net_exist($user_id,$amount)
	{
		return $this->db->get_where('fx_salary',array('user_id'=>$user_id,'amount'=>$amount))->num_rows();
	}

	public function update_user_payslip($user_id,$year,$month,$result)
	{
		$where = array(
			'user_id' => $user_id,
			'p_year' =>$year,
			'p_month' => $month
		);
		$this->db->where($where);
		return $this->db->update('fx_payslip',$result);
	}

	public function get_user_payslip($user_id)
	{
		$this->db->select('*');
		$this->db->from($this->user);
		$this->db->join($this->account_details,'AD.user_id = U.id');
		$this->db->join($this->designation,'D.id = U.designation_id');
		$this->db->where('U.id',$user_id);
		$this->db->where('U.role_id !=',1);
		return $this->db->get()->row_array();
	}

	public function get_payslip_user($user_id,$year,$month)
	{
		return $this->db->get_where($this->payslips,array('user_id'=>$user_id,'p_year'=>$year,'p_month'=>$month))->row_array();
	}

	public function all_users()
	{
		$this->db->select('U.id as user_id,AD.fullname');
		$this->db->from($this->user);
		$this->db->join($this->account_details,'AD.user_id = U.id');
		$this->db->where('U.role_id !=',1);
		$this->db->where('U.activated',1);
		return $this->db->get()->result_array();
	}

	public function over_all_projects()
	{
		return $this->db->get($this->projects)->result_array();
	}

	public function get_projectByUserId($user_id)
	{
		return $this->db->get_where($this->projects,array('client'=>$user_id))->result_array();
	}
	
	public function task_by_project($project_id)
	{
		return $this->db->get_where($this->tasks,array('project'=>$project_id))->result_array();
	}

	public function get_task_status($project_id,$status)
	{
		if($status == "open")
		{
			$result = $this->db->get_where($this->tasks,array('task_progress !='=>100,'project'=>$project_id))->result_array();
		}else{
			$result = $this->db->get_where($this->tasks,array('task_progress ='=>100,'project'=>$project_id))->result_array();
		}
		return $result;
	}

	public function get_task_files($tasks)
	{
		$file_count = array();
		foreach($tasks as $task)
		{
			$res = $this->db->get_where($this->taskfiles,array('task'=>$task['t_id']))->result_array();
			if(($res != 0) || ($res != ''))
			{
				$file_count[] = count($res);
			}
		}
		return array_sum($file_count);
	}

	public function get_comment_project($project_id)
	{
		return $this->db->get_where($this->comments,array('project'=>$project_id))->result_array();
	}

	public function get_user_detail($designation,$ids)
	{
		$all_users = array();
		if($designation == 'lead')
		{
			$all_users = $this->db->select('U.id as user_id,AD.fullname,AD.avatar')
			->from($this->user)
			->join($this->account_details,'U.id=AD.user_id')
			->where('U.id',$ids)
			->get()->row_array();
		}else{
			$ids = unserialize($ids); 
			foreach ($ids as $id) {
				$all_users[] = $this->db->select('U.id as user_id,AD.fullname,AD.avatar')
				->from($this->user)
				->join($this->account_details,'U.id=AD.user_id')
				->where('U.id',$id)
				->get()->row_array();
			}
		}
		return $all_users;
	}

	public function get_all_clients()
	{
		return $this->db->select('*')
				->from($this->companies)
				->join($this->account_details,'AD.id = C.primary_contact','left')
				->where('C.is_lead','0')
				->get()
				->result_array();
	}

	public function get_clientById($co_id)
	{
		return $this->db->select('*')
				->from($this->companies)
				->join($this->account_details,'AD.id = C.primary_contact','left')
				->where('C.is_lead','0')
				->where('C.co_id',$co_id)
				->get()
				->row_array();
	}

	public function get_all_estimate()
	{
		return $this->db->select('*') 
					->from($this->estimate)
					->join($this->user,'ES.client = U.id')
					->join($this->account_details,'AD.user_id = U.id')
					->get()->result_array();
	}

	public function get_estimateByClient($co_id)
	{
		return $this->db->select('*') 
					->from($this->estimate)
					->join($this->user,'ES.client = U.id')
					->join($this->account_details,'AD.user_id = U.id')
					->where('ES.client',$co_id)
					->get()->result_array();
	}

	public function get_estimate_cost($estimate_id)
	{
		return $this->db->get_where($this->estimate_items,array('estimate_id'=>$estimate_id))->result_array();
	}

	public function get_company_details($company_id)
	{
		return $this->db->get_where($this->companies,array('co_id'=>$company_id))->row_array();
	}

	public function get_all_invoices()
	{
		return $this->db->select('*') 
					->from($this->invoice)
					->join($this->user,'IN.client = U.id')
					->join($this->account_details,'AD.user_id = U.id')
					->get()->result_array();
	}

	public function get_invoicesbyClient($client_id)
	{
		return $this->db->select('*') 
					->from($this->invoice)
					->join($this->user,'IN.client = U.id')
					->join($this->account_details,'AD.user_id = U.id')
					->where('IN.client',$client_id)
					->get()->result_array();
	}

	public function get_invoice_items($invoice_id)
	{
		return $this->db->get_where($this->items,array('invoice_id'=>$invoice_id))->result_array();
	}

	public function get_invoice_payment($invoice_id)
	{
		return $this->db->get_where($this->payments,array('invoice'=>$invoice_id))->result_array();
	}

	public function get_userById($user_id)
	{
		return $this->db->get_where($this->account_detail,array('user_id'=>$user_id))->row_array();
	}

	public function get_invoiceByClientId($client_id)
	{
		// echo $client_id; exit;
		return $this->db->get_where('fx_invoices',array('client'=>$client_id))->result_array();
	}

	public function get_allProjectsByClient($client_id)
	{
		return $this->db->get_where('fx_projects',array('client'=>$client_id))->result_array();
	}

	public function get_allEstimateByClient($client_id)
	{
		return $this->db->select('*')
		->from('fx_estimates E')
		->join('fx_estimate_items EI','E.est_id = EI.estimate_id','left')
		->where('E.client',$client_id)
		->get()->result_array();
	}

	public function get_deviceIdByUser($user_id)
	{
		return $this->db->get_where('fx_device_details',array('user_id'=>$user_id))->row_array();
	}

	public function get_taskDetails($task_id)
	{
		// return $this->db->select('*')
		// 		->from($this->tasks)
		// 		->join($this->projects,'PJ.project_id = TK.project')
		// 		->where('TK.t_id',$task_id)
		// 		->get()->row_array();
		return $this->db->get_where('fx_tasks',array('t_id'=>$task_id))->result_array();
	}

	public function get_taskfileById($task_id)
	{
		return $this->db->get_where('fx_task_files',array('task'=>$task_id))->result_array();
	}

	public function get_common_session()
	{
		return $this->db->get('fx_chat_common_session',array('com_sess_id'=>1))->row_array();
	}

	public function check_connectionidByUser($user_id)
	{
		return $this->db->get_where('fx_chat_connectionids',array('user_id'=>$user_id))->row_array();
	}

	public function connectionid_status($user_id,$connection_id,$status)
	{
		if($status == 'update'){
			$res = array('connection_id' => $connection_id);
			$this->db->where('user_id',$user_id);
			$result = $this->db->update('fx_chat_connectionids',$res);
		}elseif($status == 'insert'){
			$res = array('user_id' => $user_id,'connection_id' => $connection_id);
			$result = $this->db->insert('fx_chat_connectionids',$res);
		}
		return $result;
	}

	public function get_all_chat_messagesByUserId($from_id,$to_id)
	{
		$sql= "SELECT * FROM `fx_chat_conversations` WHERE `from_id` = '$from_id' AND `to_id` = '$to_id' OR `from_id` = '$to_id' AND `to_id` = '$from_id' AND `msg_type` = 'one'";
			$query = $this->db->query($sql);
			return $query->result_array();
	}

	public function get_group_members($group_id)
	{
		return $this->db->get_where('fx_chat_group_members',array('group_id'=>$group_id))->result_array();
	}

	public function get_group_message($group_id)
	{
		return $this->db->get_where('fx_chat_conversations',array('msg_type'=>'group','group_id'=>$group_id))->result_array();
	}

	public function get_all_chat_detailsByUserId($user_id)
	{
		$sql= "SELECT * FROM `fx_chat_conversations` WHERE `from_id` = '$user_id' OR `to_id` = '$user_id' AND `msg_type` = 'one' ORDER BY `msg_id`  DESC";
			$query = $this->db->query($sql);
			return $query->result_array();
	}

	public function get_all_groupmembers($user_id)
	{
		return $this->db->get_where('fx_chat_group_members',array('login_id'=>$user_id))->result_array();
	}

	public function get_groupname($group_id)
	{
		return $this->db->get_where('fx_chat_group_details',array('group_id'=>$group_id))->row_array();
	}

	public function get_last_msg($group_id)
	{
		$this->db->order_by('msg_id','DESC');
		$this->db->limit(1);
		return $this->db->get_where('fx_chat_conversations',array('group_id' => $group_id,'msg_type' =>'group'))->row_array();

	}

}

/* End of file Api_model.php */
/* Location: ./application/controllers/Api_model.php */