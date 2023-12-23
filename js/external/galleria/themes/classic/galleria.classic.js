/**
 * @preserve Galleria Classic Theme 2011-02-14
 * http://galleria.aino.se
 *
 * Copyright (c) 2011, Aino
 * Licensed under the MIT license.
 */
 
/*global jQuery, Galleria */

(function($) {

Galleria.addTheme({
    name: 'classic',
    author: 'Galleria',
    css: 'galleria.classic.css',
    defaults: {
        transition: 'slide',
        thumbCrop:  'height',
        
		// set this to false if you want to show the caption all the time:
        _toggleInfo: true
    },
    init: function(options) {
        
        // add some elements
        this.addElement('info-link','info-close');
        this.append({
            'info' : ['info-link','info-close']
        });
        
        // cache some stuff
        var info = this.$('info-link,info-close,info-text'),
            touch = Galleria.TOUCH,
            click = touch ? 'touchstart' : 'click';
        
        // show loader & counter with opacity
        this.$('loader,counter').show().css('opacity', 0.4);

        // some stuff for non-touch browsers
        if (! touch ) {
            this.addIdleState( this.get('image-nav-left'), { left:-50 });
            this.addIdleState( this.get('image-nav-right'), { right:-50 });
            this.addIdleState( this.get('counter'), { opacity:0 });
        }
        
        // toggle info
        if ( options._toggleInfo === true ) {
            info.on( click, function() {
                info.toggle();
            });
        } else {
			info.show();
			this.$('info-link, info-close').hide();
		}
        
        // bind some stuff
        this.on('thumbnail', function(e) {
            if (!touch) {
                // fade thumbnails
                $(e.thumbTarget).css('opacity', 0.6).parent().on('mouseenter', function() {
                    $(this).not('.active').children().stop().fadeTo(100, 1);
                }).on('mouseleave', function() {
                    $(this).not('.active').children().stop().fadeTo(400, 0.6);
                });

                if (e.index === options.show) {
                    $(e.thumbTarget).css('opacity', 1);
                }
            }
        });
        
        this.on('loadstart', function(e) {
            if (!e.cached) {
                this.$('loader').show().fadeTo(200, 0.4);
            }
            
            this.$('info').toggle( this.hasInfo() );
            
            $(e.thumbTarget).css('opacity',1).parent().siblings().children().css('opacity', 0.6);
        });
        
        this.on('loadfinish', function(e) {
            this.$('loader').fadeOut(200);
        });
    }
});

}(jQuery));
