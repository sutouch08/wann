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
    		<div class="page_layout" style="position:relative; width: <?php echo $pageWidth; ?>mm; padding-top:1mm; margin:auto; margin-bottom:10px;">
          <div style="width:<?php echo $contentWidth; ?>mm; margin:auto; padding-bottom:10px;">
            <table class="table narrow" style="margin-bottom:0px; font-size:9px;">
              <tr>
                <td class="width-25"></td>
                <td class="width-25"></td>
                <td class="width-25"></td>
                <td class="width-25"></td>
              </tr>
              <tr>
                <td colspan="4" class="text-center">ใบสั่งชั่งวัตถุดิบ</td>
              </tr>
              <tr>
                <td colspan="4" class="text-center" style="font-size:45px; font-weight:bold;">
                  <?php echo barcodeImage($doc->BeginStr.$doc->DocNum, 15, ($contentWidth-10), 0); ?>
                </td>
              </tr>
              <tr>
                <td colspan="2">เลขที่อ้างอิง : &nbsp; <?php echo $doc->U_ProductionOrder; ?></td>
                <td colspan="2" class="text-right">เลขที่เอกสาร : &nbsp; <?php echo $doc->BeginStr.$doc->DocNum; ?></td>
              </tr>
              <tr>
                <td colspan="2">คลังต้นทาง : <?php echo $doc->Filler; ?></td>
                <td colspan="2" class="text-right">วันที่เอกสาร : <?php echo thai_date($doc->DocDate, FALSE, '/'); ?></td>
              </tr>
              <tr>
                <td colspan="2">คลังปลายทาง : <?php echo $doc->toWhsCode; ?></td>
                <td colspan="2" class="text-right">วันที่ครบกำหนด : <?php echo thai_date($doc->DocDueDate, FALSE, '/'); ?></td>
              </tr>


          <?php if( ! empty($details)) : ?>
            <?php foreach($details as $rs) : ?>
              <tr>
                <td colspan="4" class="text-center"><hr/></td>
              </tr>
              <tr>
                <td colspan="4" class="text-center"><?php echo barcodeImage($rs->ItemCode, 10); ?></td>
              </tr>
              <tr>
                <td colspan="2">รหัสวัตถุดิบ : <?php echo $rs->ItemCode; ?></td>
                <td colspan="2" class="text-right">จำนวนขอโอน : <?php echo number($rs->OpenQty, 2); ?> <?php echo $rs->unitMsr; ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
              <tr>
                <td colspan="4" class="text-center"><hr/></td>
              </tr>
              <tr>
                <td colspan="4" class="text-right">Print By : <?php echo $this->_user->uname; ?> Print at : <?php echo date('d/m/Y H:i:s'); ?></td>
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
