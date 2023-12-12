<div class="modal fade" id="reject-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 <div class="modal-dialog" style="width:600px; max-width:95vw; margin-left:auto; margin-right:auto;">
   <div class="modal-content">
       <div class="modal-header">
       <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
       <h4 class="modal-title">ไม่อนุมัติ</h4>
      </div>
      <div class="modal-body">
        <div class="row" style="padding-left:12px; padding-right:12px;">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<label>เหตุผมที่ไม่อนุมัติ</label>
						<textarea id="reject-message" maxlength="254" rows="3" class="form-control" placeholder="กรุณาระบุเหตุผล"></textarea>
						<div class="err-label" id="reject-message-error"></div>
					</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-default btn-100" onclick="closeModal('reject-modal')">Close</button>
				<button type="button" id="btn-add" class="btn btn-sm btn-danger btn-100" onclick="doReject()">Reject</button>
      </div>
   </div>
 </div>
</div>
