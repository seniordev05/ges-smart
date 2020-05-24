<div class="header-<?=config_item('top_bar_color')?> header">
	<div class="header-left">
		<a class="logo" href="<?=base_url()?>">
			<?php $display = config_item('logo_or_icon'); ?>
		<?php if ($display == 'logo' || $display == 'logo_title') { ?>
			<img src="<?=base_url()?>assets/images/<?=config_item('company_logo')?>">
		<?php } ?>
		</a>
	</div>
	
	<a id="toggle_btn" href="javascript:void(0);">
		<span class="bar-icon">
			<span></span>
			<span></span>
			<span></span>
		</span>
	</a>
	
	<div class="page-title-box pull-left">
		<h3>
			<?php 
			if ($display == 'logo_title') {
				if (config_item('website_name') == '') { echo config_item('company_name'); } else { echo config_item('website_name'); }
			} ?>
		</h3>
	</div>
	<a href="#nav" class="mobile_btn pull-left" id="mobile_btn"><i aria-hidden="true" class="fa fa-bars"></i></a>
	<ul class="nav navbar-nav navbar-right nav-user pull-right">
		<?php $role = User::login_role_name(); 
		$user_id = $this->session->userdata('user_id');
		$dept_id = $this->db->get_where('dgt_users',array('id'=> $user_id))->row_array();
		$department_id = $dept_id['department_id'];
		$last_login = $dept_id['last_login'];
		$lastseen = config_item('last_seen_activities');

		$get_activities = $this->db->select('*')
						  ->from('dgt_activities')
						  ->where('activity_date >',date("Y-m-d H:i:s",$lastseen))
						  // ->where('user',$user_id)
						  ->group_start()
						  ->or_where("FIND_IN_SET('".$department_id."', value2)")
						  ->or_where('value2','00')
						  ->or_where('user',$user_id)
						  ->group_end()
						  ->get()->result_array();
		// echo ($this->db->last_query()); exit()	;
		?>
		<li>
			<a id="user-activities v" href="<?=base_url()?>profile/activities">
			<?php if ($role == 'admin') {
				$lastseen = config_item('last_seen_activities');
				$activities = $this->db->where('activity_date >',date("Y-m-d H:i:s",$lastseen))->get('activities')->result();
				$an=0;
				foreach ($activities as $aa) {
					if($aa->module_field_id==0){
						if($aa->value2!=$user_id) $an++;
					}
				}
				$act = count($activities)-$an;
				$badge = 'bg-purple';
				if ($act == 0) $badge = 'bg-purple';
			  ?>
			 <span class="badge <?=$badge;?> pull-right"><?=$act;?></span>
			<i class="fa fa-bell-o"></i><?php } elseif($role == 'staff') 
			{ 

			$act_staff = count($get_activities);
			$badge_staff = 'bg-purple';
			if ($act_staff == 0) $badge_staff = 'bg-purple';
			?><span class="badge <?=$badge_staff;?> pull-right"><?=$act_staff;?></span><i class="fa fa-bell-o"></i><?php } ?>
			</a>
		</li>
		<li class="hidden-xs">
			<a href="javascript:;" data-toggle="sidebar-chat" onclick="show_user_sidebar()">
				<i class="fa fa-comment-o"></i>
			</a>
	   </li>
		<?php // foreach ($timers as $timer) : if ($role == 'admin' || ($role == 'staff' && User::get_id() == $timer['user'])) : ?>
			<?php //	$type = (isset($timer['task'])) ? 'task' : 'project'; 
					//$title = (isset($timer['task'])) ? Project::view_task($timer['task'])->task_name : Project::by_id($timer['project'])->project_title;
					//$id = (isset($timer['task'])) ? $timer['pro_id'] : $timer['project']; 
			?> 
			<!-- <li class="timer hidden-xs" start="<?php //echo $timer['start_time']; ?>">
				<a title="<?php //echo lang($type).": ".$title.' by '.User::displayName($timer['user']); ?>" data-placement="bottom" data-toggle="tooltip" class="dker" href="<?php //echo site_url('projects/view/'.$id).($type == 'task' ? '?group=tasks':'');  ?>">
					<img src="<?php //echo User::avatar_url($timer['user']); ?>" class="img-rounded">
					<span></span>
				</a>
			</li> -->
		<?php // endif; endforeach; ?>
		<?php $up = count($updates); ?>
		<li class="dropdown main-drop">
			<a href="#" class="dropdown-toggle user-link" data-toggle="dropdown">
				<span class="user-img">
					<?php
					$user = User::get_id();
					$user_email = User::login_info($user)->email;
					?>
					<img src="<?php echo User::avatar_url($user);?>" class="img-circle" width="40">
				</span>
				<span><?php echo User::displayName($user);?></span>
				<b class="caret"></b>
			</a>
			<ul class="dropdown-menu">
				<?php if(($role != 'admin') && ($role != 'superadmin')){ ?>
					<li><a href="<?=base_url()?>employees/profile_view/<?php echo $this->session->userdata('user_id'); ?>">My Profile</a></li>
				<?php } ?>
				<li><a href="<?=base_url()?>profile/settings"><?=lang('account_settings')?></a></li>
				<li> <a href="<?=base_url()?>logout" ><?=lang('logout')?></a> </li>
			</ul>
		</li>
	</ul>
	
            <div class="dropdown mobile-user-menu pull-right">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                <ul class="dropdown-menu pull-right">
                    <li><a href="<?=base_url()?>profile/settings"><?=lang('settings')?></a></li>
				<li>
					<a id="user-activities" href="<?=base_url()?>profile/activities">
					<?php if ($role == 'admin') {
						$lastseen = config_item('last_seen_activities');
						$activities = $this->db->where('activity_date >',date("Y-m-d H:i:s",$lastseen))->get('activities')->result();
						$act = count($activities);
						$badge = 'bg-danger';
						if ($act == 0) $badge = 'bg-success';
					?>
					 <span class="badge <?=$badge;?> pull-right"><?=$act;?></span>
					<?php } ?><?=lang('activities')?>
					</a>
				</li>
				<li> <a href="<?=base_url()?>logout" ><?=lang('logout')?></a> </li>
                </ul>
            </div>
	
