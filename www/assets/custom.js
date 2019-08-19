$(document).ready(function() {
    var app = new managerAppClass();
    app.init();
    app.initConnection();
    app.initPagination();
});


var managerAppClass = function() {
    var self = this;

    // Collect initial data from the dom
    var $appData = $('#app-data').first();

    self.URL_PUSHER_SERVICE = $appData.data('urlPusherService');
    self.URL_LEADERBOARD = $appData.data('urlLeaderboard');
    self.CLASS_ITEM_PLAYER = 'item-player';

    self.page = parseInt($appData.data('page'));
    self.limit = parseInt($appData.data('limit'));
    self.total = parseInt($appData.data('total'));

    self.TIME_STATUS_VISIBLE = 2000;
    self.TIME_STATUS_HIDE_ANIM = 2000;
    self.TIME_REMOVE_PLAYER_ANIM = 500;


    // -----------------------------------------------------------------
    // Initialize the app object
    // -----------------------------------------------------------------
    self.init = function() {

        // Initialize Leaderboard container
        self.$leaderboard = $('.leaderboard-wrapper').first();


        // Initialize playerTemplate
        self.$playerTemplate = $appData.find('.' + self.CLASS_ITEM_PLAYER).first();


        // Initialize Application status container
        self.$status = $('.app-status-wrapper').first();


        // Initialize mixer
        self.mixer = mixitup(self.$leaderboard.get(0), {
            selectors: {
                target: '.' + self.CLASS_ITEM_PLAYER
            },

            behavior: {
                liveSort: true
            },
            
            animation: {
                duration: 500,
                queue: false,
                easing: 'cubic-bezier(0.645, 0.045, 0.355, 1)'
            }
            
        });

        self.mixer.forceRefresh();

        self.reindexLeaderbord();

        // Handle button action click
        $('.action-group > a').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var $this = $(this);
        
            if (self.ajax) {
                self.ajax.abort();
            }

            if ($this.hasClass('action-start')) {
                $this.addClass('disabled').attr('disabled', true);
            } else {
                $this.parent().find('.action-start').first().removeClass('disabled').attr('disabled', false);
            }
            
            self.ajax = $.ajax({
                'url' : $this.attr('href') + '?' + self.getCurrentPaginationQuery(),
                'method' : 'get',
                'dataType' : 'json',
                'success' : function(response) {
                    // console.log('action-group success: ', response);
                },
                'error' : function(jqXHR, textStatus, errorThrown) {
                    // console.log('action-group-limit error: ', errorThrown);
                }
            });
        });


        // Handle Pagination Limit click
        $(document).on('click', '.action-group-limit > li > a', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var $this = $(this);
            self.limit = parseInt($this.data('limit'));
            self.page = 1;

            self.initPagination();
            self.sendClientRequest();

            $this.closest('ul').find('li').removeClass('active');
            $this.closest('li').addClass('active');

            // $('.action-group > a').removeClass('disabled').attr('disabled', false);
        
            if (self.ajax) {
                self.ajax.abort();
            }
            
            self.ajax = $.ajax({
                'url' : self.URL_LEADERBOARD + '?' + self.getCurrentPaginationQuery(),
                'method' : 'get',
                'dataType' : 'json',
                'success' : function(response) {
                    // console.log('action-group-limit success: ', response);
                    self.updateLeaderboard(response);
                },
                'error' : function(jqXHR, textStatus, errorThrown) {
                    console.log('action-group-limit error: ', errorThrown);
                }
            });
        });


        // Handle Pagination Page click
        $(document).on('click', '.action-group-page > li > a', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var $this = $(this);
            self.page = parseInt($this.parent().index() + 1);

            self.initPagination();
            self.sendClientRequest();

            $this.closest('ul').find('li').removeClass('active');
            $this.closest('li').addClass('active');

            // $('.action-group > a').removeClass('disabled').attr('disabled', false);
        
            if (self.ajax) {
                self.ajax.abort();
            }
            
            self.ajax = $.ajax({
                'url' : self.URL_LEADERBOARD + '?' + self.getCurrentPaginationQuery(),
                'method' : 'get',
                'dataType' : 'json',
                'success' : function(response) {
                    // console.log('action-group-page success: ', response);
                    self.updateLeaderboard(response);
                },
                'error' : function(jqXHR, textStatus, errorThrown) {
                    console.log('action-group-page error: ', errorThrown);
                }
            });
        });
    }
    
    
    // -----------------------------------------------------------------
    // Update Leaderboard's child items with fresh data just received.
    // -----------------------------------------------------------------
    self.updateLeaderboard = function(data) {

        var removed = 0,
            created = 0,
            updated = 0;

        self.$leaderboard.children().each(function(k) {
            var $this = $(this);
            
            if (typeof data.leaderboard[$this.data('id')] == 'undefined') {
                removed++;
                $this.remove();
            }
        });

        $.each(data.leaderboard, function(playerName, playerScore) {
            var itemId = playerName;
            var $item = self.$leaderboard.find('*[data-id="' + itemId + '"]').first();
            
            playerScore = parseInt(playerScore);

            if ($item.length === 0) {
                
                // It's a new player. Should be appended to player's list
                $item = self.$playerTemplate.clone();
                $item.find('.item-name').text(playerName);
                $item.find('.item-position').text(self.$leaderboard.length + 1);
                $item.find('.item-score').text(playerScore);
                $item.attr('data-id', playerName);
                $item.attr('data-score', playerScore);
                
                self.$leaderboard.append($item);

                created++;

            } else if (parseInt($item.attr('data-score')) != playerScore) {

                // It's an existing player with changed score. Item data should be updated.
                $item.find('.item-score').text(playerScore);
                $item.attr('data-score', playerScore);
                self.glowItem($item);
                
                updated++;
            }
        });

        self.setAppStatus('Leaderboard items:', 'Added (' + created + '), Updated (' + updated + ')' + ', Removed (' + removed + ')');

        self.mixer.forceRefresh();

        
        //self.mixer.remove(removed, false)
        //    .then(function(state) {
                
                //self.mixer.append(created.children())
                    //.then(function(state) {
                        self.mixer
                            .sort('score:desc')
                            .then(function(state) {
                                self.reindexLeaderbord();
                        });
                    //}
                //);

        //    }
        //);
        
    }


    // -----------------------------------------------------------------
    // Reindex sorted player list.
    // -----------------------------------------------------------------
    self.reindexLeaderbord = function() {
        var pos = (self.limit < 0 || self.limit >= self.total) ? 0 : (self.page - 1) * self.limit;
        self.$leaderboard.children().each(function(k) {
            $(this).find('.item-position').text(pos + k + 1);
        });
    }


    // -----------------------------------------------------------------
    // Update Application status container.
    // -----------------------------------------------------------------
    self.setAppStatus = function(title, message) {
        let $message = $('<span class="app-status-message"><strong class="text-info">' + title + '</strong> ' + message + '</span>');
        self.$status.html($message);
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


    // -----------------------------------------------------------------
    // Glows an item.
    // -----------------------------------------------------------------
    self.glowItem = function($item) {
        var $glow = $('<div class="glow"></div>');
        $item.append($glow);
        $glow.fadeOut(500, function() {
            $(this).remove();
        })
    }
    
    
    // -----------------------------------------------------------------
    // Initialize socket connection.
    // -----------------------------------------------------------------
    self.initConnection = function() {
        
        //self.socket = io(self.URL_PUSHER_SERVICE);
        self.socket = io(self.URL_PUSHER_SERVICE, {
            secure: true,
            transports: ['websocket', 'polling', 'flashsocket']
        });

        self.socket.on('leaderboard', function(message) {
            console.log('Push message received', message);
            try {
                var data = JSON.parse(message);
                self.updateLeaderboard(data);
            } catch (error) {
                console.log(error);   
            }
        });

        self.socket.on('connect', function () {
            self.socket.emit('client_preferences', JSON.stringify({'page' : self.page, 'limit' : self.limit}));
            // console.log('App connected successfully.');
        });
    }


    self.sendClientRequest = function() {
        if (self.socket) {
            self.socket.emit('client_leaderboard', JSON.stringify({'page' : self.page, 'limit' : self.limit}));
        }
        // console.log('Request for changing pagination details.');
    }

    self.getCurrentPaginationQuery = function() {
        var params = {
            page: self.page,
            limit: self.limit
        };
        return $.param(params);
    }


    self.initPagination = function() {
        
        if (!self.total || !self.limit) {
            return;
        }
        var pages = Math.ceil(self.total / self.limit);

        var $pager = $('.action-group-page').first();

        $pager.children().remove();
        if (pages < 2) {
            return;
        }
        
        for (var i = 1; i <= pages; i++) {
            let $page = $('<li class="page-item"><a class="page-link" href="#">' + i + '</a></li>');
            if (self.page === i) {
                $page.addClass('active');
            }
            $pager.append($page);
        }
    }
};