
  <form id="inventoryForm" method="post" action="<?php echo $this->home; ?>/update_config">
    <div class="row">

    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><span class="form-control left-label">คลังชั่งสาร</span></div>
    <div class="col-lg-5 col-md-9 col-sm-9 col-xs-12">
      <select class="form-control input-sm" name="REQUEST_WAREHOUSE">
        <option value="">เลือกคลัง</option>
        <?php echo select_warehouse($REQUEST_WAREHOUSE); ?>
      </select>
      <span class="help-block">กำหนดรหัสคลังที่ใช้เป็นคลังสำหรับชั่งสารเคมี</span>
    </div>
    <div class="divider-hidden"></div>
		<div class="divider-hidden"></div>
		<div class="divider-hidden"></div>


      <div class="col-lg-9 col-md-9 col-sm-9 col-lg-offset-3 col-md-offset-3 col-sm-offset-3 hidden-xs">
        <?php if($this->pm->can_add OR $this->pm->can_edit) : ?>
      	<button type="button" class="btn btn-sm btn-success btn-100" onClick="updateConfig('inventoryForm')"><i class="fa fa-save"></i> บันทึก</button>
        <?php endif; ?>
      </div>
			<div class="col-xs-12 text-center visible-xs">
        <?php if($this->pm->can_add OR $this->pm->can_edit) : ?>
      	<button type="button" class="btn btn-sm btn-success btn-100" onClick="updateConfig('inventoryForm')"><i class="fa fa-save"></i> บันทึก</button>
        <?php endif; ?>
      </div>
      <div class="divider-hidden"></div>

    </div><!--/row-->
  </form>
