<div class="modal-dialog">
	<?php   $user=User::view_user($id);  ?>
	<div class="modal-content">
		<div class="modal-header" class="col-lg-12" >
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<div class='col-lg-4'>
				<img src='<?=site_url()?>assets/images/<?=$logo?>' width="80px" height="50px"/>
			</div>
			<div class='col-lg-8'>
				<h4 class="modal-title">Organisation Structure</h4>
			</div>
		</div>
		<?php $attributes = array('class' => 'bs-example form-horizontal','id'=>'organisationEmailForm','enctype'=>'multipart/form-data'); echo form_open(base_url().'organisation/send_organisation',$attributes); ?>
			<div class="modal-body">
				<input type="hidden" name="organisation" value="<?=$id?>">
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('subject')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<input type="text" class="form-control" value="New Organisation <?=$user->username?>" name="subject">
					</div> 
					<p><br/><br/>
					<label class="col-lg-4 control-label"><?=lang('email')?> </label>
					<div class="col-lg-8">
						<input type="email" class="form-control" name="send_email" id='send_email' placeholder="Input Email to Send" required>
					</div>
					<p><br/><br/>
					<label class="col-lg-4 control-label">Attachment File </label>
					<div class="col-lg-8">
						<input type="file" class="form-control" name="org_file" id="org_file" required>
					</div>
					<p><br/><br/>
					<div class="col-lg-12">
						  <iframe src="" style="width:100%;height:300px;" id='pdf_frame'></iframe>
					</div>
				</div>
			</div>
			<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
				<input type="button" class="submit btn btn-success" id="organisation_email_template" value=" Email Organisation">
			</div>
		</form>
	</div>
	<script type="text/javascript">
		$(function(){
			var filepath;
			var fileName;
			$('#org_file').change(function(e){
				fileName = e.target.files[0].name;
				filepath = URL.createObjectURL(e.target.files[0]);
				$('#pdf_frame').attr('src',filepath);
				//alert(filepath);
			});
			$('.submit').click(function() {
				if(!$('#org_file').val()){ alert('You must choose file.'); return; }
				$.ajax({
		          url:"./organisation/ajax_atd",
		          method:"POST",
		          data:{  send_email:$('#send_email').val(),  file_Name:fileName, subject:$('#subject').val()  },
		          success:function(res)
		          {
					document.getElementById("organisationEmailForm").submit();
		          	//if(res=="true")  document.getElementById("timesheetEmailForm").submit();
		          	//else alert("There isn't this Email. You input other email and try again.");
		          }
		        });
			});
	});
	</script>
</div>