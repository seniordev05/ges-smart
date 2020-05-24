<div class="content">
	
	<div class="row">
		<div class="col-xs-12 message_notifcation" ></div>

		<div class="col-xs-4">

			<h4 class="page-title">Employees</h4>

		</div>

		<div class="col-sm-8 col-9 text-right m-b-20">

			<!-- <a href="#aside" data-toggle="class:show" class="btn btn-primary pull-right rounded"><i class="fa fa-plus"></i> <?=lang('new_user')?></a> -->
			<?php
				// $aaa = $this->db->get_where('config', array('config_key'=>'employee_number'))->result();
				$aaa = $this->db->where("config_key", "employee_number")->get('config')->result();
			?>
			<a href="javascript:void(0)" class="btn add-btn" <?php if($count < $aaa[0]->value) { echo 'data-toggle="modal" data-target="#add_new_user"';} ?>><i class="fa fa-plus"></i> Add Employee</a>

			<a style="color: red; margin: 0 10px"><?php if($count >= $aaa[0]->value) {echo "You can't add new employee now. please contact us to add more users";} ?></a>

			<div class="view-icons">
				<a href="javascript:void(0)" onclick="changeviews(this,'grid')" class="viewby grid-view btn btn-link"><i class="fa fa-th"></i></a>
				<a href="javascript:void(0)" onclick="changeviews(this,'list')" class="viewby list-view btn btn-link active"><i class="fa fa-bars"></i></a>
			</div>

		</div>

	</div>	

	<div class="row filter-row">

		<div class="col-sm-6 col-xs-12 col-md-2">  

			<div class="form-group form-focus">

				<label class="control-label">Employee ID</label>

				<input type="text" class="form-control floating" id="employee_id" name="employee_id">
				<label id="employee_id_error" class="error display-none" for="employee_id">Employee Id must not empty</label>

			</div>

		</div>



		<div class="col-sm-6 col-xs-12 col-md-2">  

			<div class="form-group form-focus">

				<label class="control-label">Full Name</label>

				<input type="text" class="form-control floating" id="username" name="username">
				<label id="employee_name_error" class="error display-none" for="username">Full Name must not empty</label>

			</div>

		</div>



		<div class="col-sm-6 col-xs-12 col-md-2">  

			<div class="form-group form-focus">

				<label class="control-label">Email</label>

				<input type="text" class="form-control floating" id="employee_email" name="employee_email">
				<label id="employee_email_error" class="error display-none" for="employee_email">Email Field must not empty</label>

			</div>

		</div>



		<div class="col-sm-6 col-xs-12 col-md-3"> 

			<div class="form-group form-focus select-focus" style="width:100%;">

				<label class="control-label">Department</label>

				<select class="select floating form-control" id="department_id" name="department_id" style="padding: 14px 9px 0px;"> 

					<option value="" selected="selected">All Departments</option>

					<?php if(!empty($departments)){ ?>

					<?php foreach ($departments as $department) { ?>

					<option value="<?php echo $department->deptid; ?>"><?php echo $department->deptname; ?></option>

					<?php  } ?>

					<?php } ?>

			</select>
		</div>
	</div>

	<div class="col-sm-6 col-xs-6 col-md-3">  
		<a href="javascript:void(0)" id="employee_search_btn" onclick="filter_next_page(1)" class="btn btn-success btn-block btn-searchEmployee form-control"> Search </a>  
	</div>  
</div>
<div class="row">
<div class="col-md-12">
<div id="employees_details" data-view="list"></div>
</div>
</div>

