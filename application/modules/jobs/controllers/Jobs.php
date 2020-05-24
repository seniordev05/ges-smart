<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Jobs extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->library(array('form_validation'));
        $this->load->model(array('Client', 'App', 'Invoice', 'Expense', 'Project', 'Payment', 'Estimate','Offer'));
        
        $this->load->library(array('form_validation')); 
    }

    public function index()
    {   
        
          
            $this->load->module('layouts');
            $this->load->library('Template');

            $data['jobs'] = $this->offerlistload();
            $data['offer_jobtype'] = $this->_jobtypename();
            $data['currencies'] = App::currencies();

 // print_r($data);exit;
       
        $this->template
                ->set_layout('login')
                ->build('joblists',isset($data)?$data:NULL);
    }

    function offerlistload()
    {
        return Offer::to_where(array('user_id'=>'1'));
    }
    function _jobtypename()
    {
        return Offer::job_where(array('user_id'=>'1'));
    }


    function time_elapsed_string($datetime, $full = false) {
        $current =    date("Y-m-d H:i:s");
    $now = new DateTime($current);
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    public function apply($jid=false,$jtype=false)
    {
        if($jid==false || $jtype==false)
        {
            header("Location: ".base_url('jobs'));
        }
         $this->load->module('layouts');
            $this->load->library('Template');

            $data['jobs'] = $this->offerlistload();
            $data['offer_jobtype'] = $this->_jobtypename();


 
       
        $this->template
                ->set_layout('login')
                ->build('jobview',isset($data)?$data:NULL);
    }

}
/* End of file contacts.php */
