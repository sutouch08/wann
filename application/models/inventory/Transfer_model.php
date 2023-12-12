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

  public function get_max_line($id)
  {
    $rs = $this->db
    ->select_max('LineNum')
    ->where('transfer_id', $id)
    ->order_by('LineNum', 'ASC')
    ->get($this->td);

    if($rs->num_rows() === 1)
    {
      return $rs->row()->LineNum;
    }

    return -1;
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
    $rs = $this->db
    ->select('td.*, u.name AS checker')
    ->from('transfer_details AS td')
    ->join('user AS u', 'td.checker_uid = u.uid', 'left')
    ->where('td.transfer_id', $id)
    ->get();

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }

  public function get_details_by_base_line($transfer_id, $baseLine)
  {
    $rs = $this->db
    ->select('td.*, u.name AS checker')
    ->from('transfer_details AS td')
    ->join('user AS u', 'td.checker_uid = u.uid', 'left')
    ->where('td.transfer_id', $transfer_id)
    ->where('td.BaseLine', $baseLine)
    ->get();

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


  public function get_request_row($transfer_id, $line_num)
  {
    $rs = $this->db->where('transfer_id', $transfer_id)->where('LineNum', $line_num)->get($this->tr);

    if($rs->num_rows() === 1)
    {
      return $rs->row();
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

  public function get_detail_by_line_num($transfer_id, $lineNum)
  {
    $rs = $this->db
    ->where('transfer_id', $transfer_id)
    ->where('LineNum', $lineNum)
    ->get($this->td);

    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return NULL;
  }


  public function get_exists_detail($transfer_id, $itemCode, $baseRef, $baseLine, $receiptNo)
  {
    $rs = $this->db
    ->where('transfer_id', $transfer_id)
    ->where('ItemCode', $itemCode)
    ->where('BaseRef', $baseRef)
    ->where('BaseLine', $baseLine)
    ->where('ReceiptNo', $receiptNo)
    ->get($this->td);

    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return NULL;
  }


  public function get_request_item($id, $itemCode)
  {
    $rs = $this->db
    ->where('transfer_id', $id)
    ->where('ItemCode', $itemCode)
    ->where('OpenQty > Qty', NULL, FALSE)
    ->order_by('LineNum', 'ASC')
    ->get($this->tr);

    if($rs->num_rows() > 0)
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


  public function update($id, array $ds = array())
  {
    if( ! empty($ds))
    {
      return $this->db->where('id', $id)->update($this->tb, $ds);
    }

    return FALSE;
  }


  public function update_details($transfer_id, array $ds = array())
  {
    if( ! empty($ds))
    {
      return $this->db->where('transfer_id', $transfer_id)->update($this->td, $ds);
    }

    return FALSE;
  }


  public function update_request_row($id, array $ds = array())
  {
    if( ! empty($ds))
    {
      return $this->db->where('id', $id)->update($this->tr, $ds);
    }

    return  FALSE;
  }


  public function delete_detail($id)
  {
    return $this->db->where('id', $id)->delete($this->td);
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

    if(isset($ds['approval']) && $ds['approval'] != 'all')
    {
      $this->db->where('approved', $ds['approval']);
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

    if(isset($ds['approval']) && $ds['approval'] != 'all')
    {
      $this->db->where('approved', $ds['approval']);
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


  public function get_sap_doc_num($code)
  {
    // $rs = $this->ms
    // ->select('DocNum')
    // ->where('U_WEBCODE', $code)
    // ->where('CANCELED', 'N')
    // ->get('OWTR');
    //
    // if($rs->num_rows() > 0)
    // {
    //   return $rs->row()->DocNum;
    // }
    // return 'WRX';
    return NULL;
  }


  public function get_transfer_logs($code)
  {
    $rs = $this->db->where('docNum', $code)->order_by('date_upd', 'ASC')->get('access_logs');

    if( ! empty($rs))
    {
      return $rs->result();
    }

    return NULL;
  }

} //--- end model

 ?>
