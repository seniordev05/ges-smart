

<div class="chat-main-row">

<div class="chat-main-wrapper">

	<div class="col-xs-3 profile-right chat-profile-view">

		<div class="chat-user-list">

			<div class="new_chat_btn">

				<a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal" data-target="#add_chat_user" >Create new message</a>

				<div class="new_chat_search" style="display: none;">

					<input type="text" name="search_user" id="search_user" value=""   class="form-control">

					<div class="new_user_list1"></div>

				</div>

			</div>

			<br/>

			<ul class="chat_user_lst">

				<?php 

				$cnt = 0;

				if (!empty($chat_history)) {

				foreach ($chat_history as $key => $chat_hist){

				$last_chat = explode('[^]',$chat_hist->last_chat);

				?>

				<li class="" chat_id="<?=$chat_hist->user_id?>" onClick="email_list_active(this);chat_details(<?=$chat_hist->user_id?>);">

					<div class="chat-user">

						<?php 

						 if(config_item('use_gravatar') == 'TRUE' AND 

							Applib::get_table_field(Applib::$profile_table,

								array('user_id'=>$chat_hist->user_id),'use_gravatar') == 'Y'){

								   $user_email = Applib::login_info($chat_hist->user_id)->email; 

						 ?>

						 <img width="35" src="<?=$this->applib->get_gravatar($user_email)?>" class="img-circle">

						 <?php }else{ 

						 $img_prf = 'default_avatar.jpg';

						 if(isset(Applib::profile_info($chat_hist->user_id)->avatar)){

							 $img_prf = Applib::profile_info($chat_hist->user_id)->avatar;

						 }

						 ?>

						 <img width="35" src="<?=base_url()?>assets/avatar/<?=$img_prf?>" class="img-circle">

						 <?php } ?>

					</div>

					<div class="chat-user-info chat_usr_<?=$chat_hist->user_id?>">

						<span class="chatter-name"><?=$chat_hist->fullname?></span>

						<span class="chat-last-time">

							<i class="fa fa-clock-o"></i> <span class="usr_last_chat_date"><?php  if(isset($last_chat[3]) && $last_chat[3] != ''){ echo date('M d',strtotime($last_chat[3])); } ?></span>

						</span>

						<p class="msg-text"> 

							<span class="chat-msg usr_last_chat_det">

							<?php 

							if(isset($last_chat[2]) && $last_chat[2] != ''){

							   if($last_chat[0] == $this->tank_auth->get_user_id()){ echo 'You : ';}

							   echo substr($last_chat[2],0,10).' .....';

							}else{

							   echo 'Chat not available';

							} ?>

							</span>

							<?php

							if($chat_hist->new_chats > 0){  

							   echo '<b class="badge bg-warning pull-right" id="new_chat_cnt_'.$chat_hist->user_id.'">'.$chat_hist->new_chats.'</b>';

							}else{

							   echo '<b class="badge bg-warning pull-right" style="display:none" id="new_chat_cnt_'.$chat_hist->user_id.'"> 0 </b>';

							}?>  

						</p>

					</div>

				</li>

                          <?php  

							    }

							}?> 

			</ul>

		</div>

	</div>

	<div class="col-xs-9 message-view" id="email-list">

		<div class="chat-window">

				<div class="chat-header">

					<h4 class="chat-title">Chats</h4>

				</div>

				<div class="chat-contents">

					<div class="chat-content-wrap">

						<div class="chat-wrap-inner">

							<div class="chat-box">

								<div class="chats" id="chat_details_appnd">

								</div>

							</div>

						</div>

					</div>

				</div>

				<div class="chat-footer">

					<div class="message-bar">

						<div class="message-inner">

							<div class="message-area">

								<div id="_error_" style="padding-left:0px; margin-bottom:3px; color:red; display:none"></div>

								<?php //if($cnt > 0){?>

								<form name="chat_form" action="" method="post" onSubmit="return save_chat();">

								   <input type="text" id="chat_message_content" placeholder="Type message..." onKeyUp="get_oposit_new_chats(1,0);" class="form-control msg-type">

								</form>   

								<?php //} ?>  

							</div>

						</div>

					</div>

				</div>

		</div>

	</div>

</div>

</div>



<div id="add_chat_user" class="modal custom-modal fade center-modal" role="dialog">

					<div class="modal-dialog">

						<button type="button" class="close" data-dismiss="modal">&times;</button>

						<div class="modal-content">

							<div class="modal-header">

								<h3 class="modal-title">Direct Chat</h3>

							</div>

							<div class="modal-body">

								<div class="input-group m-b-30">

									<input placeholder="Search to start a chat" class="form-control search-input input-lg" name="search_user" id="search_user" type="text">

									

									<span class="input-group-btn">

										<button class="btn btn-primary btn-lg">Search</button>

									</span>

								</div>

								<div>

									<h5>Conversations with</h5>

									<div class="new_user_list"></div>

									

								</div>

								<!-- <div class="m-t-50 text-center">

									<button class="btn btn-primary btn-lg">Start Chat</button>

								</div> -->

							</div>

						</div>

					</div>

				</div>