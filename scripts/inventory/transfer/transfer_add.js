
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
              type:'wanning'
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



function loadRequestData() {
  let code = $('#request-code').val();

  if(code.length > 7) {
    load_in();

    setTimeout(() => {
      $.ajax({
        url:HOME + 'get_request_data',
        type:'GET',
        cache:false,
        data: {
          'code' : code
        },
        success:function(rs) {
          load_out();

          if(isJson(rs)) {
            let ds = JSON.parse(rs);

            console.log(ds);
            return false;
            if(ds.status == 'success') {
              let source = $('#transfer-template').html();
              let output = $('#transfer-table');
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
        },
        error:function(xhr) {
          load_out();
          swal({
            title:'Error!',
            text: xhr.status + ' : '.xhr.responseText,
            type:'error'
          });
        }
      })
    }, 200);
  }
}
