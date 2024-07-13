<?php 
    
    session_start();
    include('../config/db_connect.php');

    $visitorId = '';
    $projectId = '';

    $isVoteGiven = '';

    $qrScannerDisplayStyle = '';
    $projectsDisplayStyle = '';

    $isFalse = 'false';

    if (isset($_POST['visitorId'])) {
        $visitorId = $_POST['visitorId'];
        $visitorId = mysqli_real_escape_string($conn, $visitorId);

        // Set the session variable for visitorId
        $_SESSION['visitorId'] = $visitorId;


        // create the query
        $sql = "SELECT isVoteGiven FROM visitorRegistration WHERE visitorId='$visitorId'";

        // get result 
        $result = mysqli_query($conn, $sql);

        // fetch result to an associative array 
        $isVoteGiven = mysqli_fetch_assoc($result);

        // print_r($isVoteGiven);

        if ($isVoteGiven['isVoteGiven'] === "false") {
            $isFalse = "true";
        }

        // echo "isFalse: " . $isFalse;
        $response['visitorId'] = $visitorId;
        $response['isFalse'] = $isFalse;

        echo json_encode($response);
        exit();
    }



    if(isset($_POST['projectId'])) {
        
        // Get the visitorId from the session
        if (isset($_SESSION['visitorId'])) {
            $visitorId = $_SESSION['visitorId'];
        }

        $projectId = $_POST['projectId'];
        $projectId = mysqli_real_escape_string($conn, $projectId);


        // Return any response if necessary
        echo "Received projectId: " . $projectId;

        // Insert vote logic here (e.g., insert into the database)
        $sql = "UPDATE visitorRegistration SET projectId='$projectId', isVoteGiven='true' WHERE visitorId='$visitorId'";
        mysqli_query($conn, $sql);


        // Getting votes from projects
        $sql2 = "SELECT votes FROM projects WHERE projectId = $projectId";
        // Getting result
        $result2 = mysqli_query($conn, $sql2);
        // Fetching data as an associative array
        $votes = mysqli_fetch_assoc($result2);
        $votesIncremented = $votes['votes'];
        $votesIncremented++;

        echo $votesIncremented;

        $sql3 = "UPDATE projects SET votes='$votesIncremented' WHERE projectId='$projectId'";
        mysqli_query($conn, $sql3);

        exit();
    }
    

    // create the query
    $sql = "SELECT projectName, project_by, projectId FROM projects";

    // get result 
    $result = mysqli_query($conn, $sql);

    // fetch result to an associative array 
    $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // print_r($projects);

    // free result from memory
    mysqli_free_result($result);

    // close connection to database
    mysqli_close($conn);

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- font awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css"
    integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">


    <title>Vote</title>

    <style>

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f5f5f5;
        }

        .container {
            /*margin: 2% auto;
            padding: 0 27px;*/

            margin: 2% 13%;
        }

        .nav-wrapper {
            display: block;
            width: 100%;
            height: 150px;
            background: linear-gradient(#ffd55c 95%, #5a95ff 5%);
        }


        /*    for scanner    */

        #qrscanner {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #reader {
            width: 600px;
        }
        #result {
            text-align: center;
            font-size: 1.5rem;
        }

        /* Ruled Grid */
        .ruled-grid {

            --gap: 2em;
            --line-thickness: 2px;
            --line-color: #cccccc;
            --line-offset: calc(var(--gap) / 2);

            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            grid-auto-rows: minmax(min-content, 1fr);
            align-items: stretch;
            gap: var(--gap);
            overflow: hidden;
        }

        /* Pseudo Elements */
        .ruled-grid > .card::before, 
        .ruled-grid > .card::after{
            content: "";
            background-color: var(--line-color);
            position: absolute;
        }

        .ruled-grid > .card {
            position: relative;
            padding: 1.5em 0;
        }

        /* Row lines */
        .ruled-grid > .card::after {  
            width: 100vw;
            height: var(--line-thickness);
            top: calc(var(--line-offset) * -1);;
            left: 0;
        }

        /* Column lines */
        .ruled-grid > .card::before {
            width: var(--line-thickness);
            height: 100vh;
            top: 0;
            left: calc(var(--line-offset) * -1);
        }

        /* projects */

        #projects {
            display: none;
        }

        #projects>h1 {
            font-size: 40px;
            color: #373736;
            margin-bottom: 5px;
        }

        #projects>p {
            font-size: 25px;
            font-weight: bolder;
            color: #88878b;
            margin-bottom: 2rem;
        }

        #projects .project-by {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
            letter-spacing: .5px;
            text-align: center;
        }

        #projects .project-by #team-name {
            color: red;
            font-size: 19px;
            display: block;
            width: 100%;
        }

        #projects .img-container {
            text-align: center;
        }

        #projects .project-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid black;
            /* background-color: red; */
            margin-bottom: 10px;
        }

        #projects .project-title {
            font-size: 19px;
            font-weight: bolder;
            color: #6b6a6e;
            text-align: center;
        }

