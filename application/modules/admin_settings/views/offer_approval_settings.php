
<?php 
    $offer_approvers = $this->db->get('offer_approver_settings')->result_array();
    if(!empty($offer_approvers)){
        $default_offer_approvals = $this->db->get_where('offer_approver_settings',array('default_offer_approval'=>'seq-approver'))->result_array();
        // echo "<pre>";print_r($default_offer_approvals);
        if(!empty($default_offer_approvals)){
            $default_offer_approval = 'seq-approver';
            $seq_approve = 'seq-approver';
            
        }else {
            $default_offer_approval = 'sim-approver';
            $sim_approve = 'sim-approver';
        }
    }else {
        $default_offer_approval = '';
    }
    // echo "<pre>";print_r($default_offer_approval);
 ?>
<div class="p-0">
<div class="col-lg-12 p-0">
        <form action="<?php echo base_url(); ?>admin_settings/offer_approval_settings" id="tokbox_form" class="bs-example form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h3 class="panel-title p-5">Offer Approval Settings</h3>
                </div>
                <div class="panel-body">
                    <!-- <input type="hidden" name="settings" value="offer_approval_setting"> -->
                    <div class="form-group">
                        <label class="control-label col-sm-3">Default Offer Approval</label>
                        <div class="col-md-9 approval-option">
                            <label class="radio-inline">
                                <input id="radio-single" class="default_offer_approval" value="seq-approver" <?php echo ($default_offer_approval =='seq-approver')?"checked":"";?> name="default_offer_approval" type="radio">Sequence Approval (Chain) <sup> <span class="badge info-badge"><i class="fa fa-info" aria-hidden="true"></i></span></sup>
                            </label>
                            <label class="radio-inline">
                                <input id="radio-multiple" class="default_offer_approval" value="sim-approver" <?php echo ($default_offer_approval =='sim-approver')?"checked":"";?> name="default_offer_approval"type="radio">Simultaneous Approval <sup><span class="badge info-badge"><i class="fa fa-info" aria-hidden="true"></i></span></sup>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row approver seq-approver" style="<?php echo ($default_offer_approval =='seq-approver')?"display: block;":"";?>">
                        <label class="control-label col-sm-3">Offer Approvers</label>
                        
                        <div class="col-sm-9 ">
                            
                                    <!-- <select class="select form-control" name="offer_approvers" >
                                        <option>Recruiter</option>
                                        <option>Hiring Manager</option>
                                        <option>Manager</option>
                                        <option>Manager</option>
                                        <option>Manager</option>
                                    </select> -->

                                <?php 
                                if(!empty($offer_approvers)){
                                    $offer_approvers_count = count($offer_approvers);
                                    if($seq_approve == 'seq-approver') { 
                                        $i=1;
                                        foreach ($offer_approvers as $offer_approver) { ?>
                                        <label class="ex_offer_approvers_<?php echo $i;?> control-label m-b-10" style="padding-left:0">Approver <?php echo $i;?></label>
                            <div class="row ex_offer_approvers_<?php echo $i;?>">
                                <div class="col-md-10">
                                         <select class="select2-option form-control offer_approvers "   style="width:260px" name="offer_approvers[]" > 
                                <optgroup>Select Approvers</optgroup> 
                                <optgroup label="Staff">
                                    <option value="">Select Approvers</option>
                                    <?php foreach (User::team() as $user): ?>
                                        <option value="<?=$user->id?>"  <?php echo ($offer_approver['approvers'] ==$user->id)?"selected":"";?>>
                                            <?=ucfirst(User::displayName($user->id))?>
                                        </option>
                                    <?php endforeach ?>
                                </optgroup> 
                            </select>
                            </div>
                            <?php if($i != 1){?>
                            <div class="col-md-2"><a class="remove_ex_approver btn rounded border text-danger" data-id ="<?php echo $i;?>"><i class="fa fa-times" aria-hidden="true"></i></a></div>
                        <?php } ?>
                            </div>
                                   <?php  $i++;
                               }
                                    } else { ?>
                                        <label class="control-label m-b-10" style="padding-left:0">Approver 1</label>
                            <div class="row">
                                <div class="col-md-10">
                                     <select class="select2-option form-control offer_approvers"   style="width:260px" name="offer_approvers[]" id="offer_approvers"> 
                                <optgroup>Select Approvers</optgroup> 
                                <optgroup label="Staff">
                                    <option value="">Select Approvers</option>
                                    <?php foreach (User::team() as $user): ?>
                                        <option value="<?=$user->id?>"  >
                                            <?=ucfirst(User::displayName($user->id))?>
                                        </option>
                                    <?php endforeach ?>
                                </optgroup> 
                            </select>
                            </div>
                            </div>
                                <?php     }
                                } else{ 
                                    $offer_approvers_count = 1;
                                 ?>
                                     <label class="control-label m-b-10" style="padding-left:0">Approver 1</label>
                            <div class="row">
                                <div class="col-md-10">
                                     <select class="select2-option form-control offer_approvers"   style="width:260px" name="offer_approvers[]" id="offer_approvers"> 
                                <optgroup>Select Approvers</optgroup> 
                                <optgroup label="Staff">
                                    <option value="">Select Approvers</option>
                                    <?php foreach (User::team() as $user): ?>
                                        <option value="<?=$user->id?>"  >
                                            <?=ucfirst(User::displayName($user->id))?>
                                        </option>
                                    <?php endforeach ?>
                                </optgroup> 
                            </select>
                            </div>
                            </div>
                              <?php   }
                                ?>
                                
                                <!-- <div class="col-md-2">
                                    <span class="m-t-10 badge btn-success">Approved</span>
                                </div> -->
                            
                            <div id="items">
                            </div>
                        </div>
                        <div class="row">
                            <input type="hidden" id="count" value="<?php echo $offer_approvers_count ;?>">
                            <div class="col-sm-9 col-md-offset-3 m-t-10">
                                <a id="add1" href="javascript:void(0)" class="add-more">+ Add Approver</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row approver sim-approver" style="<?php echo ($sim_approve =='sim-approver')?"display: block;":"";?>">
                        <label class="control-label col-sm-3">Offer Approvers</label>
                        <div class="col-sm-9 ">
                            <?php if(!empty($offer_approvers)){
                                    $offer_approvers_count = count($offer_approvers);
                                    if($sim_approve == 'sim-approver') { 
                                        
                                         ?>
                            <label class="control-label" style="margin-bottom:10px;padding-left:0">Simultaneous Approval </label>
                            <div class="row">
                                <div class="col-md-10">
                                    <select class="select2-option form-control offer_approvers_sim" multiple="multiple" style="width:260px" name="offer_approvers_sim[]" > 
                                        <optgroup label="Staff">
                                            <?php 
                                            foreach ($offer_approvers as $offer_approver) {
                                            foreach (User::team() as $user){ ?>
                                                <option value="<?=$user->id?>" <?php echo ($offer_approver['approvers'] ==$user->id)?"selected":"";?>>
                                                    <?=ucfirst(User::displayName($user->id))?>
                                                </option>
                                           
                                        <?php } } ?>
                                        </optgroup> 
                                    </select>

                                </div>

                                <!-- <div class="col-md-2">
                                    <span class="m-t-10 badge btn-success">Approved</span>
                                </div> -->
                            </div>
                                <?php  
                               
                                    } else{ ?>
                                         <label class="control-label" style="margin-bottom:10px;padding-left:0">Simultaneous Approval </label>
                            <div class="row">
                                <div class="col-md-10">
                                    <select class="select2-option form-control offer_approvers_sim" multiple="multiple" style="width:260px" name="offer_approvers_sim[]" > 
                                        <optgroup label="Staff">
                                            <?php foreach (User::team() as $user): ?>
                                                <option value="<?=$user->id?>">
                                                    <?=ucfirst(User::displayName($user->id))?>
                                                </option>
                                            <?php endforeach ?>
                                        </optgroup> 
                                    </select>

                                </div>
                                <!-- <div class="col-md-2">
                                    <span class="m-t-10 badge btn-success">Approved</span>
                                </div> -->
                            </div>

                                   <?php  }
                                } else{ 
                                   
                                 ?>
                                  <label class="control-label" style="margin-bottom:10px;padding-left:0">Simultaneous Approval </label>
                            <div class="row">
                                <div class="col-md-10">
                                    <select class="select2-option form-control offer_approvers_sim" multiple="multiple" style="width:260px" name="offer_approvers_sim[]" > 
                                        <optgroup label="Staff">
                                            <?php foreach (User::team() as $user): ?>
                                                <option value="<?=$user->id?>">
                                                    <?=ucfirst(User::displayName($user->id))?>
                                                </option>
                                            <?php endforeach ?>
                                        </optgroup> 
                                    </select>

                                </div>
                                <!-- <div class="col-md-2">
                                    <span class="m-t-10 badge btn-success">Approved</span>
                                </div> -->
                            </div>
                             <?php   }
                                ?>
                        </div>
                    </div>
                    <div class="text-center m-t-30">
                        <button id="offer_approval_set_btn" type="submit" class="btn btn-primary btn-lg">Save Changes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    </div>