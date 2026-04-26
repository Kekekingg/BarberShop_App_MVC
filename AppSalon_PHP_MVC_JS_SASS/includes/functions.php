<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escape / Sanitize the HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}