<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transfer_request extends PS_Controller
{
  public $menu_code = 'ICTRRQ';
	public $menu_group_code = 'IC';
	public $title = 'Transfer Request';
	public $segment = 4;

	public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'inventory/transfer_request';
		$this->load->model('inventory/transfer_request_model');
    $this->load->helper('warehouse');
  }


  public function index()
  {
		$filter = array(
			'prefix' => get_filter('prefix', 'tq_prefix', ''),
      'docNum' => get_filter('docNum', 'tq_docNum', ''),
      'fromWhs' => get_filter('fromWhs', 'tq_fromWhs', 'all'),
      'toWhs' => get_filter('toWhs', 'tq_toWhs', 'all'),
      'status' => get_filter('status', 'tq_status', 'all'),
      'from_date' => get_filter('from_date', 'tq_from_date', ''),
      'to_date' => get_filter('to_date', 'tq_to_date', '')
		);

		if($this->input->post('search'))
		{
			redirect($this->home);
		}
		else
		{
			//--- แสดงผลกี่รายการต่อหน้า
			$perpage = get_rows();

			$rows = $this->transfer_request_model->count_rows($filter);
			//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
			$init	= pagination_config($this->home.'/index/', $rows, $perpage, $this->segment);
			$filter['data'] = $this->transfer_request_model->get_list($filter, $perpage, $this->uri->segment($this->segment));
			$this->pagination->initialize($init);
			$this->load->view('inventory/transfer_request/transfer_request_list', $filter);
		}
  }


  public function view_detail($id)
  {
    $doc = $this->transfer_request_model->get($id);

    if( ! empty($doc))
    {
      $ds = array(
        'doc' => $doc,
        'details' => $this->transfer_request_model->get_details($id)
      );

      $this->load->view('inventory/transfer_request/transfer_request_detail', $ds);

    }
    else
    {
      $this->page_error();
    }
  }


  public function clear_filter()
  {
    $filter = array(
			'tq_prefix',
      'tq_docNum',
      'tq_fromWhs',
      'tq_toWhs',
      'tq_status',
      'tq_from_date',
      'tq_to_date'
		);

    return clear_filter($filter);
  }
} //--- end class

 ?>
