
<div class="content container-fluid">
  <div class="row">
    <div class="col-xs-10">
      <h4 class="page-title">Balance Sheet</h4>
    </div>
    <div class="col-xs-2">
      <div class="row">
        <div class='col-xs-6 text-right'>
          <a href="<?php echo base_url(); ?>balance_sheet/pdf" class="btn btn-sm btn-primary">
            <i class="fa fa-file-pdf-o"></i> <?=lang('pdf') ?>
          </a>
        </div>
        <div class='col-xs-6'>
          <a href="<?php echo base_url(); ?>balance_sheet/excel" class="btn btn-sm btn-primary">
            <i class="fa fa-file-excel-o"></i> EXCEL
          </a>
        </div>
      </div>
    </div>
  </div>
  <div class="row filter-row">
    <div class="col-md-12 padding-2p search_date">
      <form id="timesheet_search" method="post" action="<?php echo base_url().'budget_revenues/'; ?>">
        <div class="row">
          <div class="col-sm-6 col-md-3 col-xs-6">  
            <div class="form-group form-focus select-focus" style="width:100%;">
              <label class="control-label">Category Name</label>
              <select class="select floating form-control" name="cat_id"  id="cat_id" style="padding: 14px 9px 0px;"> 
                <option value="" selected="selected">All category</option>
                <?php  if(!empty($categories)){ ?>
                <?php foreach($categories as $re) { ?>
                <option value="<?php echo $re['cat_id']; ?>" <?php if($this->session->userdata('cat_id') !=''){ if($this->session->userdata('cat_id') == $re['cat_id']) { echo 'selected="selected"'; } }?>><?php echo $re['category_name']; ?></option>
                <?php   } ?>
                <?php  } ?>
              </select>
              <label id="employee_id_error" class="error display-none" for="employee_id">Please select an option</label>
            </div>
          </div>
          <div class="col-sm-6 col-md-3 col-xs-6">
            <div class="form-group form-focus">
              <label class="control-label">Date From</label>
              <div class="cal-icon">
                <input class="form-control floating" id="expenses_date_from" type="text" data-date-format="dd-mm-yyyy" name="budget_start_date"  value="<?php if($this->session->userdata('budget_start_date') !=''){ echo $this->session->userdata('budget_start_date');  } ?>" size="16">
                <label id="timesheet_date_from_error" class="error display-none" for="expenses_date_from">From Date Shouldn't be empty</label>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-3 col-xs-6">
            <div class="form-group form-focus">
              <label class="control-label">Date To</label>
              <div class="cal-icon">
                <input class="form-control floating" id="expenses_date_to" type="text" data-date-format="dd-mm-yyyy" name="budget_end_date"  value="<?php if($this->session->userdata('budget_end_date') !=''){ echo $this->session->userdata('budget_end_date');  } ?>" size="16">
                <label id="timesheet_date_to_error" class="error display-none" for="expenses_date_to">To Date Shouldn't be empty</label>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-3 col-xs-6">  
            <div class="form-group">
                <button id="budget-revenuet_search_btn" class="btn btn-success form-control" > Search </button>  <!-- 
                <a href="javascript:void(0)" id="budget_revenue_search_btn" class="btn btn-success btn-block form-control" > Search </a>   -->
            </div>
          </div> 
        </div>
      </form>
    </div> 
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="table-responsive">
        <table class="table table-striped custom-table datatable" id="table-budget-revenue">
          <thead>
            <tr>
              <th>No</th>
              <th>Transaction ID</th>     
              <th>Invoice Code</th> 
              <th>PO Code</th>                      
              <th>Category Name</th>
              <th>SubCategory Name</th>
              <th>Expense amount</th>
              <th>Expense date</th>
              <th>Selling Price</th>
              <th>Budget Revenue</th>
              <th>Revenue Date</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; foreach($budget_expenses as $budget){ 
              if($budget['project_id'] != 0){
                $project = $this->db->get_where('projects',array('project_id'=>$budget['project_id']))->row_array();
                $project_name = $project['project_title'];
              }else{
                $project_name = '-';
              }

              if(($budget['category_id'] != 0) || ($budget['s_category_id'] != 0)){
                $category = $this->db->get_where('budget_category',array('cat_id'=>$budget['category_id']))->row_array();
                $subcategory = $this->db->get_where('budget_subcategory',array('sub_id'=>$budget['s_category_id']))->row_array();
                $category_name = $category['category_name'];
                $subcategory_name = $subcategory['sub_category'];
              }else{
                $category_name = '-';
                $subcategory_name = '-';
              }

              ?>
              <tr>
                <td style="text-align: center;"><?php echo $i; ?></td>
                <td style="text-align: center;"><?php echo $budget['transaction_id']; ?></td>
                <td style="text-align: center;">--</td>
                <td style="text-align: center;">PO00<?php echo $budget['po_code']; ?></td>
                <td style="text-align: center;"><?php echo $category_name; ?></td>
                <td style="text-align: center;"><?php echo $subcategory_name; ?></td>
                <td style="text-align: center;"><?=Applib::format_currency($inv->currency, $budget['amount'])?></td>
                <td style="text-align: center;"><?php echo date('d-M-Y',strtotime($budget['expense_date'])); ?></td>
                <td style="text-align: center;"><?=Applib::format_currency($inv->currency, "0")?></td>
                <td style="text-align: center;">-<?=Applib::format_currency($inv->currency, $budget['amount'])?></td>
                <td style="text-align: center;"><?php echo date('d-M-Y',strtotime($budget['expense_date'])); ?></td>
              </tr>
            <?php $i++; } ?>
            <?php foreach($budget_revenues as $budget){ 
              if($budget['project_id'] != 0){
                $project = $this->db->get_where('projects',array('project_id'=>$budget['project_id']))->row_array();
                $project_name = $project['project_title'];
              }else{
                $project_name = '-';
              }

              if(($budget['category_id'] != 0) || ($budget['s_category_id'] != 0)){
                $category = $this->db->get_where('budget_category',array('cat_id'=>$budget['category_id']))->row_array();
                $subcategory = $this->db->get_where('budget_subcategory',array('sub_id'=>$budget['s_category_id']))->row_array();
                $category_name = $category['category_name'];
                $subcategory_name = $subcategory['sub_category'];
              }else{
                $category_name = '-';
                $subcategory_name = '-';
              }

              ?>
              <tr>
                <td style="text-align: center;"><?php echo $i; ?></td>
                <td style="text-align: center;"><?php echo $budget['transaction_id']; ?></td>
                <td style="text-align: center;">--</td>
                <td style="text-align: center;">PO00<?php echo $budget['po_code']; ?></td>
                <td style="text-align: center;"><?php echo $category_name; ?></td>
                <td style="text-align: center;"><?php echo $subcategory_name; ?></td>
                <td style="text-align: center;">--</td>
                <td style="text-align: center;">--</td>
                <td style="text-align: center;"><?=Applib::format_currency($inv->currency, $budget['amount'])?></td>
                <td style="text-align: center;">--</td>
                <td style="text-align: center;"><?php echo date('d-M-Y',strtotime($budget['revenue_date'])); ?></td>
              </tr>
            <?php $i++; } ?>
            <?php
            if(count($dgt_payments)>0){
              foreach ($dgt_payments as $dgt_payment) {
              ?>
              <tr>
                <td style="text-align: center;"><?=$i?></td>
                <td style="text-align: center;"><?php echo $dgt_payment['trans_id'];?></td>
                <td style="text-align: center;">
                  <a class="text-info" href="<?=base_url()?>invoices/view/<?=$dgt_payment['inv_id'];?>">
                    <?php echo $dgt_payment['reference_no']?>
                  </a>
                </td>
                <td style="text-align: center;">--</td>
                <td style="text-align: center;">--</td>
                <td style="text-align: center;">--</td>
                <td style="text-align: center;">
                  <?=Applib::format_currency($inv->currency, $dgt_payment['total_cost_quantity'])?>
                </td>
                <?php 
                  $invoice_date = date("d-M-Y", strtotime($dgt_payment['date_saved']));
                ?>         
                <td style="text-align: center;"><?php echo $invoice_date?></td>
                <td style="text-align: center;">
                  <?=Applib::format_currency($inv->currency, $dgt_payment['total_sale_quantity'])?>
                </td>
                <td style="text-align: center;">
                  <?=Applib::format_currency($inv->currency, $dgt_payment['total_sale_quantity']-$dgt_payment['total_cost_quantity'])?>
                </td>
                <?php 
                  $trans_date = date("d-M-Y", strtotime($dgt_payment['payment_date']));
                ?>
                <td style="text-align: center;"><?php echo $trans_date?></td>
              </tr>
            <?php
            $i++;
                }
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


