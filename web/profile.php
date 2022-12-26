<?php
   //start session to use data storing in the session
    session_start();
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
            background-image: url('book.jpg');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
         }
         .navbar{
            margin-bottom: 0px;
            border-radius: 0px;
            background: linear-gradient(to right, #65da9c 0%, #65daab 0%, #65dace 15%, #65ceda 33%, #65daab 66%, #65da9c 90%);
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
         #bigBox
         {
            font-family:Cambria, Helvetica, sans-serif;
            margin: 0 auto;  /* login框剧中 */
            margin-top: 70px; /* login框与顶部的距离 */
            padding: 20px 50px; /* login框内部的距离(内部与输入框和按钮的距离) */
            background: linear-gradient(to right, #65da9c 0%, #65daab 0%, #65dace 15%, #65ceda 33%, #65daab 66%, #65da9c 90%);
            width: 400px;
            height: 500px;
            border-radius: 50px;   /* 圆角边 */
            text-align: center; /* 框内所有内容剧中 */
         }

         #bigBox .inputBox
         {
            margin-top: 20px;   /* 输入框顶部与LOGIN标题的间距 */
         }
         #bigBox .inputBox .inputText
         {
            margin-top: 20px;   /* 输入框之间的距离 */
         }
         #bigBox .inputBox .inputText input
         {
            border: 0; /* 删除输入框边框 */
            padding: 10px 10px; /* 输入框内的间距 */
            border-bottom: 1px solid white; /* 输入框白色下划线 */
            background-color: #00000000; /* 输入框透明 */
            font-family:Cambria, Helvetica, sans-serif;
         }
         table caption{
            font-size: 1.5em;
            font-weight: bold;
            text-align: center;
            color: black;
         }
         th,td{
            text-align: center;
            padding: 4.5px 0;
            color: black;
         }

         h1{
            margin-top: 50px;
            font-weight: bold;
            color:lightseagreen;
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
         if (isset($_SESSION['islogin'])) {
            //check whether there is a logged-in user, if user logged in, then a profile option will show up
            echo "<li><a href='profile.php'><span class='glyphicon glyphicon-user'></span> ".$_SESSION['login_user']."</a></li>";
            echo "<li><a href='Logout.php'><span class='glyphicon glyphicon-log-in'></span> log out</a></li>";
         }
         else {
            //if there is no logged-in user, only show log-in option
            echo "<li><a href='Login.html'><span class='glyphicon glyphicon-user'></span> log in</a></li>";
         }
         ?>
        </ul>
    </div>
    </div>
</nav>
<body>
   <h1><span class='glyphicon glyphicon-pushpin'></span> <?php echo $_SESSION['login_user']; ?></h1><!-- username as title of profile page-->
   <div id="bigBox">

        <form id="profileform" action="" method="post">
            <?php
            // fetch user's information, including name, email, address, affiliation and level& profile
            $conn = mysqli_connect('localhost','root','736;*9f,cQZ6&XyX','project2');
            mysqli_set_charset($conn,"utf8");
            $target = mysqli_real_escape_string($conn, $_SESSION['login_user']);

            $sql = "SELECT * FROM Users WHERE username = '$target'";
            $result = mysqli_query($conn,$sql);
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $uid = floatval($_SESSION["uid"]);
            // show user's information line by line
            echo "<table class='inputBox' cellspacing='0' cellpadding='0'><caption>User Information</caption>";
            echo "<thead><tr><th colspan='2'></th></tr></thead><tbody>";
            echo "<tr><td>Name: </td><td>".$row['firstname']." ".$row['lastname']."</td></tr>";
            echo "<tr><td>Email: </td><td>".$row['email']."</td></tr>";
            echo "<tr><td>Address:</td><td>".$row['city'].", ".$row['state'].", ".$row['country']."</td></tr>";
            echo "<tr><td>Affiliation:</td><td>".$row['affilation']."</td></tr>";
            echo "<tr><td>Level: </td><td>".$row['level']."</td></tr>";
            echo "<tr><td>Introduction:</td><td>".$row['personal_profile']."</td></tr>";
            echo "<form method = 'post'>
            <input type='text' name='profile' maxlength = '150' value= 'Change Profile'>
            </form>";
            echo "</tbody></table>";
            if (isset($_POST["profile"])){
               $uid = floatval($_SESSION["uid"]);
               $new_prof = mysqli_real_escape_string($conn,$_POST['profile']);
               $sql = "Call edit_profile($uid, '$new_prof')";
               mysqli_query($conn, $sql);
               exit("<script>alert('Success!'); window.history.go(-1)</script>");
            }
            ?>
            
        </form>
</div>
</body>
</html>