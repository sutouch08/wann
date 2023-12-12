<?php $this->load->view('include/header'); ?>
	<div class="row">
	  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-5 text-center">
	    <h1>Hello! <?php echo get_cookie('displayName'); ?></h1>
	    <h5>Good to see you here</h5>
	  </div>
	  <div class="divider-hidden"></div>
		<div class="divider"></div>
	  <div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12 padding-5 text-center">
			<input type="text" id="result" />
			<button type="button" class="btn btn-sm btn-success" onclick="readData()">Read</button>
	  </div>

		<button type="button" class="btn btn-sm btn-success" onclick="getConnect()">getConnect</button>
	</div>
	<ul id="list2"></ul>
	<script>

		var ports = null;
		var port = null;

		var reader = null;
		var keepReading = true;
		//var usbProductId = 8200;
		//var usbVendorId = 1367;
		var filter = {usbVendorId:8200, usbProductId:1367};
		// window.addEventListener('load', () => {
		// 	connect();
		// });

		async function connect() {
			ports = await navigator.serial.getPorts();

			if(ports) {
				port = ports[0];
			}
		}


		// $('#result').focus(function() {
		// 	readData();
		// });

		// $('#result').focusout(function() {
		// 	$(this).val('');
		// 	closeReader();
		// })

		// async function closeReader() {
		// 	if(reader !== null) {
		// 		try {
		// 			// await reader.releaseLock();
		//
		// 			await port.close();
		// 		}
		// 		catch(err) {
		// 			await port.close();
		// 		}
		// 	}
		// 	// else {
		// 	// 	if(port.readable) {
		// 	// 		await port.close();
		// 	// 	}
		// 	// }
		// }

		async function readData() {
			ports = await navigator.serial.getPorts();
			if(ports) {
				port = ports[0];
			}

			await port.open({baudRate:2400});
			const decoder = new TextDecoder();
			if(port.readable) {
				reader = port.readable.getReader();
				let txt = "";
				while(true) {
					const { value, done } = await reader.read();
					var num = decoder.decode(value);
					if(num != "") {
						if(num == "g") {
							break;
						}

						txt = txt + num;
					}

					if(done) {
						console.log(txt);
						break;
					}
				}

				$('#result').val(parseFloat(txt));
				reader.releaseLock();

				await port.close();
			}
		}


		// async function readData() {
		// 	ports = await navigator.serial.getPorts();
		// 	if(ports) {
		// 		port = ports[0];
		// 	}
		//
		// 	await port.open({baudRate:2400});
		// 	if(port.readable) {
		// 		// With transform streams.
		// 		const textDecoder = new TextDecoderStream();
		// 		const readableStreamClosed = port.readable.pipeTo(textDecoder.writable);
		// 		const reader = textDecoder.readable.getReader();
		//
		// 		// Listen to data coming from the serial device.
		// 		let txt = "";
		// 		while (true) {
		// 			const { value, done } = await reader.read();
		// 			if(value != "") {
		// 				if(value == "g") {
		// 					break;
		// 				}
		//
		// 				txt = txt + value;
		// 				console.log(value);
		// 			}
		// 		}
		//
		// 		console.log(txt);
		//
		// 		const textEncoder = new TextEncoderStream();
		// 		const writableStreamClosed = textEncoder.readable.pipeTo(port.writable);
		//
		// 		reader.cancel();
		// 		await readableStreamClosed.catch(() => { /* Ignore the error */ });
		//
		// 		writer.close();
		// 		await writableStreamClosed;
		//
		// 		await port.close();
		// 	}
		// }

		// async function readData() {
		// 	ports = await navigator.serial.getPorts();
		// 	if(ports) {
		// 		port = ports[0];
		// 	}
		//
		// 	await port.open({baudRate:1200});
		// 	const decoder = new TextDecoder();
		// 	if(port.readable) {
		// 		reader = port.readable.getReader();
		// 		let txt = "";
		// 		while(true) {
		// 			const { value, done } = await reader.read();
		// 			if(done) {
		// 				reader.releaseLock();
		// 				break;
		// 			}
		// 			console.log(value);
		// 			// var num = decoder.decode(value);
		// 			// if(num != "") {
		// 			// 	if(num == "g") {
		// 			// 		reader.releaseLock();
		// 			// 		$('#result').val(parseFloat(txt));
		// 			// 		break;
		// 			// 	}
		// 			//
		// 			// 	txt = txt + num;
		// 			// 	console.log(num);
		// 			// }
		// 		}
		//
		// 		// $('#result').val(parseFloat(txt));
		// 		// reader.releaseLock();
		// 		reader.cancle();
		// 		await port.close();
		// 	}
		// }


		function getConnect() {
			port = navigator.serial.requestPort({ filter })
		}

	</script>

<?php $this->load->view('include/footer'); ?>
