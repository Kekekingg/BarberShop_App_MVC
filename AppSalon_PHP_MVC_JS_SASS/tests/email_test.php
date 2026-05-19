<?php

require __DIR__ . '/../vendor/autoload.php';

use Classes\Email;

$tests = [
    ['email' => 'test1@example.com', 'name' => 'Test One'],
    ['email' => 'test2@example.com', 'name' => 'Test Two'],
    ['email' => 'test3@example.com', 'name' => 'Test Three'],
    ['email' => 'test4@example.com', 'name' => 'Test Four'],
    ['email' => 'test5@example.com', 'name' => 'Test Five'],
];

foreach ($tests as $i => $t) {
    $token = bin2hex(random_bytes(8));
    $email = new Email($t['email'], $t['name'], $token);
    try {
        $email->sendConfirmation();
        echo sprintf("[%d] Sent confirmation to %s (%s)\n", $i+1, $t['email'], $t['name']);
    } catch (Exception $e) {
        echo sprintf("[%d] Error sending to %s: %s\n", $i+1, $t['email'], $e->getMessage());
    }
    sleep(1);
}

echo "Done.\n";
