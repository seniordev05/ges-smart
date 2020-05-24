<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Wiki_model extends CI_Model
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
		self::$db->from('wiki'); 		 
		self::$db->order_by('id','DESC');
		return self::$db->get()->result();
    }
    
    static function find($wiki_id)
	{
		return self::$db->where('id',$wiki_id)->get('wiki')->row();
	}
	
	static function wiki_exists($title,$id='')
	{		 
		if($id!='')
		{
			return self::$db->where('title',$title)->where('id != ',$id)->get('wiki')->row();            
		}
		else 
		{
			return self::$db->where('title',$title)->get('wiki')->row();            
		}
		
	}

	static function delete($wiki)
	{
		self::$db->where('id',$wiki)->delete('wiki');
	}

}