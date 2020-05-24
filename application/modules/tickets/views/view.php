<?php $info = Ticket::view_by_id($id); ?>
<!--Start -->
                    <div class="header-fixed hidden-print">
                        <div class="row">
                            <input type="hidden" name="filesizeupload" id="filesizeupload" value="<?php echo config_item('file_max_size'); ?>">
                        <div class="col-md-12">
        
        <a href="#t_info" class="btn btn-sm btn-danger" id="info_btn" data-toggle="class:hide"><i class="fa fa-info-circle"></i></a>
        <?php if (!User::is_client()) { ?>
            <a href="<?=base_url()?>tickets/edit/<?=$info->id?>" class="btn btn-sm btn-success">
            <i class="fa fa-pencil"></i> <?=lang('edit_ticket')?></a>
        <?php } ?>

        <div class="btn-group">
            <button class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown">
                                    <?=lang('change_status')?>
                                    <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <?php
                                        $statuses = $this->db->get('status')->result();
                                        foreach ($statuses as $key => $s) { ?>
                                        <li><a href="<?=base_url()?>tickets/status/<?=$info->id?>/?status=<?=$s->status?>"><?=ucfirst($s->status)?></a></li>
                                        <?php } ?>
                                    </ul>
        </div>
        <?php if (User::is_admin()) { ?>
                                <a href="<?=base_url()?>tickets/delete/<?=$info->id?>" class="btn btn-sm btn-danger pull-right m-r-2" data-toggle="ajaxModal">
                                <i class="fa fa-trash-o"></i> <?=lang('delete_ticket')?></a>

        <?php } ?>
   </div>


                            
                        </div>

                    </div>
<div class="content">
                    <?php
                    $rep = $this->db->where('ticketid',$info->id)->get('ticketreplies')->num_rows();
                    if($rep == 0 AND $info->status != 'closed'){ ?>

                <div class="alert alert-success hidden-print">
                        <button type="button" class="close" data-dismiss="alert">×</button> <i class="fa fa-warning"></i>
                        <?= lang('ticket_not_replied') ?>
                    </div>
                <?php } ?>


                        <!-- Start ticket Details -->
                        <div class="row p-0">
                                <div class="col-sm-12 col-lg-7" id="t_info">
								
								<div class="panel panel-white">
														<div class="panel-body">
															<div class="project-title">
																<div class="m-b-20">
																	<span class="h5 panel-title "><?=$info->subject?></span>
																</div>
															</div>
														<div class=""><?=nl2br_except_pre($info->body)?></div>
														</div>
													</div>
													
													<div class="panel panel-white m-t-3">
														<div class="panel-body">
															<h5 class="panel-title m-b-20">Uploaded image files</h5>
															<div class="row">
		
								                                <?php if($info->attachment != NULL){
                                $files = '';
                                if (json_decode($info->attachment)) {
                                $files = json_decode($info->attachment);
                                foreach ($files as $f) {
                                    $ext = pathinfo($f, PATHINFO_EXTENSION);
                                    if($ext == 'zip'){
                                        $zip_ext = 1;
                                    }else{
                                        $zip_ext = 0;
                                    }
                                 ?>
									<div class="col-md-3 col-sm-6">
										<div class="thumbnail">
											<div class="thumb">
												<?php if($zip_ext != 1){ ?>
                                                <img alt="" class="img-responsive" src="<?=base_url()?>assets/attachments/<?=$f?>">
                                            <?php }else{ ?>
                                                <span style="font-size: 50px;padding-left: 65%;"><i class="fa fa-file-archive-o" aria-hidden="true"></i></span>
                                            <?php } ?>
											</div>
											<div class="caption text-center">
												 <?=$f?>
											</div>
										</div>
									</div>
                                <?php }
                                }else{ ?>
								<div class="col-md-3 col-sm-6">
                                <a class="label bg-info" href="<?=base_url()?>assets/attachments/<?=$info->attachment?>" target="_blank"><?=$info->attachment?></a></div>
                                <?php } ?>

                                <?php } ?>
															</div>
														</div>
													</div>

                            </div>
                            <!-- End ticket details-->


<style>
img { max-width: 100%; height: auto; }
</style>


                            <div class="col-sm-12 col-lg-5 task-chat-view ticket_body m-t-3">

								
