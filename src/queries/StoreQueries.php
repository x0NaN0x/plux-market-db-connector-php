<?php

declare(strict_types=1);

use Dotenv\Dotenv;

// Ensure DEMO_MODE is fetched as a boolean
$demoMode = filter_var($_ENV['DEMO_MODE'], FILTER_VALIDATE_BOOLEAN);

return [
    'SELLER_STORE' => "
        SELECT
            c.char_id,
            vi.vending_id,
            vi.cartinventory_id,
            ci.amount,
            ci.refine,
            vi.price,
            idb.id,
            idb.slots,
            ci.card0,
            idb_card0.name_english AS card0_name,
            ci.card1,
            idb_card1.name_english AS card1_name,
            ci.card2,
            idb_card2.name_english AS card2_name,
            ci.card3,
            idb_card3.name_english AS card3_name,
            ci.option_id0,
            ci.option_val0,
            ci.option_parm0,
            ci.option_id1,
            ci.option_val1,
            ci.option_parm1,
            ci.option_id2,
            ci.option_val2,
            ci.option_parm2,
            ci.option_id3,
            ci.option_val3,
            ci.option_parm3,
            ci.option_id4,
            ci.option_val4,
            ci.option_parm4,
            idb.name_english,
            idb.type,
            c.name,
            v.map,
            v.title,
            v.x,
            v.y
        FROM vending_items vi
        LEFT JOIN vendings AS v ON vi.vending_id = v.id
        LEFT JOIN cart_inventory AS ci ON vi.cartinventory_id = ci.id
        LEFT JOIN item_db AS idb ON ci.nameid = idb.id
        LEFT JOIN `char` AS c ON ci.char_id = c.char_id
        LEFT JOIN item_db AS idb_card0 ON ci.card0 = idb_card0.id
        LEFT JOIN item_db AS idb_card1 ON ci.card1 = idb_card1.id
        LEFT JOIN item_db AS idb_card2 ON ci.card2 = idb_card2.id
        LEFT JOIN item_db AS idb_card3 ON ci.card3 = idb_card3.id
        WHERE vi.vending_id = ?
        " . (!$demoMode ? "AND c.online > 0 " : "") . "
        GROUP BY vi.cartinventory_id;
    ",
    // NOTE: This query returns all of the items
    // Remember that we filter unique with highest vending_id
    // This would remove results from the query.
    'MARKET_BUY' => "
        SELECT
            c.char_id,
            vi.vending_id,
            vi.cartinventory_id,
            ci.amount,
            ci.refine,
            vi.price,
            idb.id,
            idb.slots,
            ci.card0,
            idb_card0.name_english AS card0_name,
            ci.card1,
            idb_card1.name_english AS card1_name,
            ci.card2,
            idb_card2.name_english AS card2_name,
            ci.card3,
            idb_card3.name_english AS card3_name,
            ci.option_id0,
            ci.option_val0,
            ci.option_parm0,
            ci.option_id1,
            ci.option_val1,
            ci.option_parm1,
            ci.option_id2,
            ci.option_val2,
            ci.option_parm2,
            ci.option_id3,
            ci.option_val3,
            ci.option_parm3,
            ci.option_id4,
            ci.option_val4,
            ci.option_parm4,
            idb.name_english,
            idb.type,
            c.name,
            v.map,
            v.title,
            v.x,
            v.y
        FROM vending_items vi
        LEFT JOIN vendings AS v ON vi.vending_id = v.id
        LEFT JOIN cart_inventory AS ci ON vi.cartinventory_id = ci.id
        LEFT JOIN item_db AS idb ON ci.nameid = idb.id
        LEFT JOIN `char` AS c ON ci.char_id = c.char_id
        LEFT JOIN item_db AS idb_card0 ON ci.card0 = idb_card0.id
        LEFT JOIN item_db AS idb_card1 ON ci.card1 = idb_card1.id
        LEFT JOIN item_db AS idb_card2 ON ci.card2 = idb_card2.id
        LEFT JOIN item_db AS idb_card3 ON ci.card3 = idb_card3.id
        WHERE idb.name_english LIKE CONCAT('%', ?, '%') OR idb.id = ?
        " . (!$demoMode ? "AND c.online > 0 " : "") . "
        GROUP BY vi.cartinventory_id;
    ",
    'MARKET_GET_ALL_SELLING_ITEMS' => "
        SELECT
            c.char_id,
            c.online,
            vi.vending_id,
            vi.cartinventory_id,
            ci.amount,
            ci.refine,
            vi.price,
            idb.id,
            idb.slots,
            ci.card0,
            idb_card0.name_english AS card0_name,
            ci.card1,
            idb_card1.name_english AS card1_name,
            ci.card2,
            idb_card2.name_english AS card2_name,
            ci.card3,
            idb_card3.name_english AS card3_name,
            ci.option_id0,
            ci.option_val0,
            ci.option_parm0,
            ci.option_id1,
            ci.option_val1,
            ci.option_parm1,
            ci.option_id2,
            ci.option_val2,
            ci.option_parm2,
            ci.option_id3,
            ci.option_val3,
            ci.option_parm3,
            ci.option_id4,
            ci.option_val4,
            ci.option_parm4,
            idb.name_english,
            idb.type,
            c.name,
            v.map,
            v.title,
            v.x,
            v.y
        FROM vending_items vi
        LEFT JOIN vendings AS v ON vi.vending_id = v.id
        LEFT JOIN cart_inventory AS ci ON vi.cartinventory_id = ci.id
        LEFT JOIN item_db AS idb ON ci.nameid = idb.id
        LEFT JOIN `char` AS c ON ci.char_id = c.char_id
        LEFT JOIN item_db AS idb_card0 ON ci.card0 = idb_card0.id
        LEFT JOIN item_db AS idb_card1 ON ci.card1 = idb_card1.id
        LEFT JOIN item_db AS idb_card2 ON ci.card2 = idb_card2.id
        LEFT JOIN item_db AS idb_card3 ON ci.card3 = idb_card3.id
        " . (!$demoMode ? "AND c.online > 0 " : "") . "
        GROUP BY vi.cartinventory_id;
    "
];
