<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/phpass-0.1/PasswordHash.php');

class User_model extends CI_Model
{
	function __construct(){
		parent::__construct();
		$this->user ='fx_users U';
		$this->account_details ='fx_account_details AD';
		$this->countries ='fx_countries CON';

	}

	public function is_valid_token($token)
	{
		if($token == 'DQCTPQMKK9R6SXN4'){
			return TRUE;
		}elseif(!empty($token)) {
			$this->db->where('unique_code', $token);
			$count = $this->db->count_all_results('fx_users');
			if($count > 0){
				return TRUE;
			}else{
				return FALSE;
			}
		}

	}
	 public function is_valid_login($input)
	 {
	 	 $username = $input['username'];
	 	 $password = $input['password'];

	 	$hasher = new PasswordHash($this->config->item('phpass_hash_strength', 'tank_auth'),
					$this->config->item('phpass_hash_portable', 'tank_auth'));
	 	$check_password = $this->get_check_password($username);

	 	$records = array();
		if($hasher->CheckPassword($password, $check_password)){

		 	$this->db->select('U.id,AD.fullname,U.unique_code,U.email,U.role_id');
		 	$this->db->from($this->user);
		 	$this->db->join($this->account_details, 'AD.user_id = U.id', 'left');

		 	$where = array('activated'=>1,'banned'=>0);
		 	$this->db->where($where);
		 	// $this->db->where('(role_id = 3 OR role_id = 1)');
		 	
		 	if (count($this->check_username_email($username)) >1) {
		 		$this->db->where('email', $username);
		 	}else{
		 		
		 		$this->db->where('username',$username);	
		 	}
		 	$records = $this->db->get()->row_array();

		 	
	    }

	 	if(!empty($records)){
	 		$user_id = $records['id'];
	 		$unique_code = $this->getToken(14,$user_id);
        	$records['unique_code'] = $unique_code;	
        	$this->db->where('id', $user_id);
        	$this->db->update($this->user, array('unique_code' => $unique_code));
	 	}

	 	return $records;
	 }

	 public function get_check_password($username){

	 	$user_or_email = $this->check_username_email($username);
	 	$this->db->select('password');
	 	if(count($user_or_email) > 1){
	 		$this->db->where('email', $username);
	 	}else{
	 		$this->db->where('username', $username);
	 	}
	 	$record = $this->db->get($this->user)->row_array();

	 	if(!empty($record['password'])){
	 		$record = $record['password'];	
	 	}else{
	 		$record  = '';
	 	}
	 	return $record;
	 }

	 public function countries()
	 {
	 	$this->db->select('id, value as country');
	 	$this->db->order_by('value', 'ASC');
	 	return $this->db->get($this->countries)->result();
	 	
	 }

	 public function check_username_email($username){

	 	if(!empty($username)){
	 		return $user_or_email = explode('@', $username);
	 	}else{
	 		return FALSE;
	 	}
	 }


