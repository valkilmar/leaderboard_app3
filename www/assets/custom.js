$(document).ready(function() {
    var app = new managerAppClass();
    app.init();
});


var managerAppClass = function() {
    var self = this;
    
    
    // -------------------------
    // Initialize the app object
    // -------------------------
    self.init = function() {
        
        self.$leaderboard = $('.leaderboard-wrapper').first();
        self.timeoutId = null;
        
        $('.api-action-group > a').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var $this = $(this);
        
            clearTimeout(self.timeoutId);
            
            self.preloadStart(self.$leaderboard);
            
            if (self.ajax) {
                self.ajax.abort();
            }
            
            self.ajax = $.ajax({
                'url' : $this.attr('href'),
                'method' : 'get',
                'dataType' : 'json',
                'success' : function(response) {
                    if (response.leaderboard) {
                        self.updateLeaderboard(response.leaderboard);
                    }
                    
                    if ($this.hasClass('api-action-start')) {
                        self.timeoutId = setTimeout(function() {
                            $this.trigger('click');
                          }, 2000);
                        
                        return;
                    }
                    self.preloadEnd(self.$leaderboard);
                    
                },
                'error' : function(jqXHR, textStatus, errorThrown) {
                    self.preloadEnd(self.$leaderboard);
                }
            });
        });
    }
    
    
    // -----------------------------------------------------------------
    // Removes Leaderboard's child items and update it with the new ones
    // -----------------------------------------------------------------
    self.updateLeaderboard = function(data) {
        self.$leaderboard.find('.item-player').remove();
        $.each(data, function(k, v) {
            self.addPlayerToLeaderboard(k, v);
        })
    }
    
    
    // -----------------------------------------------------------------
    // Add a player list item to the Leaderboard
    // -----------------------------------------------------------------
    self.addPlayerToLeaderboard = function(playerName, playerScore) {
        var $item = $('<div class="alert item-player" role="alert"></div>');
        $item.append($('<span class="mr-2">' + playerName + '</span>'));
        $item.append($('<span class="badge badge-danger">' + playerScore + '</span>'));
        self.$leaderboard.append($item);
    }
    
    
    // -----------------------------------------------------------------
    // Adds a preloader over a jQuery object
    // -----------------------------------------------------------------
    self.preloadStart = function($item) {
        if ($item.find('.loader').length > 0) {
            return;
        }
        var $preloader = $('<div class="loader"></div>');
        $preloader.css({
            'width': $item.outerWidth(),
            'height': $item.outerHeight(),
            'top': 0,
            'left': 0,
            'display': 'none'
        });
        $item.css({
            'position': 'relative'
        });
            
        $item.append($preloader);
        $item.find('.loader').fadeIn(500, function(e) {
            
        });
    }
    
    
    // -----------------------------------------------------------------
    // Removes the preloader from a jQuery object if any
    // -----------------------------------------------------------------
    self.preloadEnd = function($item) {
        $item.find('.loader').fadeOut(500, function(e) {
            $(this).remove();
        });
    }
};