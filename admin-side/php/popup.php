<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Popup Design</title>
	<link rel="stylesheet" type="text/css" href="css/popup.css">
	<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/remixicon@2.2.0/fonts/remixicon.css'>
</head>
<body>
	<div class="container">
		<button type="submit" class="btn" onclick="openPopup()">Submit</button>
		<div class="popup" id="popup"><br>
			<i class="ri-checkbox-circle-line" style="font-size: 10em; color: #0BA350;"></i>
			<strong><h2>Successfully Submitted</h2></strong>
			<p>The application period has been successfully submitted in the Application Qualifications & Requirements section.</p>
			<div style="padding: 10px;">
			<button type="button" onclick="closePopup()" style="margin-right: 15px;"><i class="ri-close-fill"></i>Cancel</button>
			<button type="button" onclick="closePopup()"><i class="ri-check-line"></i>Comfirm</button>
			</div>
		</div>

	</div>

<script>
	let popup = document.getElementById("popup");

	function openPopup(){
		popup.classList.add("open-popup")
	}
	function closePopup(){
		popup.classList.remove("open-popup")
	}
</script>
</body>
</html>