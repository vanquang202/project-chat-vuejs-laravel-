<?php 


function session(){
    ob_start();
}
function endSession(){
    return ob_get_clean();
}
 
session();
require_once 'testD/test.html' ;
$result = endSession(); 

session();
 echo 'HIHI';
$resul2 = endSession(); 

function here($data){

    echo $GLOBALS[$data];

}
here('resul2');