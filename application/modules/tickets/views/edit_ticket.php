<?php $info = Ticket::view_by_id($id); ?>
<div class="content">

					<div class="row">
						<div class="col-xs-6">
							<h4 class="page-title"><?=lang('edit_ticket')?></h4>
						</div>
						<div class="col-xs-6 text-right m-b-0">
					
						<div class="btn-group">
						<a href="<?=base_url()?>tickets/view/<?=$info->id?>" data-original-title="<?=lang('view_details')?>" data-toggle="tooltip" data-placement="bottom" class="btn btn-success btn-sm"><i class="fa fa-info-circle"></i> <?=lang('ticket_details')?></a>
						</div>
						
						</div>
					</div>
					 <!-- Start create ticket -->
<div class="row">
<div class="col-sm-12">
	<section class="panel panel-white">
	
	<div class="panel-heading">
		<h3 class="panel-title"><?=lang('ticket_details')?> - <?=$info->ticket_code?></h3>
	</div>
	<div class="panel-body">
	
<!-- Start ticket form -->
<?php echo $this->session->flashdata('form_error'); ?>

	<?php 
			 $attributes = array('class' => 'bs-example form-horizontal','id'=>'ticketEditForm');
          echo form_open_multipart(base_url().'tickets/edit/',$attributes);
           ?>
			 
			 <input type="hidden" name="id" value="<?=$info->id?>">

			    <div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('ticket_code')?> <span class="text-danger">*</span></label>
				<div class="col-lg-3">
					<input type="text" class="form-control" value="<?=$info->ticket_code?>" name="ticket_code">
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('subject')?> <span class="text-danger">*</span></label>
				<div class="col-lg-6">
					<input type="text" class="form-control" value="<?=$info->subject?>" name="subject">
				</div>
				</div>

				

			

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('priority')?> <span class="text-danger">*</span> </label>
				<div class="col-lg-6">
					<div class="m-b"> 
					<select name="priority" class="form-control" >
					<option value="<?=$info->priority?>"><?=lang('use_current')?></option>
					<?php 
					$priorities = $this->db->get('priorities')->result();
						foreach ($priorities as $p): ?>
					<option value="<?=$p->priority?>"><?=lang(strtolower($p->priority))?></option>
					<?php endforeach; ?>
					</select> 
					</div> 
				</div>
			</div>

			 <div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('department')?> <span class="text-danger">*</span></label>
				<div class="col-lg-6">
					<div class="m-b"> 
					<select name="department" class="form-control" >
					<?php 
					$departments = App::get_by_where('departments',array('deptid >'=>'0'));
						foreach ($departments as $d): ?>
					<option value="<?=$d->deptid?>"<?=($info->department == $d->deptid ? ' selected="selected"' : '')?>><?=strtoupper($d->deptname)?></option>
					<?php endforeach;  ?>
					</select> 
					</div> 
				</div>
			</div>


			<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('reporter')?> <span class="text-danger">*</span> </label>
				<div class="col-lg-6">
					<div class="m-b"> 
					<select class="select2-option form-control" style="width:260px" name="reporter" >
					<optgroup label="<?=lang('users')?>"> 
					<?php foreach (User::all_users() as $user): ?>
					<option value="<?=$user->id?>"<?=($info->reporter == $user->id ? ' selected="selected"' : '')?>><?php echo User::displayName($user->id); ?></option>
					<?php endforeach; ?>
					</optgroup> 
					</select> 
					</div> 
				</div>
			</div>

			<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('ticket_message')?> <span class="text-danger">*</span></label>
				<div class="col-lg-9">
				<textarea name="body" class="form-control foeditor foeditor-ticket-messageU"><?=$info->body?></textarea>
				<div class="row">
				<div class="col-md-6">
				<label id="editTicket_message_error" class="error display-none" style="position:inherit;top:0;font-size:15px;">Message must not empty</label>
				</div>
				</div>
				</div>
				</div>

			<div id="file_container">
				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('attachment')?></label>
				<div class="col-lg-6">
					<label class="btn btn-default btn-choose">Choose File</label>
					<input type="file" class="form-control UpLoadFileSize" name="ticketfiles[]">
				</div>
				</div>

			</div>

<a href="#" class="btn btn-primary btn-sm" id="add-new-file"><?=lang('upload_another_file')?></a>
<a href="#" class="btn btn-danger btn-sm" id="clear-files" style="margin-left:6px;"><?=lang('clear_files')?></a>

<div class="text-center m-t-30">
	<button type="submit" class="btn btn-lg btn-primary" id="tickets_edit_ticket"><?=lang('edit_ticket')?></button>
</div>

				
		</form>



		<!-- End ticket -->
		
</div>
</section>
</div>
</div>


<!-- End edit ticket -->




<script src="<?=base_url()?>assets/js/jquery-2.2.4.min.js"></script>
<script type="text/javascript">
        $('#clear-files').click(function(){
            $('#file_container').html(
                "<div class='form-group'>" +
                    "<label class='col-lg-3 control-label'></label>" +
                    "<div class='col-lg-6'>" +
                    "<label class='btn btn-default btn-choose'>Choose File</label><input class='form-control UpLoadFileSize' type='file' name='ticketfiles[]'>" +
                    "</div></div>"
            );
        });

        $('#add-new-file').click(function(){
            $('#file_container').append(
                "<div class='form-group'>" +
                    "<label class='col-lg-3 control-label'></label>" +
                    "<div class='col-lg-6'>" +
                    "<label class='btn btn-default btn-choose'>Choose File</label><input type='file' class='form-control UpLoadFileSize' name='ticketfiles[]'>" +
                    "</div></div>"
            );
        });
    </script>


		</section>



<!-- end -->