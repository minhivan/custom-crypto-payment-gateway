<?php

/**
 * Constants data class
 */
final class Constants
{
    /**
     * @var string
     */
    public static $currencyConverterAPI = 'https://min-api.cryptocompare.com/data/price';

    /**
     * @var string
     */
    public static $bscChainId = '0x38';

    /**
     * @var array
     */
    public static $chains = [
        '0x38' => [
            'id' => '0x38',
            "name" => "Binance Smart Chain",
            'rpcUrl' => "https://bsc-dataseed.binance.org/",
            "explorerUrl" => "https://bscscan.com/",
            "nativeCurrency" => [
                "name" => "BNB",
                "symbol" => "BNB",
                "decimals" => 18
            ]
        ]
    ];

    /**
     * @var string
     */
    public static $bscChainIdDemo = '0x61';

    /**
     * @var array
     */
    public static $chainsDemo = [
        '0x61' => [
            'id' => '0x61',
            "name" => "BSC Testnet",
            'rpcUrl' => "https://data-seed-prebsc-1-s1.binance.org:8545",
            "explorerUrl" => "https://testnet.bscscan.com/",
            "nativeCurrency" => [
                "name" => "BNB",
                "symbol" => "BNB",
                "decimals" => 18
            ]
        ]
    ];
}