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

  function getReply(){
    return  array(
      "TextOdg" => "neki odgovor",
      "AutorOdg" => "Ime autora"
     );
  }

 ?>
