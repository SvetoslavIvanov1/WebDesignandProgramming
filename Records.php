<?php
ini_set("session.save_path", "/home/unn_w17004799/sessionData");
session_start();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title> All dem Records</title>
    <link rel="stylesheet" type="text/css" href="index.css">

</head>
<body>
<<ul>
    <li><a href="index.html">Home</a></li>
    <li><a href="Credits.html">Credits</a></li>
    <li><a href="orderRecordsForm.php">Order Records</a></li>
    <li><a href="Records.php">Records</a></li>
    <li><a href="Logout.php">Logout</a></li>
</ul>

<?php
if(isset($_SESSION['logged-in']) AND $_SESSION['logged-in'] = true) {


    require_once("database_conn.php");
    $dbConn = getConnection();

    $sqlRecordQuery = "SELECT recordID, recordTitle,  recordYear, catDesc, recordPrice
FROM nmc_records
INNER JOIN nmc_category 
ON nmc_records.catID = nmc_category.catID
ORDER BY recordTitle";

    $sqlRecordResult = $dbConn->prepare($sqlRecordQuery);
    $sqlRecordResult->execute();

    echo "<table class='record'>
<tr class='recordHeaderRow'>
<th class='recordHeader'>Title</th>
<th class='recordHeader'>Year</th>
<th class='recordHeader'>Category</th>
<th class='recordHeader'>Price</th>
<th class='recordHeader'>ID</th>
</tr>";

    while ($record = $sqlRecordResult->fetchObject())
        echo "
<tr class='recordInfoRow'>
<td class='recordInfo'><a href='editR.php?recordID={$record->recordID}'>{$record->recordTitle}</a></td>
<td class='recordInfo'>{$record->recordYear}</td>
<td class='recordInfo'>{$record->catDesc}</td>
<td class='recordInfo'>{$record->recordPrice}</td>
<td class='recordInfo'>{$record->recordID}</td>
</tr>";
    echo "</table>";

}

else {
    echo "<a href='index.html'>Click here to log IN </a>";
}
?>


<a href='Logout.php'>Click here to log out</a>