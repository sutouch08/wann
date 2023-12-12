<?php
$this->load->helper('print');
$pageWidth = 80; //--- ควรสร้างเป็น config ไว้ที่เครื่องของ user เพราะว่าบางเครื่องอาจจะไม่ได้ใช้กระดาษหน้ากว้างเท่ากัน
$contentWidth = 78;
?>
<!DOCTYPE html>
<html>
  <head>
  	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  	<link rel="icon" href="<?php echo base_url(); ?>assets/images/icons/favicon.ico" type="image/x-icon" />
  	<title><?php echo $this->title; ?></title>
  	<link href="<?php echo base_url(); ?>assets/fonts/fontawesome-5/css/all.css" rel="stylesheet" />
  	<link href="<?php echo base_url(); ?>assets/css/bootstrap.css" rel="stylesheet" />
  	<link href="<?php echo base_url(); ?>assets/css/template.css" rel="stylesheet" />
  	<link href="<?php echo base_url(); ?>assets/css/print.css" rel="stylesheet" />
  	<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
  	<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <style>
    .page_layout{
      border: solid 1px #aaa;
      border-radius:0px;
    }

    .content-table > tbody > tr {
      height:5mm;
    }

    .content-table > tbody > tr:last-child {
      height: auto;
    }

    .table > tbody > tr > td {
      border: none;
      padding:3px;
    }

    @media print{
      .page_layout{ border: none; }
    }
    </style>
  	</head>
  	<body>

      <div style="width:100%">
        <!-- Page Start -->
    		<div class="page_layout" style="position:relative; width: <?php echo $pageWidth; ?>mm; padding-top:1mm; margin:auto; margin-top:30px; margin-bottom:10px;">
          <div style="width:<?php echo $contentWidth; ?>mm; margin:auto; padding-bottom:10px;">
            <table class="table narrow" style="margin-bottom:0px;">
              <tr>
                <td class="text-center">ผู้ตรวจสอบ</td>
              </tr>
              <tr>
                <td class="text-center">
                  <div class="item-box" style="width:90%; height:200px; margin-left:auto; margin-right:auto;">

                  </div>
                </td>
              </tr>
              <tr>
                <td class="text-center">
                  <?php echo barcodeImage($user->uid, 8, 0, 0); ?>
                </td>
                <tr>
                  <td class="text-center"><?php echo (empty($user->empName) ? $user->name : $user->empName); ?></td>
                </tr>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </body>
  </html>

<script>
//   $(document).ready(function () {
//     window.print();
// });
</script>
