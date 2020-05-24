<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class Chats_model extends CI_Model
{
  function user_chat_history($user_id)
	{

		/*$usr_qry = "SELECT c.chat_id,
					 CASE
						WHEN c.chat_from = ".$user_id." THEN (chat_to)
						WHEN c.chat_from != ".$user_id." THEN (chat_from)
						ELSE 0
					 END AS user_id,
					 CASE
						WHEN c.chat_from = ".$user_id." THEN (SELECT  fullname FROM fx_account_details WHERE user_id = c.chat_to)
						WHEN c.chat_from != ".$user_id." THEN (SELECT fullname FROM fx_account_details WHERE user_id = c.chat_from)
						ELSE ''
					 END AS fullname,
					 CASE
						WHEN c.chat_from = ".$user_id." THEN (SELECT  count(id) FROM fx_chats_text WHERE text_from = c.chat_to and text_to = ".$user_id." and text_status = 0)
						WHEN c.chat_from != ".$user_id." THEN (SELECT  count(id) FROM fx_chats_text WHERE text_from = c.chat_from and text_to = ".$user_id." and text_status = 0)
						ELSE ''
						END AS new_chats,
					 (SELECT concat(text_from,'[^]',text_to,'[^]',text_content,'[^]',text_date_time) as det FROM fx_chats_text WHERE text_to = user_id order by text_date_time desc limit 1) as last_chat
					 FROM `fx_chats` c 
					 WHERE (c.chat_from = ".$user_id." or c.chat_to = ".$user_id.") order by chat_date_time desc";  
 		$query   = $this->db->query($usr_qry); 
 		//chat_id = c.chat_id
 		//echo $this->db->last_query();
 		//exit;
		if ($query->num_rows() > 0){
			return $query->result();
		} */

	 $id =$user_id;
      
      $this->db->select('id,text_from,text_to');
      $this->db->from('fx_chats_text');
      $this->db->where("text_from = $user_id OR text_to = $user_id");
      $this->db->order_by('id', 'desc');
      $chat_history = $this->db->get()->result_array();
      
      $chat_user_ids = array();
      if(!empty($chat_history)){
        foreach ($chat_history as $history) {
       	  $from = $history['text_from'];
          if(!in_array($from, $chat_user_ids) && $from!=$id){
            $chat_user_ids[] = $from; 
          }
          $to = $history['text_to'];
          if(!in_array($to, $chat_user_ids) && $to!=$id){
            $chat_user_ids[] = $to; 
          }
        }
      }
      $chat_user_ids = array_filter($chat_user_ids);

      $chathistory = array();

      if(!empty($chat_user_ids)){
      	foreach ($chat_user_ids as $chat_id) {

      	$result = $this->db->query("SELECT CT.id as chat_id, if(text_from = $user_id, text_to,text_from) as user_id,CONCAT(text_from,'[^]',text_to,'[^]',text_content,'[^]',text_date_time) as last_chat,text_status as new_chats FROM `fx_chats_text` CT  WHERE (text_from = $user_id and text_to =$chat_id) OR (text_from = $chat_id and text_to =$user_id) order by text_date_time DESC limit 1")->row_array();
		$this->db->select('fullname');
		 
      	if(!empty($result)){
      			$data = $this->db->get_where('fx_account_details', array('user_id' => $chat_id))->row_array();	
      			$result['fullname'] = $data['fullname'];
				$chathistory[] = (object)$result;      		
      		}
      	}
      }
      /*echo "<pre>";
    print_r($chathistory);exit;*/
	return $chathistory;
    
	}
 function chat_text_details($user1,$user2,$order_by)
	{
		$qry      = "SELECT ct.*,ad1.fullname as from_user_name,ad2.fullname as to_user_fullname
					 FROM `fx_chats_text` ct
					 left join fx_account_details as ad1 on ad1.user_id = ct.text_from
					 left join fx_account_details as ad2 on ad2.user_id = ct.text_to
					 where (ct.text_from = ".$user1." and ct.text_to = ".$user2.") OR 
					 (ct.text_from = ".$user2." and ct.text_to = ".$user1." )"; 
		if($order_by == 1){ $qry .= "order by ct.id asc";}			 
		if($order_by == 2){ $qry .= "order by ct.id asc";}	 
  		$query   = $this->db->query($qry); 
		if ($query->num_rows() > 0){
			return $query->result_array();
		} 
	}	
	
 function check_already_chat($user1,$user2)
	{
		$usr_qry = "SELECT  chat_id from fx_chats WHERE (chat_from = ".$user1." and chat_to = ".$user2.") OR (chat_from = ".$user2." and chat_to = ".$user1.")";  
 		$query   = $this->db->query($usr_qry); 
		if ($query->num_rows() > 0){
			$chat = $query->result_array();
			return $chat[0]['chat_id'];
		} 
	}	
	 
function inser_chat($data,$sts)
	{ 
	    if($sts == 1){
	    	$this->db->insert('fx_chats',$data);
			return $this->db->insert_id();
		}else if($sts == 2){
			$this->db->update('fx_chats',$data,array('chat_id'=>$data['chat_id']));
		}
	}		 
	 
	 function new_chat_user($name='')
	 {
	 	$user_id = $this->session->userdata('user_id');
	 	$query = $this->db->query("SELECT chat_from,chat_to FROM fx_chats WHERE chat_from = $user_id OR chat_to = $user_id");
	 	$ids = array();
	 	$ids[] = $user_id;
	 	if($query->num_rows() > 0){
	 		$datas = $query->result_array();
	 		foreach ($datas as  $value) {
	 			$ids[] = $value['chat_from'];
	 			$ids[] = $value['chat_to'];
	 		}
	 		$ids = array_unique($ids);
	 	}

	 	$query_data = $this->db->query("SELECT fullname,user_id FROM fx_account_details WHERE user_id NOT IN (".implode(',',$ids).") AND fullname like '%".$name."%'");
		$results = array();
		 
		if($query_data->num_rows() > 0){
			$results = $query_data->result_array();
		}
		return $results;
	 }

	 public function new_chat_userdetails($id='')
	 {
	 	$query_data = $this->db->query("SELECT fullname,user_id,avatar FROM fx_account_details WHERE user_id = $id")->row_array();
	 	
	 	$from_id = $this->session->userdata('user_id');
		$to_id = $query_data['user_id'];	
		$array['chat_from'] = $from_id;
		$array['chat_to']= $to_id;
		$array['chat_date_time']= date('Y-m-d h:i:s'); 
		$id = $this->inser_chat($array,1);

	   $data2['text_from']         = $from_id;
	   $data2['text_to']           = $to_id;
	   $data2['text_content']      = ''; 
 	   $data2['text_date_time']    = date('Y-m-d h:i:s'); 
	   $this->db->insert('fx_chats_text',$data2);
 	   $last_id                    = $this->db->insert_id();
	    $query_data['last_id']	= $last_id;
	    $query_data['lastdate']	= date('d M',strtotime($data2['text_date_time']));
	    return $query_data;
	 }
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	
}

/* End of file model.php */