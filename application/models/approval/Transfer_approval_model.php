<?php
class Transfer_approval_model extends CI_Model
{
  private $tb = "transfer";  

  public function __construct()
  {
    parent::__construct();
  }

  public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
  {
    $this->db
    ->where('Status', 0)
    ->where('must_approve', 1)
    ->where('approved', 'P');

    if(isset($ds['code']) && $ds['code'] != '')
    {
      $this->db->like('code', $ds['code']);
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
    $this->db
    ->where('Status', 0)
    ->where('must_approve', 1)
    ->where('approved', 'P');

    if(isset($ds['code']) && $ds['code'] != '')
    {
      $this->db->like('code', $ds['code']);
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
} //--- end class

 ?>
