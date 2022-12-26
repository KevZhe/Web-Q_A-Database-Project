<?php
    if (session_id() == "") {
      session_start(); #start session to use data storing in the session
   }

 $conn = mysqli_connect('localhost','root','736;*9f,cQZ6&XyX','project2');
 mysqli_set_charset($conn,"utf8");

   if (isset($_POST["title"])) {
      if (isset($_SESSION['islogin'])) {
         $qtitle = $_POST["title"];
         $qbody = $_POST["qbody"];
         $topic = $_POST["choosetopic"];

         $uid = floatval($_SESSION['uid']);
         

         $stmt = $conn->prepare("Call Add_Question(?,?,?,?)");
         $stmt->bind_param("iiss", $uid, $topic, $qtitle, $qbody);
         $stmt->execute();

         $sql = "Select qid From Question Order by questiondate DESC Limit 1";
         $result = mysqli_query($conn,$sql);
         $row = mysqli_fetch_assoc($result);

         $_SESSION["qid"] = $row["qid"];
         header("Location: Q_A.php");
         exit();
      }

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
            margin: 0;
            padding: 0;
            background-image: url('books.png');
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
            margin: 0 auto;  /* login框剧中 */
            margin-top: 30px; /* login框与顶部的距离 */
            padding: 20px 50px; /* login框内部的距离(内部与输入框和按钮的距离) */
            background-color: #00000099; /* login框背景颜色和透明度 */
            width: 500px;
            height: 660px;
            border-radius: 10px;   /* 圆角边 */
            text-align: center; /* 框内所有内容剧中 */
         }

         h1
         {
            font-family:Cambria, Helvetica, sans-serif;
            color: #4fd19d; 
            font-weight: bold; 
            margin-top: 15px;
         }
         .inputBox
         {
            margin-top: 5px;   /* 输入框之间的距离 */
         }
         .inputBox input
         {
            border: 0; /* 删除输入框边框 */
            padding: 10px 10px; /* 输入框内的间距 */
            border-bottom: 1px solid white; /* 输入框白色下划线 */
            background-color: #00000000; /* 输入框透明 */
            color: ghostwhite; /* 输入字体的颜色 */
            font-family:Cambria, Helvetica, sans-serif;
         }

         #bigBox .submitButton
         {
            font-family:Cambria, Helvetica, sans-serif;
            margin-right: 30px;
            margin-top: 40px;   /* 按钮顶部与输入框的距离 */
            width: 100px;
            height: 25px;
            color: white; /* 按钮字体颜色 */
            border: 0; /* 删除按钮边框 */
            border-radius: 20px;   /* 按钮圆角边 */
            background-image: linear-gradient(to right, #65da9c 0%, #65daab 0%, #65dace 15%, #65ceda 33%, #65daab 66%, #65da9c 90%);  /* 按钮颜色 */
         }
         .m-left{
            margin-left: 30px;
         }

         .fgtpwd{
            position: absolute;
            margin-bottom: 1000px;
            right: 10px;
            color: #ffffff;
            /*left:  calc(5% - 200px);*/
            margin-right:666px;
            /*bottom: 240px;*/
            font-size: 13px;
            font-family:Cambria, Helvetica, sans-serif;
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
<div id="bigBox"><!-- Question information collecting region -->
   <h1><span class='glyphicon glyphicon-info-sign'></span> Question Details</h1><!-- title -->
        <form id="addquestion"  method="post">
<!-- Question title collecting region -->
<p><label style="color:  whitesmoke;" for="title">Title of your Question:</label></p>
<textarea class="inputBox",id="title", name="title" rows="1" cols="50" maxlength = "80">
Enter whatever your heart desires to know!
</textarea>
<br><!-- Question information description region -->
<p><label style="color:  whitesmoke;" for="qbody">Explain your question in detail:</label></p>
<textarea class="inputBox", id="qbody", name="qbody" rows="10" cols="50" maxlength = "180">
What specifically do you want to ask?
</textarea>
<br><!-- topic selection region -->


            <label style="color:  whitesmoke;" for="choosetopic">What topic does this question fall under?</label>
            <br>
            <select name="choosetopic" id="choosetopic">
            <option value=1>Computer Science:Programming</option>
            <option value=2>Computer Science:Java</option>
            <option value=3>Computer Science:C++</option>
            <option value=4>Computer Science:Python</option>
            <option value=5>Computer Science:HTML</option>
            <option value=6>Computer Science:JavaScript</option>
            <option value=7>Mathematics:Calculus</option>
            <option value=8>Mathematics:Algebra</option>
            <option value=9>Mathematics:Applied Math</option>
            <option value=10>Others:Misc</option>
            <option value=11>Others:Introduction</option>
            <option value=12>Science:Chemistry</option>
            <option value=13>Science:Physics</option>
            </select>
            <br>
<input class="submitButton" type="submit" name="Submit" value="Post"/>
        </form>
</div>
</body>
</html>
