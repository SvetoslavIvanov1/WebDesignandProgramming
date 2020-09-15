<?php
ini_set("session.save_path", "/home/unn_w17004799/sessionData");
session_start();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Add a product record - php</title>
</head>
<body>
<?php
if(isset($_SESSION['logged-in']) AND $_SESSION['logged-in'] = true)
require_once("database_conn.php");
$dbConn = getConnection();

$sqlGetCatID = "SELECT DISTINCT catID FROM nmc_category";
$sqlGetPubID = "SELECT DISTINCT pubID FROM nmc_publisher";

$sqlGetCatIDPrep = $dbConn->prepare($sqlGetCatID);
$sqlGetCatIDPrep->execute();

$sqlGetPubIDPrep = $dbConn->prepare($sqlGetPubID);
$sqlGetPubIDPrep->execute();



/* Get each parameter value from the request stream and using ternary if operators check each parameter to see if it was set. If it is, store it in a variable. Otherwise store a value of null in the variable */

$recordID = filter_has_var(INPUT_POST, 'recordID') ? $_POST['recordID'] : null;
$recordTitle = filter_has_var(INPUT_POST, 'recordTitle') ? $_POST['recordTitle'] : null;
$recordYear = filter_has_var(INPUT_POST, 'recordYear') ? $_POST['recordYear'] : null;
$pubID = filter_has_var(INPUT_POST, 'pubName') ? $_POST['pubName'] : null;
$catID = filter_has_var(INPUT_POST, 'catDesc') ? $_POST['catDesc'] : null;
$price = filter_has_var(INPUT_POST, 'recordPrice') ? $_POST['recordPrice'] : null;

$recordID = trim($recordID);
$recordTitle = trim($recordTitle);
$recordYear = trim($recordYear);
$pubID = trim($pubID);
$catID = trim($catID);
$price = trim($price);

$errors = false;

// Check for required variables

if (empty($recordID)) {
    echo "<p>You have not entered a recordID</p>\n";
    $errors = true;
}
else if(!filter_var($recordID, FILTER_VALIDATE_INT)) {
    echo "<p>The recordID should be a number</p>\n";
    $errors = true;
}
if (empty($recordTitle)) {
    echo "<p>You have not entered a record title</p>\n";
    $errors = true;
}
// Check product length
if (empty($recordYear)) {
    echo "<p>You have not entered a record year</p>\n";
    $errors = true;
}

if (empty($pubID)) {
    echo "<p>You have not entered a publisher name</p>\n";
    $errors = true;
}else{
    $pubIDBool=false;
    while ($publisher = $sqlGetPubIDPrep->fetchObject()){
        if ($publisher->pubID == $pubID) $pubIDBool = true;
    }
    if(!$pubIDBool){
        echo "<p>publisher doesnt exist</p>\n";
        $errors = true;
    }
}

if (empty($catID)) {
    echo "<p>You have not entered a category description</p>\n";
    $errors = true;
}else{
    $catIDBool=false;
    while ($category = $sqlGetCatIDPrep->fetchObject()){
        if ($category->catID == $catID) $catIDBool = true;
    }
    if(!$catIDBool){
        echo "<p>category doesnt exist</p>\n";
        $errors = true;
    }
}
if (empty($price)) {
    echo "<p>You have not entered a price</p>\n";
    $errors = true;
}
else if(!filter_var($price, FILTER_VALIDATE_FLOAT,2 ) or $price <= 0) {
    echo "<p>The price should be a number</p>\n";
    $errors = true;
}

if ($errors) {
    echo "<p>Please try <a href='editR.php?recordID=$recordID'>again</a></p>\n";
}

else {
    try {
        $updateSQL = "UPDATE nmc_records 
                      SET recordTitle = :recordTitle,recordYear = :recordYear, pubID = :pubID, catID = :catID, recordPrice = :recordPrice
					  WHERE recordID = :recordID ";
        $stmt = $dbConn->prepare($updateSQL);
        $stmt->execute(array(':recordTitle' => $recordTitle, ':recordYear' => $recordYear, ':pubID' => $pubID, ':catID' => $catID, ':recordPrice' => $price,':recordID' => $recordID));

        echo "<h1>Product details</h1>\n";
        echo "<p>Name: $recordTitle</p>\n";
        echo "<p>Description: $recordYear</p>\n";
        echo "<p>Category: $pubID</p>\n";
        echo "<p>Price: $catID</p>\n";
        echo "<p>Price: $price</p>\n";

    }
    catch (Exception $e) {
        echo "Records not found: " . $e->getMessage();
    }
}
?>

</body>
</html>