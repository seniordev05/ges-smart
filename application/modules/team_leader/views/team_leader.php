            <div class="content container-fluid">
					<div class="row">
						<div class="col-sm-8">
							<h4 class="page-title"><?=lang('team_leader')?></h4>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-striped custom-table m-b-0 datatable">
									<thead>
										<tr>
											<th>#</th>
											<th>Team Leaders</th>
											<th>Email</th>
											<th>Phone</th>
											<th>Project</th>
											<th>Company</th>
											<th>Status</th>
											<th>Created</th>
										</tr>
									</thead>
									<tbody>
										<?php 

											$all_leads = $this->db->select('*')
											         ->from('users U')
											         ->join('account_details AD','U.id = AD.user_id')

											         ->join('projects P','U.id = P.team_lead')
											         ->join('companies D','P.client = D.co_id')
											         // ->where('U.role_id',3)
											         // ->where('U.is_teamlead','yes')
											         ->where('U.activated',1)
											         ->where('U.banned',0)
											         ->group_by('P.team_lead')
											         ->get()->result_array();

											         $i = 1;
											// echo "<pre>";  print_r($all_leads); exit;
											// $all_leads = $this->db->get_where('users',array('role_id'=>3,'is_teamlead'=>'yes','activated'=>1,'banned'=>0))->result_array(); 
											foreach($all_leads as $leads){
										?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><a href="<?php echo base_url(); ?>employees/profile_view/<?php echo $leads['user_id']; ?>"><?php echo $leads['fullname']; ?></a></td>
											<td><?php echo $leads['email']; ?></td>
											<td><?php echo $leads['phone']; ?></td>
											<td>
												<?php $check_lead_projects = $this->db->get_where('projects',array('team_lead'=>$leads['user_id']))->result_array();
												if(count($check_lead_projects) != 0){
													foreach ($check_lead_projects as $ch_projects) { ?>	
													<a href="<?php echo base_url(); ?>projects/view/<?php echo $ch_projects['project_id']; ?>"><span class="label label-info-border"><?php echo $ch_projects['project_title']; ?></span></a><br>
												<?php } }else{ ?>
													<span>-</span>
												<?php }
												?>
											</td>
											
											<td>
											    <?php
												$check_lead_projects1=$this->db->select('*')
																			 ->from('projects UR')
																			 ->join('dgt_companies ACD','UR.client = ACD.co_id')
																			  ->where('UR.team_lead',$leads['user_id'])
																			 ->get()->result_array();
												// echo "<pre>";
												// print_r($check_lead_projects1); exit;
												foreach ($check_lead_projects1 as  $value) {
													?>
													<a href="<?php echo base_url(); ?>companies/view/<?php echo $value['client']; ?>">
														<span class="label label-info-border"><?php echo $value['company_name']; ?></span>
													</a>
													<br>

													<?php
												}
												?>
											</td>
											<!--<td><?php// echo $leads['company_name']; ?></td>-->
											<!-- <td>
												<ul class="team-members">
													<?php 
													// $team_members = $this->db->get_where('')->result_array(); 
													$team_members = $this->db->select('*')
																			 ->from('users UR')
																			 ->join('account_details ACD','UR.id = ACD.user_id')
																			 ->where('UR.teamlead_id',$leads['user_id'])
																			 ->where('UR.is_teamlead','no')
																			 ->where('UR.activated',1)
																			 ->where('UR.banned',0)
																			 ->get()->result_array();
													foreach ($team_members as $members) {
														if($members['avatar'] == '')
														{
															$pro_pic = base_url().'assets/avatar/default_avatar.png';
														}else{
															$pro_pic = base_url().'assets/avatar/'.$members['avatar'];
														}
													?>
														<li>
															<a href="#" title="<?php echo $members['fullname']; ?>" data-toggle="tooltip"><img src="<?php echo $pro_pic; ?>" alt="<?php echo $members['fullname']; ?>"></a>
														</li>
													<?php } ?>
												</ul>
											</td> -->
											<!--<td><span class="label label-success-border">Working</span></td>-->
											
											<td>
											    <?php
												$check_lead_projects1=$this->db->select('*')
																			 ->from('projects UR')
																			 ->join('dgt_companies ACD','UR.client = ACD.co_id')
																			  ->where('UR.team_lead',$leads['user_id'])
																			 ->get()->result_array();
												// echo "<pre>";
												// print_r($check_lead_projects1); exit;
												foreach ($check_lead_projects1 as  $value) {
													?>
														<span class="label label-success-border"><?php echo $value['status']; ?></span>
													<br>

													<?php
												}
												?>
											</td>
											
												<td>
											    <?php
												$check_lead_projects1=$this->db->select('*')
																			 ->from('projects UR')
																			 ->join('dgt_companies ACD','UR.client = ACD.co_id')
																			  ->where('UR.team_lead',$leads['user_id'])
																			 ->get()->result_array();
												// echo "<pre>";
												// print_r($check_lead_projects1); exit;
												foreach ($check_lead_projects1 as  $value) {
													?>
														<span class="label label-danger-border"><?php echo $value['date_created']; ?></span>
													<br>

													<?php
												}
												?>
											</td>
											<!--<td><?php echo date('d-M-Y',strtotime($leads['created'])); ?></td>-->
										</tr>
									<?php $i++; } ?>
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
        </div>
				
				<div class="notification-box">
					<div class="msg-sidebar notifications msg-noti">
						<div class="topnav-dropdown-header">
							<span>Messages</span>
						</div>
						<div class="drop-scroll msg-list-scroll">
							<ul class="list-box">
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">R</span>
											</div>
											<div class="list-body">
												<span class="message-author">Richard Miles </span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item new-message">
											<div class="list-left">
												<span class="avatar">J</span>
											</div>
											<div class="list-body">
												<span class="message-author">John Doe</span>
												<span class="message-time">1 Aug</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">T</span>
											</div>
											<div class="list-body">
												<span class="message-author"> Tarah Shropshire </span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">M</span>
											</div>
											<div class="list-body">
												<span class="message-author">Mike Litorus</span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">C</span>
											</div>
											<div class="list-body">
												<span class="message-author"> Catherine Manseau </span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">D</span>
											</div>
											<div class="list-body">
												<span class="message-author"> Domenic Houston </span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">B</span>
											</div>
											<div class="list-body">
												<span class="message-author"> Buster Wigton </span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">R</span>
											</div>
											<div class="list-body">
												<span class="message-author"> Rolland Webber </span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">C</span>
											</div>
											<div class="list-body">
												<span class="message-author"> Claire Mapes </span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">M</span>
											</div>
											<div class="list-body">
												<span class="message-author">Melita Faucher</span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">J</span>
											</div>
											<div class="list-body">
												<span class="message-author">Jeffery Lalor</span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">L</span>
											</div>
											<div class="list-body">
												<span class="message-author">Loren Gatlin</span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">T</span>
											</div>
											<div class="list-body">
												<span class="message-author">Tarah Shropshire</span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
							</ul>
						</div>
						<div class="topnav-dropdown-footer">
							<a href="chat.html">See all messages</a>
						</div>
					</div>
				</div>