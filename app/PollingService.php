<?php

namespace AppVal;
use AppVal\Utils;

class PollingService {

    /**
     * Start polling.
     * 
     * @return void 
     */
    public static function doStart()
    {
        self::call(Utils::getConfig('url_polling_service') . '/start');
    }


    /**
     * Stop polling.
     * 
     * @return void 
     */
    public static function doStop()
    {
        self::call(Utils::getConfig('url_polling_service') . '/stop');
    }


    /**
     * Stop polling and reset scores.
     * 
     * @return void 
     */
    public static function doReset()
    {
        self::call(Utils::getConfig('url_polling_service') . '/reset');
    }


    /**
     * Get leaderboard.
     * 
     * @param array $params
     * @return array
     */
    public static function getLeaderboard($page = 1, $limit = 10)
    {
        return self::call(Utils::getConfig('url_polling_service') . '/leaderboard', [
            'page' => $page,
            'limit' => $limit
        ], true);
    }


    /**
     * Get total players count.
     * 
     * @return int
     */
    public static function getTotalCount()
    {
        $result = self::call(Utils::getConfig('url_polling_service') . '/total-count', [], true);
        if ($result && isset($result['total'])) {
            return (int)$result['total'];
        }

        return 0;
    }


    /**
     * Get current status of the Polling API.
     * 
     * @return boolean
     */
    public static function isPolling()
    {
        $result = self::call(Utils::getConfig('url_polling_service') . '/status', [], true);
        if ($result && isset($result['status'])) {
            return (bool)$result['status'];
        }

        return false;
    }


    /**
     * Executes a call to the API
     * 
     * @param string $url
     * @param array $params 
     * @param boolean $returnTransfer
     * @return null|array 
     */
    private static function call($url, $params = [], $returnTransfer = false)
    {
        $requestHeaders = [
            'Content-Type: application/json',
            // 'Content-Length: ' . strlen($message),
            'Content-Encoding: ' . 'gzip'
        ];

        if (is_array($params)) {
            $url .= '?' . http_build_query($params);
        }

        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $returnTransfer);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
    
        curl_close($ch);

        if (empty($result)) {
            return null;
        }

        $response = \json_decode($result, true);

        if (isset($response['error'])) {
            throw new \BadMethodCallException($response['error']);
        }
        
        return $response;
    }
}