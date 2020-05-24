<?php

	$okr_id = $this->session->userdata('user_id');
	$okrdetails = $this->db->get_where('okrdetails',array('user_id'=>$okr_id))->row_array();
	$okr_objective = $this->db->get_where('okr_key_results',array('okrdetailsid'=>$okrdetails['id']))->result();
	$teamlead = $this->db->get_where('account_details',array('user_id'=>$okrdetails['lead']))->row_array();
	// print_r($okrdetails);exit;
?>

           
                <div class="content container-fluid">
					<div class="row">
						<div class="col-sm-8">
							<h4 class="page-title m-b-5">OKR Performance</h4>
							<!-- <ol class="breadcrumb page-breadcrumb">
								<li><a href="#">Offer Accepted</a></li>
								<li><a href="#">Completed Forms</a></li>
								<li class="active">Set Your Goal</li>
							</ol> -->
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-sm-4">
									<table class="table table-border user-info-table">
										<tbody>
											<tr>
												<td>Employee</td>
												<td class="text-right"><?php echo $okrdetails['emp_name']?></td>
											</tr>
											<tr>
												<td>Position</td>
												<td class="text-right"><?php echo $okrdetails['position']?></td>
											</tr>
											<tr>
												<td>Direct Manager</td>
												<td class="text-right"><?php echo $teamlead['fullname']?></td>
											</tr>
										</tbody>
									</table>
								</div>
								<form action="<?php echo base_url()?>performance/edit_okrdetails" method="post">
								<div class="col-sm-8">
									<div class="join-year">
										<span>Year</span>
										<select class="select form-control" name="goal_year">
											
											<option value="2019" <?php if($okrdetails['goal_year'] == "2019") echo 'selected';?>>2019</option>
											<option value="2020" <?php if($okrdetails['goal_year'] == "2020") echo 'selected';?>>2020</option>
											<option value="2021" <?php if($okrdetails['goal_year'] == "2021") echo 'selected';?>>2021</option>
											<option value="2022" <?php if($okrdetails['goal_year'] == "2022") echo 'selected';?>>2022</option>
											<option value="2023" <?php if($okrdetails['goal_year'] == "2023") echo 'selected';?>>2023</option>
											<option value="2024" <?php if($okrdetails['goal_year'] == "2024") echo 'selected';?>>2024</option>
											
											

										</select>
									</div>
								</div>
							</div>
							
								<input type="hidden" name="emp_name" value="<?php echo $okrdetails['emp_name']?>">
								<input type="hidden" name="position" value="<?php echo $okrdetails['position']?>">	
								<input type="hidden" name="lead" value="<?php echo $okrdetails['lead']?>">
								<input type="hidden" name="id" value="<?php echo $okrdetails['id']?>">
							<div class="form-group">
								<label>Goal Duration</label>
								<div class="radio_input">
									<label class="radio-inline custom_radio">
										
									<input type="radio" name="goal_duration" <?php echo ($okrdetails['goal_duration'] =='90 days')? 'checked':'' ?> value="90 days">90 days<span class="checkmark"  ></span>
									</label>
									<label class="radio-inline custom_radio">
										
										<input type="radio" name="goal_duration"  <?php echo ($okrdetails['goal_duration'] =='6 month')? 'checked':'' ?>  value="6 month">6 Month<span class="checkmark"></span>
									</label>
									<label class="radio-inline custom_radio">
										
										<input type="radio" name="goal_duration" <?php echo ($okrdetails['goal_duration'] =='1 year')? 'checked':'' ?> value="1 year">1 year<span class="checkmark" ></span>
									</label> 
								</div>
							</div>
					<?php foreach ($okr_objective as $key => $obj) { ?>
							<div class="performance-wrap">
								
								<div class="performance-box">
									
									<div class="table-responsive">
										
										<table class="table performance-table">
											<thead>
												<tr>
													<th></th>
													<th class="text-center">Status</th>
													<th class="text-center">Progress</th>
													<th class="text-center">Grading</th>
													<th class="text-center" style="width: 85px;">Feedback</th>
												</tr>
											</thead>
											
											<tbody>
												<?php 
												// $objective = json_decode($okrdetails['objective']);
												
												if($obj->okr_status == "Approved")  
												{
													$btn_style = 'btn-success';
												}
												elseif($obj->okr_status == "Pending")
												{
													$btn_style = 'btn-danger';
												}
												
												?>
												<tr>
												<td>
													<div class="label-input">
														<label>Objective <?php echo $key+1?></label>
														<input type="text" class="form-control" name="objective[]" value="<?php echo $obj->objective?>" readonly>
													</div>
													</td>
													<td class="text-center">
														<div class="dropdown">
															 <span class="badge <?=$btn_style?>" name="okr_status[]"><?php echo $obj->okr_status ?></span>
															  
															
														</div>
													</td>
													<td class="text-center">
														
														<button class="btn btn-warning" type="button" 
														 name="progress[]"><?php if($obj->progress_value != '') { ?><?php echo $obj->progress_value?>%<?php } else {?>0%<?php } ?></span>
														</button>
													
														<input type="hidden" class="progress_value" name="progress_value[]" id="progress_value" value="<?php echo $obj->progress_value?>">
													</td>
													<td class="text-center">
														<!-- <strong class="" name="grade"><?php echo $obj->grade_value?></strong>
														<input type="hidden" class="grade_val" name="grade_value[]" value="<?php echo $obj->grade_value?>"> -->
														<select class="form-control select" name="grade_value[]" >
															<?php $ratings = $this->db->get_where('okr_ratings')->row_array() ; ?>
															<?php if(isset($ratings) && !empty($ratings)){ 
															$rating_no = explode('|',$ratings['rating_no']);
															$rating_value = explode('|',$ratings['rating_value']);
															$definition = explode('|',$ratings['definition']);
															$a= 1;
															for ($i=0; $i <count($rating_no) ; $i++) {
																if(!empty($rating_no[$i])){
															  ?>
															<option value="<?php echo $rating_no[$i];?>" <?php echo ($obj->grade_value == $rating_no[$i])?"selected":"";?>><?php echo $rating_no[$i];?></option>
														<?php } } } else { ?>
																<option value="">Ratings Not Found</option>
														<?php } ?>

														</select>
													</td>
													<input type="hidden" id="fb_<?php echo $i; ?>" value="<?php echo $obj->feedback;?>">
													<td class="text-center">													   
														<button type="button" class="btn btn-success obj_feedback" onclick="objective_feedback('<?php echo $i?>')" data-id="<?php echo $obj->feedback;?>"><i class="fa fa-comment"></i></button>

													</td>
												</tr>
											</tbody>
											<?php 
											$key_result = json_decode($obj->key_result);
                                            $key_status = json_decode($obj->key_status);
                                            $keyres_progress = json_decode($obj->keyprog_value);
                                            $key_gradeval = json_decode($obj->key_gradeval);
                                            $key_feedback = json_decode($obj->key_feedback);
											?>
											
											<tbody id="key_result_container">
												<?php
												for ($i=0; $i <count($key_result) ; $i++) { 
													if($key_status[$i] == "Approved")  
												{
													$btn_pending = 'btn-success';
												}
												elseif($key_status[$i] == "Pending")
												{
													$btn_pending = 'btn-danger';
												}
												?>	
												<tr>
													<td>
														<div class="label-input">
														<label>Key Result <?php echo $i+1;?></label>
														<input type="text" class="form-control" name="key_result[]" value="<?php echo $key_result[$i]?>" readonly>
														
														</div>
													</td>
													<td class="text-center">
														<div class="dropdown">
															<span class="badge <?=$btn_pending?>" name="okr_status[]"><?php echo $key_status[$i]?></span>
															
														</div>
													</td>
													<td class="text-center">
														<!-- <button class="btn btn-success" type="button"><?php echo $okrdetails['keyprog_value']?>%</button> -->

														<button class="btn btn-success" type="button" id="keyres_progress" name="keyprog_value[]"><?php if($keyres_progress[$i] != '') { ?><?php echo $keyres_progress[$i]?>%<?php } else {?>0%<?php } ?></button>
														<input type="hidden" class="keyres_value" name="keyres_value[]" value="<?php echo $keyres_progress[$i]?>">
													</td>
													<td class="text-center">
														<!-- <strong class="" name="key_grade"><?php if($obj->key_gradeval != '') { ?><?php echo $obj->key_gradeval?><?php } else {?>0<?php } ?></strong>
														<input type="hidden" class="key_gradeval" name="key_gradeval[]" value="<?php echo $obj->key_gradeval?>"> -->
														<select class="form-control select" name="key_gradeval[]" >
															<?php $ratings = $this->db->get_where('okr_ratings')->row_array() ; ?>
															<?php if(isset($ratings) && !empty($ratings)){ 
															$rating_no = explode('|',$ratings['rating_no']);
															$rating_value = explode('|',$ratings['rating_value']);
															$definition = explode('|',$ratings['definition']);
															$a= 1;
															for ($j=0; $j <count($rating_no) ; $j++) {
																if(!empty($rating_no[$j])){
															  ?>
															<option value="<?php echo $rating_no[$j];?>" <?php echo ($key_gradeval[$i] == $rating_no[$j])?"selected":"";?>><?php echo $rating_no[$j];?></option>
														<?php } } } else { ?>
																<option value="">Ratings Not Found</option>
														<?php } ?>

														</select>
													</td>
													<td class="text-center">
														<input type="hidden" id="fb_<?php echo $i; ?>" value="<?php echo $key_feedback[$i]; ?>">
														 <button type="button" class="btn <?php echo !empty($key_feedback[$i])?'btn-success':'btn-white'?>  key_feedback" onclick="objective_feedback('<?php echo $i?>')" data-id="<?php echo $key_feedback[$i]; ?>"><i class="fa fa-comment"></i></button>
													</td>
												</tr>
												<?php } ?>
											</tbody>
												
										</table>
									
									</div>

								</div>
						
							</div>
							<?php } ?>

						</div>
					</div>
                </div>

           
        </div>
        <?php 

		 // foreach ($okr_objective as $key => $obj) { 

		?>



		<div class="modal fade" id="myModal" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Feedback</h4>
					</div>
					<div class="modal-body">
						<form>
							<div class="form-group">
								<label>Feedback</label>
								
							
								<textarea rows="4" class="form-control objec_feedback" name="obj_feedback[]" id="feedback_obj" readonly></textarea>

								
							
							
							</div>
							
						</form>
					</div>
				</div>
			</div>
		</div> 
		
		
		<!-- Add Feedback Modal -->
		<div id="add_opj_feedback" class="modal center-modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Feedback</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label>Feedback</label>							
						</div>
						
					</div>
				</div>
			</div>
		</div>
		<!-- /Add Feedback Modal -->
		</form>
	

		