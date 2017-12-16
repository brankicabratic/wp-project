<?php require_once("parts.php"); require_once("db_utils.php");?>
<html> 
<head> 
  <title>getHeding</title> <link rel="stylesheet" type="text/css" href="css/style.css">
</head> 
<body> 
	<?php printHeader();  
		if (isset($_GET["id"])) {
			$post=getPost($_GET["id"]);
		}
		else
    		header("Location:not_id.php");

	?>
  <h1> <?php echo "getHeading"; ?> </h1> 
  <div align="right">   
    <a href="putpost.php"><button>Postavi pitanje</button></a><br> 
    asked: <?php echo $post["Datum"];?><br> 
    viewed: Koliko je vidjeno php<br> 
    active: Kad je zadnji put odgovarano<br> 
  </div> 
  <div align="center"> 
    <?php 
    if (isset($_GET["id"])) {
    	echo "<div>";
    	$post=getPost($_GET["id"]);
    	foreach ($post as $key => $value) {
    		echo $value."<br>";
    	}
    	
    	echo "<div><br>";
    	$tags=getTags();
    	foreach ($tags as $key => $value) {
    		echo $value. "\t";
    	}
    	echo "</div>";
    	echo "</div>";
    	$Answers=getAnswers($_GET["id"]);
    	foreach ($Answers as $key => $value) {
    		echo "<div><br>";
    		foreach ($value as $id => $val){
    			echo $val."<br>";
    		}
    		echo "</div>";
    	}
    
    }
   

    ?>
   
 
  </div> 
  
  Vas odgovor
 <form method="post">
 	<textarea rows="4" cols="50" name="comment" form="usrform" placeholder="Upisite vas odgovor"></textarea><br>
 	<input type="submit" value="Postavite vas odgovor" name="">
 	
 </form>
</body> 