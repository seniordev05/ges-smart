<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Time_sheets extends MX_Controller {

	function __construct()
	{
		parent::__construct();
		User::logged_in();

		$this->load->module('layouts');	
		$this->load->library(array('template','form_validation'));
		$this->template->title('time_sheets');
		$this->load->model(array('App','timesheet_model'));

		$this->applib->set_locale();
		$this->load->helper('date');
	}

	function index()
	{
		$this->load->module('layouts');

		$this->load->library('template');

		$this->template->title(lang('timesheets'));
		$this->session->unset_userdata('search_employee');
		$this->session->unset_userdata('search_from_date');
		$this->session->unset_userdata('search_to_date');
		 

		// $this->template->title(lang('users').' - '.config_item('company_name'));
		$data['page'] = lang('timesheets');
		$data['datatables'] = TRUE;
		$data['form'] = TRUE;
		$data['datepicker'] = TRUE;
		// $data['users'] = $this->timesheet_model->get_all_users_detail();
		$user_id = $this->session->userdata('user_id');
		$role_id = $this->session->userdata('role_id');
		if(!$_POST){
				if(($role_id != 1) && ($role_id != 4))
				{
					// echo $user_id; exit;
					$data['all_timesheet'] = $this->timesheet_model->get_user_timesheets($user_id);
					// echo "<pre>"; print_r($data['all_timesheet']); exit;
					$data['projects'] = $this->timesheet_model->get_all_user_projects($user_id);
				}else{
					$data['all_timesheet'] = $this->timesheet_model->get_all_timesheets();

				}
		}else{
			$inputs = $this->input->post();
			// print_r($inputs); exit;
				if(($role_id != 1) && ($role_id != 4))
				{
					$data['all_timesheet'] = $this->timesheet_model->get_all_timesheets_search($inputs,$user_id);
					$data['projects'] = $this->timesheet_model->get_all_user_projects($user_id);
				}else{
					$data['all_timesheet'] = $this->timesheet_model->get_all_timesheets_search($inputs);

				}

		}

		$this->template

			 ->set_layout('users')

			 ->build('timesheets_view',isset($data) ? $data : NULL);
	}

	public function add_timesheet()
	{
		$user_id = $this->session->userdata('user_id');
		$project_name = $this->input->post('project_name');
		$timeline_desc = $this->input->post('timeline_desc');
		$timeline_hours = $this->input->post('timeline_hours');
		$timeline_date = $this->input->post('timeline_date');
		$tm_date = date("Y-m-d", strtotime($timeline_date));
		$check_status = $this->timesheet_model->check_timesheetByDate($user_id,$tm_date);
		$total_hours = array();
		for($i=0;$i<count($check_status);$i++)
		{
			$time    = explode(':', $check_status[$i]['hours']);
			$minutes = ($time[0] * 60.0 + $time[1] * 1.0);
			$total_hours[] = $minutes;
		}
		$total_minitues =  array_sum($total_hours); 
		// echo $total_minitues; exit;
		// if($total_minitues > 480)
		// {
		// 	echo "error_daily"; exit;
		// }else{

			$current_hour    = explode(':', $timeline_hours);
			$current_minutes = ($current_hour[0] * 60.0 + $current_hour[1] * 1.0);
			$balance_hours =  480 - $total_minitues ;
			// if($current_minutes <= $balance_hours)
			// {
				$result = array(
					'user_id'       => $user_id,
					'project_id' 	=> $project_name,
					'hours'      	=> $timeline_hours,
					'timeline_date' => $tm_date,
					'timeline_desc' => $timeline_desc
				);
				$this->db->insert('dgt_timesheet',$result);
				echo "success"; exit;
			// }else{
			// 	echo "hoursless"; exit;
			// }


		// }
	}

	public function edit_timesheet()
	{
		$user_id = $this->session->userdata('user_id');
		$edit_id = $this->input->post('edit_id');
		$project_name = $this->input->post('project_name');
		$timeline_desc = $this->input->post('timeline_desc');
		$timeline_hours = $this->input->post('timeline_hours');
		$timeline_date = $this->input->post('timeline_date');
		$tm_date = date("Y-m-d", strtotime($timeline_date));
		$check_status = $this->timesheet_model->check_timesheetByDate($user_id,$tm_date);
		$total_hours = array();
		for($i=0;$i<count($check_status);$i++)
		{
			$time    = explode(':', $check_status[$i]['hours']);
			$minutes = ($time[0] * 60.0 + $time[1] * 1.0);
			$total_hours[] = $minutes;
		}
		$total_minitues =  array_sum($total_hours);
		// if($total_minitues > 480)
		// {
		// 	echo "error_daily"; exit;
		// }else{

			$current_hour    = explode(':', $timeline_hours);
			$current_minutes = ($current_hour[0] * 60.0 + $current_hour[1] * 1.0);
			$balance_hours =  480 - $total_minitues;
			// echo $current_minutes.'-----'.$balance_hours; exit;
			// if($current_minutes < $balance_hours)
			// {
				$result = array(
					'user_id'       => $user_id,
					'project_id' 	=> $project_name,
					'hours'      	=> $timeline_hours,
					'timeline_date' => $tm_date,
					'timeline_desc' => $timeline_desc
				);
				$this->db->where('time_id', $edit_id);
				$this->db->update('dgt_timesheet',$result);
				echo "success"; exit;
			// }else{
			// 	echo "hoursless"; exit;
			// }


		// }
	}

	public function delete_timesheet()
	{
		$time_id = $this->input->post('time_id');
		$this->db->where('time_id',$time_id);
		$this->db->delete('dgt_timesheet');
		echo "success"; exit;
	}

	public function pdf(){
		$data['page'] = lang('timesheets');
		$data['datatables'] = TRUE;
		$data['form'] = TRUE;
		$data['datepicker'] = TRUE;
		$data['all_timesheet'] = $this->timesheet_model->get_all_timesheets();
        // $data['rates'] = Invoice::get_tax_rates();
        // $data['id'] = $invoice_id;
        $html = $this->load->view('timesheets_pdf', $data, true);
        $pdf = array(
	        'html' => $html,
	        'title' => lang('timesheets'),
	        'author' => config_item('company_name'),
	        'creator' => config_item('company_name'),
	        'filename' => lang('timesheets').'.pdf',
	        'badge' => config_item('display_invoice_badge'),
    	);

        $this->applib->create_pdf($pdf);
	}

    public function send_timesheet($user_id = null)
    {
        if ($this->input->post()) {
            $id = $this->input->post('timesheet');
            $send_email = $this->input->post('send_email');

            $send_user=User::get_user_by_email($send_email)['id'];

            $send_filename = $_FILES['time_file']['name'];
            $this->load->library('upload', $config);            
            $config['upload_path'] = './assets/uploads';
            $config['allowed_types'] = '*';
            $this->upload->initialize($config);
            $filename='./assets/uploads/'.$send_filename;
            if (!file_exists($filename)) $this->upload->do_upload("time_file");    

            $activity = array(
                'user' => $id,
                'module' => 'timesheet',
                'module_field_id' => 'mark',
                'activity' => 'activity_timesheet_sent',
                'icon' => 'fa-envelope',
                'value1' => $send_filename,
                'value2' => $send_user,
            );

            App::Log($activity);
            // Applib::go_to('invoices/view/'.$id, 'success', lang('invoice_sent_successfully'));
            $this->session->set_flashdata('tokbox_success', 'Timesheets sent successfully');
            redirect('time_sheets');
        } else {
            $res=$this->db->where('config_key','company_logo')->get('config')->result()[0];
            $data['id'] = $user_id;
            $data['logo'] = $res->value;
            $this->load->view('modal/email_timesheet', $data);
        }
    }
    function ajax_atd(){
/*		$send_email=$this->input->post('send_email');
	    $time_file =  $this->input->post('time_file');
	    $subject = $this->input->post('subject');
      
	    $email = '';
	    $user = User::get_id();
		$email = User::login_info($user)->email;
		$headers = 'From:' . $email . "\r\n" .
		'Reply-To:'. $email . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
	
	    if(!$send_email){
			echo 'false';  exit;
        } else {
			$send_user=User::get_user_by_email($send_email);
			//if(!$send_user){ echo 'false';  exit;  }

			echo mail($send_email,$subject,$time_file,$headers); exit;
			//echo 'true'; exit;
        }

		$send_email=$this->input->post('send_email');
	    $my_file =  $this->input->post('file_Name');
	    $subject = $this->input->post('subject');
      
	    $email = '';
	    $user = User::get_id();
		$email = User::login_info($user)->email;
		
		$my_path = "/Downloads/";
		$my_name = "";
		$my_mail = $email;
		$my_replyto = $email;
		$my_subject = $subject;
		$my_message = "";
		echo mail_attachment($my_file, $my_path, $send_email, $my_mail, $my_name, $my_replyto, $my_subject, $my_message);
		exit;
*/		
		$email = '';
	    $user = User::get_id();
		$email = User::login_info($user)->email;
		
		$to 		 =$this->input->post('send_email');
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
		echo mail($to, $subject, $message, $headers);
		exit;
    }

}

/* End of file projects.php */