/*        popup       */
        
        .popup {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
        }

        .popup-content {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            width: 50%;
            max-width: 500px;
            position: relative;
            animation: fadeIn 0.3s ease-in-out;
        }

        .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 25px;
            cursor: pointer;
            color: #333;
        }

        .close-btn:hover {
            color: #e74c3c;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

    </style>

</head>

<body>
    <div id="responseMessage"></div>

    <!-- navbar -->
    <header>
        <nav class="nav-wrapper">
            <div class="nav-container">

            </div>
        </nav>
    </header>

    <div id="error" style="display: none;">You have already given vote.</div>

    

    <!-- projects -->
    <section class="container" id="projects">

        <h1>Vote Now</h1>
        <p>Open Until Today 2:00 o'clock</p>

        <div class="ruled-grid">
            <?php foreach ($projects as $project) { ?>
                <div class="card project" id="<?php echo htmlspecialchars($project['projectId']); ?>">
                    <div class="project-by">
                        <span>Team</span>
                        <span id="team-name"><?php echo htmlspecialchars($project['project_by']); ?></span>
                    </div>
                    <div class="img-container"><img class="project-img" onclick="projectClicked(this)" src="../images/demo.jpg" alt="Project pic"></div>
                    <div class="project-title"><?php echo htmlspecialchars($project['projectName']); ?></div>
                </div>
            <?php } ?>
        </div>

    </section>

    <!-- qrscanner -->
    <div id="qrscanner" class="container">
        <div id="reader"></div>
        <div id="result"></div>
    </div>

    <!-- popup -->

    <div id="popup" class="popup">
        <div class="popup-content">
            <span class="close-btn" onclick="closePopup()">&times;</span>
            <p>Thanks for voting us!</p>
        </div>
    </div>

    <footer style="display: flex; justify-content: center; align-items: center; color: #9e9e9e; height: 80px; background-color: #ffd55c; font-size: 19px; font-weight: bolder;">
        <div>Copyright &copy; 2024 Maymar E Noe Academy</div>
    </footer>

    <script src="./node_modules/html5-qrcode/html5-qrcode.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.4/html5-qrcode.min.js" integrity="sha512-k/KAe4Yff9EUdYI5/IAHlwUswqeipP+Cp5qnrsUjTPCgl51La2/JhyyjNciztD7mWNKLSXci48m7cctATKfLlQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>

        const scanner = new Html5QrcodeScanner('reader', { 
            // Scanner will be initialized in DOM inside element with id of 'reader'
            qrbox: {
                width: 250,
                height: 250,
            },  // Sets dimensions of scanning box (set relative to reader element width)
            fps: 20, // Frames per second to attempt a scan
        });


        scanner.render(success, error);
        // Starts scanner

        function success(result) {

            document.getElementById('reader').innerHTML = `
                ${result}
            `;

            let visitorId = result;

            // sending data using ajax
            $.post("vote.php", {"visitorId": visitorId}, function(data) {
               console.log(data);

                responseData = JSON.parse(data);

                let isFalse = responseData.isFalse;

                // console.log("response: " + isFalse);

                // Set display styles based on the value of isFalse
                if (isFalse == "true") {
                    document.getElementById('qrscanner').style.display = "none";
                    document.getElementById('projects').style.display = "block";
                    // $.post("vote.php", {"visitorId": }, function(data2) {
                    //     console.log(data2);
                    // });
                } else {
                    document.getElementById('qrscanner').style.display = "none";
                    document.getElementById('error').style.display = "block";
                }
            });

            scanner.clear();
            // Clears scanning instance

            // Removes reader element from DOM since no longer needed
            document.getElementById('reader').remove();

        }

        function error(err) {
            console.error(err);
            // Prints any errors to the console
        }

    </script>


    

    <script>
        let vote = 0;
        

        function projectClicked(project) {

            let projectId = project.parentNode.parentNode.id;

            $.post("vote.php", {"projectId": projectId}, function(data2) {
                console.log(data2);
            });

            // Open the popup for demonstration
            openPopup();

        }


        // Function to open the popup
        function openPopup() {
            document.getElementById('popup').style.display = 'flex';
        }

        // Function to close the popup
        function closePopup() {
            document.getElementById('popup').style.display = 'none';
            location.reload();
        }

        

    </script>


</body>

</html>