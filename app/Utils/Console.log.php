<?php 

use Laravel\Prompts\Note;

if(!function_exists('consolog')){
  function consolog(mixed $msg){
    if(is_int($msg) || is_string($msg)){
      (new Note($msg.'', null))->display();
    }else if(is_array($msg) || is_object($msg)){
      $data = json_encode($msg, JSON_PRETTY_PRINT);
      (new Note($data, null))->display();
    }else if(is_null($msg)){
      (new Note('Valor NULL',null))->display();
    }else {
      (new Note('Valor no soportado', null))->display();
    }
  }
}