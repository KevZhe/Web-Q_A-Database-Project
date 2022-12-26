<?php
//start session to use data storing in the session

    if (session_id() == "") {
       session_start();
    }
    if (isset($_POST["search"])) {
        $_SESSION["qid"] = floatval($_POST["search"]);
        header("Location: Q_A.php");
        exit();
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

  
<form id="sort" method="post">
            <label for="sortby">Sort Questions By:</label>
            <select name="sortby" id="sortby">
            <option value="r">relevance</option>
            <option value="c">recent</option>
            </select>
            <input type="submit" name="Submit" value="Select"/>
</form>
    <div class="bigBox">


    <?php
        $keywords = $_SESSION["keywords"];
        $conn = mysqli_connect('localhost','root','736;*9f,cQZ6&XyX','project2');
        mysqli_set_charset($conn,"utf8");
        
            
        if(isset($_POST['sortby'])) {
            $sort = $_POST['sortby'];

            if ($sort =="r") {
                $sql = "Call Search_Relevance('$keywords')";
            }
            else {
                $sql = "Call Search_Chronologically('$keywords')";
            }
            $result = mysqli_query($conn,$sql);

            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)){
                    echo "<form method='post'>";
                    echo "<div style='margin-top: 30px; margin-left: 20px;'><button name = 'search' value = " . $row['qid']. "style='float: left; border: none; background-color: rgb(255,255,255,0);font-size: 20px;font-weight: bolder;margin-left: 20px;'>";
                    if ($row['qstatus'] == "Answered") {
                        echo "<span class='glyphicon glyphicon-paperclip'></span>";
                    }
                    else{
                        echo " ";
                    }
                    echo " ".$row['Title']."</button><br><p>";
                    echo "<center>". $row['Question_Body'] . "<center>";
                    echo "<center>" .$row['questiondate'] . "<center>";
                    echo "</div><br>";
                    echo "</form>";
                }
            }
            else {
                echo "<div><h2>No Questions Found</h2></div>";
            }
        }
 ?>
</form>
</div>
 
</body>
</html>









