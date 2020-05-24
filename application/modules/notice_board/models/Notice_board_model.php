<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Notice_board_model extends CI_Model
{
    private static $db;

    function __construct()
    {
		parent::__construct();
		self::$db = &get_instance()->db;
	}

    static function all()
	{	
		self::$db->select('*');
		self::$db->from('notice_board'); 		 
		self::$db->order_by('id','DESC');
		return self::$db->get()->result();
    }
    
    static function find($notice_board_id)
	{
		return self::$db->where('id',$notice_board_id)->get('notice_board')->row();
	}
	
	static function notice_board_exists($title,$id='')
	{		 
		if($id!='')
		{
			return self::$db->where('title',$title)->where('id != ',$id)->get('notice_board')->row();            
		}
		else 
		{
			return self::$db->where('title',$title)->get('notice_board')->row();            
		}
		
	}

	static function delete($notice_board_id)
	{
		self::$db->where('id',$notice_board_id)->delete('notice_board');
	}

}