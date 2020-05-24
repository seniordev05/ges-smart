<div class="modal-dialog">
	<?php   $user=User::view_user($id);  ?>
	<div class="modal-content">
		<div class="modal-header" class="col-lg-12" >
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<div class='col-lg-4'>
				<img src='<?=site_url()?>assets/images/<?=$logo?>' width="80px" height="50px"/>
			</div>
			<div class='col-lg-8'>
				<h4 class="modal-title"><?=lang('attendance')?></h4>
			</div>
		</div>
		<?php $attributes = array('class' => 'bs-example form-horizontal','id'=>'attendanceEmailForm','enctype'=>'multipart/form-data'); echo form_open(base_url().'attendance/send_attendance',$attributes); ?>
			<div class="modal-body">
				<input type="hidden" name="attendance" value="<?=$id?>">
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('subject')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<input type="text" class="form-control" value="New Attendance <?=$user->username?>" name="subject" id="subject">
					</div> 
					<p><br/><br/>
					<label class="col-lg-4 control-label"><?=lang('email')?> </label>
					<div class="col-lg-8">
						<input type="email" class="form-control" name="send_email" id='send_email' placeholder="Input Email to Send" required>
					</div>
					<p><br/><br/>
					<label class="col-lg-4 control-label">Attachment File </label>
					<div class="col-lg-8">
						<input type="file" class="form
					<p><br/><br/>-control" name="atd_file" id="atd_file" required>
					</div>
					<div class="col-lg-12">
						  <iframe src="" style="width:100%;height:300px;" id='pdf_frame'></iframe>
					</div>
				</div>
			</div>
			<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
				<input type="button" class="submit btn btn-success" id="attendance_email_template" value=" Email Attendance">
			</div>
		</form>
	</div>
	<script type="text/javascript">
		$(function(){
			var filepath;
			var fileName;
			$('#atd_file').change(function(e){
				fileName = e.target.files[0].name;
				filepath = URL.createObjectURL(e.target.files[0]);
				$('#pdf_frame').attr('src',filepath);
				//console.log(e.target.files[0].name);
				filepath = '/download/'.fileName;
			});
			
			
			
			
			$('.submit').click(function() {
								
				if(!$('#atd_file').val()){ alert('You must choose file.'); return; }
				//alert($('#atd_file').val());								
				$.ajax({
		          url:"./attendance/ajax_atd",
		          method:"POST",
		          data:{  send_email:$('#send_email').val(),  file_Name:fileName, subject:$('#subject').val()  },
		          success:function(res)
		          {
					console.log(res);
					//alert(res);
		          	//if(res=="true") 
		          		document.getElementById("attendanceEmailForm").submit();
		          	//else 
		          	//	alert("There isn't this Email. You input other email and try again.");
		          }
		        });
			});
	});
	</script>
</div>