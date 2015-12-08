<?php
/**
 * Retrieve the top 20 scores and echo to the page
 */
function printScores() {
	try {
        $dbh = new PDO('mysql:host=localhost;dbname=XXXXXXXX', 'XXXXXXXX', 'XXXXXXXX');
        $sql = 'SELECT name, score FROM scores ORDER BY score DESC LIMIT 20';
        $it = 1;
        foreach ($dbh->query($sql) as $row) {
        	if ($it % 2 == 0) {
        		echo "<tr class=\"alt\"><td><strong>" . $it . ".</strong></td>";
        	} else {
        		echo "<tr><td><strong>" . $it . ".</strong></td>";
        	}        	
        	echo "<td>" . $row['name'] . "</td>";
        	echo "<td>" . $row['score'] . "</td></tr>";
        	$it = $it + 1;
    	}
    } catch (PDOException $e) {
        die("Error connecting to MySQL Database");
    }  
}

/**
 * Insert a score, name pair into the database
 */
function addScore($name, $score) {
    try {        
        $dbh = new PDO('mysql:host=localhost;dbname=XXXXXXX', 'XXXXXXXX', 'XXXXXXXX');
        $sql = "INSERT INTO ArcTetris.scores VALUES (null, :user, :score, CURRENT_TIMESTAMP)";
        $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':user' => $name, ':score' => $score));
        if ($sth->rowCount() > 0) {
            return true;
        }
        return false;
    } catch (PDOException $e) {
        die("Error connecting to MySQL Database");
    }    
}

// Ensure running over HTTPS
if($_SERVER["HTTPS"] != "on") {
   // reportError("Only available through HTTPS", 1);
	die("fail");
}

// Ensure if they're adding a score that they've given a score to add
if ($_POST["action"] == "add" && !isset($_POST["score"])) {
    die("no score provided");
}

if ($_POST["action"] == "add")
{
	if (!isset($_POST["name"])) {
		$name = "Anonymous";
	} else {
		$name = $_POST["name"];
	}
	if (!addScore($name, $_POST["score"])) {
		die("fail");
	} else {
		die("ok");
	}
}
?>
<html>
<head>
<title>ArcTetris Highscores</title>
<style>
h1 {
	font-family: Arial, Helvetica, sans-serif;
	text-align: center;
}
.scores {
	margin-left: auto;
	margin-right: auto;
}
/* some garbage auto generated table style*/
.datagrid table { border-collapse: collapse; text-align: left; width: 100%; } .datagrid {font: normal 12px/150% Arial, Helvetica, sans-serif; background: #fff; overflow: hidden; border: 1px solid #000000; }.datagrid table td, .datagrid table th { padding: 5px 5px; }.datagrid table thead th {background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #999999), color-stop(1, #8F8F8F) );background:-moz-linear-gradient( center top, #999999 5%, #8F8F8F 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#999999', endColorstr='#8F8F8F');background-color:#999999; color:#FFFFFF; font-size: 15px; font-weight: bold; border-left: 1px solid #D1D1D1; } .datagrid table thead th:first-child { border: none; }.datagrid table tbody td { color: #000000; border-left: 1px solid #D1D1D1;font-size: 12px;font-weight: normal; }.datagrid table tbody .alt td { background: #F4F4F4; color: #000000; }.datagrid table tbody td:first-child { border-left: none; }.datagrid table tbody tr:last-child td { border-bottom: none; }
</style>
</head>
<body>
<h1>ArcTetris HighScores</h1>
<div class="datagrid">
<table>
<thead>
<tr>
<th>Rank</th>
<th>Name</th>
<th>Score</th>
</tr>
</thead>
<?php
printScores();
?>
</table>
</div>
</body>
</html>
