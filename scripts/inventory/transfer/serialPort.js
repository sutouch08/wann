let portOpen = false; // tracks whether a port is corrently open
let portPromise; // promise used to wait until port succesfully closed
let holdPort = null; // use this to park a SerialPort object when we change settings so that we don't need to ask the user to select it again
let port; // current SerialPort object
let reader; // current port reader object so we can call .cancel() on it to interrupt port reading
let deviceList = null; // use to store device list object that get from localStorage 'WannDeviceData' as array of object
let activeDevice = null; // use to store active device data to make connection to serial port (localStorage: wannActiveDevice);
let deviceUnit = null;
let unitLabel = null;
let rate = 1;

navigator.serial.addEventListener("connect", (e) => {
  swal({
    title:'Port Connected',
    type:'success',
    timer:1000
  });

  setTimeout(() => {
    window.location.reload();
  }, 1200);
});

navigator.serial.addEventListener("disconnect", (e) => {
  swal({
    title:'Port Disconnected',
    type:'warning'
  }, function() {
    window.location.reload();
  });
})

window.addEventListener('load', async () => {

  //--- 1. get device list from localStorage name 'WannDeviceData'
  deviceList = await fetchDevice();
  //------ if not exists warn user no serial device available
  if(deviceList === null || deviceList === undefined) {
    noDeviceData();
    return false;
  }
  //--- 1. get active device from localStorage name 'wannActiveDevice'
  activeDevice = await getActiveDevice();
  //------ if active device not exists  lets user pick one on device list
  if(activeDevice === null || activeDevice === undefined) {
    showDevice();
  }
  else {
    unitLabel = activeDevice.unitLabel;
    $('#request-unit').text(unitLabel);
    $('#device-unit').text(unitLabel);
    connect();
  }

});

function fetchDevice() {
  let deviceData = localStorage.getItem('WannDeviceData');

  if(deviceData !== null && deviceData !== undefined) {
    let devices = JSON.parse(deviceData);

    if(devices.length) {
      return devices;
    }
  }

  return null;
}


function getActiveDevice() {
  let device = localStorage.getItem('WannActiveDevice');

  if(device !== null && device !== undefined) {
    let ds = JSON.parse(device);

    if(ds) {
      return ds;
    }
  }

  return null;
}


function setActiveDevice(id, baudRate, unit) {
  let unitLabel = unit == 'g' ? 'กรัม' : (unit == 'kg' ? 'กิโลกรัม' : unit);

  let ds = {
    "deviceId" : id,
    "deviceBaudRate" : baudRate,
    "deviceUnit" : unit,
    "unitLabel" : unitLabel
  };

  activeDevice = ds;

  localStorage.setItem('WannActiveDevice', JSON.stringify(ds));
  $('#device-unit').text(unitLabel);
  closeModal('devices-modal');
  window.location.reload();
}

function showDevice() {
  if(deviceList) {
    let source = $('#device-template').html();
    let output = $('#device-table');

    render(source, deviceList, output);

    $('#devices-modal').modal('show');
  }
  else {
    noDeviceData();
  }
}


function noDeviceData() {
  swal({
    title:'Not Found !',
    text:'ไม่พบเครื่องชั่ง กรุณาเพิ่มเครื่องชั่งในระบบ หรือ ติดต่อ Admin',
    type:'warning'
  });
}



// This function is bound to the "Open" button, which also becomes the "Close" button
// and it detects which thing to do by checking the portOpen variable
async function connect() {
  const deviceBaudRate = parseInt(activeDevice.deviceBaudRate);
  var resultText;

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
      try {
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
            // If we've reached this point then we're connected to a serial port
            // Set a bunch of variables and enable the appropriate DOM elements
            portOpen = true;
          } catch (e) {
            swal({
              title:'Not found',
              text:'No Serial port available',
              type:'warning'
            })
          }

          // Create a textDecoder stream and get its reader, pipe the port reader to it
          if(portOpen) {
            const textDecoder = new TextDecoderStream();
            reader = textDecoder.readable.getReader();
            const readableStreamClosed = port.readable.pipeTo(textDecoder.writable);

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
              addWeight(txt);
              reConnect();
            }
            else {
              swal({
                title:'Error!',
                text: resultText,
                type:'error'
              });

              disconnect();
            }


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

          } //-- if port open


          // Resolve the promise that we returned earlier. This helps other functions know the port status
          resolve();
        })();
      } catch (e) {
        disconnect();
      }
      // Async anonymous function to open the port

    });
  }

  return;
}

async function reConnect() {
  holdPort = port; // stash the current SerialPort object
  reader.cancel(); // force-close the current port
  console.log("changing setting...");
  console.log("waiting for port to close...");
  await portPromise; // wait for the port to be closed
  console.log("port closed, opening with new settings...");
  connect(); // open the port again (it will grab the new settings while opening the port)
}


async function disconnect() {
  if(portOpen) {
    await reader.cancel();
    // Set a bunch of variables and disable the appropriate DOM elements
    portOpen = false;
  }
}
