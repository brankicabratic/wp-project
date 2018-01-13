<?php
  function get_first($arr) {
    return array_map(
        create_function('$el', 'return $el[0];'),
        $arr
      );
  }

  function generate_random_key($length) {
    $result = "";
    for($i = 0; $i < $length; $i++) {
      $k = rand(0, 65);
      if($k <= 25)
        $result .= chr(ord('a') + $k);
      else if($k <= 51)
        $result .= chr(ord('A') + $k - 26);
      else if($k <= 61)
        $result .= chr(ord('0') + $k - 52);
      else
        $result .= '-';
    }
    return $result;
  }

?>
