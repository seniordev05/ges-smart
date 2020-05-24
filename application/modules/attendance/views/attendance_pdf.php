  <?php 
ini_set('memory_limit', '-1');
function stripAccents($string) {
    $chars = array("Ά"=>"Α","ά"=>"α","Έ"=>"Ε","έ"=>"ε","Ή"=>"Η","ή"=>"η","Ί"=>"Ι","ί"=>"ι","Ό"=>"Ο","ό"=>"ο","Ύ"=>"Υ","ύ"=>"υ","Ώ"=>"Ω","ώ"=>"ω");
    foreach ($chars as $find => $replace) {
        $string = str_replace($find, $replace, $string);
    }
    return $string;
}
$m=array("","January","February","March","April","May","June","July","August","September","October","November","December");
$month=$m[$mon]; 

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
        <table align="center">
            <tr>
                <td> <h3>
                  <?php echo $month."   ".$year; ?>
                  </h3>  </td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td width="60%" height="<?=$logo_height?>">
                    <img style="height: <?=$logo_height?>px; width: <?=$logo_width?>px;" src="<?=base_url()?>assets/images/logos/<?= config_item('invoice_logo') ?>" />
                </td>
                <td width="40%" style="text-align: right;">
                    <div style="font-weight: bold; color: #111111; font-size: 20pt; text-transform: uppercase;"><?=stripAccents($lang2['attendance'])?></div>
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
<div style="margin-bottom: 20px; margin-top: 30px;"></div>
<sethtmlpageheader name="myheader" value="off" />
  <table class="items" width="100%"  align="center" style="border-spacing:3px; font-size: 9pt; border-collapse: collapse; " cellpadding="10">
      <thead>
          <tr>
            <td width="8%">Team Members </td>
            <?php   
                $month_days=(int)substr($attend[0]['month_days'],2,2); 
                for($i=1;$i<=$month_days;$i++)
                {
                    echo '<td style="font-weight:bold">'.$i.'</td>';
                }  
            ?>
          </tr>
      </thead>
      <tbody align="center">
        <?php
        foreach ($attend as $at) {
          echo '<tr>';
          $user_name=$this->db->get_where('dgt_account_details',array('user_id'=>$at['user_id']))->result_array()[0]['fullname'];
           if(!$user_name) continue;
            $st=$at['month_days']; 
            echo '<td width="8%" style="color:blue;">'.$user_name.'</td>';
            $no=0; $d=0;
            for($i=1;$i<=$month_days;$i++){
                $str="";

aaa:;           if($st[$no]=='{'){
                   $d++;
                   if($d==$i+1){$no++; goto bbb;}
                }
                $no++;   goto aaa;
bbb:;           if($st[$no+10]=='i'&&$st[$no+12]=='1') $str="O";
// -------------------Calculate day of date---------------
                $mm=$mon; $dd=$i;  if($mon<10) $mm='0'.$mon;   if($i<10) $dd='0'.$i;
                $anyDate = $year.'-'.$mm.'-'.$dd;
                $date = strtotime($anyDate);
                $date = date('l', $date);
                if($date=="Sunday") $str.='X'; 
                echo '<td style="color:red">'.$str."</td>";
            }
            echo '</tr>';
        }
        
        ?>
      </tbody>
  </table>
</body>
</html>
