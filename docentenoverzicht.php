
<?php
    $sHost = 'localhost';
    $sUser = 'root';
    $sPass = '';
    $sDB = '213server';
    //create connection
    $conStr = mysqli_connect($sHost, $sUser, $sPass, $sDB);
    
    // check connection
if (!($conStr)) {
    die('Failed to connect to MySQL Database Server - #' . mysqli_connect_errno() . ': ' . mysqli_Connect_error());
    if (!mysqli_select_db('slb')) {
        die('Connected to Server, but Failed to Connect to Database - #' . mysqli_connect_errno() . ': ' . mysqli_connect_errno());
    }
} else {

}
    
function sqlResultToArray($sqlResult){
        $rows = [];
        while($row = mysqli_fetch_array($sqlResult)) {
            $rows[] = $row;
        }
        return $rows;
}

function getName($docentId){
    global $conStr;
    $sql = "SELECT Firstname,Prefix,Lastname FROM docenten WHERE DocentID = " . $docentId;
    $result = $conStr->query($sql);
    $name = "";
    //if results still contain rows
    if ($result && $result->num_rows > 0) {
        //output data of each row
        while ($row = $result->fetch_assoc()) {
            //get full name
            $name = $row["Firstname"] . " " . $row["Prefix"] . " " . $row["Lastname"];
        }
    }
    return $name;
}

function getVakken($docentId){
    global $conStr;
    $sql = "SELECT vakken.Vaknaam "
            . "FROM vakken "
            . "INNER JOIN docentenoverzicht ON vakken.VakID = docentenoverzicht.VakID "
            . "WHERE docentenoverzicht.DocentID = " . $docentId;
    $result = $conStr->query($sql);
    $stringVak = "";
    if ($result && $result->num_rows > 0) {
        //output data of each row
        while ($row = $result->fetch_assoc()) {
            $stringVak = $stringVak . $row["Vaknaam"] . "  ";
        }
    }
    return $stringVak;
}

function generateDocenten(){
    global $conStr;
    $sql = "SELECT DocentID FROM docenten";
    $result = $conStr->query($sql);
    if ($result && $result->num_rows > 0) {
        //output data of each row
        while ($row = $result->fetch_assoc()) {
            //fetch values
            $id = $row["DocentID"];
            $name = getName($id);
            $vakken = getVakken($id);
            //generate html
            echo "<center>
                    <div class='docent-label'> 
                        <div class='docent-label-header'></div>
                        <div class='col-sm-2 docent-label-foto'></div>
                        <div class='col-sm-4 docent-label-naam'>leraar: " . $name . "</div>
                        <div class='col-sm-2 docent-label-vakken'>vakken: " . $vakken . "</div>
                    </div>
                 </center>";
        }
    }
}
?>

<html>
<head>
    <title>Docentenoverzicht</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="offcanvas.css"/>

</head>
<body>
    <div id="main">
        <div class="col-sm-12" style="height:50px; background-color: #44A0FF">
            <span style="font-size:30px;cursor:pointer;color: white" onclick="toggleNav()">
                
            </span>
        </div>
        <div class="col-sm-12 Opdrachten-view">
            <?php
            generateDocenten();
            ?>
        </div>
    </div>
</body>
</html>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>