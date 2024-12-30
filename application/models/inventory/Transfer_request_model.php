<?php
class Transfer_request_model extends CI_Model
{
  private $tb = 'OWTQ';
  private $td = 'WTQ1';

  public function __construct()
  {
    parent::__construct();
  }

  public function get($id)
  {
    $rs = $this->ms
    ->select('tq.DocEntry, tq.DocNum, tq.DocDate, tq.DocDueDate, tq.DocStatus, tq.CANCELED')
    ->select('tq.Filler, tq.toWhsCode, tq.Series, tq.Comments, tq.U_BEX_EXREMARK, tq.U_ProductionOrder')
    ->select('sr.BeginStr, wh1.WhsName AS fromWarehouse, wh2.WhsName AS toWarehouse')
    ->from('OWTQ AS tq')
    ->join('NNM1 AS sr', 'tq.Series = sr.Series', 'left')
    ->join('OWHS AS wh1', 'tq.Filler = wh1.WhsCode', 'left')
    ->join('OWHS AS wh2', 'tq.toWhsCode = wh2.WhsCode', 'left')
    ->where('tq.DocEntry', $id)
    ->get();

    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return NULL;
  }

  public function get_by_code($code)
  {
    $rs = $this->ms
    ->select('tq.DocEntry, tq.DocNum, tq.DocDate, tq.DocDueDate, tq.DocStatus, tq.CANCELED')
    ->select('tq.Filler, tq.toWhsCode, tq.Series, tq.Comments, tq.U_BEX_EXREMARK, tq.U_ProductionOrder')
    ->select('sr.BeginStr, wh1.WhsName AS fromWarehouse, wh2.WhsName AS toWarehouse')
    ->from('OWTQ AS tq')
    ->join('NNM1 AS sr', 'tq.Series = sr.Series', 'left')
    ->join('OWHS AS wh1', 'tq.Filler = wh1.WhsCode', 'left')
    ->join('OWHS AS wh2', 'tq.toWhsCode = wh2.WhsCode', 'left')
    ->where('tq.DocNum', $code)
    ->get();

    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return NULL;
  }


  public function get_production_order($U_ProductionOrder)
  {
    if( ! empty($U_ProductionOrder))
    {
      $rs = $this->ms
      ->select('ItemCode, Comments, ProdName, U_Kanban_BPR, U_Kanban_BMR')
      ->where('DocNum', $U_ProductionOrder)
      ->get('OWOR');

      if($rs->num_rows() === 1)
      {
        return $rs->row();
      }
    }

    return NULL;
  }


  public function get_item($ItemCode)
  {
    $rs = $this->ms
    ->select('ItemCode, ItemName, U_OldCode, U_BOMCode')
    ->where('ItemCode', $ItemCode)
    ->get('OITM');

    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return NULL;
  }


  public function get_details($id)
  {
    $rs = $this->ms->where('DocEntry', $id)->get($this->td);

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }


  public function get_open_details($id)
  {
    $rs = $this->ms
    ->select('DocEntry, LineNum, LineStatus, ItemCode, Dscription, Quantity, OpenQty')
    ->select('UomEntry, UomCode, unitMsr, NumPerMsr')
    ->select('UomEntry2, UomCode2, unitMsr2, NumPerMsr2')
    ->where('DocEntry', $id)
    ->where('LineStatus', 'O')
    ->where('OpenQty > 0')
    ->get($this->td);

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }

  public function get_list(array $ds = array(), $perpage = 20, $offset = 0)
  {
    $this->ms
    ->select('tq.DocEntry, tq.DocNum, tq.DocDate, tq.DocDueDate, tq.DocStatus, tq.CANCELED')
    ->select('tq.Filler, tq.toWhsCode, tq.Series, tq.Comments, tq.U_BEX_EXREMARK')
    ->select('sr.BeginStr, wh1.WhsName AS fromWarehouse, wh2.WhsName AS toWarehouse')
    ->from('OWTQ AS tq')
    ->join('NNM1 AS sr', 'tq.Series = sr.Series AND sr.ObjectCode = 1250000001', 'left')
    ->join('OWHS AS wh1', 'tq.Filler = wh1.WhsCode', 'left')
    ->join('OWHS AS wh2', 'tq.toWhsCode = wh2.WhsCode', 'left');

    if( ! empty($ds['prefix']))
    {
      $this->ms->like('sr.BeginStr', $ds['prefix']);
    }

    if( ! empty($ds['docNum']))
    {
      $this->ms->like('tq.DocNum', $ds['docNum']);
    }

    if( ! empty($ds['fromWhs']) && $ds['fromWhs'] != 'all')
    {
      $this->ms->where('tq.Filler', $ds['fromWhs']);
    }

    if( ! empty($ds['toWhs']) && $ds['toWhs'] != 'all')
    {
      $this->ms->where('tq.toWhsCode', $ds['toWhs']);
    }

    if( ! empty($ds['from_date']))
    {
      $this->ms->where('tq.DocDate >=', from_date($ds['from_date']));
    }

    if( ! empty($ds['to_date']))
    {
      $this->ms->where('tq.DocDate <=', to_date($ds['to_date']));
    }

    if( ! empty($ds['status']) && $ds['status'] != 'all')
    {
      if($ds['status'] == 'D')
      {
        $this->ms->where('tq.CANCELED', 'Y');
      }
      else
      {
        $this->ms->where('tq.CANCELED', 'N')->where('tq.DocStatus', $ds['status']);
      }
    }

    $rs = $this->ms
    ->order_by('tq.DocDate', 'DESC')
    ->limit($perpage, $offset)
    ->get();

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }


  public function count_rows(array $ds = array())
  {
    $this->ms
    ->from('OWTQ AS tq')
    ->join('NNM1 AS sr', 'tq.Series = sr.Series AND sr.ObjectCode = 1250000001', 'left');

    if( ! empty($ds['prefix']))
    {
      $this->ms->like('sr.BeginStr', $ds['prefix']);
    }

    if( ! empty($ds['docNum']))
    {
      $this->ms->like('tq.DocNum', $ds['docNum']);
    }

    if( ! empty($ds['fromWhs']) && $ds['fromWhs'] != 'all')
    {
      $this->ms->where('tq.Filler', $ds['fromWhs']);
    }

    if( ! empty($ds['toWhs']) && $ds['toWhs'] != 'all')
    {
      $this->ms->where('tq.toWhsCode', $ds['toWhs']);
    }

    if( ! empty($ds['from_date']))
    {
      $this->ms->where('tq.DocDate >=', from_date($ds['from_date']));
    }

    if( ! empty($ds['to_date']))
    {
      $this->ms->where('tq.DocDate <=', to_date($ds['to_date']));
    }

    if( ! empty($ds['status']) && $ds['status'] != 'all')
    {
      if($ds['status'] == 'D')
      {
        $this->ms->where('tq.CANCELED', 'Y');
      }
      else
      {
        $this->ms->where('tq.CANCELED', 'N')->where('tq.DocStatus', $ds['status']);
      }
    }

    return $this->ms->count_all_results();
  }
} //-- end model

 ?>
