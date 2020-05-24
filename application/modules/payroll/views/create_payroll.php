<div class="content">  
	<div class="row">
		<div class="col-xs-7">
			<h4 class="page-title"><?='Pay Slip';?></h4>
		</div>

		<?php 
		$payroll_total = 0;
		$users_list = $this->db->query("SELECT u.*
				FROM `dgt_users` u  
				inner join dgt_bank_statutory bs on bs.user_id = u.id 
				where u.activated = 1  and u.status=1 order by u.created desc")->result_array();

			foreach ($users_list as $user_rows) 
			{
				$payroll_user_id[]=$user_rows['id'];
			}
			for ($p=0; $p <count($payroll_user_id) ; $p++) { 
				$all_statutorys = $this->db->get_where('bank_statutory',array('user_id'=>$payroll_user_id[$p]))->result_array(); 
				foreach ($all_statutorys as $all_statutory) {
					$payroll_total += $all_statutory['salary'];
				}
			}
		 ?>
		 
		<div class="col-xs-4" style="display: flex; align-items: center; position: relative; bottom: 5px;">
			<label class="col-lg-7 text-right">payroll total</label>
			<div class="col-lg-5">
				<input type="text" class="form-control payroll_total" name="payroll_total" value = "<?php echo (isset($payroll_total))?$payroll_total:'';?>">
			</div>
		</div>
		
		<div class="col-xs-1 text-right m-b-30">
			<a class="btn btn-primary rounded pull-right" href="#" data-toggle="modal" data-target="#run_payroll">Run Payroll</a>
		</div>
	</div>
	<?php  
//	if (($this->tank_auth->user_role($this->tank_auth->get_role_id()) == 'admin') || ($this->tank_auth->user_role($this->tank_auth->get_role_id()) == 'superadmin')) { ?>
	<div class="row">
	<div class="col-md-12">
	<div class="table-responsive">
	   <?php 
		$users_list = $this->db->query("SELECT u.*,ad.*,d.designation,(select concat(amount,'[^]',date_created) from dgt_salary where user_id = u.id order by id desc limit 1) as salary_det
									FROM `dgt_users` u  
									left join dgt_account_details ad on ad.user_id = u.id 
									left join dgt_designation d on d.id=u.designation_id
									where u.activated = 1 and u.role_id = 3 order by u.created desc")->result_array();
	   ?>
	   <table id="table-users" class="table table-striped custom-table m-b-0 AppendDataTables">
			<thead>
				<tr>
					<th> # </th> 
					<th> Fullname </th> 
					<th> Current Salary </th>
					<th> Designation </th>
					<th class="hidden-sm">Joining Date</th>
					<th class="text-right">Payroll Status</th>
					<th class="col-options no-sort text-right">Options</th>
				</tr>
			 </thead>
			 <tbody>
				<?php  foreach($users_list as $key => $usrs){  ?>
				<tr>
					<td><?=$key+1?></td>
					<td>
						<a class="pull-left avatar">
							<?php 
							if(config_item('use_gravatar') == 'TRUE' AND 
								Applib::get_table_field(Applib::$profile_table,
									array('user_id'=>$usrs['user_id']),'use_gravatar') == 'Y'){
												$user_email = Applib::login_info($usrs['user_id'])->email; 
							?>
							<img src="<?=$this->applib->get_gravatar($user_email)?>" class="img-circle">
							<?php }else{ ?>
							<img src="<?=base_url()?>assets/avatar/<?=Applib::profile_info($usrs['user_id'])->avatar?>" class="img-circle">
							<?php } ?>
						</a>
						<h2>
							<a href="javascript:;"> 
								<?=$usrs['fullname']?>
							</a>
						</h2>
					</td> 
					
					<td>  
					<?php
						$salary = ''; 
						if(isset($usrs['salary_det'])&& $usrs['salary_det'] != ''){
							$exp = explode('[^]',$usrs['salary_det']);
							if($exp[0] != 0){ $salary = $exp[0]; }
						} 

						$user_details = $this->db->get_where('dgt_bank_statutory',array('user_id'=>$usrs['user_id']))->row_array();
						?>
						<strong> <?php echo  $user_details['salary']?$user_details['salary']:'N/A'; ?> </strong> 
					</td>
					<td>
						<span class="label label-info"><? echo $usrs['designation'];?></span>
					</td>
					<td>
						<?=strftime(config_item('date_format'), strtotime($usrs['doj']));?>
					</td> 
					<td> 
						<span class="status-toggle pull-right">
							<input type="checkbox" value="1" class="check" onchange="user_status_change(<?php echo $usrs['user_id'];?>)" id="payroll_user_status<?php echo $usrs['user_id'];?>" <?php if($usrs['status']=='1') echo'checked' ;?>>
							<label class="checktoggle" for="payroll_user_status<?php echo $usrs['user_id'];?>">checkbox</label>
						</span>
					</td> 
					<td class="text-right">
						<!-- <a class="btn btn-success btn-xs" data-toggle="ajaxModal" href="<?=base_url()?>payroll/edit_salary/<?=$usrs['user_id']?>" title="Edit Salary" data-original-title="Edit Salary">
							<i title="Edit Salary" class="fa fa-suitcase"></i>
						</a>
						<a class="btn btn-danger btn-xs" data-toggle="ajaxModal" href="<?=base_url()?>payroll/create/<?=$usrs['user_id']?>" title="Create Pay Slip" data-original-title="Create Pay Slip">
							<i title="Create Pay Slip" class="fa fa-money"></i>
						</a>     -->

						<a class="btn btn-danger btn-xs"  href="<?=base_url()?>payroll/payslip/<?=$usrs['user_id']?>" title="View Pay Slip" >
							<i title="View Pay Slip" class="fa fa-money"></i>
					</td>
				</tr>
				<?php } ?>  
			</tbody>
	   </table>   
		</div>
		</div> 
   </div>
   <?php // } ?>
</div>

<!-- Run Payroll Modal -->
<div class="modal custom-modal center-modal fade" id="run_payroll" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div class="form-head">
					<h3>Run Payroll</h3>
					<p>Are you sure want to run payroll?</p>

				</div>
				<div class="modal-btn delete-action">
					<div class="row">
						<div class="col-xs-6">
							<a href="<?php echo base_url();?>payroll/run_payroll" class="btn btn-primary continue-btn">Yes</a>
						</div>
						<div class="col-xs-6">
							<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">No</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Run Payroll Modal -->

<script type="text/javascript">
		$('.datepicker-input').datepicker({ language: locale});
		$(document).ready(function(){
			var radioValue = $("input[type='radio']:checked").val();
			if(radioValue == 'item'){
					$('.ExpenseItem').css('display','block');
					$('.ExpenseQty').css('display','block');
					$('.ExpenseCategory').css('display','none');
					$('.ExpenseSubCategory').css('display','none');
			}

			if(radioValue == 'category'){
					$('.ExpenseItem').css('display','none');
					$('.ExpenseQty').css('display','none');
					$('.ExpenseCategory').css('display','block');
					$('.ExpenseSubCategory').css('display','block');
			}
    });
		$(document).on("change", ".ExpenseType", function() {
			var expense_type = $(this).val();
			if(expense_type == 'item'){
					$('.ExpenseItem').css('display','block');
					$('.ExpenseQty').css('display','block');
					$('.ExpenseCategory').css('display','none');
					$('.ExpenseSubCategory').css('display','none');
			}

			if(expense_type == 'category'){
					$('.ExpenseItem').css('display','none');
					$('.ExpenseQty').css('display','none');
					$('.ExpenseCategory').css('display','block');
					$('.ExpenseSubCategory').css('display','block');
			}
		});
		$(document).on("change", ".item_qty", function() {
			var item_qty = $(this).val();
			var item_unit_cost = $(".item_selected").val();
			var amount = item_qty * item_unit_cost;
			$(".expense_amount").val(amount);
		});
</script>