</div>
<?php
$chat_qry      =  "SELECT ad.*,u.* 
                   FROM dgt_users u  
				   left join dgt_account_details as ad on ad.user_id = u.id
				   WHERE u.activated = 1 and u.id != ".$this->tank_auth->get_user_id().""; 
$chat_users 	= $this->db->query($chat_qry)->result();
//$chat_users         = $this->user_model->users();
//$chat_user_roles    = $this->user_model->roles();
?>
<div class="notification-box chat_user_sidebar">
	<div class="chat-search" style="height: auto">
		<input type="text" placeholder="Search" class="form-control" onkeyup="filter_chat_user(this.value);">
	</div>
	<ul class="chat-contacts list-box" id="chat-contacts_list">
	<?php 
		if (!empty($chat_users)) {
		foreach ($chat_users as $key => $chat_user) {?>
		<li class="online" onclick="get_users_chats(<?=$chat_user->user_id?>,0);">
			<a href="javascript:;">
				<div class="list-item">
					<div class="list-left">
						<?php 
						if(config_item('use_gravatar') == 'TRUE' AND 
							Applib::get_table_field(Applib::$profile_table,
								array('user_id'=>$chat_user->user_id),'use_gravatar') == 'Y'){
								   $user_email = Applib::login_info($chat_user->user_id)->email; 
						?>
						<img width="38" src="<?=$this -> applib -> get_gravatar($user_email)?>" class="avatar">
						<?php }else{  $pro_pic = Applib::profile_info($chat_user->user_id)->avatar; if($pro_pic != ''){ $pro_pic = $pro_pic; }else{ $pro_pic = 'default_avatar.jpg'; } ?>
						<img width="38" src="<?=base_url()?>assets/avatar/<?php echo $pro_pic; ?>" class="avatar">
						<?php } ?>
					 </div>
					<div class="list-body">
						<div class="message-author"> <?=$chat_user->fullname?> </div>
						<div class="clearfix"></div>
					</div>
				</div>
			</a>
		</li>
	 <?php 
		}
	}?>    
	</ul>
</div> 
<div class="chat-window-container" id="chat-window-container">
</div>