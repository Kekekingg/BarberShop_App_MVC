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

function isLast(string $current, string $next): bool {
    if ($current !== $next) {
        return true;
    }
    return false;
}

// function that checks that the user is authenticated
function isAuth (): void {
    if (!isset($_SESSION['login'])) {
        header('Location: /');
    };
}