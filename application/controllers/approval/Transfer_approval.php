<?php
class Transfer_approval extends PS_Controller
{
  public $menu_code = "ICAPTR";
  public $menu_group_code = "AP";
  public $title = 'Transfer Approval';
	public $segment = 4;

	public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'approval/transfer_approval';
    $this->load->model('approval/transfer_approval_model');
    $this->load->model('inventory/transfer_model');
    $this->load->helper('warehouse');
    $this->load->helper('transfer');
  }


  public function index()
  {
		$filter = array(
			'code' => get_filter('code', 'tr_code', ''),
      'BaseRef' => get_filter('BaseRef', 'tr_base_ref', ''),
      'fromWhs' => get_filter('fromWhs', 'tr_fromWhs', 'all'),
      'toWhs' => get_filter('toWhs', 'tr_toWhs', 'all'),
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

			$rows = $this->transfer_approval_model->count_rows($filter);
			//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
			$init	= pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);
			$filter['data'] = $this->transfer_approval_model->get_list($filter, $perpage, $this->uri->segment($this->segment));
			$this->pagination->initialize($init);
			$this->load->view('approval/transfer_approval_list', $filter);
		}
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
        'request_rows' => $request_rows
      );

      $this->load->view('approval/transfer_approval_detail', $arr);
    }
    else
    {
      $this->page_error();
    }
  }


  function reject()
  {
    $sc = TRUE;
    $id = $this->input->post('id');
    $message = get_null(trim($this->input->post('message')));

    if($this->cando())
    {
      $doc = $this->transfer_model->get($id);

      if( ! empty($doc))
      {
        if( $doc->Status == 0 && $doc->must_approve == 1 && $doc->approved == 'P')
        {
          $arr = array(
            'approved' => 'R',
            'approver' => $this->_user->uname,
            'Message' => $message
          );

          if( ! $this->transfer_model->update($id, $arr))
          {
            $sc = FALSE;
            set_error('update');
          }
          else
          {
            $arr = array(
              'user_id' => $this->_user->id,
              'uname' => $this->_user->uname,
              'docType' => 'TR',
              'docNum' => $doc->code,
              'action' => 'reject',
              'ip_address' => $_SERVER['REMOTE_ADDR']
            );

            $this->user_model->add_logs($arr);
          }
        }
        else
        {
          $sc = FALSE;
          $this->error = "Invalid Document Status";
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "Invalid Document Number";
      }
    }
    else
    {
      $sc = FALSE;
      set_error('permission');
    }

    $this->_response($sc);
  }


  public function approve()
  {
    $sc = TRUE;
    $id = $this->input->post('id');
    $ex = 0;

    if($this->cando())
    {
      $doc = $this->transfer_model->get($id);

      if( ! empty($doc))
      {
        if( $doc->Status == 0 && $doc->approved == 'P')
        {
          $arr = array(
            'approved' => 'A',
            'approver' => $this->_user->uname
          );

          if( ! $this->transfer_model->update($id, $arr))
          {
            $sc = FALSE;
            $this->error = "Failed to approve document";
          }
          else
          {
            $arr = array(
              'user_id' => $this->_user->id,
              'uname' => $this->_user->uname,
              'docType' => 'TR',
              'docNum' => $doc->code,
              'action' => 'approve',
              'ip_address' => $_SERVER['REMOTE_ADDR']
            );

            $this->user_model->add_logs($arr);
          }

          if($sc === TRUE)
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
        else
        {
          $sc = FALSE;
          $this->error = "Invalid document status";
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "Document number could be found";
      }
    }
    else
    {
      $sc = FALSE;
      set_error('permission');
    }

    $arr = array(
      'status' => $sc === TRUE ? 'success' : 'failed',
      'message' => $sc === TRUE ? 'success' : $this->error,
      'ex' => $ex
    );

    echo json_encode($arr);
  }


  private function cando()
  {
    $cando = ($this->pm->can_add + $this->pm->can_edit + $this->pm->can_delete) > 0 ? TRUE : FALSE;

    return $cando;
  }

  public function clear_filter()
  {
    $filter = array(
      'tr_code',
      'tr_base_ref',
      'tr_fromWhs',
      'tr_toWhs',
      'tr_user',
      'tr_status',
      'tr_from_date',
      'tr_to_date'
    );

    return clear_filter($filter);
  }
}

 ?>