<?php /* ?>

	<div class="row">

		<div class="col-lg-12">

			<div class="table-responsive">

				<table id="table-employee" class="table table-striped custom-table AppendDataTables">

					<thead>

						<tr>

							<th><?=lang('full_name')?></th>

							<th><?=lang('company')?></th>

							<th><?=lang('employee_id')?></th>

							<th><?=lang('email')?></th>

							<th><?=lang('mobile_phone')?> </th>

							<th class="hidden-sm"><?=lang('date')?> </th>

							<!-- <th><?=lang('role')?> </th> -->

							<th class="col-options no-sort text-right"><?=lang('options')?></th>

						</tr>

					</thead>

					<tbody>

						<?php foreach (User::all_staffs_users() as $key => $user) {





							?>

							<tr>

								<?php $info = User::profile_info($user->id); ?>

				<!-- <td>

				<a class="pull-left" data-toggle="tooltip" data-title="<?=User::login_info($user->id)->email?>" data-placement="right">





	<img src="<?php echo User::avatar_url($user->id); ?>" class="img-circle" width="32">



	<span class="label label-<?=($user->banned == '1') ? 'danger': 'success'?>"><?=$user->username?></span>



	<?php if($user->role_id == '3') { ?>

	 <strong class=""><?=config_item('default_currency_symbol')?><?=User::profile_info($user->id)->hourly_rate;?>/<?=lang('hour')?></strong>

	 <?php }?>

				</a>

			</td> -->



			<td class="sorting_1">

				<a href="javascript:void(0)" class="avatar"><?=strtoupper(substr($info->fullname,0,1))?></a>

				<h2><a href="javascript:void(0)"><?=$info->fullname?> <span><!-- Web Developer --></span></a></h2>

			</td>

			<td class="">

				<?php if($info->company > 0){ ?>

				<a href="<?=base_url()?>companies/view/<?=$info->company?>" class="text-info">

					<?=($info->company > 0) ? Client::view_by_id($info->company)->company_name : 'N/A'; ?></a>

					<?php }else{ ?>

					<a href="javascript:void(0)">N/A</a>

					<?php }  ?>

				</td>

				<td class=""><?='FT-00'.$info->user_id?></td>

				<td class=""><?=$user->email?></td>

				<td class=""><?=$info->phone?></td>

				<td class="hidden-sm">

					<?=strftime(config_item('date_format'), strtotime($user->created));?>

				</td>



				<!-- <td>



	<?php if (User::get_role($user->id) == 'admin') {

			  $span_badge = 'label label-danger';

		  }elseif (User::get_role($user->id) == 'staff') {

			  $span_badge = 'label label-info';

		  }elseif (User::get_role($user->id) == 'client') {

			  $span_badge = 'label label-default';

		  }else{

			  $span_badge = '';

		}

	?>

				<span class="<?=$span_badge?>">

				<?=lang(User::get_role($user->id))?></span>

			</td> -->

			<td class="text-right">

				<div class="dropdown">

					<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>

					<ul class="dropdown-menu pull-right">

		<li><a href="<?=base_url()?>employees/update/<?=$user->id?>"  data-toggle="ajaxModal"><?=lang('edit')?></li>
		<li><a href="<?=base_url()?>employees/delete/<?=$user->id?>"  data-toggle="ajaxModal"><?=lang('delete')?></a></li>

						</ul>

					</div>

				</td>





						<!-- <td class="text-right">

						<a href="<?=base_url()?>users/account/auth/<?=$user->id?>" class="btn btn-info btn-xs" data-toggle="ajaxModal" title="<?=lang('user_edit_login')?>"><i class="fa fa-lock"></i>

						</a>

						<?php if($user->role_id == '3') { ?>

						<a href="<?=base_url()?>users/account/permissions/<?=$user->id?>" class="btn btn-danger btn-xs" data-toggle="ajaxModal" title="<?=lang('staff_permissions')?>"><i class="fa fa-shield"></i>

						</a>

						<?php } ?>



						<a href="<?=base_url()?>users/account/update/<?=$user->id?>" class="btn btn-success btn-xs" data-toggle="ajaxModal" title="<?=lang('edit')?>"><i class="fa fa-edit"></i>

						</a>

						<?php if ($user->id != User::get_id()) { ?>



						<a href="<?=base_url()?>users/account/ban/<?=$user->id?>" class="btn btn-warning btn-<?=($user->banned == '1') ? 'danger': 'default'?> btn-xs" data-toggle="ajaxModal" title="<?=lang('ban_user')?>"><i class="fa fa-times-circle-o"></i>

						</a>



						<a href="<?=base_url()?>users/account/delete/<?=$user->id?>" class="btn btn-primary btn-xs" data-toggle="ajaxModal" title="<?=lang('delete')?>"><i class="fa fa-trash-o"></i>

						</a>

						<?php } ?>

					</td> -->

				</tr>

				<?php } ?>

			</tbody>

		</table>

	</div>

</div>

</div>
<?php */ ?>




