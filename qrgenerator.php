<?php 
	
	// conneting to database
	include('config/db_connect.php');

	$visitorName = '';
	$visitorId = 0;
	$isVoteGiven = 'false';
	$createdAt = '';
	$entered_at = '';

	$response = '';

	// Getting Data by POST Method
	// Start POST

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

		// store front-end data to variables
		$visitorName = $_POST['visitorName'];

		// echo $visitorId;

        // sending data to database
		$visitorName = mysqli_real_escape_string($conn, $visitorName);

		// checking the data exist in database
		$sql = "SELECT MAX(visitorId) AS highest_visitorId FROM visitorRegistration";

		// get result 
	    $result = mysqli_query($conn, $sql);

	    // fetch data as an array 
	    $usedId = mysqli_fetch_assoc($result);

	    // print_r($usedId);

	    // header('Content-Type: application/json');

		if ($usedId != null) {
			$visitorId = $usedId['highest_visitorId'];
			$visitorId++;
			$sql = "INSERT INTO visitorRegistration(visitorId, visitorName, isVoteGiven) VALUES('$visitorId', '$visitorName', '$isVoteGiven')";
			mysqli_query($conn, $sql);
			$response = ['visitorId' => $visitorId];
		}
		else {
			$visitorId++;
			$sql = "INSERT INTO visitorRegistration(visitorId, visitorName, isVoteGiven) VALUES('$visitorId', '$visitorName', '$isVoteGiven')";
			$response = ['visitorId' => $visitorId];
		}
		

		// if ($usedId != null) {
		// 	$visitorId = $usedId['highest_visitorId'];
		// 	$visitorId++;
		// 	echo $visitorId;
		// 	$sql = "INSERT INTO visitorRegistration(visitorId, visitorName, isScoreGiven) VALUES('$visitorId', '$visitorName', '$isScoreGiven')";
		// 	mysqli_query($conn, $sql);
		// }
		// else {
		// 	$sql = "INSERT INTO visitorRegistration(visitorId, visitorName, isScoreGiven) VALUES('$visitorId', '$visitorName', '$isScoreGiven')";
		// 	mysqli_query($conn, $sql);
		// 	}
		
		// Getting created_at

		// write query
	    $sql = "SELECT created_at FROM visitorRegistration WHERE visitorId='$visitorId'";

	    // get result 
	    $result = mysqli_query($conn, $sql);

	    // fetch data as an array 
	    $createdAt = mysqli_fetch_assoc($result);

		$entered_at = $createdAt['created_at'];

		$response = ['visitorId' => $visitorId, 'entered_at' => $entered_at];
    	echo json_encode($response);

		// echo $entered_at;
	
		// $entered_at = date($createdAt['created_at']);

		// echo "Visitor entered at";

		// free result from memory
		mysqli_free_result($result);

		// Close Connection from database
		mysqli_close($conn);

		exit();

	
	}
	// echo $visitorId;

		

	// End POST

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>QRCode Generator</title>
	<style>
		* {
			padding: 0;
			margin: 0;
			box-sizing: border-box;
			font-family: Poppins, sans-serif;
		}

		body {
			background: #262a2f;
		}

		.container {
			width: 100%;
			position: absolute;
			top: 25%;
			display: flex;
			justify-content: space-around;
		}

		.sec-generate {
			width: 400px;
			height: fit-content;
			max-height: 405px;
			padding: 25px 35px;
			background: #fff;
			border-radius: 10px;
		}

		.sec-generate p {
			font-weight: 600;
			font-size: 15px;
			margin-bottom: 8px;
		}

		.sec-generate input {
			width: 100%;
			height: 50px;
			border: 1px solid #494eea;
			outline: 0;
			padding: 10px;
			margin: 10px 0 20px;
			border-radius: 5px;
		}

		.sec-generate #btn-generate {
			width: 100%;
			height: 50px;
			background: #494eea;
			color: #fff;
			border: 0;
			outline: 0;
			border-radius: 5px;
			box-shadow: 0 10px 10px rgba(0, 0, 0, 0.1);
			cursor: pointer;
			font-weight: 500;
			margin: 20px 0;
			text-align: center;
		}

		.sec-generate #imgbox {
			width: 100%;
			border-radius: 5px;
			max-height: 300px;
			overflow: hidden;
			transition: max-height 1s;
			text-align: center;
			display: flex;
			justify-content: center;
			align-items: center;
			padding: 10px;
		}

		#imgBox img {
			width: 100%;
			max-width: 150px;
			max-height: 150px;
		}

		/*#imgBox.show-img {
			max-height: 300px;
			margin: 10px auto;
			border: 1px solid #d1d1d1;
		}


		/*	Section Print	*/

		.sec-print {
			border-left: 2px solid grey;
			padding-left: 30px;
		}

		#btn-print {
			width: 100%;
			height: 50px;
			background: #494eea;
			color: #fff;
			border: 0;
			outline: 0;
			box-shadow: 0 10px 10px rgba(0, 0, 0, 0.1);
			cursor: pointer;
			font-weight: 500;
			margin: 20px 0;
		}
	</style>
