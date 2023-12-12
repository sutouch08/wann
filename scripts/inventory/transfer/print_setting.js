  function saveSetting() {
    let stickerWidth = parseDefault(parseFloat($('#sticker-width').val()), 105);
    let stickerHeight = parseDefault(parseFloat($('#sticker-height').val()), 32);
    let stickerPaddingTop = parseDefault(parseFloat($('#sticker-padding-top').val()), 1);
    let stickerPaddingBottom = parseDefault(parseFloat($('#sticker-padding-bottom').val()), 1);
    let stickerPaddingLeft = parseDefault(parseFloat($('#sticker-padding-left').val()), 2);
    let stickerPaddingRight = parseDefault(parseFloat($('#sticker-padding-right').val()), 2);

    let labelPaddingTop = parseDefault(parseFloat($('#label-padding-top').val()), 2);
    let labelPaddingBottom = parseDefault(parseFloat($('#label-padding-bottom').val()), 2);
    let labelPaddingLeft = parseDefault(parseFloat($('#label-padding-left').val()), 2);
    let labelPaddingRight = parseDefault(parseFloat($('#label-padding-right').val()), 2);

    let labelSpace = parseDefault(parseFloat($('#label-space').val()), 0);
    let fontSize = parseDefault(parseFloat($('#label-font-size').val()), 8);

    let printAfterCheck = $('#print-after-check').is(':checked') ? 1 : 0;
    let printPreview = $('#print-preview').is(':checked') ? 1 : 0;
    let closeAfterPrint = $('#close-after-print').is(':checked') ? 1 : 0;

    let setting = {
      "sticker" : {
        "width" : stickerWidth,
        "height" : stickerHeight,
        "paddingTop" : stickerPaddingTop,
        "paddingBottom" : stickerPaddingBottom,
        "paddingLeft" : stickerPaddingLeft,
        "paddingRight" : stickerPaddingRight
      },
      "label" : {
        "paddingTop" : labelPaddingTop,
        "paddingBottom" : labelPaddingBottom,
        "paddingLeft" : labelPaddingLeft,
        "paddingRight" : labelPaddingRight,
        "space" : labelSpace,
        "fontSize" : fontSize
      },
      "printOption" : {
        "printAfterCheck" : printAfterCheck,
        "printPreview" : printPreview,
        "closeAfterPrint" : closeAfterPrint
      }
    }

    localStorage.setItem("WannPrintSetting", JSON.stringify(setting)) ;
  }

  function showPrintSetting() {
    let setting = localStorage.getItem('WannPrintSetting');

    if(setting !== null && setting !== undefined) {
      let con = JSON.parse(setting);
      let s = con.sticker;
      let l = con.label;
      let p = con.printOption;

      $('#sticker-width').val(s.width);
      $('#sticker-height').val(s.height);
      $('#sticker-padding-top').val(s.paddingTop);
      $('#sticker-padding-bottom').val(s.paddingBottom);
      $('#sticker-padding-left').val(s.paddingLeft);
      $('#sticker-padding-right').val(s.paddingRight);

      $('#label-padding-top').val(l.paddingTop);
      $('#label-padding-bottom').val(l.paddingBottom);
      $('#label-padding-left').val(l.paddingLeft);
      $('#label-padding-right').val(l.paddingRight);

      $('#label-space').val(l.space);
      $('#label-font-size').val(l.fontSize);

      let sticker = {
        "width" : `${s.width}mm`,
        "height" : `${s.height}mm`,
        "padding-top" : `${s.paddingTop}mm`,
        "padding-bottom" : `${s.paddingBottom}mm`,
        "padding-left" : `${s.paddingLeft}mm`,
        "padding-right" : `${s.paddingRight}mm`,
      }

      let label = {
        "padding-top" : `${l.paddingTop}mm`,
        "padding-bottom" : `${l.paddingBottom}mm`,
        "padding-left" : `${l.paddingLeft}mm`,
        "padding-right" : `${l.paddingRight}mm`,
      }

      $('#sticker').css(sticker);
      $('.sticker-label').css(label);
      $('.label-space').css('width', `${l.space}mm`);
      $('.sticker-content').css('font-size', `${l.fontSize}px`);

      if(p !== undefined && p !== null) {
        if(p.printAfterCheck == 1) {
          $('#print-after-check').prop('checked', true);
        }
        else {
          $('#print-after-check').prop('checked', false);
        }

        if(p.printPreview == 1) {
          $('#print-preview').prop('checked', true);
        }
        else {
          $('#print-preview').prop('checked', false);
        }

        if(p.closeAfterPrint == 1) {
          $('#close-after-print').prop('checked', true);
        }
        else {
          $('#close-after-print').prop('checked', false);
        }
      }

    }
    else {
      getDefault();
    }

    $('#print-modal').modal('show');
  }


  function stickWidth() {
    let num = parseDefault(parseFloat($('#sticker-width').val()), 0);

    $('#sticker').css('width', `${num}mm`);
  }

  function stickerHeight() {
    let num = parseDefault(parseFloat($('#sticker-height').val()), 0);

    $('#sticker').css('height', `${num}mm`);
  }


  function stickerTop() {
    let num = parseDefault(parseFloat($('#sticker-padding-top').val()), 0);

    $('#sticker').css('padding-top', `${num}mm`);
  }

  function stickerBottom() {
    let num = parseDefault(parseFloat($('#sticker-padding-bottom').val()), 0);

    $('#sticker').css('padding-bottom', `${num}mm`);
  }

  function stickerLeft() {
    let num = parseDefault(parseFloat($('#sticker-padding-left').val()), 0);

    $('#sticker').css('padding-left', `${num}mm`);
  }

  function stickerRight() {
    let num = parseDefault(parseFloat($('#sticker-padding-right').val()), 0);

    $('#sticker').css('padding-right', `${num}mm`);
  }

  function labelTop() {
    let num = parseDefault(parseFloat($('#label-padding-top').val()), 0);

    $('.sticker-label').css('padding-top', `${num}mm`);
  }

  function labelBottom() {
    let num = parseDefault(parseFloat($('#label-padding-bottom').val()), 0);

    $('.sticker-label').css('padding-bottom', `${num}mm`);
  }

  function labelLeft() {
    let num = parseDefault(parseFloat($('#label-padding-left').val()), 0);

    $('.sticker-label').css('padding-left', `${num}mm`);
  }

  function labelRight() {
    let num = parseDefault(parseFloat($('#label-padding-right').val()), 0);

    $('.sticker-label').css('padding-right', `${num}mm`);
  }

  function labelSpace() {
    let num = parseDefault(parseFloat($('#label-space').val()), 0);

    $('.label-space').css('width', `${num}mm`);
  }

  function labelFontSize() {
    let num = parseDefault(parseFloat($('#label-font-size').val()), 0);

    $('.sticker-content').css('font-size', `${num}px`);
  }

  function getDefault() {
    let labelSpace = 0;
    let fontSize = 8;

    let sticker = {
      "width" : "105mm",
      "height" : "32mm",
      "padding-top" : "1mm",
      "padding-bottom" : "1mm",
      "padding-left" : "2mm",
      "padding-right" : "2mm"
    }

    let label = {
      "padding-top" : "2mm",
      "padding-bottom" : "2mm",
      "padding-left" : "2mm",
      "padding-right" : "2mm"
    }

    $('#sticker').css(sticker);
    $('.sticker-label').css(label);
    $('.label-space').css('width', `${labelSpace}mm`);
    $('.sticker-content').css('font-size', `${fontSize}px`);
    $('#print-after-check').prop('checked', true);
    $('#print-preview').prop('checked', true);
    $('#close-after-print').prop('checked', true);
  }



  function testPrint() {
    var center = ($(document).width() - 800)/2;
    var prop = "width=800, height=900, left="+center+", scrollbars=yes";
    var target = HOME + 'test_print';
  	window.open(target, "_blank", prop);
  }
