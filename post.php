<?php require_once("parts.php"); ?>
<html> 
<head> 
  <title>getHeding</title> 
</head> 
<body> 
	<?php printHeader();?>
  <h1> <?php echo "getHeading"; ?> </h1> 
  <div align="right">   
    <a href="putpost.php"><button>Postavi pitanje</button></a><br> 
    asked: Datum php<br> 
    viewed: Koliko je vidjeno php<br> 
    active: Kad je zadnji put odgovarano<br> 
  </div> 
  <div align="center"> 
    <p> ukratko pitanje <br> 
    Kod pitanja<br> 
    Jos pitanja<br> 
    Tagovi<br> 
    Korisnik koji je objavio<br> 
    </p> 
 
  </div> 
  <div align="center"> 
  	Svi odgovori preko niza
    <p> ukratko odgovori <br> 
    Kod odgovor<br> 
    Jos odgovor</p> 
    Korisnik koji je objavio<br></p> 
  </div> 
  Vas odgovor
 <form method="post">
 	<textarea rows="4" cols="50" name="comment" form="usrform" placeholder="Upisite vas odgovor"></textarea><br>
 	<input type="submit" value="Postavite vas odgovor" name="">
 	
 </form>
</body> 