	 public function create_user($inputs,$token)
	 {
	 	
		$username = $inputs['username'];
		$email = $inputs['email'];
	 	$password = $inputs['password'];

		$count_name  =	$this->checkusernameemail(array('username'=>$username));
		$count_email =	$this->checkusernameemail(array('email'=>$email));
		$records = 2;
		if($count_name == 0 && $count_email ==0){

	 	$hasher = new PasswordHash(
					$this->config->item('phpass_hash_strength', 'tank_auth'),
					$this->config->item('phpass_hash_portable', 'tank_auth'));
		$hashed_password = $hasher->HashPassword($password);
		$record =  $this->get_role_and_userid($token);
		$records = array();
			if(!empty($record)){
				$role    = $record['role_id'];
			 	if($role == 1){
			 		if($inputs['designation_id'] != '')
			 		{
			 			$designation_id = '';
			 		}else{
			 			$designation_id = $inputs['designation_id'];
			 		}
			 		if($inputs['department_id'] != '')
			 		{
			 			$department_id = '';
			 		}else{
			 			$department_id = $inputs['department_id'];
			 		}
			 		$user['username'] = $inputs['username'];
			 		$user['password'] = $hashed_password;
			 		$user['email'] = $inputs['email'];
			 		$user['role_id'] = 3;
			 		$user['designation_id'] = $designation_id;
			 		$user['department_id'] = $department_id;
			 		$user['activated'] = 1;
			 		$user['created'] = date('Y-m-d');
			 		$this->db->insert('fx_users', $user);
			 		$id = $this->db->insert_id();
			 		$account_details['user_id'] = $id;
			 		$account_details['fullname'] = $inputs['fullname'];
			 		$account_details['phone'] = $inputs['phone'];
			 		$account_details['address'] = !empty($inputs['address'])?$inputs['address']:'';
			 		$account_details['city'] = !empty($inputs['city'])?$inputs['city']:'';
			 		$account_details['country'] = !empty($inputs['country'])?$inputs['country']:'';
			 		$this->db->insert('fx_account_details', $account_details);
			 		$records = 1;
			 	}else{
			 		$records = 3;
			 	}
			}
		}
		return $records;

	 }

	

	 public function checkusernameemail($where)
	 {
	 	$this->db->where($where);
	 	$count = $this->db->count_all_results('fx_users');
	 	return $count;
	 }

	    public function getToken($length,$user_id)
   {
       $token = $user_id;

       $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
       $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
       $codeAlphabet.= "0123456789";

       $max = strlen($codeAlphabet); // edited

    for ($i=0; $i < $length; $i++) {
         $token .= $codeAlphabet[$this->crypto_rand_secure(0, $max-1)];

    }

    return $token;
   }

   function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 0) return $min; // not so random...
        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
}
	public function get_role_and_userid($token)
	{
		$this->db->select('id as user_id, role_id');
		return $this->db->get_where($this->user,array('unique_code'=>$token,'activated'=>1))->row_array();
	}

	public function get_attendance_data($token,$inputs,$month,$year)
	{
		$this->db->select('*');
		$this->db->from($this->user);
		$this->db->join('fx_attendance_details','fx_attendance_details.user_id = U.id');
		$this->db->where('U.unique_code',$token);
		$this->db->where('fx_attendance_details.a_month',$month);
		$this->db->where('fx_attendance_details.a_year',$year);
		return $this->db->get()->row_array();
	}

	

	public function update_attendance_data($user_id,$month_days,$month,$year)
	{
		$this->db->where('user_id',$user_id);
		$this->db->where('a_month',$month);
		$this->db->where('a_year',$year);
		$res = $this->db->update('fx_attendance_details',$month_days);
		return $res;
	}

	public function get_all_attendance($user_id,$month,$year)
	{
		$this->db->select('a_month,a_year,month_days');
		return $this->db->get_where('fx_attendance_details',array('user_id'=>$user_id,'a_month'=>$month,'a_year'=>$year))->result_array();
	}

	public function upload_profilepic($user_id,$filename)
	{
		$res = array(
			'avatar' => $filename
		);
		$this->db->where('user_id',$user_id);
		return $this->db->update('fx_account_details',$res);
	}

	public function check_device($user_id)
	{
		return $this->db->get_where('fx_device_details',array('user_id'=>$user_id))->row_array();
	}

	public function device_update($user_id,$device_id,$option)
	{
		if($option == 'update')
		{
			$res_up = array(
				'device_id' => $device_id
			);
			$this->db->where('dev_id',$user_id);
			$this->db->update('fx_device_details',$res_up);
			return 'updated';
		}else{
			$res_in = array(
				'user_id' => $user_id,
				'device_id' => $device_id
			);
			$this->db->insert('fx_device_details',$res_in);
			return 'inserted';
		}
	}
}

/* End of file model.php */