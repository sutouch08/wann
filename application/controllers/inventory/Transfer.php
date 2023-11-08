<?php
class Transfer extends PS_Controller
{
  public $menu_code = "ICTRWH";
  public $menu_group_code = "IC";
  public $title = 'Transfer';
	public $segment = 4;

	public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'inventory/transfer';
    $this->load->model('inventory/transfer_model');
    $this->load->helper('warehouse');
  }


  public function index()
  {
		$filter = array(
			'code' => get_filter('code', 'tr_code', ''),
      'DocNum' => get_filter('DocNum', 'tr_doc_num', ''),
      'BaseRef' => get_filter('BaseRef', 'tr_base_ref', ''),
      'fromWhs' => get_filter('fromWhs', 'tr_fromWhs', 'all'),
      'toWhs' => get_filter('toWhs', 'tr_toWhs', 'all'),
      'status' => get_filter('status', 'tr_status', 'all'),
      'from_date' => get_filter('from_date', 'tr_from_date', ''),
      'to_date' => get_filter('to_date', 'tr_to_date', ''),
      'user' => get_filter('user', 'tr_user', '')
		);

		if($this->input->post('search'))
		{
			redirect($this->home);
		}
		else
		{
			//--- แสดงผลกี่รายการต่อหน้า
			$perpage = get_rows();

			$rows = $this->transfer_model->count_rows($filter);
			//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
			$init	= pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);
			$filter['data'] = $this->transfer_model->get_list($filter, $perpage, $this->uri->segment($this->segment));
			$this->pagination->initialize($init);
			$this->load->view('inventory/transfer/transfer_list', $filter);
		}
  }


  public function add_new()
  {
    $ds = array(
      'code' => $this->get_new_code()
    );

    $this->load->view('inventory/transfer/transfer_add', $ds);
  }

  public function get_request_data()
  {
    $this->load->model('inventory/transfer_request_model');
    $sc = TRUE;
    $code = $this->input->get('code');

    $doc = $this->transfer_request_model->get_by_code($code);

    if( ! empty($doc))
    {
      if($doc->CANCELED == 'N' && $doc->DocStatus == 'O')
      {
        $details = $this->transfer_request_model->get_open_details($doc->DocEntry);

        if(empty($details))
        {
          $sc = FALSE;
          $this->error = "ไม่พบรายการรอโอน";
        }
      }
      else
      {
        $sc = FALSE;
        if($doc->CANCELED == 'Y')
        {
          $this->error = "เอกสารถูกยกเลิกไปแล้ว";
        }
        elseif($doc->DocStatus == 'C')
        {
          $this->error = "เอกสารถูกปิดไปแล้ว";
        }
        else
        {
          $this->error = "Invalid Document Status";
        }
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "ไม่พบเลขที่เอกสาร";
    }

    $arr = array(
      'status' => $sc === TRUE ? 'success' : 'failed',
      'message' => $sc === TRUE ? 'success' : $this->error,
      'doc' => $sc === TRUE ? $doc : NULL,
      'details' => $sc === TRUE ? $details : NULL
    );

    echo json_encode($arr);
  }


  public function add()
  {
    $this->load->model('inventory/transfer_request_model');
    $sc = TRUE;
    $request_code = trim($this->input->post('code'));
    $doc_date = db_date($this->input->post('doc_date'));
    $due_date = db_date($this->input->post('due_date'));
    $tax_date = db_date($this->input->post('tax_date'));
    $fromWhsCode = $this->input->post('fromWhsCode');
    $toWhsCode = $this->input->post('toWhsCode');
    $remark = get_null(trim($this->input->post('remark')));

    $DocNum = preg_replace('/[^0-9]/', '', $request_code);

    if( ! empty($DocNum))
    {
      $doc = $this->transfer_request_model->get_by_code($DocNum);

      if( ! empty($doc))
      {
        if($doc->CANCELED == 'N' && $doc->DocStatus == 'O')
        {
          //--- ตรวจสอบว่ามี Transfer request code ถูกโหลดเข้าเอกสารอื่นแล้วยังไม่เข้า SAP อยู่หรือเปล่า
          $transfer_code = $this->transfer_model->get_transfer_code_by_active_transfer_request($doc->DocEntry);

          if(empty($transfer_code))
          {
            //--- ไม่มีใบขอโอนที่เปิดค้างไว้ สามารถใช้ open qty จากใบขอโอนที่โหลดเข้ามาได้เลย
            $details = $this->transfer_request_model->get_open_details($doc->DocEntry);

            if( ! empty($details))
            {
              $this->db->trans_begin();

              //--- สร้างเอกสารใหม่
              $code = $this->get_new_code($doc_date);
              $arr = array(
                'code' => $code,
                'DocDate' => $doc_date,
                'DocDueDate' => $due_date,
                'TaxDate' => $tax_date,
                'fromWhsCode' => empty($fromWhsCode) ? $doc->Filler : $fromWhsCode,
                'toWhsCode' => empty($toWhsCode) ? $doc->toWhsCode : $toWhsCode,
                'BaseEntry' => $doc->DocEntry,
                'BasePrefix' => $doc->BeginStr,
                'BaseRef' => $doc->DocNum,
                'remark' => $remark,
                'user' => $this->_user->uname
              );

              $id = $this->transfer_model->add($arr);

              if( ! empty($id))
              {
                //--- create request row data for regconsign
                foreach($details as $rs)
                {
                  if($sc === FALSE)
                  {
                    break;
                  }

                  if($rs->OpenQty > 0)
                  {
                    $arr = array(
                      'transfer_id' => $id,
                      'transfer_code' => $code,
                      'DocEntry' => $doc->DocEntry, //--- Transfer request Entry
                      'DocNum' => $doc->DocNum, //--- Transfer request DocNum
                      'LineNum' => $rs->LineNum,
                      'ItemCode' => $rs->ItemCode,
                      'Dscription' => $rs->Dscription,
                      'UomEntry' => $rs->UomEntry,
                      'UomCode' => $rs->UomCode,
                      'unitMsr' => $rs->unitMsr,
                      'NumPerMsr' => $rs->NumPerMsr,
                      'UomEntry2' => $rs->UomEntry2,
                      'UomCode2' => $rs->UomCode2,
                      'unitMsr2' => $rs->unitMsr2,
                      'NumPerMsr2' => $rs->NumPerMsr2,
                      'OpenQty' => $rs->OpenQty
                    );

                    if( ! $this->transfer_model->add_request_data_row($arr))
                    {
                      $sc = FALSE;
                      $this->error = "เพิ่มรายการยอดตั้งต้นไม่สำเร็จ";
                    }
                  }
                }
              }
              else
              {
                $sc = FALSE;
                $this->error = "สร้างเอกสารไม่สำเร็จ";
              }

              if($sc === TRUE)
              {
                $this->db->trans_commit();
              }
              else
              {
                $this->db->trans_rollback();
              }
            }
            else
            {
              $sc = FALSE;
              $this->error = "ไม่พบรายการรอโอนย้าย : No Open items found";
            }
          }
          else
          {
            //---
            $sc = FALSE;
            $this->error = "พบใบขอโอนย้ายถูกเปิดค้างไว้ที่ {$transfer_code}";
          }

        }
        else
        {
          $sc = FALSE;
          $this->error = "ใบขอโอนย้ายถูก ปิด หรือ ยกเลิก ไปแล้ว";
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "ไม่พบเลขที่ใบโอนย้ายที่ระบุ : {$request_code}";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "Missing required parameter";
    }

    $arr = array(
      'status' => $sc === TRUE ? 'success' : 'failed',
      'message' => $sc === TRUE ? 'success' : $this->error,
      'id' => $sc === TRUE ? $id : NULL
    );

    echo json_encode($arr);
  }


  public function edit($id)
  {
    $doc = $this->transfer_model->get($id);

    if( ! empty($doc))
    {
      $arr = array(
        'doc' => $doc,
        'details' => $this->transfer_model->get_details($id),
        'request_rows' => $this->transfer_model->get_request_rows($id)
      );

      $this->load->view('inventory/transfer/transfer_edit', $arr);
    }
    else
    {
      $this->page_error();
    }
  }

  public function get_request_code()
  {
    $txt = trim($_REQUEST['term']);

    $ds = array();

    if( ! empty($txt))
    {
      $this->ms
      ->select('tq.DocEntry, tq.DocNum, sr.BeginStr')
      ->from('OWTQ AS tq')
      ->join('NNM1 AS sr', 'tq.Series = sr.Series AND sr.ObjectCode = 1250000001', 'left')
      ->where('tq.CANCELED', 'N')
      ->where('tq.DocStatus', 'O');

      if($txt != '*')
      {
        $prefix = preg_replace('/[^a-zA-Z]/', '', $txt);
        $code = preg_replace('/[^0-9]/', '', $txt);

        if( ! empty($prefix))
        {
          $this->ms->like('sr.BeginStr', $prefix);
        }

        if( ! empty($code))
        {
          $this->ms->like('tq.DocNum', $code);
        }
      }

      $rd = $this->ms->order_by('tq.DocDate', 'DESC')->limit(50)->get();

      if($rd->num_rows() > 0)
      {
        foreach($rd->result() as $rs)
        {
          $ds[] = $rs->BeginStr.$rs->DocNum;
        }
      }
    }

    echo json_encode($ds);
  }


  public function get_new_code($date = NULL)
  {
    $date = empty($date) ? date('Y-m-d') : $date;
    $Y = date('y', strtotime($date));
    $M = date('m', strtotime($date));
    $prefix = getConfig('PREFIX_TRANSFER');
    $run_digit = getConfig('RUN_DIGIT_TRANSFER');
    $pre = $prefix .'-'.$Y.$M;
    $code = $this->transfer_model->get_max_code($pre);

    if( ! empty($code))
    {
      $run_no = mb_substr($code, ($run_digit*-1), NULL, 'UTF-8') + 1;
      $new_code = $prefix . '-' . $Y . $M . sprintf('%0'.$run_digit.'d', $run_no);
    }
    else
    {
      $new_code = $prefix . '-' . $Y . $M . sprintf('%0'.$run_digit.'d', '001');
    }

    return $new_code;
  }


  public function clear_filter()
  {
    $filter = array(
      'tr_code',
      'tr_doc_num',
      'tr_base_ref',
      'tr_fromWhs',
      'tr_toWhs',
      'tr_user',
      'tr_from_date',
      'tr_to_date'
    );

    return clear_filter($filter);
  }
}

 ?>
