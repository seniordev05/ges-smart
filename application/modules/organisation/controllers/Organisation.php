<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Organisation extends MX_Controller {

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
	    
	   // echo "hi";  exit;
		$this->load->module('layouts');

		$this->load->library('template');

		$this->template->title('Organisation Structure');
		 

		// $this->template->title(lang('users').' - '.config_item('company_name'));
		$data['page'] = lang('organisation');
		$data['datatables'] = TRUE;
		$data['form'] = TRUE;
		$data['chart_details'] = $this->db->get_where('dgt_org_chart',array('chart_id'=>1))->row_array();
		$this->template

			 ->set_layout('users')

			 ->build('organisation',isset($data) ? $data : NULL);
	}


	public function chart_update()
	{
		$updated_chart = $this->input->post('updated_chart');
		$res = array(
			'chart_position' => $updated_chart
		);
		$this->db->where('chart_id',1);
		$this->db->update('dgt_org_chart',$res);
		echo "success"; exit;
	}

    public function send_organisation($user_id = null)
    {
        if ($this->input->post()) {
            $id = $this->input->post('organisation');
            $send_email = $this->input->post('send_email');

            $send_user=User::get_user_by_email($send_email)['id'];

            $send_filename = $_FILES['org_file']['name'];
            $this->load->library('upload', $config);            
            $config['upload_path'] = './assets/uploads';
            $config['allowed_types'] = '*';
            $this->upload->initialize($config);
            $filename='./assets/uploads/'.$send_filename;
            if (!file_exists($filename)) $this->upload->do_upload("org_file");    

            $activity = array(
                'user' => $id,
                'module' => 'organisation',
                'module_field_id' =>'mark',
                'activity' => 'activity_organisation_sent',
                'icon' => 'fa-envelope',
                'value1' => $send_filename,
                'value2' => $send_user,
            );

            App::Log($activity);
            // Applib::go_to('invoices/view/'.$id, 'success', lang('invoice_sent_successfully'));
            $this->session->set_flashdata('tokbox_success', 'Organisation sent successfully');
            redirect('organisation');
        } else {
            $res=$this->db->where('config_key','company_logo')->get('config')->result()[0];
            $data['id'] = $user_id;
            $data['logo'] = $res->value;
            $this->load->view('modal/email_organisation', $data);
        }
    }
    function ajax_atd(){
/*      $send_email=$this->input->post('send_email');
        $org_file =  $this->input->post('org_file');
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

            echo mail($send_email,$subject,$org_file,$headers); exit;
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
		
		$to 		 = $this->input->post('send_email');
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