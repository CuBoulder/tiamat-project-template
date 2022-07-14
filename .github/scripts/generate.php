<?php

$composer = null;
try{
  // read the development version of composer.json
  $composer = @file_get_contents("./composer.json");
  if( $composer === false){
    throw new Exception("ERROR: Unable to fetch resource \n");
  }
}
catch(Exception $e){
  echo $e->getMessage();
}
$res =  json_decode($composer, true);

// skip first index since that's the composer package
for( $i = 1; $i < count( $res['repositories'] ); $i++ ){
  preg_match( '/CuBoulder\/(.*?)\.git/', $res['repositories'][$i]['url'], $match);
  $package = $match[1];
  // add the package into the require section
  $res['require']['cu-boulder/'.$package] = 'dev-main';
}

// remove the dev-dependencies
unset($res['require-dev']);

// write the updated file out
$composer = json_encode($res, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
file_put_contents('./composer.json', $composer);
?>