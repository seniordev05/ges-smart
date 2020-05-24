<div class="content container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<h4 class="page-title"><?=lang('activities')?></h4>
		</div>
	</div>
    <div class="row">
	    <form method="POST" action="<?php echo base_url()?>profile/activities">
    		<div class="col-lg-4 col-sm-6 col-xs-12">
    				<div class="form-group form-focus">
    				<div class="ref-icon"> 
    					<label class="control-label">Date From</label>
    					<input class="form-control floating activity-from-datepicker" type="text" data-date-format="dd-mm-yyyy" id="ser_activity_date_from" name="dfrom" value="<?php echo (isset($dfrom) && ($dfrom != ''))?$dfrom:""?>" size="16">
    					<i class="fa fa-refresh fa-clearicon" title="Clear To Date" onclick="$('#ser_activity_date_from').val('');$(this).parent().parent().removeClass('focused');"></i>
    					<label id="ser_activity_date_from_error" class="error display-none" for="ser_activity_date_from">From Date Shouldn't be empty</label>
    				</div>	
    				</div>
    		</div>
    		<div class="col-lg-4 col-sm-6 col-xs-12">
    				<div class="form-group form-focus">
    				<div class="ref-icon">
    					<label class="control-label">Date To</label>
    					<input class="form-control floating activity-to-datepicker" type="text" data-date-format="dd-mm-yyyy" id="ser_activity_date_to" name="dto" value="<?php echo (isset($dto) && ($dto != ''))?$dto:""?>" size="16">
    					<i class="fa fa-refresh fa-clearicon" title="Clear To Date" onclick="$('#ser_activity_date_to').val('');$(this).parent().parent().removeClass('focused');"></i>
    					<label id="ser_activity_date_to_error" class="error display-none m-l-5" for="ser_activity_date_to">To Date Shouldn't be empty</label>
    					</div>
    				</div>
    		</div>
    		<div class="col-lg-4 col-sm-6 col-xs-12">
    			<div class="form-group form-focus">
    				<button type="submit" class="btn btn-success form-control p-0" id="activity_search"> Search </button>
    			</div>
    		</div>
	    </form>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="activity">
				<div class="activity-box">
					<ul class="activity-list">
						
							
						<?php foreach ($activity as $key => $a) { 
						?>
						<li>								
							<div class="activity-user">
								<a class="avatar" href="javascript:void(0);">
									<img src="<?php echo User::avatar_url($a->user);?>" class="img-circle">
								</a>
							</div>
							<div class="activity-content">
								<div class="timeline-content">
									<a href="<?=base_url().$a->module?>" class="name"><?=User::displayName($a->user)?></a>
									<?php 
									if($a->module=='attendance'||$a->module=='timesheet'||$a->module=='organisation'){
										if($a->value2==User::get_id())
											echo " received ".$a->module.'.    ';
										else 
											echo " sent ".$a->module.'.    ';
									?>
										<a href="<?=base_url()?>profile/file_download/<?=$a->value1?>"><?php echo $a->value1; ?></a>;
										<a style="float: right" href="<?=base_url()?>profile/delete/<?=$a->activity_id?>" class="btn btn-danger btn-xs" data-toggle="ajaxModal" title="<?=lang('delete')?>">
											<i class="fa fa-trash-o"></i>
										</a>
									<?php 
									}
									else{
										if (lang($a->activity) != '') {
											if (!empty($a->value1)) {
												if (!empty($a->value2)){
													echo sprintf(lang($a->activity), '<a href="'.base_url().$a->module.'">'.$a->value1.'</a>', '<a href="'.base_url().$a->module.'">'.$a->value2.'</a>');
												} else {
													echo sprintf(lang($a->activity), '<a href="'.base_url().$a->module.'">'.$a->value1.'</a>');
												}
											} else { echo '<a href="'.base_url().$a->module.'">'.lang($a->activity).'</a>'; }
										} else { echo '<a href="'.base_url().$a->module.'">'.$a->activity.'</a>'; }
									}
									?>
									<span class="time"><?php echo Applib::time_elapsed_string(strtotime($a->activity_date));?></span>
								</div>
							</div>
						</li>
						<?php } ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<?php
    $role_id = $this->session->userdata('role_id');
    $dept_id = $this->db->get_where('dgt_users',array('id'=> $this->session->userdata('user_id')))->row_array();
	$department_id = $dept_id['department_id'];
    $get_activities = $this->db->select('*')
						  ->from('dgt_activities')
						  ->where_in('value2',$department_id)
						  // ->where("FIND_IN_SET('".$department_id."', value2)")
						  ->order_by('activity_id','DESC')
						  ->get()->result_array();


	 if($role_id == '3') {
	 	
	 ?>
	<div class="row">
		<div class="col-md-12">
			<div class="activity">
				<div class="activity-box">
					<ul class="activity-list">
						<?php foreach ($get_activities as $activities) { 
						?>
						<li>								
							<div class="activity-user">
								<a class="avatar" href="javascript:void(0);">
									<!-- <img src="<?php echo User::avatar_url($a->user);?>" class="img-circle"> -->
								</a>
							</div>
							<div class="activity-content">
								<div class="timeline-content">
									<a href="javascript:void(0);" class="name"><?=User::displayName($activities['user'])?></a> 
									
									<span><?php echo $activities['activity']?></span>
									<a href="javascript:void(0);"><?php echo $activities['value1']?></a> 
									<span class="time"><?php echo Applib::time_elapsed_string(strtotime($activities['activity_date']));?></span>

								</div>
							</div>
						</li>
						<?php } ?>
					</ul>
				</div>
			</div>
		</div>
	</div>

<?php }?>
</div>

<!-- <script>
$(".datepicker-activity").datepicker({
    todayHighlight: true,
    todayBtn: "linked",
    //autoclose: true
 }).on('hide', function(e) {
        console.log($(this).val());
        $(this).val($(this).val());
        if($(this).val() != '')
        {
        $(this).parent().parent().addClass('focused');
        }
        else
        {
        $(this).parent().parent().removeClass('focused');
        }
    });

</script> -->