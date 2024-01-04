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
      <button type="button" class="btn btn-xs btn-warning top-btn" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
    </p>
  </div>
</div>
<hr>
<div class="row">
  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4">
    <label>วันที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center" id="doc_date" name="doc_date" value="<?php echo date('d-m-Y'); ?>" readonly />
  </div>
  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4">
    <label>วันที่ครบกำหนด</label>
    <input type="text" class="form-control input-sm text-center" id="due_date" name="due_date" value="<?php echo date('d-m-Y'); ?>" readonly />
  </div>
  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-4">
    <label>Posting Date</label>
    <input type="text" class="form-control input-sm text-center" id="tax_date" name="tax_date" value="<?php echo date('d-m-Y'); ?>" readonly />
  </div>
  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
    <label>คลังต้นทาง</label>
    <select class="form-control input-sm" id="fromWhsCode" name="fromWhsCode">
      <option value="">ตามใบขอโอนย้าย</option>
      <?php //echo select_warehouse(); ?>
    </select>
  </div>

  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
    <label>คลังปลายทาง</label>
    <select class="form-control input-sm" id="toWhsCode" name="toWhsCode">
      <option value="">ตามใบขอโอนย้าย</option>
      <?php //echo select_warehouse(); ?>
    </select>
  </div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6">
    <label>User</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $this->_user->uname; ?>" disabled/>
  </div>

  <div class="col-lg-8-harf col-md-12 col-xs-12">
    <label>หมายเหตุ</label>
    <input type="text" class="form-control input-sm" maxlength="254" id="remark" name="remark" placeholder="ระบุหมายเหตุ (ถ้ามี)"/>
  </div>

  <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
    <label>ใบขอโอนย้าย</label>
    <input type="text" class="form-control input-sm text-center" name="requestCode" id="request-code" placeholder="Transfer Request" autofocus/>
  </div>
  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-3">
    <label class="display-block not-show">load</label>
    <button type="button" class="btn btn-xs btn-success btn-block" onclick="add()">Add</button>
  </div>
</div>

<hr class="margin-top-15 margin-bottom-15">

<script src="<?php echo base_url(); ?>/scripts/inventory/transfer/transfer.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>/scripts/inventory/transfer/transfer_add.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
