let portOpen = false; // tracks whether a port is corrently open
let portPromise; // promise used to wait until port succesfully closed
let holdPort = null; // use this to park a SerialPort object when we change settings so that we don't need to ask the user to select it again
let port; // current SerialPort object
let reader; // current port reader object so we can call .cancel() on it to interrupt port reading

window.addEventListener('load', () => {
  fetchDevice();
});

function fetchDevice() {
  let deviceData = localStorage.getItem('WannDeviceData');

  if(deviceData != null && deviceData.length) {
    let devices = JSON.parse(deviceData);

    if(devices.length) {
      let source = $('#rows-template').html();
      let output = $('#device-table');

      render(source, devices, output);

      reIndex();
    }
    else {
      $('#device-table').html('');
    }
  }
}

// This function is bound to the "Open" button, which also becomes the "Close" button
// and it detects which thing to do by checking the portOpen variable
async function connect() {
  const deviceBaudRate = parseInt($('#device-baud-rate').val());
  // Is there a port open already?
  if (portOpen) {
    // Port's open. Call reader.cancel() forces reader.read() to return done=true
    // so that the read loop will break and close the port
    reader.cancel();
    console.log("attempt to close");
  } else {
    // No port is open so we should open one.
    // We write a promise to the global portPromise var that resolves when the port is closed
    portPromise = new Promise((resolve) => {
      // Async anonymous function to open the port
      (async () => {
        // Check to see if we've stashed a SerialPort object
        if (holdPort == null) {
          // If we haven't stashed a SerialPort then ask the user to select one
          ports = await navigator.serial.getPorts();

          if(ports) {
            port = ports[0];
          }
          else {
            port = await navigator.serial.requestPort();
          }
        } else {
          // If we have stashed a SerialPort then use it and clear the stash
          port = holdPort;
          holdPort = null;
        }

        // Grab the currently selected baud rate from the drop down menu
        //var baudSelected = deviceBaudRate; //parseInt(document.getElementById("baud_rate").value);
        // Open the serial port with the selected baud rate
        try {
          await port.open({ baudRate: deviceBaudRate });
        } catch (e){
          port = await navigator.serial.requestPort();

          try {
            await port.open({ baudRate: deviceBaudRate });
          }
          catch(e) {
            swal({
              title:'Not found',
              text:'No Serial port available',
              type:'warning'
            })
          }
        }

        // Create a textDecoder stream and get its reader, pipe the port reader to it
        const textDecoder = new TextDecoderStream();
        reader = textDecoder.readable.getReader();
        const readableStreamClosed = port.readable.pipeTo(textDecoder.writable);

        // If we've reached this point then we're connected to a serial port
        // Set a bunch of variables and enable the appropriate DOM elements
        portOpen = true;

        $('#reopen-btn').addClass('hide');
        $('#open-close-btn').text("Disconnect").removeClass('hide');

        // NOT SUPPORTED BY ALL ENVIRONMENTS
        // Get port info and display it to the user in the port_info span
        let portInfo = port.getInfo();

        let resultText = "เชื่อมต่อสำเร็จ VID " +
        portInfo.usbVendorId +
        " and PID " +
        portInfo.usbProductId +
        "\r\nกรุณากดส่งข้อมูลจากเครื่องชั่งเพื่อทดสอบความถูกต้อง";

        $('#result-message').val(resultText)


        // Serial read loop. We'll stay here until the serial connection is ended externally or reader.cancel() is called
        // It's OK to sit in a while(true) loop because this is an async function and it will not block while it's await-ing
        // When reader.cancel() is called by another function, reader will be forced to return done=true and break the loop
        let txt = "";
        let unit = "";
        let count = 1;
        while (true) {
          const { value, done } = await reader.read();
          count++;

          if (done) {
            reader.releaseLock(); // release the lock on the reader so the owner port can be closed
            break;
          }

          if(value != "" && value != " ") {
            if(value == "g" || value == "kg") {
              unit = value;
              $('#device-unit').val(value);
              break;
            }

            if(isNaN(value) && value != ".") {
              resultText = "Baud Rate " + deviceBaudRate + " ไม่ตรงกับเครื่องชั่ง " +
              " \r\nเลือก Baud Rate ใหม่แล้วลองอีกครั้ง";
              break;
            }

            txt = txt + value;
          }
        }

        if( ! isNaN(parseFloat(txt))) {
          resultText = "การเชื่อมต่อสำเร็จ\r\nค่าที่รับได้คือ : " + txt + " " + unit;

          $('#save-btn').removeAttr('disabled');
        }

        $('#open-close-btn').addClass('hide');
        $('#reopen-btn').removeClass('hide');

        $('#result-message').val(resultText);
        // If we've reached this point then we're closing the port
        // first step to closing the port was releasing the lock on the reader
        // we did this before exiting the read loop.
        // That should have broken the textDecoder pipe and propagated an error up the chain
        // which we catch when this promise resolves
        await readableStreamClosed.catch(() => {
          /* Ignore the error */
        });
        // Now that all of the locks are released and the decoder is shut down, we can close the port
        await port.close();

        // Set a bunch of variables and disable the appropriate DOM elements
        portOpen = false;
        $('#reopen-btn').addClass('hide');
        $('#open-close-btn').text("Connect").removeClass('hide');
        $('#result-message').val("Disconnected");

        console.log("port closed");

        // Resolve the promise that we returned earlier. This helps other functions know the port status
        resolve();
      })();
    });
  }

  return;
}

