<?php 

$pdf = new tFPDF();

$pdf->AddPage(); 

$user_det = $this->db->query("select fullname FROM `fx_account_details` where user_id = ".$form_data['user_id']."")->result_array();

$this->db->select('U.id,AD.fullname as username,U.email,U.created,C.company_name, CONCAT(C.company_address,", ",C.city,", ",C.state,", ",C.country) as address');
$this->db->from('users U');
$this->db->join('account_details AD', 'AD.user_id = U.id', 'left');
$this->db->join('companies C', 'C.co_id = AD.company', 'left');
$this->db->where('U.id', $form_data['user_id']);
$employee_info = $this->db->get()->row();

if(!empty($user_det)){

  	$gross_salary = ($form_data['basic']+$form_data['da']+$form_data['hra']+$form_data['conveyance']+$form_data['allowance']

	                 +$form_data['medical_allowance']+$form_data['others']);

	 

	$deductions   = ($form_data['deduction_tds']+$form_data['deduction_esi']+$form_data['deduction_pf']+$form_data['deduction_leave']+

	                 $form_data['deduction_prof']+$form_data['deduction_welfare']+$form_data['deduction_fund']+$form_data['deduction_others']);

	$pdf->Cell(20,20);			 

	$pdf->Image('assets/images/logo2.png',97,4,0,10); 

	$pdf->Ln(5);				 

	 
 	$pdf->SetFont('Arial','B',8);

	$pdf->Write(5,'Company Name : ');

	$pdf->SetFont('Arial','',8);

	$pdf->Write(5,$employee_info->company_name);

	
	$pdf->SetFont('Arial','B',8);

	$pdf->Write(5,'                                     '); 
	$pdf->Write(5,'                                     '); 
	$pdf->Write(5,'                                     '); 
	$pdf->Write(5,'                                     '); 


	$pdf->SetFont('Arial','B',8);

	$pdf->Write(5,'PAYSLIP');

	$pdf->SetFont('Arial','',8);

	$pdf->Write(5,' #'.$form_data['payslipid']);

	$pdf->Ln(5);
	

	$pdf->SetFont('Arial','B',8);

	$pdf->Write(5,'Address : ');

	$pdf->SetFont('Arial','',8);

	$pdf->Write(5,$employee_info->address);

	$pdf->SetFont('Arial','B',8);

	$pdf->Write(5,'                                     '); 
	$pdf->Write(5,'                                     '); 
	

	$pdf->SetFont('Arial','B',8);

	$pdf->Write(5,'Salary Month:');

	$salary_moth = $form_data['year'].'-'.$form_data['month'].'-1';

	$pdf->SetFont('Arial','',8);

	$pdf->Write(5, date('F Y',strtotime($salary_moth)));

	$pdf->Ln(5);

 


	$pdf->SetFont('Arial','B',8);

	$pdf->Write(5,'Employee Name : ');

	$pdf->SetFont('Arial','',8);

	$pdf->Write(5,$user_det[0]['fullname']);

	$pdf->Ln(5);

	

	$pdf->SetFont('Arial','B',8);

	$pdf->Write(5,'Month & Year : ');

	$pdf->SetFont('Arial','',8);

	$pdf->Write(5,date('F',strtotime($form_data['month'])).','.$form_data['year']);

	$pdf->Ln(10);

	

	$pdf->SetFont('Arial','B',8);

	$pdf->Cell(50,5,'Earnings 1',1);

	$pdf->Cell(50,5,'',1);

	$pdf->Cell(50,5,'Deductions',1);

	$pdf->Cell(40,5,'',1);

	

	$pdf->Ln();

	 

	$pdf->SetFont('Arial','',7);

	$pdf->Cell(50,5,'Basic',1);

	$pdf->Cell(50,5,$form_data['basic'],1);

	$pdf->Cell(50,5,'TDS',1);

	$pdf->Cell(40,5,$form_data['deduction_tds'],1);

	

	$pdf->Ln();

	

	$pdf->Cell(50,5,'DA(40%)',1);

	$pdf->Cell(50,5,$form_data['da'],1);

	$pdf->Cell(50,5,'ESI',1);

	$pdf->Cell(40,5,$form_data['deduction_esi'],1);

	

	$pdf->Ln();

	

	$pdf->Cell(50,5,'HRA(15%)',1);

	$pdf->Cell(50,5,$form_data['hra'],1);

	$pdf->Cell(50,5,'PF',1);

	$pdf->Cell(40,5,$form_data['deduction_pf'],1);

	

	$pdf->Ln();

	 

	$pdf->Cell(50,5,'Conveyance ',1);

	$pdf->Cell(50,5,$form_data['conveyance'],1);

	$pdf->Cell(50,5,'Leave',1);

	$pdf->Cell(40,5,$form_data['deduction_leave'],1);

	

	$pdf->Ln();

	

	$pdf->Cell(50,5,'Allowance ',1);

	$pdf->Cell(50,5,$form_data['allowance'],1);

	$pdf->Cell(50,5,'Prof. Tax ',1);

	$pdf->Cell(40,5,$form_data['deduction_prof'],1);

 	

	$pdf->Ln();

	

	$pdf->Cell(50,5,'Medical Allowance  ',1);

	$pdf->Cell(50,5,$form_data['medical_allowance'],1);

	$pdf->Cell(50,5,'Labour Welfare ',1);

	$pdf->Cell(40,5,$form_data['deduction_welfare'],1);

	

	$pdf->Ln();

	

	$pdf->Cell(50,5,'OTHERS',1);

	$pdf->Cell(50,5,$form_data['others'],1);

	$pdf->Cell(50,5,'Fund',1);

	$pdf->Cell(40,5,$form_data['deduction_fund'],1);

	

	$pdf->Ln();



	$pdf->Cell(50,5,'',1);

	$pdf->Cell(50,5,'',1);

	$pdf->Cell(50,5,'OTHERS',1);

	$pdf->Cell(40,5,$form_data['deduction_others'],1);

	

	if($gross_salary == 0) $gross_salary = '';

	if($deductions == 0) $deductions = '';

	

	$pdf->Ln();

	$pdf->SetFont('Arial','B',8);

	$pdf->Cell(50,5,'Total Earnings',1);

	$pdf->Cell(50,5,$gross_salary,1);

	$pdf->Cell(50,5,'Total Deductions',1);

	$pdf->Cell(40,5,$deductions,1);

	

	$pdf->Ln();

	

	$pdf->SetFont('Arial','B',8);

	$pdf->Cell(50,5,'',1);

	$pdf->Cell(50,5,'',1);

	$pdf->Cell(50,5,'NET Salary',1);

	$pdf->Cell(40,5,($gross_salary-$deductions),1);

	

	$pdf->Ln(20);

	

	$pdf->SetFont('Arial','B',8);

	$pdf->Write(5,'Signature of Employee');

	$pdf->SetFont('Arial','',8);

	$pdf->Write(5,' _______________________________'); 

	

	

	$pdf->SetFont('Arial','B',8);

	$pdf->Write(5,' ');

	$pdf->SetFont('Arial','',8);

	$pdf->Write(5,'                                     '); 

	

	$pdf->SetFont('Arial','B',8);

	$pdf->Write(5,'Signature of Director');

	$pdf->SetFont('Arial','',8);

	$pdf->Write(5,' _______________________________');

	$pdf->Ln(10);

}

$dir='assets/uploads/';
$filename= "Employee_Payslip_".$form_data['year']."_".$form_data['month']."_".$form_data['user_id']."_".time().".pdf";
$pdf ->Output($dir.$filename,'F');

$this->session->set_userdata('payslip_pdf', $dir.$filename);
return $dir.$filename; 
  

exit; //don't remove this