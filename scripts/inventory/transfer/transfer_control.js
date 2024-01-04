
$('#pd-code').keyup(function(e) {
  if(e.keyCode === 13) {
    let code = $.trim($(this).val());
    if(code.length) {
      getRequestItem(code);
    }
  }
});

$('#receipt-no').keyup(function(e) {
  if(e.keyCode === 13) {
    let receiptNo = $.trim($(this).val());

    if(receiptNo.length == 0) {
      swal("กรุณาระบุ Lot/ReceiptNo");
      return false;
    }
    else {
      validateReceiptNo(receiptNo);
    }
  }
})


$('#checker-uid').keyup(function(e) {
  if(e.keyCode === 13) {
    let uid = $.trim($('#checker-uid').val());

    if(uid.length) {
      updateRequestRow();
    }
  }
})


function validateReceiptNo(receiptNo) {
  if(receiptNo.length > 0) {

    let pdCode = $.trim($('#pd-code').val());
    let whsCode = $('#fromWhsCode').val();

    if(pdCode.length > 0) {
      load_in();

      $.ajax({
        url:HOME + 'is_exists_receipt_no',
        type:'POST',
        cache:false,
        data:{
          'receiptNo' : receiptNo,
          'item_code' : pdCode,
          'warehouse_code' : whsCode
        },
        success:function(rs) {
          load_out();

          if(isJson(rs)) {
            let ds = JSON.parse(rs);

            if(ds.status == 'success') {
              $('#checker-uid').focus();
            }
            else {
              beep();
              swal({
                title:'Error!',
                text:ds.message,
                type:'error'
              });
            }
          }
          else {
            beep();
            swal({
              title:'Error!',
              text:rs,
              type:'error'
            });
          }
        }
      });
    }
  }
}


function addWeight(weight) {
  $('#input-qty').val(weight);
  weight = parseDefault(parseFloat(weight), 0);
  requestWeight = parseDefault(parseFloat($('#request-qty').val()), 0);
  itemCode = $.trim($('#pd-code').val());

  if(itemCode.length == 0) {
    $('#pd-code').focus();
    return false;
  }

  if(requestWeight <= 0 || weight <= 0) {
    beep();
    swal("น้ำหนักไม่ถูกต้อง");
    return false;
  }

  if(weight > requestWeight) {
    beep();
    swal("น้ำหนักเกิน");
    return false;
  }

  $('#checker-uid').val('').focus();
}


