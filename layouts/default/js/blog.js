/**
 * Created by Sarfraz on 4/3/2015.
 */

$(function () {
    // enable syntax highlighting
    hljs.initHighlightingOnLoad();

    // set nav link as selected if on the page
    var pageArray = document.location.href.split('/');
    var page = pageArray[pageArray.length - 1];

    if (!page) {
        page = pageArray[pageArray.length - 2];
    }

    $('.page-links a').each(function () {
        var linkArray = this.href.split('/');
        var link = linkArray[linkArray.length - 1];

        if (link === page) {
            $(this).closest('li').addClass('active');
        }
    });

    // enable tooltips
    $('[data-toggle=tooltip]').tooltip();

    // lightbox for all images + make them responsive
    $('.main-content img').each(function () {
        var src = this.src;
        $(this).addClass('img-responsive');
        $(this).wrap('<a data-toggle="lightbox" href="' + src + '"></a>');
    });

    $(document).delegate('*[data-toggle="lightbox"]', 'click', function (event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });

});
