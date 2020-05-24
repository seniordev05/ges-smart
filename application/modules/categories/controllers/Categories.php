<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Categories extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        User::logged_in();

        $this->load->library(array('form_validation'));
        $this->load->model(array('Client', 'App', 'Lead'));
        // if (!User::is_admin()) {
        //     $this->session->set_flashdata('message', lang('access_denied'));
        //     redirect('');
        // }
        $all_routes = $this->session->userdata('all_routes');
        foreach($all_routes as $key => $route){
            if($route == 'categories'){
                $routname = categories;
            } 
        }
        if(empty($routname)){
             $this->session->set_flashdata('message', lang('access_denied'));
            redirect('');
        }
        $this->load->helper(array('inflector'));
        $this->applib->set_locale();
        $this->lead_view = (isset($_GET['list'])) ? $this->session->set_userdata('lead_view', $_GET['list']) : 'kanban';
    }

    public function index()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('categories').' - '.config_item('company_name'));
        $data['page'] = lang('categories');
        $data['form'] = true;
        $data['datatables'] = true;
        $data['leads_plugin'] = true;
        $data['fuelux'] = true;
        $data['list_view'] = $this->session->userdata('lead_view');
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        $data['leads'] = Lead::all();
        $data['countries'] = App::countries();
        $this->template
                ->set_layout('users')
                ->build('all_categoires', isset($data) ? $data : null);
    }


    function categoiress(){
        if ($_POST) {
            $settings = $_POST['settings'];
            unset($_POST['settings']);
            App::save_data('budget_category',$this->input->post());

            // $this->session->set_flashdata('response_status', 'success');
            // $this->session->set_flashdata('message', lang('department_added_successfully'));
            $this->session->set_flashdata('tokbox_success', lang('category_added_successfully'));
            redirect($_SERVER['HTTP_REFERER']);
        }else{
            // $this->index();
            $this->load->view('modal/add_category');
        }
    }

        function sub_categories($cat_id =NULL){
        if ($_POST) {
            $settings = $_POST['settings'];
            unset($_POST['settings']);
            App::save_data('budget_subcategory',$this->input->post());
            $this->session->set_flashdata('tokbox_success', 'Sub-Category Added successfully');
            redirect($_SERVER['HTTP_REFERER']);
        }else{
            // $this->index();
            $data['category_id'] = $cat_id;
            $this->load->view('modal/add_sub_category',$data);
        }
    }

    function edit_sub_category($sub_id)
    {
        if($_POST){
            $this->db->where('sub_id',$sub_id) -> update('budget_subcategory',$this->input->post());
            $this->session->set_flashdata('tokbox_success', 'Sub-Category Update Successfully');
            redirect($_SERVER['HTTP_REFERER']);
        }else{
            $data['sub_details'] = $this->db->get_where('budget_subcategory',array('sub_id'=>$sub_id))->row_array();
            $this->load->view('modal/edit_sub_category',$data);
        }
    }

    function delete_sub_category($sub_id)
    {
        if($_POST){
            $this->db->where('sub_id',$sub_id) -> delete('budget_subcategory');
            $this->session->set_flashdata('tokbox_success', 'Designation Deleted Successfully');
            redirect($_SERVER['HTTP_REFERER']);
        }else{
            $data['des_details'] = $this->db->get_where('budget_subcategory',array('sub_id'=>$sub_id))->row_array();
            $this->load->view('modal/delete_sub_category',$data);
        }
    }


    function edit_categories($cat_id = NULL){
        if ($_POST) {
            if(isset($_POST['delete_category']) AND $_POST['delete_category'] == 'on'){
                $this->db->where('cat_id',$_POST['cat_id']) -> delete('budget_category');
                $this->session->set_flashdata('tokbox_error', lang('category_deleted'));
                redirect($_SERVER['HTTP_REFERER']);
            }else{
                $this->db->where('cat_id',$_POST['cat_id']) -> update('budget_category',$this->input->post());
                $this->session->set_flashdata('tokbox_success', lang('category_updated'));
                redirect($_SERVER['HTTP_REFERER']);
            }
        }else{
            $data['cat_id'] = $cat_id;
            $data['category_info'] = $this->db ->where(array('cat_id'=>$cat_id)) -> get('budget_category') -> result();
            $this->load->view('modal_edit_category',isset($data) ? $data : NULL);
        }
    }

    function view_sub_categories($cat_id){
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('categories').' - '.config_item('company_name'));
        $data['page'] = lang('categories');
        $data['form'] = true;
        $data['datatables'] = true;
        $data['all_subcategories'] = $this->db->get_where('budget_subcategory',array('cat_id'=>$cat_id))->result();
        $data['category_id'] = $cat_id;
        $this->template
                ->set_layout('users')
                ->build('all_sub_categories', isset($data) ? $data : null);
    }

    
}
/* End of file all_departments.php */
