<?php $this->load->helper('print'); ?>
<!DOCTYPE html>
<html>
  <head>
  	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  	<link rel="icon" href="<?php echo base_url(); ?>assets/img/favicon.png" type="image/x-icon" />
  	<title><?php echo $this->title; ?></title>
  	<link href="<?php echo base_url(); ?>assets/css/bootstrap.css" rel="stylesheet" />
  	<link href="<?php echo base_url(); ?>assets/css/template.css" rel="stylesheet" />
  	<link href="<?php echo base_url(); ?>assets/css/print.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/css/font-sarabun.css" rel="stylesheet" />
    <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <style>
    .view-port {
      display: flex;
      flex-direction: column;
      flex-wrap: wrap;
      align-items:center;
    }

    #sticker {
      display: flex;
      border:solid 1px #ddd;
      width: 105mm;
      min-width: 50mm;
      height:32mm;
      min-height: 20mm;
      padding-left: 2mm;
      padding-right: 2mm;
      padding-top: 1mm;
      padding-bottom: 1mm
    }

    .sticker-label {
      border:solid 1px #ccc;
      min-width:20mm;
      min-height: 10mm;
      width:50%;
      border-radius: 5px;
      padding:2mm;
    }

    .label-space {
      width:0;
      height:100%;
    }

    .sticker-content {
      width: 100%;
      height:100%;
      border:1px;
      border-style: dashed;
      border-color:rgba(3,169,244,0.5);
      font-size:8px;
      font-weight: bold;
    }

    @media print {
      #sticker {
        border:none;
      }

      .sticker-label {
        border:none;
      }

      .sticker-content {
        border:none;
      }
    }
    </style>
  	</head>
  	<body>
      <div class="col-lg-12 view-port">
        <div class="hidden-print" style="height:50px;">&nbsp;</div>
        <div class="" id="sticker">
          <div class="sticker-label">
            <div class="sticker-content">
              <table class="width-100">
                <tr><td colspan="2" class="text-center">ฉลากวัตถุดิบ</td></tr>
                <tr>
                  <td class="width-40">รหัสวัตถุดิบ</td>
                  <td class="width-60"><?php echo $ItemCode; ?></td>
                </tr>
                <tr>
                  <td>เลขที่รับ</td>
                  <td><?php echo $ReceiptNo; ?></td>
                </tr>
                <tr>
                  <td>รหัสผลิตภัณฑ์</td>
                  <td>11-000-018</td>
                </tr>
                <tr>
                  <td>Lot No.</td>
                  <td>-</td>
                </tr>
                <tr>
                  <td>น้ำหนักวัตถุดิบ</td>
                  <td><?php echo number($Qty, 6); ?> &nbsp;&nbsp; <?php echo $unitMsr; ?></td>
                </tr>
              </table>
              <table class="width-100">
                <tr>
                  <td colspan="2" class="width-50">ผู้ชั่ง  <?php echo $this->user_model->get_name_by_id($user_id); ?></td>
                  <td colspan="2" class="width-50">ผู้ตรวจ  <?php echo $this->user_model->get_name_by_uid($checker_uid); ?></td>
                </tr>
                <tr>
                  <td colspan="3" class="width-50">วันที่  <?php echo date('d/m/Y H:i'); ?></td>
                  <td class="width-50 text-right" style="padding-right:5px;">FM-SP-14/00</td>
                </tr>
              </table>
            </div>
          </div>
          <div class="label-space"></div>
          <div class="sticker-label sticker-right">
            <div class="sticker-content">
              <table class="width-100">
                <tr><td colspan="2" class="text-center">ฉลากวัตถุดิบ</td></tr>
                <tr>
                  <td class="width-40">รหัสวัตถุดิบ</td>
                  <td class="width-60"><?php echo $ItemCode; ?></td>
                </tr>
                <tr>
                  <td>เลขที่รับ</td>
                  <td><?php echo $ReceiptNo; ?></td>
                </tr>
                <tr>
                  <td>รหัสผลิตภัณฑ์</td>
                  <td>11-000-018</td>
                </tr>
                <tr>
                  <td>Lot No.</td>
                  <td>-</td>
                </tr>
                <tr>
                  <td>น้ำหนักวัตถุดิบ</td>
                  <td><?php echo number($Qty, 6); ?> &nbsp;&nbsp; <?php echo $unitMsr; ?></td>
                </tr>
              </table>
              <table class="width-100">
                <tr>
                  <td colspan="2" class="width-50">ผู้ชั่ง  <?php echo $this->user_model->get_name_by_id($user_id); ?></td>
                  <td colspan="2" class="width-50">ผู้ตรวจ  <?php echo $this->user_model->get_name_by_uid($checker_uid); ?></td>
                </tr>
                <tr>
                  <td colspan="3" class="width-50">วันที่  <?php echo date('d/m/Y H:i'); ?></td>
                  <td class="width-50 text-right" style="padding-right:5px;">FM-SP-14/00</td>
                </tr>
              </table>
            </div>
          </div>
        </div>
        <div class="hidden-print text-center" style="margin-top:30px;">
          <button type="button" class="btn btn-lg btn-info btn-100" onclick="window.print()">พิมพ์</button>
        </div>
      </div>
    </body>
  </html>

<script>
  $(document).ready(function () {
    let setting = localStorage.getItem('WannPrintSetting');

    if(setting !== null && setting !== undefined) {
      let con = JSON.parse(setting);
      let s = con.sticker;
      let l = con.label;
      let p = con.printOption;

      let sticker = {
        "width" : `${s.width}mm`,
        "height" : `${s.height}mm`,
        "padding-top" : `${s.paddingTop}mm`,
        "padding-bottom" : `${s.paddingBottom}mm`,
        "padding-left" : `${s.paddingLeft}mm`,
        "padding-right" : `${s.paddingRight}mm`,
      }

      let label = {
        "padding-top" : `${l.paddingTop}mm`,
        "padding-bottom" : `${l.paddingBottom}mm`,
        "padding-left" : `${l.paddingLeft}mm`,
        "padding-right" : `${l.paddingRight}mm`,
      }

      $('#sticker').css(sticker);
      $('.sticker-label').css(label);
      $('.label-space').css('width', `${l.space}mm`);
      $('.sticker-content').css('font-size', `${l.fontSize}px`);

      let printPreview = (p !== undefined && p !== null) ? p.printPreview : 1;
      let closeAfterPrint = (p !== undefined && p !== null) ? p.closeAfterPrint : 1;

      if(closeAfterPrint) {
        window.onafterprint = window.close;
      }

      if( ! printPreview) {
        window.print();
      }
    }
    else {
      getDefault();
    }

});
</script>
