
	<form id="documentForm" method="post" action="<?php echo $this->home; ?>/update_config">
    <div class="row">
			<div class="col-lg-2 col-md-3 col-sm-4 hidden-xs"><h4 class="margin-top-30">Transfer</h4></div>
			<div class="col-xs-12 visible-xs"><h4 class="title-xs">Transfer</h4></div>
      <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
				<label>Prefix</label>
				<input type="text" class="form-control input-sm text-center prefix"
				maxlength="3" name="PREFIX_TRANSFER" onkeyup="validPrefix(this)"
				value="<?php echo $PREFIX_TRANSFER; ?>"/>
			</div>
      <div class="col-lg-1-harf col-md-1-harf col-sm-2 col-xs-6 padding-5">
				<label>Running</label>
				<select class="form-control input-sm" name="RUN_DIGIT_TRANSFER">
					<option value="3" <?php echo is_selected('3', $RUN_DIGIT_TRANSFER); ?>>3</option>
					<option value="4" <?php echo is_selected('4', $RUN_DIGIT_TRANSFER); ?>>4</option>
					<option value="5" <?php echo is_selected('5', $RUN_DIGIT_TRANSFER); ?>>5</option>
					<option value="6" <?php echo is_selected('6', $RUN_DIGIT_TRANSFER); ?>>6</option>
					<option value="7" <?php echo is_selected('7', $RUN_DIGIT_TRANSFER); ?>>7</option>
				</select>
			</div>
		</div>

		<div class="divider-hidden"></div>
		<div class="divider-hidden"></div>



		<div class="row">
      <div class="divider-hidden"></div>
			<div class="divider-hidden"></div>
			<div class="divider-hidden"></div>
			<div class="divider-hidden"></div>
			<div class="divider-hidden"></div>
			<div class="divider-hidden"></div>
      <div class="col-lg-5 col-md-6 col-sm-8 hidden-xs padding-5 text-right">
			<?php if($this->pm->can_edit OR $this->pm->can_add) : ?>
      	<button type="button" class="btn btn-sm btn-success input-small" onClick="checkDocumentSetting()"><i class="fa fa-save"></i> บันทึก</button>
			<?php endif; ?>
      </div>
			<div class="col-xs-12 visible-xs padding-5 text-center">
			<?php if($this->pm->can_edit OR $this->pm->can_add) : ?>
      	<button type="button" class="btn btn-sm btn-success btn-block" onClick="checkDocumentSetting()"><i class="fa fa-save"></i> บันทึก</button>
			<?php endif; ?>
      </div>
      <div class="divider-hidden"></div>
		</div>
  </form>
