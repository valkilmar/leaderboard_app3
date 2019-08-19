<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Leaderboard</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Lato:300,700|Prata" rel="stylesheet">
        <link rel="stylesheet" href="assets/custom.css?_t=<?php echo $timestamp; ?>">
    </head>
    <body>
        <div class="container-fluid mt-4">
            <div class="text-center">
                <h1>Leaderboard</h1>
                <div class="text-center mt-3">
                    <p>Polling service (App1) | Listener/Pusher (App2) | Client application (App3) | Pagination (Bonus)</br>
                    Powered by <strong class="text-danger">Valentin Stoyanov</strong>.
                    </p>
                </div>
                <div class="btn-group action-group" role="group">
                    <a class="btn btn-success action-start" href="<?php echo $urlStart; ?>">Start polling</a>
                    <a class="btn btn-danger action-stop" href="<?php echo $urlStop; ?>">Stop polling</a>
                    <a class="btn btn-warning action-reset" href="<?php echo $urlReset; ?>">Reset scores</a>
                </div>
            </div>
            <div class="app-status-wrapper text-center mt-4"></div>
            <div class="row mt-2">
                <div class="col">
                    <ul class="action-group-limit pagination pagination-sm">
                        <li class="page-item<?php echo ( ($limit === 10) ? ' active' : ''); ?>">
                            <a class="page-link" data-limit="10" href="#">Top 10</a>
                        </li>
                        <li class="page-item<?php echo ( ($limit === 20) ? ' active' : ''); ?>">
                            <a class="page-link" data-limit="20" href="#">Top 20</a>
                        </li>
                        <li class="page-item<?php echo ( (($limit === -1) || ($limit >= $total)) ? ' active' : ''); ?>">
                            <a class="page-link" data-limit="-1" href="#">Show All</a>
                        </li>
                    </ul>
                </div>
                <div class="col">
                    <ul class="action-group-page pagination pagination-sm justify-content-end">
                    </ul>
                </div>
            </div>
            <div class="leaderboard-wrapper mt-4">
                <?php echo $leaderboard; ?>
            </div>
            
        </div>

        <div id="app-data" style="display: none"
            data-url-pusher-service="<?php echo ($urlPusherService); ?>"
            data-url-leaderboard="<?php echo ($urlLeaderboard); ?>"
            data-total="<?php echo ($total); ?>"
            data-page="<?php echo ($page); ?>"
            data-limit="<?php echo ($limit); ?>"
        >
            <?php echo $playerTemplate; ?>
        </div>
        
        <script src="<?php echo ($urlPusherService); ?>/socket.io/socket.io.js?_t=<?php echo $timestamp; ?>"></script>
        <script src="assets/mixitup.min.js?_t=<?php echo $timestamp; ?>"></script>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="assets/custom.js?_t=<?php echo $timestamp; ?>"></script>
    </body>
</html>