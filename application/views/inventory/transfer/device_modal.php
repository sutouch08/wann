<div class="modal fade" id="print-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:800px; max-width:90vw;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title-site text-center" >ตั้งค่าหน้ากระดาษ</h4>
      </div>
      <div class="modal-body">
        <div class="row" style="margin-left:0; margin-right:0;">
          <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
            <label>กว้าง&nbsp;</label>
            <div class="input-group">
              <input type="number" class="form-control input-mini text-center" id="sticker-width" onkeyup="stickWidth()" value="105" />
              <span class="input-group-addon">&nbsp;mm.</span>
            </div>
          </div>

          <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
            <label>สูง&nbsp;</label>
            <div class="input-group">
              <input type="number" class="form-control input-mini text-center" id="sticker-height" onkeyup="stickerHeight()" value="32" />
              <span class="input-group-addon">&nbsp;mm.</span>
            </div>
          </div>

          <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
            <label>ขอบบน&nbsp;</label>
            <div class="input-group">
              <input type="number" class="form-control input-mini text-center" id="sticker-padding-top" onkeyup="stickerTop()" value="1" />
              <span class="input-group-addon">&nbsp;mm.</span>
            </div>
          </div>

          <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
            <label>ขอบล่าง&nbsp;</label>
            <div class="input-group">
              <input type="number" class="form-control input-mini text-center" id="sticker-padding-bottom" onkeyup="stickerBottom()" value="1" />
              <span class="input-group-addon">&nbsp;mm.</span>
            </div>
          </div>

          <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
            <label>ขอบซ้าย</label>
            <div class="input-group">
              <input type="number" class="form-control input-mini text-center" id="sticker-padding-left" onkeyup="stickerLeft()" value="2" />
              <span class="input-group-addon">&nbsp;mm.</span>
            </div>
          </div>

          <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
            <label>ขอบขวา</label>
            <div class="input-group">
              <input type="number" class="form-control input-mini text-center" id="sticker-padding-right" onkeyup="stickerRight()" value="2" />
              <span class="input-group-addon">&nbsp;mm.</span>
            </div>
          </div>

          <div class="divider-hidden"></div>

          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="divider-hidden"></div>

            <div class="col-lg-12 view-port">
              <div class="" id="sticker">
                <div class="sticker-label">
                  <div class="sticker-content">
                    <table class="width-100">
                      <tr><td colspan="2" class="text-center">ฉลากวัตถุดิบ</td></tr>
                      <tr>
                        <td class="width-40">รหัสวัตถุดิบ</td>
                        <td class="width-60">OS-1-0073</td>
                      </tr>
                      <tr>
                        <td>เลขที่รับ</td>
                        <td>R0750/23</td>
                      </tr>
                      <tr>
                        <td>รหัสผลิตภัณฑ์</td>
                        <td>11-000-018</td>
                      </tr>
                      <tr>
                        <td>Lot No.</td>
                        <td>230011</td>
                      </tr>
                      <tr>
                        <td>น้ำหนักวัตถุดิบ</td>
                        <td>2,010.00 g</td>
                      </tr>
                    </table>
                    <table class="width-100">
                      <tr>
                        <td class="width-50">ผู้ชั่ง  มารวย</td>
                        <td class="width-50">ผู้ตรวจ  อรรถสิทธิ์</td>
                      </tr>
                      <tr>
                        <td class="width-50">วันที่  31/03/2323 09:19</td>
                        <td class="width-50 text-right">FM-SP-14/00</td>
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
                        <td class="width-60">OS-1-0073</td>
                      </tr>
                      <tr>
                        <td>เลขที่รับ</td>
                        <td>R0750/23</td>
                      </tr>
                      <tr>
                        <td>รหัสผลิตภัณฑ์</td>
                        <td>11-000-018</td>
                      </tr>
                      <tr>
                        <td>Lot No.</td>
                        <td>230011</td>
                      </tr>
                      <tr>
                        <td>น้ำหนักวัตถุดิบ</td>
                        <td>2,010.00 g</td>
                      </tr>
                    </table>
                    <table class="width-100">
                      <tr>
                        <td class="width-50">ผู้ชั่ง  มารวย</td>
                        <td class="width-50">ผู้ตรวจ  อรรถสิทธิ์</td>
                      </tr>
                      <tr>
                        <td class="width-50">วันที่  31/03/2323 09:19</td>
                        <td class="width-50 text-right">FM-SP-14/00</td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="divider-hidden"></div>
          <div class="divider-hidden"></div>
          <div class="divider-hidden"></div>

          <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
            <label>ขอบในบน&nbsp;</label>
            <div class="input-group">
              <input type="number" class="form-control input-mini text-center" id="label-padding-top" onkeyup="labelTop()" value="2" />
              <span class="input-group-addon">&nbsp;mm.</span>
            </div>
          </div>

          <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
            <label>ขอบในล่าง&nbsp;</label>
            <div class="input-group">
              <input type="number" class="form-control input-mini text-center" id="label-padding-bottom" onkeyup="labelBottom()" value="2" />
              <span class="input-group-addon">&nbsp;mm.</span>
            </div>
          </div>

          <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
            <label>ขอบในซ้าย</label>
            <div class="input-group">
              <input type="number" class="form-control input-mini text-center" id="label-padding-left" onkeyup="labelLeft()" value="2" />
              <span class="input-group-addon">&nbsp;mm.</span>
            </div>
          </div>

          <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
            <label>ขอบในขวา</label>
            <div class="input-group">
              <input type="number" class="form-control input-mini text-center" id="label-padding-right" onkeyup="labelRight()" value="2" />
              <span class="input-group-addon">&nbsp;mm.</span>
            </div>
          </div>
          <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
            <label>ระยะห่าง</label>
            <div class="input-group">
              <input type="number" class="form-control input-mini text-center" id="label-space" onkeyup="labelSpace()" value="0" />
              <span class="input-group-addon">&nbsp;mm.</span>
            </div>
          </div>

          <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">
            <label>ขนาดตัวอักษร</label>
            <div class="input-group">
              <input type="number" class="form-control input-mini text-center" id="label-font-size" onkeyup="labelFontSize()" value="8" />
              <span class="input-group-addon">&nbsp;px.</span>
            </div>
          </div>
          <div class="divider"></div>

          <div class="col-lg-4 col-md-4 col-sm-4">
            <label>
              <input type="checkbox" class="ace" id="print-after-check" value="1" checked/>
              <span class="lbl">Print after check</span>
            </label>
          </div>

          <div class="col-lg-4 col-md-4 col-sm-4">
            <label>
              <input type="checkbox" class="ace" id="print-preview" value="1" checked/>
              <span class="lbl">Preview before print</span>
            </label>
          </div>

          <div class="col-lg-4 col-md-4 col-sm-4">
            <label>
              <input type="checkbox" class="ace" id="close-after-print" value="1" checked/>
              <span class="lbl">Close after print</span>
            </label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-info btn-100" onclick="testPrint()">Test Print</button>
        <button type="button" class="btn btn-sm btn-default btn-100" onclick="getDefault()">ค่าเริ่มต้น</button>
        <button type="button" class="btn btn-sm btn-primary btn-100" onclick="saveSetting()">บันทึก</button>
      </div>
    </div>
  </div>
