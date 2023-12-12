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
      <button type="button" class="btn btn-sm btn-purple top-btn" onclick="showPrintSetting()"><i class="fa fa-cogs"></i>  เครื่องพิมพ์</button>
      <button type="button" class="btn btn-sm btn-purple top-btn" onclick="showDevice()"><i class="fa fa-cogs"></i>  เครื่องชั่ง</button>
    <?php if(($doc->Status == -1 OR $doc->Status == 0) && ($this->pm->can_add OR $this->pm->can_edit)) : ?>
      <button type="button" class="btn btn-sm btn-success top-btn" onclick="save()"><i class="fa fa-save"></i> บันทึก</button>
    <?php endif; ?>
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

  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6">
    <label>ใบขอโอนย้าย</label>
    <input type="text" class="form-control input-sm text-center" id="request-code" placeholder="Transfer Request" value="<?php echo $doc->BasePrefix.$doc->BaseRef; ?>" disabled/>
  </div>

  <div class="col-lg-8 col-md-8-harf col-sm-8-harf col-xs-6">
    <label>หมายเหตุ</label>
    <input type="text" class="form-control input-sm" maxlength="254" id="remark" value="<?php echo $doc->remark; ?>" placeholder="ระบุหมายเหตุ (ถ้ามี)"/>
  </div>

  <div class="col-lg-1 col-md-1-harf col-sm-1-harf col-xs-6">
    <label class="display-block not-show">load</label>
    <button type="button" class="btn btn-xs btn-success btn-block" onclick="update()">Update</button>
  </div>

  <input type="hidden" id="id" value="<?php echo $doc->id; ?>" />
  <input type="hidden" id="baseEntry" value="<?php echo $doc->BaseEntry; ?>" />
  <input type="hidden" id="baseRef" value="<?php echo $doc->BaseRef; ?>" />
  <input type="hidden" id="user-uid" value="<?php echo $this->_user->uid; ?>" />

  <?php $test = getConfig('TEST'); ?>

</div>
<hr class="margin-top-15 margin-bottom-15">
<div class="row">
  <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
    <label>รหัสวัตถุดิบ</label>
    <input type="text" class="form-control input-sm text-center" id="pd-code" autocomplete="off" autofocus/>
  </div>
  <div class="col-lg-2-harf col-md-7 col-sm-6-harf col-xs-12">
    <label>ชื่อวัตถุดิบ</label>
    <input type="text" class="form-control input-sm text-center" id="pd-name" autocomplete="off" readonly/>
  </div>

  <div class="col-lg-1-harf col-md-2 col-sm-2-harf col-xs-6">
    <label>เลขที่รับเข้า</label>
    <input type="text" class="form-control input-sm text-center" id="receipt-no" autocomplete="off" />
  </div>

  <div class="divider-hidden hidden-lg"></div>

  <div class="col-lg-2 col-md-3 col-sm-3-harf col-xs-6">
    <label>น้ำหนักสั่ง</label>
    <div class="input-group width-100">
      <input type="number" class="form-control input-sm text-right" id="request-qty" autocomplete="off" readonly style="padding-right:10px;" />
      <span class="input-group-addon" id="request-unit"></span>
    </div>
  </div>
  <div class="col-lg-2 col-md-3 col-sm-3-harf col-xs-6">
    <label>น้ำหนักชั่ง</label>
    <div class="input-group width-100">
      <input type="number" class="form-control input-sm text-right" id="input-qty" autocomplete="off" <?php echo ($test ? "" : "readonly"); ?> style="padding-right:10px;" />
      <span class="input-group-addon" id="device-unit"></span>
    </div>
  </div>
  <div class="divider-hidden visible-xs"></div>
  <div class="col-lg-2 col-md-3 col-sm-5 col-xs-6">
    <label>ผู้ตรวจสอบ</label>
    <input type="text" class="form-control input-sm text-center" id="checker-uid" />
  </div>

  <input type="hidden" id="LineNum" value="" />
  <input type="hidden" id="uomEntry" value="" />
  <input type="hidden" id="uomCode" value="" />
  <input type="hidden" id="unitMsr" value="" />
  <input type="hidden" id="numPerMsr" value="" />
  <input type="hidden" id="uomEntry2" value="" />
  <input type="hidden" id="uomCode2" value="" />
  <input type="hidden" id="unitMsr2" value="" />
  <input type="hidden" id="numPerMsr2" value="" />

  <!-- ตั้งค่าหน่วยของเครื่องขั่งเป็น กรัม หรือ กิโลกรัม -->
  <input type="hidden" id="deviceUom" value="" />  <!-- g or kg -->
  <input type="hidden" id="printAfterCheck" value="1" />
</div>
<hr class="margin-top-15 margin-bottom-15">
<?php $this->load->view('inventory/transfer/style'); ?>

