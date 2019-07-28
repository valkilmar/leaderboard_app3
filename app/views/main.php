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
                <div class="btn-group api-action-group" role="group">
                    <a class="btn btn-success api-action-start" href="/start">Start polling</a>
                    <a class="btn btn-danger api-action-stop" href="/stop">Stop polling</a>
                    <a class="btn btn-warning api-action-reset" href="/reset">Reset scores</a>
                </div>
            </div>
            <div class="leaderboard-wrapper mt-4"><?php echo $content; ?></div>
            <div class="text-center mt-3">
                <p>Almost 100% powered by <strong class="text-danger">Valentin Stoyanov</strong>. 33% left unfinished unfortunately.</br>
            App1: 100% | App2: 0% | App3: 100%</p>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="assets/custom.js?_t=<?php echo $timestamp; ?>"></script>
    </body>
</html>