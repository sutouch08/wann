<?php $this->load->view('include/header') ?>
<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-6 hidden-xs">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
  <div class="col-xs-12 visible-xs">
    <h3 class="title-xs"><?php echo $this->title; ?></h3>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <p class="pull-right top-p">
      <button type="button" class="btn btn-xs btn-warning top-btn" onclick="leave()"><i class="fa fa-arrow-left"></i> Back</button>
    </p>
  </div>
</div>
<hr>
<div class="row">
  <div class="col-lg-1-harf col-md-3 col-sm-3 col-xs-6">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center" id="code" value="<?php echo $doc->code; ?>" disabled />
  </div>
  <div class="col-lg-1-harf col-md-3 col-sm-3 col-xs-6">
    <label>วันที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center" id="doc_date" name="doc_date" value="<?php echo thai_date($doc->DocDate); ?>" readonly />
  </div>
  <div class="col-lg-1-harf col-md-3 col-sm-3 col-xs-6">
    <label>วันที่ครบกำหนด</label>
    <input type="text" class="form-control input-sm text-center" id="due_date" name="due_date" value="<?php echo thai_date($doc->DocDueDate); ?>" readonly />
  </div>
  <div class="col-lg-1-harf col-md-3 col-sm-3 col-xs-6">
    <label>Posting Date</label>
    <input type="text" class="form-control input-sm text-center" id="tax_date" name="tax_date" value="<?php echo thai_date($doc->TaxDate); ?>" readonly />
  </div>
  <div class="col-lg-3 col-md-5 col-sm-5 col-xs-6">
    <label>คลังต้นทาง</label>
    <select class="form-control input-sm" id="fromWhsCode" name="fromWhsCode">
      <?php echo select_warehouse($doc->fromWhsCode); ?>
    </select>
  </div>

  <div class="col-lg-3 col-md-5 col-sm-5 col-xs-6">
    <label>คลังปลายทาง</label>
    <select class="form-control input-sm" id="toWhsCode" name="toWhsCode">
      <?php echo select_warehouse($doc->toWhsCode); ?>
    </select>
  </div>

  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6">
    <label>User</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $doc->user; ?>" disabled/>
  </div>

  <div class="col-lg-7 col-md-8-harf col-sm-8-harf col-xs-6">
    <label>หมายเหตุ</label>
    <input type="text" class="form-control input-sm" maxlength="254" id="remark" value="<?php echo $doc->remark; ?>" placeholder="ระบุหมายเหตุ (ถ้ามี)"/>
  </div>

  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
    <label>ใบขอโอนย้าย</label>
    <input type="text" class="form-control input-sm text-center" id="request-code" placeholder="Transfer Request" value="<?php echo $doc->BasePrefix.$doc->BaseRef; ?>" disabled/>
  </div>
  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6">
    <label class="display-block not-show">load</label>
    <button type="button" class="btn btn-xs btn-success btn-block" onclick="update()">Update</button>
  </div>

  <input type="hidden" id="id" value="<?php echo $doc->id; ?>" />
  <input type="hidden" id="baseEntry" value="<?php echo $doc->BaseEntry; ?>" />
  <input type="hidden" id="baseRef" value="<?php echo $doc->BaseRef; ?>" />
</div>
<hr class="margin-top-15 margin-bottom-15">
<div class="row">

</div>
<hr class="margin-top-15 margin-bottom-15">
<style>
.check-title {
  background-color: #f8f8f8;
  border: solid 1px #CCC;
  padding:8px;
  font-size: 14px;
  text-align: center;
}

.check-table {
  padding-left: 0;
  padding-right: 0;
  background-color: #CCC;
  height:400px;
  max-height: 400px;
  overflow: auto;
}
</style>

<div class="row">
  <div class="col-lg-6-harf col-md-7 col-sm-12 col-xs-12 padding-5">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 check-title" >กำลังตรวจนับ</div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 check-table">
      <table class="table table-striped tableFixHead border-1" style="min-width:440px;">
        <thead>
          <tr class="freez">
            <th class="fix-width-120">บาร์โค้ด</th>
            <th class="min-width-150">รหัสสินค้า</th>
            <th class="fix-width-50 text-center">จำนวน</th>
            <th class="fix-width-80 text-center">เวลา</th>
            <th class="fix-width-40 text-center">
              <a class="pointer red" href="javascript:removeCheck()" title="ลบรายการที่เลือก"><i class="fa fa-trash fa-lg"></i></a>
            </th>
          </tr>
        </thead>
        <tbody id="check-table">

        </tbody>
      </table>
    </div>
  </div>

  <div class="divider-hidden visible-xs"></div>
  <div class="divider-hidden visible-xs"></div>
  <div class="divider-hidden visible-xs"></div>

  <div class="col-lg-5-harf col-md-5 col-sm-12 col-xs-12 padding-5">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 check-title">ตรวจนับแล้ว</div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 check-table">
      <table class="table table-striped border-1 tableFixHead" style="min-width:350px;">
        <thead>
          <tr class="freez">
            <th class="fix-width-120">บาร์โค้ด</th>
            <th class="min-width-150">รหัสสินค้า</th>
            <th class="fix-width-80 text-right">จำนวน</th>
          </tr>
        </thead>
        <tbody id="checked-table">
          <tr id="head" class="hide"><td colspan="3"></td></tr>
        
        </tbody>
      </table>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-6">

  </div>
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
    <table class="table table-striped table-bordered" style="min-width:900px;">
      <thead>
        <tr>
          <th class="fix-width-50 middle text-center">ลำดับ<br>No.</th>
          <th class="fix-width-150 middle text-center">รหัสสินค้า<br>Product Code</th>
          <th class="fix-width-250 middle text-center">รายการสินค้า<br>Product Description</th>
          <th class="fix-width-150 middle text-center">จำนวนขอโอน<br>Quantity</th>
          <th class="fix-width-150 middle text-center">Receipt</th>
          <th class="fix-width-150 middle text-center">บรรจุ</th>
        </tr>
      </thead>
      <tbody id="transfer-table">
        <tr id="{{DocEntry}}-{{LineNum}}">

          <td class="no">{{no}}</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<script src="<?php echo base_url(); ?>/scripts/inventory/transfer/transfer.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