<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-5">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 check-title" >รายการชั่ง</div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 check-table">
      <table class="table table-striped tableFixHead border-1" style="min-width:440px;">
        <thead>
          <tr class="freez">
            <th class="width-35">รหัสวัตถุดิบ</th>
            <th class="width-20">เลขที่รับเข้า</th>
            <th class="width-30 text-right">จำนวนบรรจุ</th>
            <th class="width-15 text-center"></th>
          </tr>
        </thead>
        <tbody id="details-table">
      <?php if( ! empty($details)) : ?>
        <?php foreach($details as $rs) : ?>
          <tr id="row-<?php echo $rs->LineNum; ?>">
            <td><?php echo $rs->ItemCode; ?></td>
            <td><?php echo $rs->ReceiptNo; ?></td>
            <td class="text-right">
              <?php echo number($rs->Qty, 6); ?>&nbsp;<?php echo $rs->unitMsr; ?>
            </td>
            <td class="text-right">
              <button type="button" class="btn btn-minier btn-danger"
                onclick="removeRow(<?php echo $rs->LineNum; ?>, '<?php echo $rs->ItemCode; ?>')"
                title="ลบรายการ"><i class="fa fa-trash"></i>
              </button>
              <button type="button" class="btn btn-minier btn-info"
                onclick="printLabel(<?php echo $doc->id; ?>, <?php echo $rs->LineNum; ?>)"
                title="พิมพ์สติ๊กเกอร์"><i class="fa fa-print"></i></button>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="divider-hidden visible-xs"></div>
  <div class="divider-hidden visible-xs"></div>
  <div class="divider-hidden visible-xs"></div>

  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-5">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 check-title">ขอโอน</div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 check-table">
      <table class="table table-striped border-1 tableFixHead" style="min-width:350px;">
        <thead>
          <tr class="freez">
            <th class="width-40">รหัสสินค้า</th>
            <th class="width-25 text-right">จำนวนที่สั่ง</th>
            <th class="width-25 text-right">จำนวนบรรจุ</th>
            <?php if($pm) : ?>
              <th class="width-10 text-center"></th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody id="request-table">
          <tr id="head" class="hide"><td colspan="3"></td></tr>
    <?php if( ! empty($request_rows)) : ?>
      <?php foreach($request_rows as $rs) : ?>
        <?php $change = $rs->OriginalQty > 0 ? 'blue' : ''; ?>
        <?php $full = $rs->OpenQty == $rs->Qty ? '' : 'hide'; ?>
            <tr id="line-no-<?php echo $rs->LineNum; ?>" class="request-row <?php echo $change; ?>" data-linenum="<?php echo $rs->LineNum; ?>">
              <input type="hidden" id="req-qty-<?php echo $rs->LineNum; ?>" value="<?php echo $rs->OpenQty; ?>" />
              <input type="hidden" id="req-unit-<?php echo $rs->LineNum; ?>" value="<?php echo $rs->unitMsr; ?>" />
              <input type="hidden" id="req-item-<?php echo $rs->LineNum; ?>" value="<?php echo $rs->ItemCode; ?>" />
              <input type="hidden" id="input-qty-<?php echo $rs->LineNum; ?>" value="<?php echo $rs->Qty; ?>" />

              <td>
                <?php echo $rs->ItemCode; ?>
                <i class="fa fa-check green <?php echo $full; ?>" id="check-<?php echo $rs->LineNum; ?>"></i>
              </td>
              <td class="text-right">
                <span id="open-qty-<?php echo $rs->LineNum; ?>"><?php echo number($rs->OpenQty, 6); ?></span>
                <?php echo $rs->unitMsr; ?>
              </td>
              <td class="text-right">
                <span id="qty-<?php echo $rs->LineNum; ?>"><?php echo number($rs->Qty, 6); ?></span>
                <?php echo $rs->unitMsr; ?>
              </td>
              <?php if($pm) : ?>
                <td class="text-center">
                  <button type="button"
                    class="btn btn-minier btn-warning"
                    id="btn-edit-qty-<?php echo $rs->LineNum; ?>"
                    onclick="editRequestQty(<?php echo $rs->LineNum; ?>, <?php echo $rs->OpenQty; ?>)">
                    <i class="fa fa-pencil"></i>
                  </button>
                </td>
              <?php endif; ?>
            </tr>
            <?php endforeach; ?>
    <?php endif; ?>

        </tbody>
      </table>
    </div>
  </div>
</div>

<script id="row-template" type="text/x-handlebarsTemplate">
  <tr id="row-{{LineNum}}">
    <td>{{ItemCode}}</td>
    <td>{{ReceiptNo}}</td>
    <td class="text-right">{{QtyLabel}}&nbsp;{{deviceUnit}}</td>
    <td class="text-right">
      <button type="button" class="btn btn-minier btn-danger"
        onclick="removeRow({{LineNum}}, '{{ItemCode}}')"
        title="ลบรายการ"><i class="fa fa-trash"></i></button>
      <button type="button" class="btn btn-minier btn-info"
        onclick="printLabel(<?php echo $doc->id; ?>, {{LineNum}})"
        title="พิมพ์สติ๊กเกอร์"><i class="fa fa-print"></i></button>
    </td>
  </tr>
</script>

<?php $this->load->view('inventory/transfer/device_modal'); ?>

<script src="<?php echo base_url(); ?>/scripts/inventory/transfer/serialPort.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>/scripts/inventory/transfer/transfer.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>/scripts/inventory/transfer/transfer_add.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>/scripts/inventory/transfer/transfer_control.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>/scripts/inventory/transfer/print_setting.js?v=<?php echo date('Ymd'); ?>"></script>
<script src="<?php echo base_url(); ?>/scripts/beep.js"></script>
<?php $this->load->view('include/footer'); ?>
