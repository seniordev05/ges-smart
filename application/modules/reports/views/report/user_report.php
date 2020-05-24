<?php 
$cur = App::currencies(config_item('default_currency')); 
$role_id = (isset($role_id)) ? $role_id : '';

?>

<div class="content">
  <section class="panel panel-white">
            
    <div class="panel-heading">

            <?=$this->load->view('report_header');?>

            <?php if($this->uri->segment(3) && count($users)> 0 ){ ?>
              <a href="<?=base_url()?>reports/userpdf/<?=$role_id;?>" class="btn btn-primary pull-right"><i class="fa fa-file-pdf-o"></i><?=lang('pdf')?>
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
          <div class="">
            <address class="row">
            <?php echo form_open(base_url().'reports/view/user_report'); ?>

          
              <div class="col-md-4">
                <label><?=lang('user_role')?> </label>
                <select class="select2-option form-control" style="width:280px" name="role_id" >
                    <optgroup label="<?=lang('tasks')?>">
                      <option value="0">All</option>
                        <?php 
                        $all_roles = $this->db->get_where('roles')->result();

                        foreach ($all_roles as $c): ?>
                            <option value="<?=$c->r_id?>" <?=($role_id == $c->r_id) ? 'selected="selected"' : '';?>>
                            <?=ucfirst($c->role)?></option>
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
            <h3 class="reports-headerspacing"><?=lang('user_report')?></h3>
            <?php if($task->t_id != NULL){ ?>
            <h5><span><?=lang('task_name')?>:</span>&nbsp;<?=$task->task_name?>&nbsp;</h5>
            <?php } ?>
        </div>

        <div class="fill-container">


          <div class="col-md-12">
                  
              <table id="user_report" class="table table-striped custom-table m-b-0">
                <thead>
                  <tr>
                    <th style="width:5px; display:none;"></th>
                    <th><b><?=lang('name')?></b></th>  
                    <th><b><?=lang('company')?></b></th>
                    <th><b><?=lang('email')?></b></th>
                    <th><b><?=lang('role')?></b></th>
                    <th><b><?=lang('designation')?></b></th>
                    <th class="col-title "><b><?=lang('status')?></b></th>
                    
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($users as $key => $p) { 

                   if($p['status'] == 1)
                    {
                      $cls = 'active';
                      $btn_actions='Active';
                    }else{
                      $cls = 'inactive';
                      $btn_actions='Inactive';
                    }
                   
                  $account_details = $this->db->get_where('account_details',array('user_id'=>$p['id']))->row_array(); 
                  $company = $this->db->get_where('companies',array('co_id'=>$account_details['company']))->row_array();

                  $role =  $this->db->get_where('roles',array('r_id'=>$p['role_id']))->row_array(); 
                  $designation =  $this->db->get_where('designation',array('id'=>$p['designation_id']))->row_array(); 
                  
                  ?> 
                  <tr >
                    
                    <td>
                     
                      <a class="text-info" data-toggle="tooltip"  href="<?=base_url()?>employees/profile_view/<?=$p['id']?>">
                        <?=$p['username']?>
                      </a>
                    
                    </td>
                    <td><?php echo $company['company_name']?></td>
                    <td><?php echo $p['email']?></td>
                    <td><?php echo $role['role']?></td>
                    <td><?php echo $designation['designation']?></td>

                    
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


     