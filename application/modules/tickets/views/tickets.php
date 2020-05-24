<!-- Start -->
<div class="content">
					<div class="row">
						<div class="col-sm-4 col-xs-3">
							<h4 class="page-title"><?=lang('all_tickets')?></h4>
						</div>
						<div class="col-sm-8 col-xs-9 text-right m-b-0">

              <a href="<?=base_url()?>tickets/add" class="btn btn-success rounded pull-right"><?=lang('create_ticket')?></a>

              <?php if(!User::is_client()) { ?>
                  <?php if ($archive) : ?>
                <a href="<?=base_url()?>tickets" class="btn btn-info rounded pull-right m-r-10"><?=lang('view_active')?></a></header>
                <?php else: ?>
              <a href="<?=base_url()?>tickets?view=archive" class="btn btn-info rounded pull-right m-r-10"><?=lang('view_archive')?></a></header>
              <?php endif; ?>
              <?php } ?>
											              <div class="btn-group pull-right m-r-10">

              <button class="btn btn-default">
              <?php
              $view = isset($_GET['view']) ? $_GET['view'] : NULL;
              switch ($view) {
                case 'pending':
                  echo lang('pending');
                  break;
                case 'closed':
                  echo lang('closed');
                  break;
                case 'open':
                  echo lang('open');
                  break;
                case 'resolved':
                  echo lang('resolved');
                  break;

                default:
                  echo lang('filter');
                  break;
              }
              ?></button>
              <button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span>
              </button>
              <ul class="dropdown-menu">

              <li><a href="<?=base_url()?>tickets?view=pending"><?=lang('pending')?></a></li>
              <li><a href="<?=base_url()?>tickets?view=closed"><?=lang('closed')?></a></li>
              <li><a href="<?=base_url()?>tickets?view=open"><?=lang('open')?></a></li>
              <li><a href="<?=base_url()?>tickets?view=resolved"><?=lang('resolved')?></a></li>
              <li><a href="<?=base_url()?>tickets"><?=lang('all_tickets')?></a></li>

              </ul>
              </div>	
					
							
						</div>
					</div>

          <div class="row filter-row">
            <div class="col-sm-3 col-md-2 col-xs-6">  
              <div class="form-group form-focus">
                <label class="control-label">Reporter</label>
                <input type="text" class="form-control floating" id="employee_name" name="employee_name" />
                <label id="employee_name_error" class="error display-none" for="employee_name">Reporter Shouldn't be empty</label>
              </div>
            </div>
            <div class="col-sm-3 col-md-2 col-xs-6"> 
              <div class="form-group form-focus select-focus">
                <label class="control-label">Status</label>
                <select class="select floating form-control" id="ticket_status" name="ticket_status"> 
                  <option value=""> All Tickets</option>
                  <option value="Pending"> Pending </option>
                  <option value="Closed"> Closed </option>
                  <option value="Open"> Open </option>
                  <option value="Resolved"> Resolved </option>
                </select>
                <label id="ticket_status_error" class="error display-none" for="ticket_status">Please Select a status</label>
              </div>
            </div>
            <div class="col-sm-3 col-md-2 col-xs-6"> 
              <div class="form-group form-focus select-focus">
                <label class="control-label">Priority</label>
                <select class="select floating form-control" id="ticked_priority" name="ticked_priority"> 
                  <option value="" selected="selected"> All Priorities </option>
                  <option value="Urgent"> Urgent </option>
                  <option value="High"> High </option>
                  <option value="Medium"> Medium </option>
                  <option value="Low"> Low </option>
                </select>
                <label id="ticked_priority_error" class="error display-none" for="ticked_priority">Please Select a priority</label>
              </div>
            </div>
            <div class="col-sm-3 col-md-2 col-xs-6">  
              <div class="form-group form-focus">
                <label class="control-label">From</label>
                <div class="cal-icon"><input class="form-control floating" id="ticket_from" name="ticket_from" type="text"></div>
                <label id="ticket_from_error" class="error display-none" for="ticket_from">From Date Shouldn't be empty</label>
              </div>
            </div>
            <div class="col-sm-3 col-md-2 col-xs-6">  
              <div class="form-group form-focus">
                <label class="control-label">To</label>
                <div class="cal-icon">
                  <input class="form-control floating" id="ticket_to" name="ticket_to" type="text"></div>
                  <label id="ticket_to_error" class="error display-none" for="ticket_to">To Date Shouldn't be empty</label>
              </div>
            </div>
            <div class="col-sm-3 col-md-2 col-xs-6">  
              <a href="javascript:void(0)" id="ticket_search_btn" class="btn btn-success btn-block"> Search </a>  
            </div>     
         </div>

            <div class="row">
            <div class="col-md-12"> 
              <div class="table-responsive">
                <table id="table-tickets<?=($archive) ? '-archive':''?>" class="table table-striped custom-table m-b-0 AppendDataTables">
                  <thead>
                    <tr>
                    <th style="width:5px; display:none;"></th>
                   <th><?=lang('subject')?></th>
                   <?php if (User::is_admin() || User::is_staff()) { ?>
                   <th><?=lang('reporter')?></th>
                    <?php } ?>
                    <th class="col-date"><?=lang('date')?></th>
                    <th class="col-options no-sort"><?=lang('priority')?></th>

                      <th class="col-lg-1"><?=lang('department')?></th>
                      <th class="col-lg-1"><?=lang('status')?></th>
                      <th style="width:5px; display:none;"></th>
                      <th class="col-lg-1 text-right"><?=lang('action')?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                        $this->load->helper('text');
                        foreach ($tickets as $key => $t) {
                        $s_label = 'default';
                        if($t->status == 'open') $s_label = 'danger';
                        if($t->status == 'closed') $s_label = 'success';
                        if($t->status == 'resolved') $s_label = 'primary';
                    ?>
                    <tr>
                    <td style="display:none;"><?=$t->id?></td>
              <td>
              <?php $rep = $this->db->where('ticketid',$t->id)->get('ticketreplies')->num_rows();
                    if($rep == 0){ ?>
<h2>
                <a class="text-info <?=($t->status == 'closed') ? 'text-lt' : ''; ?>" href="<?=base_url()?>tickets/view/<?=$t->id?>" data-toggle="tooltip" data-title="<?=lang('ticket_not_replied')?>">
                     <?php }else{ ?>
                <h2><a class="text-info <?=($t->status == 'closed') ? 'text-lt' : ''; ?>" href="<?=base_url()?>tickets/view/<?=$t->id?>">
                      <?php } ?>

                     <?=word_limiter($t->subject, 8);?>
                     </a></h2><br>
                     <?php if($rep == 0 && $t->status != 'closed'){ ?>
                     <span class="text-danger">Pending for <?=Applib::time_elapsed_string(strtotime($t->created));?></span>
                     <?php } ?>

                      </td>
                      <?php if (User::is_admin() || User::is_staff()) { ?>

                      <td>
                      <?php
                      if($t->reporter != NULL){ ?>
                        <a class="pull-left avatar" data-toggle="tooltip" title="<?php echo User::login_info($t->reporter)->email; ?>" data-placement="right">
                                <img src="<?php echo User::avatar_url($t->reporter); ?>" class="img-rounded thumb-sm" width='25' height='25'>
                                <?php echo User::displayName($t->reporter); ?>
                          &nbsp;

                            </a>
                      <?php } else { echo "NULL"; } ?>

                      </td>

                      <?php } ?>

                       <td class=""><?=date("D, d M g:i:A",strtotime($t->created));?><br/>
                      <span class="text-primary">(<?=Applib::time_elapsed_string(strtotime($t->created));?>)</span>
                       </td>

                      <td>
                      <span class="label label-<?php if($t->priority == 'Urgent') { echo 'danger'; }elseif($t->priority == 'High') { echo 'warning'; }else{ echo 'default'; } ?>"> <?=$t->priority?></span>
                      </td>

                      <td class="">
                      <?php 
                      $department = App::get_dept_by_id($t->department);
                      if(!empty($department)){echo $department;}else{echo '-';} ?>
                      </td>

                      <td>
                       <?php
                                    switch ($t->status) {
                                        case 'open':
                                            $status_lang = 'open';
                                            break;
                                        case 'closed':
                                            $status_lang = 'closed';
                                            break;
                                        case 'pending':
                                            $status_lang = 'pending';
                                            break;
                                        case 'resolved':
                                            $status_lang = 'resolved';
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                    ?>
                                    <span class="label label-<?=$s_label?>"><?=ucfirst(lang($status_lang))?></span> </td>
                   <td style="display:none;"><?=date("m/d/Y",strtotime($t->created));?></td>                  
									<td class="text-right">
									
                        <div class="dropdown">
							<a data-toggle="dropdown" class="action-icon dropdown-toggle" href="#">
								<i class="fa fa-ellipsis-v"></i>
							</a>
                          <ul class="dropdown-menu pull-right">
                            <li><a href="<?=base_url()?>tickets/view/<?=$t->id?>"><?=lang('preview_ticket')?></a></li>

                            <?php if (User::is_admin()) { ?>

                            <li><a href="<?=base_url()?>tickets/edit/<?=$t->id?>"><?=lang('edit_ticket')?></a></li>
                            <li><a href="<?=base_url()?>tickets/delete/<?=$t->id?>" data-toggle="ajaxModal" title="<?=lang('delete_ticket')?>"><?=lang('delete_ticket')?></a></li>
                                <?php if ($archive) : ?>
                                <li><a href="<?=base_url()?>tickets/archive/<?=$t->id?>/0"><?=lang('move_to_active')?></a></li>
                                <?php else: ?>
                                <li><a href="<?=base_url()?>tickets/archive/<?=$t->id?>/1"><?=lang('archive_ticket')?></a></li>
                                <?php endif; ?>
                            <?php } ?>

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