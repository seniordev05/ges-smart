<?php 
$cur = App::currencies(config_item('default_currency')); 
// $task = ($task_id > 0) ? $this->db->get_where('tasks',array('t_id'=>$data['task_id'])) : array();
// $project_id = (isset($task_id)) ? $task_id : '';
// $task_progress = (isset($task_progress)) ? $task_progress : '';
$user_id = (isset($user_id)) ? $user_id : '';

?>

<div class="content">
  <section class="panel panel-white">
            
    <div class="panel-heading">

            <?=$this->load->view('report_header');?>

            <?php if($this->uri->segment(3) && count($employees)> 0 ){ ?>
              <a href="<?=base_url()?>reports/employeepdf/<?=$user_id;?>" class="btn btn-primary pull-right"><i class="fa fa-file-pdf-o"></i><?=lang('pdf')?>
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
            <?php echo form_open(base_url().'reports/view/employee_report'); ?>

          
              <div class="col-md-3">
                <label><?=lang('name')?> </label>
                <select class="select2-option form-control" style="width:280px" name="user_id" >
                    <optgroup label="<?=lang('name')?>">
                      <option value="0">All</option>
                        <?php 

                        $this->db->select('*');
                        $this->db->join('account_details','account_details.user_id = users.id');   
                        $this->db->where('users.role_id', 3);
                        $users = $this->db->get('users')->result(); 

                        foreach ($users as $c): ?>
                            <option value="<?=$c->user_id?>" <?=($user_id == $c->user_id) ? 'selected="selected"' : '';?>>
                            <?=ucfirst($c->fullname)?></option>
                        <?php endforeach;  ?>
                    </optgroup>
                </select>
              </div>

              <div class="col-md-3">
                <label><?=lang('department')?> </label>
                <select class="select2-option form-control" style="width:280px" name="department_id" >
                    <optgroup label="<?=lang('department')?>">
                      <option value="0">All</option>
                        <?php 
                        $department = $this->db->get_where('departments')->result();

                        foreach ($department as $c): ?>
                            <option value="<?=$c->deptid?>" <?=($department_id == $c->deptid) ? 'selected="selected"' : '';?>>
                            <?=ucfirst($c->deptname)?></option>
                        <?php endforeach;  ?>
                    </optgroup>
                </select>
              </div>

              <div class="col-md-3">
                <label><?=lang('designation')?> </label>
                <select class="select2-option form-control" style="width:280px" name="designation_id" >
                    <optgroup label="<?=lang('designation')?>">
                      <option value="0">All</option>
                        <?php 
                        $designation = $this->db->get_where('designation')->result();

                        foreach ($designation as $c): ?>
                            <option value="<?=$c->id?>" <?=($designation_id == $c->id) ? 'selected="selected"' : '';?>>
                            <?=ucfirst($c->designation)?></option>
                        <?php endforeach;  ?>
                    </optgroup>
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
            <h3 class="reports-headerspacing"><?=lang('employee_report')?></h3>
            <!-- <?php if($task->t_id != NULL){ ?>
            <h5><span><?=lang('project_name')?>:</span>&nbsp;<?=$task->task_name?>&nbsp;</h5>
            <?php } ?> -->
        </div>

        <div class="fill-container">


          <div class="col-md-12">
                  
              <table id="task_report" class="table table-striped custom-table m-b-0">
                <thead>
                  <tr>
                    <th style="width:5px; display:none;"></th>
                    <th><b><?=lang('name')?></b></th>  
                    <!-- <th><b><?=lang('company')?></b></th> -->
                    <th><b><?=lang('email')?></b></th>
                    <th><b><?=lang('department')?></b></th>
                    <th><b><?=lang('designation')?></b></th>
                    <th class="col-title "><b><?=lang('status')?></b></th>
                    
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($employees as $key => $p) { 

                     // $company_name = $this->db->get_where('companies',array('co_id'=>$p['company']))->row_array(); 
                     // $users = $this->db->get_where('account_details',array('user_id'=>$p['user_id']))->row_array();
                     // $designation = $this->db->get_where('designation',array('id'=>$p['designation_id']))->row_array();
                     // $department = $this->db->get_where('departments',array('deptid'=>$p['department_id']))->row_array();

                   if($p['status'] == 1)
                    {
                      $cls = 'active';
                      $btn_actions='Active';
                    }else{
                      $cls = 'inactive';
                      $btn_actions='Inactive';
                    }
                   
                  
                                           
                  ?> 
                  <tr >
                    
                    <td>
                     
                      <a class="text-info" data-toggle="tooltip"  href="<?=base_url()?>employees/profile_view/<?=$p['id']?>">
                        <?=$p['fullname']?>
                      </a>
                    
                    </td>
                    <!-- <td><?php echo $company_name['company_name']?></td> -->
                    <td><?php echo $p['email']?></td>
                    <td><?php echo $p['department']?></td>
                    <td><?php echo $p['designation']?></td>

                    
                    <?php 
                      switch ($p['status']) {
                        case '1': $label = 'success'; break;
                        case '0': $label = 'warning'; break;
                      }
                    ?>
                    <td>
                      <span class="label label-<?=$label?>"><?php echo $btn_actions ?></span>
                    </td>
                   
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


     