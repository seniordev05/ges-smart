<?php
ini_set('memory_limit', '-1');
function stripAccents($string) {
    $chars = array("Ά"=>"Α","ά"=>"α","Έ"=>"Ε","έ"=>"ε","Ή"=>"Η","ή"=>"η","Ί"=>"Ι","ί"=>"ι","Ό"=>"Ο","ό"=>"ο","Ύ"=>"Υ","ύ"=>"υ","Ώ"=>"Ω","ώ"=>"ω");
    foreach ($chars as $find => $replace) {
        $string = str_replace($find, $replace, $string);
    }
    return $string;
}

$ratio = 1.3;
$logo_height = intval(config_item('invoice_logo_height') / $ratio);
$logo_width = intval(config_item('invoice_logo_width') / $ratio);
$color = config_item('invoice_color');

$lang2 = $this->lang->load('fx_lang', $l, TRUE, FALSE, '', TRUE); ?>
<html>
<head>
    <style>
        body {
            font-family: dejavusanscondensed;
            font-size: 10pt;
            line-height: 13pt;
            color: #777777;
        }
        p {
            margin: 4pt 0 0 0;
        }
        td {
            vertical-align: top;
        }
        .items td {
            border: 0.2mm solid #ffffff;
            background-color: #F5F5F5;
        }
        table thead td {
            vertical-align: bottom;
            text-align: center;
            text-transform: uppercase;
            font-size: 7pt;
            font-weight: bold;
            background-color: #FFFFFF;
            color: #111111;
        }
        table thead td {
            border-bottom: 0.2mm solid <?=$color?>;
        }
        table .last td  {
            border-bottom: 0.2mm solid <?=$color?>;
        }
        table .first td  {
            border-top: 0.2mm solid <?=$color?>;
        }
        .watermark {
            text-transform: uppercase;
            font-weight: bold;
            position: absolute;
            left: 100px;
            top: 400px;
        }
    </style>
</head>
<body>

<htmlpageheader name="myheader">
    <div>
        <table width="100%">
            <tr>
                <td width="60%" height="<?=$logo_height?>">
                </td>
                <td width="40%" style="text-align: right;">
                  <div style="font-weight: bold; color: #111111; font-size: 20pt; text-transform: uppercase;"><?=stripAccents($lang2['balance_sheet'])?></div>
                </td>
            </tr>
        </table>
    </div>
</htmlpageheader>

<htmlpagefooter name="myfooter">
    <div style="font-size: 9pt; text-align: left; padding-top: 3mm; width:40%; float:left;">
        <?=nl2br(config_item('invoice_footer'))?>
    </div>
    <div style="font-size: 9pt; text-align: right; padding-top: 3mm; width:40%; float:right;">
        <?=$lang2['page']?> {PAGENO} <?=$lang2['page_of']?> {nb}
    </div>
</htmlpagefooter>

<sethtmlpageheader name="myheader" value="on" show-this-page="1"  />
<sethtmlpagefooter name="myfooter" value="on" />

<div style="height:<?=$logo_height?>px;">&nbsp;</div>

<sethtmlpageheader name="myheader" value="off" />
<table class="items" width="100%" style="border-spacing:3px; font-size: 9pt; border-collapse: collapse;" cellpadding="10">
    <thead>
      <tr>
        <td style="text-align: center;">No</td>
        <td style="text-align: center;">Transaction ID</td>
        <td style="text-align: center;">Invoice Code</td>
        <td style="text-align: center;">PO Code</td>
        <td style="text-align: center;">Category Name</td>
        <td style="text-align: center;">SubCategory Name</td>
        <td style="text-align: center;">Expense amount</td>
        <td style="text-align: center;">Expense date</td>
        <td style="text-align: center;">Selling Price</td>
        <td style="text-align: center;">Budget Revenue</td>
        <td style="text-align: center;">Revenue Date</td>
      </tr>
    </thead>
    <tbody>
      <?php $budget_expenses = $this->db->get('budget_expenses')->result_array(); ?>
      <?php $budget_revenues = $this->db->get('budget_revenues')->result_array(); ?>
      <?php $dgt_payments = $this->db->select('*')->from('dgt_payments U')->join('dgt_invoices P','U.invoice = P.inv_id')->get()->result_array(); ?>
      <!-- ITEMS HERE -->
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
</body>
</html>
