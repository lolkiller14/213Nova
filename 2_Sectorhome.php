<?php
//check if Sector ID is giving with the selection.....yes this is hardcoded hehe
//check if Sector ID is equal to 1 , 2 or 3 if not redirect to starting page
if (!isset($_GET['SectorID']) || !in_array($_GET['SectorID'], [1, 2, 3])){
    echo "<script>window.location.href='/';</script>";
    die();
}
include 'DatabaseConnection.php';

if(isset($_GET['SectorID'])) {
    $SectorID = $_GET['SectorID'];
}
if(isset($_GET['VakID'])){
    $VakID = $_GET['VakID'];
}

function echoGet($var){
    if(isset($_GET[$var])) {
        echo $_GET[$var];
    }
}

function chooseLogo(){
    global $SectorID;
    switch($SectorID){
        case 1: echo "'images/Sectorimg_AO.png'";
        case 2: echo "'images/Sectorimg_ICTB.png'";
        case 3: echo "'images/Sectorimg_NETB.png'";
    }
}

function generateHelp(){
    echo "
        <div class='opdrachten-label'>
            <div class='opdrachten-label-header'><b>
            Welkom op ICT Academie 213Server
            </b></div>
            <div class='opdrachten-label-textbox'>
                Klik op de knop linksbovenin om de vakken weer te geven
                <br>Klik op een vak om de opdrachten weer te geven
            </div>
        </div>  ";
}

function createNav(){
    global $conStr;
    global $SectorID;
    global $NavOpen;
    $sqlNav = "SELECT * FROM vakken WHERE SectorID =" . $SectorID ;
    $result = $conStr->query($sqlNav);
    if ($result && $result->num_rows > 0) {
        //output data of each row
        while ($row = $result->fetch_assoc()) {
            echo'<center><div sector=' . $row["SectorID"] . ' vak=' . $row["VakID"] . ' class="MenuItem">' .
                $row["Vaknaam"] . "</div></center>";
        }
    } else {
        echo "Geen vakken in deze sector gevonden";
    }
}

function generateOpdrachten(){
        global $conStr;
        global $VakID;
        $sqlNav = "SELECT * FROM opdrachten WHERE VakID =" .$VakID;
        $result = $conStr->query($sqlNav);
        if ($result && $result->num_rows > 0) { //output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "  <div class='opdrachten-label'>
                            <div class='opdrachten-label-header'><b>".$row["Titel"] . "</b></div>
                            <div class='opdrachten-label-textbox'>". $row["Omschrijving"] ."</div>
                            <div class='opdrachten-label-teacher'> leraar: ". $row["Verantwoordelijke"] .
                                "<div class='opdrachten-label-download-btn'>
                                    <a href=".$row["Downloadlink"]." download>download</a>
                                </div>
                            </div>
                        </div>  ";
            }
        } 
        else { 
            echo "Geen opdrachten in dit vak gevonden";
        }
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="offcanvas.css"/>
</head>
<body>
<nav id='navbarblue' class="navbar navbar-custom-blue student-nav-side-fix">
    <div  class='nav navbar-nav pull-left'><li id='navspacer'><p></p></li></div>
    <div class='nav navbar-nav pull-left'>
        <li><a style='font-size:30px;cursor:pointer;color: white' onclick="toggleNav()">&#9776;</a></li>
    </div>
    <div class='nav navbar-nav pull-left'><li><a href='1_Landingpage.php'>Naar Sectoroverzicht</a></li></div>
</nav>
<div style="height:50px;"></div>

<div id="mySidenav" class="sidenav">
    <a class="imglink" href="2_Sectorhome.php?SectorID=1">
        <img class="Logo" src=<?php chooseLogo(); ?> style="width:100%;">
    </a>
    <?php createNav() ?>
</div>

<div id="main">
    <div id="content" class="container-fluid opdrachten-view">
        <?php
        if (isset($_GET['VakID'])) {
            generateOpdrachten();
        } else {
            generateHelp();
        }
        ?>
    </div>
</div>

</body>
</html>
<!-- alle java scripts HIER aub dankuwel ;)-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js "></script>
<scrip src="http://code.jquery.com/jquery-1.7.1.min.js"></scrip>
<script src="js/General.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script>
    // 0 = dicht, 1 = open
    var navOpen = false;
    function getInnerWidth(elem) {
        return parseFloat(window.getComputedStyle(elem).width);
    }
    
    function toggleNav() {
        var navbar = document.getElementById("navspacer");
        var sidenav = document.getElementById("mySidenav");
        var main = document.getElementById("main");
        if (navOpen) {
            //close navbar
            sidenav.style.width = "0";
            main.style.marginLeft = "0";
            navbar.style.width = "0";
        } else {
            //open navbar
            sidenav.style.width = "250px";
            main.style.marginLeft = "250px";
            navbar.style.width = "250px";
        }
        navOpen = !navOpen;
    }

    function redirect(id, id2, open) {
        window.location.href = "2_Sectorhome.php?SectorID="+id+"&VakID="+id2+"&NavOpen="+open;
    }
</script>







