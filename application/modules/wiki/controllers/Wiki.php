<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Wiki extends MX_Controller
{
    public function __construct()
    {   
        parent::__construct();
        User::logged_in();

        $this->load->library(array('form_validation'));
        $this->load->model(array('App', 'Lead', 'Wiki_model'));
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
        $this->template->title(lang('wiki').' - '.config_item('company_name'));
        $data['page'] = lang('wiki');
        $data['form'] = true;
        $data['datatables'] = true;
        $data['leads_plugin'] = true;
        $data['fuelux'] = true;
        $data['list_view'] = $this->session->userdata('lead_view');
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        $data['wikis'] = Wiki_model::all();          
        $this->template
                ->set_layout('users')
                ->build('wiki/index', isset($data) ? $data : null);
    }


    function add($wiki_id='')
    { 
        if ($_POST||$wiki_id!='') 
        {  
            if(sizeof(array_filter($_POST))<1&&!empty($wiki_id))
            { 
                $wiki = Wiki_model::find($wiki_id);    
                $data = [];
                $wiki_array = [];
                foreach ($wiki as $key=>$val)
                {
                    $wiki_array[$key]=$val;
                }    
                $data['wiki'] = $wiki_array;     
                $this->load->view('add',$data);
            }
            if(sizeof(array_filter($_POST))>0)
            {   
                if(isset($_POST['id'])&&!empty($_POST['id']))
                {
                    $wiki_id = $_POST['id'];
                }
                // $current_date_time = date('Y-m-d H:i:s'); 
                if(isset($_POST['edit'])&&$_POST['edit']=="true"&&!empty($wiki_id))
                {
                    $wiki_exists = Wiki_model::wiki_exists($_POST['title'],$_POST['id']); 
                    if(!$wiki_exists)
                    {
                        $wiki_id = $_POST['id'];
                        unset($_POST['edit']);
                        unset($_POST['id']);
                        // $_POST['modified_date'] = $current_date_time;
                        App::update('wiki',array('id'=>$wiki_id),$this->input->post());     
                        $this->session->set_flashdata('tokbox_success', lang('wiki_updated_successfully'));
			            redirect('wiki');
                    }
                    else 
                    {
                        $this->session->set_flashdata('tokbox_error', lang('wiki_exists'));
			            redirect('wiki');
                    }
                }
                else 
                {
                    $wiki_exists = Wiki_model::wiki_exists($_POST['title']); 
                    if(!$wiki_exists)
                    {
                        // $_POST['created_date'] = $current_date_time;
                        App::save_data('wiki',$this->input->post());     
                    }
                    else 
                    {
                        $this->session->set_flashdata('tokbox_error', lang('wiki_exists'));
			            redirect('wiki');
                    }
                }                            
                $this->session->set_flashdata('tokbox_success', lang('wiki_types_added_successfully'));
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
            Wiki_model::delete($id);  
            $this->session->set_flashdata('tokbox_success', lang('wiki_deleted_successfully'));
            redirect('wiki');
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