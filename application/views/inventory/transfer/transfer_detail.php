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
      <button type="button" class="btn btn-sm btn-default top-btn" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
    <?php if($doc->Status < 1) : ?>
      <button type="button" class="btn btn-sm btn-warning top-btn" onclick="edit(<?php echo $doc->id; ?>)"><i class="fa fa-pencil"></i> Edit</button>
    <?php endif; ?>
    <?php if(($doc->Status == 1) && $this->pm->can_delete) : ?>
      <button type="button" class="btn btn-sm btn-purple top-btn" onclick="rollback(<?php echo $doc->id; ?>, '<?php echo $doc->code; ?>')">ย้อนสถานะเอกสาร</button>
    <?php endif; ?>
    <?php if($doc->Status == 1 OR $doc->Status == 3) : ?>
      <button type="button" class="btn btn-sm btn-success top-btn" onclick="sendToSap(<?php echo $doc->id; ?>, '<?php echo $doc->code; ?>')">Send To SAP</button>
    <?php endif; ?>
    <!--
    <button type="button" class="btn btn-sm btn-info top-btn" onclick="printTransfer(<?php echo $doc->id; ?>)"><i class="fa fa-print"></i> Print</button>
  -->
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
    <input type="text" class="form-control input-sm text-center" id="doc_date" name="doc_date" value="<?php echo thai_date($doc->DocDate); ?>" disabled />
  </div>
  <div class="col-lg-1-harf col-md-3 col-sm-3 col-xs-6">
    <label>วันที่ครบกำหนด</label>
    <input type="text" class="form-control input-sm text-center" id="due_date" name="due_date" value="<?php echo thai_date($doc->DocDueDate); ?>" disabled />
  </div>
  <div class="col-lg-1-harf col-md-3 col-sm-3 col-xs-6">
    <label>Posting Date</label>
    <input type="text" class="form-control input-sm text-center" id="tax_date" name="tax_date" value="<?php echo thai_date($doc->TaxDate); ?>" disabled />
  </div>
  <div class="col-lg-3 col-md-5 col-sm-5 col-xs-6">
    <label>คลังต้นทาง</label>
    <select class="form-control input-sm" id="fromWhsCode" name="fromWhsCode" disabled>
      <?php echo select_warehouse($doc->fromWhsCode); ?>
    </select>
  </div>

  <div class="col-lg-3 col-md-5 col-sm-5 col-xs-6">
    <label>คลังปลายทาง</label>
    <select class="form-control input-sm" id="toWhsCode" name="toWhsCode" disabled>
      <?php echo select_warehouse($doc->toWhsCode); ?>
    </select>
  </div>

  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6">
    <label>User</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $doc->user; ?>" disabled/>
  </div>

  <div class="col-lg-6-harf col-md-7 col-sm-6-harf col-xs-6">
    <label>หมายเหตุ</label>
    <input type="text" class="form-control input-sm" maxlength="254" id="remark" value="<?php echo $doc->remark; ?>" disabled />
  </div>

  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6">
    <label>ใบขอโอนย้าย</label>
    <input type="text" class="form-control input-sm text-center" id="request-code" placeholder="Transfer Request" value="<?php echo $doc->BasePrefix.$doc->BaseRef; ?>" disabled/>
  </div>

  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6">
    <label>SAP No.</label>
    <input type="text" class="form-control input-sm text-center" id="sap-code" value="<?php echo $doc->DocNum; ?>" disabled/>
  </div>

  <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-6">
    <label>Status</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo statusLabel($doc->Status, $doc->approved); ?>" disabled/>
  </div>
  <input type="hidden" id="id" value="<?php echo $doc->id; ?>" />
  <input type="hidden" id="baseEntry" value="<?php echo $doc->BaseEntry; ?>" />
  <input type="hidden" id="baseRef" value="<?php echo $doc->BaseRef; ?>" />
</div>
<hr class="margin-top-15 margin-bottom-15">
<?php if($doc->Status == 0 && $doc->approved == 'R') : ?>
  <?php $this->load->view('reject_watermark'); ?>
<?php endif; ?>
<?php if($doc->Status == 2) : ?>
  <?php $this->load->view('cancle_watermark'); ?>
