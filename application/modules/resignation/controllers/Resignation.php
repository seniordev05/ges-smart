<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Resignation extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model(array( 'App'));
        $this->load->model('resignationmodel','resignations');
        /*if (!User::is_admin()) {
            $this->session->set_flashdata('message', lang('access_denied'));
            redirect('');
        }*/
        //App::module_access('menu_leaves');
        $this->load->helper(array('inflector'));
        $this->applib->set_locale();
    }

    function index()
    {
		if($this->tank_auth->is_logged_in()) { 
                $this->load->module('layouts');
                $this->load->library('template');
                $this->template->title(lang('resignation')); 
 				$data['datepicker'] = TRUE;
				$data['form']       = TRUE; 
                $data['page']       = lang('resignation');
                $data['role']       = $this->tank_auth->get_role_id();
                $this->template
					 ->set_layout('users')
					 ->build('resignation',isset($data) ? $data : NULL);
		}else{
		   redirect('');	
		}
     }


      public function resignation_list()
    {
        $list = $this->resignations->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $a=1;
         foreach ($list as $resignation) {

           $no++;
            $row = array();
            $row[] = $a++;
            $row[] = $resignation->fullname;
            $row[] = $resignation->deptname;
            $row[] = $resignation->reason;
            $row[] = date('d M Y',strtotime($resignation->noticedate));
            $row[] = date('d M Y',strtotime($resignation->resignationdate));

            $row[]='<div class="dropdown dropdown-action">
                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="#" onclick="edit_resignation('.$resignation->id.')"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
                                <li><a href="#" onclick="delete_resignations('.$resignation->id.')"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
                            </ul>
                        </div>';


           

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->resignations->count_all(),
            "recordsFiltered" => $this->resignations->count_filtered(),
            "data" => $data,
            );
//output to json format
        echo json_encode($output);
        exit;
    }


    public function resignation_edit($id)
    {
        $data = $this->resignations->get_by_id($id);

        echo json_encode($data);
        exit;
    }

   




    public function add_resignation()
    {
        $employee=$this->input->post('employee');
        $noticedate=date('Y-m-d',strtotime($this->input->post('noticedate')));
        $resignationdate=date('Y-m-d',strtotime($this->input->post('resignationdate')));
        $reason=$this->input->post('reason');
       
       
            $data = array(
                'employee'=>$employee,
                'noticedate'=>$noticedate,
                'resignationdate'=>$resignationdate,
                'reason'=>$reason,
               'posted_date'=>date('Y-m-d H:i:s')
                );
            $this->db->insert('resignation',$data);
            $result=($this->db->affected_rows()!= 1)? false:true;

            if($result==true) 
            {
                $datas['result']='yes';
                $datas['status']='Resignation added successfully';
            }   
            else
            {
                $datas['result']='no';
                $datas['status']='Resignation added failed!';
            }
        
        echo json_encode($datas);

        exit;

    }


    public function update_resignation()
    {

        $id=$this->input->post('id');
        $employee=$this->input->post('employee');
        $noticedate=date('Y-m-d',strtotime($this->input->post('noticedate')));
        $resignationdate=date('Y-m-d',strtotime($this->input->post('resignationdate')));
         $reason=$this->input->post('reason');

        
            $data = array(
                'employee'=>$employee,
                'noticedate'=>$noticedate,
                'resignationdate'=>$resignationdate,
                'reason'=>$reason,
               'posted_date'=>date('Y-m-d H:i:s')
                );
            $this->db->where('id',$id);
            $this->db->update('resignation',$data);
            $result=($this->db->affected_rows()!= 1)? false:true;

            if($result==true) 
            {
                $datas['result']='yes';
                $datas['status']='Resignation update successfully';
            }   
            else
            {
                $datas['result']='no';
                $datas['status']='Resignation update failed!';
            }
        
        echo json_encode($datas);

        exit;

    }


   

    public function resignation_delete($id)
    {
        $data = array(
            'status' =>1,
            );
        $this->resignations->update(array('id' => $id), $data);
        echo json_encode(array("status" => TRUE));
        exit;
    } 
	 




}
