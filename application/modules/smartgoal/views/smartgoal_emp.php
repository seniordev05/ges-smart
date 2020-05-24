<?php

$user_details = $this->session->userdata();

$employee_details = $this->db->get_where('users',array('id'=>$user_details['user_id']))->row_array();
$designation = $this->db->get_where('designation',array('id'=>$employee_details['designation_id']))->row_array();
$account_details = $this->db->get_where('account_details',array('user_id'=>$user_details['user_id']))->row_array();
$team_lead = $this->db->get_where('account_details',array('user_id'=>$employee_details['teamlead_id']))->row_array();
$teamlead = $this->db->get_where('account_details',array('user_id'=>$team_lead['user_id']))->row_array();
?>
          
                <div class="content container-fluid">
                    <div class="row">
                        <div class="col-sm-8">
                            <h4 class="page-title">Smart Goal</h4>
                            
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
                                                <td class="text-right"><?php echo $account_details['fullname']?></td>
                                                
                                            </tr>
                                            <tr>
                                                <td>Position</td>
                                                <td class="text-right"><?php echo $designation['designation']?></td>    
                                                
                                            </tr>
                                            <tr>
                                                <td>Direct Manager</td>
                                                <td class="text-right"><?php echo $teamlead['fullname']?></td>
                                                    
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            
                                
                            </div>
                            <form action="<?php echo base_url()?>smartgoal/add_smartgoal" method="post" id="goal_form">
                                <input type="hidden" name="user_id" value="<?php echo $account_details['user_id']?>">
                                <input type="hidden" name="position" value="<?php echo $designation['designation']?>">  
                                <input type="hidden" name="lead" value="<?php echo $teamlead['user_id']?>">
                                <input type="hidden" name="fullname" value="<?php echo $account_details['fullname']?>">

                                        <div class="form-group">                                    
                                        <div class="join-year">
                                        <span>Year</span>
                                        <select class="select form-control" name="goal_year">
                                            <option value="2019">2019</option>
                                            <option value="2020">2020</option>
                                            <option va;ue="2021">2021</option>
                                            <option value="2022">2022</option>
                                            <option value="2023">2023</option>
                                            <option value="2024">2024</option>
                                        </select>
                                    </div>
                                </div>
                                                
                            <div class="form-group">
                                <label>Goal Duration</label>
                                <div class="radio_input">
                                    <label class="radio-inline custom_radio">
                                        <input type="radio" name="goal_duration" value="90 days" checked>90 Days <span class="checkmark"></span>
                                    </label>
                                    <label class="radio-inline custom_radio">
                                        <input type="radio" name="goal_duration" value="6 month">6 Month <span class="checkmark"></span>
                                    </label>
                                    <label class="radio-inline custom_radio">
                                        <input type="radio" name="goal_duration" value="1 year">1 Year <span class="checkmark"></span>
                                    </label> 
                                </div>
                            </div>
                    
                            <div class="performance-wrap">
                                <div class="performance-box">
                                <div class="table-responsive">
                                  
                                    <table class="table performance-table">
                                        <thead>
                                            <tr>
                                                <th class="goalCount">Goal 1</th>
                                                 <th class="text-center" style="min-width: 140px;">Status</th>
                                                <th class="text-center" style="min-width: 140px;">Start</th>
                                                <th class="text-center" style="min-width: 140px;">Completed</th>
                                                <th class="text-center" style="min-width: 140px;">Rating</th>
                                                <th class="text-center" style="min-width: 85px;">Feedback</th>                                               
                                            </tr>
                                        </thead>
                                        <tbody>
                                          
                                            <tr>
                                                <td style="width: 600px;">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" data-goalid="goal_1" name="goal[]" id="goal" required>
                                                    </div>
                                                    <div class="progress m-b-0">
                                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100">
                                                            <span class="progress_percentage" data-progress="progress_1" value=""></span>

                                                        </div>
                                                         <input type="hidden" class="goal_progress" name="goal_progress[]"value="">
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                     <div class="dropdown">                                                          
                                                        <select class="form-control" name="status[]" disabled="">
                                                            <option value="Pending">Pending</option>
                                                            <option value="Approved">Approved</option>
                                                        </select>                                                       
                                                     </div>
                                                  </td>

                                                <td class="text-center">
                                                    <div class="cal-icon">
                                                        <input type="text" class="form-control datetimepicker" name="created_date[]" id="created_date" required>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="cal-icon">
                                                        <input type="text" class="form-control datetimepicker" name="completed_date[]" id="completed_date" required>
                                                    </div>
                                                </td>
                                                <td>
                                 <?php $ratings = $this->db->get_where('smart_goal_configuration')->row_array() ; ?>
                                
                                 <select class="form-control select" name="rating[]" disabled="">
                                    <option>Select</option>
                                   <?php if(isset($ratings) && !empty($ratings)){ 
                                             $rating_no = explode('|',$ratings['rating_no']);
                                             $rating_value = explode('|',$ratings['rating_value']);
                                             $definition = explode('|',$ratings['definition']);
                                             $a= 1;
                                             for ($i=0; $i <count($rating_no) ; $i++) {
                                                if(!empty($rating_no[$i])){
                                               ?>
                                             <option value="<?php echo $rating_no[$i];?>"><?php echo $rating_value[$i];?></option>
                                          <?php } } } else { ?>
                                                <option value="">Ratings Not Found</option>
                                          <?php } ?>
                                       <!--     <option value="Achieved">Achieved</option>
                                    <option value="Not Achieved">Not Achieved</option>
                                    <option value="Over Achieved">Over Achieved</option> -->
                                 </select>

                                
                              </td>
                              <td class="text-center">
                                 <button type="button" class="btn btn-white obj_feedback" onclick="goal_feedback(<?php echo $key?>)" data-id="<?php echo $key ?>"><i class="fa fa-commenting"></i></button>
                              </td>
                                                
                                            </tr>
                                        </tbody>
                                        <tbody>
                                            <tr>
                                                <td colspan="6">
                                                    <div class="add-another">
                                                        <a href="javascript:void(0);" class="add_goal_action"><i class="fa fa-plus"></i> Actions</a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <!-- Goal Actions -->
                                                    <div class="task-wrapper goal-wrapper">
                                                        <div class="task-list-container">
                                                            <div class="task-list-body">
                                                                <ul class="task-list" id="tasklist">
                                                                    <li class="task">
                                                                        <div class="task-container">
                                                                            <span class="task-action-btn task-check">
                                                                                <span class="action-circle large complete-btn" onclick="progress_smartgoal(this)" title="Mark Complete">
                                                                                    <i class="material-icons">check</i>
                                                                                </span>
                                                                            </span>
                                                                            <input type="text" class="form-control" name="goal_action[0][]" data-action="action_1" value="Goal Action 1">

                                                                            <span class="task-action-btn task-btn-right">
                                                                                <span class="action-circle large delete-btn" title="Delete Goal Action">
                                                                                    <i class="material-icons">delete</i>
                                                                                </span>
                                                                            </span>
                                                                        </div>
                                                                    </li>
                                                                    <li class="task">
                                                                        <div class="task-container">
                                                                            <span class="task-action-btn task-check">
                                                                                <span class="action-circle large complete-btn" onclick="progress_smartgoal(this)" title="Mark Complete">
                                                                                    <i class="material-icons">check</i>
                                                                                </span>
                                                                            </span>
                                                                              <input type="text" class="form-control" name="goal_action[0][]" data-action="action_1" value="Goal Action 2">
                                                                            <span class="task-action-btn task-btn-right">
                                                                                <span class="action-circle large delete-btn" title="Delete Goal Action">
                                                                                    <i class="material-icons">delete</i>
                                                                                </span>
                                                                            </span>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="task-list-footer">
                                                                <div class="new-task-wrapper">
                                                                    <textarea class="add-new-goal" placeholder="Enter new goal action here. . ."></textarea>
                                                                    <span class="error-message hidden">You need to enter a goal action first</span>
                                                                    <span class="add-new-task-btn btn add_goal">Add Goal Action</span>
                                                                    <span class="cancel-btn btn close-goal-panel">Close</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="notification-popup hide">
                                                        <p>
                                                            <span class="task"></span>
                                                            <span class="notification-text"></span>
                                                        </p>
                                                    </div>
                                                    <!-- /Goal Actions -->
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    
                               
                                </div>
                            </div>
							
                                </form>
                                <div class="add-another-goal">
                                <a href="javascript:void(0);" id="add_smart_goal" ><i class="fa fa-plus"></i> Add SMART Goal</a>
                            </div>
                                <div>
                                <input type="submit" value="Submit" class="btn btn-primary submit-btn" style="display:block;margin:auto;margin-top:15px">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
                                    <input type="hidden" name="" id="count" value="2">
                                    <input type="hidden" name="" id="task_count" value="0">
                                     
                                     
        <script type="text/javascript">
            // var ratings = <?php echo $ratings; ?>;
             var ratings_value = new Array();
             var definition_value = new Array();
            // var rating_value = <?php echo $rating_value; ?>;
   //          var definition = '<?php echo $definition; ?>';

        
    <?php foreach($rating_no as $val){ ?>
        ratings_value.push('<?php echo $val; ?>');
    <?php } ?>
    <?php foreach($rating_value as $val){ ?>
        definition_value.push('<?php echo $val; ?>');
    <?php } ?>
        console.log(ratings_value);
        console.log(definition_value);
        </script>
        
 