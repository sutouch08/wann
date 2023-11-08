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
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
<div class="row">
  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Web No</label>
    <input type="text" class="form-control input-sm search-box" name="code"  value="<?php echo $code; ?>" />
  </div>

  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Request No</label>
    <input type="text" class="form-control input-sm search-box" name="BaseRef"  value="<?php echo $BaseRef; ?>" />
  </div>

  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>Transfer No</label>
    <input type="text" class="form-control input-sm search-box" name="DocNum"  value="<?php echo $DocNum; ?>" />
  </div>

  <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6 padding-5">
    <label>คลังต้นทาง</label>
    <select class="form-control input-sm filter" name="fromWhs">
      <option value="all">ทั้งหมด</option>
      <?php echo select_warehouse($fromWhs); ?>
    </select>
  </div>

	<div class="col-lg-2 col-md-3 col-sm-3 col-xs-6 padding-5">
    <label>คลังปลายทาง</label>
    <select class="form-control input-sm filter" name="toWhs">
      <option value="all">ทั้งหมด</option>
      <?php echo select_warehouse($toWhs); ?>
    </select>
  </div>


	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-6 padding-5">
    <label>สถานะ</label>
    <select class="form-control input-sm" name="status" onchange="getSearch()">
			<option value="all">ทั้งหมด</option>
			<option value="-1" <?php echo is_selected('-1', $status); ?>>Draft</option>
      <option value="0" <?php echo is_selected('0', $status); ?>>Pending</option>
      <option value="1" <?php echo is_selected('1', $status); ?>>Success</option>
      <option value="2" <?php echo is_selected('2', $status); ?>>Canceled</option>
      <option value="3" <?php echo is_selected('3', $status); ?>>Failed</option>
		</select>
  </div>

	<div class="col-lg-2 col-md-2-harf col-sm-2-harf col-xs-6 padding-5">
    <label>วันที่</label>
    <div class="input-daterange input-group">
      <input type="text" class="form-control input-sm width-50 text-center from-date" name="from_date" id="fromDate" value="<?php echo $from_date; ?>" />
      <input type="text" class="form-control input-sm width-50 text-center" name="to_date" id="toDate" value="<?php echo $to_date; ?>" />
    </div>
  </div>

  <div class="col-lg-1-harf col-md-2 col-sm-2 col-xs-6 padding-5">
    <label>User</label>
    <input type="text" class="form-control input-sm search-box" name="user"  value="<?php echo $user; ?>" />
  </div>

  <div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block not-show">buton</label>
    <button type="submit" class="btn btn-xs btn-primary btn-block"><i class="fa fa-search"></i> Search</button>
  </div>
	<div class="col-lg-1-harf col-md-1-harf col-sm-1-harf col-xs-3 padding-5">
    <label class="display-block not-show">buton</label>
    <button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
  </div>
</div>
<hr class="margin-top-15">
</form>
<?php echo $this->pagination->create_links(); ?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 table-responsive">
		<table class="table table-striped border-1" style="min-width:840px;">
			<thead>
				<tr>
					<th class="fix-width-60 middle"></th>
					<th class="fix-width-50 middle text-center">ลำดับ</th>
					<th class="fix-width-100 middle text-center">วันที่</th>
					<th class="fix-width-150 middle">เลขที่เอกสาร</th>
          <th class="fix-width-40 middle">สถานะ</th>
					<th class="fix-width-100 middle">ต้นทาง</th>
					<th class="fix-width-100 middle">ปลายทาง</th>
          <th class="fix-width-120 middle">Request No.</th>
          <th class="fix-width-120 middle">Transfer No.</th>
				</tr>
			</thead>
			<tbody>
        <?php if(!empty($data)) : ?>
          <?php $no = $this->uri->segment(4) + 1; ?>
          <?php foreach($data as $rs) : ?>
            <tr id="row-<?php echo $rs->id; ?>">
							<td class="middle text-right">
								<button type="button" class="btn btn-minier btn-info" onclick="viewDetail('<?php echo $rs->id; ?>')"><i class="fa fa-eye"></i></button>
                <button type="button" class="btn btn-minier btn-warning" onclick="edit(<?php echo $rs->id; ?>)"><i class="fa fa-pencil"></i></button>
                <button type="button" class="btn btn-minier btn-danger" onclick="confirmDelete(<?php echo $rs->id; ?>, '<?php echo $rs->code; ?>')"><i class="fa fa-trash"></i></button>
							</td>
              <td class="middle text-center"><?php echo $no; ?></td>
              <td class="middle text-center"><?php echo thai_date($rs->DocDate); ?></td>
              <td class="middle"><?php echo $rs->code; ?></td>
              <td class="middle">
                <?php if($rs->Status == -1) : ?>
                  <span class="purple">Draft</span>
                <?php elseif($rs->Status == 0) : ?>
                  <span class="orange">Pending</span>
                <?php elseif($rs->Status == 1) : ?>
                  <span class="green">Success</span>
                <?php elseif($rs->Status == 2) : ?>
                  <span class="grey">Canceled</span>
                <?php elseif($rs->Status == 3) : ?>
                  <span class="red">Failed</span>
                <?php else : ?>
                  Unknow
                <?php endif; ?>
              </td>
              <td class="middle"><?php echo $rs->fromWhsCode; ?></td>
              <td class="middle"><?php echo $rs->toWhsCode; ?></td>
              <td class="middle"><?php echo ( ! empty($rs->BasePrefix) ? $rs->BasePrefix.'-'.$rs->BaseRef : $rs->BaseRef); ?></td>
              <td class="middle"><?php echo ( ! empty($rs->DocPrefix) ? $rs->DocPrefix.'-'.$rs->DocNum : $rs->DocNum); ?></td>
            </tr>
            <?php $no++; ?>
          <?php endforeach; ?>
        <?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<script src="<?php echo base_url(); ?>scripts/inventory/transfer/transfer.js?v=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>
