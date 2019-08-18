<?php

namespace AppVal;
use AppVal\PollingService;

class App {
    
    const ACTION_START = 'start';
    const ACTION_STOP = 'stop';
    const ACTION_RESET = 'reset';
    const ACTION_LEADERBOARD = 'leaderboard';
    
    /**
     * 
     * @return string
     */
    public function handeRequest()
    {
        $response = '';
        $action = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), '/');
        
        switch ($action) {
            case self::ACTION_START:
                PollingService::doStart();
                break;

            case self::ACTION_STOP:
                PollingService::doStop();
                break;

            case self::ACTION_RESET:
                PollingService::doReset();
                break;

            case self::ACTION_LEADERBOARD:
                // PollingService::doStop();
                $page = (int)Utils::getValue($_GET, 'page', 1);
                $limit = (int)Utils::getValue($_GET, 'limit', 10);
                $result = PollingService::getLeaderboard($page, $limit);
                echo json_encode($result);
                exit;
            
            default:
                $page = (int)Utils::getValue($_GET, 'page', 1);
                $limit = (int)Utils::getValue($_GET, 'limit', 10);
                $response = $this->renderMain($limit, $page, true);
                break;
        }
        
        return $response;
    }

    
    /**
     * Renders the main application page
     *
     * @param int $limit 
     * @param boolean $returnResult
     * @return string
     */
    public function renderMain($limit, $page = 1, $returnResult = false)
    {
        $playerTemplate = new Template('playerItem', [
            'name' => '',
            'score' => 0,
            'position' => 0,
        ]);
        
        $template = new Template('main', [
            'leaderboard' => $this->renderLeaderboard($limit, $page, true),
            'playerTemplate' => $playerTemplate->render(true), 
            'page' => 1,
            'limit' => $limit,
            'total' => PollingService::getTotalCount(),
            'isPolling' => PollingService::isPolling(),
            'urlStart' => '/' . self::ACTION_START,
            'urlStop' => '/' . self::ACTION_STOP,
            'urlReset' => '/' . self::ACTION_RESET,
            'urlLeaderboard' => '/' . self::ACTION_LEADERBOARD,
            'urlPusher' => Utils::getConfig('url_pusher'),
            'timestamp' => time()
        ]);

        return $template->render($returnResult);
    }
    
    
    /**
     * 
     * Renders the Leaderbord
     * 
     * @param array $players
     * @param boolean $returnResult
     * @return string
     */
    public function renderLeaderboard($limit, $page = 1, $returnResult = false)
    {
        $playerTemplate = new Template('playerItem', [
            'name' => '',
            'score' => '',
            'position' => 0,
        ]);

        $result = PollingService::getLeaderboard(1, $limit);

        $players = (isset($result['leaderboard'])) ? $result['leaderboard'] : [];

        if (!empty($players)) {
            $position = 1;
            $response = '';

            foreach ($players as $playerName => $playerScore) {
                $response .= $playerTemplate->render(true, [
                    'name' => $playerName,
                    'score' => $playerScore,
                    'position' => $position++,
                ]);
            }
        }

        if ($returnResult) {
            return $response;
        }

        echo $response;
    }
}