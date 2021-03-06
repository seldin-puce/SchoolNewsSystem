<?php include 'core/connection.php'; ?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> 
</html><![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8" lang="en"> 
</html><![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9" lang="en"> </html><![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <title>ETS - Rezultati ankete</title>
     <?php 
        include 'includes/SEO.php';
        include 'includes/css_js.php';
    ?>
  <script>
window.addEventListener("load", function(){
  var load_screen = document.getElementById("loader");
  document.body.removeChild(load_screen);
});
</script>
   </head>
<body ng-app="ETS">
    <!-- LOADER -->
    <div class="page-loader" id="loader">
      <div class="loading">
        <div class="loading-bar"></div>
        <div class="loading-bar"></div>
        <div class="loading-bar"></div>
        <div class="loading-bar"></div>
      </div>
    </div>
    <!-- ./LOADER -->
    <?php 
         include('view/header_mobile.php');
         include('view/header.php'); 
    ?>
    
    
    
    <!-- Being Page Title -->
    <div class="container">
        <div class="page-title clearfix">
            <div class="row">
                <div class="col-md-12">
                    <h6><a href="./index.php">Naslovnica</a></h6>
                    <h6><span class="page-active">Rezultati ankete</span></h6>
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="row">

            <!-- Here begin Main Content -->
            <div class="col-md-8">

                <div class="row">
                    <div class="col-md-12">
                        
                        <div class="course-post">
                            <div class="course-details clearfix">
                                <h3 class="course-post-title">Rezultati ankete</h3>
                                <?php 
                                    $connect = connectOnDb();
                                    if(!isset($_GET['pollID'])) {
                                        $connect = NULL;
                                        echo "<script>location.href='index.php';</script>";
                                        exit();
                                    }
                                    $pollID = $_GET['pollID'];
                                    $pollID = preg_replace("/[^0-9]/", "", $pollID);
                                    $fetchPoll = $connect->prepare("SELECT * FROM ankete_pitanja WHERE anketa_pitanje_id = :id LIMIT 1");
                                    $fetchPoll->bindParam(":id", $pollID);
                                    $fetchPoll->execute();
                                    if($fetchPoll->rowCount() > 0) {
                                        foreach($fetchPoll as $key=>$value) {
                                            echo "<p>".$value['anketa_pitanje_content']."</p>";
                                        }
                                        $totalAnswer = $connect->query("SELECT SUM(anketa_odg_num_votes) AS maxAnswers FROM ankete_odgovori WHERE anketa_pitanje_id = '$pollID'");
                                        $row = $totalAnswer->fetch(PDO::FETCH_ASSOC);
                                        $maxAnswers = $row['maxAnswers'];
                                        if($maxAnswers == 0){
                                            $maxAnswers = 1;
                                        }
                                        $fetchAnswers = $connect->prepare("SELECT * FROM ankete_odgovori WHERE anketa_pitanje_id = :id");
                                        $fetchAnswers->bindParam(":id", $pollID);
                                        $fetchAnswers->execute();
                                        if($fetchAnswers->rowCount() > 0) {
                                            foreach($fetchAnswers as $key=>$value) {
                                                $numOfAnswers = $value['anketa_odg_num_votes'];
                                                $precent = ($numOfAnswers / $maxAnswers) * 100;
                                                $precent = round($precent, 2);
                                                
                                                if($numOfAnswers == 1) {
                                                    $voteString = "glas";
                                                } else if($numOfAnswers < 5 && $numOfAnswers >0) {
                                                    $voteString = "glasa";
                                                } else {
                                                    $voteString = "glasova";
                                                }
                                                
                                                echo "<p>".$value['anketa_odg_content']."</p>";
                                                echo '<div class="progress">
                                                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="'.$precent.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$precent.'%">
                                                            <span>'.$numOfAnswers." ".$voteString.' - '.$precent.'%</span>
                                                        </div>
                                                    </div>';
                                            }
                                        }
                                        
                                    }
                                    $connect = NULL;
                                ?>
                            </div> <!-- /.course-details -->
                        </div> <!-- /.course-post -->

                    </div> <!-- /.col-md-12 -->
                </div> <!-- /.row -->

            </div> <!-- /.col-md-8 -->


            <!-- Here begin Sidebar -->
            <div class="col-md-4">

               <?php 
                    include 'widgets/dogadjaji_widget.php'; 
                    include 'widgets/oznake_widget.php';
                    include 'widgets/galerija_widget.php';
                ?>

            </div> <!-- /.col-md-4 -->
    
        </div> <!-- /.row -->
    </div> <!-- /.container -->

   <?php include 'view/footer.php'; ?>


    <script src="./bootstrap/js/bootstrap.min.js"></script>
    <script src="./js/plugins.js"></script>
    <script src="./js/custom.js"></script>

</body>
</html>