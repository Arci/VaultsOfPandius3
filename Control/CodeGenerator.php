<?php

/*
 * generates a random string of the desired length
 * @param lenght: the length of the string
 * @return: the random string
 */
 function random_string($length) {
    
  $string = "";
  
  //generates a random string that has a length equal to the next multiple of 32 to $ length
  for ($i = 0; $i <= ($length/32); $i++)
      $string .= md5(time()+rand(0,99));

  //start index limit
  $max_start_index = (32*$i)-$length;

  //Select the string, using as a starting index value between 0 and $ max_start_point
  $random_string = substr($string, rand(0, $max_start_index), $length);

  return $random_string;
}
?>