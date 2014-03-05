
    $('.chart').easyPieChart({
        scaleColor:false,
        barColor: '#111',
        lineWidth:21,
        trackColor:'#2e2e2e',
        lineCap:'butt',
        animate:1000,
        size:130
    });

	$('.shortcode_chart_skin').easyPieChart({
        scaleColor:false,
		barColor: '#111',
        lineWidth:5,
        trackColor:'#e9e6e6',
        lineCap:'butt',
		animate:1200,
        size:130
    });
	
	$('.shortcode_chart').easyPieChart({
        scaleColor:false,
		barColor:'#555',
        lineWidth:5,
        trackColor:'#e9e6e6',
        lineCap:'butt',
		animate:1200,
        size:130
    });

    var scrollHandlerPageTO = false,
        scrollHandlerPage = function(){
            var scrollPosition  = parseInt($(window).scrollTop(), 10),
                windowHeight = $(window).height() - 0,
                windowWidth = $(window).width() - 0;

            if ($('#headcont').hasClass('has-fixed')) {
                var topHeaderHeight, subHeaderHeight, subAndTopHeight, middleHeaderHeight, resetMarginMenu;

                headerLineHeight    = 120;
                topHeaderHeight     = $('.topheader').height();
                subHeaderHeight     = $('.top_static_img').height();
                subAndTopHeight     = topHeaderHeight + subHeaderHeight;
                newTopHeaderHeight  = Math.max(subAndTopHeight-scrollPosition, 0);
                newTopHeaderSub     = Math.max(subHeaderHeight-scrollPosition, -topHeaderHeight);

                middleHeaderHeight = Math.max(Math.round(headerLineHeight-scrollPosition), 50);
                hideTopBar = subHeaderHeight + middleHeaderHeight;

                if (scrollPosition > 0 && windowWidth > 1023) {
                    $('#headers').css({'top': newTopHeaderHeight});

                    resetMarginMenu = 0;
                    $('.wrapper.navbar-default').css({'height': middleHeaderHeight+'px', 'line-height': middleHeaderHeight+'px'});
                    $('.megaMenuContainer').css({'margin': resetMarginMenu+'px'});

                    if ($('#headcont').hasClass('has-subheader')) {
                        $('#topheaders').addClass('navbar-fixed-top');
                        $('#subheaders').css({'margin-top': topHeaderHeight});
                    }

                    newHeaderHeight = $('.wrapper.navbar-default').height();
                    $('#contents').css({'padding-top': newHeaderHeight});

                } else {
                    $('#headers').css({'top': subAndTopHeight});
                    $('#contents').css({'padding-top': headerLineHeight});

                    $('.wrapper.navbar-default').css({'height': '', 'line-height': ''});
                    $('.megaMenuContainer').css({'margin': ''});
                    $('#topheaders').removeClass('navbar-fixed-top');
                    $('#subheaders').css({'margin-top': ''});
                }

                if (scrollPosition >= subHeaderHeight) {
                    $('#topheaders').css({'top': newTopHeaderSub});
                } else {
                    $('#topheaders').css({'top': 0});
                }
            }
        };

    scrollHandlerPage();

    $(window).scroll(function() {
        scrollHandlerPage();
    });

    $(window).resize(function() {
        if (scrollHandlerPageTO !== false) {
            window.clearTimeout(scrollHandlerPageTO);
        }
        scrollHandlerPageTO = window.setTimeout(function(){
            scrollHandlerPage();
        }, 5);
    });

    $('.w-toplink').click(function(event) {
        event.preventDefault();
        event.stopPropagation();
        $.smoothScroll({
            scrollTarget: '#'
        });
    });