<div id="add_new_user" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Add Employee</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<?php $attributes = array('id' => 'employeeAddForm'); echo form_open(base_url().'auth/register_user',$attributes); ?>

				<p class="text-danger"><?php echo $this->session->flashdata('form_errors'); ?></p>

				<input type="hidden" name="r_url" value="<?=base_url()?>employees">

				<div class="row">

					<div class="col-sm-6">

						<div class="form-group">

							<label><?=lang('full_name')?> <span class="text-danger">*</span></label>

							<input type="text" class="form-control" value="<?=set_value('fullname')?>" placeholder="<?=lang('eg')?> <?=lang('user_placeholder_name')?>" name="fullname" autocomplete="off">

						</div>

					</div>



					<div class="col-sm-6">

						<div class="form-group">

							<label><?=lang('username')?> <span class="text-danger">*</span> <span id="already_username" style="display: none;color:red;">Already Registered Username</span></label>

							<input type="text" name="username" placeholder="<?=lang('eg')?> <?=lang('user_placeholder_username')?>" id="check_username" value="<?=set_value('username')?>" class="form-control" autocomplete="off">
							

						</div>

					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Gender</label><span class="text-danger">*</span>
							<select class="select2-option form-control" name="gender" style="width:100%;">
								<option value="" selected disabled>Gender</option>
								<option value="male">Male</option>
								<option value="female">Female</option>
							</select>
						</div>
					</div>
					</div>

					<div class="row">
					<div class="col-sm-6">

						<div class="form-group">

							<label><?=lang('email')?> <span class="text-danger">*</span> <span id="already_email" style="display: none;color:red;">Already Registered Email</span></label>

							<input type="email" placeholder="<?=lang('eg')?> <?=lang('user_placeholder_email')?>" name="email" id="checkuser_email" value="<?=set_value('email')?>" class="form-control" autocomplete="off">
							

						</div>
						<!-- <span id="error_emailid" style="display: none;color:red;">Invalid Email-Id</span> -->
						

					</div>

					<div class="col-sm-6">

						<div class="form-group">

							<label><?=lang('password')?> <span class="text-danger">*</span></label>

							<input type="password" placeholder="<?=lang('password')?>" value="<?=set_value('password')?>" name="password" id="password" class="form-control" autocomplete="off">

						</div>

					</div>
					</div>

					<div class="row">

					<div class="col-sm-6">  

						<div class="form-group">

							<label><?=lang('confirm_password')?> <span class="text-danger">*</span></label>

							<input type="password" placeholder="<?=lang('confirm_password')?>" value="<?=set_value('confirm_password')?>" name="confirm_password"  class="form-control" autocomplete="off">

						</div>

					</div>



					<div class="col-sm-6">

						<div class="form-group">

							<label><?=lang('phone')?> <span class="text-danger">*</span></label>

							<input type="text" class="form-control telephone" value="<?=set_value('phone')?>" id="add_employee_phone" name="phone" placeholder="<?=lang('eg')?> <?=lang('user_placeholder_phone')?>" autocomplete="off">

						</div>

					</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Address</label>  <a href="javascript:void(0)" class="office_address">Head Office</a>
								<input type="text" class="form-control" name="address" id="address" value="<?php echo $employee_details['address'];?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>City</label>
								<input type="text" class="form-control" name="city" id="city" value="<?php echo $employee_details['city'];?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>State/Province</label>
								<input type="text" class="form-control" name="state" id="state" value="<?php echo $employee_details['state'];?>">
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
								<label>Postal or Zip Code</label>
								<input type="text" class="form-control" name="pincode" id="pincode" value="<?php echo $employee_details['pincode'];?>">
							</div>
						</div>					
			

						<div class="col-sm-6">  

							<div class="form-group">

								<label>Start Date<span class="text-danger">*</span></label>

								<input class="form-control" readonly size="16" type="text" value="" name="emp_doj" id="emp_doj" data-date-format="yyyy-mm-dd" >

							</div>

						</div>

					</div>


						<?php 

							$departments = $this->db->order_by("deptname", "asc")->get('departments')->result();

							$mydefault = current($departments);

							$deptid   = (!empty($mydefault->deptid))?$mydefault->deptid:'-';

							$deptname = (!empty($mydefault->deptname))?$mydefault->deptname:lang('department_name');

							$records = array();

							if($deptid!='-'){



								$this->db->select('id,designation');

								$this->db->from('designation');

								$this->db->where('department_id', $deptid);

								$records = $this->db->get()->result_array();

							}



						 ?>	
						
						<div class="row">
						<div class="col-sm-6">

							<div class="form-group">

								<label><?=lang('department')?> <span class="text-danger">*</span></label>
								<input type="hidden" name="role" value="3">	
								<select class="select2-option" style="width:100%;" name="department_name" id="department_name">
										<option value="" selected disabled>Department</option>
											<?php

											

											 if(!empty($departments))	{

											 foreach ($departments as $department){ ?>

												<option value="<?=$department->deptid?>"><?=$department->deptname?></option>

												<?php } ?>

												<?php } ?>

											<!-- </optgroup> -->

								</select>
							</div>

						</div>
					
						<div class="col-sm-6">

							<div class="form-group">

								<label>Position <span class="text-danger">*</span></label>

							<select class="form-control" style="width:100%;" name="designations" id="designations">
								<option value="" selected disabled>Position</option>
								</select>

							</div>

						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
								<div class="form-group">
									<label>Reporting to </label>
									<select class="form-control" style="width:100%;" name="reporting_to" id="reporting_to">
										<option value="" disabled="disabled" selected="">Reporter's Name</option>
									</select>
								</div>
							</div>


						<div class="col-sm-6">

							<div class="form-group">

								<label><?=lang('user_type')?> <span class="text-danger">*</span></label>
									
								<select class="select2-option" style="width:100%;" name="user_type" id="user_type">
										<option value="" selected disabled>User Type</option>
											<?php

											$user_type = $this->db->order_by('role','asc')->get('roles')->result();


											 if(!empty($user_type))	{

												foreach ($user_type as $type){
													if ($type->r_id != 1) {
													?>

														<option value="<?=$type->r_id?>"><?=$type->role?></option>

													<?php } ?>

												<?php } ?>

											<?php } ?>

											

								</select>
							</div>

						</div>

						</div>


					<div class="submit-section">

						<button class="btn btn-primary submit-btn" id="register_btn">Submit</button>

					</div>
				</form>
					</div>



			</div>

		</div>

	</div>

</div>

</div>
<script type="text/javascript">
	var office_address = "<?=config_item('company_address')?>";
	var office_city = "<?=config_item('company_city')?>";
	var office_state = "<?=config_item('company_state')?>";
	var office_zip_code = "<?=config_item('company_zip_code')?>";

</script>>