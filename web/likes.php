<?php
    if (session_id() == "") {
      session_start();
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
   </style>
</head>
<nav class="navbar navbar-default navbar-static-top" role="navigation">

</nav>

<?php
    if (isset($_SESSION['islogin'])) {

      $uid = floatval($_SESSION["uid"]);
      $arr = $_POST["likes"];
      $qid = floatval($arr[0]);
      $num = floatval($arr[1]);

      $conn = mysqli_connect('localhost','root','736;*9f,cQZ6&XyX','project2');
      $sql = "Select answerer From Answer Where anum = $num and qid = $qid";
      $result = mysqli_query($conn,$sql);
      $row = mysqli_fetch_assoc($result);
      if ($row["answerer"] == $uid) {
         echo "You are not allowed to like your own answer!";
         echo "<br>";
         echo "<a href='Q_A.php'><span class='glyphicon glyphicon-arrow-left'></span> Go Back</a>";
      }
      else {
         $sql = "Call Likes($uid, $qid, $num)";
         try {
               $result = mysqli_query($conn,$sql);
               if (!$result) {
                  throw new Exception($result->errno);
               }  
               echo "Success!";
         }
         catch (Exception $err) {
      
               echo "You are not allowed to like an answer twice!";
         }
         echo "<br>";
         echo "<a href='Q_A.php'><span class='glyphicon glyphicon-arrow-left'></span> Go Back</a>";
      }
   }
   else{
      echo "You need to login to perform that action";
      echo "<br>";
      echo "<a href='Q_A.php'><span class='glyphicon glyphicon-arrow-left'></span> Go Back</a>";
   }

?>
</body>
</html>