async function changeSettings() {
  holdPort = port; // stash the current SerialPort object
  reader.cancel(); // force-close the current port
  console.log("changing setting...");
  console.log("waiting for port to close...");
  await portPromise; // wait for the port to be closed
  console.log("port closed, opening with new settings...");
  connect(); // open the port again (it will grab the new settings while opening the port)
}


function saveAndClose() {
  const deviceName = $.trim($('#device-name').val());
  const deviceUnit = $('#device-unit').val();
  const deviceUnitName = $('#device-unit option:selected').data('name');
  const deviceBaudRate = parseInt($('#device-baud-rate').val());
  const devicePort = $('#device-port').val();
  const devicePortName = $('#device-port option:selected').data('name');

  if(deviceName.length == 0) {
    $('#result-message').val("กรุณาระบุชื่อเครื่องชั่ง");
    return false;
  }

  let ds = {
    'deviceId' : generateUID(),
    'deviceName' : deviceName,
    'deviceUnit' : deviceUnit,
    'deviceUnitName' : deviceUnitName,
    'deviceBaudRate' : deviceBaudRate,
    'devicePort' : devicePort,
    'devicePortName' : devicePortName
  }

  var db = localStorage.getItem('WannDeviceData');

  if(db !== null && db !== undefined) {
    db = JSON.parse(db);
    db.push(ds);
  }
  else {
    db = [ds];
  }

  console.log(db);

  localStorage.setItem('WannDeviceData', JSON.stringify(db));

  disconectPort('add-modal');

  fetchDevice();
}


function disconectPort(modalName) {
  if(portOpen) {
    reader.cancel();
  }

  closeModal(modalName);
}

function addNew() {
  $('#device-name').val('');
  $('#device-baud-rate').val('2400');
  $('#result-message').val('');
  $('#add-modal').modal('show');
}

$('#add-modal').on('shown.bs.modal', function() {
  $('#device-name').focus();
})

function removeDevice(id, name) {
  swal({
    title:'Are you sure ?',
    text:'ต้องการลบ '+ name + ' หรือไม่ ?',
    type:'warning',
    showCancelButton:true,
    confirmButtonColor:'#cf715b',
    confirmButtonText:'Yes',
    cancelButtonText:'No',
    closeOnConfirm:true
  }, function() {
    setTimeout(() => {
      removeDeviceData(id);
    }, 100);
  })
}


function removeDeviceData(id) {
  let devices = localStorage.getItem('WannDeviceData');

  if(devices !== null && devices !== undefined) {
    let result = JSON.parse(devices);
    if(result.length) {
      var ds = result.filter((obj) => {
        return obj.deviceId != id;
      });

      localStorage.setItem('WannDeviceData', JSON.stringify(ds));

      //--- check activeDevice
      let activeDevice = localStorage.getItem('WannActiveDevice');

      if(activeDevice !== null && activeDevice !== undefined) {
        let ad = JSON.parse(activeDevice);

        if(ad.deviceId == id) {
          localStorage.removeItem('WannActiveDevice');
        }
      }

      swal({
        title:'Success',
        type:'success',
        timer:1000
      });

      setTimeout(() => {
        fetchDevice();
      }, 1200);
    }
  }
}
