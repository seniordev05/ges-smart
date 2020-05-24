<div class="content">
	<div class="row">
		<div class="col-sm-8 col-xs-3">
			<?php 
			$user_id = $this->uri->segment(3);
			$username = $this->db->get_where('dgt_account_details',array('user_id'=>$user_id))->row_array();
			
			?>
			<h4 class="page-title"><?php echo $username['fullname']?> Expenses</h4>
		</div>
		<!-- <div class="col-sm-4 col-xs-9 text-right m-b-30">
			<?php if (($this->tank_auth->user_role($this->tank_auth->get_role_id()) != 'admin') && ($this->tank_auth->user_role($this->tank_auth->get_role_id()) != 'superadmin')) { ?>
			<a href="javascript:;" class="btn btn-primary rounded pull-right New-Leave" onclick="$('.new_leave_reqst').show();$('#date_alert_msg').hide();" data-loginid="<?php echo $this->session->userdata('user_id'); ?>" ><i class="fa fa-plus"></i> <?='New Leave Request';?></a>
			<?php } ?>
		</div> -->
	</div>
	<!-- <?php  if($this->session->flashdata('alert_message')){?>
	<div class="panel panel-default" id="date_alert_msg">
		<div class="panel-heading font-bold" style="color:white; background:#FF0000">
			<i class="fa fa-info-circle"></i> Alert Details 
			<i class="fa fa-times pull-right" style="cursor:pointer" onclick="$('#date_alert_msg').hide();"></i>
		</div>
		<div class="panel-body">
			<p style="color:red"> Already you have make request for now requested Dates! Please Check...</p>
		</div>
	</div>
	<?php  }  ?>   -->
	
	<?php 
			$check_teamlead = $this->db->get_where('dgt_users',array('id'=>$this->session->userdata('user_id')))->row_array(); 

	 ?> 

	
	<?php if (($this->tank_auth->user_role($this->tank_auth->get_role_id()) == 'admin') || ($this->tank_auth->user_role($this->tank_auth->get_role_id()) == 'superadmin') || ($check_teamlead['is_teamlead'] =='yes')) { 


		
		
	
		  
		?>

		<!-- Leave Statistics -->
					<!-- <div class="row">
						<div class="col-md-3">
							<div class="stats-info">
								<h6>Annual Leave</h6>
								<h4> <?php echo $annual_leave_count['leave_days'] ?> / <?php echo $annual_leave['leave_days']; ?></h4>
							</div>
						</div>
						<div class="col-md-3">
							<div class="stats-info">
								<h6>Sick Leave</h6>
								<h4><?php if($sick_leave_count['leave_days'] != '') { echo $sick_leave_count['leave_days']; } elseif($sick_leave_count['leave_days'] == '') { echo '0'; } ?> / <?php echo $sick_leave['leave_days'];?></h4>
							</div>
						</div>
						<div class="col-md-3">
							<div class="stats-info">
								<h6>Total Leaves</h6>
								<h4><?php echo $leave_count['leave_days']?></h4> 
								
							</div>
						</div>
						<div class="col-md-3">
							<div class="stats-info">
								<h6>Loss of Pay</h6>
								<h4>
								<?php
								if($leave_count['leave_days'] > $leave_dayss['leave_days'])
								{
									$lop = $leave_count['leave_days'] - $leave_dayss['leave_days'];
								} 
								else
								{
									$lop = 0;
									
								}
								?><?php echo $lop?>
								</h4>
							</div>
						</div>
					</div> -->
					<!-- /Leave Statistics -->






	

		<div class="row">
		<div class="col-md-12">
		<div class="table-responsive">
			<?php 
			$user_id = $this->uri->segment(3);
			

	  		
			$expenses = $this->db->query("SELECT * FROM `dgt_expenses` where added_by =".$user_id." order by id  ASC ")->result();
			 // print_r($leave_details); exit;
	   		?>
			 <table id="table-expenses" class="table table-striped custom-table m-b-0">
			<thead>
				<tr>
					<th style="width:5px;">No</th>
					<th class=""><?=lang('project')?></th>
					<th class="col-currency"><?=lang('amount')?></th>					
					<th class=""><?=lang('category')?></th>
					<th style="width:5px; display:none;"></th>
					<th class=""><?=lang('expense_date')?></th>
					<th class=""><?=lang('status')?></th>
					
					
				</tr>
			</thead>
			<tbody>
			<?php if(!empty($expenses)){

			foreach ($expenses as $key => $e) { 
			if($e->project != '' || $e->project != 'NULL'){
			$p = Project::by_id($e->project);
			}else{ $p = NULL; } ?>
			<tr id="row_id_<?=$e->id?>" >
				<td ><?=$key+1?></td>
				<td>
					<?php if($e->show_client != 'Yes'){ ?>
					<a href="<?=base_url()?>expenses/show/<?=$e->id?>" data-toggle="tooltip" data-title="<?=lang('show_to_client')?>" data-placement="right">
						<i class="fa fa-circle-o text-danger"></i>
					</a>
					<?php } ?>
					<?php if($e->receipt != NULL){ ?>
					<a href="<?=base_url()?>assets/uploads/<?=$e->receipt?>" target="_blank" data-toggle="tooltip" data-title="<?=$e->receipt?>" data-placement="right">
						<i class="fa fa-paperclip"></i>
					</a>
					<?php } ?>
					<?=($p != NULL) ? $p->project_title : 'N/A'; ?>
				</td>
				<td class="col-currency">
					<strong>
					<?php
					$cur = ($p != NULL) ? $p->currency : $e->currency_symbol; 
					$cur = ($e->client > 0) ? Client::client_currency($e->client)->code : $cur;
					?>
					

						<?php if($e->currency_symbol != ''){
								echo $e->currency_symbol. ''.$e->amount;
						} else{ 
							echo Applib::format_currency($cur, $e->amount); } ?>
						
					
					</strong>
				</td>
				<td>
					<?php echo App::get_category_by_id($e->category); ?>
				</td>
				<th style="width:5px; display:none;"><?php echo date('m/d/Y',strtotime($e->expense_date)); ?></th>
				<td>
					<?=strftime(config_item('date_format'), strtotime($e->expense_date))?>
				</td>
				<td>
				<?php 
					
					$label_color = 'warning';
					if($check_teamlead['is_teamlead'] == 'no')
				   {
				   	$approved_status = 'Pending';
						if(isset($e->expense_status)&&!empty($e->expense_status))
						{
							if($e->expense_status==1)
							{
								$approved_status = 'Approved';
								$label_color = 'success';
							}
							if($e->expense_status==2)
							{
								$approved_status = 'Rejected';
								$label_color = 'danger';
							}
						}
					}	

					if($check_teamlead['is_teamlead'] == 'yes')
				   {
				   	$approved_status = 'Requested';
						$expense_approvers_status   = $this->db->get_where('dgt_expense_approvers',array('approvers'=>$this->session->userdata('user_id'),'expense'=>$e->id))->row_array();
						if(isset($expense_approvers_status)&&!empty($expense_approvers_status))
						{
							if($expense_approvers_status['status']==1)
							{
								$approved_status = 'Approved';
								$label_color = 'success';
							}
							if($expense_approvers_status['status']==2)
							{
								$approved_status = 'Rejected';
								$label_color = 'danger';
							}
						}	
					}				 
					?>
					<span class="small label label-<?=$label_color?>">
						<?=$approved_status;?>
					</span>
				</td>			
				
			</tr>
			<?php  }
		} else { ?>
			<td colspan="6"> No Records Found</td>
		<?php } ?>
			</tbody>
		</table>  
	    </div>
		</div>
		</div>
		<!-- user leave end -->
		<?php } ?>
		

			

		


