<?php
class Validate_credentials extends CI_Controller
{
  public $error;

  public function __construct()
  {
    parent::__construct();
  }


  public function get_permission()
  {
    $sc = TRUE;
    $s_key 	= $this->input->post('s_key');
    $skey   = $s_key === '' ? FALSE : md5($s_key);
  	$menu   = $this->input->post('menu');
  	$field 	= $this->input->post('field');
    $user = $this->user_model->get_user_credentials_by_skey($skey);

    if( ! empty($user))
  	{
      $rs = $this->user_model->get_permission($menu, $user->uid, $user->id_profile);

  		if( ! empty($rs))
  		{
        if(empty($field))
        {
          $val = $rs->can_add + $rs->can_edit + $rs->can_delete;

          if($val == 0)
          {
            $sc = FALSE;
            $this->error = "You don't have permission !";
          }
        }
        else
        {
          if($field == 'add' && $rs->can_add == 0)
          {
            $sc = FALSE;
          }

          if($field == 'edit' && $rs->can_edit == 0)
          {
            $sc = FALSE;
          }

          if($field == 'delete' && $rs->can_delete == 0)
          {
            $sc = FALSE;
          }

          if($field == 'approve' && $rs->can_approve == 0)
          {
            $sc = FALSE;
          }

          if($sc === FALSE)
          {
            $this->error = "You don't have permission !";
          }
        }
  		}
      else
      {
        $sc = FALSE;
        $this->error = "Invalid Permission Code";
      }
  	}
    else
    {
      $sc = FALSE;
      $this->error = "Invalid passcode";
    }

    $arr = array(
      'status' => $sc === TRUE ? 'success' : 'failed',
      'message' => $sc === TRUE ? 'success' : $this->error,
      'uname' => empty($user) ? NULL : $user->uname
    );

    echo json_encode($arr);
  }


  public function validate_permission()
  {
    $sc = TRUE;
    $s_key 	= $this->input->post('s_key');
    $skey   = $s_key === '' ? FALSE : $s_key;
  	$menu   = $this->input->post('menu');
  	$field 	= $this->input->post('field');
    $uid = $this->input->post('uid');

    $user = $this->user_model->get_user_by_uid($uid);

    if( ! empty($user))
    {
      if($user->s_key)
      {
        if( password_verify($s_key, $user->s_key) )
        {
          if( ! $user->id_profile == -987654321)
          {
            $rs = $this->user_model->get_permission($menu, $uid, $user->id_profile);

            if( ! empty($rs))
            {
              if(empty($field))
              {
                $val = $rs->can_add + $rs->can_edit + $rs->can_delete;

                if($val == 0)
                {
                  $sc = FALSE;
                  $this->error = "You don't have permission !";
                }
              }
              else
              {
                if($field == 'add' && $rs->can_add == 0)
                {
                  $sc = FALSE;
                }

                if($field == 'edit' && $rs->can_edit == 0)
                {
                  $sc = FALSE;
                }

                if($field == 'delete' && $rs->can_delete == 0)
                {
                  $sc = FALSE;
                }

                if($field == 'approve' && $rs->can_approve == 0)
                {
                  $sc = FALSE;
                }

                if($sc === FALSE)
                {
                  $this->error = "You don't have permission !";
                }
              }
            }
            else
            {
              $sc = FALSE;
              $this->error = "You don't have permission";
            }            
          }
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "INVALID PIN CODE";
      }
    }

    $arr = array(
      'status' => $sc === TRUE ? 'success' : 'failed',
      'uid' => $sc === TRUE ? $user->uid : NULL,
      'uname' => $sc === TRUE ? $user->uname : NULL,
      'message' => $sc === TRUE ? 'success' : $this->error
    );

    echo json_encode($arr);
  }


}//-- end class

 ?>
