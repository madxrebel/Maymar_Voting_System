<?php 

	include('config/db_connect.php');

	$comptName;
	$comptId = 0;
	$comptCreatedAt;

	$id_to_delete;


	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

		
	    // Get the value of comptName
	    $comptName = $_POST['comptName'];
	    $comptCreatedAt = $_POST['date'];
	    
		  // sending data to database
			$comptId = mysqli_real_escape_string($conn, $comptId);
			$comptName = mysqli_real_escape_string($conn, $comptName);
			$comptCreatedAt = mysqli_real_escape_string($conn, $comptCreatedAt);

			// checking the data exist in database
			$sql = "SELECT MAX(comptId) AS highest_comptId FROM Competitions";

			// get result 
	    $result = mysqli_query($conn, $sql);

	    // fetch data as an array 
	    $usedId = mysqli_fetch_assoc($result);

			if ($usedId != null) {
				$comptId = $usedId['highest_comptId'];
				$comptId++;
				$sql = "INSERT INTO Competitions(comptId, comptName, created_at) VALUES('$comptId', '$comptName', '$comptCreatedAt')";
				mysqli_query($conn, $sql);
			}
			else {
				$comptId += 1;
				$sql = "INSERT INTO Competitions(comptId, comptName, created_at) VALUES('$comptId', '$comptName', '$comptCreatedAt')";
				mysqli_query($conn, $sql);
			}
			echo $comptId;
			// Exit to prevent further execution and avoid echoing the rest of the page
	    exit;
	}

	// Get created_at from Competitions
	$sql = "SELECT comptName, created_at, comptId FROM Competitions ORDER BY created_at";

	$result = mysqli_query($conn, $sql);

	// fetch data as an array 
  $competitions = mysqli_fetch_all($result, MYSQLI_ASSOC);


  // Echo the competitions data as JSON to send to the frontend
	// echo json_encode($competitions);

  // print_r($competitions);

  if (isset($_GET['id'])) {

	  $id_to_delete = mysqli_real_escape_string($conn, $_GET['id']);

		echo $id_to_delete;		

		$sql = "DELETE FROM Competitions WHERE comptId = $id_to_delete";

		if (mysqli_query($conn, $sql)) {
			header("Location: addCompetitions.php");
		}
		else {
			echo 'query error: ' . mysqli_error($conn);
		}

	}
	else {
		echo "not deleted";
	}

  
	

	// Close Connection from database
	mysqli_close($conn);


	 // free result from memory
	mysqli_free_result($result);



?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Competitions</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
    	.btn-add-project a {
    		color: #fff;
    		text-decoration: none;
    	}
    </style>
  </head>
  <body>	

  	<div id="backend-data"></div>
  	<div id="competitions-list"></div>


  	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		  <div class="container-fluid">
		    <a class="navbar-brand" href="#">Maymar-e-Noe Academy</a>
		    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		      <span class="navbar-toggler-icon"></span>
		    </button>
		    <div class="collapse navbar-collapse" id="navbarSupportedContent">
		      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
		        <li class="nav-item">
		          <a class="nav-link active" aria-current="page" href="#">Home</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" href="#">About us</a>
		        </li>
		        <li class="nav-item dropdown">
		          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
		            Projects
		          </a>
		          <ul class="dropdown-menu">
		            <li><a class="dropdown-item" href="#">Maymar Science & Tech Expo</a></li>
		            <li><a class="dropdown-item" href="#">Maymar Quiz Competition</a></li>
		            <li><hr class="dropdown-divider"></li>
		            <li><a class="dropdown-item" href="#">Arts and Crafts 2024</a></li>
		          </ul>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" aria-disabled="true">Contact</a>
		        </li>
		      </ul>
		      <form class="d-flex" role="search">
		        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
		        <button class="btn btn-outline-success" type="submit">Search</button>
		      </form>
		    </div>
		  </div>
		</nav>

		<div class="container my-4">
			<h2 class="text-center">Add Competition</h2>
			<div>
				<div class="mb-3">
				  <label for="compt-name">Competition Name</label>
				  <input type="text" class="form-control" id="compt-name" name="compt-name">
				  <div id="inp-error" style="display: none; color: red;"></div>
				  <div class="form-text">Add competition to your list.</div>
				</div>
				<button type="submit" class="btn btn-primary" id="btn-add-compt" name="submit-compt">Add to list</button>
			</div>

			<div id="comptTable" class="my-4">
				<h2>Competitions</h2>
				<table class="table">
				  <thead>
				    <tr>
				    	<th scope="col"></th>
				      <th scope="col">Competition Name</th>
				      <th scope="col">Date</th>
				      <th scope="col">Actions</th>
				    </tr>
				  </thead>
				  <tbody id="tableBody">
				  	<?php foreach($competitions as $competition) { ?>
					  	<tr>
					  		<td style="font-weight: bold;">-</td>
						 	  <td><?php echo htmlspecialchars($competition['comptName']); ?></td>
						 	  <td id="dateBox"><?php echo htmlspecialchars($competition['created_at']); ?></td>
						 	  <td>
						 	      <button class="btn btn-primary btn-add-project">
						 	      	<a href="addProjects.php?comptId=<?php echo htmlspecialchars($competition['comptId']); ?>">Add Projects</a>
						 	      </button>						 	      
						 	      <!-- Delete form -->
										<button type="submit" class="btn btn-primary" id="btn-delete" name="delete">
											<a style="color: #fff; text-decoration: none;" href="addCompetitions.php?id=<?php echo htmlspecialchars($competition['comptId']); ?>">Delete</a>
									</button>
							  </td>
					  	</tr>
					  <?php } ?>
				  </tbody>
				</table>
			</div>
		</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
    	let	btnAddCompt = document.getElementById("btn-add-compt");
    	let comptName =  document.getElementById("compt-name");
    	let backendData = document.getElementById("backend-data");

    	let tableBody = document.getElementById("tableBody");

    	let date = new Date();

    	let inpError = document.getElementById("inp-error");

    	let btnDelete = document.getElementById("btn-delete");

    	let comptId = <?php echo $comptId; ?>;

    	console.log(comptId);

    	btnAddCompt.onclick = () => {

    		if (comptName.value != "") {

    			inpError.style.display = "none";

    			// sending data using ajax
			    $.post("addCompetitions.php", {"comptName": comptName.value, "date": date.toDateString()}, function(data1) {
						backendData.innerHTML = data1;
						comptId = parseInt(data1);

						// Create new row
						let newRow = `
							<tr>
								<td style="font-weight: bold;">-</td>
							  <td>${comptName.value}</td>
							  <td id="dateBox">${date.toDateString()}</td>
							  <td>
						 	      <button class="btn btn-primary btn-add-project">
						 	      	<a href="addProjects.php?comptId=${comptId}">Add Projects</a>
						 	      </button>
						 	      <!-- Delete form -->
										<button type="submit" class="btn btn-primary" id="btn-delete" name="delete">
											<a style="color: #fff; text-decoration: none;" href="addCompetitions.php?id=${comptId}">Delete</a>
										</button>
								  </td>
						  </tr>
						`;

	          // Append the new row to the table
	          tableBody.insertAdjacentHTML("beforeend", newRow);

						// Reset input field
	          comptName.value = "";

					});
    		}
    		else {

    			inpError = document.getElementById("inp-error");
    			inpError.innerHTML = "The input field is empty!";
    			inpError.style.display = "block";
    		}

    	};
  		
    </script>

    


  </body>
</html>