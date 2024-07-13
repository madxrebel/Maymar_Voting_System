<?php 

	if (isset($_POST['submit'])) {
		echo "submit";
	}
	else {
		echo "not submit";
	}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>test qrgenerator</title>
</head>
<body>

	<section class="sec-generate">
		<form id="qrForm" action="qrgenerator.php" method="POST">
			<p>Enter your text or URL</p>
			<input placeholder="Text or URL" id="qrText" name="qrText">
			<div id="imgbox">
				<img id="qrImage" src="">
			</div>
			<input type="button" name="submit" value="Generate QR Code" id="btn-submit">
		</form>
	</section>

</body>
</html>