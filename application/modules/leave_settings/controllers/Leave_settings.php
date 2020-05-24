<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Leave_settings extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model(array( 'App'));
        App::module_access('menu_leaves');
        $this->load->helper(array('inflector'));
        $this->applib->set_locale();
    }

    function index()
    {
		if($this->tank_auth->is_logged_in()) { 
                $this->load->module('layouts');
                $this->load->library('template');
                $this->template->title('Leaves'); 
 				$data['datepicker'] = TRUE;
				$data['form']       = TRUE; 
                $data['page']       = 'leaves';
                $data['role']       = $this->tank_auth->get_role_id();
                $data['all_employees']       = App::GetAllEmployees();
                $this->template
					 ->set_layout('users')
					 ->build('leave_settings',isset($data) ? $data : NULL);
		}else{
		   redirect('');	
		}
     }



     function leave_types(){
        if ($this->input->post()) {
            
            $tbl_id               = $this->input->post('leave_type_tbl_id');
            $det['leave_type']    = $this->input->post('leave_type');
            $det['leave_days']    = $this->input->post('leave_days'); 
            if($tbl_id == ''){
                $this->db->insert('dgt_leave_types',$det);
                $this->session->set_flashdata('tokbox_success', 'Leave Type Added Successfully');
            }else{ 
                $this->db->update('dgt_leave_types',$det,array('id'=>$tbl_id));
            $this->session->set_flashdata('tokbox_success', 'Leave Type Update Successfully');
            } 
            redirect('leave_settings');
        }
    }
    function delete_leave_types(){
        if ($this->input->post()) {
            $det['status']   = 1;
            $this->db->update('dgt_leave_types',$det,array('id'=>$this->input->post('leave_type_id'))); 
            $this->session->set_flashdata('tokbox_error', 'Leave Type Deleted Successfully');
            redirect('leave_settings');
        }else{
            $data['leave_type_id'] = $this->uri->segment(3);
            $this->load->view('modal/delete_leave_type',$data);
        } 
    }
    
    function update_annual_leaves()
    {
        $annual_leaves = $this->input->post('annual_leaves');
        $res =array(
            'leave_days' => $annual_leaves
            );
        $this->db->where('leave_id',1);
        $this->db->update('common_leave_types',$res);

        $cr_yr = date('Y');
        $check_leaves = $this->db->get_where('yearly_leaves',array('years'=>$cr_yr))->row_array();

        $anu_leaves = $this->db->get_where('common_leave_types',array('leave_id'=>'1','status'=>'0'))->row_array();
        $cr_leaves = $this->db->get_where('common_leave_types',array('leave_id'=>'2','status'=>'0'))->row_array();
        if(count($cr_leaves) != 0){
            $cry_leaves = $cr_leaves['leave_days'];
        }else{
            $cry_leaves = 0;
        }

        $leave = array(
            'annual_leaves' => $anu_leaves['leave_days'],
            'cr_leaves' => $cry_leaves,
        );

        if(count($check_leaves) != 0)
        {
            $total_leaves = array('total_leaves' =>json_encode($leave));
            $this->db->where('years',$cr_yr);
            $this->db->update('yearly_leaves',$total_leaves);
        }else{
            $total_leaves = array('total_leaves' =>json_encode($leave));
            $total_leaves['years'] = $cr_yr;
            $this->db->insert('yearly_leaves',$total_leaves);
        }
        echo $annual_leaves; exit;
    }
    
    function update_sick_leave()
    {
        $sick_leave = $this->input->post('sick_leave');
        $res =array(
            'leave_days' => $sick_leave
            );
        $this->db->where('leave_id',4);
        $this->db->update('common_leave_types',$res);
        echo $sick_leave; exit;
    }
    
    function update_hospitalisation_leave()
    {
        $hospitalisation = $this->input->post('hospitalisation');
        $res =array(
            'leave_days' => $hospitalisation
            );
        $this->db->where('leave_id',5);
        $this->db->update('common_leave_types',$res);
        echo $hospitalisation; exit;
    }
    
    function update_maternity_leave()
    {
        $maternity = $this->input->post('maternity');
        $res =array(
            'leave_days' => $maternity
            );
        $this->db->where('leave_id',6);
        $this->db->update('common_leave_types',$res);
        echo $maternity; exit;
    }
    
    function update_paternity_leave()
    {
        $paternity = $this->input->post('paternity');
        $res =array(
            'leave_days' => $paternity
            );
        $this->db->where('leave_id',7);
        $this->db->update('common_leave_types',$res);
        echo $paternity; exit;
    }
    
    function update_carry_forward_leave()
    {
        $carry_max = $this->input->post('carry_max');
        $leave_status = $this->input->post('leave_status');
        $res =array(
            'leave_days' => $carry_max,
            'leave_status' => $leave_status
            );
        $this->db->where('leave_id',2);
        $this->db->update('common_leave_types',$res);
        $check_leaves = $this->db->get_where('yearly_leaves',array('years'=>$cr_yr))->row_array();
        $anu_leaves = $this->db->get_where('common_leave_types',array('leave_id'=>'1','status'=>'0'))->row_array();
        $cr_leaves = $this->db->get_where('common_leave_types',array('leave_id'=>'2','status'=>'0'))->row_array();
        if(count($cr_leaves) != 0){
            $cry_leaves = $cr_leaves['leave_days'];
        }else{
            $cry_leaves = 0;
        }

        $leave = array(
            'annual_leaves' => $anu_leaves['leave_days'],
            'cr_leaves' => $cry_leaves,
        );

        if(count($check_leaves) != 0)
        {
            $total_leaves = array('total_leaves' =>json_encode($leave));
            $this->db->where('years',$cr_yr);
            $this->db->update('yearly_leaves',$total_leaves);
        }else{
            $total_leaves = array('total_leaves' =>json_encode($leave));
            $total_leaves['years'] = $cr_yr;
            $this->db->insert('yearly_leaves',$total_leaves);
        }
        echo $carry_max; exit;
    }
    
    function update_earned_leave()
    {
        $earned_leaves = $this->input->post('earned_leaves');
        $leave_status = $this->input->post('leave_status');
        $res =array(
            'leave_days' => $earned_leaves,
            'leave_status' => $leave_status
            );
        $this->db->where('leave_id',3);
        $this->db->update('common_leave_types',$res);
        echo $earned_leaves; exit;
    }
    
    function change_status()
    {
        $policy_id = $this->input->post('policy_id');
        $policy_name = $this->db->get_where('common_leave_types',array('leave_id'=>$policy_id))->row_array();
        if($policy_name['status'] == 0)
        {
            $status = "1";
        }else{
            $status = "0";
        }
        $res = array(
            'status' => $status
        );
        $this->db->where('leave_id',$policy_id);
        $this->db->update('common_leave_types',$res);
        echo "success"; exit;
    }

    function add_custom_policy()
    {
        // echo "<pre>"; print_r($_POST); exit;
        $policy_name = $this->input->post('policy_name');
        $policy_days = $this->input->post('policy_days');
        $policy_id = $this->input->post('policy_id');
        $policy = array(
            'custom_policy_name' => $policy_name,
            'policy_leave_days' => $policy_days,
            'leave_id' => $policy_id,
        );
        $this->db->insert('custom_policy',$policy);
        $insert_id = $this->db->insert_id();
        // echo $insert_id; exit;
        $users = $this->input->post('users');
        foreach($users as $user)
        {
            $u = array(
                'policy_id' => $insert_id,
                'user_id' => $user
            );
            $this->db->insert('assigned_policy_user',$u);
        }
        echo "success"; exit;
    }

    function add_new_leave_type()
    {
        $all_inputs = $this->input->post();
        $res = array(
            'leave_type' => $all_inputs['leave_type_name'],
            'leave_days' => $all_inputs['leave_days']
        );
        $this->db->insert('common_leave_types',$res);
        $this->session->set_flashdata('tokbox_success', 'New LeaveType Added Successfully');
        redirect('leave_settings');
    }


    function update_policy_user()
    {
        echo "<pre>"; print_r($_POST); exit;
    }


    function delete_newleave_types()
    {
        $leave_id = $this->input->post('leave_id');
        $this->db->where('leave_id',$leave_id);
        $this->db->delete('common_leave_types');
        echo 'success'; exit;
    }


    function policy_delete($id)
    {

        $this->db->where('assigned_id',$id);
        if($this->db->delete('assigned_policy_user'))
        {
            $this->session->set_flashdata('tokbox_success', 'Policy Deleted Successfully');
            echo "1";
        }
        else
        {
            $this->session->set_flashdata('tokbox_danger', 'Policy Deleted failed');
            echo "0";
        }
        exit;
        
        
    }







}
