<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Weighing_manchine extends PS_Controller {
  public $menu_code = 'SCDECF';
	public $menu_group_code = 'SC'; //--- System security
	public $title = 'เพิ่ม/แก้ไข เครื่องชั่ง';

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'weighing_manchine';
  }


  public function index()
  {
    $this->load->view('weighing_manchine/device_list');
  }
} //--- end class
 ?>
