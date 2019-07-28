<?php foreach($items as $playerName => $playerScore) { ?>
    <div class="alert item-player" role="alert">
        <span class="mr-2"><?php echo $playerName; ?></span><span class="badge badge-danger"><?php echo $playerScore; ?></span>
    </div>
<?php } ?>
