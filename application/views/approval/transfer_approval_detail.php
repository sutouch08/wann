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
    </p>
  </div>
</div>
<hr>
<div class="row">
  <div class="col-lg-1-harf col-md-3 col-sm-3 col-xs-6">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo $doc->code; ?>" disabled />
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

  <div class="col-lg-8 col-md-9 col-sm-8-harf col-xs-6">
    <label>หมายเหตุ</label>
    <input type="text" class="form-control input-sm" maxlength="254" id="remark" value="<?php echo $doc->remark; ?>" disabled />
  </div>

  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6">
    <label>ใบขอโอนย้าย</label>
    <input type="text" class="form-control input-sm text-center" id="request-code" placeholder="Transfer Request" value="<?php echo $doc->BasePrefix.$doc->BaseRef; ?>" disabled/>
  </div>

  <div class="col-lg-1 col-md-1 col-sm-1-harf col-xs-6">
    <label>Status</label>
    <input type="text" class="form-control input-sm text-center" value="<?php echo statusLabel($doc->Status, $doc->approved); ?>" disabled/>
  </div>
  <input type="hidden" id="id" value="<?php echo $doc->id; ?>" />

  <input type="hidden" id="code" value="<?php echo $doc->code; ?>" />
  <input type="hidden" id="baseEntry" value="<?php echo $doc->BaseEntry; ?>" />
  <input type="hidden" id="baseRef" value="<?php echo $doc->BaseRef; ?>" />
</div>
<hr class="margin-top-15 margin-bottom-15">
<?php $this->load->view('inventory/transfer/style'); ?>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
      <table class="table table-bordered border-1" style="min-width:1220px;">
        <thead>
          <tr class="freez">
            <th class="middle fix-width-50 text-center">#</th>
            <th class="middle fix-width-150 text-center">รหัสสินค้า</th>
            <th class="middle min-width-300 text-center">สินค้า</th>
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
<div class="divider"></div>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
    <button type="button" class="btn btn-sm btn-danger btn-100" onclick="rejectReason()">Reject</button>
    <button type="button" class="btn btn-sm btn-primary btn-100" onclick="doApprove()">Approve</button>
  </div>
</div>

<?php $this->load->view('approval/reject_modal'); ?>

<script>
$(function() {
  $('[data-toggle="popover"]').popover({html:true});
})

</script>
<script src="<?php echo base_url(); ?>/scripts/approval/transfer_approval.js?v=<?php echo date('Ymd'); ?>"></script>
<?php $this->load->view('include/footer'); ?>
