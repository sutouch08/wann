<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 hidden-xs">
    <h3 class="title"> <?php echo $this->title; ?></h3>
  </div>
	<div class="col-xs-12 visible-xs">
		<h3 class="title-xs"><?php echo $this->title; ?></h3>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <p class="pull-right top-p">
			<button type="button" class="btn btn-sm btn-warning top-btn" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
			<button type="button" class="btn btn-sm btn-info top-btn" onclick="printTQ()"><i class="fa fa-print"></i> พิมพ์</button>
		</p>
  </div>
</div><!-- End Row -->
<hr class="padding-5"/>
<div class="row">
  <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control text-center" id="code" value="<?php echo $doc->BeginStr.$doc->DocNum; ?>" disabled/>
  </div>
  <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6">
    <label>วันที่เอกสาร</label>
    <input type="text" class="form-control text-center" id="date_add" value="<?php echo thai_date($doc->DocDate, FALSE); ?>" readonly disabled />
  </div>
  <div class="col-lg-4-harf col-md-4-harf col-sm-4 col-xs-6">
    <label>คลังต้นทาง</label>
    <select class="form-control" name="fromWhsCode" id="fromWhsCode" disabled>
      <option value="">Not specify</option>
      <?php echo select_warehouse($doc->Filler); ?>
		</select>
  </div>

  <div class="col-lg-4-harf col-md-4-harf col-sm-4 col-xs-6">
    <label>คลังปลายทาง</label>
		<select class="form-control" name="toWhsCode" id="toWhsCode" disabled>
      <option value="">Not specify</option>
      <?php echo select_warehouse($doc->toWhsCode); ?>
		</select>
  </div>

  <div class="col-lg-10-harf col-md-10-harf col-sm-10-harf col-xs-9">
    <label>หมายเหตุ</label>
    <input type="text" class="form-control" name="remark" id="remark" maxlength="254" value="<?php echo $doc->Comments; ?>" disabled/>
  </div>
	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-3">
		<label>สถานะ</label>
		<input type="text" class="form-control text-center" value="<?php echo docStatusText($doc->CANCELED, $doc->DocStatus); ?>" disabled />
	</div>
</div>

<input type="hidden" id="id" value="<?php echo $doc->DocEntry; ?>" />

<?php if($doc->CANCELED === 'Y') { $this->load->view('cancle_watermark'); } ?>

<hr class="margin-bottom-10 margin-top-10"/>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
    <table class="table table-striped table-bordered border-1">
      <thead>
        <tr>
          <th class="fix-width-40 text-center">#</th>
          <th class="fix-width-200 text-center">Item Code</th>
          <th class="fix-width-300 text-center">Description</th>
          <th class="fix-width-100 text-center">From Whs</th>
          <th class="fix-width-100 text-center">To Whs</th>
          <th class="fix-width-100 text-center">Quantity</th>
					<th class="fix-width-100 text-center">Open Qty</th>
					<th class="min-width-100">Uom</th>
        </tr>
      </thead>
      <tbody>
    <?php if( ! empty($details)) : ?>
      <?php $no = 1; ?>
      <?php foreach($details as $rs) : ?>
        <tr id="row-<?php echo $no; ?>">
          <td class="middle text-center no"><?php echo $no; ?></td>
          <td class="middle"><?php echo $rs->ItemCode; ?></td>
          <td class="middle"><?php echo $rs->Dscription; ?></td>
          <td class="middle text-center"><?php echo $rs->FromWhsCod; ?></td>
          <td class="middle text-center"><?php echo $rs->WhsCode; ?></td>
          <td class="middle text-right"><?php echo number($rs->Quantity, 2); ?></td>
					<td class="middle text-right"><?php echo number($rs->OpenQty, 2); ?></td>
					<td class="middle"><?php echo $rs->UomCode; ?></td>
        </tr>
        <?php $no++; ?>
      <?php endforeach; ?>
    <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="<?php echo base_url(); ?>scripts/inventory/transfer_request/transfer_request.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>
