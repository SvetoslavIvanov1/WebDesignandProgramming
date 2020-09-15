<?php
ini_set("session.save_path", "/home/unn_w17004799/sessionData");
session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Records</title>
</head>
<body>

<?php
if(isset($_SESSION['logged-in']) AND $_SESSION['logged-in'] = true)

    $recordID = filter_has_var(INPUT_GET, 'recordID') ? $_GET['recordID'] : null;

    /*IF record is empty prompts the user to go back and reselect
    */
    if(empty($recordID))
    {
        echo"<p>Please select a <a href='Records.php'>record</a></p>";
    }
    /*ELSE displays a chosen record to edit
    */
    else{
        try{
            require_once ("database_conn.php");
            $dbConn = getConnection();
            /*Generate record*/
            $sqlRecordQuery = "SELECT recordID, recordTitle,  recordYear, nmc_records.catID, catDesc,nmc_records.pubID, pubName, recordPrice , location
        FROM nmc_records
        INNER JOIN nmc_category 
        ON nmc_records.catID = nmc_category.catID
        INNER JOIN nmc_publisher
        ON nmc_records.pubID = nmc_publisher.pubID
        WHERE recordID = :recordID";

            /*Dynamic generation of category and publisher*/
            $sqlCategoryQuery = "SELECT DISTINCT catID,catDesc
        FROM nmc_category";

            $sqlPublisherQuery = "SELECT DISTINCT pubID,pubName
        FROM nmc_publisher";

            /*Statement preparation*/
            $sqlRecordResult = $dbConn ->prepare($sqlRecordQuery);
            $sqlRecordResult ->execute(array(":recordID" => $recordID));

            $sqlCategoryResult = $dbConn ->prepare($sqlCategoryQuery);
            $sqlCategoryResult -> execute();

            $sqlPublisherResult = $dbConn ->prepare($sqlPublisherQuery);
            $sqlPublisherResult -> execute();

            /*Form generation */
            $record = $sqlRecordResult ->fetchObject();
            $recordForm = "";
            $recordForm.="
        <form action='update.php' method='POST'>
       <p>ID</p> <input type='text' name='recordID' value={$record->recordID} readonly/>
       <p>Title</p><input type='text' name='recordTitle' value='{$record->recordTitle}' />
        <p>Year</p><input type='number' name='recordYear' value={$record->recordYear} />
        <p>Description</p><select name='catDesc'>";
            /*Dynamic generation of categories

            With a check to set up initial value as given from the record
            */
            while($category = $sqlCategoryResult ->fetchObject())
            {
                if($record->catID == $category->catID){
                    $recordForm.= "<option value='{$category->catID}' selected>{$category->catDesc}</option>";
                } else{
                    $recordForm.= "<option value='{$category->catID}'>{$category->catDesc}</option>";
                }
            }

            $recordForm.="</select>
        <p>Publisher Name</p><select name='pubName'>";
            /*Dynamic generation of publishers

        With a check to set up initial value as given from the record
        */
            while($publisher = $sqlPublisherResult->fetchObject())
            {
                if($record->pubID == $publisher->pubID){
                    $recordForm.= "<option value='{$publisher->pubID}' selected>{$publisher->pubName}</option>";
                } else{
                    $recordForm.= "<option value='{$publisher->pubID}'>{$publisher->pubName}</option>";
                }
            }

            $recordForm.="</select>
       <p>Price</p> <input type='number' name='recordPrice' value={$record->recordPrice} />
               <input type='submit' name='submit' value='Send' class='send'>
        </form>";

            echo $recordForm;
        }
        catch (Exception $e){
            echo "<p>Record details not found: ".$e->getMessage()."</p>\n";
        }

}

?>
</body>
</html>