</head>
<body>

	<div id="responseMessage" style="color: white;"></div>

	<div class="container">
		<section class="sec-generate">
			<form id="qrForm" method="POST">
				<p>Enter your text or URL</p>
				<input placeholder="Text or URL" id="qrText" name="qrText" required>
				<!-- <input type="hidden" name="visitorIdBox" id="visitorIdBox"> -->
				<div id="imgbox">
					<img id="qrImage" src="" name="qrImage">
				</div>
				<input type="submit" name="submit" id="btn-generate" value="Generate QRCode">
			</form>
		</section>
		<!-- <div id="v-line" style="border-left: 2px solid grey; margin: 0 150px;"></div> -->
		<section class="sec-print" id="qrPrint">

			<div id="visitorCard" style="padding: 10px; width: 300px; height: 400px; background: #fff; display: flex; align-items: center; flex-direction: column;">

				<div style="width: fit-content; height: fit-content;">
					<img src="images/MaymarLogoWithName.png" id="maymarLogo" style="height: 180px; width: 180px; margin-bottom: 5px;">
				</div>
				
				<h1 id="lb-visitor" style="margin-bottom: 5px;">Visitor</h1>

				<p id="prVisitorName" style="margin-bottom: 10px; font-size: 19px;"></p>

				<div>
					<img src="" id="prQrImage" style="width: 60px; height: 60px; margin-bottom: 2rem;">
				</div>

				<p id="entered-at" style="width: 100%; height: 20px; text-align: center;"></p>

			</div>

			<br>

			<input type="button" name="print" id="btn-print" value="Print" onclick="printVisitorCard()">

		</section>
	</div>

	

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

 	<script>

 		// Getting references

 		// Section Generate QR Code
		let imgbox = document.getElementById("imgbox");
		let qrText = document.getElementById("qrText");
		let btnGenerate = document.getElementById("btn-generate");
		let qrImage = document.getElementById("qrImage");
		let qrForm = document.getElementById("qrForm");

		let secPrint = document.getElementById("sec-print")

		// let visitorIdBox = document.getElementById("visitorIdBox");
		
		// let visitorId = 0;

		//  Section Print QR Code
		let prVisitorName = document.getElementById("prVisitorName");
		let maymarLogo = document.getElementById("maymarLogo");
		let prQrImage = document.getElementById("prQrImage");
		let enteredAt = document.getElementById("entered-at");
		let btnPrint = document.getElementById("btn-print");

		// let visitorId;

		btnGenerate.onclick = () => {
			if (qrText.value != "") {

				// Add event listener to form to prevent default submission
			    document.getElementById("qrForm").addEventListener("submit", function(event) {
			    	event.preventDefault();
			    })

				// sending data using ajax
			    $.post("qrgenerator.php", {"visitorName": qrText.value}, function(data) {
					

					// console.log("Response:", data);

					// Remove any non-JSON characters before parsing
				    let trimmedData = data.trim();
				    let responseData;
				    try {
				        // Attempt to parse the JSON data
				        responseData = JSON.parse(trimmedData);
				    } catch (error) {
				        // Handle parsing errors
				        console.error("Error parsing JSON:", error);
				        return;
				    }

				    // Extract the visitorId from the response
				    let visitorId = responseData.visitorId;
					let enteredAtValue = responseData.entered_at;

					enteredAt.innerHTML = enteredAtValue;

					// Chaning the 

				    // Proceed with further processing
				    // console.log(visitorId);

					let qrImageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" + visitorId;

					// Update the QR code image
					qrImage.src = qrImageUrl;

					// console.log('VisitorId is: ' + visiterId);
					imgbox.classList.add("show-img");

					cardData();

				});

			}
			
		}

		function encodeId() {

			// visitorId += 1;

			// // find visitorId's length
			// let visitorIdLength = String(visitorId).length;

			// // add multiple charaters before and after visiterId
			// let stringId = 12ddfbanikk${visitorId}aklsdfj;

			// // shuffle the string to make it random
			// var shuffled = stringId.split('').sort(function(){return 0.5-Math.random()}).join('');

			// // search the index of visitorId in the string
			// let hiddenId1 = shuffled.search(${visitorId});
			// console.log(hiddenId1);

			// // checking when shuffling visitorId index become less than 0
			// if (hiddenId1 < 0) {
			//     hiddenId1 = 0;
			//     // If visiterId is not found, shuffle it to the beginning of the string
			//     shuffled = visitorId + shuffled.replace(visitorId, '');
			// }

			// // getting the actual visitorId from shuffled string
			// let hiddenId2 = shuffled.substring(hiddenId1, hiddenId1 + visitorIdLength);
			// console.log(hiddenId2);

			// visitorIdBox.value = btoa(String(shuffled));
			// visitorIdBox.value = visitorId;

		}

		function generateQRCode() {

			let qrImageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" + visitorId;

			// Update the QR code image
			qrImage.src = qrImageUrl;

			// console.log('VisitorId is: ' + visiterId);
			imgbox.classList.add("show-img");
		}


		function cardData() {
			prVisitorName.innerHTML = qrText.value;
			// maymarLogo.src = "images/MaymarLogo.png";
			prQrImage.src = qrImage.src;
		}

		function printVisitorCard() {
    var divToPrint = document.getElementById("visitorCard");
    var newWin = window.open("", "", "width=400,height=500");

    // Copy the contents of the div to the new window
    newWin.document.write('<html><head><title>Print</title>');
    newWin.document.write('<style>');
    newWin.document.write(`
        body {
            font-family: Poppins, sans-serif;
        }
    `);
    newWin.document.write('</style></head><body>');
    newWin.document.write(divToPrint.outerHTML);
    newWin.document.write('</body></html>');

    // Add an event listener to wait for the image to load
    var image = newWin.document.getElementById('prQrImage');
    image.onload = function() {
        newWin.print();
        newWin.close();
    };

    // In case the image is cached and already loaded
    if (image.complete) {
        image.onload();
    }
}

	</script>

</body>
</html>