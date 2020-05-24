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

// $inv = Invoice::view_by_id($id);

// $l = Client::view_by_id($inv->client)->language;
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
                    <img style="height: <?=$logo_height?>px; width: <?=$logo_width?>px;" src="<?=base_url()?>assets/images/logos/<?= config_item('invoice_logo') ?>" />
                </td>
                <td width="40%" style="text-align: right;">
                    <div style="font-weight: bold; color: #111111; font-size: 20pt; text-transform: uppercase;"><?=stripAccents($lang2['timesheets'])?></div>
                </td>
            </tr>
        </table>
    </div>
</htmlpageheader>

<htmlpagefooter name="myfooter">
    <div style="font-size: 9pt; text-align: right; padding-top: 3mm; width:40%; float:right;">
        <?=$lang2['page']?> {PAGENO} <?=$lang2['page_of']?> {nb}
    </div>
</htmlpagefooter>

<sethtmlpageheader name="myheader" value="on" show-this-page="1"  />
<sethtmlpagefooter name="myfooter" value="on" />

<div style="height:<?=$logo_height?>px;">&nbsp;</div>
</div>
<sethtmlpageheader name="myheader" value="off" />
<table class="items" width="100%" style="border-spacing:3px; font-size: 9pt; border-collapse: collapse;" cellpadding="10">
    <thead>
    <tr>
            <td width="5%" style="text-align: left;"><?= stripAccents('S.NO') ?> </td>
            <td width="20%"><?= stripAccents('Empploy Name') ?> </td>
            <td width="20%"><?= stripAccents("Date") ?> </td>
            <td width="20%"><?= stripAccents('Project Name') ?> </td>
            <td width="15%"><?= stripAccents('Work Hours') ?> </td>
            <td width="20%"><?= stripAccents('Work Description') ?> </td>
    </tr>
    </thead>
    <tbody>
    <!-- ITEMS HERE -->
                                        <?php if(count($all_timesheet) != 0){ $a = 1; foreach($all_timesheet as $timesheet){  ?>
                                        <tr>
                                            <td width="5%" style="text-align: center;"><div style="margin-bottom:6px; font-weight:bold; color: #111111;"><?php echo $a ?></div>
                                            </td>      

                                            <td width="20%" style="text-align: center;">
                                                <h2><?php echo $timesheet['fullname']; ?> <span><?php echo $timesheet['designation']?$timesheet['designation']:'-'; ?> </span></h2>
                                            </td>
                                            <?php $tm_date = date("d-M-Y", strtotime($timesheet['timeline_date']));?>
                                            <td width="20%" style="text-align: center;"><?php echo $tm_date; ?></td>
                                            <td width="20%" style="text-align: center;">
                                                <h2><?php echo $timesheet['project_title']; ?></h2>
                                            </td>
                                            <td width="15%" style="text-align: center;"><?php echo $timesheet['hours']; ?></td>
                                            <!-- <td class="text-center">7</td> -->
                                            <td width="20%" style="text-align: center;" class="col-md-4"><?php echo $timesheet['timeline_desc']; ?></td>
                                        </tr>
                                    <?php $a++;} }else{ ?>
                                        <tr>
                                            <td colspan="6" style="text-align: center;">No Result Found</td>
                                        </tr>
                                    <?php } ?>

    </tbody>
</table>
</body>
</html>
