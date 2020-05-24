<?php 

$check_teamlead = $this->db->get_where('dgt_users',array('id'=>$employee_details['user_id']))->row_array();
//echo "<pre>"; echo $personal_details['education_details']; exit; ?>
<div class="content container-fluid">
	<!-- Page Title -->
	<div class="row">
		<div class="col-sm-12">
			<h4 class="page-title">Profile</h4>
		</div>
	</div>
	<!-- /Page Title -->

	<div class="card-box m-b-0">
		<div class="row">
			<div class="col-md-12">
				<div class="profile-view">
					<div class="profile-img-wrap">
						<div class="profile-img">
							<a href="#"><img class="avatar" src="<?php echo base_url(); ?>assets/avatar/<?php echo $employee_details['avatar']; ?>" alt=""></a>
						</div>
					</div>
					<div class="profile-basic">
						<div class="row">
							<div class="col-md-5">
								<div class="profile-info-left">
									<h3 class="user-name m-t-0 m-b-0"><?php echo $employee_details['fullname']; ?></h3>
									<?php $des = $this->db->get_where('designation',array('id'=>$employee_details['designation_id']))->row_array(); ?>
									<?php $dept = $this->db->get_where('departments',array('deptid'=>$employee_details['department_id']))->row_array(); ?>
									<small class="text-muted"><?php echo $des['designation']; ?></small>
									<div class="staff-id">Employee ID : <?php echo 'FT-00'.$employee_details['user_id']; ?></div>
									<div class="small text-muted">Started : <?php echo date('d-M-Y',strtotime($employee_details['doj'])); ?></div>
									<?php if($this->session->userdata('role_id') == 1){ ?>
									<div class="staff-msg"><a href="<?php echo base_url(); ?>chats" class="btn btn-custom">Send Message</a></div>
								<?php } ?>
								</div>
							</div>
							<div class="col-md-7">
								<ul class="personal-info">
									<li>
										<span class="title">Phone:</span>
										<span class="text"><a href=""><?php echo $employee_details['phone']; ?></a></span>
									</li>
									<li>
										<span class="title">Email:</span>
										<span class="text"><a href=""><?php echo $employee_details['email']; ?></a></span>
									</li>
									<li>
										<span class="title">Birthday:</span>
										<span class="text"><?php echo $employee_details['dob']?date('d-M-Y',strtotime($employee_details['dob'])):'-'; ?></span>
									</li>
									<li>
										<span class="title">Address:</span>
										<span class="text"><?php echo $employee_details['address']?$employee_details['address'].' '.$employee_details['city'].' '.$employee_details['state'].' '.$employee_details['country']:''; ?></span>
									</li>
									<li>
										<span class="title">Gender:</span>
										<span class="text"><?php echo $employee_details['gender']; ?></span>
									</li>
									<li>
										<span class="title">Reports to:</span>
										<span class="text">
										   <div class="avatar-box">
										   	<?php $reporting_to = $this->db->get_where('account_details',array('user_id'=>$employee_details['teamlead_id']))->row_array(); 
										   	if(count($reporting_to) != 0 ){
												   	if($reporting_to['avatar'] == '')
												   	{
												   		$pro_pic = base_url().'assets/avatar/default_avatar.jpg';
												   	}else{
												   		$pro_pic = base_url().'assets/avatar/'.$reporting_to['avatar'];
												   	}
										   	?>
											  <div class="avatar avatar-xs">
												 <img src="<?php echo $pro_pic;?>" alt="">
											  </div>
										   </div>
										   <!-- <a href="<?php echo base_url().'employees/profile_view/'.$employee_details['teamlead_id']?>"> -->
												<h4 class="user-name"><?php echo $reporting_to['fullname']; ?></h4>
											<!-- </a> -->
										<?php }else{ ?>
											<div class="avatar avatar-xs">
												 <img src="<?php echo base_url().'assets/avatar/default_avatar.jpg'; ?>" alt="">
											  </div>
										   </div>
										   <a href="">
												-
											</a>
										<?php } ?>
										</span>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="pro-edit"><a href="#" class="edit-icon" data-toggle="modal" data-target="#profile_info"><i class="fa fa-pencil"></i></a></div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="card-box tab-box">
		<div class="row user-tabs">
			<div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
				<ul class="nav nav-tabs nav-tabs-bottom">
					<li class="active"><a href="#emp_profile" data-toggle="tab">Profile</a></li>
					<?php if(($this->session->userdata('role_id') != 2) && ($this->session->userdata('role_id') != 3)){ ?>
					<li><a href="#bank_statutory" data-toggle="tab">Bank & Statutory</a></li>
					<li><a href="#tab_additions" data-toggle="tab">Payroll Addition</a></li>
					<li><a href="#tab_deductions" data-toggle="tab">Payroll Deduction</a></li>
					<?php } ?>
					<?php if($this->session->userdata('role_id') == 3){ ?>
						<li><a href="#tab_overtime" data-toggle="tab">Overtime</a></li>
					<?php } ?>
					<?php if($check_teamlead['is_teamlead'] == 'yes'){ ?>
						<li><a href="#tab_teamovertime" data-toggle="tab">Team Overtime</a></li>
					<?php } ?>
					<li><a href="#tab_leaves" data-toggle="tab">Leaves</a></li>
					<li><a href="#tab_attachments" data-toggle="tab">Attachments</a></li>
				</ul>
			</div>
		</div>
	</div>
	
	<div class="tab-content">
					
		<!-- Profile Info Tab -->
		<?php $personal_info = json_decode($personal_details['personal_info']); ?>
			<div id="emp_profile" class="pro-overview tab-pane fade in active">
				<div class="row">
					<div class="col-md-6">
						<div class="card-box profile-box">
							<h3 class="card-title">Personal Informations <a href="#" class="edit-icon" data-toggle="modal" data-target="#personal_info_modal"><i class="fa fa-pencil"></i></a></h3>
							<ul class="personal-info">
								<li>
									<span class="title">Passport No.</span>
									<span class="text"><?php echo $personal_info->passport_no; ?></span>
								</li>
								<li>
									<span class="title">Passport Exp Date.</span>
									<span class="text"><?php echo date('d M Y',strtotime($personal_info->passport_expiry)); ?></span>
								</li>
								<li>
									<span class="title">Tel</span>
									<span class="text"><a href=""><?php echo $personal_info->tel_number; ?></a></span>
								</li>
								<li>
									<span class="title">Nationality</span>
									<span class="text"><?php echo $personal_info->nationality; ?></span>
								</li>
								<li>
									<span class="title">Religion</span>
									<span class="text"><?php echo $personal_info->religion; ?></span>
								</li>
								<li>
									<span class="title">Marital status</span>
									<span class="text"><?php echo $personal_info->marital_status; ?></span>
								</li>
								<li>
									<span class="title">Employment of spouse</span>
									<span class="text"><?php echo $personal_info->spouse; ?></span>
								</li>
								<li>
									<span class="title">No. of children</span>
									<span class="text"><?php echo $personal_info->no_children; ?></span>
								</li>
							</ul>
						</div>
					</div>
				
					<div class="col-md-6">
						<?php $emergency_info = json_decode($personal_details['emergency_info']); ?>
						<div class="card-box profile-box">
							<h3 class="card-title">Emergency Contact <a href="#" class="edit-icon" data-toggle="modal" data-target="#emergency_contact_modal"><i class="fa fa-pencil"></i></a></h3>
							<h5 class="section-title">Primary</h5>
							<ul class="personal-info">
								<li>
									<span class="title">Name</span>
									<span class="text"><?php echo $emergency_info->contact_name1; ?></span>
								</li>
								<li>
									<span class="title">Relationship</span>
									<span class="text"><?php echo $emergency_info->relationship1; ?></span>
								</li>
								<li>
									<span class="title">Phone </span>
									<span class="text"><?php echo $emergency_info->contact1_phone1; ?> <?php echo $emergency_info->contact1_phone2; ?></span>
								</li>
							</ul>
							<hr>
							<h5 class="section-title">Secondary</h5>
							<ul class="personal-info">
								<li>
									<span class="title">Name</span>
									<span class="text"><?php echo $emergency_info->contact_name2; ?></span>
								</li>
								<li>
									<span class="title">Relationship</span>
									<span class="text"><?php echo $emergency_info->relationship2; ?></span>
								</li>
								<li>
									<span class="title">Phone </span>
									<span class="text"><?php echo $emergency_info->contact2_phone1; ?> <?php echo $emergency_info->contact2_phone2; ?></span>
								</li>
							</ul>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-6">
						<div class="card-box profile-box">
							<?php $bank_info = json_decode($personal_details['bank_info']); ?>
							<h3 class="card-title">Bank information <?php if($this->session->userdata('role_id') != 3){ ?><a href="#" class="edit-icon" data-toggle="modal" data-target="#bank_iformation_modal"><i class="fa fa-pencil"></i></a> <?php } ?></h3>
							<ul class="personal-info">
								<li>
									<span class="title">Bank name</span>
									<span class="text"><?php echo $bank_info->bank_name; ?></span>
								</li>
								<li>
									<span class="title">Bank account No.</span>
									<span class="text"><?php echo $bank_info->bank_ac_no; ?></span>
								</li>
								<li>
									<span class="title">IFSC Code</span>
									<span class="text"><?php echo $bank_info->ifsc_code; ?></span>
								</li>
								<li>
									<span class="title">PAN No</span>
									<span class="text"><?php echo $bank_info->pan_no; ?></span>
								</li>
							</ul>
						</div>
					</div>
				
					<div class="col-md-6">
						<div class="card-box profile-box">
							<h3 class="card-title">Family Informations <a href="#" class="edit-icon" data-toggle="modal" data-target="#family_info_modal"><i class="fa fa-pencil"></i></a></h3>
							<table class="table table-nowrap">
								<thead>
									<tr>
										<th>Name</th>
										<th>Relationship</th>
										<th>Date of Birth</th>
										<th>Phone</th>
									</tr>
								</thead>
								<tbody>
									<?php $personal_info = json_decode($personal_details['family_members_info']); 
											// echo "<pre>"; print_r($personal_info); exit;
										if(count($personal_info) != 0)
										{
											foreach ($personal_info as $per) {
									?>
										<tr>
											<td><?php echo $per->member_name; ?></td>
											<td><?php echo $per->member_relationship; ?></td>
											<td><?php echo $per->member_dob; ?></td>
											<td><?php echo $per->member_phone; ?></td>
										</tr>
									<?php } }else{ ?>
										<tr><td colspan="4"><div class="no-results">No Data Found</div></td></tr>
										<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
							
				<div class="row">
					<div class="col-md-6">
						<div class="card-box user-box">
							<h3 class="card-title">Education Informations <a href="#" class="edit-icon" data-toggle="modal" data-target="#education_info"><i class="fa fa-pencil"></i></a></h3>
							<?php $education_details = json_decode($personal_details['education_details']); 
									// echo "<pre>"; print_r($education_details); exit;
							?>
							<div class="experience-box">
								<ul class="experience-list">
									<?php if($education_details != ''){ 
										foreach ($education_details as $education_detail) {
									?>
										<li>
											<div class="experience-user">
												<div class="before-circle"></div>
											</div>
											<div class="experience-content">
												<div class="timeline-content">
													<a href="#/" class="name"><?php echo $education_detail->institute; ?></a>
													<div><?php echo $education_detail->degree; ?></div>
													<span class="time"><?php echo $education_detail->start_date; ?> - <?php echo $education_detail->end_date; ?></span>
												</div>
											</div>
										</li>
									<?php } }else{ ?>
										<li><div class="no-results">Not Updated</div></li>
									<?php } ?>
								</ul>
							</div>
						</div>
					</div>
				
					<div class="col-md-6">
						<div class="card-box user-box">
							<h3 class="card-title">Experience <a href="#" class="edit-icon" data-toggle="modal" data-target="#experience_info"><i class="fa fa-pencil"></i></a></h3>
							<div class="experience-box">
								<ul class="experience-list">
								    <?php $personal_detailss = json_decode($personal_details['personal_details']);
								        foreach($personal_detailss as $personal_detail){
            						?>
										<li>
											<div class="experience-user">
												<div class="before-circle"></div>
											</div>
											<div class="experience-content">
												<div class="timeline-content">
													<a href="#/" class="name"><?php echo $personal_detail->job_position; ?> at <?php echo $personal_detail->company_name; ?></a>
													<span class="time"><?php echo date('d M Y',strtotime($personal_detail->period_from)); ?> - <?php echo date('d M Y',strtotime($personal_detail->period_from)); ?></span>
												</div>
											</div>
										</li>
									<?php } ?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
						<!-- /Profile Info Tab -->
			<?php 
				$bank_statutories = $this->db->get_where('bank_statutory',array('user_id'=>$employee_details['user_id']))->row_array(); 
				$in_bank_statutories = json_decode($bank_statutories['bank_statutory']);
							// echo $in_bank_statutories->pf_contribution; exit;
					// print_r($in_bank_statutories); exit;
			?>

					<!-- Bank Statutory Tab -->
			<div class="tab-pane fade" id="bank_statutory">
				<div class="card">
					<div class="card-body">
						<h3 class="card-title"> Basic Salary Information </h3>
						<form method="post" id="bank_statutory_form" action="<?php echo base_url(); ?>employees/bank_statutory">
							<div class="row">
											<!-- <div class="col-sm-4">
												<div class="form-group">
													<label class="col-form-label">Salary basis <span class="text-danger">*</span></label>
													<select class="form-control" >
														<option value="" disabled>Select salary basis type</option>
														<option value="HOURLY">Hourly</option>
														<option value="DAILY">Daily</option>
														<option value="WEEKLY">Weekly</option>
														<option value="MONTHLY">Monthly</option>
													</select>
											   </div>
											</div> -->
								<div class="col-sm-4">
									<div class="form-group">
										<label class="col-form-label">Salary amount <small class="text-muted">per month</small></label>
										<div class="input-group">
											<div class="input-group-addon">
												<span class="input-group-text">$</span>
											</div>
											<input type="text" class="form-control" name="user_salary" id="user_salary" placeholder="Type your salary amount" value="<?php echo $bank_statutories['salary']; ?>" >
											<input type="hidden" name="bankuser_id" id="bankuser_id" value="<?php echo $employee_details['user_id']; ?>">
										</div>
									</div>
								</div>

								<div class="col-sm-4">
									<div class="form-group">
										<label class="col-form-label">Payment type</label>
										<select class="form-control" name="payment_type" id="payment_type">
											<option value="" disabled>Select payment type</option>
											<option value="bank" <?php if($bank_statutories['payment_type'] == 'bank'){ echo "selected"; } ?>>Bank transfer</option>
											<option value="cheque" <?php if($bank_statutories['payment_type'] == 'cheque'){ echo "selected"; } ?>>Cheque</option>
											<option value="cash" <?php if($bank_statutories['payment_type'] == 'cash'){ echo "selected"; } ?>>Cash</option>
										</select>
								   </div>
								</div>
							</div>
							<hr>
							<h3 class="card-title"> PF Information</h3>
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
										<label class="col-form-label">PF contribution</label>
										<select class="form-control " name="pf_contribution" id="pf_contribution">
											<option value="" disabled>Select PF contribution</option>
											<option value="yes" <?php if($in_bank_statutories->pf_contribution == 'yes'){ echo "selected"; } ?>>YES</option>
											<option value="no" <?php if($in_bank_statutories->pf_contribution == 'no'){ echo "selected"; } ?>>NO</option>
										</select>
									</div>
								</div>

								<div class="col-sm-4">
									<div class="form-group">
										<label class="col-form-label">PF No. <span class="text-danger">*</span></label>
										<input type="text" class="form-control PFrecords" name="pf_no" id="pf_no" placeholder="Type your PF No" value="<?php echo $in_bank_statutories->pf_no; ?>" <?php if($in_bank_statutories->pf_contribution == 'no'){ echo "disabled"; } ?>>
									</div>
								</div>
							</div>
					
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
										<label class="col-form-label">Employee PF rate</label>
										<select class="form-control PFrecords" name="pf_rates" id="pf_rates" <?php if($in_bank_statutories->pf_contribution == 'no'){ echo "disabled"; } ?>>
											<option value="" disabled>Select PF contribution</option>
											<option value="yes" <?php if($in_bank_statutories->pf_rates == 'yes'){ echo "selected"; } ?>>YES</option>
											<option value="no" <?php if($in_bank_statutories->pf_rates == 'no'){ echo "selected"; } ?>>NO</option>
										</select>
									</div>
								</div>
					
								<div class="col-sm-4">
									<div class="form-group">
										<label class="col-form-label">Additional rate(%) <span class="text-danger">*</span></label>
										<input type="text" class="form-control PFrecords EMprate" name="pf_add_rates" id="pf_add_rates" placeholder="Type your PF No" value="<?php echo $in_bank_statutories->pf_add_rates; ?>" <?php if($in_bank_statutories->pf_contribution == 'no'){ echo "disabled"; } ?>>
									</div>
								</div>
			
								<div class="col-sm-4">
									<div class="form-group">
										<label class="col-form-label">Total rate</label>
										<input type="text" class="form-control PFrecords EMprate" name="pf_total_rate" id="pf_total_rate" placeholder="Amount"  value="<?php echo $in_bank_statutories->pf_total_rate; ?>" readonly <?php if($in_bank_statutories->pf_contribution == 'no'){ echo "disabled"; } ?>>
									</div>
								</div>
							</div>
			
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
										<label class="col-form-label">Employer PF rate</label>
										<select class="form-control PFrecords" name="pf_employer_contribution" id="pf_employer_contribution" <?php if($in_bank_statutories->pf_contribution == 'no'){ echo "disabled"; } ?>>
											<option value="" disabled>Select PF contribution</option>
											<option value="yes" <?php if($in_bank_statutories->pf_employer_contribution == 'yes'){ echo "selected"; } ?>>YES</option>
											<option value="no" <?php if($in_bank_statutories->pf_employer_contribution == 'no'){ echo "selected"; } ?>>NO</option>
										</select>
									</div>
								</div>
			
								<div class="col-sm-4">
									<div class="form-group">
										<label class="col-form-label">Additional rate(%) <span class="text-danger">*</span></label>
										<input type="text" class="form-control PFrecords EmprRate" name="employer_add_rates" id="employer_add_rates" placeholder="N/A"  value="<?php echo $in_bank_statutories->employer_add_rates; ?>" <?php if($in_bank_statutories->pf_contribution == 'no'){ echo "disabled"; } ?>>
									</div>
								</div>
			
								<div class="col-sm-4">
									<div class="form-group">
										<label class="col-form-label">Total rate</label>
										<input type="text" class="form-control PFrecords EmprRate" name="employer_total_rates" id="employer_total_rates" placeholder="Amount"  value="<?php echo $in_bank_statutories->employer_total_rates; ?>" readonly <?php if($in_bank_statutories->pf_contribution == 'no'){ echo "disabled"; } ?>>
									</div>
								</div>
							</div>
										
							<hr>
							<h3 class="card-title"> ESI Information</h3>
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
										<label class="col-form-label">ESI contribution</label>
										<select class="form-control" name="esi_contribution" id="esi_contribution">
											<option value="" disabled>Select ESI contribution</option>
											<option value="yes" <?php if($in_bank_statutories->esi_contribution == 'yes'){ echo "selected"; } ?>>YES</option>
											<option value="no" <?php if($in_bank_statutories->esi_contribution == 'no'){ echo "selected"; } ?>>NO</option>
										</select>
									</div>
								</div>
			
								<div class="col-sm-4">
									<div class="form-group">
										<label class="col-form-label">ESI No. <span class="text-danger">*</span></label>
										<input type="text" class="form-control ESIrecords" name="esi_no" id="esi_no" placeholder="Type your ESI No"  value="<?php echo $in_bank_statutories->esi_no; ?>" <?php if($in_bank_statutories->esi_contribution == 'no'){ echo "disabled"; } ?>>
									</div>
								</div>
							</div>
			
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
										<label class="col-form-label">Employee ESI rate</label>
										<select class="form-control ESIrecords" name="esi_rate" id="esi_rate" <?php if($in_bank_statutories->esi_contribution == 'no'){ echo "disabled"; } ?>>
											<option value="" disabled>Select ESI Rate</option>
											<option value="yes" <?php if($in_bank_statutories->esi_rate == 'yes'){ echo "selected"; } ?> >YES</option>
											<option value="no" <?php if($in_bank_statutories->esi_rate == 'no'){ echo "selected"; } ?> >NO</option>
										</select>
									</div>
								</div>
			
								<div class="col-sm-4">
									<div class="form-group">
										<label class="col-form-label">Additional rate(%) <span class="text-danger">*</span></label>
										<input type="text" class="form-control ESIrecords ESIRates" name="esi_add_rate" id="esi_add_rate" placeholder="Rates(%)"  value="<?php echo $in_bank_statutories->esi_add_rate; ?>" <?php if($in_bank_statutories->esi_contribution == 'no'){ echo "disabled"; } ?>>
									</div>
								</div>
			
								<div class="col-sm-4">
									<div class="form-group">
										<label class="col-form-label">Total rate</label>
										<input type="text" class="form-control ESIrecords ESIRates" name="esi_total_rate" id="esi_total_rate" placeholder="Total Rate"  value="<?php echo $in_bank_statutories->esi_total_rate; ?>" readonly <?php if($in_bank_statutories->esi_contribution == 'no'){ echo "disabled"; } ?>>
									</div>
								</div>
						   </div>
			
							<div class="submit-section">
								<button class="btn btn-primary submit-btn" type="submit" >Save</button>
							</div>
						</form>
					</div>
				</div>
			</div>
						<!-- /Bank Statutory Tab -->
						
						<!-- Additions Tab -->
			<div class="tab-pane" id="tab_additions">
						
							<!-- Add Addition Button -->
				<div class="text-right m-b-30 clearfix">
					<button class="btn btn-primary add-btn" type="button" data-toggle="modal" data-target="#add_addition"><i class="fa fa-plus"></i> Add Addition</button>
				</div>
							<!-- /Add Addition Button -->

				<div class="payroll-table card">
					<table class="table table-hover table-radius">
						<thead>
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>Category</th>
								<th>Default/Unit Amount</th>
								<th class="text-right">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php $all_addtional = $this->db->get_where('bank_statutory',array('user_id'=>$employee_details['user_id']))->row_array(); 
								$addtional_ar = json_decode($all_addtional['pf_addtional'],TRUE);
								$i=1;
											// foreach ($addtional_ar as $add_ar) {
								if(is_array($addtional_ar)){
									foreach ($addtional_ar as $key => $value) {
							?>
									<tr>
										<th><?php echo $i; ?></th>
										<th><?php echo $value['addtion_name']; ?></th>
										<th><?php if($value['category_name'] == 'monthly'){ echo "Monthly remuneration"; }
												  if($value['category_name'] == 'addtional'){ echo "Additional remuneration"; } ?>
										</th>
										<th><?php echo $value['unit_amount']; ?></th>
										<td class="text-right">
											<div class="dropdown dropdown-action">
												<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
												<div class="dropdown-menu dropdown-menu-right">
													<a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_addition<?php echo $value['id']; ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
													<a class="dropdown-item DeleteAddtion" style="cursor: pointer;" data-arid="<?php echo $value['id']; ?>" data-keyid="<?php echo $key; ?>" data-user="<?php echo $employee_details['user_id']; ?>"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
												</div>
											</div>
										</td>
									</tr>
								<?php $i++; } } ?>
						</tbody>
					</table>
				</div>
			</div>
						<!-- Additions Tab -->
						
						<!-- Overtime Tab -->
			<div class="tab-pane" id="tab_overtime">
						
							<!-- Add Overtime Button -->
				<div class="text-right m-b-30 clearfix">
					<button class="btn btn-primary add-btn" type="button" data-toggle="modal" data-target="#add_overtime"><i class="fa fa-plus"></i> Add Overtime</button>
				</div>
							<!-- /Add Overtime Button -->

				<div class="payroll-table card">
					<table class="table table-hover table-radius">
						<thead>
							<tr>
								<th>#</th>
								<th>Description</th>
								<th>Date</th>
								<th>Hours</th>
								<th>Status</th>
								<th>Options</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$overtime=$this->db->where('user_id',$employee_details['user_id'])->get('overtime')->result_array();
									 
								if(!empty($overtime))
								{
									$o=1;
									foreach ($overtime as $o_row) {
							?>
								<tr>
									<th><?php echo $o++;?></th>
									<th><?php echo $o_row['ot_description'];?></th>
									<td><?php echo date('d M Y',strtotime($o_row['ot_date']));?></td>
									<td><?php echo $o_row['ot_hours'];?></td>
									<td>
									<?php
										if($o_row['status'] == 0){
											if($check_teamlead['is_teamlead'] == 'no')
											echo '<span class="label" style="background:#D2691E"> Pending </span>';
											if($check_teamlead['is_teamlead'] == 'yes')
											echo '<span class="label" style="background:#D2691E"> Requested </span>';
										}else if($o_row['status'] == 1){
											echo '<span class="label label-success"> Approved </span> ';
										}else if($o_row['status'] == 2){
											echo '<span class="label label-danger"> Rejected</span>';
										}else if($o_row['status'] == 3){
											echo '<span class="label label-danger"> Cancelled</span>';
										}
									?>
									</td>
									<td> 
										<?php if($check_teamlead['is_teamlead'] == 'no'){ ?>
											<?php if($o_row['status'] == 0){ ?> 
											<a class="btn btn-danger btn-xs"  
												href="<?=base_url()?>employees/overtime_cancel/<?=$o_row['id']?>/<?php echo $o_row['user_id'];?>" title="Cancel" data-original-title="Cancel">
											<i class="fa fa-times"></i> 
									 		</a>
										<?php } }
										if($check_teamlead['is_teamlead'] == 'yes'){ 
											if(($o_row['status'] != 3) &&  ($o_row['status'] != 1)){
								 		?>
											<a  class="btn btn-success btn-xs"  
											href="<?=base_url()?>employees/overtime_approve/<?=$o_row['id']?>/<?php echo $o_row['user_id'];?>" title="Approve" data-original-title="Approve" >
												<i class="fa fa-thumbs-o-up"></i> 
											</a> 
											<a class="btn btn-danger btn-xs"  
											href="<?=base_url()?>employees/overtime_reject/<?=$o_row['id']?>/<?php echo $o_row['user_id'];?>" title="Reject" data-original-title="Reject">
												<i class="fa fa-thumbs-o-down"></i> 
											</a>
										 <?php } } ?>
									 <!--<a class="btn btn-danger btn-xs"  
									 data-toggle="ajaxModal" href="<?=base_url()?>leaves/delete/<?=$levs['id']?>" title="Delete" data-original-title="Delete">
										<i class="fa fa-trash-o"></i> 
									 </a>-->
									</td>
								</tr>

							<?php } } ?>
						</tbody>
					</table>
				</div>
							
			</div>
						<!-- /Overtime Tab -->


			<div class="tab-pane" id="tab_teamovertime">
						
				<!-- Add Overtime Button -->
				<div class="text-right m-b-30 clearfix">
					<button class="btn btn-primary add-btn" type="button" data-toggle="modal" data-target="#add_overtime"><i class="fa fa-plus"></i> Add Overtime</button>
				</div>
							<!-- /Add Overtime Button -->
				<div class="payroll-table card">
					<table class="table table-hover table-radius">
						<thead>
							<tr>
								<th>#</th>
								<th>Username</th>
								<th>Description</th>
								<th>Date</th>
								<th>Hours</th>
								<th>Status</th>
								<th>Options</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$overtime=$this->db->where('teamlead_id',$check_teamlead['id'])->get('overtime')->result_array();

								if(!empty($overtime))
								{
									$o=1;
									foreach ($overtime as $o_row) {
							?>
									<tr>
										<th><?php echo $o++;?></th>
										<?php if($check_teamlead['is_teamlead'] == 'yes') { $user_details = $this->db->get_where('account_details',array('user_id'=>$o_row['user_id']))->row_array(); ?>
										<td><?=$user_details['fullname']?></td> <?php } ?>
										<th><?php echo $o_row['ot_description'];?></th>
										<td><?php echo date('d M Y',strtotime($o_row['ot_date']));?></td>
										<td><?php echo $o_row['ot_hours'];?></td>
										<td>
											<?php
												if($o_row['status'] == 0){
													if($check_teamlead['is_teamlead'] == 'no')
														echo '<span class="label" style="background:#D2691E"> Pending </span>';
												if($check_teamlead['is_teamlead'] == 'yes')
													echo '<span class="label" style="background:#D2691E"> Requested </span>';
												}else if($o_row['status'] == 1){
													echo '<span class="label label-success"> Approved </span> ';
												}else if($o_row['status'] == 2){
													echo '<span class="label label-danger"> Rejected</span>';
												}else if($o_row['status'] == 3){
													echo '<span class="label label-danger"> Cancelled</span>';
												}
											?>
										</td>
										<td> 
											<?php if($check_teamlead['is_teamlead'] == 'no'){ ?>
												<?php if($o_row['status'] == 0){ ?> 
													<a class="btn btn-danger btn-xs"  
														href="<?=base_url()?>employees/overtime_cancel/<?=$o_row['id']?>/<?php echo $o_row['user_id'];?>" title="Cancel" data-original-title="Cancel">
														<i class="fa fa-times"></i> 
													</a>
											<?php } }
												if($check_teamlead['is_teamlead'] == 'yes'){ 
												if(($o_row['status'] != 3) &&  ($o_row['status'] != 1)){
											?>
												<a  class="btn btn-success btn-xs"  
												href="<?=base_url()?>employees/overtime_approve/<?=$o_row['id']?>/<?php echo $o_row['teamlead_id'];?>" title="Approve" data-original-title="Approve" >
													<i class="fa fa-thumbs-o-up"></i> 
												</a> 
												<a class="btn btn-danger btn-xs"  
												href="<?=base_url()?>employees/overtime_reject/<?=$o_row['id']?>/<?php echo $o_row['teamlead_id'];?>" title="Reject" data-original-title="Reject">
													<i class="fa fa-thumbs-o-down"></i> 
												</a>
											 <?php } } ?>
									 <!--<a class="btn btn-danger btn-xs"  
									 data-toggle="ajaxModal" href="<?=base_url()?>leaves/delete/<?=$levs['id']?>" title="Delete" data-original-title="Delete">
										<i class="fa fa-trash-o"></i> 
									 </a>-->
										</td>
									</tr>

								<?php } } ?>
							</tbody>
						</table>
					</div>
							
				</div>
						
						<!-- Deductions Tab -->
				<div class="tab-pane" id="tab_deductions">
						
							<!-- Add Deductions Button -->
							<div class="text-right m-b-30 clearfix">
								<button class="btn btn-primary add-btn" type="button" data-toggle="modal" data-target="#add_deduction"><i class="fa fa-plus"></i> Add Deduction</button>
							</div>
							<!-- /Add Deductions Button -->

							<div class="payroll-table card">
								<table class="table table-hover table-radius">
									<thead>
										<tr>
											<th>#</th>
											<th>Name</th>
											<th>Default/Unit Amount</th>
											<th class="text-right">Action</th>
										</tr>
									</thead>
									<tbody>
										<?php $all_addtional = $this->db->get_where('bank_statutory',array('user_id'=>$employee_details['user_id']))->row_array(); 

											$deduction_ar = json_decode($all_addtional['pf_deduction'],TRUE);
											$i=1;
											if(is_array($deduction_ar)){
											foreach ($deduction_ar as $key => $value) {
										?>
										<tr>
											<th><?php echo $i; ?></th>
											<th><?php echo $value['model_name']; ?></th>
											<td>$<?php echo $value['unit_amount']; ?></td>
											<td class="text-right">
												<div class="dropdown dropdown-action">
													<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_deduction<?php echo $value['id']; ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
														<a class="dropdown-item DeleteDeduction" style="cursor: pointer;" data-arid="<?php echo $value['id']; ?>" data-keyid="<?php echo $key; ?>" data-user="<?php echo $employee_details['user_id']; ?>" ><i class="fa fa-trash-o m-r-5"></i> Delete</a>
													</div>
												</div>
											</td>
										</tr>
										<?php $i++; } } ?>
									</tbody>
								</table>
							</div>
							
				</div>
						<!-- /Deductions Tab -->
						
						<!-- Leaves Tab -->
				<div class="tab-pane" id="tab_leaves">
							
					<?php 			
			
						$user_id = $this->uri->segment(3);
	  		
						$leave_details = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname
										FROM `dgt_user_leaves` ul
										left join dgt_common_leave_types lt on lt.leave_id = ul.leave_type
										left join dgt_account_details ad on ad.user_id = ul.user_id 
										where ul.user_id='".$user_id."' ")->result_array();
			 		// print_r($leave_details); exit;
	   				?>
			 		<table id="table-holidays" class="table table-striped custom-table m-b-0 AppendDataTables">
						<thead>
							<tr>
								<th> No </th>
								<th> Leave Type </th>
								<th> Date From </th>
								<th> Date To </th>
								<th> Reason </th> 
								<th> No.of Days </th>  
								<th> Status </th>  							
							</tr>
						</thead>
						<tbody id="admin_leave_tbl">
							<?php 
								if(!empty($leave_details)){
									foreach($leave_details as $key => $details){  ?>
									
										<tr>
											<td><?=$key+1?></td>
											
											<td><?=$details['l_type']?></td>
											<td><?=date('d-m-Y',strtotime($details['leave_from']))?></td>
											<td><?=date('d-m-Y',strtotime($details['leave_to']))?></td>
											<td width="30%"><?=$details['leave_reason']?></td>
											<td>
												<?php 
												echo $details['leave_days'];
												if($details['leave_day_type'] == 1){
													echo ' ( Full Day )';
												}else if($details['leave_day_type'] == 2){
													echo ' ( First Half )';
												}else if($details['leave_day_type'] == 3){
													echo ' ( Second Half )';
												}?>
											</td>
											<td>
												<?php
													if($details['status'] == 4){
														echo '<span class="label label-info"> TL - Approved</span><br>';
														echo '<span class="label label-danger"> Management - Pending</span>';
													}else if($details['status'] == 7){
																echo '<span class="label label-danger"> Deleted </span>';
															}
													if($details['status'] == 0){
														echo ' <span class="label" style="background:#D2691E"> Pending </span>';
													}else if($details['status'] == 1){
														echo '<span class="label label-success"> Approved </span> ';
													}else if($details['status'] == 2){
														echo '<span class="label label-danger"> Rejected</span>';
													}else if($details['status'] == 3){
														echo '<span class="label label-danger"> Cancelled</span>';
													}
												?>
											</td>								
										</tr>
									<?php  } ?>  
								<?php  }else{ ?>
									<tr><td class="text-center" colspan="9">No details were found</td></tr>
							<?php } ?>  
						</tbody>
		   			</table>							
				</div>		<!-- /Leaves Tab -->


				<!--  Attachments tab  -->

				<div class="tab-pane" id="tab_attachments">						
							
					<div class="text-right m-b-30 clearfix">
						<button class="btn btn-primary add-btn" type="button" data-toggle="modal" data-target="#add_attachments"><i class="fa fa-plus"></i> Add Attachment</button>
					</div>

					<div class="payroll-table card">
						<table class="table table-hover table-radius">
							<thead>
								<tr>
									<th style="width: 50px;">#</th>
									<th style="width: 200px;">Attachment Name</th>
									<th>Description</th>
									<th style="width: 30px;" class="text-right">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$all_attachments = $this->db->get_where('attachments',array('user_id'=>$employee_details['user_id']))->result_array();
									
									if(!empty($all_attachments)){
										foreach ($all_attachments as $key => $value) {
								?>
										<tr>
											<th><?php echo $key + 1; ?></th>
											<th><?php echo $value['attach_name']; ?></th>											
											<th><?php echo $value['description']; ?></th>
											<td class="text-right">
												<div class="dropdown dropdown-action">
													<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
													<div class="dropdown-menu dropdown-menu-right">
														<ul>
															<li style="margin: 5px; color: black;">
																<!-- <a class="dropdown-item" href="#"><i class="fa fa-download m-r-5"></i> Download</a> -->
																<form method="post" id="delete_attachment_form" action="<?php echo base_url(); ?>employees/download/<?php echo $value['id']; ?>">
																	<div class="form-group">
																		<input class="form-control" type="hidden" name = "user_id" value="<?php echo $employee_details['user_id']; ?>">
																	</div>												
																	<div style="margin-top: 0; text-align: left;">
																		<button class="btn" style="background-color: white; color: black; font-size: 13px; border: none; width: 10px;  min-width: 10px; padding: 0;"><i class="fa fa-download m-r-5"></i>Download</button>
																	</div>
																</form>
															</li>
															<li style="margin: 5px; color: black;">
																<a style="color: #101010;" class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_attachment<?php echo $value['id']; ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
															</li>
															<li style="margin: 5px; color: black;">
																<a style="color: #101010;" class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_attachment<?php echo $value['id']; ?>"><i class="fa fa-trash-o m-r-5"></i> Delete</a>																
															</li>
														</ul>														
													</div>
												</div>
											</td>
										</tr>
									<?php $i++; } } ?>
							</tbody>
						</table>
					</div>
				</div>

				<!-- /Attachments tab -->


			</div>
		</div>

				<!-- Profile Modal -->
				<div id="profile_info" class="modal fade" role="dialog">
					<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Profile Information</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form id="basic_info_form1" method="post" action="<?php echo base_url(); ?>employees/basic_info_add/<?php echo $employee_details['user_id']; ?>" enctype="multipart/form-data">
									<div class="row">
										<div class="col-md-12">
											<div class="profile-img-wrap edit-img">
												<?php if($employee_details['avatar'] != ''){
													$pro_pict = $employee_details['avatar'];
												}else{
													$pro_pict = 'default_avatar.jpg';
												} ?>
												<img class="inline-block" src="<?php echo base_url(); ?>assets/avatar/<?php echo $pro_pict; ?>" alt="user">
												<div class="fileupload btn">
													<span class="btn-text">Edit</span>
													<input class="upload" type="file" id="employee_pro_pics" name="userfile">
													<input type="hidden" id="employee_user_id" name="users_id" value="<?php echo $employee_details['user_id']; ?>">
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label>Full Name</label>
														<input type="text" class="form-control" name="full_name" value="<?php echo $employee_details['fullname']; ?>">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Gender</label><span class="text-danger">*</span>
														<select class="select2-option form-control" name="gender" style="width:100%;">
															<option value="male" <?php echo ($employee_details['gender'] =='male')?"selected":""?>>Male</option>
															<option value="female" <?php echo ($employee_details['gender'] =='female')?"selected":""?>>Female</option>
														</select>
													</div>
												</div>
												
												<div class="col-md-6">
													<div class="form-group">
														<label>Email</label>
														<input type="text" style="cursor: not-allowed;" class="form-control" value="<?php echo $employee_details['email']; ?>" readonly >
													</div>
												</div>
												
												
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label>Address </label><span><a href="javascript:void(0)" class="office_address"> Head Office</a></span>
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
										<div class="col-md-6">
											<div class="form-group">
												<label style="display:block;">Phone Number</label>
												<input type="text" class="form-control telephone" name="phone" id="phone" value="<?php echo $employee_details['phone']; ?>">
											</div>
										</div>
										<?php $departments = $this->db->order_by("deptname", "asc")->get('departments')->result(); 
											$get_department = $this->db->get_where('departments',array('deptid'=>$employee_details['department_id']))->row_array();
										?>
										<?php if($this->session->userdata('user_id') == $employee_details['user_id']){?>
											<input type="hidden" name="department_name" value="<?php echo $get_department['deptid']?>">																								
										<?php }?>
										<div class="col-md-6">
											<div class="form-group">
												<label><?=lang('department')?> <span class="text-danger">*</span></label>
												<input type="hidden" name="role" value="3">	
												<select class="<?php echo ($this->session->userdata('user_id') == $employee_details['user_id'])?"form-control":"select2-option";?>" style="width:100%;" name="department_name" id="department_name" <?php echo ($this->session->userdata('user_id') == $employee_details['user_id'])?"disabled":"";?>>
														<option value="<?php echo $get_department['deptid']?$get_department['deptid']:''; ?>" selected ><?php echo $get_department['deptname']?$get_department['deptname']:'Select'; ?></option>
															<?php
															 if(!empty($departments))	{
															 foreach ($departments as $department){ ?>
																<option value="<?=$department->deptid?>"><?=$department->deptname?></option>
															<?php } ?>
															<?php } ?>

												</select>
											</div>
										</div>
										<div class="col-sm-6">

											<div class="form-group">
												<?php $get_designation = $this->db->get_where('designation',array('id'=>$employee_details['designation_id']))->row_array(); ?>
												
												<?php if($this->session->userdata('user_id') == $employee_details['user_id']){?>
											
													<input type="hidden" name="designations" value="<?php echo $get_designation['id'];?>">	
											
												
												<?php }?>

												<label>Position <span class="text-danger">*</span></label>

											<select class="form-control" style="width:100%;" name="designations" id="designations" <?php echo ($this->session->userdata('user_id') == $employee_details['user_id'])?"disabled":"";?>>
												<option value="<?php echo $get_designation['id']?$get_designation['id']:'';?>" selected ><?php echo $get_designation['designation']?$get_designation['designation']:'Select';?></option>
												</select>

											</div>

										</div>

										<div class="col-sm-6 TeamDiv">
											<?php $get_users = $this->db->get_where('account_details',array('user_id'=>$employee_details['teamlead_id']))->row_array(); ?>
											<?php if($this->session->userdata('user_id') == $employee_details['user_id']){?>
											
											<input type="hidden" name="reporting_to" value="<?php echo $get_users['user_id'];?>">	
												
										<?php }?>
												<div class="form-group">
													<label>Reporting to </label>
													<select class="form-control" style="width:100%;" name="reporting_to" id="reporting_to" <?php echo ($this->session->userdata('user_id') == $employee_details['user_id'])?"disabled":"";?>>
														<option value="<?php echo $get_users['user_id']?$get_users['user_id']:''; ?>"  selected><?php echo $get_users['fullname']?$get_users['fullname']:'Select'; ?></option>
													</select>
												</div>
											</div>


											<!-- <div class="col-sm-6">

											<div class="form-group">

												<label><?=lang('user_type')?> <span class="text-danger">*</span></label>
													
												<select class="select2-option" style="width:100%;" name="user_type" id="user_type">
														<option value="" selected disabled>User Type</option>
															<?php

															$user_type = $this->db->get('roles')->result();


															 if(!empty($user_type))	{

															 foreach ($user_type as $type){ ?>

																<option value="<?=$type->r_id?>"><?=$type->role?></option>

																<?php } ?>

																<?php } ?>

															

												</select>
											</div>

										</div>
 -->

 										<div class="col-sm-6">

											<div class="form-group">
												<?php 
												$user_type = $this->db->order_by("role", "asc")->get('roles')->result(); 
												$get_usertype = $this->db->get_where('roles',array('r_id'=>$employee_details['user_type']))->row_array(); ?>
												<?php if($this->session->userdata('user_id') == $employee_details['user_id']){?>
											
											<input type="hidden" name="user_type" value="<?php echo $get_usertype['r_id'];?>">	
												
										<?php }?>

												<label>User Type <span class="text-danger">*</span></label>

											<select class="form-control" style="width:100%;" name="user_type" id="user_type" <?php echo ($this->session->userdata('user_id') == $employee_details['user_id'])?"disabled":"";?>>
												<option value="<?php echo $get_usertype['r_id']?$get_usertype['r_id']:'';?>" selected ><?php echo $get_usertype['r_id']?$get_usertype['role']:'Select';?></option>
												<?php
															 if(!empty($user_type))	{
																foreach ($user_type as $usertype){
																	if ($usertype->r_id != 1) {
																	?>
																	<option value="<?=$usertype->r_id?>"><?=$usertype->role?></option>
																	<?php } ?>
																<?php } ?>
															<?php } ?>
												</select>

											</div>

										</div>


										</div>


									<div class="submit-section">
										<button class="btn btn-primary submit-btn" id="basic_info_btn">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Profile Modal -->

				<!-- Bank Modal -->
				<div id="bank_iformation_modal" class="modal fade" role="dialog">
					<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Bank Information</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form id="bank_info_form" method="post" action="<?php echo base_url(); ?>employees/bank_info_add/<?php echo $employee_details['user_id']; ?>">
									<div class="row">
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label>Bank Name</label>
														<input type="text" class="form-control" name="bank_name" value="<?php echo $bank_info->bank_name; ?>">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Bank Account No</label>
														<input type="text" name="bank_ac_no" id="bank_ac_no" class="form-control" value="<?php echo $bank_info->bank_ac_no; ?>" >
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>IFSC Code</label>
														<input type="text" class="form-control" name="ifsc_code" id="ifsc_code" value="<?php echo $bank_info->ifsc_code; ?>">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>PAN No</label>
														<input type="text" name="pan_no" id="pan_no" class="form-control" value="<?php echo $bank_info->pan_no; ?>" >
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn" id="basic_info_btn">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Bank Modal -->

				<!-- Personal Info Modal -->
				<div id="personal_info_modal" class="modal custom-modal fade" role="dialog">
					<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Personal Information</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form id="personal_info_form" method="post" action="<?php echo base_url(); ?>employees/personal_info_add/<?php echo $employee_details['user_id']; ?>">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Passport No <span class="text-danger">*</span></label>
												<input type="text" class="form-control" name="passport_no" id="passport_no" value="<?php echo $personal_info->passport_no; ?>">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Passport Expiry Date <span class="text-danger">*</span></label>
												<div class="cal-icon">
													<input class="form-control datetimepicker" type="text" name="passport_expiry" id="passport_expiry" data-date-format="dd-mm-yyyy" value="<?php echo $personal_info->passport_expiry; ?>">
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Tel <span class="text-danger">*</span></label>
												<input class="form-control" type="text" name="tel_number" id="tel_number" value="<?php echo $personal_info->tel_number; ?>">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Nationality <span class="text-danger">*</span></label>
												<input class="form-control" type="text" name="nationality" id="nationality" value="<?php echo $personal_info->nationality; ?>">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Religion <span class="text-danger">*</span></label>
													<input class="form-control" type="text" name="religion" id="religion" value="<?php echo $personal_info->religion; ?>">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Marital status <span class="text-danger">*</span></label>
												<select class="select form-control" name="marital_status" id="marital_status" >
													<option value="" disabled>-</option>
													<option value="single" <?php if($personal_info->religion == 'single'){ echo "selected"; } ?> >Single</option>
													<option value="married" <?php if($personal_info->religion == 'married'){ echo "selected"; } ?>>Married</option>
												</select>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Employment of spouse</label>
												<input class="form-control" type="text" name="spouse" id="spouse" value="<?php echo $personal_info->spouse; ?>">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>No. of children</label>
												<input class="form-control" type="text" name="no_children" id="no_children" value="<?php echo $personal_info->no_children; ?>">
											</div>
										</div>
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Personal Info Modal -->
				
				<!-- Family Info Modal -->
				<div id="family_info_modal" class="modal custom-modal fade" role="dialog">
					<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title"> Family Informations</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form method="post" id="family_info_form" action="<?php echo base_url(); ?>employees/family_info_add/<?php echo $employee_details['user_id']; ?>">
									<div class="form-scroll">
										<div class="card-box AllFamilyMembers">
											<?php $personal_info = json_decode($personal_details['family_members_info']); 
														// echo "<pre>"; print_r($personal_info); exit;
														if(count($personal_info) != 0)
														{
															$i = 1; 
														foreach ($personal_info as $per) {
												?>
											<div class="FamilyMembers">
												<h3 class="card-title">Family Member </h3><?php if($i != 1) { ?> <a href="#" class="remove_family_div"><i class="fa fa-trash-o"></i></a> <?php } ?>
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label>Name <span class="text-danger">*</span></label>
															<input class="form-control" type="text" name="member_name[]" value="<?php echo $per->member_name; ?>">
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label>Relationship <span class="text-danger">*</span></label>
															<input class="form-control" type="text" name="member_relationship[]" value="<?php echo $per->member_relationship; ?>">
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label>Date of birth <span class="text-danger">*</span></label>
															<input class="form-control ALlmembers" type="text" name="member_dob[]" value="<?php echo $per->member_dob; ?>" >
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label>Phone <span class="text-danger">*</span></label>
															<input class="form-control" type="text" name="member_phone[]" value="<?php echo $per->member_phone; ?>"> 
														</div>
													</div>
												</div>
											</div>
										<?php $i++; } }else{ ?>
											<div class="FamilyMembers">
												<h3 class="card-title">Family Member </h3>
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label>Name <span class="text-danger">*</span></label>
															<input class="form-control" type="text" name="member_name[]">
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label>Relationship <span class="text-danger">*</span></label>
															<input class="form-control" type="text" name="member_relationship[]">
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label>Date of birth <span class="text-danger">*</span></label>
															<input class="form-control ALlmembers" type="text" name="member_dob[]" >
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label>Phone <span class="text-danger">*</span></label>
															<input class="form-control" type="text" name="member_phone[]"> 
														</div>
													</div>
												</div>
											</div>
										<?php } ?>
											<div class="add-more">
												<a href="#" id="add_more_family"><i class="fa fa-plus-circle"></i> Add More</a>
											</div>
										</div>
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Family Info Modal -->
				
				<!-- Emergency Contact Modal -->
				<div id="emergency_contact_modal" class="modal custom-modal fade" role="dialog">
					<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Emergency Contact Information</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form id="emergency_form" method="post" action="<?php echo base_url(); ?>employees/emergency_info_add/<?php echo $employee_details['user_id']; ?>"> 
									<div class="card-box">
										<h3 class="card-title">Primary Contact</h3>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>Name <span class="text-danger">*</span></label>
													<input type="text" class="form-control" name="contact_name1" id="contact_name1" value="<?php echo $emergency_info->contact_name1; ?>">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Relationship <span class="text-danger">*</span></label>
													<select class="select form-control" name="relationship1" id="relationship1">
													<option value="" disabled>-</option>
													<option value="father" <?php if($emergency_info->relationship1 == 'father'){ echo "selected"; }  ?> >Father</option>
													<option value="mother" <?php if($emergency_info->relationship1 == 'mother'){ echo "selected"; }  ?> >Mother</option>
													<option value="sister" <?php if($emergency_info->relationship1 == 'sister'){ echo "selected"; }  ?>>Sister</option>
													<option value="brother" <?php if($emergency_info->relationship1 == 'brother'){ echo "selected"; }  ?> >Brother</option>
													<option value="others" <?php if($emergency_info->relationship1 == 'others'){ echo "selected"; }  ?>>Others</option>
												</select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Phone <span class="text-danger">*</span></label>
													<input class="form-control" type="text" name="contact1_phone1" id="contact1_phone1" value="<?php echo $emergency_info->contact1_phone1;  ?>">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Phone 2</label>
													<input class="form-control" type="text" name="contact1_phone2" id="contact1_phone2" value="<?php echo $emergency_info->contact1_phone2;  ?>">
												</div>
											</div>
										</div>
									</div>
									<div class="card-box">
										<h3 class="card-title">Secondary Contact</h3>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>Name <span class="text-danger">*</span></label>
													<input type="text" class="form-control" name="contact_name2" id="contact_name2" value="<?php echo $emergency_info->contact_name2;  ?>">
												</div>
											</div>
											<div class="col-md-6">
													<div class="form-group">
													<label>Relationship <span class="text-danger">*</span></label>
													<select class="select form-control" name="relationship2" id="relationship2">
													<option value="" disabled>-</option>
													<option value="father" <?php if($emergency_info->relationship2 == 'father'){ echo "selected"; }  ?> >Father</option>
													<option value="mother" <?php if($emergency_info->relationship2 == 'mother'){ echo "selected"; }  ?> >Mother</option>
													<option value="sister" <?php if($emergency_info->relationship2 == 'sister'){ echo "selected"; }  ?>>Sister</option>
													<option value="brother" <?php if($emergency_info->relationship2 == 'brother'){ echo "selected"; }  ?> >Brother</option>
													<option value="others" <?php if($emergency_info->relationship2 == 'others'){ echo "selected"; }  ?>>Others</option>
												</select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Phone <span class="text-danger">*</span></label>
													<input class="form-control" type="text" name="contact2_phone1" id="contact2_phone1" value="<?php echo $emergency_info->contact2_phone1;  ?>">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Phone 2</label>
													<input class="form-control" type="text" name="contact2_phone2" id="contact2_phone2" value="<?php echo $emergency_info->contact2_phone2;  ?>">
												</div>
											</div>
										</div>
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Emergency Contact Modal -->

				<!-- Education Modal -->
				<div id="education_info" class="modal fade" role="dialog">
					<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title"> Education Informations</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form method="post" id="education_info_form" action="<?php echo base_url(); ?>employees/education_info_add/<?php echo $employee_details['user_id']; ?>">
									<div class="form-scroll AllInstitute">
										<?php $i =1; 
												 // print_r($personal_details); exit;
												if(!empty($education_details)){
												 // $pers = json_decode($personal_details['education_details']);
												 foreach($education_details as $p){ ?>
										<div class="card-box MultipleInstitutions">
											<h3 class="card-title">Education Informations</h3> <?php if($i != 1) { ?> <a href="#" class="remove_div"><i class="fa fa-trash-o"></i></a> <?php } ?>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group form-focus focused">
														<input type="text" class="form-control floating" name="institute[]" value="<?php echo $p->institute; ?>">
														<label class="control-label">Institution</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group form-focus focused">
														<input type="text" class="form-control floating" name="subject[]" value="<?php echo $p->subject; ?>">
														<label class="control-label">Subject</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group form-focus focused">
														<div class="cal-icon">
															<input type="text" name="start_date[]" class="form-control floating datetimepicker" value="<?php echo $p->start_date; ?>">
														</div>
														<label class="control-label">Starting Date</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group form-focus focused">
														<div class="cal-icon">
															<input type="text" name="end_date[]" class="form-control floating datetimepicker" value="<?php echo $p->end_date; ?>">
														</div>
														<label class="control-label">Complete Date</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group form-focus focused">
														<input type="text" name="degree[]" class="form-control floating" value="<?php echo $p->degree; ?>">
														<label class="control-label">Degree</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group form-focus focused">
														<input type="text" name="grade[]" class="form-control floating" value="<?php echo $p->grade; ?>">
														<label class="control-label">Grade</label>
													</div>
												</div>
											</div>
										</div>
									<?php $i++; } }else{ ?>
										<div class="card-box MultipleInstitutions">
											<h3 class="card-title">Education Informations </h3>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group form-focus focused">
														<input type="text" value="" class="form-control floating" name="institute[]">
														<label class="control-label">Institution</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group form-focus focused">
														<input type="text" class="form-control floating" name="subject[]">
														<label class="control-label">Subject</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group form-focus focused">
														<div class="cal-icon">
															<input type="text" name="start_date[]" class="form-control floating datetimepicker">
														</div>
														<label class="control-label">Starting Date</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group form-focus focused">
														<div class="cal-icon">
															<input type="text" name="end_date[]" class="form-control floating datetimepicker">
														</div>
														<label class="control-label">Complete Date</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group form-focus focused">
														<input type="text" name="degree[]" class="form-control floating">
														<label class="control-label">Degree</label>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group form-focus focused">
														<input type="text" name="grade[]" class="form-control floating">
														<label class="control-label">Grade</label>
													</div>
												</div>
											</div>
										</div>
									<?php }  ?>
										<div class="add-more">
											<a href="#" id="Add_more_institution"><i class="fa fa-plus-circle"></i> Add More Institute</a>
										</div>
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Education Modal -->

<!-- Experience Modal -->

<div id="experience_info" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Experience Informations</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" id="experience_info_form" action="<?php echo base_url(); ?>employees/experience_info_add/<?php echo $employee_details['user_id']; ?>">
					<div class="form-scroll">
						<div class="card-box AllExperience">
						    <?php $i =1; 
												
								 $pers = json_decode($personal_details['personal_details']);
								 // echo "<pre>"; print_r($pers); exit;
								 if(!empty($pers)){
								 foreach($pers as $p){ ?>
						    <div class="MultipleExperience">
							<h3 class="card-title">Experience Informations </h3> <?php if($i != 1){ ?> <a href="#" class="remove_exp_div"><i class="fa fa-trash-o"></i></a><?php } ?>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group form-focus">
										<input type="text" class="form-control floating" value="<?php echo $p->company_name; ?>" name="company_name[]">
										<label class="control-label">Company Name</label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group form-focus">
										<input type="text" class="form-control floating" value="<?php echo $p->location; ?>" name="location[]" >
										<label class="control-label">Location</label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group form-focus">
										<input type="text" class="form-control floating" value="<?php echo $p->job_position; ?>" name="job_position[]">
										<label class="control-label">Job Position</label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group form-focus">
										<div class="cal-icon">
											<input type="text" class="form-control floating datetimepicker" value="<?php echo $p->period_from; ?>" name="period_from[]">
										</div>
										<label class="control-label">Period From</label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group form-focus">
										<div class="cal-icon">
											<input type="text" class="form-control floating datetimepicker" value="<?php echo $p->period_to; ?>" name="period_to[]">
										</div>
										<label class="control-label">Period To</label>
									</div>
								</div>
							</div>
							</div>
							<?php $i++; } }else{ ?>
							    <div class="MultipleExperience">
							<h3 class="card-title">Experience Informations </h3>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group form-focus">
										<input type="text" class="form-control floating" value="" name="company_name[]">
										<label class="control-label">Company Name</label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group form-focus">
										<input type="text" class="form-control floating" value="" name="location[]">
										<label class="control-label">Location</label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group form-focus">
										<input type="text" class="form-control floating" value="" name="job_position[]">
										<label class="control-label">Job Position</label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group form-focus">
										<div class="cal-icon">
											<input type="text" class="form-control floating datetimepicker" value="" name="period_from[]">
										</div>
										<label class="control-label">Period From</label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group form-focus">
										<div class="cal-icon">
											<input type="text" class="form-control floating datetimepicker" value="" name="period_to[]">
										</div>
										<label class="control-label">Period To</label>
									</div>
								</div>
							</div>
							</div>
							<?php } ?>
						</div>
						<div class="add-more">
								<a href="#" id="Add_experience"><i class="fa fa-plus-circle"></i> Add More</a>
						</div>
					</div>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /Experience Modal -->

				<!-- Add Addition Modal -->
				<div id="add_addition" class="modal center-modal fade" role="dialog">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Add Addition</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form method="post" id="addtional_form" action="<?php echo base_url(); ?>employees/addtional_pf_details">
									<div class="form-group">
										<label>Name <span class="text-danger">*</span></label>
										<input class="form-control" type="text" name="addtion_name" id="addtion_name" value="" placeholder="Name"> 
										<input type="hidden" name="user_id" id="user_id" value="<?php echo $employee_details['user_id']; ?>">
									</div>
									<div class="form-group">
										<label>Category <span class="text-danger">*</span></label>
										<select class="select" name="category_name" id="category_name">
											<option value="" selected disabled>Select a category</option>
											<option value="monthly" >Monthly remuneration</option>
											<option value="addtional">Additional remuneration</option>
										</select>
									</div>
									<div class="form-group">
										<label>Unit Amount</label>
										<div class="input-group">
											<span class="input-group-addon">
												$
											</span>
											<input type="text" class="form-control" name="unit_amount" id="unit_amount" value="" placeholder="Unit Amount">
											<span class="input-group-addon">
												.00
											</span>
										</div>
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Add Addition Modal -->

				<?php foreach ($addtional_ar as $add_ar) { ?>
				
				<!-- Edit Addition Modal -->
				<div id="edit_addition<?php echo $add_ar['id']; ?>" class="modal center-modal fade" role="dialog">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Edit Addition</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form method="post" id="edit_addtional_form" action="<?php echo base_url(); ?>employees/edit_additional/<?php echo $add_ar['id']; ?>">
									<div class="form-group">
										<label>Name <span class="text-danger">*</span></label>
										<input class="form-control" type="text" name="addtion_name" value="<?php echo $add_ar['addtion_name']; ?>" >
										<input type="hidden" name="user_id" value="<?php echo $employee_details['user_id']; ?>">
									</div>
									<div class="form-group">
										<label>Category <span class="text-danger">*</span></label>
										<select class="select" name="category_name">
											<option value="" disabled>Select a category</option>
											<option value="monthly" <?php if($add_ar['category_name'] == 'monthly'){ echo "selected"; } ?>>Monthly remuneration</option>
											<option value="addtional" <?php if($add_ar['category_name'] == 'addtional'){ echo "selected"; } ?>>Additional remuneration</option>
										</select>
									</div>
									<div class="form-group">
										<label>Unit Amount</label>
										<div class="input-group">
											<span class="input-group-addon">
												$
											</span>
											<input type="text" class="form-control" name="unit_amount" value="<?php echo $add_ar['unit_amount']; ?>">
											<span class="input-group-addon">
												.00
											</span>
										</div>
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Save</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Edit Addition Modal -->
				
				<!-- Delete Addition Modal -->
				<div class="modal center-modal fade" id="delete_addition<?php echo $add_ar['id']; ?>" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-body">
								<div class="form-header">
									<h3>Delete Addition</h3>
									<p>Are you sure want to delete?</p>
								</div>
								<div class="modal-btn delete-action">
									<div class="row">
										<div class="col-6">
											<a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
										</div>
										<div class="col-6">
											<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Delete Addition Modal -->
				<?php } ?>
				
				<!-- Add Overtime Modal -->
				<div id="add_overtime" class="modal center-modal fade" role="dialog">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Add Overtime</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form action="<?php echo base_url();?>employees/add_overtime"  id="add_overtimes" method="post" enctype="multipart/form-data" data-parsley-validate novalidate > 
									<div class="form-group">
										<label>Description <span class="text-danger">*</span></label>
										<input class="form-control" type="text" name="ot_description" required value="" >
										<input class="form-control" type="hidden" name="user_id"  value="<?php echo $employee_details['user_id']; ?>" >
									</div>
									<div class="form-group">
										<label>Date<span class="text-danger">*</span></label>
										<input class="form-control datetimepicker" type="text" name="ot_date" required id="ot_date">
										<?php $teamlead_details = $this->db->get_where('dgt_users',array('id'=>$this->session->userdata('user_id')))->row_array(); ?>
						                 <input type="hidden" name="teamlead_id" id="teamlead_id" value="<?php echo $teamlead_details['teamlead_id']; ?>">
									</div>
									<div class="form-group">
										<label>Hours<span class="text-danger">*</span></label>
										<input class="form-control" type="text" name="ot_hours" id="ot_hours" required="" placeholder="HH:MM">
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Add Overtime Modal -->
				
				<!-- Edit Overtime Modal -->
				<div id="edit_overtime" class="modal center-modal fade" role="dialog">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Edit Overtime</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form>
									<div class="form-group">
										<label>Name <span class="text-danger">*</span></label>
										<input class="form-control" type="text" name="ot_description" value="" placeholder="OT Description">
									</div>
									<div class="form-group">
										<label>Date<span class="text-danger">*</span></label>
										<input class="form-control" type="text" name="ot_date" id="ot_date">
									</div>
									<div class="form-group">
										<label>Hours<span class="text-danger">*</span></label>
										<input class="form-control" type="text" name="ot_hours" id="ot_hours" placeholder="HH:MM">
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Save</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Edit Overtime Modal -->
				
				<!-- Delete Overtime Modal -->
				<div class="modal center-modal fade" id="delete_overtime" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-body">
								<div class="form-header">
									<h3>Delete Overtime</h3>
									<p>Are you sure want to delete?</p>
								</div>
								<div class="modal-btn delete-action">
									<div class="row">
										<div class="col-6">
											<a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
										</div>
										<div class="col-6">
											<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Delete Overtime Modal -->
				
				<!-- Add Deduction Modal -->
				<div id="add_deduction" class="modal center-modal fade" role="dialog">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Add Deduction</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form method="post" action="<?php echo base_url(); ?>employees/add_deduction/">
									<div class="form-group">
										<label>Name <span class="text-danger">*</span></label>
										<input class="form-control" type="text" name = "model_name" id="model_name" value="">
										<input class="form-control" type="hidden" name = "user_id" value="<?php echo $employee_details['user_id']; ?>">
									</div>
									<div class="form-group">
										<label>Unit Amount</label>
										<div class="input-group">
											<span class="input-group-addon">
												$
											</span>
											<input type="text" class="form-control" name="unit_amount" value="">
											<span class="input-group-addon">
												.00
											</span>
										</div>
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Add Deduction Modal -->

				<?php foreach ($deduction_ar as $dec_ar) { ?>
				
				<!-- Edit Deduction Modal -->
				<div id="edit_deduction<?php echo $dec_ar['id']; ?>" class="modal center-modal fade" role="dialog">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Edit Deduction</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form method="post" id="edit_addition_form" action="<?php echo base_url(); ?>employees/edit_pfdeduction/<?php echo $dec_ar['id']; ?>">
									<div class="form-group">
										<label>Name <span class="text-danger">*</span></label>
										<input class="form-control" type="text" name="model_name" value="<?php echo $dec_ar['model_name']; ?>">
										<input class="form-control" type="hidden" name="user_id" value="<?php echo $employee_details['user_id']; ?>">
									</div>
									<div class="form-group">
										<label>Unit Amount</label>
										<div class="input-group">
											<span class="input-group-addon">
												$
											</span>
											<input type="text" class="form-control" name="unit_amount" value="<?php echo $dec_ar['unit_amount']; ?>">
											<span class="input-group-addon">
												.00
											</span>
										</div>
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Save</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Edit Addition Modal -->
				<?php } ?>
				
				<!-- Delete Deduction Modal -->
				<div class="modal center-modal fade" id="delete_deduction" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-body">
								<div class="form-header">
									<h3>Delete Deduction</h3>
									<p>Are you sure want to delete?</p>
								</div>
								<div class="modal-btn delete-action">
									<div class="row">
										<div class="col-6">
											<a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
										</div>
										<div class="col-6">
											<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Delete Deduction Modal -->



				<!-- Add Attachment Modal -->
				<div id="add_attachments" class="modal center-modal fade" role="dialog">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Add Attachment</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form method="post" action="<?php echo base_url(); ?>employees/add_attachments/" enctype="multipart/form-data">
									<div class="form-group">
										<label>Attachment Name <span class="text-danger">*</span></label>
										<input class="form-control" type="text" name = "model_name" id="model_name" value="">
										<input class="form-control" type="hidden" name = "user_id" value="<?php echo $employee_details['user_id']; ?>">
									</div>

									<div class="form-group">
										<label>Description <span class="text-danger">*</span></label>										
										<textarea class="form-control" name="description" id="description" rows="4"></textarea>
									</div>

									<div class="form-group">
										<label>Upload Attachment </label>
										<div class="custom-file">
											<input type="file" class="custom-file-input" name="attach_upload" id="attach_upload">
											<input type="hidden" name="attach_files" id="attach_files">
											<label class="custom-file-label" for="attach_upload">Choose file</label>
										</div>
									</div>
									
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Add Attachment Modal -->

				<?php foreach ($all_attachments as $dec_ar => $value) { ?>
				
				<!-- Edit Attachment Modal -->
				<div id="edit_attachment<?php echo $value['id']; ?>" class="modal center-modal fade" role="dialog">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Edit Attachment</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form method="post" id="edit_addition_form" action="<?php echo base_url(); ?>employees/edit_attachments/<?php echo $value['id']; ?>" enctype="multipart/form-data">
									<div class="form-group">
										<label>Attachment Name <span class="text-danger">*</span></label>
										<input class="form-control" type="text" name = "model_name" id="model_name" value="<?php echo $value['attach_name']; ?>">
										<input class="form-control" type="hidden" name = "user_id" value="<?php echo $employee_details['user_id']; ?>">
									</div>

									<div class="form-group">
										<label>Description <span class="text-danger">*</span></label>										
										<textarea class="form-control" name="description" id="description" rows="4"><?php echo $value['description']; ?></textarea>
									</div>

									<div class="form-group">
										<label>Upload Attachment </label>
										<div class="custom-file">
											<input type="file" class="custom-file-input" name="attach_upload" id="attach_upload">
											<input type="hidden" name="attach_files" id="attach_files">
											<label class="custom-file-label" for="attach_upload">Choose file</label>
										</div>
									</div>
									
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Save</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Edit Attachment Modal -->

				<!-- Delete Attachment Modal -->
				<div class="modal center-modal fade" id="delete_attachment<?php echo $value['id']; ?>" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-body">
								<div class="form-header">
									<h3>Delete Attachment</h3>
									<p>Are you sure want to delete?</p>
								</div>
								<div class="modal-btn delete-action">
									<div class="row">
										<div class="col-6">
											<form method="post" id="delete_attachment_form" action="<?php echo base_url(); ?>employees/delete_attachments/<?php echo $value['id']; ?>">
												<div class="form-group">													
													<input class="form-control" type="hidden" name = "user_id" value="<?php echo $employee_details['user_id']; ?>">
												</div>												
												<div class="submit-section">
													<button class="btn btn-primary submit-btn">Delete</button>
												</div>
											</form>
										</div>
										<div class="col-6">											
											<form method="post" id="cancel_attachment_form" action="">
												<div class="submit-section" style="margin-top: 20px;">													
													<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn" style="width: 195px; margin: 0 auto;">Cancel</a>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Delete Attachment Modal -->
				<?php } ?>
				
				
<script type="text/javascript">
	var office_address = "<?=config_item('company_address')?>";
	var office_city = "<?=config_item('company_city')?>";
	var office_state = "<?=config_item('company_state')?>";
	var office_zip_code = "<?=config_item('company_zip_code')?>";

</script>>