<?php endif; ?>
<?php $this->load->view('inventory/transfer/style'); ?>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
      <table class="table table-bordered border-1" style="min-width:1170px;">
        <thead>
          <tr class="freez">
            <th class="middle fix-width-50 text-center">#</th>
            <th class="middle fix-width-150 text-center">รหัสสินค้า</th>
            <th class="middle min-width-250 text-center">สินค้า</th>
            <th class="middle fix-width-150 text-center">จำนวนที่สั่ง</th>
            <th class="middle fix-width-150 text-center">จำนวนบรรจุ</th>
            <th class="fix-width-120 text-center">Lot/Receipt no.</th>
            <th class="fix-width-150 text-center">น้ำหนักที่ชัง</th>
            <th class="fix-width-150 text-center">ผู้ตรวจสอบ</th>
          </tr>
        </thead>
        <tbody>
    <?php if( ! empty($request_rows)) : ?>
      <?php $no = 1; ?>
      <?php foreach($request_rows as $rs) : ?>
        <?php $count = empty($rs->details) ? 0 : count($rs->details); ?>
        <?php $rowspan = $count > 1 ? "rowspan='{$count}'" : ""; ?>
            <tr>
              <td <?php echo $rowspan; ?> class="text-center no"><?php echo $no; ?></td>
              <td <?php echo $rowspan; ?> class=""><?php echo $rs->ItemCode; ?></td>
              <td <?php echo $rowspan; ?> class="" style="overflow:ellipsis;"><?php echo $rs->Dscription; ?></td>
              <td <?php echo $rowspan; ?> class="text-right">
              <?php if( ! empty($rs->OriginalQty)) : ?>
                <a class="pointer"
                tabindex="0"
                data-toggle="popover"
                data-container="body"
                data-title="Overwrite"
                data-trigger="focus"
                data-content="Original Qty : <?php echo number($rs->OriginalQty, 6); ?> <?php echo $rs->unitMsr; ?>
                <br/>Overwrite by : <?php echo $rs->OverwriteUser; ?>
                <br/>Overwrite on : <?php echo thai_date($rs->OverwriteTime, TRUE, '/'); ?>">
                *&nbsp;<?php echo number($rs->OpenQty, 6); ?>&nbsp;&nbsp;<?php echo $rs->unitMsr; ?>
              </a>
              <?php else : ?>
                  <?php echo number($rs->OpenQty, 6); ?>&nbsp;&nbsp;<?php echo $rs->unitMsr; ?>
                <?php endif; ?>
              </td>
              <td <?php echo $rowspan; ?> class="text-right">
                <span><?php echo number($rs->Qty, 6); ?></span>
                <?php echo $rs->unitMsr; ?>
              </td>
            <?php if($count > 1) : ?>
              <?php $row = 1; ?>
              <?php foreach($rs->details as $rd) : ?>
                <?php if($row > 1) : ?>
                <tr>
                <?php endif; ?>
                  <td class="text-center"><?php echo $rd->ReceiptNo; ?></td>
                  <td class="text-right"><?php echo number($rd->weight, 6); ?>&nbsp;<?php echo $rd->deviceUnit; ?></td>
                  <td class="text-center"><?php echo $rd->checker; ?></td>
                </tr>
                <?php $row++; ?>
              <?php endforeach; ?>
            <?php else : ?>
              <?php if( ! empty($rs->details)) : ?>
                <?php foreach($rs->details as $rd) : ?>
                    <td class="text-center"><?php echo $rd->ReceiptNo; ?></td>
                    <td class="text-right"><?php echo number($rd->weight, 6); ?>&nbsp;<?php echo $rd->deviceUnit; ?></td>
                    <td class="text-center"><?php echo $rd->checker; ?></td>
                <?php endforeach; ?>
              <?php else : ?>
                    <td class="text-center">-</td>
                    <td class="text-right">-</td>
                    <td class="text-center">-</td>
              <?php endif; ?>
              </tr>
            <?php endif; ?>
            <?php $no++; ?>
          <?php endforeach; ?>
    <?php endif; ?>

        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="row">
<?php if($doc->approved == 'R' && ! empty($doc->Message)) : ?>
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <p class="red">Rejected Reason : <?php echo $doc->Message; ?></p>
  </div>
<?php endif; ?>
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <?php if( ! empty($logs)) : ?>
      <?php foreach($logs as $log) : ?>
        <p class="p-logs"><?php echo "{$log->action} by {$log->uname} @ ".thai_date($log->date_upd, TRUE); ?></p>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<script>
$(function() {
  $('[data-toggle="popover"]').popover({html:true});
})

</script>
<script src="<?php echo base_url(); ?>/scripts/inventory/transfer/transfer.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
