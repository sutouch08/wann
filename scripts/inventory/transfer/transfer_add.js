
$('#doc_date').datepicker({
  dateFormat:'dd-mm-yy'
});

$('#due_date').datepicker({
  dateFormat:'dd-mm-yy'
});

$('#tax_date').datepicker({
  dateFormat:'dd-mm-yy'
});


$('#request-code').autocomplete({
  source:HOME + 'get_request_code',
  autoFocus:true
});


function add() {
  let code = $.trim($('#request-code').val());
  let doc_date = $('#doc_date').val();
  let due_date = $('#due_date').val();
  let tax_date = $('#tax_date').val();
  let fromWhsCode = $('#fromWhsCode').val();
  let toWhsCode = $('#toWhsCode').val();
  let remark = $('#remark').val();

  if(code.length) {

    if( ! isDate(doc_date)) {
      swal("วันที่เอกสารไม่ถูกต้อง");
      return false;
    }

    if( ! isDate(due_date)) {
      swal("วันที่ครบกำหนดไม่ถูกต้อง");
      return false;
    }

    if( ! isDate(tax_date)) {
      swal("Posting date ไม่ถูกต้อง");
      return false;
    }

    load_in();

    $.ajax({
      url:HOME + 'add',
      type:'POST',
      cache:false,
      data:{
        'code' : code,
        'doc_date' : doc_date,
        'due_date' : due_date,
        'tax_date' : tax_date,
        'fromWhsCode' : fromWhsCode,
        'toWhsCode' : toWhsCode,
        'remark' : remark
      },
      success:function(rs) {
        load_out();

        if(isJson(rs)) {
          let ds = JSON.parse(rs);

          if(ds.status == 'success') {
            edit(ds.id);
          }
          else {
            swal({
              title:'Oops!',
              text:ds.message,
              type:'warning'
            });
          }
        }
        else {
          swal({
            title:'Error !',
            text:rs,
            type:'error'
          });
        }
      },
      error:function(xhr) {
        load_out();
        swal({
          title:'Error!',
          text:xhr.responseText,
          type:'error'
        });
      }
    });
  }
}

function update() {
  let id = $('#id').val();
  let doc_date = $('#doc_date').val();
  let due_date = $('#due_date').val();
  let tax_date = $('#tax_date').val();
  let remark = $.trim($('#remark').val());

  if(! isDate(doc_date)) {
    $('#doc_date').addClass('has-error');
    swal("วันที่ไม่ถูกต้อง");
    return false;
  }
  else {
    $('#doc_date').removeClass('has-error');
  }

  if(! isDate(due_date)) {
    $('#due_date').addClass('has-error');
    swal("วันที่ไม่ถูกต้อง");
    return false;
  }
  else {
    $('#due_date').removeClass('has-error');
  }

  if(! isDate(tax_date)) {
    $('#tax_date').addClass('has-error');
    swal("วันที่ไม่ถูกต้อง");
    return false;
  }
  else {
    $('#tax_date').removeClass('has-error');
  }

  load_in();
  $.ajax({
    url:HOME + 'update',
    type:'POST',
    cache:false,
    data: {
      'id' : id,
      'doc_date' : doc_date,
      'due_date' : due_date,
      'tax_date' : tax_date,
      'remark' : remark
    },
    success:function(rs) {
      load_out();
      if(isJson(rs)) {
        let ds = JSON.parse(rs);

        if(ds.status === 'success') {
          swal({
            title:'Success',
            type:'success',
            timer:1000
          });
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
          title:'Error!',
          text:rs,
          type:'error'
        })
      }
    }
  })
}


function save() {
  let id = $('#id').val();
  let must_approve = 0;

  //---- ตรวจสอบว่า ชั่งได้ครบตามที่สั่งมาหรือเปล่า
  $('.request-row').each(function(e) {
    $(this).removeClass('red');
    let no = $(this).data('linenum');
    let open = removeCommas($('#open-qty-'+no).text());
    let qty = removeCommas($('#qty-'+no).text());
    open = parseDefault(parseFloat(open), 0);
    qty = parseDefault(parseFloat(qty), 0);

    if(open == 0 || qty == 0) {
      must_approve = 1;
      $(this).addClass('red');
    }

    if(open > qty) {
      must_approve = 1;
      $(this).addClass('red');
    }

    if(open < qty) {
      must_approve = 1;
      $(this).addClass('red');
    }
  });


  if(must_approve == 1) {
    swal({
      title:'น้ำหนักไม่ตรง',
      text:'พบรายการที่น้ำหนักไม่ตรงกับใบขอโอน<br>ต้องการดำเนินการต่อหรือไม่ ?',
      type:'info',
      html:true,
      showCancelButton:true,
      confirmButtonColor:'#cf715b',
      confirmButtonText:'Yes',
      cancelButtonText:'No',
      closeOnConfirm:true
    },
    function() {
      setTimeout(() => {
        saveTransfer(must_approve);
      }, 200);
    });
  }
  else {
    saveTransfer(must_approve);
  }
}


function saveTransfer(must_approve) {
  let id = $('#id').val();

  load_in();
  $.ajax({
    url:HOME + 'save',
    type:'POST',
    cache:false,
    data: {
      "id" : id,
      "must_approve" : must_approve
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
            viewDetail(id);
          }, 1200);
        }
        else {
          if(ds.ex == 1) {
            swal({
              title:'Interface Failed',
              text:ds.message,
              type:info
            }, function() {
              setTimeout(() => {
                viewDetail(id);
              }, 300);
            });
          }
          else {
            swal({
              title:'Error!',
              text:ds.message,
              type:'error'
            });
          }
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
  });
}
