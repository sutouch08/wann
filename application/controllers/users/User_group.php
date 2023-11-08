<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_group extends PS_Controller{
	public $menu_code = 'DBGROUP';
	public $menu_group_code = 'SC';
	public $title = 'User Group';

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'users/user_group';
		$this->load->model('users/user_group_model');
  }


  public function index()
  {
		$filter = array(
			'name' => get_filter('name', 'group_name', ''),
		);

		if($this->input->post('search'))
		{
			redirect($this->home);
		}
		else
		{
			//--- แสดงผลกี่รายการต่อหน้า
			$perpage = get_filter('set_rows', 'rows', 20);

			if($perpage > 300)
			{
				$perpage = get_filter('rows', 'rows', 300);
			}

			$segment = 4; //-- url segment
			$rows = $this->user_group_model->count_rows($filter);

			//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
			$init	= pagination_config($this->home.'/index/', $rows, $perpage, $segment);

			$rs = $this->user_group_model->get_list($filter, $perpage, $this->uri->segment($segment));

			if( ! empty($rs))
			{
				foreach($rs as $rd)
				{
					$rd->member = $this->user_group_model->count_member($rd->id);
				}
			}

			$filter['data'] = $rs;

			$this->pagination->initialize($init);

	    $this->load->view('user_group/user_group_list', $filter);
		}
  }


  public function add_new()
  {
		if($this->pm->can_add)
		{
			$this->load->view('user_group/user_group_add');
		}
		else
		{
			$this->permission_deny();
		}
  }


	public function add()
	{
		$sc = TRUE;

		if($this->pm->can_add)
		{
			$name = trim($this->input->post('name'));

			if( ! empty($name))
			{
				if( ! $this->user_group_model->is_exists_name($name))
				{
					$arr = array(
						'name' => $name
					);

					if( ! $this->user_group_model->add($arr))
					{
						$sc = FALSE;
						set_error('insert');
					}
				}
				else
				{
					$sc = FALSE;
					set_error('exists', $name);
				}
			}
			else
			{
				$sc = FALSE;
				set_error('required', ' : Name');
			}
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		$this->_response($sc);
	}



	public function edit($id)
	{
		if($this->pm->can_edit)
		{
			$ds = $this->user_group_model->get($id);

			if( ! empty($ds))
			{
				$this->load->view('user_group/user_group_edit', $ds);
			}
			else
			{
				$this->error_page();
			}
		}
		else
		{
			$this->permission_deny();
		}
	}


	public function update()
	{
		$sc = TRUE;

		if($this->pm->can_edit)
		{
			$id = $this->input->post('id');
			$name = trim($this->input->post('name'));

			if( ! empty($id) && ! empty($name))
			{
				if( ! $this->user_group_model->is_exists_name($name, $id))
				{
					$arr = array(
						'name' => $name
					);

					if( ! $this->user_group_model->update($id, $arr))
					{
						$sc = FALSE;
						set_error('update');
					}
				}
				else
				{
					$sc = FALSE;
					set_error('exists', $name);
				}
			}
			else
			{
				$sc = FALSE;
				set_error('required', ' : id and name');
			}
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		$this->_response($sc);
	}



	public function delete()
	{
		$sc = TRUE;

		if($this->pm->can_delete)
		{
			$id = $this->input->post('id');

			if( ! empty($id))
			{
				$member = $this->user_group_model->count_member($id);

				if( $member == 0)
				{
					if( ! $this->user_group_model->delete($id))
					{
						$sc = FALSE;
						set_error('delete');
					}
				}
				else
				{
					$sc = FALSE;
					$this->error = "Unable to delete group. This group has related to {$member} users";
				}
			}
			else
			{
				$sc = FALSE;
				set_error('required', ' : id');
			}
		}
		else
		{
			$sc = FALSE;
			set_error('permission');
		}

		$this->_response($sc);
	}


	public function clear_filter()
	{
		return clear_filter(array('group_name'));
	}
}
?>
