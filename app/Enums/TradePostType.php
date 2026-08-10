<?php

namespace App\Enums;

enum TradePostType: string
{
    case Buy = 'buy';
    case Sell = 'sell';
    case Broker = 'broker';
}
