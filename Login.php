<?php
ini_set("session.save_path", "/home/unn_w17004799/sessionData");
session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Login</title>
</head>
<body>
<?php
$username = filter_has_var(INPUT_POST, 'username') ? $_POST['username'] : null;
$username = trim($username);
$password = filter_has_var(INPUT_POST, 'password') ? $_POST['password'] : null;
$password = trim($password);

if (empty($username)   || empty($password)) {
    echo "<p> Provide the Neeeded Information Please</p>";
}

else {
    try {
        unset($_SESSION["username"]);
        unset($_SESSION["logged-in"]);

        require_once("database_conn.php");
        $dbConn = getConnection();

        $querySQL = "SELECT passwordHash FROM nmc_users WHERE username = :username";
        $stmt = $dbConn->prepare($querySQL);
        $stmt->execute(array(":username" => $username));
        $user = $stmt->fetchObject();

        //If there is a record returned
        if($user){
            if(password_verify($password, $user->passwordHash)) {
                echo "<p> Welcome User You can now Proceed to </p>/n";
                echo "<a href ='index.html'>Home</a>/n";

                $_SESSION['logged-in'] = true;
                $_SESSION['username'] = $username;

            } else {
                echo "<p> USERNAME OR PASS INCORRECT<p/>";
            }
        } else {
            echo "<p> Username or Pass Incorrect </p>";

        }
    } catch (Exception $e) {
        echo "Record not found :" . $e->getMessage();
    }
}


?>
</body>
</html>
