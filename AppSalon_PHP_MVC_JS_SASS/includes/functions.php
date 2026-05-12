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

// function that checks that the user is authenticated

function isAuth (): void {
    if (!isset($_SESSION['login'])) {
        header('Location: /');
    };
}