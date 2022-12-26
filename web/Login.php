<?php
   header("Content-type:text/html;charset=utf-8");
   $conn = mysqli_connect('localhost','root','736;*9f,cQZ6&XyX','project2');

   session_start(); //start session to store user's information, so we can call them in the future

   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
      
      $myusername = mysqli_real_escape_string($conn,$_POST['username']);
      $mypassword = mysqli_real_escape_string($conn,$_POST['password']); 
      
      if (empty($myusername)||empty($mypassword)){
            // error detection: the username and password neither should be empty
            exit("<script>alert('Username or Password cannot be empty!'); window.history.go(-1)</script>");// go back the previous visited page which is log-in page
      }
      // check the validity of the username and the password
      $sql = "SELECT uid, username, password FROM Users WHERE username = '$myusername' and password = '$mypassword'";
      
      $result = mysqli_query($conn,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      
      $count = mysqli_num_rows($result);
      
      // If result matched $myusername and $mypassword, table row must be 1 row
      
      if($count == 1) {
         // if there exist a row of fetched information, there all is good
         // store some user information in the session for following operations
         $_SESSION['login_user'] = $myusername;
         $_SESSION['islogin'] = 1;
         $_SESSION['uid'] = $row["uid"];
         header('location: homepage.php');
      }else { 
         //error detection: either username or password is wrong or meaningless
         exit("<script>alert('Your Login Name or Password is Invalid.'); window.history.go(-1)</script>");
      }
   }
?>