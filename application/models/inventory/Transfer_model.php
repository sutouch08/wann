<?php
class Transfer_model extends CI_Model
{
  private $tb = "transfer";
  private $td = "transfer_details";
  private $tr = "transfer_request_data";

  public function __construct()
  {
    parent::__construct();
  }

  public function get($id)
  {
    $rs = $this->db->where('id', $id)->get($this->tb);

    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return NULL;
  }


  public function get_by_code($code)
  {
    $rs = $this->db->where('code', $code)->get($this->tb);

    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return NULL;
  }


  public function get_details($id)
  {
    $rs = $this->db->where('transfer_id', $id)->get($this->td);

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }

  public function get_request_rows($transfer_id)
  {
    $rs = $this->db->where('transfer_id', $transfer_id)->get($this->tr);

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }
  

  public function get_detail($id)
  {
    $rs = $this->db->where('id', $id)->get($this->td);

    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return NULL;
  }


  public function add(array $ds = array())
  {
    if( ! empty($ds))
    {
      if($this->db->insert($this->tb, $ds))
      {
        return $this->db->insert_id();
      }
    }

    return FALSE;
  }


  public function add_detail(array $ds = array())
  {
    if( ! empty($ds))
    {
      if($this->db->insert($this->td, $ds))
      {
        return $this->db->insert_id();
      }
    }

    return FALSE;
  }


  public function add_request_data_row(array $ds = array())
  {
    if( ! empty($ds))
    {
      return $this->db->insert($this->tr, $ds);
    }

    return FALSE;
  }


  public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
  {
    if(isset($ds['code']) && $ds['code'] != '')
    {
      $this->db->like('code', $ds['code']);
    }

    if(isset($ds['DocNum']) && $ds['DocNum'] != '')
    {
      $this->db->like('DocNum', $ds['DocNum']);
    }

    if(isset($ds['BaseRef']) && $ds['BaseRef'] != '')
    {
      $this->db->like('BaseRef', $ds['BaseRef']);
    }

    if(isset($ds['fromWhs']) && $ds['fromWhs'] != 'all')
    {
      $this->db->where('fromWhsCode', $ds['fromWhs']);
    }

    if(isset($ds['toWhs']) && $ds['toWhs'] != 'all')
    {
      $this->db->where('toWhsCode', $ds['toWhs']);
    }

    if(isset($ds['status']) && $ds['status'] != 'all')
    {
      $this->db->where('Status', $ds['status']);
    }

    if( ! empty($ds['from_date']))
    {
      $this->db->where('DocDate >=', from_date($ds['from_date']));
    }

    if( ! empty($ds['to_date']))
    {
      $this->db->where('DocDate <=', to_date($ds['to_date']));
    }

    if( isset($ds['user']) && $ds['user'] != '')
    {
      $this->db->where('user', $ds['user']);
    }

    $rs = $this->db->order_by('DocDate', 'DESC')->limit($perpage, $offset)->get($this->tb);

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }


  public function count_rows(array $ds = array())
  {
    if(isset($ds['code']) && $ds['code'] != '')
    {
      $this->db->like('code', $ds['code']);
    }

    if(isset($ds['DocNum']) && $ds['DocNum'] != '')
    {
      $this->db->like('DocNum', $ds['DocNum']);
    }

    if(isset($ds['BaseRef']) && $ds['BaseRef'] != '')
    {
      $this->db->like('BaseRef', $ds['BaseRef']);
    }

    if(isset($ds['fromWhs']) && $ds['fromWhs'] != 'all')
    {
      $this->db->where('fromWhsCode', $ds['fromWhs']);
    }

    if(isset($ds['toWhs']) && $ds['toWhs'] != 'all')
    {
      $this->db->where('toWhsCode', $ds['toWhs']);
    }

    if(isset($ds['status']) && $ds['status'] != 'all')
    {
      $this->db->where('Status', $ds['status']);
    }

    if( ! empty($ds['from_date']))
    {
      $this->db->where('DocDate >=', from_date($ds['from_date']));
    }

    if( ! empty($ds['to_date']))
    {
      $this->db->where('DocDate <=', to_date($ds['to_date']));
    }

    if( isset($ds['user']) && $ds['user'] != '')
    {
      $this->db->where('user', $ds['user']);
    }

    return $this->db->count_all_results($this->tb);
  }


  public function get_commit_qty($baseEntry, $baseLine, $itemCode, $transfer_id = NULL)
  {
    $this->db
    ->select_sum('Qty')
    ->where('BaseEntry', $baseEntry)
    ->where('BaseLine', $baseLine)
    ->where('ItemCode', $itemCode);

    if( ! empty($transfer_id))
    {
      $this->db->where('transfer_id !=', $transfer_id);
    }

    $rs = $this->db->get($this->td);

    if($rs->num_rows() === 1)
    {
      return $rs->row()->Qty;
    }

    return 0;
  }


  public function get_transfer_code_by_active_transfer_request($baseEntry)
  {
    $rs = $this->db
    ->select('code')
    ->where('baseEntry', $baseEntry)
    ->where_in('Status', array(-1, 0, 3))
    ->where('DocEntry IS NULL', NULL, FALSE)
    ->where('DocNum IS NULL', NULL, FALSE)
    ->get($this->tb);

    if($rs->num_rows() > 0)
    {
      return $rs->row()->code;
    }

    return NULL;
  }


  public function get_max_code($pre)
  {
    $rs = $this->db
    ->select_max('code')
    ->like('code', $pre, 'after')
    ->order_by('code', 'DESC')
    ->get($this->tb);

    if($rs->num_rows() === 1)
		{
			return $rs->row()->code;
		}

		return NULL;
  }

} //--- end model

 ?>
