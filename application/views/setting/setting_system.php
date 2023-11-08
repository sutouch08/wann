<?php
    $open     = $CLOSE_SYSTEM == 0 ? 'btn-success' : '';
    $close    = $CLOSE_SYSTEM == 1 ? 'btn-danger' : '';
    $freze    = $CLOSE_SYSTEM == 2 ? 'btn-warning' : '';
		$strongOn = $USE_STRONG_PWD == 1 ? 'btn-primary' : '';
		$strongOff = $USE_STRONG_PWD == 0 ? 'btn-primary' : '';
		$disable = $this->_SuperAdmin ? "" : "disabled";
    $log_on = $LOGS_JSON == 1 ? 'btn-primary' : '';
    $log_off = $LOGS_JSON == 1 ? '' : 'btn-primary';
    $test_on = $TEST == 1 ? 'btn-primary' : '';
    $test_off = $TEST == 1 ? '' : 'btn-primary';
?>

  <form id="systemForm" method="post" action="<?php echo $this->home; ?>/update_config">
    <div class="row">
    	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><span class="form-control left-label">ปิดระบบ</span></div>
      <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
      	<div class="btn-group input-xlarge">
        	<button type="button" class="btn btn-sm <?php echo $open; ?>" style="width:33%;" id="btn-open" onClick="openSystem()" <?php echo $disable; ?>>เปิด</button>
          <button type="button" class="btn btn-sm <?php echo $close; ?>" style="width:33%;" id="btn-close" onClick="closeSystem()" <?php echo $disable; ?>>ปิด</button>
          <button type="button" class="btn btn-sm <?php echo $freze; ?>" style="width:34%" id="btn-freze" onclick="frezeSystem()" <?php echo $disable; ?>>ดูอย่างเดียว</button>
        </div>
        <span class="help-block">กรณีปิดระบบจะไม่สามารถเข้าใช้งานระบบได้ในทุกส่วน โปรดใช้ความระมัดระวังในการกำหนดค่านี้</span>
      	<input type="hidden" name="CLOSE_SYSTEM" id="closed" value="<?php echo $CLOSE_SYSTEM; ?>" />
      </div>
      <div class="divider-hidden"></div>

    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><span class="form-control left-label">Strong Password</span></div>
    <div class="col-lg-5 col-md-9 col-sm-9 col-xs-12">
      <div class="btn-group input-medium">
        <button type="button" class="btn btn-sm <?php echo $strongOn; ?>" style="width:50%;" id="btn-strong-on" onClick="toggleStrongPWD(1)">เปิด</button>
        <button type="button" class="btn btn-sm <?php echo $strongOff; ?>" style="width:50%;" id="btn-strong-off" onClick="toggleStrongPWD(0)">ปิด</button>
      </div>
      <span class="help-block">เมื่อเปิดใช้งาน การกำหนดรหัสผ่านจะต้องประกอบด้วย ตัวอักษรภาษาอังกฤษ พิมพ์ใหญ่ พิมพ์เล็ก ตัวเลข และสัญลักษณ์พิเศษ อย่างน้อย อย่างล่ะ 1 ตัว และต้องมีความยาว 8 ตัวอักษรขึ้นไป</span>
      <input type="hidden" name="USE_STRONG_PWD" id="use-strong-pwd" value="<?php echo $USE_STRONG_PWD; ?>" />
    </div>
    <div class="divider-hidden"></div>


		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><span class="form-control left-label">Password Age</span></div>
    <div class="col-lg-7 col-md-9 col-sm-9 col-xs-12">
      <input type="number" class="form-control input-sm input-mini text-center" name="USER_PASSWORD_AGE" id="pwd-age" value="<?php echo $USER_PASSWORD_AGE; ?>" />
      <span class="help-block">กำหนดอายุของรหัสผ่าน(วัน) User จำเป็นต้องเปลี่ยนรหัสผ่านหากรหัสผ่านหมดอายุ</span>
    </div>
    <div class="divider-hidden"></div>

		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><span class="form-control left-label">Interface API Endpoint</span></div>
    <div class="col-lg-5 col-md-7 col-sm-7 col-xs-12">
      <input type="text" class="form-control input-sm" name="SAP_API_HOST" id="" value="<?php echo $SAP_API_HOST; ?>" />
      <span class="help-block">กำหนด URL endpoint สำหรับการ Interface กับ ระบบ SAP</span>
    </div>
    <div class="divider-hidden"></div>

    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><span class="form-control left-label">Save Interface Logs</span></div>
    <div class="col-lg-5 col-md-9 col-sm-9 col-xs-12">
      <div class="btn-group input-medium">
        <button type="button" class="btn btn-sm <?php echo $log_on; ?>" style="width:50%;" id="btn-logs-on" onClick="toggleLogsJson(1)">เปิด</button>
        <button type="button" class="btn btn-sm <?php echo $log_off; ?>" style="width:50%;" id="btn-logs-off" onClick="toggleLogsJson(0)">ปิด</button>
      </div>
      <span class="help-block">บันทึก Interface logs ไว้เพื่อตรวจสอบหรือไม่ หากเปิดไว้จะเก็บข้อมูลการ Interface ไว้ใน database ทำให้มีการใช้งาน disk มากขึ้น</span>
      <input type="hidden" name="LOGS_JSON" id="logs-json" value="<?php echo $LOGS_JSON; ?>" />
    </div>
    <div class="divider-hidden"></div>

    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><span class="form-control left-label">Test Interface</span></div>
    <div class="col-lg-5 col-md-9 col-sm-9 col-xs-12">
      <div class="btn-group input-medium">
        <button type="button" class="btn btn-sm <?php echo $test_on; ?>" style="width:50%;" id="btn-test-on" onClick="toggleTestMode(1)">เปิด</button>
        <button type="button" class="btn btn-sm <?php echo $test_off; ?>" style="width:50%;" id="btn-test-off" onClick="toggleTestMode(0)">ปิด</button>
      </div>
      <span class="help-block">เมื่อเปิด Test Mode ระบบจะไม่ส่งข้อมูลไปที่ SAP แต่จะสร้างเลขที่เอกสารหลอกขี้นมาแทน</span>
      <input type="hidden" name="TEST" id="test-mode" value="<?php echo $TEST; ?>" />
    </div>
    <div class="divider-hidden"></div>

		<div class="divider-hidden"></div>
		<div class="divider-hidden"></div>


      <div class="col-lg-9 col-md-9 col-sm-9 col-lg-offset-3 col-md-offset-3 col-sm-offset-3 hidden-xs">
        <?php if($this->pm->can_add OR $this->pm->can_edit) : ?>
      	<button type="button" class="btn btn-sm btn-success btn-100" onClick="updateConfig('systemForm')"><i class="fa fa-save"></i> บันทึก</button>
        <?php endif; ?>
      </div>
			<div class="col-xs-12 text-center visible-xs">
        <?php if($this->pm->can_add OR $this->pm->can_edit) : ?>
      	<button type="button" class="btn btn-sm btn-success btn-100" onClick="updateConfig('systemForm')"><i class="fa fa-save"></i> บันทึก</button>
        <?php endif; ?>
      </div>
      <div class="divider-hidden"></div>

    </div><!--/row-->
  </form>
