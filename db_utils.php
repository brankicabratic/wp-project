<?php
  function getPost($id){
    return array(
      "Naslov"=>"Neki naslov",
      "Text"=> "Neki tekst",
      "Autor"=>"ime autora",
      "Datum"=> "12.12.2017"
    );
  }

  function getTags(){
      return array("tag", "macka","novitag");
  }

  function getAnswers(){
    return array( array(
      "TextOdg" => "neki odgovor1",
      "AutorOdg" => "Ime autora1"
    ),
    array(
      "TextOdg" => "neki odgovor2",
      "AutorOdg" => "Ime autora2"
    ),
    array(
      "TextOdg" => "neki odgovor3",
      "AutorOdg" => "Ime autora3"
    ),
    array(
      "TextOdg" => "neki odgovor4",
      "AutorOdg" => "Ime autora4"
    )
  );
  }

 ?>
