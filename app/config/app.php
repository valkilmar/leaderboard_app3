<?php

if (getenv('ENV') === 'heroku') {
    return [
        'url_polling_service' => 'https://mysterious-refuge-63151.herokuapp.com',
        'url_pusher_service' => 'https://powerful-brushlands-87145.herokuapp.com',
    ];
} else {
    return [
        'url_polling_service' => 'http://local.leaderboard.service',
        'url_pusher_service' => 'http://localhost:3000',
    ];
}