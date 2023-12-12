<?php
function statusLabel($status = -1, $approved = 'S')
{
  $txt = "unknow";

  if($status == -1)
  {
    $txt = "Draft";
  }
  else if($status == 0)
  {
    if($approved == 'P')
    {
      $txt = "Pending";
    }

    if($approved == 'R')
    {
      $txt = "Rejected";
    }
  }
  else if($status == 2)
  {
    $txt = "Canceled";
  }
  else if($status == 3)
  {
    $txt = "Failed";
  }
  else if($status == 1)
  {
    if($approved == 'S' OR $approved == 'A')
    {
      $txt = "Closed";
    }
  }

  return $txt;
}

 ?>
