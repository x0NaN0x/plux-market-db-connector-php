<?php

declare(strict_types=1);

return [
    'VENDING_LOG_EXISTS' => "
        SELECT 1 
        FROM vending_log 
        LIMIT 1;
    ",
];
