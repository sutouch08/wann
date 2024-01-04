function goBack() {
  window.location.href = HOME;
}


function addNew() {
  window.location.href = HOME + 'add_new';
}


function edit(id) {
  window.location.href = HOME + 'edit/'+id;
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


function printTransfer(id) {
  var center = ($(document).width() - 800)/2;
  var prop = "width=800, height=900, left="+center+", scrollbars=yes";
  var target = HOME + 'print_transfer';
  window.open(target, "_blank", prop);
}


function cancleTransfer(id, code) {
  swal({
    title:'Are you sure ?',
    text: 'ต้องการยกเลิก '+ code + ' หรือไม่ ?',
    type:'warning',
    showCancelButton:true,
    confirmButtonColor:'#cf715b',
    confirmButtonText:'Yes',
    cancelButtonText:'No',
    closeOnConfirm:true
  }, function() {
    load_in();
    setTimeout(() => {
      $.ajax({
        url:HOME + 'cancle_transfer',
        type:'POST',
        cache:false,
        data:{
          'id' : id
        },
        success:function(rs) {
          load_out();

          if(isJson(rs)) {
            let ds = JSON.parse(rs);
            if(ds.status == 'success') {
              swal({
                title:'Success',
                type:'success',
                timer:1000
              });

              setTimeout(() => {
                window.location.reload();
              }, 1200);
            }
            else {
              swal({
                title:'Error!',
                text:ds.message,
                type:'error'
              });
            }
          }
          else {
            swal({
              title:'Error',
              text:rs,
              type:'error'
            });
          }
        }
      })

    }, 200);
  });
}


function rollback(id, code) {
  swal({
    title:'Are you sure ?',
    text: 'ต้องการย้อนสถานะ '+ code + ' หรือไม่ ?',
    type:'warning',
    showCancelButton:true,
    confirmButtonColor:'#cf715b',
    confirmButtonText:'Yes',
    cancelButtonText:'No',
    closeOnConfirm:true
  }, function() {
    load_in();
    setTimeout(() => {
      $.ajax({
        url:HOME + 'unsave',
        type:'POST',
        cache:false,
        data:{
          'id' : id
        },
        success:function(rs) {
          load_out();

          if(isJson(rs)) {
            let ds = JSON.parse(rs);
            if(ds.status == 'success') {
              swal({
                title:'Success',
                type:'success',
                timer:1000
              });

              setTimeout(() => {
                window.location.reload();
              }, 1200);
            }
            else {
              swal({
                title:'Error!',
                text:ds.message,
                type:'error'
              });
            }
          }
          else {
            swal({
              title:'Error',
              text:rs,
              type:'error'
            });
          }
        }
      })

    }, 200);
  });
}


function sendToSap(id, code) {
  swal({
    title:'Send to SAP ?',
    text: 'ต้องการส่งเอกสาร '+ code + ' เข้า SAP อีกครั้งหรือไม่ ?',
    type:'warning',
    showCancelButton:true,
    confirmButtonColor:'#cf715b',
    confirmButtonText:'Yes',
    cancelButtonText:'No',
    closeOnConfirm:true
  }, function() {
    load_in();
    setTimeout(() => {
      $.ajax({
        url:HOME + 'do_export',
        type:'POST',
        cache:false,
        data:{
          'id' : id
        },
        success:function(rs) {
          load_out();

          if(isJson(rs)) {
            let ds = JSON.parse(rs);
            if(ds.status == 'success') {
              swal({
                title:'Success',
                type:'success',
                timer:1000
              });

              setTimeout(() => {
                window.location.reload();
              }, 1200);
            }
            else {
              swal({
                title:'Error!',
                text:ds.message,
                type:'error'
              });
            }
          }
          else {
            swal({
              title:'Error',
              text:rs,
              type:'error'
            });
          }
        }
      })

    }, 200);
  });
}


function showError(id) {
  let message = $('#error-message-'+id).val();
  $('#error-message').text(message);

  $('#error-modal').modal('show');

}
