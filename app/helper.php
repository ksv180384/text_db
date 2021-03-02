<?php


function e($val){
    return htmlspecialchars($val);
}

function redirect($url){
    header('Location: ' . $url, true, 301 );
    exit;
}

function error404(){
    $error = new ErrorController();
    $error->index();
}