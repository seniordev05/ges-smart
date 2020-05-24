<?php 
$cur = App::currencies(config_item('default_currency')); 
$task = ($task_id > 0) ? $this->db->get_where('tasks',array('t_id'=>$data['task_id'])) : array();
$project_id = (isset($task_id)) ? $task_id : '';
$task_progress = (isset($task_progress)) ? $task_progress : '';
$task_id = (isset($task_id)) ? $task_id : '';

?>

<div class="content">
  <section class="panel panel-white">
            
    <div class="panel-heading">

            <?=$this->load->view('report_header');?>

            <?php if($this->uri->segment(3) && count($tasks)> 0 ){ ?>
              <a href="<?=base_url()?>reports/taskpdf/<?=$task_id;?>/<?=$task_progress?>" class="btn btn-primary pull-right"><i class="fa fa-file-pdf-o"></i><?=lang('pdf')?>
              </a>              
            <?php } ?>
             
    </div>

    <div class="panel-body">

            <!-- <div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <i class="fa fa-info-sign"></i><?=lang('amount_displayed_in_your_cur')?>&nbsp;<span class="label label-success"><?=config_item('default_currency')?></span>
            </div> -->

      <div class="fill body reports-top rep-new-band">
        <div class="criteria-container fill-container hidden-print">
          <div class="criteria-band">
            <address class="row">
            <?php echo form_open(base_url().'reports/view/taskreport'); ?>

          
              <div class="col-md-4">
                <label><?=lang('task_name')?> </label>
                <select class="select2-option form-control" style="width:280px" name="task_id" >
                    <optgroup label="<?=lang('tasks')?>">
                      <option value="0">All</option>
                        <?php 
                        $all_tasks = $this->db->get_where('tasks')->result();

                        foreach ($all_tasks as $c): ?>
                            <option value="<?=$c->t_id?>" <?=($task_id == $c->t_id) ? 'selected="selected"' : '';?>>
                            <?=ucfirst($c->task_name)?></option>
                        <?php endforeach;  ?>
                    </optgroup>
                </select>
              </div>
              <div class="col-md-2">
                <label><?=lang('status')?></label>
                <select class="form-control" name="task_progress">
                  <option value="" <?=($task_progress == ' ') ? 'selected="selected"': ''; ?>><?=lang('all')?></option>
                  <option value="100" <?=($task_progress == '100') ? 'selected="selected"': ''; ?>><?=lang('completed')?></option>
                  <option value="0" <?=($task_progress == '0') ? 'selected="selected"': ''; ?>><?=lang('pending')?></option>
                </select>
              </div>
                


              <div class="col-md-2">  
                <button class="btn btn-success" type="submit">
                  <?=lang('run_report')?>
                </button>
              </div>



            </address>
          </div>
        </div>


        <?php  form_close(); ?>

        <div class="rep-container">
          <div class="page-header text-center">
            <h3 class="reports-headerspacing"><?=lang('task_report')?></h3>
            <?php if($task->t_id != NULL){ ?>
            <h5><span><?=lang('project_name')?>:</span>&nbsp;<?=$task->task_name?>&nbsp;</h5>
            <?php } ?>
        </div>

        <div class="fill-container">


          <div class="col-md-12">
                  
              <table id="task_report" class="table table-striped custom-table m-b-0">
                <thead>
                  <tr>
                    <th style="width:5px; display:none;"></th>
                    <th class="col-title"><b><?=lang('task_name')?></b></th>  
                    <th><b><?=lang('start_date')?></b></th>
                    <th><b><?=lang('end_date')?></b></th>
                    <th class="col-title "><b><?=lang('status')?></b></th>
                    <th><b><?=lang('assigned_to')?></b></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($tasks as $key => $p) { 
                    if($p['task_progress'] == 100)
                    {
                      $cls = 'completed';
                      $btn_actions='Completed';
                    }else{
                      $cls = 'pending';
                      $btn_actions='Pending';
                    }

                   $assign_member = $this->db->select('*')
                       ->from('assign_tasks PA')
                       ->join('account_details AD','PA.assigned_user = AD.user_id')
                       ->where('PA.task_assigned',$p['t_id'])
                       ->get()->row_array(); 

                       
                          if($assign_member['avatar'] == '' )
                       {
                        $pro_pic_teams = base_url().'assets/avatar/default_avatar.jpg';
                       }else{
                        $pro_pic_teams= base_url().'assets/avatar/'.$assign_member['avatar'];
                       }

                       $assignrds_name=$assign_member['fullname'];
                  // $progress = Project::get_progress($p->project_id); ?>
                  <tr >
                    <td style="display:none;"><?=$p->project?></td>
                    <td>
                     
                      <a class="text-info" data-toggle="tooltip"  href="<?=base_url()?>all_tasks/task_view/<?php echo $p['task_name']?>/<?=$p['t_id']?>">
                        <?=$p['task_name']?>
                      </a>
                    
                    </td>
                    <td><?php if(!empty($p['date_added'])) { echo date('M-d-Y',strtotime($p['date_added'])); } ?></td>
                    <td><?php if(!empty($p['due_date'])) { echo date('M-d-Y',strtotime($p['due_date'])); }?></td>

                    
                    <?php 
                      switch ($p['task_progress']) {
                        case '100': $label = 'success'; break;
                        case '0': $label = 'warning'; break;
                      }
                    ?>
                    <td>
                      <span class="label label-<?=$label?>"><?php echo $btn_actions ?></span>
                    </td>
                    <td><?php echo $assignrds_name;?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>


     