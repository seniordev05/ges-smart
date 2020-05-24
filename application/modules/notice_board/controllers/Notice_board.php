<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Notice_board extends MX_Controller
{
    public function __construct()
    {   
        parent::__construct();
        User::logged_in();

        $this->load->library(array('form_validation'));
        $this->load->model(array('App', 'Lead', 'Notice_board_model'));
        // if (!User::is_admin()) {
        //     $this->session->set_flashdata('message', lang('access_denied'));
        //     redirect('');
        // }
        $this->load->helper(array('inflector'));
        $this->applib->set_locale();
        $this->lead_view = (isset($_GET['list'])) ? $this->session->set_userdata('lead_view', $_GET['list']) : 'kanban';
    }

    public function index()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('notice_board').' - '.config_item('company_name'));
        $data['page'] = lang('notice_board');
        $data['form'] = true;
        $data['datatables'] = true;
        $data['leads_plugin'] = true;
        $data['fuelux'] = true;
        $data['list_view'] = $this->session->userdata('lead_view');
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        $data['notice_boards'] = Notice_board_model::all();          
        $this->template
                ->set_layout('users')
                ->build('notice_board/index', isset($data) ? $data : null);
    }


    function add($notice_board_id='')
    { 
        if ($_POST||$notice_board_id!='') 
        {  
            if(sizeof(array_filter($_POST))<1&&!empty($notice_board_id))
            { 
                $notice_board = Notice_board_model::find($notice_board_id);    
                $data = [];
                $notice_board_array = [];
                foreach ($notice_board as $key=>$val)
                {
                    $notice_board_array[$key]=$val;
                }    
                $data['notice_board'] = $notice_board_array;     
                $this->load->view('add',$data);
            }
            if(sizeof(array_filter($_POST))>0)
            {   
                if(isset($_POST['id'])&&!empty($_POST['id']))
                {
                    $notice_board_id = $_POST['id'];
                }
                // $current_date_time = date('Y-m-d H:i:s'); 
                if(isset($_POST['edit'])&&$_POST['edit']=="true"&&!empty($notice_board_id))
                {
                    $notice_board_exists = Notice_board_model::notice_board_exists($_POST['title'],$_POST['id']); 
                    if(!$notice_board_exists)
                    {
                        $notice_board_id = $_POST['id'];
                        unset($_POST['edit']);
                        unset($_POST['id']);
                        // $_POST['modified_date'] = $current_date_time;
                        App::update('notice_board',array('id'=>$notice_board_id),$this->input->post());     
                        $this->session->set_flashdata('tokbox_success', lang('notice_board_updated_successfully'));
			            redirect('notice_board');
                    }
                    else 
                    {
                        $this->session->set_flashdata('tokbox_error', lang('notice_board_exists'));
			            redirect('notice_board');
                    }
                }
                else 
                {
                    $notice_board_exists = Notice_board_model::notice_board_exists($_POST['title']); 
                    if(!$notice_board_exists)
                    {
                        // $_POST['created_date'] = $current_date_time;
                        App::save_data('notice_board',$this->input->post());     
                    }
                    else 
                    {
                        $this->session->set_flashdata('tokbox_error', lang('notice_board_exists'));
			            redirect('notice_board');
                    }
                }                            
                $this->session->set_flashdata('tokbox_success', lang('notice_board_types_added_successfully'));
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
        else
        {    
            $this->load->view('add');
        }
    }

    public function delete($id='')
    {
        if ($this->input->post()) 
        {
            $id = $this->input->post('id', true);
            Notice_board_model::delete($id);  
            $this->session->set_flashdata('tokbox_success', lang('notice_board_deleted_successfully'));
            redirect('notice_board');
        } 
        else 
        {
            if($id!='')
            {
                $data['id'] = $id;
                $this->load->view('delete',$data);
            }
        }
    }

    
}