function goBack() {
  window.location.href = HOME;
}


function viewDetail(id) {
  window.location.href = HOME + 'view_detail/'+id;
}

$('#fromDate').datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(sd) {
    $('#toDate').datepicker('option', 'minDate', sd);
  }
});

$('#toDate').datepicker({
  dateFormat:'dd-mm-yy',
  onClose:function(sd) {
    $('#fromDate').datepicker('option', 'maxDate', sd);
  }
});


function printTQ(docEntry) {
  var center = ($(document).width() - 800)/2;
  var prop = "width=800, height=900, left="+center+", scrollbars=yes";
  var target = HOME + 'printTQ/'+docEntry;
	window.open(target, "_blank", prop);
}
