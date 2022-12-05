<?php

$db_hostname = "localhost";
$db_username = "root";
$db_password = "";
$db_database = "filuppladdning";
$database = new mysqli($db_hostname, $db_username, $db_password, $db_database);

if ($database->connect_error) {
    die("connection to database failed :(");
}

$signingIn = isset($_POST["sign-in"]);
$signingUp = isset($_POST["sign-up"]);

if ($signingIn || $signingUp) {
    $username = $_POST["username"];
    $password = $_POST["password"];
}

if ($signingIn) {
    if (validLogin()) { // login success
        echo "you are now logged in :)<br>";
        session_start();
        $_SESSION["user"] = $_POST["username"];
    } else {
        echo "wrong login<br>";
    }
} else if ($signingUp) {
    if (validNewUsername()) {
        echo "new user added";
        $database->query("INSERT INTO users (username, password) VALUES ('$username', '$password')");
    } else {
        echo "invalid new username<br>";
    }
}

echo "<a href='index.php'>Go home</a>";

function validLogin()
{
    global $database;
    global $username;
    global $password;

    $password_query = $database->query("SELECT * FROM users WHERE username='$username'");
    return $password_query->fetch_assoc()["password"] === $password;
}

function validNewUsername()
{
    global $database;
    global $username;

    $username_query = $database->query("SELECT * FROM users WHERE username='$username'");
    return !$username_query->fetch_assoc();
}
