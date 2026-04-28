<?php

function debuggin($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escape / Sanitize the HTML
function san($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}