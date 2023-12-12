<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 padding-5 hidden-xs">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
	<div class="col-xs-12 padding-5 visible-xs">
		<h3 class="title-xs"><?php echo $this->title; ?></h3>
	</div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 padding-5">
    <p class="pull-right top-p">
      <?php if($this->pm->can_add) : ?>
        <button type="button" class="btn btn-sm btn-success" onclick="addNew()"><i class="fa fa-plus"></i> Add New</button>
      <?php endif; ?>
  </div>
</div><!-- End Row -->
<hr class=""/>

<div class="row" id="device-table">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="width-100 font-size-24 text-center" style="padding-top:100px; padding-bottom:100px;">
			No Device
		</div>
	</div>
</div>

<div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:400px; max-width:90vw;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title-site text-center" >เชื่อมต่อเครื่องชั่งใหม่</h4>
      </div>
      <div class="modal-body">
        <div class="row" style="margin-left:0; margin-right:0;">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <label>ชื่อเครื่องชั่ง</label>
            <input type="text" class="form-control input-sm" id="device-name" value="" />
          </div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
						<label>หน่วยวัด</label>
						<select class="form-control input-sm" id="device-unit">
							<option value="g" data-name="กรัม">กรัม</option>
							<option value="kg" data-name="กิโลกรัม">กิโลกรัม</option>
						</select>
					</div>
          <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <label>Baud Rate</label>
            <select class="form-control input-sm" id="device-baud-rate">
              <option value="110">110</option>
              <option value="200">200</option>
              <option value="300">300</option>
              <option value="600">600</option>
              <option value="1200">1200</option>
              <option value="1800">1800</option>
              <option value="2400" selected>2400</option>
              <option value="4800">4800</option>
              <option value="7200">7200</option>
							<option value="9600">9600</option>
							<option value="1440">1440</option>
							<option value="1920">1920</option>
            </select>
          </div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
						<label class="display-block not-show">submit</label>
						<button type="button" class="btn btn-xs btn-primary btn-block" id="open-close-btn" onclick="connect()">Connect</button>
						<button type="button" class="btn btn-xs btn-primary btn-block hide" id="reopen-btn" onclick="changeSettings()">Connect</button>
					</div>

          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <label class="display-block not-show">result</label>
						<textarea class="form-control text-center" id="result-message" disabled></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
				<button type="button" class="btn btn-xs btn-success btn-100" id="save-btn" onclick="saveAndClose()">Save and Close</button>
        <button type="button" class="btn btn-xs btn-default btn-100" onclick="disconectPort('add-modal')">Close</button>
      </div>
    </div>
  </div>
</div>

<script id="rows-template" type="text/x-handlebarsTemplate">
  {{#each this}}
		<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
			<div class="item-box">
				<div style="float:left; width:100px; margin-right:15px;">
					<img src="<?php echo base_url(); ?>images/weighing-scale.png" style="max-width:100%;"/>
				</div>
				<div class="discription">Name : {{deviceName}}</div>
				<div class="discription">Baud Rate : {{deviceBaudRate}}</div>
				<div class="discription">Unit : {{deviceUnitName}}</div>
				<?php if($this->pm->can_delete) : ?>
				<div class="discription text-right">
					<button type="button" class="btn btn-xs btn-danger" onclick="removeDevice('{{deviceId}}', '{{deviceName}}')">
					<i class="fa fa-trash"></i>&nbsp; ลบ
					</button>
				</div>
				<?php endif; ?>
			</div>
		</div>
  {{/each}}
</script>

<script src="<?php echo base_url(); ?>scripts/weighing_manchine/device.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>
