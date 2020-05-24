
<!-- Start -->


<div class="content">

					 <!-- Start create invoice -->
<div class="row">
<div class="col-sm-12">
	<div class="panel panel-white">
		<div class="panel-heading font-bold">
			<h3 class="panel-title"><?=lang('ticket_details')?></h3>
		</div>
	<div class="panel-body">
	<?php echo $this->session->flashdata('form_error'); ?>

	<?php if(isset($_GET['dept'])){
			 $attributes = array('class' => 'bs-example form-horizontal','id'=>'ticketCreateForm');
          echo form_open_multipart(base_url().'tickets/add/?dept='.$_GET['dept'],$attributes);
           ?>

			 <input type="hidden" name="department" value="<?=$_GET['dept']?>">

			    <div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('ticket_code')?> <span class="text-danger">*</span></label>
				<div class="col-lg-3">
					<input type="text" class="form-control" style="width:260px" value="<?php echo Ticket::generate_code(); ?>" name="ticket_code" readonly="readonly">
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('subject')?> <span class="text-danger">*</span></label>
				<div class="col-lg-7">
					<input type="text" class="form-control" style="width:260px" placeholder="<?=lang('sample_ticket_subject')?>" name="subject">
				</div>
				</div>
				<?php if (User::is_admin()) { ?>

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('reporter')?> <span class="text-danger">*</span> </label>
				<div class="col-lg-6">
					<div class="m-b">
					<select class="select2-option form-control" style="width:260px" name="reporter" >
					<optgroup label="<?=lang('users')?>">
					<?php foreach (User::all_users() as $u): ?>
					<option value="<?=$u->id?>"><?=User::displayName($u->id);?></option>
					<?php endforeach; ?>
					</optgroup>
					</select>
					</div>
				</div>
			</div>
			<?php } ?>



				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('priority')?> <span class="text-danger">*</span> </label>
				<div class="col-lg-6">
					<div class="m-b">
					<select name="priority" style="width:260px" class="form-control">
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
				<label class="col-lg-3 control-label"><?=lang('ticket_message')?> <span class="text-danger">*</span></label>
				<div class="col-lg-9">
				<textarea name="body" class="form-control foeditor foeditor-ticket-message" placeholder="<?=lang('message')?>"><?php echo set_value('body');?></textarea>
				<div class="row">
				<div class="col-md-6">
				<label id="addticket_message_error" class="error display-none" style="position:inherit;top:0;font-size:15px;">Message must not empty</label>
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




			<?php
			$dept = isset($_GET['dept']) ? $_GET['dept'] : 0;
		$additional = $this->db->where(array('deptid'=> $dept))->get("fields")->result_array();
if (is_array($additional) && !empty($additional))
{
	foreach ($additional as $item)
	{
		$label = ($item['label'] == NULL) ? $item['name'] : $item['label'];
		echo '<div class="form-group">';
		echo ' <label class="col-lg-3 control-label"> ' .$label. '</label>';
		echo ' <div class="col-lg-3">';
		if ($item['type'] == 'text')
		{
			echo ' <input type="text" class="form-control" name="' . $item['uniqid'] . '">  ';
		}
		echo ' </div>';
		echo ' </div>';
	}


}
?>

<div class="text-center m-t-30">
<button type="submit" class="btn btn-primary btn-lg" id="tickets_create_ticket">
<?=lang('create_ticket')?></button>
</div>



		</form>

		<?php }else{
			$attributes = array('class' => 'bs-example form-horizontal','id'=>'ticketSelectDept');
          echo form_open(base_url().'tickets/add',$attributes); ?>

          <div class="form-group">
				<label class="col-lg-2 control-label"><?=lang('department')?> <span class="text-danger">*</span> </label>
				<div class="col-lg-6">
					<div class="m-b">
					<select name="dept" class="form-control" required>
					<?php
					$departments = App::get_by_where('departments',array('deptid >'=>'0'));
					foreach ($departments as $d): ?>
					<option value="<?=$d->deptid?>"><?=strtoupper($d->deptname)?></option>
					<?php endforeach; ?>
					</select>
					</div>
				</div>

				<?php if (User::is_admin()) { ?>
				<a href="<?=base_url()?>settings/?settings=departments" class="btn btn-info" data-toggle="tooltip" title="<?=lang('departments')?>"><i class="fa fa-plus"></i> <?=lang('departments')?></a>
				<?php } ?>


			</div>
<button type="submit" class="btn btn-success" id="ticket_select_dept"><?=lang('select_department')?></button>

</form>
		<?php } ?>
</div>
</div>
</div>
</div>


<!-- End create ticket -->

<script src="<?=base_url()?>assets/js/jquery-2.2.4.min.js"></script>
<script type="text/javascript">
        $('#clear-files').click(function(){
            $('#file_container').html(
                "<div class='form-group'>" +
                    "<label class='col-lg-3 control-label'><?=lang('attachment')?></label>" +
                    "<div class='col-lg-6'>" +
                    "<label class='btn btn-default btn-choose'>Choose File</label><input type='file' class='form-control UpLoadFileSize' name='ticketfiles[]'>" +
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


		</div>



<!-- end -->
