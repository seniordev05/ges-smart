<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Chats extends MX_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->module('layouts');
		$this->load->library(array('template','tank_auth'));
		$this->template->title('Chats - '.config_item('company_name'));
		$this->page = 'chats';
		if (!$this->tank_auth->get_username()) {
			$this->session->set_flashdata('response', 'error');
			$this->session->set_flashdata('message', lang('access_denied'));
			redirect('');
		}
		//$this->group = isset($_GET['group']) ? $_GET['group'] : 'inbox';
		$this->load->model(array('chats_model', 'App'));
	}

	function index()
	{
		if($this->tank_auth->is_logged_in()) {
			$this->load->module('layouts');
			$this->load->library('template');
			$this->template->title('Chats'); 
			$data['datepicker']   = TRUE;
			$data['form']         = TRUE; 
			$data['page']         = 'chats';  
			$data['chat_history'] = $this->chats_model->user_chat_history($this->tank_auth->get_user_id());
			/*echo $this->db->last_query();
			echo "<pre>";
			print_r($data['chat_history']);
			exit;*/
			$data['role']         = $this->tank_auth->get_role_id();
			$this->template
				 ->set_layout('users')
				 ->build('chats',isset($data) ? $data : NULL);
	    }else{
		   redirect('');	
		}
	}

	function new_chat_user(){
		if($this->input->post('name')){
			$name = $this->input->post('name');
			$new_users = $this->chats_model->new_chat_user($name);
			echo json_encode($new_users);
			die();
		}
	}
	function new_chat_userdetails(){
		if($this->input->post('id')){
			$id = $this->input->post('id');
			$new_users = $this->chats_model->new_chat_userdetails($id);
			echo json_encode($new_users);
			die();
		}
	}

	function all_new_chats()
	{ 
 	   $sess_id      =  $this->tank_auth->get_user_id();
 	   //$html1        =  $html2 =  array();
 	   $qry          = "SELECT ct.*,ad1.fullname as from_user_name 
						FROM `fx_chats_text` ct
						left join fx_account_details as ad1 on ad1.user_id = ct.text_from
						where  ct.text_to = ".$sess_id." and ct.text_status =0 order by ct.id desc"; 
  	   $chat_det     = $this->db->query($qry)->result_array(); 
	   $chats        = array();
	   if(!empty($chat_det)){
 		   foreach($chat_det as $key => $val){
  			   $prf_img2   = ''; 
			   if(config_item('use_gravatar') == 'TRUE' AND 
					Applib::get_table_field(Applib::$profile_table,
						array('user_id'=>$val['text_from']),'use_gravatar') == 'Y'){
						   $user_email = Applib::login_info($val['text_from'])->email; 
					$prf_img2   = $this -> applib -> get_gravatar($user_email);
			   }else{  
			        $prf_img2   = base_url().'assets/avatar/default_avatar.jpg';
					if(isset(Applib::profile_info($val['text_from'])->avatar)){
						 $prf_img2   = base_url().'assets/avatar/'.Applib::profile_info($val['text_from'])->avatar;
				    }
  			   }  
 			   $html1_cnt     = '<section class="panel panel-default raj1" id="c_wind2_'.$val['id'].'" c_lst2="'.$val['id'].'">
			                        <div class="panel-body">
									   <div class="row">
 											<div class="col-md-11" style="padding-left:20px; text-align:right">
												'.$val['from_user_name'].' <span style="font-size: 11px; float:left"> '.date('Y M d h:i A',strtotime($val['text_date_time'])).' </span>
												 <p style="font-size: 13px"> '.$val['text_content'].' </p>
											</div>
											<div class="col-md-1">
											   <img width="50" height="50" class="img-circle" src="'.$prf_img2.'">
											</div>
									   </div> 
									</div> 
								</section>'; 
 			   $chats[$val['text_from']]['html1'][$val['id']] = $html1_cnt;
			   if($key == 0){
				   $chats[$val['text_from']]['time']    = date('M d',strtotime($val['text_date_time']));
				   $chats[$val['text_from']]['content'] = substr($val['text_content'],0,8);
			   }
  			   $html2_cnt  = '<div class="media right" id="c_wind_'.$val['id'].'" c_lst="'.$val['id'].'">
								<div class="pull-right">
									 <img width="25" class="img-circle" src="'.$prf_img2.'">
								</div>
								<div class="media-body">
									<span class="message"> '.$val['text_content'].' </span>
								</div>
							 </div>';  
 			   $chats[$val['text_from']]['html2'][$val['id']] = $html2_cnt;
				//$data_up['text_status'] = 1;
				//$this->db->update('fx_chats_text',$data_up,array('id'=>$val['id']));
				//if($key == 0){
				//	$new_chat_time   = date('M d',strtotime($val['text_date_time']));
					//$new_chat_conten = $val['text_content'];
				//} 
	   
		   }
	   }
	   echo json_encode(array("chats"=>$chats));
	   exit;
	}
	function new_chat_window()
	{ 
	   $user_id      = $_POST['user_id'];
	   $type         = $_POST['type']; 
	   $sess_id      = $this->tank_auth->get_user_id(); 
 	   $chat_id      = $this->chats_model->check_already_chat($user_id,$sess_id);
	   if($chat_id == '') {  $chat_id = 0;  }
 	   //echo $chat_id;exit;
       $html         = ''; 
       $user_Detail  = $this->db->query("SELECT  * FROM  fx_account_details WHERE user_id = ".$user_id."")->result_array();
	   $chat_det     = $this->chats_model->chat_text_details($user_id,$sess_id,1); 
 	   /*$qry          = "select c.*,ad1.fullname as from_user_name,ad1.avatar as from_avatar,ad2.fullname as to_user_fullname,ad2.avatar as to_avatar
 		                from fx_chats c
					    left join fx_account_details as ad1 on ad1.user_id = c.chat_from
					    left join fx_account_details as ad2 on ad2.user_id = c.chat_to
					    where (c.chat_from = ".$chat_id." and c.chat_to = ".$sess_id.") OR 
					    (c.chat_from = ".$sess_id." and c.chat_to = ".$chat_id." ) order by c.chat_id asc";
  	   $chat_det  = $this->db->query($qry)->result_array(); */
	    $prf_img   = ''; 
	    if(config_item('use_gravatar') == 'TRUE' AND 
			Applib::get_table_field(Applib::$profile_table,
				array('user_id'=>$user_id),'use_gravatar') == 'Y'){
				   $user_email = Applib::login_info($user_id)->email; 
 			$prf_img   = $this -> applib -> get_gravatar($user_email);
	    }else{  
		    $prf_img2   = base_url().'assets/avatar/default_avatar.jpg';
			if(isset(Applib::profile_info($user_id)->avatar)){
 				 $prf_img   = base_url().'assets/avatar/'.Applib::profile_info($user_id)->avatar;
			}
 	    } 
 	    $html .= '<div style="display: block;" id="chat-'.$user_id.'" class="panel panel-default" >
					<div class="panel-heading" data-toggle="chat-collapse" data-target="#chat-bill" onclick="chat_window_toggle(this);">
						<a href="javascript:;" class="close"><i class="fa fa-times" onclick="$(this).parent().parent().parent().remove();"></i></a>
						<a href="javascript:;">
							 <img width="40" class="img-circle" src="'.$prf_img.'">
							<span class="contact-name"> '.$user_Detail[0]['fullname'].' </span>
						</a>
					</div>
					<div class="panel-body" id="chat-bill" style="max-height:200px;min-height:200px; overflow-y: auto;">';
			$new_chat_ids = '';		
			if(!empty($chat_det)){ 
  			 foreach($chat_det as $key => $val){ 
  				 if($val['text_from'] == $sess_id){  	
				     $prf_img2   = ''; 
					 if(config_item('use_gravatar') == 'TRUE' AND 
						Applib::get_table_field(Applib::$profile_table,
							array('user_id'=>$sess_id),'use_gravatar') == 'Y'){
							   $user_email = Applib::login_info($sess_id)->email; 
						$prf_img2   = $this -> applib -> get_gravatar($user_email);
					 }else{  
 						$prf_img2   = base_url().'assets/avatar/default_avatar.jpg';
						if(isset(Applib::profile_info($sess_id)->avatar)){
							 $prf_img2   = base_url().'assets/avatar/'.Applib::profile_info($sess_id)->avatar;
						}
					 }  
 					 $html .= '<div class="chat" id="c_wind_'.$val['id'].'" c_lst="'.$val['id'].'">
									<div class="chat-avatar">
										<a class="avatar" href="javascript:void(0);">
											<img class="img-responsive img-circle" src="'.$prf_img2.'" alt="">
										</a>
									</div>
									<div class="chat-body2">
										<div class="chat-content2">
											<p> '.$val['text_content'].' </p>
										</div>
									</div>
								</div>';
				 }
				 if($val['text_from'] != $sess_id){
					 $prf_img3   = ''; 
					 if(config_item('use_gravatar') == 'TRUE' AND 
						Applib::get_table_field(Applib::$profile_table,
							array('user_id'=>$val['text_from']),'use_gravatar') == 'Y'){
							   $user_email = Applib::login_info($val['text_from'])->email; 
						$prf_img3   = $this -> applib -> get_gravatar($user_email);
					 }else{  
 						$prf_img3   = base_url().'assets/avatar/default_avatar.jpg';
						if(isset(Applib::profile_info($val['text_from'])->avatar)){
							 $prf_img3   = base_url().'assets/avatar/'.Applib::profile_info($val['text_from'])->avatar;
						}
					 }  
 					 $html .= '<div class="chat chat-left" id="c_wind_'.$val['id'].'" c_lst="'.$val['id'].'">
									<div class="chat-avatar">
										<a class="avatar" href="javascript:void(0);">
											<img class="img-responsive img-circle" src="'.$prf_img3.'" alt="">
										</a>
									</div>
									<div class="chat-body2">
										<div class="chat-content2">
											<p>'.$val['text_content'].'</p>
										</div>
									</div>
								</div>';
					if($type == 1 && $val['text_status'] == 0){
						if($new_chat_ids == '') $new_chat_ids .= $val['id'];
						else $new_chat_ids .= ','.$val['id'];
 					} 		
 				 }
  			 }
			}
			//$new_chat_ids = '';
			$html .= '</div>
			          <form name="chat_form" action="" method="post" onSubmit="return save_chat2('.$user_id.');">
					        <input type="hidden" id="new_chat_tbl_ids_'.$user_id.'" value="'.$new_chat_ids.'">
					        <input type="hidden" id="chat_tbl_id_bx'.$user_id.'" value="'.$chat_id.'">
           		      		<input type="text" name="ch_txt" id="chat_txt_bx'.$user_id.'" onfocus="change_chat_sts(new_chat_tbl_ids_'.$user_id.','.$user_id.');" 
							onKeyUp="get_oposit_new_chats(2,'.$user_id.'); " onclick="change_chat_sts(new_chat_tbl_ids_'.$user_id.','.$user_id.');" class="form-control chat_text_bx" placeholder="Type message...">
					  </form>		
        </div>';
 	    echo $html;
		exit;
 	}
	
	function change_chat_sts()
	{ 
	   $ids   =  explode(',',$_POST['ids']);
	   $data['text_status'] = 1;
	   $this->db->where_in('id', $ids);
       $this->db->update('fx_chats_text' ,$data);
 	   exit;
	}
	function save_chat()
	{ 
	   $user_id   =  $_POST['chat_id'];
	   $sess_id   = $this->tank_auth->get_user_id();
	   $content   =  $_POST['chat_content'];
 	   $chat_id   = $this->chats_model->check_already_chat($user_id,$sess_id);
 	   if($chat_id == 0 || $chat_id == ''){ 
		    $data1['chat_id']        = $chat_id;
		    $data1['chat_date_time'] = date('Y-m-d h:i:s'); 
 		    $chat_id                 = $this->chats_model->inser_chat($data1,2); 
	   }
 	   //$data['chat_id']           = $chat_id;
	   $data['text_from']         = $sess_id;
	   $data['text_to']           = $user_id;
	   $data['text_content']      = $content; 
 	   $data['text_date_time']    = date('Y-m-d H:i:s'); 
	 // print_r($data);exit;
	   $this->db->insert('fx_chats_text',$data);
 	   $last_id                   = $this->db->insert_id();
	   $qry                       = $this->db->query("SELECT fullname from fx_account_details where user_id = ".$sess_id."");
	   $login_user                = $qry->result_array(); 
  	   $prf_img                   = ''; 
	    if(config_item('use_gravatar') == 'TRUE' AND 
			Applib::get_table_field(Applib::$profile_table,
				array('user_id'=>$sess_id),'use_gravatar') == 'Y'){
				   $user_email = Applib::login_info($sess_id)->email; 
 			$prf_img   = $this -> applib -> get_gravatar($user_email);
	    }else{  
 			$prf_img   = base_url().'assets/avatar/default_avatar.jpg';
			if(isset(Applib::profile_info($sess_id)->avatar)){
				$prf_img   = base_url().'assets/avatar/'.Applib::profile_info($sess_id)->avatar;
			}
	    } 
  	   $html1 = '<div class="chat" id="c_wind2_'.$last_id.'" c_lst2="'.$last_id.'">
					<div class="chat-avatar">
						<a class="avatar">
							<img alt="" src="'.$prf_img.'" class="img-responsive img-circle">
						</a>
					</div>
					<div class="chat-body">
						<div class="chat-content">
							<p>
							 '.$content.' 
							</p>
							<span class="chat-time"> '.date('Y M d h:i A').' </span>
						</div>
					</div>
				</div>';
				
	  $html2  = '<div class="media media1" id="c_wind_'.$last_id.'" c_lst="'.$last_id.'">
					<div class="pull-left">
						 <img width="25" class="img-circle" src="'.$prf_img.'">
					</div>
					<div class="media-body">
						<span class="message"> '.$content.' </span>
					</div>
				</div>'; 
				
	   echo json_encode(array("html1"=>$html1,"html2"=>$html2,'time'=>date('M d'),'content'=>'You : '.substr($content,0,10).' .....'));
 	   exit;
 	}
	function save_chat2()
	{ 
	   $chat_id                   =  $_POST['chat_tbl_id'];
	   $user_id                   =  $_POST['chat_id'];
	   $sess_id                   =  $this->tank_auth->get_user_id();
	   $content                   =  $_POST['chat_content'];
	   if($chat_id == 0 || $chat_id == ''){
		    $data['chat_from']      = $sess_id;
			$data['chat_to']        = $user_id;
			$data['chat_date_time'] = date('Y-m-d h:i:s'); 
 			$chat_id                = $this->chats_model->inser_chat($data,1); 
 	   }else{
		    $data['chat_id']        = $chat_id;
		    $data['chat_date_time'] = date('Y-m-d h:i:s'); 
 		    $this->chats_model->inser_chat($data,2); 
	   }
 	   //$data2['id']           = $chat_id;
	   $data2['text_from']         = $sess_id;
	   $data2['text_to']           = $user_id;
	   $data2['text_content']      = $content; 
 	   $data2['text_date_time']    = date('Y-m-d h:i:s'); 
	   $this->db->insert('fx_chats_text',$data2);
 	   $last_id                    = $this->db->insert_id();
	   $qry                        = $this->db->query("SELECT  fullname from fx_account_details where user_id = ".$sess_id."");
	   $login_user                 = $qry->result_array(); 
 	   $prf_img   = ''; 
	    if(config_item('use_gravatar') == 'TRUE' AND 
			Applib::get_table_field(Applib::$profile_table,
				array('user_id'=>$sess_id),'use_gravatar') == 'Y'){
				   $user_email = Applib::login_info($sess_id)->email; 
 			$prf_img   = $this -> applib -> get_gravatar($user_email);
	    }else{  
 			$prf_img   = base_url().'assets/avatar/default_avatar.jpg';
			if(isset(Applib::profile_info($sess_id)->avatar)){
				$prf_img   = base_url().'assets/avatar/'.Applib::profile_info($sess_id)->avatar;
			}
	    } 
  	   $html1 = '<div class="chat" id="c_wind2_'.$last_id.'" c_lst2="'.$last_id.'">
					<div class="chat-avatar">
						<a class="avatar" >
							<img class="img-responsive img-circle" src="'.$prf_img.'" alt="">
						</a>
					</div>
					<div class="chat-body">
						<div class="chat-content">
							<p>
								'.$content.' 
							</p>
							<span class="chat-time"> '.date('Y M d h:i A').'</span>
						</div>
					</div>
				</div>';
				
	  $html2  = '<div class="chat" id="c_wind_'.$last_id.'" c_lst="'.$last_id.'">
					<div class="chat-avatar">
						<a class="avatar" href="javascript:void(0);">
							<img class="img-responsive img-circle" src="'.$prf_img.'" alt="">
						</a>
					</div>
					<div class="chat-body2">
						<div class="chat-content2">
							<p> '.$content.' </p>
						</div>
					</div>
				</div>'; 
	   echo json_encode(array("html1"=>$html1,"html2"=>$html2,'time'=>date('M d'),'content'=>'You : '.substr($content,0,10).' .....','chat_tbl_id'=>$chat_id));
 	   exit;
 	}
	public function oposit_new_chat() {   	
	   $chat_id      =  $_POST['last_chat'];
	   $user_id      =  $_POST['chat_id'];
	   $sess_id      =  $this->tank_auth->get_user_id();
 	   $html1        =  $html2   =  array();
 	   $qry          = "SELECT ct.*,ad1.fullname as from_user_name 
						FROM `fx_chats_text` ct
						left join fx_account_details as ad1 on ad1.user_id = ct.text_from
						where ct.text_from = ".$user_id." and ct.text_to = ".$sess_id." and  ct.id > ".$chat_id." order by ct.id desc"; 
  	   $chat_det     = $this->db->query($qry)->result_array(); 
 	   $new_chat_time = $new_chat_conten = '';
   	   if(!empty($chat_det)){
 		   foreach($chat_det as $key => $val){
 			   $prf_img2   = ''; 
			   if(config_item('use_gravatar') == 'TRUE' AND 
					Applib::get_table_field(Applib::$profile_table,
						array('user_id'=>$user_id),'use_gravatar') == 'Y'){
						   $user_email = Applib::login_info($user_id)->email; 
					$prf_img2   = $this -> applib -> get_gravatar($user_email);
			   }else{  
 					$prf_img2   = base_url().'assets/avatar/default_avatar.jpg';
					if(isset(Applib::profile_info($user_id)->avatar)){
						$prf_img2   = base_url().'assets/avatar/'.Applib::profile_info($user_id)->avatar;
					}
			   }  
 			   $html1_cnt     = '<div class="chat chat-left" id="c_wind2_'.$val['id'].'" c_lst2="'.$val['id'].'">
									<div class="chat-avatar">
										<a class="avatar" >
											<img class="img-responsive img-circle" src="'.$prf_img2.'" alt="">
										</a>
									</div>
									<div class="chat-body">
										<div class="chat-content">
											<p>
												'.$val['text_content'].' 
											</p>
											<span class="chat-time"> '.date('Y M d h:i A',strtotime($val['text_date_time'])).'</span>
										</div>
									</div>
								</div>'; 
 			   $html1[$val['id']] = $html1_cnt;
 			   $html2_cnt  = '<div class="chat chat-left" id="c_wind_'.$val['id'].'" c_lst="'.$val['id'].'">
								<div class="chat-avatar">
									<a class="avatar" href="javascript:void(0);">
										<img class="img-responsive img-circle" src="'.$prf_img2.'" alt="">
									</a>
								</div>
								<div class="chat-body2">
									<div class="chat-content2">
										<p>'.$val['text_content'].'</p>
									</div>
								</div>
							</div>';  
 			    $html2[$val['id']] = $html2_cnt;
				$data_up['text_status'] = 1;
				$this->db->update('fx_chats_text',$data_up,array('id'=>$val['id']));
				if($key == 0){
					$new_chat_time   = date('M d',strtotime($val['text_date_time']));
					$new_chat_conten = $val['text_content'];
				}
 		   }
 	   } 	
 	   echo json_encode(array("html1"=>$html1,"html2"=>$html2,'time'=>$new_chat_time,'content'=> substr($new_chat_conten,0,10) ) );
 	   exit;
 	}
	public function new_sidebar_window() {  
 	     $user_id    = $this->input->post('user_id');
		 $sess_id    =  $this->tank_auth->get_user_id();
 		 $qry        = $this->db->query("SELECT  fullname from fx_account_details where user_id = ".$user_id."");
	     $user_det   = $qry->result_array(); 
 		 $html = '<li class="" chat_id="'.$user_id.'" onClick="email_list_active(this);chat_details('.$user_id.');">
							<div class="chat-user">'; 
 							 if(config_item('use_gravatar') == 'TRUE' AND 
								Applib::get_table_field(Applib::$profile_table,
									array('user_id'=>$user_id),'use_gravatar') == 'Y'){
									   $user_email = Applib::login_info($user_id)->email; 
 							 $html .= '<img width="35" src="'.$this -> applib -> get_gravatar($user_email).'" class="img-circle">';
							  }else{ 
 								if(isset(Applib::profile_info($user_id)->avatar)){
									$html .= '<img width="35" src="'.base_url().'assets/avatar/'.Applib::profile_info($user_id)->avatar.'" class="img-circle">';
								}else{
									$html .= '<img width="35" src="'.base_url().'assets/avatar/default_avatar.jpg';
								}
						      }  
 		   $hist_qry = "SELECT text_from,text_to,text_content,text_date_time,
			            (SELECT count(id) FROM fx_chats_text WHERE text_from = ".$user_id." and text_to = ".$sess_id." and text_status = 0) new_chats
					    FROM fx_chats_text 
					    WHERE (text_from = ".$user_id." and text_to = ".$sess_id.") OR (text_from = ".$sess_id." and text_to = ".$user_id.")
						order by text_date_time desc limit 1";  
		   $hist_qry2  = $this->db->query($hist_qry);			
 		   $hist_det   = $hist_qry2->result_array(); 		
 		   $html .= '</div>
					 <div class="chat-user-info chat_usr_'.$user_id.'">
						  <span class="chatter-name">'.$user_det[0]['fullname'].'</span>
						  <span class="chat-last-time">
						  <i class="fa fa-clock-o"></i>  <span class="usr_last_chat_date"> ';
				      if(isset($hist_det[0]['text_date_time']) && $hist_det[0]['text_date_time'] != ''){ $html .= date('M d',strtotime($hist_det[0]['text_date_time'])); }  
			$html .= '</span>
			</span>
						  <p class="msg-text"> 
						  <span class="chat-msg usr_last_chat_det">';
 						  if(isset($hist_det[0]['text_content']) && $hist_det[0]['text_content'] != ''){
							  if($hist_det[0]['text_from'] == $sess_id){ $html .= 'You : '; }
							  $html .= substr($hist_det[0]['text_content'],0,10).' .....';
						  }else{
							  $html .= 'Chat not available';
						  } 
			   $html .= '</span>';			  
						  if($hist_det[0]['new_chats']  > 0){  
							  $html .= '<b class="badge bg-warning pull-right" id="new_chat_cnt_'.$user_id.'">'.$hist_det[0]['new_chats'].'</b>';
						  } 
		    $html .= '</p>
					</div>
			 </li>'; 
		 echo $html;
	   exit;	 
	}
	public function chat_details() {  
 	     $chat_id  = $this->input->post('user_id');
		 $sess_id  = $this->tank_auth->get_user_id(); 
		 $chat_det  = $this->chats_model->chat_text_details($chat_id,$sess_id,2);

    	 $html 	  = ''; 
		 if(!empty($chat_det)){

  			 if($chat_det[0]['text_content'] == ''){
  			 	unset($chat_det[0]);
  			 }	
  			 if(!empty($chat_det[0]['text_content']) || !empty($chat_det[1]['text_content'])){ 

  			 foreach($chat_det as $key => $val){

 				 if($val['text_from'] == $sess_id){  
				    $prf_img2   = ''; 
					 if(config_item('use_gravatar') == 'TRUE' AND 
						Applib::get_table_field(Applib::$profile_table,
							array('user_id'=>$sess_id),'use_gravatar') == 'Y'){
							   $user_email = Applib::login_info($sess_id)->email; 
						$prf_img2   = $this -> applib -> get_gravatar($user_email);
					 }else{  
 						$prf_img2   = base_url().'assets/avatar/default_avatar.jpg';
						if(isset(Applib::profile_info($sess_id)->avatar)){
							$prf_img2   = base_url().'assets/avatar/'.Applib::profile_info($sess_id)->avatar;
						}
					 }  
 					$html .= '<div class="chat" id="c_wind2_'.$val['id'].'" c_lst2="'.$val['id'].'">
									<div class="chat-avatar">
										<a class="avatar" >
											<img class="img-responsive img-circle" src="'.$prf_img2.'" alt="">
										</a>
									</div>
									<div class="chat-body">
										<div class="chat-content">
											<p>
												'.$val['text_content'].' 
											</p>
											<span class="chat-time"> '.date('Y M d h:i A',strtotime($val['text_date_time'])).'</span>
										</div>
									</div>
								</div>'; 
 				 }
 				 if($val['text_from'] != $sess_id){
					$prf_img3   = ''; 
					 if(config_item('use_gravatar') == 'TRUE' AND 
						Applib::get_table_field(Applib::$profile_table,
							array('user_id'=>$val['text_from']),'use_gravatar') == 'Y'){
							   $user_email = Applib::login_info($val['text_from'])->email; 
						$prf_img3   = $this -> applib -> get_gravatar($user_email);
					 }else{  
 						$prf_img3   = base_url().'assets/avatar/default_avatar.jpg';
						if(isset(Applib::profile_info($val['text_from'])->avatar)){
							$prf_img3   = base_url().'assets/avatar/'.Applib::profile_info($val['text_from'])->avatar;
						}
					 }  
					$html .= '<div class="chat chat-left" id="c_wind2_'.$val['id'].'" c_lst2="'.$val['id'].'">
									<div class="chat-avatar">
										<a class="avatar">
											<img alt="" src="'.$prf_img3.'" class="img-responsive img-circle">
										</a>
									</div>
									<div class="chat-body">
										<div class="chat-content">
											<p>
											'.$val['text_content'].'
											</p>
											<span class="chat-time">'.date('Y M d h:i A',strtotime($val['text_date_time'])).'</span>
										</div>
									</div>
								</div>'; 
				 }     
				 if($val['text_to'] == $sess_id && $val['text_status'] == 0){
					 $data_up['text_status'] = 1;
					 $this->db->update('fx_chats_text',$data_up,array('id'=>$val['id']));
				 } 
		 }
		 }else {
		     $html = '<p style="color: red"> &nbsp; &nbsp; No Chats Availabe </p>';	 
 		 }
		 
		 }else {
		     $html = '<p style="color: red"> &nbsp; &nbsp; No Chats Availabe </p>';	 
 		 }
 	  echo $html; 
	  exit;
 	}
	
 
} 