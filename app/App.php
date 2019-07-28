<?php

namespace AppVal;
use AppVal\service\PollingService;

class App {
    
    const ACTION_START = 'start';
    const ACTION_STOP = 'stop';
    const ACTION_RESET = 'reset';
    
    /**
     * 
     * @return string
     */
    public function handeRequest()
    {
        $response = '';
        $request = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $action = end($request);

        switch ($action) {
            case App::ACTION_START:
                $response = file_get_contents(Utils::getConfig('url_polling_service') . '/start');
                break;

            case App::ACTION_STOP:
                $response = file_get_contents(Utils::getConfig('url_polling_service') . '/stop');
                break;

            case App::ACTION_RESET:
                $response = file_get_contents(Utils::getConfig('url_polling_service') . '/reset');
                break;
            
            default:
                $response = $this->renderMain(true);
                break;
        }
        
        return $response;
    }
    
    
    /**
     * Renders the main application page
     * 
     * @param boolean $returnResult
     * @return string
     */
    public function renderMain($returnResult = false)
    {
        $players = file_get_contents(Utils::getConfig('url_polling_service') . '/leaderboard');
        $players = json_decode($players, true);
        $content = (!empty($players)) ? $this->renderLeaderboard($players['leaderboard'], true) : '';
        $template = new Template('main', [
            'content' => $content,
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
    public function renderLeaderboard($players, $returnResult = false)
    {
        $template = new Template('leaderboard', [
            'items' => $players
        ]);
        return $template->render($returnResult);
    }
}