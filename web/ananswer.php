<?php
if (session_id() == "") { #start session to use data storing in the session
   session_start();
}
if (isset($_POST["answerq"])) { # check if there exist an answer id stored in session, and if so, update the id information
   $_SESSION["qid"] = $_POST["answerq"];
   header("Location: Q_A.php");
   exit();
} 
?>
<!DOCTYPE html>
<html>
<head>
   <link href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
   <script src="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
   <style type = "text/css">
         body {
            text-align:center;
            font-family:Cambria, Helvetica, sans-serif;
            font-size:16px;
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
         .bigBox
         {
            float: right;
            width: 100%;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            
            background-image: linear-gradient(to right, rgba(190, 245, 88,1), rgba(233, 240, 110,1), rgba(233, 240, 110,1),rgba(237, 226, 119,1),rgba(237, 226, 119,0.95),rgba(237, 226, 119,0.93),rgba(240, 230, 139,0.9), rgba(240, 230, 139,0.5), rgba(240, 230, 139,0));
            flex: 1;
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
        <ul class="nav navbar-nav navbar-right">
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
<body>
   <?php
   $conn = mysqli_connect('localhost','root','736;*9f,cQZ6&XyX','project2');
   mysqli_set_charset($conn,"utf8");
   if (isset($_SESSION['islogin'])) { # check if there is a logged-in user

      $target = floatval($_SESSION['uid']);
      $sql = "Call See_Answers_ByUser($target)";
      $result = mysqli_query($conn,$sql);
      // show accessed answers' information
      if (mysqli_num_rows($result) > 0) {
         while($row = mysqli_fetch_assoc($result)){ # including answer detail, answer created date; if it is a best answer, show the best-answer sign
            echo "<div style='margin-top: 30px; margin-left: 20px;'><p><span class='glyphicon glyphicon-chevron-font'></span> ".$row['abody']."</p><p>  on   ".$row['answerdate']." </p>";
            echo "<p><span class='glyphicon glyphicon-thumbs-up'></span> ".$row['numlikes']."</p>";
            if($row['bestanswer']) echo "<p><span class='glyphicon glyphicon-bold'></span></p>";
            echo "<form method = 'post'>
            <input type='hidden' name='answerq' value= ". $row['qid'] . ">
            <input type='submit' name='l' value= 'See Question'>
            </form>";
            echo "</div><br>";
         }
      }
      else {
         echo "<div><h2>Did not find any answers replied by you.</h2></div>";
      }
   }
   ?>
</body>
</html>