<?php
//start session to use data storing in the session
header("Content-Type: text/html;charset=utf-8");
// temporarily store all the information needed
$username = isset($_POST['username']) ? $_POST['username'] : "";
$password = isset($_POST['password']) ? $_POST['password'] : "";
$re_password = isset($_POST['re_password']) ? $_POST['re_password'] : "";
$firstname = isset($_POST['firstname']) ? $_POST['firstname'] : "";
$lastname = isset($_POST['lastname']) ? $_POST['lastname'] : "";
$email = isset($_POST['email']) ? $_POST['email'] : "";
$city = isset($_POST['city']) ? $_POST['city'] : "";
$state = isset($_POST['state']) ? $_POST['state'] : "";
$country = isset($_POST['country']) ? $_POST['country'] : "";
$affilation = isset($_POST['affilation']) ? $_POST['affilation'] : "";

if ($password == $re_password) { //works only if the re-enter password is correct
    $conn = mysqli_connect('localhost','root','736;*9f,cQZ6&XyX','project2');
    mysqli_set_charset($conn,"utf8");

    $sql = "SELECT username FROM Users WHERE username = '$username'"; // check if the username is duplicate, since username should be distinct
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);

    if ($count > 0) {
        // plural results exist, then the username is invalid to use
        exit("<script>alert('Username already exists'); window.history.go(-1)</script>");// show error message and go back to the register page, which is the previous visited page in the history
    } 
    else {
        // if all are valid, then create an user information in database with offered information
        $sql_insert = "INSERT INTO Users(firstname,lastname,username,email,password,city,state,country,affilation) VALUES ('$firstname','$lastname','$username','$email','$password','$city','$state','$country','$affilation')";
        mysqli_query($conn, $sql_insert);
        header('location: Login.html');
        echo "<script>alert('Success!');location.href='Login.html';</script>";
    } //关闭数据库
    mysqli_close($conn);
} else {
    exit("<script>alert('Double check your passwords'); window.history.go(-1)</script>");
} ?>