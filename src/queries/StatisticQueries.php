<?php

declare(strict_types=1);

return [
    'ITEM_STATISTICS' => "
        SELECT
            AVG(price) AS average_price,
            (SELECT trade_time FROM vending_log AS vl WHERE vl.item_id = v.item_id ORDER BY trade_time DESC LIMIT 1) AS bought_last_at,
            COUNT(*) AS amount_sold
        FROM vending_log AS v
        WHERE v.item_id = ? AND v.refine = ?;
    ",
    'PRICE_DEVELOPMENT' => "
        SELECT
            DATE(trade_time) AS sale_date,
            MAX(price) AS price
        FROM vending_log
        WHERE item_id = ?
        AND refine = ?
        AND trade_time >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
        GROUP BY sale_date
        ORDER BY sale_date;
    ",
    'LAST_ITEM_SOLD' => "
        SELECT vl.*, idb.name_english, idb.type
            FROM vending_log vl
            LEFT JOIN item_db AS idb ON vl.item_id = idb.id
            ORDER BY vl.trade_time DESC
            LIMIT 1;
    ",
    'MOST_EXPENSIVE_ITEM_SOLD_THIS_WEEK' => "
        SELECT vl.*, idb.name_english, idb.type
            FROM vending_log vl
            LEFT JOIN item_db AS idb ON vl.item_id = idb.id
            WHERE YEARWEEK(trade_time, 1) = YEARWEEK(CURDATE(), 1)
            ORDER BY price DESC
            LIMIT 1;
    ",
    // If there is a tie between two items
    // We will return the item with the most recent trade time
    'MOST_POPULAR_ITEM' => "
        WITH current_week_sales AS (
            SELECT
                item_id,
                COUNT(*) AS sales_count,
                MAX(trade_time) AS most_recent_trade_time
            FROM vending_log
            WHERE YEARWEEK(trade_time, 1) = YEARWEEK(CURDATE(), 1)
            GROUP BY item_id
        ),
        previous_week_sales AS (
            SELECT item_id, COUNT(*) AS sales_count
            FROM vending_log
            WHERE YEARWEEK(trade_time, 1) = YEARWEEK(CURDATE(), 1) - 1
            GROUP BY item_id
        ),
        most_popular_item AS (
            SELECT item_id, sales_count
            FROM current_week_sales
            ORDER BY sales_count DESC, most_recent_trade_time DESC
            LIMIT 1
        )
        SELECT
            m.item_id,
            m.sales_count AS current_week_sales,
            COALESCE(p.sales_count, 0) AS previous_week_sales,
            ROUND(
                CASE
                    WHEN COALESCE(p.sales_count, 0) = 0 THEN 100.0
                    ELSE ((m.sales_count - COALESCE(p.sales_count, 0)) / p.sales_count) * 100
                END, 2
            ) AS percentage_change,
            idb.name_english,
            idb.type
        FROM most_popular_item m
        LEFT JOIN previous_week_sales p ON m.item_id = p.item_id
        LEFT JOIN item_db AS idb ON m.item_id = idb.id;
    ",
    'ZENY_SPENT' => "
        SELECT
            SUM(CASE WHEN YEARWEEK(trade_time, 1) = YEARWEEK(CURDATE(), 1) THEN price ELSE 0 END) AS total_sales_this_week,
            SUM(price) AS total_sales_all_time
        FROM vending_log;
    "
];
