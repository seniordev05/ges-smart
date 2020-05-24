<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Attendance_model extends CI_Model
{

    private static $db;

    function __construct(){
        parent::__construct();
        self::$db = &get_instance()->db;
    }

    // Get all projects
    static function get_list($user_id = NULL, $date = NULL)
    {
        $condition = "";
        if($user_id != NULL){
          $condition .= " U.id = '$user_id' AND";
        }
    /*    $attenlogs = "SELECT AE.user_id, AE.punch_in_date_time, AE.punch_in_note, AE.in_address, AE.punch_out_note, AE.out_address, IF(AE.punch_out_date_time != '0000-00-00 00:00:00', AE.punch_out_date_time, '--') AS punch_out_date_time, AD.fullname, IF((time_to_sec(timediff(AE.punch_out_date_time, AE.punch_in_date_time )) / 3600) IS NULL,0,(time_to_sec(timediff(AE.punch_out_date_time, AE.punch_in_date_time )) / 3600) ) AS cal_hours FROM dgt_account_details AS AD 
            LEFT JOIN  dgt_attendance_employee AS AE ON AD.user_id = AE.user_id 
            LEFT JOIN  dgt_users AS U ON U.id = AD.user_id 
            WHERE ".$condition." role_id = 3 AND (date(punch_in_date_time) = '$date' OR date(punch_out_date_time) = '$date') ORDER BY AE.user_id ASC"; */
             $attenlogs = "SELECT U.id as user_id,AD.fullname, AE.punch_in_date_time, AE.punch_in_note, AE.in_address, AE.punch_out_note, AE.out_address, IF(AE.punch_out_date_time != '0000-00-00 00:00:00', AE.punch_out_date_time, '-') AS punch_out_date_time,  IF((time_to_sec(timediff(AE.punch_out_date_time, AE.punch_in_date_time )) / 3600) IS NULL,0,(time_to_sec(timediff(AE.punch_out_date_time, AE.punch_in_date_time )) / 3600) ) AS cal_hours
                    FROM dgt_users U
                    INNER JOIN dgt_account_details AD ON AD.user_id = U.id 
                    INNER JOIN dgt_attendance_employee AE ON AE.user_id = AD.user_id
                    WHERE $condition  U.role_id = 3  AND (date(punch_in_date_time) = '$date' OR date(punch_out_date_time) = '$date') ORDER BY AE.user_id ASC";
       
        return self::$db->query($attenlogs)->result();
    }

    static function attendance_list($inputs){

        $page = $inputs['page'];
        $employee_name = $inputs['employee_name'];
        $employee_id = $inputs['employee_id'];
        $limit = 'LIMIT 0,10';
        if($page > 1){
            $start = ($page - 1) * 10;
            $limit = "LIMIT $start,10";
        }
        $query_string = "SELECT count(U.id) as total_records  FROM dgt_users U LEFT JOIN dgt_account_details AD ON AD.user_id = U.id WHERE U.role_id = 3 ";
        if(!empty($employee_name)){
            $query_string .= " AND AD.fullname LIKE '%".$employee_name."%'";
        }
        if($employee_id !=0){
            $query_string .= " AND U.id =  $employee_id";    
        }

        
        
        $total_pages  = self::$db->query($query_string)->row_array();
        
        if(!empty($total_pages)){
            $total_pages  = $total_pages['total_records'];
            if($total_pages > 0){
                $total_pages = ceil($total_pages/10);
            }    
        }else{
             $total_pages = 0 ;
        }
         
        $query_string = "SELECT U.id as user_id,AD.fullname FROM dgt_users U LEFT JOIN dgt_account_details AD ON AD.user_id = U.id WHERE U.role_id = 3 ";
        
        if(!empty($employee_name)){
            $query_string .= " AND AD.fullname LIKE '%".$employee_name."%'";
        }
        if($employee_id !=0){
            $query_string .= " AND U.id =  $employee_id";    
        }
        $query_string .= " ORDER BY AD.fullname ASC $limit ";
        $results =  self::$db->query($query_string)->result();
        $records = array();
        if(!empty($results)){
            foreach ($results as $result) {
                $user_id   = $result->user_id;
                $attendance  = self::attendance($user_id,$inputs);
                $result->attendance = unserialize($attendance['month_days']);
                $records[] = $result;
            }
        }
        return array($total_pages,$records);
        
    }

    static function attendance($user_id,$inputs)
    {   
        $a_month = $inputs['attendance_month'];
        $a_year =  $inputs['attendance_year'];
        $result = self::$db->query("SELECT month_days,month_days_in_out FROM dgt_attendance_details WHERE user_id = $user_id AND a_month = $a_month AND a_year = $a_year ")->row_array();
        if(!empty($result)){
            return $result;
        }else{
            $days = array();
            $days_in_out = array();
            $lat_day = date('t',strtotime($a_year.'-'.$a_month.'-'.'1'));
            for($i=1;$i<=$lat_day;$i++){
                $day = date('D',strtotime($a_year.'-'.$a_month.'-'.$i));
                $day = (strtolower($day)=='sun')?0:'';
                $day_details = array('day'=>$day,'punch_in'=>'','punch_out'=>'');
                $days[] = $day_details;
                $days_in_out[] = array($day_details);
            }
            $insert = array(
                'user_id'=>$user_id,
                'month_days'=>serialize($days),
                'month_days_in_out'=>serialize($days_in_out),
                'a_month'=>$a_month,
                'a_year'=>$a_year
                );
            self::$db->insert("dgt_attendance_details",$insert);

        return  self::$db->query("SELECT month_days,month_days_in_out FROM dgt_attendance_details WHERE user_id = $user_id AND a_month = $a_month AND a_year = $a_year ")->row_array();
        }
       
    }

}

/* End of file Project.php */
