<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Attendance extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model(array( 'App', 'Attendance_model'));
        /*if (!User::is_admin()) {
            $this->session->set_flashdata('message', lang('access_denied'));
            redirect('');
        }*/
        $all_routes = $this->session->userdata('all_routes');
        foreach($all_routes as $key => $route){
            if($route == 'attendance'){
                $routname = attendance;
            } 
        }
        if(empty($routname)){
             $this->session->set_flashdata('message', lang('access_denied'));
            redirect('');
        }
        App::module_access('menu_attendance');
        $this->load->helper(array('inflector'));
        $this->applib->set_locale();
    }

    function index()
    {
        if($this->tank_auth->is_logged_in()) {
            $this->load->module('layouts');
            $this->load->library('template');
            $this->template->title('Attendance');
            $data['datepicker'] = TRUE;
            $data['form']       = TRUE;
            $data['page']       = lang('attendance');
            $data['role']       = $this->tank_auth->get_role_id();
            $data['user_id']    = $this->tank_auth->get_user_id();

            $role_id = $this->tank_auth->get_role_id();
            $page = (($role_id==4) || ($role_id==1))?'attendance':'create_attendance';
            $this->template
                  ->set_layout('users')
                  ->build($page,isset($data) ? $data : NULL);
          }else{
           redirect('');
          }
    }


     function details($user_id)
    {
        if($this->tank_auth->is_logged_in()) {
            $this->load->module('layouts');
            $this->load->library('template');
            $this->template->title('Attendance');
            $data['datepicker'] = TRUE;
            $data['form']       = TRUE;
            $data['page']       = 'attendance';
            $data['role']       = $this->tank_auth->get_role_id();
            $data['user_id']    = $user_id;

            $role_id = $this->tank_auth->get_role_id();
            $page = 'attendance_details';
            $this->template
                  ->set_layout('users')
                  ->build($page,isset($data) ? $data : NULL);
          }else{
           redirect('');
          }
    }

     function get_list(){
        if($_POST){
           $user_id = $_POST['user_id'];
           $date = date("Y-m-d", strtotime($_POST['date']));
           $attendance_list = Attendance_model::get_list($user_id, $date);
           $records = array();
           $length = count($attendance_list);
           $total_hours = 0;
           for($i = 0; $i < $length; ++$i) {
             $row = array();
             $row['fullname'] = $attendance_list[$i]->fullname;
             $row['punch_in_date_time'] = $attendance_list[$i]->punch_in_date_time;
             $row['punch_in_note'] = $attendance_list[$i]->punch_in_note;
             $row['punch_in_address'] = $attendance_list[$i]->in_address;
             $row['punch_out_date_time'] = $attendance_list[$i]->punch_out_date_time;
             $row['punch_out_note'] = $attendance_list[$i]->punch_out_note;
             $row['punch_out_address'] = $attendance_list[$i]->out_address;
             $row['cal_hours'] = $attendance_list[$i]->cal_hours;
             $total_hours += $attendance_list[$i]->cal_hours;

               $row['total_hours'] = '--';
               $j = $i+1;
               $user_id = !empty($attendance_list[$j]->user_id)?($attendance_list[$j]->user_id):'';
               if ((($attendance_list[$i]->user_id) !== $user_id) ||  empty($user_id)) {
                 $row['total_hours'] = $total_hours;
                 $total_hours = 0;
               }
             $records[] = $row;
           }
           echo json_encode($records);
           exit;
        }
      }

      public function attendance_list()
      {
        
        if($this->input->post()){

        $params = $this->input->post();
        $month = $params['attendance_month'];
        $year  = $params['attendance_year'];
        $last_day = $year.'-'.$month.'-1';
       
        $records = array();
        $records['current_page']     = $params['page'];
        $attendance_list = Attendance_model::attendance_list($params); 

        $records['attendance_list']  =  $attendance_list[1];
        $records['total_page']       =  $attendance_list[0];
        $records['last_day']         = date('t',strtotime($last_day));  

        echo json_encode($records);
        exit;
      }
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
   return redirect('attendance');
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
   return redirect('attendance');
   }

   }


   public function attendance_details($user_id,$day,$month,$year)
   {
            $data['user_id'] = $user_id;
            $data['atten_day'] = $day;
            $data['atten_month'] = $month;
            $data['atten_year'] = $year;
             $where     = array('user_id'=>$user_id,'a_month'=>$month,'a_year'=>$year);
             $this->db->select('month_days,month_days_in_out');
             $data['record']  = $this->db->get_where('dgt_attendance_details',$where)->row_array();
            $this->load->view('modal/attendance', $data);
   }


   public function pdf(){
            $year=$this->input->get('year');
            $mon=$this->input->get('month');
            $attend=$this->db->get_where('dgt_attendance_details',array('a_year'=>$year,"a_month"=>$mon))->result_array();
            $this->load->module('layouts');
            $this->load->library('template');
            $this->template->title('Attendance');
            $data['datepicker'] = TRUE;
            $data['form']       = TRUE;
            $data['page']       = 'attendance';
            $data['role']       = $this->tank_auth->get_role_id();
            $data['year']    = $year;
            $data['mon']    = $mon;
            $data['attend']    = $attend;

            $page = 'attendance_pdf';
            
            $html = $this->load->view($page,$data,true);
            $pdf = array(
              'html' => $html,
              'title' => lang('attendance').' '.$user_id,
              'author' => config_item('company_name'),
              'creator' => config_item('company_name'),
              'filename' => lang('attendance').$user_id.'.pdf',
              'badge' => config_item('display_invoice_badge'),
            );
        $this->applib->create_pdf($pdf);

   }

    public function send_attendance($user_id = null)
    {
        if ($this->input->post()) {
            $id = $this->input->post('attendance');
            $send_email = $this->input->post('send_email');
            $send_user=User::get_user_by_email($send_email)['id'];
            $send_filename = $_FILES['atd_file']['name'];
            $this->load->library('upload', $config);            
            $config['upload_path'] = './assets/uploads';
            $config['allowed_types'] = '*';
            $this->upload->initialize($config);
            $filename='./assets/uploads/'.$send_filename;
            if (!file_exists($filename)) $this->upload->do_upload("atd_file");    

            $my_email=User::view_user($id)->email;


            $activity = array(
                'user' => $id,
                'module' => 'attendance',
                'module_field_id' => "mark",
                'activity' => 'activity_attendance_sent',
                'icon' => 'fa-envelope',
                'value1' => $send_filename,
                'value2' => $send_user,

            );

            App::Log($activity);
            // Applib::go_to('invoices/view/'.$id, 'success', lang('invoice_sent_successfully'));
            $this->session->set_flashdata('tokbox_success', 'Attendance sent successfully');
            redirect('attendance');
        } else {
            $res=$this->db->where('config_key','company_logo')->get('config')->result()[0];
            $data['id'] = $user_id;
            $data['logo'] = $res->value;
            $this->load->view('modal/email_attendance', $data);
        }
    }
    function ajax_atd(){
        $send_email=$this->input->post('send_email');
	    $my_file =  $this->input->post('file_Name');
	    //$subject = $this->input->post('subject');
      
	    $email = '';
	    $user = User::get_id();
		$email = User::login_info($user)->email;

/*		$headers = 'From:' . $email . "\r\n" .
		'Reply-To:'. $email . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
	
	    if(!$send_email){
			echo 'false';  exit;
        } else {
			$send_user=User::get_user_by_email($send_email);
			//if(!$send_user){ echo 'false';  exit;  }

			echo mail($send_email,$subject,$pdf_path,$headers); exit;
			//echo 'true'; exit;
        }
*/		
		//$my_file = "somefile.zip";
//		$my_path = "C:/Users/Rocket_2020/Downloads/";
//		$my_name = "";
//		$my_mail = $email;
//		$my_replyto = $email;
//		$my_subject = $subject;
//		$my_message = "";
		
		//echo "true"; exit;
//		echo mail_attachment($my_file, $my_path, $send_email, $my_mail, $my_name, $my_replyto, $my_subject, $my_message);
//		exit;
		
		$to          = "email1@domain.com, email2@domain.com"; // addresses to email pdf to
		$from        = $email; // address message is sent from
		$subject     = $this->input->post('subject'); // email subject
		$body        = "<p>The PDF is attached.</p>"; // email body
		$pdfLocation = "C:/Users/".getenv("username")."/Downloads/";
		$pdfName     = $this->input->post('file_Name'); // pdf file name recipient will get
		$filetype    = "application/pdf"; // type

		// create headers and mime boundry
		$eol = PHP_EOL;
		$semi_rand     = md5(time());
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
		$headers       = "From: $from$eol" .
		  "MIME-Version: 1.0$eol" .
		  "Content-Type: multipart/mixed;$eol" .
		  " boundary=\"$mime_boundary\"";

		// add html message body
		  $message = "--$mime_boundary$eol" .
		  "Content-Type: text/html; charset=\"iso-8859-1\"$eol" .
		  "Content-Transfer-Encoding: 7bit$eol$eol" .
		  $body . $eol;

		// fetch pdf
		$file = fopen($pdfLocation.$my_file, 'rb');
		$data = fread($file, filesize($pdfLocation.$my_file));
		fclose($file);
		$pdf = chunk_split(base64_encode($data));
		
		// attach pdf to email
		$message .= "--$mime_boundary$eol" .
		  "Content-Type: $filetype;$eol" .
		  " name=\"$pdfName\"$eol" .
		  "Content-Disposition: attachment;$eol" .
		  " filename=\"$pdfName\"$eol" .
		  "Content-Transfer-Encoding: base64$eol$eol" .
		  $pdf . $eol .
		  "--$mime_boundary--";

		// Send the email
		echo mail($send_email, $subject, $message, $headers);
		exit;
		
	}
}
