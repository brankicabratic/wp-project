<?php
  function get_first($arr) {
    return array_map(
        create_function('$el', 'return $el[0];'),
        $arr
      );
  }
?>
