<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User_pin extends CI_Controller
{
	public $menu_code = '';
	public $menu_group_code = '';
  public $menu_sub_group_code = '';
  public $title = 'เปลี่ยน PIN Code';
	public $pm;
  public $error;
	public $_user;

	public function __construct()
	{
		parent::__construct();
		_check_login();
		$this->pm = new stdClass();
		$this->pm->can_view = 1;
    $this->load->model('users/user_model');
    $this->home = base_url().'user_pin';
	}


	public function index()
	{
		$uid = get_cookie('uid');

		$this->_user = $this->user_model->get_user_by_uid($uid);

    if( ! empty($this->_user))
		{
			$ds['data'] = $this->_user;
			$this->load->view('users/change_pin', $ds);
		}
	}


  public function change($code)
	{
    if(!empty($code))
    {
      $user = $this->user_model->get($code);

      if(!empty($user))
      {
        $ds['data'] = $user;
        $this->load->view('users/change_pwd', $ds);
      }
      else
      {
        //--- ถ้าไม่มีข้อมูล ให้ไป login ใหม่
        redirect(base_url().'users/authentication');
      }
    }
    else
    {
      //--- ถ้าไม่มีข้อมูล ให้ไป login ใหม่
  		redirect(base_url().'users/authentication');
    }
	}


	public function check_current_pin()
	{
		$uid = get_cookie('uid');
		$this->_user = $this->user_model->get_user_by_uid($uid);
		$uname = $this->input->post('uname');
		$s_key = $this->input->post('pin');

		if($uname == $this->_user->uname)
		{
			if( ! empty($this->_user->s_key))
			{
				if(password_verify($s_key, $this->_user->s_key))
				{
					echo "valid";
				}
				else
				{
					echo "invalid";
				}
			}
			else
			{
				echo "valid";
			}
		}
		else
		{
			echo "Invalid Username : {$uname}";
		}
	}



  public function change_pin()
	{
		$sc = TRUE;

		$uid = get_cookie('uid');
		$this->_user = $this->user_model->get_user_by_uid($uid);
		$uname = $this->input->post('uname');
		$s_key = $this->input->post('pin');
		$new_key = $this->input->post('new_pin');

		if($uname == $this->_user->uname)
		{
			if($this->_user->s_key)
			{
				if(password_verify($s_key, $this->_user->s_key))
				{
					$arr = array(
						's_key' => password_hash($new_key, PASSWORD_DEFAULT)
					);

					//--- update last pass change
					if( ! $this->user_model->update($this->_user->id, $arr))
					{
						$sc = FALSE;
						$this->error = "Failed to update new PIN";
					}
				}
				else
				{
					$sc = FALSE;
					$this->error = "Invalid PIN";
				}
			}
			else
			{
				$arr = array(
					's_key' => password_hash($new_key, PASSWORD_DEFAULT)
				);

				//--- update last pass change
				if( ! $this->user_model->update($this->_user->id, $arr))
				{
					$sc = FALSE;
					$this->error = "Failed to update new PIN";
				}
			}
		}
		else
		{
			$sc = FALSE;
			$this->error = "Invalid Username : {$uname}";
		}

		echo $sc === TRUE ? 'success' : $this->error;
	}

}
 ?>