function updateRequestRow() {
  let checker_uid = $.trim($('#checker-uid').val());
  let qty = parseDefault(parseFloat($('#input-qty').val()), 0);
  let r_qty = parseDefault(parseFloat($('#request-qty').val()), 0);
  let itemCode = $.trim($('#pd-code').val());
  let receipt = $.trim($('#receipt-no').val());
  let id = $('#id').val();
  let lineNum = $('#LineNum').val();
  let deviceUnit = unitLabel; //--- หน่วยเครื่องขั่งได้จากตอนที่ เชื่อมต่อเครื่องชั่ง
  let printAfterCheck = $('#printAfterCheck').val();

  if(checker_uid.length == 0) {
    swal("กรุณาระบุผู้ตรวจสอบ");
    return false;
  }

  if(itemCode.length == 0) {
    swal("กรุณาแสกนบาร์โค้ดวัตุดิบ");
    return false;
  }

  if(qty <= 0 || r_qty <= 0) {
    swal("น้ำหนักไม่ถูกต้อง");
    return false;
  }

  if(qty > r_qty) {
    swal("น้ำหนักเกิน");
    return false;
  }

  $.ajax({
    url:HOME + 'update_request_row',
    type:'POST',
    cache:false,
    data: {
      "id" : id,
      "LineNum" : lineNum,
      "ItemCode" : itemCode,
      "ReceiptNo" : receipt,
      "Qty" : qty,
      "deviceUnit" : deviceUnit,
      "rate" : rate,
      "checker_uid" : checker_uid
    },
    success:function(rs) {
      if(isJson(rs)) {
        let ds = JSON.parse(rs);

        if(ds.status == 'success') {
          $('#qty-'+lineNum).text(ds.qtyLabel);
          $('#input-qty-'+lineNum).val(ds.qty);

          if(ds.full) {
            $('#check-'+lineNum).removeClass('hide');
          }
          else {
            $('#check-'+lineNum).addClass('hide');
          }

          let row = ds.row;
          let source = $('#row-template').html();
          let output = $('#details-table');

          render_append(source, row, output);

          if(printAfterCheck == '1') {
            printLabel(id, ds.row.LineNum);
          }

          resetControl();
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
  });
}

function resetControl() {
  $('#pd-name').val('');
  $('#receipt-no').val('');
  $('#request-qty').val('');
  $('#input-qty').val('');
  $('#checker-uid').val('');
  $('#pd-code').val('').focus();
}

function getRequestItem(code) {
  let id = $('#id').val();

  if(code.length) {
    $.ajax({
      url:HOME + 'get_request_item',
      type:'GET',
      cache:false,
      data:{
        'id' : id,
        'itemCode' : code
      },
      success:function(rs) {
        if(isJson(rs)) {
          let ds = JSON.parse(rs);

          if(ds.status == 'success') {
            /*
              หน่ายที่เป็นไปได้ สั่ง/เครื่องชั่ง
              g/g, g/kg, kg/g, kg/kg, pcs/kg
            */
            rate = 1; //--- set global rate ;
            let unitMsr = ds.data.unitMsr; //--- หน่วยที่ต้องการได้
            let unitMsr2 = ds.data.unitMsr2; //---- ตัวแปลงหน่วย ถ้า ตัวแรกกับตัว 2 ไม่ตรงกันต้องคำนวนให้เป็นหน่วนที่ย่อยกว่า
            let numPerMsr = parseDefault(parseFloat(ds.data.NumPerMsr), 1); //--- ตัวคูณต่อหน่วย
            let numPerMsr2 = parseDefault(parseFloat(ds.data.NumPerMsr2), 1); //--- ตัวคูณต่อหน่วย
            let deviceUnit = unitLabel; //--- หน่วยเครื่องขั่งได้จากตอนที่ เชื่อมต่อเครื่องชั่ง
            let qty = parseDefault(parseFloat(ds.data.balance), 0);

            if(unitMsr != "ชิ้น") {
              if(unitMsr == "กรัม" && deviceUnit == "กิโลกรัม") {
                rate = 1000;
                qty = roundNumber(qty / rate, 6);
              }

              if(unitMsr == "กิโลกรัม" && deviceUnit == "กรัม") {
                rate = 1000;
                qty = roundNumber(qty * rate, 6);
              }
            }
            else {
              if(unitMsr2 == "กิโลกรัม" && deviceUnit == "กิโลกรัม") {
                rate = numPerMsr2;
                qty = roundNumber(qty / rate, 6);
              }

              if(unitMsr2 == "กิโลกรัม" && deviceUnit == "กรัม") {
                rate = numPerMsr2;
                qty = roundNumber(qty / rate, 6) * 1000;
              }
            }

            if(qty <= 0) {
              swal({
                title:'Error!',
                text:'น้ำหนักไม่ถูกต้อง ตัวแปลงหน่วยอาจคำนวนผิดพลาด',
                type:'error'
              });

              return false;
            }

            $('#LineNum').val(ds.data.LineNum);
            $('#pd-name').val(ds.data.Dscription);
            $('#request-qty').val(qty);
            $('#request-unit').text(unitLabel);
            $('#unitMsr').val(ds.data.unitMsr);
            $('#unitMsr2').val(ds.data.unitMsr2);
            $('#uomEntry').val(ds.data.UomEntry);
            $('#uomEntry2').val(ds.data.UomEntry2);
            $('#uomCode').val(ds.data.UomCode);
            $('#uomCode2').val(ds.data.UomCode2);
            $('#numPerMsr').val(ds.data.NumPerMsr);
            $('#numPerMsr2').val(ds.data.NumPerMsr2);
            $('#receipt-no').focus();

            $('#line-no-'+ds.data.LineNum).insertAfter($('#head'));

          }
          else {
            beep();
            swal({
              title:'Error!',
              text:ds.message,
              type:'error'
            });
          }
        }
        else {
          beep();
          swal({
            title:'Error!',
            text:rs,
            type:'error'
          })
        }
      }
    });
  }
}


function removeRow(lineNum, itemCode) {
  swal({
    title:'Are you sure ?',
    text: 'ต้องการลบ '+ itemCode + ' หรือไม่ ?',
    type:'warning',
    showCancelButton:true,
    confirmButtonColor:'#cf715b',
    confirmButtonText:'Yes',
    cancelButtonText:'No',
    closeOnConfirm:true
  },
  function() {
    let id = $('#id').val();
    setTimeout(() => {
      $.ajax({
        url:HOME + 'remove_row',
        type:'POST',
        cache:false,
        data:{
          'transfer_id' : id,
          'LineNum' : lineNum
        },
        success: function(rs) {
          if(isJson(rs)) {
            let ds = JSON.parse(rs);
            if(ds.status === 'success') {
              $('#row-'+lineNum).remove();
              $('#qty-'+ ds.LineNum).text(ds.qtyLabel);
              $('#input-qty-'+ds.LineNum).val(ds.qty);
              $('#check-'+ds.LineNum).addClass('hide');

              swal({
                title:'Deleted',
                type:'success',
                timer:1000
              });

              setTimeout(() => {
                $('#pd-code').focus();
              }, 1100);
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
            });
          }
        }
      }, 100);
    })
  })
}

//--- Over wirte Request Qty
function editRequestQty(lineNum) {
  let qty = parseDefault(parseFloat($('#req-qty-'+lineNum).val()), 0);
  let checkQty = parseDefault(parseFloat($('#input-qty-'+lineNum).val()), 0);
  let code = $('#req-item-'+lineNum).val();
  let unit = $('#req-unit-'+lineNum).val();

  $('#edit-item-code').val(code);
  $('#edit-weight').val(qty);
  $('#check-qty').val(checkQty);
  $('#origin-qty').val(qty);
  $('#edit-unit').text(unit);
  $('#edit-line-num').val(lineNum);

  $('#overwrite-modal').modal('show');
}


function confirmEdit() {
  let lineNum = $('#edit-line-num').val();
  let weight = parseDefault(parseFloat($('#edit-weight').val()), 0);
  let checkQty = parseDefault(parseFloat($('#check-qty').val()), 0);
  let originQty = parseDefault(parseFloat($('#origin-qty').val()), 0);

  if(weight != originQty) {
    if(weight <= 0) {
      $('#edit-weight').addClass('has-error');
      $('#error-label').text('น้ำหนักต้องมากกว่า 0.00');
      return false;
    }
    else {
      $('#error-label').text('');
    }

    if(weight < checkQty) {
      $('#edit-weight').addClass('has-error');
      $('#error-label').text('น้ำหนักต้องไม่เกินน้ำหนักที่บรรจุแล้ว');
      return false;
    }
    else {
      $('#error-label').text('');
    }

    $('#overwrite-modal').modal('hide');

    showApproveBox();
  }
  else {
    $('#overwrite-modal').modal('hide');
  }
}

function showApproveBox() {
  setTimeout(() => {
    $('#approve-modal').modal('show');
    $('#approve-modal').on('shown.bs.modal', function() { $('#s-code').val('').focus();});
  },200);
}


function confirmOverwrite() {
  let uid = $('#user-uid').val();
  let s_code = $('#s-code').val();
  let menu = 'ICAPRQ';

  $.ajax({
    url:BASE_URL + 'users/validate_credentials/validate_permission',
    type:'POST',
    cache:false,
    data:{
      'uid' : uid,
      's_key' : s_code,
      'menu' : menu
    },
    success:function(rs) {
      if(isJson(rs)) {
        let ds = JSON.parse(rs);

        if(ds.status == 'success' && ds.uid == uid) {
          $('#approve-modal').modal('hide');

          let id = $('#id').val();
          let lineNum = $('#edit-line-num').val();
          let qty = parseDefault(parseFloat($('#edit-weight').val()), 0);

          setTimeout(() => {
            load_in();

            $.ajax({
              url:HOME + 'change_request_qty',
              type:'POST',
              cache:false,
              data:{
                'id' : id,
                'LineNum' : lineNum,
                'Qty' : qty,
                'uname' : ds.uname
              },
              success:function(rd) {
                load_out();

                if(isJson(rd)) {
                  let de = JSON.parse(rd);

                  if(de.status === 'success') {
                    $('#req-qty-'+lineNum).val(de.qty);
                    $('#open-qty-'+lineNum).text(de.qtyLabel);
                    $('#line-no-'+lineNum).addClass('blue');

                    swal({
                      title:'Success',
                      type:'success',
                      timer:1000
                    });

                    setTimeout(() => {
                      $('#pd-code').focus();
                    }, 1500);
                  }
                  else {
                    swal({
                      title:'Error!',
                      text:de.message,
                      type:'error'
                    }, function() {
                      $('#pd-code').focus();
                    })
                  }
                }
                else {
                  swal({
                    title:'Error!',
                    text:rd,
                    type:'error'
                  }, function() {
                    $('#pd-code').focus();
                  });
                }
              }
            })

          }, 200);
        }
        else {
          $('#approve-error').text(ds.message);
        }
      }
      else {
        $('#approve-error').text(rs);
      }
    }
  })
}


function printLabel(id, lineNum) {
  var center = ($(document).width() - 800)/2;
  var prop = "width=800, height=900, left="+center+", scrollbars=yes";
  var target = HOME + 'printLabel/'+id+'/'+lineNum;
	window.open(target, "_blank", prop);
}

$(document).ready(function() {
  let printSetting = localStorage.getItem('WannPrintSetting');

  if(printSetting !== null && printSetting !== undefined) {
    let setting = JSON.parse(printSetting);
    if(setting !== null && setting !== undefined) {
      let p = setting.printOption;

      if(p.printAfterCheck !== null && p.printAfterCheck !== undefined) {
        $('#printAfterCheck').val(p.printAfterCheck);
      }
    }
  }
});
