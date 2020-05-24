
                <div class="content container-fluid">
					<div class="row admin-dash">
						<div class="col-md-6 col-sm-6 col-lg-3">
							<div class="dash-widget clearfix card-box">
								<span class="dash-widget-icon"><i class="fa fa-user" aria-hidden="true"></i></span>
								<div class="dash-widget-info">
									<?php $users_count = $this->db->get_where('users',array('role_id '=>3,'status'=>1))->result_array(); 
									$inactive_user = $this->db->get_where('users',array('role_id '=>3,'status'=>0))->result_array();	
									?>
									<span>Employees</span>
									<h3><?php echo count($users_count); ?></h3>
									<div><span class="badge bg-success-light">Active <?php echo count($users_count); ?></span> <span class="badge bg-danger-light">Inactive <?php echo count($inactive_user); ?></span></div>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-lg-3">
							<div class="dash-widget clearfix card-box">
								<span class="dash-widget-icon"><i class="fa fa-lock"></i></span>
								<div class="dash-widget-info">
									<span>Permission</span>
									<h3>Roles</h3>
									<small>Set Roles</small>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-lg-3">
							<div class="dash-widget clearfix card-box">
								<span class="dash-widget-icon"><i class="fa fa-calendar"></i></span>
								<div class="dash-widget-info">
									<span>Management</span>
									<h3>Leave</h3>
									<small><a href="<?php echo base_url()?>leaves">View Application</a></small>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-lg-3">
							<div class="dash-widget clearfix card-box">
								<span class="dash-widget-icon"><i class="fa fa-cog"></i></span>
								<div class="dash-widget-info">
									<span>Theme</span>
									<h3>Settings</h3>
									<small><a href="<?php echo base_url()?>settings">Configuration</a></small>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-6">
							<div class="card-box chart-div">
								<h3 class="card-title">Overview</h3>
									<div class="row">
										<div class="col-md-6 m-b-10">
											<canvas id="gas2"></canvas>
										</div>
										<div class="col-md-6">
											<ul class="list chart-list">
												<li class="list-item"><span class="m-r-10" style="color:#ffc999"><i class="fa fa-circle" aria-hidden="true"></i> </span>Projects</li>
												<li class="list-item"><span class="m-r-10" style="color:#fc6075"><i class="fa fa-circle" aria-hidden="true"></i> </span> Clients</li>
												<li class="list-item"><span class="m-r-10" style="color:#ff9b44"><i class="fa fa-circle" aria-hidden="true"></i> </span> Tasks</li>
												<li class="list-item"><span class="m-r-10" style="color:#fd9ba8"><i class="fa fa-circle" aria-hidden="true"></i> </span> Employees</li>
											</ul>
										</div>
									</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="card-box">
									<h3 class="card-title">Invoices</h3>
									<canvas id="canvas"></canvas>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-6 col-sm-6 col-lg-3">
							<div class="dash-widget clearfix card-box">
								<span class="dash-widget-icon"><i class="fa fa-cubes" aria-hidden="true"></i></span>
								<div class="dash-widget-info m-b-15">
									<?php $projects_count = $this->db->get_where('projects',array('proj_deleted'=>'No'))->result_array(); ?>
									<h3><?php echo count($projects_count); ?></h3>
									<span>Projects</span>
								</div>
								<?php $all_progress = Project::get_all_progress(); ?>
								<div class="progress mb-2" style="height: 5px">
									<div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $all_progress.'%'; ?>" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<p class="m-b-0">Completed <span class="pull-right"><?php echo $all_progress.'%'; ?></span></p>
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-lg-3">
							<div class="dash-widget clearfix card-box">
								<span class="dash-widget-icon"><i class="fa fa-diamond"></i></span>
								<div class="dash-widget-info m-b-15">
									<?php $tasks_count = $this->db->get('tasks')->result_array(); ?>
									<h3><?php echo count($tasks_count); ?></h3>
									<span>Tasks</span>
								</div>
								<div class="progress mb-2" style="height: 5px">
									<div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $all_progress.'%'; ?>" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<p class="m-b-0">Completed <span class="pull-right"><?php echo $all_progress.'%'; ?></span></p>
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-lg-3">
							<div class="dash-widget clearfix card-box">
								<span class="dash-widget-icon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
								<div class="dash-widget-info m-b-15">
									
									<?php 
									$today = date("Y-m-d");
									$today_absents = $this->db->select("*")
									 		 ->from('dgt_user_leaves')
											 ->where("leave_from <=",$today)
							                 ->where("leave_to >=",$today)
							                 ->get()->result_array();
							        $users_count = $this->db->get_where('users',array('role_id '=>3,'status'=>1))->result_array(); 

							        $today_present = count($users_count) - count($today_absents);

						         	$today_present_per = ($today_present / count($users_count))*100;
						         	$present_per = Applib::format_deci($today_present_per);

						         	$today_absents_per = (count($today_absents) / count($users_count))*100;
						         	$absents_per = Applib::format_deci($today_absents_per);
									
									?>
									<h3><?php echo count($today_absents)?></h3>
									<span>Today Absents</span>
								</div>
								<div class="progress mb-2" style="height: 5px">
									<div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $absents_per.'%'; ?>" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<p class="m-b-0">Absent Status <span class="pull-right"><?php echo $absents_per.'%'; ?></span></p>
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-lg-3">
							<div class="dash-widget clearfix card-box">
								<span class="dash-widget-icon"><i class="fa fa-calendar-o" aria-hidden="true"></i></span>
								<div class="dash-widget-info m-b-15">
									<h3><?php echo $today_present ?></h3>
									<span>Today Presents</span>
								</div>
								<div class="progress mb-2" style="height: 5px">
									<div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $present_per.'%'; ?>" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
								<p class="m-b-0">Present Status <span class="pull-right"><?php echo $present_per.'%'; ?></span></p>
							</div>
						</div>
					</div>
					
				<div class="row">
                    <div class="col-md-6 col-sm-6 col-lg-4">
                        <div class="dash-widget ticket-widget">
                            <span class="dash-widget-icon bg-info-light"><i class="fa fa-ticket"></i></span>
                            <div class="dash-widget-info">
                            	<?php 
                            	$total_tickets = $this->db->select("*")
												   ->from('dgt_tickets')
												   ->get()->result_array();
								$open_tickets = $this->db->select("*")
												   ->from('dgt_tickets')
												   ->where('status',open)
												   ->get()->result_array();
								$closed_tickets = $this->db->select("*")
												   ->from('dgt_tickets')
												   ->where('status',Closed)
												   ->get()->result_array();
                            	 
                            	?>
                                <h3><?php echo count($total_tickets)?></h3>
                                <span>Total Tickets</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-4">
                        <div class="dash-widget ticket-widget">
                            <span class="dash-widget-icon bg-danger-light"><i class="fa fa-server"></i></span>
                            <div class="dash-widget-info">
                                <h3><?php echo count($open_tickets)?></h3>
                                <span>Open Tickets</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-4">
                        <div class="dash-widget ticket-widget">
                            <span class="dash-widget-icon bg-success-light"><i class="fa fa-thumbs-up"></i></span>
                            <div class="dash-widget-info">
                                <h3><?php echo count($closed_tickets)?></h3>
                                <span>Closed Tickets</span>
                            </div>
                        </div>
                    </div>
                </div>

					<div class="row">
						<div class="col-md-6">
							<div class="panel panel-table">
								<div class="panel-heading">
									<h3 class="panel-title">Invoices</h3>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<?php 
										$invoice = $this->db->select("*")
												   ->from('dgt_invoices')
												   ->order_by('inv_id',desc)
												   ->limit(7)
												   ->get()->result_array();
												  
										?>
										<table class="table table-striped custom-table m-b-0">
											<thead>
												<tr>
													<th>Invoice ID</th>
													<th>Client</th>
													<th>Due Date</th>
													<th>Total</th>
													<th>Status</th>
												</tr>
											</thead>
											<tbody>
												<?php 
												foreach($invoice as $invoice_details) {
												$status = Invoice::payment_status($invoice_details['inv_id']);
												switch ($status) {
												case 'fully_paid': $label2 = 'label-success-border';  break;
												case 'partially_paid': $label2 = 'label-warning-border'; break;
												case 'not_paid': $label2 = 'label-danger-border'; break;
												case 'cancelled': $label2 = 'label-warning-border'; break;
												}
												$client_details = $this->db->get_where('companies',array('co_id'=>$invoice_details['client']))->row_array();	
												?>
												<tr>
													<td><a href="<?php echo base_url(); ?>invoices/view/<?php echo $invoice_details['inv_id']; ?>"><?php echo $invoice_details['reference_no']?></a></td>
													<td>
														<h2><a href="<?php echo base_url(); ?>companies/view/<?php echo $invoice_details['client']; ?>"><?php echo $client_details['company_name']; ?></a></h2>
													</td>
														<td><?php echo date('d-M-Y',strtotime($invoice_details['due_date'])); ?></td>
													 <td><?=Applib::format_currency($invs->currency, Invoice::get_invoice_subtotal($invoice_details['inv_id']))?></td>

													<td> 
														<span class="label <?php echo $label2; ?>"><?=lang($status)?></span>
													</td>
												</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
								<div class="panel-footer">
									<a href="<?php echo base_url()?>companies" class="text-primary">View all clients</a>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="panel panel-table">
								<div class="panel-heading">
									<h3 class="panel-title">Recent Projects</h3>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table class="table table-striped custom-table m-b-0">
											<thead>
												<tr>
													<th class="col-md-3">Project Name </th>
													<th class="col-md-3">Progress</th>
													<th class="text-right col-md-1">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php 
												$this->db->limit(5);
												$this->db->order_by('project_id',asc);
												$all_projects = $this->db->get('projects')->result_array(); 
												foreach($all_projects as $project){
												?>
												<tr>
													<td>
														<h2><a href="<?php echo base_url(); ?>projects/view/<?php echo $project['project_id']; ?>"><?php echo $project['project_title']; ?></a></h2>
														<small class="block text-ellipsis">
															<?php 
															$completed_task_count = $this->db->get_where('tasks',array('project'=>$project['project_id'],'task_progress'=>'100'))->result_array();
															$open_task_count = $this->db->get_where('tasks',array('project'=>$project['project_id'],'task_progress !='=>'100'))->result_array(); ?>
															<span class="text-xs"><?php echo count($open_task_count); ?></span> <span class="text-muted">open tasks, </span>
															<span class="text-xs"><?php echo count($completed_task_count); ?></span> <span class="text-muted">tasks completed</span>
														</small>
													</td>
													<?php $progress = Project::get_progress($project['project_id']); ?>
													<td>
														<div class="progress progress-xs progress-striped">
															<div class="progress-bar bg-success" role="progressbar" data-toggle="tooltip" title="<?php echo $progress.'%'; ?>" style="width: <?php echo $progress.'%'; ?>"></div>
														</div>
													</td>
													<td class="text-right">
														<div class="dropdown">
															<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
															<ul class="dropdown-menu pull-right">
																<li><a href="<?php echo base_url()?>projects/edit/<?php echo $project['project_id']?>" title="Edit"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
																<li><a href="<?php echo base_url()?>projects/delete/<?php echo $project['project_id']?>" data-toggle="ajaxModal"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
															</ul>
														</div>
													</td>
												</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
								<div class="panel-footer">
									<a href="<?php echo base_url()?>projects" class="text-primary">View all projects</a>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-12">
							<div class="panel m-b-0">
								<div class="panel-heading">
									<h3 class="panel-title">Calender</h3>
								</div>
								<div class="panel-body">
									<div id="calendar"></div>
								</div>
							</div>
						</div>
					</div>
					<!-- <div class="themes">
						<div class="themes-icon"><i class="fa fa-cog"></i></div>
						<div class="themes-body">
							<ul id="theme-change" class="theme-colors">
								<li><a href="../orange/index.html"><span class="theme-orange"></span></a></li>
								<li><a href="../purple/index.html"><span class="theme-purple"></span></a></li> 
								<li><a href="../blue/index.html"><span class="theme-blue"></span></a></li>
								<li><a href="../maroon/index.html"><span class="theme-maroon"></span></a></li>
								<li><a href="../light/index.html"><span class="theme-light"></span></a></li> 
								<li><a href="../dark/index.html"><span class="theme-dark"></span></a></li> 
								<li><a href="../rtl/index.html"><span class="theme-rtl">RTL</span></a></li>
							</ul>
						</div>
					</div> -->
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
		