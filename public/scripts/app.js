var Dashboard = function () {

    var dataSets = {};
    var total = 0;
    var active = 0;

    var initHandlers = function () {

        $('#burger').click(function () {
        	$(this).toggleClass('open');
        	var overlay = $('.overlay');
            if (overlay.hasClass('show')) {
            	overlay.removeClass('show');
            } else {
            	overlay.addClass('show');
            }
        });

        $(".navigation .arrow").click(function (e) {

            var paginate = $(this).attr('data-paginate');

            if (paginate == '+') {
                active++;
            } else {
                active--;
            }

            arrayBounds();

            $('.iframe').removeClass('show');

            var frame = normalizePath(dataSets[active]);

            clearInterval(x);
            var x = setTimeout(function () {
                showFrame(frame);
            }, 750);
            
        });
    }
    
    var arrayBounds = function () {
        if (active >= total) {
            active = 0;
        } else if (active <= 0) {
            active = total - 1;
        }
        console.log(active, 'active');
    }

    var initRandomFrame = function () {
        var frame = randomFrame();
        setTimeout(function () {
            showFrame(frame);
            $('.iframe').addClass('show');
        }, 500);
    };

    var normalizePath = function (data) {
        return '/assets/' + data.parent_name + '/' + data.name + '/index.html';
    }

    var showFrame = function (frame) {
    	var data = dataSets[active];
    	$('.project .info').html('<a src="/assets/' + data.parent_name + '/' + data.name + '/index.html" class="category" target="_blank">' + data.parent_name + '</a> / ' + data.name);
        $('.iframe').addClass('show');
        $('.iframe').attr('src', frame);
    }

    var randomFrame = function () {
        total = dataSets.length;
        active = Math.floor((Math.random() * total));
        arrayBounds();
        return normalizePath(dataSets[active]);
    }

    return {

        load: function () {
            var that = this;
            $.when($.post('/data')).done(function (response) {
                dataSets = JSON.parse(response);
                that.init();
            });
        },

        init: function () {
            initHandlers();
            initRandomFrame();
        },

        isTouchDevice: function () {
            try {
                document.createEvent("TouchEvent");
                return true;
            } catch (e) {
                return false;
            }
        },

        getViewPort: function () {
            var e = window,
                a = 'inner';
            if (!('innerWidth' in window)) {
                a = 'client';
                e = document.documentElement || document.body;
            }

            return {
                width: e[a + 'Width'],
                height: e[a + 'Height']
            };
        },

        getResponsiveBreakpoint: function (size) {
            var sizes = {
                'xs': 480, // extra small
                'sm': 768, // small
                'md': 992, // medium
                'lg': 1200 // large
            };

            return sizes[size] ? sizes[size] : 0;
        },

        randValue: function () {
            return (Math.floor(Math.random() * (1 + 50 - 20))) + 10;
        }

    };

}();

jQuery(document).ready(function () {
    Dashboard.load();
});
