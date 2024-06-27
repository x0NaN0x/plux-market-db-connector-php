<?php

declare(strict_types=1);

return [
    'ITEM_SEARCH_DB' => "
        SELECT id, name_english, type
        FROM `item_db`
        WHERE name_english LIKE CONCAT('%', ?, '%')
        OR id = ?
        AND trade_nosell IS NULL
        AND type != 'Cash';
    ",
    'LIST_RANDOM_ITEMS' => "
        SELECT
            main_query.char_id,
            main_query.vending_id,
            main_query.cartinventory_id,
            main_query.amount,
            main_query.refine,
            main_query.price,
            main_query.id,
            main_query.slots,
            main_query.card0,
            item_db_card0.name_english AS card0_name,
            main_query.card1,
            item_db_card1.name_english AS card1_name,
            main_query.card2,
            item_db_card2.name_english AS card2_name,
            main_query.card3,
            item_db_card3.name_english AS card3_name,
            main_query.option_id0,
            main_query.option_val0,
            main_query.option_parm0,
            main_query.option_id1,
            main_query.option_val1,
            main_query.option_parm1,
            main_query.option_id2,
            main_query.option_val2,
            main_query.option_parm2,
            main_query.option_id3,
            main_query.option_val3,
            main_query.option_parm3,
            main_query.option_id4,
            main_query.option_val4,
            main_query.option_parm4,
            main_query.name_english,
            main_query.type,
            main_query.name,
            main_query.map,
            main_query.title,
            main_query.x,
            main_query.y
        FROM (
            SELECT
                c.char_id,
                vending_items.vending_id,
                vending_items.cartinventory_id,
                cart_inventory.amount,
                cart_inventory.refine,
                vending_items.price,
                item_db.id,
                item_db.slots,
                cart_inventory.card0,
                cart_inventory.card1,
                cart_inventory.card2,
                cart_inventory.card3,
                cart_inventory.option_id0,
                cart_inventory.option_val0,
                cart_inventory.option_parm0,
                cart_inventory.option_id1,
                cart_inventory.option_val1,
                cart_inventory.option_parm1,
                cart_inventory.option_id2,
                cart_inventory.option_val2,
                cart_inventory.option_parm2,
                cart_inventory.option_id3,
                cart_inventory.option_val3,
                cart_inventory.option_parm3,
                cart_inventory.option_id4,
                cart_inventory.option_val4,
                cart_inventory.option_parm4,
                item_db.name_english,
                item_db.type,
                c.name,
                vendings.map,
                vendings.title,
                vendings.x,
                vendings.y
            FROM vendings
            LEFT JOIN vending_items ON vending_items.vending_id = vendings.id
            LEFT JOIN cart_inventory ON vending_items.cartinventory_id = cart_inventory.id
            LEFT JOIN item_db ON cart_inventory.nameid = item_db.id
            LEFT JOIN `char` AS c ON cart_inventory.char_id = c.char_id
            WHERE c.char_id IS NOT NULL
            " . ($_ENV['DEMO_MODE'] ? "" : "WHERE c.online > 0") . "
            GROUP BY vending_items.vending_id
            ORDER BY RAND()
            LIMIT 100
        ) AS main_query
        LEFT JOIN item_db AS item_db_card0 ON main_query.card0 = item_db_card0.id
        LEFT JOIN item_db AS item_db_card1 ON main_query.card1 = item_db_card1.id
        LEFT JOIN item_db AS item_db_card2 ON main_query.card2 = item_db_card2.id
        LEFT JOIN item_db AS item_db_card3 ON main_query.card3 = item_db_card3.id;
    "

];