<div id="task_window">
							<div class="">
												<div class="chats">
												                               <?php 
                                    if(count(Ticket::view_replies($id)) > 0) {
                                    foreach (Ticket::view_replies($id) as $key => $r) {
                                    $role = User::get_role($r->replierid); 
                                    $role_label = ($role == 'admin') ? 'danger' : 'info';
                                    ?>
													<div class="chat chat-left">
														<div class="chat-avatar">
															<a class="thumb-sm avatar" href="javascript:void(0);">
																 <img src="<?php echo  User::avatar_url($r->replierid); ?>" class="img-circle" alt="<?=User::displayName($r->replierid)?>">
															</a>
														</div>
														<div class="chat-body">
															<div class="chat-bubble">
																<div class="task-contents">
																	<span class="task-chat-user"><?php echo User::displayName($r->replierid); ?>                                                 <span class="label bg-<?=$role_label?> m-l-xs">
                                                <?php echo ucfirst(User::get_role($r->replierid))?></span></span> <span class="chat-time">                    <?php echo strftime(config_item('date_format')." %H:%M:%S", strtotime($r->time)); ?>
                          <?php
                        if(config_item('show_time_ago') == 'TRUE'){
                        echo ' - '.Applib::time_elapsed_string(strtotime($r->time));
                      }
                        ?></span><p class="activate_links">
                                                <?=$r->body?>
                                                </p>
												
												<ul class="attach-list">
                                                <?php if($r->attachment != NULL){
                                                $replyfiles = '';
                                                if (json_decode($r->attachment)) {
                                                $replyfiles = json_decode($r->attachment);
                                                foreach ($replyfiles as $rf) { ?>
												
												 <li class="img-file">
													<div class="attach-img-download"><a href="<?=base_url()?>assets/attachments/<?=$rf?>"><?=$rf?></a></div>
													<div class="task-attach-img"><img alt="" src="<?=base_url()?>assets/attachments/<?=$rf?>"></div>
												</li>
												
                                                <?php }
                                                }else{ ?>
                                                <a href="<?=base_url()?>assets/attachments/<?=$r->attachment?>" target="_blank"><?=$r->attachment?></a><br>
                                                <?php } ?>

                                                <?php } ?>
												</ul>

																</div>
															</div>
														</div>
													</div>
						<?php } } else { ?>
																
													<div class="chat chat-left">
														<div class="no-info"><?=lang('no_ticket_replies')?></div>
													</div>
                                    <?php } ?>			
													</div>
	
								<div class="chat-footer">
									<div class="message-bar">
										<div class="message-inner">
											<div class="message-area">
											                                    <!-- comment form -->
                                    <div class="comment-item media" id="comment-form">
                                        <a class="pull-left thumb-sm avatar">
                                           
            <img src="<?php echo User::avatar_url(User::get_id()); ?>" class="img-circle">
                                        
                                        </a>
                                        <div class="media-body">
                                            <div class="foeditor-noborder">
                                                <?php $attributes = 'class="m-b-0"'; echo form_open_multipart(base_url().'tickets/reply',$attributes); ?>
                                                <input type="hidden" name="ticketid" value="<?=$info->id?>">
                                                <input type="hidden" name="ticket_code" value="<?=$info->ticket_code?>">
                                                <input type="hidden" name="replierid" value="<?=User::get_id();?>">
                                                <textarea required="required" class="form-control foeditor" name="reply" rows="3" placeholder="<?=lang('ticket')?> <?=$info->ticket_code?> <?=lang('reply')?>">
                                                </textarea>
                                                <div id="file_container">
                                                <div class="form-group">
                                                    <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="btn btn-default btn-filechoose">Choose File</label>                  
                                                        <input type="file" class="filestyle UpLoadFileSize" data-buttonText="<?=lang('choose_file')?>" data-icon="false" data-classButton="btn btn-default" data-classInput="form-control inline" name="ticketfiles[]">
                                                    </div>
                                                </div>
                                                </div>
                                                        
                                                </div>
                                                <div class="">
                                                    
                                                    <hr>
                                                    <a href="#" class="btn btn-warning btn-sm" id="add-new-file"><?=lang('upload_another_file')?></a>
                                                    <a href="#" class="btn btn-danger btn-sm" id="clear-files" style="margin-left:6px;"><?=lang('clear_files')?></a>
                                                    <div class="line line-dashed line-lg"></div>
                                                    <button class="btn btn-success pull-right" type="submit"><?=lang('reply_ticket')?></button>
                                                    <ul class="nav nav-pills nav-sm">
                                                    </ul>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
											</div>
										</div>
									</div>
									
								</div>
							</div>
						</div>
							
							
							
							
							
							
							

                        </div>
                        <!-- End details -->
                </div>
                <!-- End display details -->
            </section>
            <script src="<?=base_url()?>assets/js/jquery-2.2.4.min.js"></script>
            <script type="text/javascript">
            $('#clear-files').click(function(){
            $('#file_container').html(
            "<div class='form-group'><div class='row'><div class='col-md-12'><label class='btn btn-default btn-filechoose'>Choose File</label><input type='file' class='form-control UpLoadFileSize' data-buttonText='<?=lang('choose_file')?>' data-icon='false' data-classButton='btn btn-default' data-classInput='form-control inline input-s' name='ticketfiles[]'></div></div></div>"
            );
            });
            $('#add-new-file').click(function(){
            $('#file_container').append(
            "<div class='form-group'><div class='row'><div class='col-md-12'><label class='btn btn-default btn-filechoose'>Choose File</label><input type='file' class='form-control UpLoadFileSize' data-buttonText='<?=lang('choose_file')?>' data-icon='false' data-classButton='btn btn-default' data-classInput='form-control inline input-s' name='ticketfiles[]'></div></div></div>"
            );
            });
            $('#info_btn').click(function(){
                var st = $( ".ticket_body" ).attr( "class" );

                if (st == 'col-sm-5 task-chat-view ticket_body' || st == 'ticket_body task-chat-view col-sm-5') {
                    $('.ticket_body').removeClass("col-sm-5");
                    $('.ticket_body').addClass("col-sm-12");
                }else{
                    $('.ticket_body').addClass("col-sm-5");
                    $('.ticket_body').removeClass("col-sm-12");
                }

            });
            </script>


			</div>
            <!-- end -->