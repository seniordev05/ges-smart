<?php 
    $offer_approvers = $this->db->get('offer_approver_settings')->result_array();
    $currency = $this->db->get('currency')->result_array();
    if(!empty($offer_approvers)){
        $default_offer_approvals = $this->db->get_where('offer_approver_settings',array('default_offer_approval'=>'seq-approver'))->result_array();
        // echo "<pre>";print_r($default_offer_approvals);
        if(!empty($default_offer_approvals)){
            $default_offer_approval = 'seq-approver';
            $seq_approve = 'seq-approver';
            
        }else {
            $default_offer_approval = 'sim-approver';
            $sim_approve = 'sim-approver';
        }
    }else {
        $default_offer_approval = '';
    }
     // echo "<pre>";print_r($currency);
 ?>


<!-- Content -->
                <div class="content container-fluid">
					
					<!-- Title -->
					<div class="row">
						<div class="col-sm-8">
							<h4 class="page-title">Create Offer</h4>
						</div>
					</div>
					<!-- / Title -->
					
					<div class="row">
						<div class="col-md-12">
							<div class="offer-create">
							
								<!-- Offer Create Wizard -->
								<div class="tabbable">
									<ul class="nav navbar-nav wizard">
										<li class="">
											<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Created</a>
										</li>
										<li>
											<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>In Progress</a>
										</li>
										<li>
											<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Approved</a>
										</li>
										
										<li>
											<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Send Offer</a>
										</li>
									</ul>
								</div>
								<!-- /Offer Create Wizard -->
								
								<!-- <div class="row">
									<div class="col-md-12">
										<h3 class="page-sub-title">Create Offer</h3>
									</div>
								</div> -->
								<div class="row">
									<div class="col-md-12">
										<form class="form-horizontal" id="create_offers" action="<?=base_url()?>offers/create" method="POST">
											
											<div class="form-group">
												<label class="control-label col-sm-3">Create offer for  </label>
												<div class="col-sm-9">
													<input class="form-control" type="text" name="candidate" id="check_candidate">
													<span id="already_candidate" style="display: none;color:red;">Already created offer to this Candidate</span>
												</div>

											</div>
											<div class="form-group">
												<label class="control-label col-sm-3">Title</label>
												<div class="col-sm-9">
													<input class="form-control" type="text" name="title" id="title">
													<input class="form-control" type="hidden" name="status" value="1">
												</div>
											</div>
											
											<div class="form-group">
												<label class="control-label col-sm-3">Job Type</label>
												<div class="col-sm-9">
													<select class="form-control select" name="job_type" id="job_type">
														<option value="">Select Job Type</option>
														<?php foreach (User::GetJobType() as $jtype =>$jvalue): ?>
															<option value="<?=$jvalue['id']?>"  >
																<?php echo ucfirst(trim($jvalue['job_type']));?>
															</option>
														<?php endforeach ?>	
													</select>
												</div>
											</div>
									<!--  -->
											<div class="form-group base_salary hide">
												<label class="control-label col-sm-3 ">Base Salary</label>
												<div class="col-sm-6">
													<input class="form-control" type="number" name="salary" >
													<input class="form-control" type="hidden" name="salary_type" value="base_salary">
												</div>
												<div class="col-sm-3">
													<select class="select2-option form-control" name="currency_type" style="width: 100%">
														<?php $cur = App::currencies(config_item('default_currency')); ?>
						                                <?php foreach ($currencies as $cur) : ?>
														<option value="<?=$cur->symbol?>" <?=(config_item('default_currency_symbol') == $cur->symbol ? ' selected="selected"' : '')?>><?=$cur->symbol?> - <?=$cur->name?></option>
						                                <?php endforeach; ?>
													</select>
													<!-- <input class="form-control" type="number" name="currency_type" > -->
												</div>
											</div>
											<div class="form-group hourly_rate hide">
												<label class="control-label col-sm-3 ">Hourly Rate</label>
												<div class="col-sm-6">
													<input class="form-control" type="number" name="salary" >
													<input class="form-control" type="hidden" name="salary_type" value="hourly_rate">
												</div>
												<div class="col-sm-3">
													<select class="select2-option form-control" name="currency_type" style="width: 100%">
														<!-- <option value="">Select Annual Incentive Plan</option> -->
														<!-- <?php foreach ($currency as $curren){ ?>
															<option value="<?=$curren['code']?>"  >
																<?php echo  ucfirst(trim($curren['country']));?>
															</option>
														<?php } ?> -->
														<?php $cur = App::currencies(config_item('default_currency')); ?>
						                                <?php foreach ($currencies as $cur) : ?>
														<option value="<?=$cur->symbol?>" <?=(config_item('default_currency_symbol') == $cur->symbol ? ' selected="selected"' : '')?>><?=$cur->symbol?> - <?=$cur->name?></option>
						                                <?php endforeach; ?>
						                            </select>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-sm-3">Annual Incentive Plan</label>
												<div class="col-sm-9">
													<select class="select form-control" name="annual_incentive_plan" id="annual_incentive_plan">
														<option value="">Select Annual Incentive Plan</option>
														<?php foreach (User::get_annual_incentive_plans() as $plans =>$plan): ?>
															<option value="<?=$plan['id']?>"  >
																<?= ucfirst(trim($plan['plan']));?>
															</option>
														<?php endforeach ?>
													</select>
												</div>
											</div>
											
											<div class="form-group">
												<label class="control-label col-sm-3">Long Term Incentive Plan</label>
												<div class=" col-sm-9">
													<div class="onoffswitch">
														<input type="checkbox" class="onoffswitch-checkbox" id="onoffswitch" name="long_term_incentive_plan" >
														<label class="onoffswitch-label" for="onoffswitch">
															<span class="onoffswitch-inner"></span>
															<span class="onoffswitch-switch"></span>
														</label>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-sm-3">Vacation</label>
												<div class="col-sm-9">
													<select class="select form-control" name="vacation" id="Vacation">
														<option value="">Select Vacation</option>
														<?php foreach (User::get_vocations() as $vocations =>$vocation): ?>
															<option value="<?=$vocation['id']?>"  >
																<?= ucfirst(trim($vocation['vocation']));?>
															</option>
														<?php endforeach ?>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-sm-3">Reports to</label>
												<div class="col-sm-9">													
													<select class="select2-option form-control"   style="width:260px" name="reports_to" id="reports_to"> 
														<optgroup>Select Report to</optgroup> 
														<optgroup label="Staff">
															<option value="">Select Report to</option>
															<?php foreach (User::team() as $user): ?>
																<option value="<?=$user->id?>"  >
																	<?=ucfirst(User::displayName($user->id))?>
																</option>
															<?php endforeach ?>
														</optgroup> 
													</select>
												</div>
											</div>
											<input type="hidden"  value="<?php echo $default_offer_approval ;?>" name="default_offer_approval">
											<!-- <div class="form-group">
												<label class="control-label col-sm-3">Default Offer Approval</label>
												<div class="col-md-9 approval-option">
													<label class="radio-inline">
														<input id="radio-single" value="seq-approver" name="default_offer_approval" type="radio">Sequence Approval (Chain) <sup> <span class="badge info-badge"><i class="fa fa-info" aria-hidden="true"></i></span></sup>
													</label>
													<label class="radio-inline">
														<input id="radio-multiple" value="sim-approver" name="default_offer_approval"type="radio">Simultaneous Approval <sup><span class="badge info-badge"><i class="fa fa-info" aria-hidden="true"></i></span></sup>
													</label>
												</div>
											</div> -->

											<?php if(!empty($offer_approvers)){ 
												if($default_offer_approval == 'seq-approver'){
												?>
												<div class="form-group row ">
												<label class="control-label col-sm-3">Offer Approvers</label>
												
												<div class="col-sm-9 ">
													<label class="control-label m-b-10" style="padding-left:0">Approver 1</label>
													<div class="row">
														<div class="col-md-10">
															<!-- <select class="select form-control" name="offer_approvers" >
																<option>Recruiter</option>
																<option>Hiring Manager</option>
																<option>Manager</option>
																<option>Manager</option>
																<option>Manager</option>
															</select> -->
															<select class="select2-option form-control"   style="width:260px" name="offer_approvers[]" id="offer_approvers"> 
														<optgroup>Select Approvers</optgroup> 
														<optgroup label="Staff">
															<?php foreach (User::team() as $user): ?>
																<option value="<?=$user->id?>"  >
																	<?=ucfirst(User::displayName($user->id))?>
																</option>
															<?php endforeach ?>
														</optgroup> 
													</select>
														</div>
														<!-- <div class="col-md-2">
															<span class="m-t-10 badge btn-success">Approved</span>
														</div> -->
													</div>
													<div id="items">
													</div>
												</div>
												<div class="row">
													<input type="hidden" id="count" value="1">
											<div class="col-sm-9 col-md-offset-3 m-t-10">
												<a id="add1" href="javascript:void(0)" class="add-more">+ Add Approver</a>
											</div>
											</div>
											</div>
	                             		<?php } }?>

	                             		<?php if($default_offer_approval == 'sim-approver'){ ?>
											<div class="form-group row ">
												<label class="control-label col-sm-3">Offer Approvers</label>
												<div class="col-sm-9 ">
													<label class="control-label" style="margin-bottom:10px;padding-left:0">Simultaneous Approval </label>
													<div class="row">
														<div class="col-md-12">
															<select class="select2-option form-control" multiple="multiple" style="width:100%" name="offer_approvers[]" > 
																<optgroup label="Staff">
																	
																<?php foreach (User::team() as $user){ ?>
						                                        <option value="<?=$user->id?>" >
						                                            <?=ucfirst(User::displayName($user->id))?>
						                                        </option>
						                                    <?php } ?>
																</optgroup> 
															</select>

														</div>
														<!-- <div class="col-md-2">
															<span class="m-t-10 badge btn-success">Approved</span>
														</div> -->
													</div>
												</div>
											</div>
											
											<?php } ?>
											
											
											
											
											
											<div class="row m-t-20">
												<div class="col-md-9 col-sm-offset-3">
													<label class="control-label" style="margin-bottom:10px;padding-left:0">Message to Approvers</label>
													<div class="row">
														<div class="col-md-12">
															 <textarea class="form-control" rows="5" name="message_to_approvers" id="message_to_approvers"></textarea>
														</div>
													</div>
												</div>
											</div>
											<div class="m-t-30 text-center">
												<button class="btn btn-primary" type="submit" id="create_offers_submit">Create Offer & Send for Approval</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
                </div>
				<!-- / Content -->