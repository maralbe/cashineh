<?php

namespace thirdparty\cashineh;


/**
 * Interface iCashinehAPIClient
 *
 * @package thirdparty\cashine
 */
interface iCashinehAPIClient
{

    function saveOrderInfo(int $shopping_trip_id, int $total_amount, int $order_id, int $shopping_status, string $order_date);
}