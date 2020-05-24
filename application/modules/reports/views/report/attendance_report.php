<?php 
$cur = App::currencies(config_item('default_currency')); 
$task = ($task_id > 0) ? $this->db->get_where('tasks',array('t_id'=>$data['task_id'])) : array();
$project_id = (isset($task_id)) ? $task_id : '';
$task_progress = (isset($task_progress)) ? $task_progress : '';
$task_id = (isset($company_id)) ? $company_id : '';



date_default_timezone_set('Asia/Kolkata');
  $punch_in_date = date('Y-m-d');
  $punch_in_time = date('H:i');
  $punch_in_date_time = date('Y-m-d H:i');


   $strtotime = strtotime($punch_in_date_time);
   $a_year    = date('Y',$strtotime);
   $a_month   = date('m',$strtotime);
   $a_day     = date('d',$strtotime);
   $a_days     = date('d',$strtotime);
   $a_dayss     = date('d',$strtotime);
   $a_cin     = date('H:i',$strtotime);
   $where     = array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
   $this->db->select('month_days,month_days_in_out');
   $record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();

   $punchin_id = 1;
   if(!empty($record['month_days'])){
     
    
      $month_days =  unserialize($record['month_days']);
      $month_days_in_out =  unserialize($record['month_days_in_out']);
     
     $a_day -=1;

     if(!empty($month_days[$a_day])  && !empty($month_days_in_out[$a_day])){  

      $day = $month_days[$a_day];
      $day_in_out = $month_days_in_out[$a_day];


      $latest_inout = end($day_in_out);

    
        if($day['day'] == '' || !empty($latest_inout['punch_out'])){ 
          $punch_in = $day['punch_in'];
          $punch_in_out = $latest_inout['punch_in'];
          $punch_out_in = $latest_inout['punch_out'];
          $punchin_id = 1;
        }else{
           $punch_in = $day['punch_in'];
          $punch_in_out = $latest_inout['punch_in'];
          $punch_out_in = $latest_inout['punch_out'];
          $punchin_id = 0;
        }
     }
         
            
     

     $punchin_time = date("g:i a", strtotime($day['punch_in']));
     $punchout_time = date("g:i a", strtotime($day['punch_out']));
   }

  ?>


  <?php
        $a_dayss -=1;
        $production_hour=0;
        $break_hour=0;

         if(!empty($record['month_days_in_out'])){

         $month_days_in_outss =  unserialize($record['month_days_in_out']);

                              
          foreach ($month_days_in_outss[$a_dayss] as $punch_detailss) 
          {

              if(!empty($punch_detailss['punch_in']) && !empty($punch_detailss['punch_out']))
              {
                
                  $production_hour += time_difference(date('H:i',strtotime($punch_detailss['punch_in'])),date('H:i',strtotime($punch_detailss['punch_out'])));
              }
                        
                                          
               
          }

           for ($i=0; $i <count($month_days_in_outss[$a_dayss]) ; $i++) { 

                      if(!empty($month_days_in_outss[$a_dayss][$i]['punch_out']) && $month_days_in_outss[$a_dayss][ $i+1 ]['punch_in'])
                      {
                          
                          $break_hour += time_difference(date('H:i',strtotime($month_days_in_outss[$a_dayss][$i]['punch_out'])),date('H:i',strtotime($month_days_in_outss[$a_dayss][ $i+1 ]['punch_in'])));
                      }

                      
            }
        }
    
       $s_year = '2015';
              $select_y = date('Y');

              $s_month = date('m');
              $e_year = date('Y');



?>

