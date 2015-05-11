$(function () {
    $('#side-menu').metisMenu();
});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function () {
    $(window).bind("load resize", function () {
        topOffset = 50;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse')
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse')
        }

        height = (this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    })
});

$(function () {
    var path = document.location.href.split('/');
    var currentPage = path[path.length - 1];
    var $selector = $('.sidebar a[href*="' + currentPage + '"]');

    $('.sidebar a').removeClass('active');
    $('.sidebar li').removeClass('active');

    $selector.addClass('active');
    $selector.closest('li').addClass('active');
    $selector.closest('ul.nav-second-level').closest('li').addClass('active');
    $selector.closest('ul.nav-second-level').addClass('in');
    $selector.closest('ul.nav-second-level').addClass('collapse');
});

