<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Termination extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model(array('App'));
        $this->load->model('terminationmodel','terminations');
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
                $this->template->title(lang('termination')); 
 				$data['datepicker'] = TRUE;
				$data['form']       = TRUE; 
                $data['page']       = lang('termination');
                $data['role']       = $this->tank_auth->get_role_id();
                $this->template
					 ->set_layout('users')
					 ->build('termination',isset($data) ? $data : NULL);
		}else{
		   redirect('');	
		}
     }


     public function add_termination_type()
    {
        $termination_type=$this->input->post('termination_type');
               
            $data = array(
                'termination_type'=>$termination_type,
                 );
            $this->db->insert('termination_type',$data);
            $result=($this->db->affected_rows()!= 1)? false:true;

            if($result==true) 
            {
                $datas['result']='yes';
                $datas['status']='Termination type added successfully';
            }   
            else
            {
                $datas['result']='no';
                $datas['status']='Termination type added failed!';
            }
        
        echo json_encode($datas);

        exit;

    } 

    public function get_termination()
    {
    	$query=$this->db->get('termination_type');
        $result= $query->result();
        $data=array();
		foreach($result as $r)
		{
			$data['value']=$r->id;
			$data['label']=$r->termination_type;
			$json[]=$data;
			
			
		}
		echo json_encode($json);
		exit;
    }

       public function termination_list()
    {
        $list = $this->terminations->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $a=1;
         foreach ($list as $termination) {

           $no++;
            $row = array();
            $row[] = $a++;
            $row[] = $termination->fullname;
            $row[] = $termination->deptname;
            $row[] = $termination->terminationtypes;
            $row[] = date('d M Y',strtotime($termination->terminationdate));
            $row[] = $termination->reason;
            $row[] = date('d M Y',strtotime($termination->lastdate));

            

            $row[]='<div class="dropdown dropdown-action">
                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="#" onclick="edit_termination('.$termination->id.')"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
                                <li><a href="#" onclick="delete_terminations('.$termination->id.')"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
                            </ul>
                        </div>';


           

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->terminations->count_all(),
            "recordsFiltered" => $this->terminations->count_filtered(),
            "data" => $data,
            );
//output to json format
        echo json_encode($output);
        exit;
    }


    public function termination_edit($id)
    {
        $data = $this->terminations->get_by_id($id);

        echo json_encode($data);
        exit;
    }

   




    public function add_termination()
    {

    	
        $employee=$this->input->post('employee');
        $termination_type=$this->input->post('termination_type');
        $lastdate=date('Y-m-d',strtotime($this->input->post('lastdate')));
        $terminationdate=date('Y-m-d',strtotime($this->input->post('terminationdate')));
        $reason=$this->input->post('reason');
       
       
            $data = array(
                'employee'=>$employee,
                'lastdate'=>$lastdate,
                'termination_type'=>$termination_type,
                'terminationdate'=>$terminationdate,
                'reason'=>$reason,
               'posted_date'=>date('Y-m-d H:i:s')
                );
            $this->db->insert('termination',$data);
            $result=($this->db->affected_rows()!= 1)? false:true;

            if($result==true) 
            {
                $datas['result']='yes';
                $datas['status']='Termination added successfully';
            }   
            else
            {
                $datas['result']='no';
                $datas['status']='Termination added failed!';
            }
        
        echo json_encode($datas);

        exit;

    }


    public function update_termination()
    {

        $id=$this->input->post('id');
        $employee=$this->input->post('employee');
        $termination_type=$this->input->post('termination_type');
        $lastdate=date('Y-m-d',strtotime($this->input->post('lastdate')));
        $terminationdate=date('Y-m-d',strtotime($this->input->post('terminationdate')));
        $reason=$this->input->post('reason');

        
            $data = array(
                'employee'=>$employee,
                'lastdate'=>$lastdate,
                'termination_type'=>$termination_type,
                'terminationdate'=>$terminationdate,
                'reason'=>$reason,
                );
            $this->db->where('id',$id);
            $this->db->update('termination',$data);
            $result=($this->db->affected_rows()!= 1)? false:true;

            if($result==true) 
            {
                $datas['result']='yes';
                $datas['status']='Termination update successfully';
            }   
            else
            {
                $datas['result']='no';
                $datas['status']='Termination update failed!';
            }
        
        echo json_encode($datas);

        exit;

    }


   

    public function termination_delete($id)
    {
        $data = array(
            'status' =>1,
            );
        $this->terminations->update(array('id' => $id), $data);
        echo json_encode(array("status" => TRUE));
        exit;
    } 




	 



}
