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

$('#reject-modal').on('shown.bs.modal', function() {
  $('#reject-message').focus();
})

function rejectReason() {
  $('#reject-reason').val('');
  $('#reject-modal').modal('show');
}


function doReject() {
  let id = $('#id').val();
  let message = $('#reject-message').val();

  $('#reject-modal').modal('hide');

  load_in();

  $.ajax({
    url:HOME + 'reject',
    type:'POST',
    cache:false,
    data:{
      "id" : id,
      "message" : message
    },
    success:function(rs) {
      load_out();

      if(rs == 'success') {
        swal({
          title:'Success',
          type:'success',
          timer:1000
        });

        setTimeout(() => {
          goBack();
        }, 1200);
      }
      else {
        swal({
          title:'Error!',
          text:rs,
          type:'error'
        });
      }
    }
  })
}


function doApprove() {
  let id = $('#id').val();
  let code = $('#code').val();

  swal({
    title:'Approval',
    text:'ต้องการอนุมัติ ' + code + ' หรือไม่ ?',    
    showCancelButton:true,
    cancelButtonText:'No',
    confirmButtonText:'Yes',
    confirmButonColor:'#6fb3e0',
    closeOnConfirm:true
  }, function() {
    setTimeout(() => {
      load_in();

      $.ajax({
        url:HOME + 'approve',
        type:'POST',
        cache:false,
        data:{
          'id' : id
        },
        success:function(rs) {
          load_out();

          if( isJson(rs)) {
            let ds = JSON.parse(rs);

            if(ds.status == 'success') {
              swal({
                title:'Success',
                type:'success',
                timer:1000
              })
            }
            else {
              swal({
                title:'Error',
                text:ds.message,
                type:'error'
              });


            }
          }
          else {
            swal({
              title:'Error!',
              text:rs,
              type:'error'
            })
          }
        }
      })
    }, 200);
  })
}
