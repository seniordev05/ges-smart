<?php 
$cur = App::currencies(config_item('default_currency')); 
$customer = ($client > 0) ? Client::view_by_id($client) : array();
$report_by = (isset($report_by)) ? $report_by : 'all';
?>

<div class="content">
          <section class="panel panel-white">
            
            <div class="panel-heading">

            <?=$this->load->view('report_header');?>

            <?php if($this->uri->segment(3) && isset($customer->co_id)){ ?>
              <a href="<?=base_url()?>reports/expensespdf/<?=$customer->co_id;?>/<?=$report_by?>" class="btn btn-primary pull-right"><i class="fa fa-file-pdf-o"></i><?=lang('pdf')?>
              </a>
            <?php } ?>
             
            </div>

            <div class="panel-body">

<div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <i class="fa fa-info-sign"></i><?=lang('amount_displayed_in_your_cur')?>&nbsp;<span class="label label-success"><?=config_item('default_currency')?></span>
            </div>

<div class="fill body reports-top rep-new-band">
<div class="criteria-container fill-container hidden-print">
  <div class="criteria-band">
    <address class="row">
    <?php echo form_open(base_url().'reports/view/expensesbyclient'); ?>


    <div class="col-md-2">
          <label><?=lang('report_by')?></label>
          <select class="form-control" name="report_by">
          <option value="all" <?=($report_by == 'all') ? 'selected="selected"': ''; ?>><?=lang('all')?></option>
          <option value="billable" <?=($report_by == 'billable') ? 'selected="selected"': ''; ?>><?=lang('billable')?></option>
          <option value="unbillable" <?=($report_by == 'unbillable') ? 'selected="selected"': ''; ?>><?=lang('unbillable')?></option>
          <option value="billed" <?=($report_by == 'billed') ? 'selected="selected"': ''; ?>><?=lang('billed')?></option>
</select>
        </div>
          
<div class="col-md-4">
          <label><?=lang('client_name')?> </label>
          <i class="fa fa-search"></i>&nbsp;
    <span></span> <b class="caret"></b>
          <select class="select2-option form-control" style="width:280px" name="client" >
                                            <optgroup label="<?=lang('clients')?>">
                                                <?php foreach (Client::get_all_clients() as $c): ?>
                                                    <option value="<?=$c->co_id?>" <?=($client == $c->co_id) ? 'selected="selected"' : '';?>>
                                                    <?=ucfirst($c->company_name)?></option>
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
  <h3 class="reports-headerspacing"><?=lang('expenses_report')?></h3>
  <?php if($client != NULL){ ?>
  <h5><span><?=lang('client_name')?>:</span>&nbsp;<?=$customer->company_name?>&nbsp;</h5>
  <?php } ?>
</div>

  <div class="fill-container">
<table class="table zi-table table-hover norow-action table-bordered table-striped"><thead>
  <tr>
<th class="text-left">
  <div class="pull-left over-flow"><?=lang('status')?></div>
</th>
         <th class="text-left">
  <div class="pull-left over-flow"> <?=lang('date')?></div>
  
</th>
         <th class="sortable text-left">
  <div class="pull-left over-flow"> <?=lang('billable')?></div>
</th>
         <th class="sortable text-left">
  <div class="pull-left over-flow"> <?=lang('category')?></div>
<!----></div>
</th>
        
         <th class="sortable text-right">
  <div class=" over-flow"> <?=lang('amount')?></div>
</th>
  </tr>
</thead>

<tbody>

<?php 
$total_expense = 0;
foreach ($expenses as $key => $ex) { ?>
        <tr>
        <td>
        <a href="<?=base_url()?>expenses/view/<?=$ex->id?>" class="text-<?=($ex->invoiced == '1') ? 'success' : 'dark'; ?>">
        <?=($ex->invoiced == 0) ? lang('unbilled') : lang('billed'); ?>
        </a></td>
        <td><?=format_date($ex->expense_date);?></td>
        <td><?=($ex->billable == '1') ? lang('yes') : lang('no'); ?></td>
        <td><?=App::get_category_by_id($ex->category);?></td>
        <td class="text-right">
        <?php if (Client::view_by_id($ex->client)->currency != config_item('default_currency')) {
          $converted = Applib::convert_currency(Client::view_by_id($ex->client)->currency, $ex->amount);
          echo Applib::format_currency($cur->code,$converted);
          $total_expense += $converted;
        }else{
          $total_expense += $ex->amount;
          echo Applib::format_currency($cur->code,$ex->amount);
        }
        ?></td>
      </tr>
<?php } ?>

        <tr class="hover-muted">
          <td colspan="4"><?=lang('total')?></td>
          <td class="text-right"><?=Applib::format_currency($cur->code,$total_expense)?></td>
        </tr>


<!----></tbody>
</table>  </div>
    

</div>


</div>




            </div>
            </section>
            </div>