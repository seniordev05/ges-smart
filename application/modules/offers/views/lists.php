<?php 
// print_r($offer_list);exit();?>
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
                      <th class="text-center">Title</th>
                      <th class="text-center">Job Type</th>
                      <th>Base Salary</th>
                      <th title='Annual Incentive plan'>Annual IP </th>
                      <th title='Long Term Incentive plan'>Long Term IP</th>
                      
                      <th class="text-center">Status</th>
                      <!-- <th class="text-center">Onboarding</th> -->
                    <!--   <th>Applicants</th>
                      <th class="text-right">Actions</th> -->
                    </tr>
                  </thead>
                  <tbody>

                    <?php
                      $jtype=array(0=>'unassigned');
                     foreach ($offer_jobtype as $jkey => $jvalue) {
                        $jtype[$jvalue->id]=$jvalue->job_type;                        
                      } 
                        
                        
                        $this->load->helper('text');
                        foreach ($offer_list as $key => $t) {
                            $offer_approvers_status   = $this->db->get_where('offer_approvers',array('status'=>1,'offer'=>$t['id']))->num_rows();
                            if($offer_approvers_status != 0){
                              $s_label = 'In-progress';
                            }else {
                                $offer_status   = $this->db->get_where('offers',array('id'=>$t['id']))->row_array();
                                if($offer_status['offer_status'] == 2) $s_label = 'Approved';
                                if($offer_status['offer_status'] == 3) $s_label = 'Send offer';
                                if($offer_status['offer_status'] == 4) $s_label = 'Offer accepted';
                                if($offer_status['offer_status'] == 5) $s_label = 'Declined';
                                if($offer_status['offer_status'] == 6) $s_label = 'Onboard';
                            }
                        //   $s_label = 'In-progress';
                        


                    ?>
                    <tr>
                      <td style="display:none;"><?=$t['id']?></td>
                      <td><?=ucfirst($t['candidate']) ?></td>
                      <td><?=ucfirst($t['title']) ?></td>
                      <td><?=ucfirst($jtype[$t['job_type']]) ?></td>
                      <td><?=$t['salary']?></td>
                      <td><?=($t['annual_incentive_plan']=='Selection')?'No':'Yes'?></td>
                      <td><?=ucfirst(($t['long_term_incentive_plan']=='on')?'Yes':'No') ?></td>
                      
                      <td><?=$s_label?></td> 
                     <!--  <td><?php if ($t['app_status'] == 2){ ?>
                        <a href="<?php echo base_url()?>offers/onboarding/<?=$t['caid']?>">Onboarding</a>

                     <?php } else echo '-';?></td> -->
                     <!--  <td><a href="job-applicants.html" class="btn btn-xs btn-primary">3 Candidates</a></td>
                      <td class="text-right">
                        <div class="dropdown">
                          <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                          <ul class="dropdown-menu pull-right">
                            <li><a href="#" title="Edit" data-toggle="modal" data-target="#edit_job"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
                            <li><a href="#" title="Delete" data-toggle="modal" data-target="#delete_job"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
                          </ul>
                        </div>
                      </td> -->
                    </tr>
                      <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
                </div>
        
            