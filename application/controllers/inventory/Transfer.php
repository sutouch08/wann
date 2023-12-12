<?php
class Transfer extends PS_Controller
{
  public $menu_code = "ICTRWH";
  public $menu_group_code = "IC";
  public $title = 'Inventory Transfer';
	public $segment = 4;

	public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'inventory/transfer';
    $this->load->model('inventory/transfer_model');
    $this->load->helper('warehouse');
    $this->load->helper('transfer');
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
      'approval' => get_filter('approval', 'tr_approval', 'all'),
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
                $arr = array(
                  'user_id' => $this->_user->id,
                  'uname' => $this->_user->uname,
                  'docType' => 'TR',
                  'docNum' => $code,
                  'action' => 'create',
                  'ip_address' => $_SERVER['REMOTE_ADDR']
                );

                $this->user_model->add_logs($arr);
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
      $can_do = FALSE;

      $pm = $this->user_model->get_permission('ICAPRQ', $this->_user->id_profile);

      if(! empty($pm))
      {
        $num = $pm->can_add + $pm->can_edit + $pm->can_delete;
        $can_do = $num ? TRUE : FALSE;
      }

      $can_do = $this->_SuperAdmin ? TRUE : $can_do;

      $arr = array(
        'pm' => $can_do,
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


  public function update()
  {
    $sc = TRUE;
    $id = $this->input->post('id');
    $doc_date = $this->input->post('doc_date');
    $due_date = $this->input->post('due_date');
    $tax_date = $this->input->post('tax_date');
    $remark = get_null(trim($this->input->post('remark')));

    $doc = $this->transfer_model->get($id);

    if( ! empty($doc))
    {
      if($doc->Status == -1 OR $doc->Status == 0)
      {
        $arr = array(
          'DocDate' => db_date($doc_date),
          'DocDueDate' => db_date($due_date),
          'TaxDate' => db_date($tax_date),
          'remark' => $remark
        );

        if( ! $this->transfer_model->update($id, $arr))
        {
          $sc = FALSE;
          $this->error = "Failed to update data";
        }
        else
        {
          $arr = array(
            'user_id' => $this->_user->id,
            'uname' => $this->_user->uname,
            'docType' => 'TR',
            'docNum' => $doc->code,
            'action' => 'update',
            'ip_address' => $_SERVER['REMOTE_ADDR']
          );

          $this->user_model->add_logs($arr);
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "Invalid document status";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "Invalid document number";
    }

    $arr = array(
      'status' => $sc === TRUE ? 'success' : 'failed',
      'message' => $sc === TRUE ? 'success' : $this->error
    );

    echo json_encode($arr);
  }


  public function view_detail($id)
  {

    $doc = $this->transfer_model->get($id);

    if( ! empty($doc))
    {
      $request_rows = $this->transfer_model->get_request_rows($id);

      if( ! empty($request_rows))
      {
        foreach($request_rows as $rs)
        {
          $rs->details = $this->transfer_model->get_details_by_base_line($doc->id, $rs->LineNum);
        }
      }

      $arr = array(
        'doc' => $doc,
        'request_rows' => $request_rows,
        'logs' => $this->transfer_model->get_transfer_logs($doc->code)
      );

      $this->load->view('inventory/transfer/transfer_detail', $arr);
    }
    else
    {
      $this->page_error();
    }
  }


  public function get_request_item()
  {
    $sc = TRUE;
    $id = $this->input->get('id');
    $itemCode = $this->input->get('itemCode');

    if($id && $itemCode)
    {
      $row = $this->transfer_model->get_request_item($id, $itemCode);

      if( ! empty($row))
      {
        $ds = array(
          'LineNum' => $row->LineNum,
          'ItemCode' => $row->ItemCode,
          'Dscription' => $row->Dscription,
          'UomEntry' => $row->UomEntry,
          'UomCode' => $row->UomCode,
          'unitMsr' => $row->unitMsr,
          'NumPerMsr' => $row->NumPerMsr,
          'UomEntry2' => $row->UomEntry2,
          'UomCode2' => $row->UomCode2,
          'unitMsr2' => $row->unitMsr2,
          'NumPerMsr2' => $row->NumPerMsr2,
          'OpenQty' => $row->OpenQty,
          'Qty' => $row->Qty,
          'balance' => round(($row->OpenQty - $row->Qty), 6)
        );
      }
      else
      {
        $sc = FALSE;
        $this->error = "Not found";
      }
    }
    else
    {
      $sc = FALSE;
      set_error('required');
    }

    $arr = array(
      'status' => $sc === TRUE ? 'success' : 'failed',
      'message' => $sc === TRUE ? 'success' : $this->error,
      'data' => $sc === TRUE ? $ds : NULL
    );

    echo json_encode($arr);
  }


  public function save()
  {
    $sc = TRUE;
    $ex = 0;
    $id = $this->input->post('id');
    $must_approve = $this->input->post('must_approve') == 1 ? TRUE : FALSE;

    $doc = $this->transfer_model->get($id);

    if( ! empty($doc))
    {
      if($doc->Status == -1 OR $doc->Status == 0)
      {
        $arr = array(
          'must_approve' => $must_approve ? 1 : 0,
          'approved' => $must_approve ? 'P' : 'S',
          'Status' => 0,
          'isSaved' => 1
        );

        if( ! $this->transfer_model->update($id, $arr))
        {
          $sc = FALSE;
          $this->error = "Failed to update document status";
        }

        if($sc === TRUE)
        {
          $arr = array(
            'user_id' => $this->_user->id,
            'uname' => $this->_user->uname,
            'docType' => 'TR',
            'docNum' => $doc->code,
            'action' => $doc->isSaved == 1 ? 'edit' : 'save',
            'ip_address' => $_SERVER['REMOTE_ADDR']
          );

          $this->user_model->add_logs($arr);

          if( ! $must_approve)
          {
            $this->load->library('api');

            if( ! $this->api->exportTransfer($id))
            {
              $sc = FALSE;
              $ex = 1;
              $this->error = "บันทึกเอกสารสำเร็จแต่สส่งข้อมูลเข้า SAP ไม่สำเร็จ กรุณากดส่งข้อมูลอีกครั้งภายหลัง : {$this->error}";
            }
            else
            {
              $arr = array(
                'LineStatus' => 'C'
              );

              $this->transfer_model->update_details($id, $arr);
            }
          }
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "Invalid document status";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "Document not found!";
    }

    $arr = array(
      'status' => $sc === TRUE ? 'success' : 'failed',
      'message' => $sc === TRUE ? 'success' : $this->error,
      'ex' => $ex,
    );

    echo json_encode($arr);
  }


  public function unsave()
  {
    $sc = TRUE;
    $id = $this->input->post('id');

    if( ! empty($id))
    {
      if($this->pm->can_delete)
      {
        $doc = $this->transfer_model->get($id);

        if( ! empty($doc))
        {
          if($doc->Status != 2)
          {
            if($doc->Status == 1)
            {
              $sap = $this->transfer_model->get_sap_doc_num($doc->code);

              if( empty($sap))
              {
                $this->db->trans_begin();

                if( ! $this->transfer_model->update_details($doc->id, array('LineStatus' => 'O')))
                {
                  $sc = FALSE;
                  $this->error = "Failed to update transfer line status";
                }

                if( $sc === TRUE)
                {
                  $arr = array(
                    'Status' => -1,
                    'DocEntry' => NULL,
                    'DocNum' => NULL,
                    'Message' => NULL
                  );

                  if( ! $this->transfer_model->update($doc->id, $arr))
                  {
                    $sc = FALSE;
                    $this->error = "Failed to update document status";
                  }
                }

                if($sc === TRUE)
                {
                  $this->db->trans_commit();

                  $arr = array(
      							'user_id' => $this->_user->id,
      							'uname' => $this->_user->uname,
      							'docType' => 'TR',
      							'docNum' => $doc->code,
      							'action' => 'rollback',
      							'ip_address' => $_SERVER['REMOTE_ADDR']
      						);

      						$this->user_model->add_logs($arr);
                }
                else
                {
                  $this->db->trans_rollback();
                }
              }
              else
              {
                $sc = FALSE;
                $this->error = "The document has already been entered into SAP. Please cancel the document in SAP before rollback this document.";
              }
            }
          }
          else
          {
            $sc = FALSE;
            $this->error = "Invalid document status";
          }
        }
        else
        {
          $sc = FALSE;
          $this->error = "Invalid document number";
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "You don't have permission to perform this operation";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "Missing required parameter";
    }

    $arr = array(
      'status' => $sc === TRUE ? 'success' : 'failed',
      'message' => $sc === TRUE ? 'success' : $this->error
    );

    echo json_encode($arr);
  }


  public function cancle_transfer()
  {
    $sc = TRUE;

    $id = $this->input->post('id');

    if( ! empty($id))
    {
      if($this->pm->can_delete)
      {
        $doc = $this->transfer_model->get($id);

        if( ! empty($doc))
        {
          if($doc->Status != 2)
          {
            if($doc->Status == 1)
            {
              //--- check document exists on sap ?
              $sap = $this->transfer_model->get_sap_doc_num($doc->code);

              if( ! empty($sap))
              {
                $sc = FALSE;
                $this->error = "The document has already been entered into SAP. Please cancel the document in SAP before canceling this document.";
              }
            }

            if( $sc === TRUE )
            {
              //-- change status to -1 (draft)
              $this->db->trans_begin();

              if( ! $this->transfer_model->update_details($doc->id, array('LineStatus' => 'D')))
              {
                $sc = FALSE;
                $this->error = "Failed to change Transfer Line Status";
              }

              if($sc === TRUE)
              {
                $arr = array(
                  'Status' => 2
                );

                if( ! $this->transfer_model->update($doc->id, $arr))
                {
                  $sc = FALSE;
                  $this->error = "Failed to update document status";
                }
              }

              if($sc === TRUE)
              {
                $this->db->trans_commit();
                $arr = array(
                  'user_id' => $this->_user->id,
                  'uname' => $this->_user->uname,
                  'docType' => 'TR',
                  'docNum' => $doc->code,
                  'action' => 'cancel',
                  'ip_address' => $_SERVER['REMOTE_ADDR']
                );

                $this->user_model->add_logs($arr);
              }
              else
              {
                $this->db->trans_rollback();
              }
            }
          }
          else
          {
            $sc = FALSE;
            $this->error = "Document already Canceled";
          }
        }
        else
        {
          $sc = FALSE;
          $this->error = "Invalid document number";
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "You don't have permission to perform this operation";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "Missing required parameter";
    }

    $arr = array(
      'status' => $sc === TRUE ? 'success' : 'failed',
      'message' => $sc === TRUE ? 'success' : $this->error
    );

    echo json_encode($arr);
  }


  public function update_request_row()
  {
    $sc = TRUE;
    $id = $this->input->post('id');

    $doc = $this->transfer_model->get($id);

    if( ! empty($doc))
    {
      if($doc->Status < 1)
      {
        $LineNum = $this->input->post('LineNum');
        $Qty = $this->input->post('Qty'); //--- นน. อ่านจากเครื่องชั่ง ยังไม่แปลงหน่วย
        $qty = $Qty; //--- will change after unit convert
        $receiptNo = trim($this->input->post('ReceiptNo'));
        $deviceUnit = $this->input->post('deviceUnit'); //--- หน่วยของเครื่องชั่ง
        $rate = $this->input->post('rate'); //--- rate ที่ใช้แปลงตอนดึงข้อมูลไปเทียบกับเครื่องชั่ง
        $checker_uid = get_null($this->input->post('checker_uid'));

        $row = $this->transfer_model->get_request_row($id, $LineNum);

        if( ! empty($row))
        {
           //---- แปลงหน่วยกลับมาเป็นหน่วยเดียวกับ request row
           //--- ก่อนหน้านี้แปลงไปเป็นหน่วยเดียวกับเครื่องชั่ง

           if($row->unitMsr != "ชิ้น") {
             if($row->unitMsr == "กรัม" && $deviceUnit == "กิโลกรัม") {
               $qty = $Qty * $rate;
             }

             if($row->unitMsr == "กิโลกรัม" && $deviceUnit == "กรัม") {
               $qty = $Qty / $rate;
             }
           }
           else {
             if($row->unitMsr2 == "กิโลกรัม" && $deviceUnit == "กิโลกรัม") {
               $qty = $Qty * $rate;
             }

             if($row->unitMsr2 == "กิโลกรัม" && $deviceUnit == "กรัม") {
               $qty = ($Qty * $rate) / 1000;
             }
           }

          //--- เช็คว่าเกินที่ request หรือไม่
          $resultQty = $row->Qty + $qty;

          if($resultQty > $row->OpenQty)
          {
            $sc = FALSE;
            $this->error = "Quantity exceeded";
          }
          else
          {
            $this->db->trans_begin();
            //--- add transfer row
            $newLine = $this->transfer_model->get_max_line($id);

            $newLine++;

            $ds = array(
              'transfer_id' => $id,
              'transfer_code' => $doc->code,
              'LineNum' => $newLine,
              'BaseRef' => $row->DocNum,
              'BaseEntry' => $row->DocEntry,
              'BaseLine' => $row->LineNum,
              'ItemCode' => $row->ItemCode,
              'Dscription' => $row->Dscription,
              'ReceiptNo' => $receiptNo,
              'Qty' => $qty,
              'fromWhsCode' => $doc->fromWhsCode,
              'toWhsCode' => $doc->toWhsCode,
              'unitMsr' => $row->unitMsr,
              'UomEntry' => $row->UomEntry,
              'UomCode' => $row->UomCode,
              'numPerMsr' => $row->NumPerMsr,
              'unitMsr2' => $row->unitMsr2,
              'UomEntry2' => $row->UomEntry2,
              'UomCode2' => $row->UomCode2,
              'numPerMsr2' => $row->NumPerMsr2,
              'user_id' => $this->_user->id,
              'checker_uid' => $checker_uid,
              'weight' => $Qty,
              'deviceUnit' => $deviceUnit
            );

            if( ! $this->transfer_model->add_detail($ds))
            {
              $sc = FALSE;
              $this->error = "Failed to add transfer row";
            }
            else
            {
              $ds['QtyLabel'] = number($Qty, 6);
              $ds['date_add'] = thai_date(now(), TRUE);
            }

            if($sc === TRUE)
            {
              $arr = array(
                'Qty' => $resultQty
              );

              if( ! $this->transfer_model->update_request_row($row->id, $arr))
              {
                $sc = FALSE;
                $this->error = "Failed to update request row";
              }
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
        }
        else
        {
          $sc = FALSE;
          $this->error = "Request data not found";
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "Invalid document status";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "Document not found";
    }


    $arr = array(
      'status' => $sc === TRUE ? 'success' : 'failed',
      'message' => $sc === TRUE ? 'success' : $this->error,
      'qtyLabel' => $sc === TRUE ? number($resultQty, 6) : NULL,
      'qty' => $sc === TRUE ? $resultQty : NULL,
      'full' => $resultQty == $row->OpenQty ? TRUE : FALSE,
      'row' => $sc === TRUE ? $ds : NULL
    );

    echo json_encode($arr);
  }


  public function remove_row()
  {
    $sc = TRUE;
    $id = $this->input->post('transfer_id');
    $lineNum = $this->input->post('LineNum');

    $row = $this->transfer_model->get_detail_by_line_num($id, $lineNum);

    if( ! empty($row))
    {
      $request_row = $this->transfer_model->get_request_row($row->transfer_id, $row->BaseLine);

      if( ! empty($request_row))
      {
        $this->db->trans_begin();
        //--- remove row
        if( ! $this->transfer_model->delete_detail($row->id))
        {
          $sc = FALSE;
          $this->error = "Failed to delete transection row";
        }

        //--- update request qty
        if( $sc === TRUE)
        {
          $Qty = $request_row->Qty - $row->Qty;

          $ds = array(
            'Qty' => $Qty < 0 ? 0 : $Qty
          );

          if( ! $this->transfer_model->update_request_row($request_row->id, $ds))
          {
            $sc = FALSE;
            $this->error = "Failed to update request row";
          }
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
        $this->error = "Request row not found";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "No data found !";
    }

    $arr = array(
      'status' => $sc === TRUE ? 'success' : 'failed',
      'message' => $sc === TRUE ? 'success' : $this->error,
      'qtyLabel' => $sc === TRUE ? number($Qty, 6) : 0,
      'qty' => $sc === TRUE ? $Qty : 0,
      'LineNum' => $sc === TRUE ? $request_row->LineNum : NULL
    );

    echo json_encode($arr);
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


  public function change_request_qty()
  {
    $sc = TRUE;
    $id = $this->input->post('id');
    $LineNum = $this->input->post('LineNum');
    $Qty = $this->input->post('Qty');
    $uname = $this->input->post('uname');

    $row = $this->transfer_model->get_request_row($id, $LineNum);

    if( ! empty($row))
    {
      if($Qty <= 0)
      {
        $sc = FALSE;
        $this->error = "น้ำหนักต้องมากกว่า 0.00";
      }

      if($sc === TRUE)
      {
        if($row->Qty > $Qty)
        {
          $sc = FALSE;
          $this->error = "น้ำหนักต้องไม่เกินน้ำหนักที่บรรจุแล้ว";
        }
      }

      if($sc === TRUE)
      {
        $ds = array(
          'OpenQty' => $Qty,
          'OriginalQty' => $row->OpenQty,
          'OverwriteUser' => $uname,
          'OverwriteTime' => now()
        );

        if( ! $this->transfer_model->update_request_row($row->id, $ds))
        {
          $sc = FALSE;
          $this->error = "Failed to update request qty";
        }
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "Request row not found";
    }

    $arr = array(
      'status' => $sc === TRUE ? 'success' : 'failed',
      'message' => $sc === TRUE ? 'success' : $this->error,
      'qtyLabel' => number($Qty, 6),
      'qty' => $Qty
    );

    echo json_encode($arr);
  }


  public function do_export()
  {
    $sc = TRUE;
    $id = $this->input->post('id');

    $doc = $this->transfer_model->get($id);

    if( ! empty($doc))
    {
      if($doc->Status == 1 OR $doc->Status == 3)
      {
        $sap = $this->transfer_model->get_sap_doc_num($doc->code);

        if( empty($sap))
        {
          $this->load->library('api');

          if( ! $this->api->exportTransfer($id))
          {
            $sc = FALSE;
            $this->error = "บันทึกเอกสารสำเร็จแต่สส่งข้อมูลเข้า SAP ไม่สำเร็จ กรุณากดส่งข้อมูลอีกครั้งภายหลัง : {$this->error}";
          }
          else
          {
            $arr = array(
              'LineStatus' => 'C'
            );

            $this->transfer_model->update_details($id, $arr);
          }
        }
        else
        {
          $sc = FALSE;
          $this->error = "The document has already been entered into SAP. Please cancel the document in SAP before.";
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "Invalid document status";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "Document number could not be found";
    }

    $arr = array(
      'status' => $sc === TRUE ? 'success' : 'failed',
      'message' => $sc === TRUE ? 'success' : $this->error
    );

    echo json_encode($arr);
  }


  public function print_transfer($id)
  {
    $doc = $this->transfer_model->get($id);

    if( ! empty($doc))
    {
      $ds = array(
        'doc' => $doc,
        'details' => $this->transfer_model->get_details($id)
      );

      $this->load->library('printer');
      $this->load->view('print/print_transfer', $ds);
    }
    else
    {
      $this->page_error();
    }
  }

  public function printLabel($id, $LineNum)
  {
    $row = $this->transfer_model->get_detail_by_line_num($id, $LineNum);

    if( ! empty($row))
    {
      $this->load->library('printer');
      $this->load->view('print/transfer_sticker', $row);
    }
    else
    {
      $this->error_page();
    }
  }


  public function test_print()
  {
    $this->load->library('printer');
    $this->load->view('print/sticker_test_print');
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
      'tr_status',
      'tr_approval',
      'tr_from_date',
      'tr_to_date'
    );

    return clear_filter($filter);
  }
}

 ?>
