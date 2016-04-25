<?php

/**
 * This code will benchmark your server to determine how high of a cost you can
 * afford. You want to set the highest cost that you can without slowing down
 * you server too much. 8-10 is a good baseline, and more is good if your servers
 * are fast enough.
 */
<<<<<<< HEAD
$timeTarget = 1;
$cost = 14;
=======
$timeTarget = 0.5;
$cost = 10;
>>>>>>> 10f24b186e460bfca237fff6174f6b6fb5b3c2b4
do {
    $cost++;
    $start = microtime(true);
    password_hash("test", PASSWORD_BCRYPT, ["cost" => $cost]);
    $end = microtime(true);
} while (($end - $start) < $timeTarget);

echo "Appropriate Cost Found: " . $cost . "\n";

