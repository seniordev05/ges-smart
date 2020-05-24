
<!-- Content -->
                <div class="content container-fluid">
					
					<!-- Title -->
					<div class="row">
						<div class="col-xs-9 col-md-8 col-sm-9">
							<h4 class="page-title">Offer Approval Dashboard</h4>
						</div>
						<div class="col-md-4 col-xs-3 col-sm-3">
							<div class="pull-right">
								<a href="<?=base_url()?>offers/create" class="btn btn-primary text-center m-b-20">Create Offer</a>
							</div>
						</div>
					</div>
					<!-- / Title -->
					<div class="row">
						<div class="col-md-6 col-sm-6 col-lg-4">
							<a href="#" class="dash-widget-pro showTable1">
								<div class="dash-widget card-box">
									<div class="dash-widget-info text-center">
										<span class="offer-total dash-color-1"><?=count($inprogress) ?></span><br>
										<span class="text-center dash-text">Offer Approval In Progress</span>
									</div>
								</div>
							</a>
						</div>
						<div class="col-md-6 col-sm-6 col-lg-4">
							<a href="#" class="dash-widget-pro showTable2">
								<div class="dash-widget card-box">
									<div class="dash-widget-info text-center">
										<span class="offer-total dash-color-2"><?=count($ready) ?></span><br>
										<span class="text-center dash-text">Offer Approved & Ready to be Send</span>
									</div>
								</div>
							</a>
						</div>
						<div class="col-md-6 col-sm-6 col-lg-4">
							<a href="#" class="dash-widget-pro showTable3">
								<div class="dash-widget card-box">
									<div class="dash-widget-info text-center">
										<span class="offer-total dash-color-3"><?=count($send) ?></span><br>
										<span class="text-center dash-text">Offers Send</span>
									</div>
								</div>
							</a>
						</div>
						<div class="col-md-6 col-sm-6 col-lg-4">
							<a href="#" class="dash-widget-pro showTable4">
								<div class="dash-widget card-box">
									<div class="dash-widget-info text-center">
										<span class="offer-total dash-color-4"><?=count($accept) ?></span><br>
										<span class="text-center dash-text">Offers Accepted</span>
									</div>
								</div>
							</a>
						</div>
						<div class="col-md-6 col-sm-6 col-lg-4">
							<a href="#" class="dash-widget-pro showTable5">
								<div class="dash-widget card-box">
									<div class="dash-widget-info text-center">
										<span class="offer-total dash-color-5"><?=count($declined) ?></span><br>
										<span class="text-center dash-text">Offers Declined</span>
									</div>
								</div>
							</a>
						</div>
						<div class="col-md-6 col-sm-6 col-lg-4">
							<a href="#" class="dash-widget-pro showTable6">
								<div class="dash-widget card-box">
									<div class="dash-widget-info text-center">
										<span class="offer-total dash-color-6"><?=count($archived) ?></span><br>
										<span class="text-center dash-text">Archived Offers</span>
									</div>
								</div>
							</a>
						</div>
						
					</div>
					<div id="table-1">
					<div class="row">
						<div class="col-md-12">
							<h3 class="page-sub-title m-t-0">Offer Approval In Process (<?=count($inprogress) ?>)</h3>
							<div class="table-responsive">
								<table class="table custom-table datatable table-bordered">
									<thead>
										<tr>
											<th style="width:20%;">Name</th>
											<th>Position</th>
											<th>Action</th>
											<th style="min-width:700px;">Status</th>
										</tr>
									</thead>
									<tbody>

										<?php 
										foreach ($inprogress as $ipk => $ipv) {
											 
										?>

										<tr>
											<td>
												<a href="<?=base_url()?>employees/profile_view/<?php echo $ipv['id']?>" class="avatar"><img class="img-circle" src="assets/img/user.jpg" alt=""></a>
												<h2><a href="<?=base_url()?>employees/profile_view/<?php echo $ipv['id']?>"><?=ucfirst($ipv['name'])?></a></h2>
											<td>
												<span><?=ucfirst($ipv['title'])?></span>
											</td>
											<td  class="text-center">
												<a href="#" class="like-icon m-r-10"><i class="fa fa-file-archive-o" title="Archive" aria-hidden="true" onclick="app_archive('1','<?=$ipv['id']?>')"></i></a>
											</td>
											<td>
												<div class="tabbable">
													<ul class="nav navbar-nav wizard m-b-0">
														<li class="active">
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Created</a>
														</li>
														<li class="active">
															<a href="<?php echo base_url(); ?>offers/offer_view/<?php echo $ipv['id']; ?>" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>In Progress</a>
														</li>
														<li>
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Approved</a>
														</li>
														
														<li>
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Send Offer</a>
														</li>
														<li>
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Accepted </a>
														</li>
														<li>
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Onboard</a>
														</li>
													</ul>
												</div>
											</td>
										</tr>
										<?php } ?> 
										 
									</tbody>
								</table>
							</div>
						</div>
					</div>
					</div>
					<div id="table-2">

					<div class="row">
						<div class="col-md-12">
							<h3 class="page-sub-title m-t-0">Offer Approval & Ready to be Send (<?=count($ready) ?>)</h3>
							<div class="table-responsive">
								<table class="table custom-table datatable table-bordered">
									<thead>
										<tr>
											<th style="width:20%;">Name</th>
											<th>Position</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php 
										foreach ($ready as $rk => $rv) {
											 
										?>
										<tr>
											<td>
												<a href="<?=base_url()?>employees/profile_view/<?php echo $ipv['id']?>" class="avatar"><img class="img-circle" src="assets/img/user.jpg" alt=""></a>
												<h2><a href="<?=base_url()?>employees/profile_view/<?php echo $ipv['id']?>"><?=ucfirst($rv['name'])?></a></h2>
											<td>
												<span><?=ucfirst($rv['title'])?></span>
											</td>
											<td>
												<div class="tabbable">
													<ul class="nav navbar-nav wizard m-b-0">
														<li class="active">
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Created</a>
														</li>
														<li class="active">
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>In Progress</a>
														</li>
														<li class="active">
															<a href="<?php echo base_url(); ?>offers/offer_view/<?php echo $rv['id']; ?>" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Approved</a>
														</li>
														
														<li>
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Send Offer</a>
														</li>
														<li>
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Accepted</a>
														</li>
														<li>
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Onboard</a>
														</li>
													</ul>
												</div>
											</td>
											<td class="text-center">
												<a href="#" class="like-icon m-r-10"><i class="fa fa-file-archive-o" title="Archive" aria-hidden="true" onclick="app_archive('2','<?=$rv['id']?>')"></i></a>
												<a href="#" onclick="send_Appmails('<?=$rv['id']?>')" class="btn btn-info">Send Offer</a>

											</td>
										</tr><?php } ?> 
										 
									</tbody>
								</table>
							</div>
						</div>
					</div>
					</div>
					<div id="table-3"><hidden value='' id='tab_hide' ></hidden>
					<div class="row">
						<div class="col-md-12">
							<h3 class="page-sub-title m-t-0">Offer Send (<?=count($send) ?>)</h3>
							<div class="table-responsive">
								<table class="table custom-table datatable table-bordered">
									<thead>
										<tr>
											<th style="width:20%;">Name</th>
											<th>Position</th>
											<th>Action</th>
											<th>Status</th>
											
										</tr>
									</thead>
									<tbody>
										<?php 
										foreach ($send as $ipk => $ipv) {
											 
										?>
										<tr>
											<td>
												<!-- <a href="profile.html" class="avatar"><img class="img-circle" src="assets/img/user.jpg" alt=""></a> -->
												<h2><a href="<?=base_url()?>employees/profile_view/<?php echo $ipv['id']?>"><?=ucfirst($ipv['name'])?></a></h2>
											<td>
												<span><?=ucfirst($ipv['title'])?></span>
											</td>
											<td class="text-center">
												<!-- <a href="#" class="like-icon text-success m-r-10"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></a> -->
												<a href="#" class="like-icon text-danger" data-toggle="modal" data-target="#offer_decline"  onclick="set_appval('<?=$ipv['id']?>')"><i class="fa fa-thumbs-o-down" title='Decline' aria-hidden="true"></i></a>
												<a href="#" class="like-icon m-r-10"><i class="fa fa-file-archive-o" title="Archive" aria-hidden="true" onclick="app_archive('3','<?=$ipv['id']?>')"></i></a>
												<a href="#" class="like-icon  text-success m-r-10"><i class="fa fa-check" title="Accept" aria-hidden="true" onclick="app_accept('<?=$ipv['id']?>')"></i></a>



											</td>
											<td>
												<div class="tabbable">
													<ul class="nav navbar-nav wizard m-b-0">
														<li class="active">
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Created</a>
														</li>
														<li class="active">
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>In Progress</a>
														</li>
														<li class="active">
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Approved</a>
														</li>
														
														<li class="active">
															<a href="<?php echo base_url(); ?>offers/offer_view/<?=$ipv['id'];?>" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Send Offer</a>
														</li>
														<li>
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Accepted</a>
														</li>
														<li>
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Onboard</a>
														</li>
													</ul>
												</div>
											</td>
											
										</tr>
										<?php } ?> 
									</tbody>
								</table>
							</div>
						</div>
					</div>
					</div>
					<div id="table-4">
					<div class="row">
						<div class="col-md-12">
							<h3 class="page-sub-title m-t-0">Offer Accepted (<?=count($accept) ?>)</h3>
							<div class="table-responsive">
								<table class="table custom-table datatable table-bordered">
									<thead>
										<tr>
											<th style="width:20%;">Name</th>
											<th>Position</th>
											<th>Action</th>
											<th>Status</th>
											
										</tr>
									</thead>
									<tbody>
										<?php 
										foreach ($accept as $ipk => $ipv) {
											 
										?>
										<tr>
											<td>
												<a href="<?=base_url()?>employees/profile_view/<?php echo $ipv['id']?>" class="avatar"><img class="img-circle" src="assets/img/user.jpg" alt=""></a>
												<h2><a href="<?=base_url()?>employees/profile_view/<?php echo $ipv['id']?>"><?=ucfirst($ipv['name'])?></a></h2>
											<td>
												<span><?=ucfirst($ipv['title'])?></span>
											</td>
											<td class="text-center">
												<!-- <a href="#" class="like-icon text-success m-r-10"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></a> -->
												<a href="#" class="like-icon text-danger" data-toggle="modal" data-target="#offer_decline"  onclick="set_appval('<?=$ipv['id']?>')"><i class="fa fa-thumbs-o-down" title='Decline' aria-hidden="true"></i></a>
												<a href="#" class="like-icon m-r-10"><i class="fa fa-file-archive-o" title="Archive" aria-hidden="true" onclick="app_archive('4','<?=$ipv['id']?>')"></i></a>
												<a href="<?php echo base_url()?>offers/onboarding/<?=$ipv['id']?>" title="Onboarding" aria-hidden="true" class="m-r-10">Onboarding</a>
											</td>
											<td>
												<div class="tabbable">
													<ul class="nav navbar-nav wizard m-b-0">
														<li class="active">
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Created</a>
														</li>
														<li class="active">
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>In Progress</a>
														</li>
														<li class="active">
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Approved</a>
														</li>
														
														<li class="active">
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Send Offer</a>
														</li>
														<li class="active">
															<a href="<?php echo base_url(); ?>offers/offer_view/<?=$ipv['id'];?>" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Accepted</a>
														</li>
														<li>
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Onboard</a>
														</li>
													</ul>
												</div>
											</td>
											
										</tr>
										 <?php }?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					</div>
					<div id="table-5">
					<div class="row">
						<div class="col-md-12">
							<h3 class="page-sub-title m-t-0">Offer Declined (<?=count($declined) ?>)</h3>
							<div class="table-responsive">
								<table class="table custom-table datatable table-bordered">
									<thead>
										<tr>
											<th style="width:20%;">Name</th>
											<th>Position</th>
											<th>Action</th>
											<th>Status</th>
											
										</tr>
									</thead>
									<tbody>
										<?php 
										foreach ($declined as $ipk => $ipv) {
											 
										?>
										<tr>
											<td>
												<a href="<?=base_url()?>employees/profile_view/<?php echo $ipv['id']?>" class="avatar"><img class="img-circle" src="assets/img/user.jpg" alt=""></a>
												<h2><a href="<?=base_url()?>employees/profile_view/<?php echo $ipv['id']?>"><?=ucfirst($ipv['name'])?></a></h2>
											<td>
												<span><?=ucfirst($ipv['title'])?></span>
											</td>
											<td class="text-center">
												<!-- <a href="#" class="like-icon text-success m-r-10"><i class="fa fa-thumbs-o-up" onclick="send_Appmails('<?=$ipv['caid']?>')" aria-hidden="true"></i></a> -->
												<!-- <a href="#" class="like-icon text-danger"><i class="fa fa-thumbs-down" aria-hidden="true"></i></a> -->
												<a href="#" class="like-icon m-r-10"><i class="fa fa-file-archive-o" title="Archive" aria-hidden="true" onclick="app_archive('5','<?=$ipv['id']?>')"></i></a>
											</td>
											<td>
												<div class="tabbable">
													<ul class="nav navbar-nav wizard m-b-0">
														<li class="active">
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Created</a>
														</li>
														<li class="active">
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>In Progress</a>
														</li>
														<li class="active">
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Approved</a>
														</li>
														
														<li class="active">
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Send Offer</a>
														</li>
														<li class="offer-declined">
															<a href="<?php echo base_url(); ?>offers/offer_view/<?=$ipv['offer_id'];?>" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Declined</a>
														</li>
														<li>
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Onboard</a>
														</li>
													</ul>
												</div>
											</td>
											
										</tr>
										 <?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					</div>
					<div id="table-6">
					<div class="row">
						<div class="col-md-12">
							<h3 class="page-sub-title m-t-0">Archived Offers (<?=count($archived) ?>)</h3>
							<div class="table-responsive">
								<table class="table custom-table datatable table-bordered">
									<thead>
										<tr>
											<th style="width:20%;">Name</th>
											<th>Position</th>
											<th>Action</th>
											<th>Status</th>
											
										</tr>
									</thead>
									<tbody><?php 
										foreach ($archived as $ipk => $ipv) {
											 
										?>
										<tr>
											<td>
												<a href="<?=base_url()?>employees/profile_view/<?php echo $ipv['id']?>" class="avatar"><img class="img-circle" src="assets/img/user.jpg" alt=""></a>
												<h2><a href="<?=base_url()?>employees/profile_view/<?php echo $ipv['id']?>"><?=ucfirst($ipv['name'])?></a></h2>
											<td>
												<span><?=ucfirst($ipv['title'])?></span>
											</td>
											<td class="text-center">
												<a href="#" class="like-icon text-success m-r-10"><i class="fa fa-thumbs-o-up" title='Accept' aria-hidden="true" onclick="app_retrieve('<?=$ipv['id']?>')"></i></a>
												<!-- <a href="#" class="like-icon text-danger" data-toggle="modal" data-target="#offer_decline"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i></a> -->

											</td>
											<td>
												<div class="tabbable">
													<ul class="nav navbar-nav wizard m-b-0">
														<li class="active">
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Created</a>
														</li>
														<li class="active">
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>In Progress</a>
														</li>
														<li class="active">
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Approved</a>
														</li>
														
														<li class="active">
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Send Offer</a>
														</li>
														<li class="active">
															<a href="<?php echo base_url(); ?>offers/offer_view/<?=$ipv['id'];?>" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Archived</a>
														</li>
														<li>
															<a href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Onboard</a>
														</li>
													</ul>
												</div>
											</td>
											
										</tr><?php } ?>
										 
									</tbody>
								</table>
							</div>
						</div>
					</div>
					</div>
                </div>
				<!-- / Content -->
				<!-- Offer Decline Modal -->
				<div class="modal fade" id="offer_decline" role="dialog">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-body">
								<div class="form-header">
									<h3>Offer Decline</h3>
									<p>Are you sure want to decline?</p>
								</div>
								<div class="modal-btn delete-action">
									<div class="row">
										<div class="col-xs-6">
											<a href="javascript:void(0);" onclick='offer_declinejs()' class="btn btn-primary btn-block continue-btn">Decline</a>
										</div>
										<div class="col-xs-6">
											<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-default btn-block cancel-btn">Cancel</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Offer Decline Modal -->
				
			<!--	<div class="notification-box">
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
           -->