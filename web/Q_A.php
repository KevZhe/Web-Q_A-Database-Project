<?php
   #start session to use data storing in the session
    if (session_id() == "") {
        session_start();
     }

     if(isset($_POST["keywords"])) {

        $_SESSION["keywords"] = $_POST["keywords"];
        header("Location: search.php");
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
        <form id = "search" class="navbar-form navbar-left" role="search" method = "post">
            <div class="form-group">
                <input type="text" id = "keywords" name="keywords" class="form-content" maxlength ="200" placeholder="   Find Q&A...">

            </div>
            <button type="submit" class="search-btn">Search</button>
        </form>
        <ul class="nav navbar-nav navbar-right">
         <?php
         if (isset($_SESSION['islogin'])) {
            #check whether there is a logged-in user, if user logged in, then a profile option will show up
            echo "<li><a href='profile.php'><span class='glyphicon glyphicon-user'></span> ".$_SESSION['login_user']."</a></li>";
            echo "<li><a href='Logout.php'><span class='glyphicon glyphicon-log-in'></span> log out</a></li>";
         }
         else {
            #if there is no logged-in user, only show log-in option
            echo "<li><a href='Login.html'><span class='glyphicon glyphicon-user'></span> log in</a></li>";
         }
         ?>
        </ul>
    </div>
    </div>
</nav>
<body>
   <?php

   if (isset($_POST["qid"])) {
      $qid = floatval($_POST["qid"]);
      $_SESSION["qid"] = $qid;
   }
   else {
      $qid = floatval($_SESSION["qid"]);
   }
   $conn = mysqli_connect('localhost','root','736;*9f,cQZ6&XyX','project2');
   mysqli_set_charset($conn,"utf8");

   $questionsql = "Select username, title, qbody, questiondate, tname, stname From Question Natural Join Topic Inner Join Users on Question.asker = Users.uid Where qid = $qid";
   $qresult = mysqli_query($conn,$questionsql);
   while ($question = mysqli_fetch_assoc($qresult)){
      # show fetched information about questons, including title, detail, asker and posted date
      echo "<h1>".$question['title'];
      echo "<h2>".$question['qbody']."</h2>";
      echo "<h2>             asked by  ".$question['username']."   on   ".$question['questiondate']."</h2>";
   }

   # find all answers affiliated to the question
   $sql = "Call Answers_to_Question($qid)";
   $aresult = mysqli_query($conn,$sql);
   if (mysqli_num_rows($aresult) > 0) {
      #check if this question is answered
      while($row = mysqli_fetch_assoc($aresult)){
         #show answers information part by part, including answer details, answerer name, answer date and number of likes
         echo "<div style='margin-top: 30px; margin-left: 20px;'><p><span class='glyphicon glyphicon-chevron-font'></span> ".$row['abody']."</p><p>by   ".$row['username']."   on   ".$row['answerdate']." </p>";
         echo "<p><span class='glyphicon glyphicon-thumbs-up'></span> ".$row['numlikes']."</p>";
         if($row['bestanswer']) echo "<p><span class='glyphicon glyphicon-bold'></span></p>";// if it is a best-answer, a sign will show up to illustrate that
         echo "<form action = 'likes.php' method = 'post'>
         <input type='hidden' name='likes[0]' value= $qid>
         <input type='hidden' name='likes[1]' value= ". $row['anum'] . ">
         <input type='submit' name='l' value= 'Like'>
         </form>";
         echo "</div><br>";
      }

   }
   else {
      #if no result, then it means no one has tried to answer the question yest
      echo "<div><h2>Did not find any replied answers.</h2></div>";
   }
   # a choice for user to answer the question
   echo "<a href='add_answer.php'><span class='glyphicon glyphicon-plus'></span> Post a Answer</a>";
   ?>
</body>
</html>

















