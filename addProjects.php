<?php 

	include('config/db_connect.php');

	$projectName;
	$projectBy;
	$projectId = 0;
	$projectCreatedAt;
	// $comptId = 0;

	$id_to_delete;

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

		// Check if the projectName parameter is set
		if(isset($_POST['projectName']) && isset($_POST['projectBy'])) {

		    // Get the value of projectName
		    $projectName = $_POST['projectName'];
		    $projectBy = $_POST['projectBy'];
		    $projectCreatedAt = $_POST['date'];
		    
		   	// sending data to database
			$projectId = mysqli_real_escape_string($conn, $projectId);
			$projectName = mysqli_real_escape_string($conn, $projectName);
			$projectBy = mysqli_real_escape_string($conn, $projectBy);
			$projectCreatedAt = mysqli_real_escape_string($conn, $projectCreatedAt);

			// checking the data exist in database
			$sql = "SELECT MAX(projectId) AS highest_projectId FROM projects";

			// get result 
		    $result = mysqli_query($conn, $sql);

		    // fetch data as an array 
		    $usedId = mysqli_fetch_assoc($result);

			if ($usedId != null) {
				$projectId = $usedId['highest_projectId'];
				$projectId++;
				$sql = "INSERT INTO projects(projectId, projectName, project_by, created_at) VALUES('$projectId', '$projectName', '$projectBy', '$projectCreatedAt')";
				mysqli_query($conn, $sql);
			}
			else {
				$projectId += 1;
				$sql = "INSERT INTO projects(projectId, projectName, project_by, created_at) VALUES('$projectId', '$projectName', '$projectBy', '$projectCreatedAt')";
			}
			// Exit to prevent further execution and avoid echoing the rest of the page
			echo $projectId;
	    	exit;
		}
	}

	// // Getting comptId using get method
	// if (isset($_GET['comptId'])) {

	// 	$comptId = mysqli_real_escape_string($conn, $_GET['comptId']);

	// 	if (isset($_POST['projectName'])) {
	// 		$sql = "INSERT INTO projects(comptId) VALUES('$comptId')";
	// 		mysqli_query($conn, $sql);
	// 	}
	// }
	// else {
	// 	echo "compt unsuccessful!";
	// }

	// $comptId = mysqli_real_escape_string($conn, $comptId);
	$sql = "SELECT projectName, project_by, created_at, projectId FROM projects";

	$result = mysqli_query($conn, $sql);

	// fetch data as an array 
  	$projects = mysqli_fetch_all($result, MYSQLI_ASSOC);

  	// print_r($projects);

  	// Delete Project start

	if (isset($_GET['id'])) {

	  $id_to_delete = mysqli_real_escape_string($conn, $_GET['id']);

		// echo $id_to_delete;		

		$sql = "DELETE FROM projects WHERE projectId = $id_to_delete";

		if (mysqli_query($conn, $sql)) {
			header("Location: addProjects.php");
		}
		else {
			echo 'query error: ' . mysqli_error($conn);
		}

	}

	// Delete Project End

	// free result from memory
	mysqli_free_result($result);

	// Close Connection from database
	mysqli_close($conn);


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Add Projects</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

	<style>
    	.btn-add-project a {
    		color: #fff;
    		text-decoration: none;
    	}
    </style>

</head>
<body>

	<div id="backend-data"></div>

	<nav class="navbar navbar-expand-lg navbar-light bg-light ">
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
		<h2 class="text-center">Add Projects</h2>
		<div>
			<div class="mb-3">
			  <label for="project-name">Project Name</label>
			  <input type="text" class="form-control" id="project-name" name="project-name">

			  <label for="project-by">Project By</label>
			  <input type="text" class="form-control" id="project-by" name="project-by">

			  <div id="inp-error" style="display: none; color: red;"></div>
			  <div class="form-text">Add projects to your list.</div>
			</div>
			<button type="submit" class="btn btn-primary" id="btn-add-project" name="submit-project">Add to list</button>
		</div>

		<div id="ProjectTable" class="my-4">
			<h2>Projects</h2>
			<table class="table">
			  <thead>
			    <tr>
		    	  <th scope="col"></th>
			      <th scope="col">Project Name</th>
			      <th scope="col">Project By</th>
			      <th scope="col">Date</th>
			      <th scope="col">Actions</th>
			    </tr>
			  </thead>
			  <tbody id="tableBody">
			  	<?php foreach($projects as $project) { ?>
				  	<tr>
				  		<td style="font-weight: bold;">-</td>
					 	  <td><?php echo htmlspecialchars($project['projectName']); ?></td>
					 	  <td><?php echo htmlspecialchars($project['project_by']); ?></td>
					 	  <td id="dateBox"><?php echo htmlspecialchars($project['created_at']); ?></td>
					 	  <td> 
					 	      <!-- Delete form -->
									<button type="submit" class="btn btn-primary" id="btn-delete" name="delete">
										<a style="color: #fff; text-decoration: none;" href="addProjects.php?id=<?php echo htmlspecialchars($project['projectId']); ?>">Delete</a>
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

    	let	btnAddProject = document.getElementById("btn-add-project");
    	let projectName =  document.getElementById("project-name");
    	let projectBy =  document.getElementById("project-by");
    	let backendData = document.getElementById("backend-data");

    	let tableBody = document.getElementById("tableBody");

    	let dateBox = document.getElementById("dateBox");

    	let date = new Date();

    	let inpError = document.getElementById("inp-error");

    	let projectId = <?php echo $projectId; ?>;

    	btnAddProject.onclick = () => {

    		if (projectName.value != "" && projectBy.value != "") {

    			inpError.style.display = "none";

    			// sending data using ajax
			    $.post("addProjects.php", {"projectName": projectName.value, "projectBy": projectBy.value, "date": date.toDateString()},function(data) {
					projectId = parseInt(data);

					// Create new row
		            let newRow = document.createElement("tr");
		            newRow.innerHTML = `
		                <tr>
					  		<td style="font-weight: bold;">-</td>
						 	  <td>${projectName.value}</td>
						 	  <td>${projectBy.value}</td>
						 	  <td id="dateBox">${date.toDateString()}</td>
						 	  <td>	 	   
						 	      <!-- Delete form -->
										<button type="submit" class="btn btn-primary" id="btn-delete" name="delete">
											<a style="color: #fff; text-decoration: none;" href="addProjects.php?id=${projectId}">Delete</a>
									</button>
							  </td>
					  	</tr>
	          		`;

			      	// Append the new row to the table
			      	tableBody.appendChild(newRow);
		   
					// Reset input field
			      	projectName.value = "";
			      	projectBy.value = "";
					});
			}
			else {

				inpError = document.getElementById("inp-error");
				inpError.innerHTML = "The input field is empty!";
				inpError.style.display = "block";
			}
    	};
    </script>

    <script>
    	// let id_to_delete = document.getElementById("id_to_delete");

    	// console.log(id_to_delete.value);

    	// // sending data using ajax
	    // $.post("addCompetitions.php", {"id_to_delete": id_to_delete.value}, function(data) {
		// 	backendData.innerHTML = data;
		// });
    	
    </script>

</body>
</html>