</div>




<div class="modal fade" id="devices-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:500px; max-width:90vw;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title-site text-center" >เชื่อมต่อเครื่องชั่ง</h4>
      </div>
      <div class="modal-body">
        <div class="row" id="device-table" style="margin-left:0; margin-right:0; max-height:400px; overflow:auto;">

        </div>
      </div>
    </div>
  </div>
</div>

<script id="device-template" type="text/x-handlebarsTemplate">
  {{#each this}}
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
			<div class="item-box">
				<div class="image">
					<img src="<?php echo base_url(); ?>images/weighing-scale.png" style="max-width:100%"/>
				</div>
				<div class="discription">Name : {{deviceName}}</div>
        <div class="discription">Port : {{devicePortName}}</div>
				<div class="discription">Baud Rate : {{deviceBaudRate}}</div>
        <div class="discription">Unit : {{deviceUnitName}}</div>
				<div class="divider"></div>
				<div class="discription">
					<button type="button" class="btn btn-lg btn-primary btn-block" onclick="setActiveDevice('{{deviceId}}', '{{deviceBaudRate}}', '{{deviceUnit}}', '{{devicePort}}')">เชื่อมต่อ</button>
				</div>
			</div>
		</div>
  {{/each}}
</script>


<div class="modal fade" id="overwrite-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:300px; max-width:90vw;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title-site text-center" >แก้ไขจำนวน</h4>
      </div>
      <div class="modal-body">
        <div class="row" style="margin-left:0; margin-right:0;">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <label>รหัสวัตถุดิบ</label>
            <input type="text" class="form-control input-sm" id="edit-item-code" value="" readonly/>
            <input type="hidden" id="edit-line-num" value="" />
            <input type="hidden" id="origin-qty" value="" />
            <input type="hidden" id="check-qty" value="" />
          </div>
          <div class="divider-hidden"></div>
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <label>น้ำหนัก</label>
            <div class="input-group width-100">
              <input type="number" class="form-control input-sm" id="edit-weight" value="" />
              <span class="input-group-addon" id="edit-unit"></span>
            </div>
          </div>
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 red padding-top-15" id="error-label">

          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-xs btn-default btn-100" onclick="closeModal('overwrite-modal')">Cancel</button>
				<button type="button" class="btn btn-xs btn-success btn-100" onclick="confirmEdit()">Update</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="approve-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:300px; max-width:90vw;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title-site text-center">อนุมัติการแก้ไข</h4>
      </div>
      <div class="modal-body">
        <div class="row" style="margin-left:0; margin-right:0;">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <input type="password" class="form-control input-sm text-center" id="s-code" placeholder="รหัสอนุมัติ"/>
          </div>
          <div class="divider-hidden"></div>
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 red padding-top-15" id="approve-error">

          </div>
        </div>
      </div>
      <div class="modal-footer text-center">
				<button type="button" class="btn btn-xs btn-primary btn-100" onclick="confirmOverwrite()">ยืนยัน</button>
      </div>
    </div>
  </div>
</div>
