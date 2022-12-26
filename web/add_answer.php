<?php
session_start();//start session to use data storing in the session
if (isset($_POST["abody"])) { #  check if there exist an answer stored in session
    if (isset($_SESSION["islogin"])) { #  check if there exist a logged-in user
      $conn = mysqli_connect("localhost", "root", "736;*9f,cQZ6&XyX", "project2");
      $abody = $_POST["abody"];
  
      $qid = floatval($_SESSION['qid']);
  
      $uid = floatval($_SESSION['uid']);  
  
      $sql = "Call Add_Answer($qid,$uid,'$abody')";
  
      try {
          $result = mysqli_query($conn,$sql);
          if (!$result) {
              throw new Exception($result->errno);
          }  
          header("Location: Q_A.php");
          exit();
      }
      catch (Exception $err) {
          echo "You may not post an answer twice!";
          echo "<br>";
          echo "<a href='Q_A.php'><span class='glyphicon glyphicon-arrow-left'></span> Go Back</a>";
      }
    }
    else{ #  throw exception
       echo "You need to login to perform that action";
    }

} 
?>
<!DOCTYPE html>
<html>
<head>
   <link href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
   <script src="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
   <style type = "text/css">/* use bootstrap here for navigation bar */
         body {
            text-align:center;
            font-family:Cambria, Helvetica, sans-serif;
            font-size:16px;
            margin: 0;
            padding: 0;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
         }
         .navbar{
            margin-bottom: 0px;
            border-radius: 0px;
            background: linear-gradient(to right, #65da9c 0%, #65daab 0%, #65dace 15%, #65ceda 33%, #65daab 66%, #65da9c 90%);;
         }
         .navbar .nav > li > a{
            color: whitesmoke;
            background-color: lightslategrey;
         }
         .navbar .nav >li >a:active{
            color: gray;
            background-color: lightblue;
         }
         .form-content
         {
            border: 0; /* 删除输入框边框 */
            width: 350px;
            margin-left: 15px;
            border-bottom: 1px solid white; /* 输入框白色下划线 */
            border-radius: 20px;
            font-family:Cambria, Helvetica, sans-serif;
         }
         .search-btn
         {
            font-family:Cambria, Helvetica, sans-serif;
            margin-left: 15px;
            width: 100px;
            color: white; /* 按钮字体颜色 */
            border: 0; /* 删除按钮边框 */
            border-radius: 10px;   /* 按钮圆角边 */
            background-image: linear-gradient(to right, #318c83 15%, #2f9c91 33%, #269186 33%);  /* 按钮颜色 */
         }
         #bigBox
         {
            font-family:Cambria, Helvetica, sans-serif;
            margin: 0 auto; 
            margin-top: 30px; 
            padding: 20px 50px; 
            background-color: #00000099;
            width: 500px;
            height: 660px;
            border-radius: 10px;  
            text-align: center;
         }
         
         h1
         {
            font-family:Cambria, Helvetica, sans-serif;
            color: #4fd19d; 
            font-weight: bold; 
            margin-top: 15px;
         }
         #bigBox .submitButton
         {
            font-family:Cambria, Helvetica, sans-serif;
            margin-right: 30px;
            margin-top: 40px;
            width: 100px;
            height: 25px;
            color: white; 
            border: 0;
            border-radius: 20px;   /* 按钮圆角边 */
            background-image: linear-gradient(to right, #65da9c 0%, #65daab 0%, #65dace 15%, #65ceda 33%, #65daab 66%, #65da9c 90%);
         }
         .answer-division
         {
            float: left;
            width: 49%;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            background-image: linear-gradient(to right, rgba(61, 216, 245,1), rgba(93, 210, 232,1), rgba(108, 215, 235,1),rgba(143, 220, 235,0.99),rgba(143, 220, 235,0.95),rgba(143, 220, 235,0.93),rgba(128, 237, 242,0.93),rgba(128, 237, 242,0.9), rgba(128, 237, 242,0.5), rgba(128, 237, 242,0));
            flex: 1;
            overflow-y: auto;
         }
         .question-division
         {
            float: right;
            width: 50%;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            background-image: linear-gradient(to left,rgba(16, 232, 131,1), rgba(41, 214, 133,1), rgba(75, 214, 149,1),rgba(111, 227, 173,1),rgba(111, 227, 173,0.99),rgba(134, 227, 184,0.95),rgba(150, 227, 191,0.93),rgba(174, 230, 204,0.93),rgba(170, 242, 218,0.9), rgba(170, 242, 218,0.5), rgba(170, 242, 218,0));
            flex: 1;
            overflow-y: auto;
         }
         .temp-msg h2{
            font-size:20px;
            color: rgb(0,0,0,0.4);
         }
   </style>
</head>
<nav class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container-fluid">
    <div class="navbar-header">
        <a class="navbar-brand">Q&A</a><!-- navbar header part -->
    </div>
    <div>
        <ul class="nav navbar-nav"><!-- navbar nav-links, including homepage, adding question page and question list page.-->
         <li><a href="homepage.php"><span class='glyphicon glyphicon-home'></span> Homepage</a></li>
            <li><a href="add_question.php"><span class='glyphicon glyphicon-comment'></span> Post a Question</a></li>
            <li><a href="qlist.php"><span class='glyphicon glyphicon-list'></span> Question List</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right"><!-- navbar options about users, including user profile, log-in, log-out -->
         <?php
        
         if (isset($_SESSION['islogin'])) { # check whether there is a logged-in user, if user logged in, then a profile option will show up
            echo "<li><a href='profile.php'><span class='glyphicon glyphicon-user'></span> ".$_SESSION['login_user']."</a></li>";
            echo "<li><a href='Logout.php'><span class='glyphicon glyphicon-log-in'></span> log out</a></li>";
         }
         else { # if there is no logged-in user, only show log-in option
            echo "<li><a href='Login.html'><span class='glyphicon glyphicon-user'></span> log in</a></li>";
         }
         ?>
        </ul>
    </div>
    </div>
</nav>

<div id="bigBox"><!-- answer information collecting region, including an input box and a submit button-->
   <h1><span class='glyphicon glyphicon-check'></span> Answer Details</h1>
        <form id="addanswer"  method="post">

        <textarea class="inputBox",id="abody", name="abody" rows="10" cols="50" maxlength = "200">
        Enter whatever your heart desires to know 
        </textarea>

    <br>

    <input class="submitButton" type="submit" name="submit" value="Post"/>
        </form>
</div>



</body>
</html>
















</body>
</html>