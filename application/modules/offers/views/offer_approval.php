<?php
$jtype=array();
 foreach ($offer_jobtype as $key => $value) {
   $jtype[$value->id] = $value->job_type;
 }
?>
                <div class="content ">
          <div class="row">
            <div class="col-xs-8">
              <h4 class="page-title">Offers</h4>
            </div>
           <!--  <div class="col-xs-4 text-right m-b-30">
              <a href="#" class="btn btn-primary rounded pull-right" data-toggle="modal" data-target="#add_job"><i class="fa fa-plus"></i> Add New Job</a>
            </div> -->
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table class="table table-striped custom-table m-b-0 datatable">
                  <thead>
                    <tr>
                      <th style="display:none;">#</th>
                      <th>Name</th>
                      <th>Title</th>
                      <th>Job Type </th> 
                      <th>Status</th>
                      <!-- <th>Resume</th> -->
                      <th>Action</th>
                    <!--   <th>Applicants</th>
                      <th class="text-right">Actions</th> -->
                    </tr>
                  </thead>
                  <tbody>

                    <?php
                    
                     foreach ($candi_list as $ck => $cv) { 
                  
                          $s_label = 'Not Approved';$s_label2 = 'Approve'; $class='success'; $color='#b31109';
                        if($cv->approver_status == 2) {$s_label = 'Approved'; $s_label2 = 'Not Approve'; $class='warning';$color='#056928';}

                        /*if($cv->status == 3) $s_label = 'Send offer';
                        if($cv->status == 4) $s_label = 'Offer accepted';
                        if($cv->status == 5) $s_label = 'Declined';
                        if($cv->status == 6) $s_label = 'Onboard';*/


                    ?>
                    <tr>
                      <td style="display:none;"><?=$cv->casid?></td>
                      <td><?=ucfirst($cv->candidate) ?></td>
                      <td><?=$cv->title?></td>
                      <td><?=ucfirst($jtype[$cv->job_type]) ?></td>                      
                      <td style="color: <?=$color?>"><?=ucfirst($s_label)?></td>
                      <!-- <td> <a href="<?= base_url().''.$cv->file_path.'/'.$cv->filename ?>" target='_blank' download>Download</td> -->
                      <td title="Change the status to"><button data-status='<?=$cv->approver_status?>' data-offerid ="<?=$cv->id?>"" data-offid='<?=$cv->app_row_id?>' type="button" class="btn btn-<?=$class?> status_changebuttons"><?=$s_label2?></button></td>
                     
                    </tr>
                      <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
                </div>
        
             