<div class="content">
  <section class="panel panel-white">
            
    <div class="panel-heading">

            <?=$this->load->view('report_header');?>

            <?php if($this->uri->segment(3) && count($employees)> 0 ){ ?>
              <a href="<?=base_url()?>reports/employeepdf/<?=$company_id;?>" class="btn btn-primary pull-right"><i class="fa fa-file-pdf-o"></i><?=lang('pdf')?>
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
              <form method="post" action="">


               <div class="col-md-3">
                <label>Employee</label>
                <select class="select2-option form-control" name="user_id" id="user_name">
                    <optgroup label="">
                    
                        <?php 
                        $employee = $this->db->get_where('account_details')->result();


                        foreach ($employee as $c): 
                        ?>

                            <option value="<?=$c->user_id?>" <?if($_POST['user_id'] == $c->user_id) { echo 'selected=selected'; }?>>
                            <?php echo $c->fullname?></option>
                        <?php endforeach;  ?>
                    </optgroup>
                </select>
              </div>
          
              <div class="col-md-3">
                <label><?=lang('month')?> </label>
               <select class="select floating form-control" id="attendance_month" name="attendance_month">  
               
                <?php 
                  for ($ji=1; $ji <=12 ; $ji++) {  
                    $sele1='';
                    

                    if(isset($_POST['attendance_month']) && !empty($_POST['attendance_month']))
                    {
                      if($_POST['attendance_month']==$ji)
                      {
                        $sele1='selected';
                      }

                    }

                    

                    ?>
                  <option value="<?php echo $ji; ?>" <?php echo $sele1;?>><?php echo date('F',strtotime($select_y.'-'.$ji)); ?></option>    
                  <?php } ?>
                
              </select>
              </div>

              

              <div class="col-md-3">
                <label><?=lang('year')?> </label>
                 <select class="select floating form-control" id="attendance_year" name="attendance_year"> 
                 
                  <?php for($k =$e_year;$k>=$s_year;$k--){ 
                    $sele2='';
                     if(isset($_POST['attendance_year']) && !empty($_POST['attendance_year']))
                    {
                      if($_POST['attendance_year']==$k)
                      {
                        $sele2='selected';
                      }
                    }

                    ?>
                  <option value="<?php echo $k; ?>" <?php echo $sele2;?> ><?php echo $k; ?></option>
                  <?php } ?>
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


      <!--   <?php  form_close(); ?> -->

        <div class="rep-container">
          <div class="page-header text-center">
            <h3 class="reports-headerspacing"><?=lang('attendance_report')?></h3>
            <?php if($task->t_id != NULL){ ?>
            <h5><span><?=lang('project_name')?>:</span>&nbsp;<?=$task->task_name?>&nbsp;</h5>
            <?php } ?>
        </div>

        <div class="fill-container">


          <div class="col-md-12">
                  
              <table id="task_report" class="table table-striped custom-table m-b-0">
                <thead>
                   <tr>
                      <th>#</th>
                      <th>Date </th>
                      <th>Clock In</th>
                      <th>Clock Out</th>
                      <th>Work status</th>
                      
                    </tr>
                </thead>
                 <tbody>

                    <?php

                     if(isset($_POST['user_id']) && !empty($_POST['user_id']))
                    {
                      $a_user=$_POST['user_id'];
                    }

                    if(isset($_POST['attendance_month']) && !empty($_POST['attendance_month']))
                    {
                      $a_month=$_POST['attendance_month'];
                    }

                     if(isset($_POST['attendance_year']) && !empty($_POST['attendance_year']))
                    {
                      $a_year=$_POST['attendance_year'];
                    }





                     $where     = array('user_id'=>$a_user,'a_month'=>$a_month,'a_year'=>$a_year);
                     $this->db->select('month_days,month_days_in_out');
                     $results  = $this->db->get_where('dgt_attendance_details',$where)->result_array();
                     
                     $sno=1;
                     foreach ($results as $rows) {

                          $list=array();


                          $month = $a_month;
                          $year = $a_year;

                          $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);


                            for($d=1; $d<=$number; $d++)
                           {
                              $time=mktime(12, 0, 0, $month, $d, $year);          
                              if (date('m', $time)==$month)       
                                  $date=date('d M Y', $time);

                                  $a_day =date('d', $time);

                                if(!empty($rows['month_days'])){
     
    
                                $month_days =  unserialize($rows['month_days']);
                                $month_days_in_out =  unserialize($rows['month_days_in_out']);
                                $day = $month_days[$a_day-1];
                                $day_in_out = $month_days_in_out[$a_day-1];
                                $latest_inout = end($day_in_out);

                                 $production_hour=0;
                                 $break_hour=0;

                                foreach ($month_days_in_out[$a_day-1] as $punch_detail) 
                                {

                                    if(!empty($punch_detail['punch_in']) && !empty($punch_detail['punch_out']))
                                    {
                                      
                                        $production_hour += time_difference(date('H:i',strtotime($punch_detail['punch_in'])),date('H:i',strtotime($punch_detail['punch_out'])));
                                    }
                                              
                                                                
                                     
                                }

                             for ($i=0; $i <count($month_days_in_out[$a_day-1]) ; $i++) { 

                                        if(!empty($month_days_in_out[$a_day-1][$i]['punch_out']) && $month_days_in_out[$a_day-1][ $i+1 ]['punch_in'])
                                        {
                                            
                                            $break_hour += time_difference(date('H:i',strtotime($month_days_in_out[$a_day-1][$i]['punch_out'])),date('H:i',strtotime($month_days_in_out[$a_day-1][ $i+1 ]['punch_in'])));
                                        }

                                        
                              }

                              $overtimes=($production_hour+$break_hour)-(9*60);
                              if($overtimes > 0)
                              {
                                $overtime=$overtimes;
                              }
                              else
                              {
                                $overtime=0;
                              }

                                                  

                    ?>


                    <tr>
                      <td><?php echo $sno++;?></td>
                      <td><?php echo $date;?></td>
                      <?php

                      if(date('D', $time)=='Sat' || date('D', $time)=='Sun')
                      {
                        if(!empty($day['punch_in']))
                        {
                        ?>

                          <td><?php echo !empty($day['punch_in'])?date("g:i a", strtotime($day['punch_in'])):'-';?></td>
                          <td><?php echo !empty($latest_inout['punch_out'])?date("g:i a", strtotime($latest_inout['punch_out'])):'-';?></td>
                          <td><?php echo !empty($production_hour)?intdiv($production_hour, 60).'.'. ($production_hour % 60).' hrs':'-';?> </td>
                          <td><?php echo !empty($break_hour)?intdiv($break_hour, 60).'.'. ($break_hour % 60).' hrs':'-';?></td>
                          <td><?php echo !empty($overtime)?intdiv($overtime, 60).'.'. ($overtime % 60).' hrs':'-';?></td>
                       
                       <?php   
                        }
                        else
                        {
                           echo'<td colspan="5" align="center" style="color:red;text-align: center;"> Week Off  </td>';
                        }

                      }
                      else
                      {
                      ?>
                     
                      <td><?php echo !empty($day['punch_in'])?date("g:i a", strtotime($day['punch_in'])):'-';?></td>
                      <td><?php echo !empty($latest_inout['punch_out'])?date("g:i a", strtotime($latest_inout['punch_out'])):'-';?></td>
                      <td><?php echo !empty($production_hour)?intdiv($production_hour, 60).'.'. ($production_hour % 60).' hrs':'-';?> </td>
                      

                      <?php  
                      }
                     ?>
                    </tr>
                    <?php } } } ?>

                  </tbody>

              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>


     