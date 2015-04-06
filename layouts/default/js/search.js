/**
 * Created by Sarfraz on 4/6/2015.
 */

$(function () {

    ////////////// EDIT BELOW FOUR VARS ///////////////
    // blog url without ending slash
    var blogURL = 'https://codeinphp.github.io';
    // class/id/selector of search keyword textbox
    var $searchKeywordSelector = $('.searchQuery');
    // class/id/selector of search button
    var $searchButtonSelector = $('.searchButton');
    // class/id/selector for element where results will show
    var $searchResultsSelector = $('.main-content');
    ///////////////////////////////////////////////////

    // now search for blog posts
    $searchButtonSelector.click(function () {
        var foundPosts = '';
        var searchQuery = $.trim($searchKeywordSelector.val());
        var queryRegex = new RegExp(searchQuery, "ig");

        // don't search if nothing specified
        if (!searchQuery) {
            return false;
        }

        $.ajax({
            url: blogURL + "/data/blog.json",
            type: "GET",
            dataType: "json",
            success: function (data) {
                $.each(data.posts, function (i, post) {
                    var postBody = $("<div/>").html(post.body).text();

                    if (post.title.search(queryRegex) != -1 || postBody.search(queryRegex) != -1) {
                        var slug = post.title.replace(/[^a-zA-Z0-9\/_|+ -]/, '');
                        slug = $.trim(slug).toLowerCase();
                        slug = slug.replace(/\s+/g,' ');
                        slug = slug.replace(/\W/g, ' ');
                        slug = slug.replace(/\s+/g, '-');

                        if (slug) {
                            foundPosts += '<h3><a href="' + data.settings.url + '/post/' + slug + '.html">' + post.title + '</a></h3><hr>';
                        }
                    }
                });

                if (foundPosts) {
                    $searchResultsSelector.html('').html(foundPosts);
                }
                else {
                    alert('No results found!');
                }
            },
            error: function (e) {
                console.log("error in searching...");
            }
        });
    });

    // on Enter keyboard button, click on search button
    $searchKeywordSelector.keydown(function (event) {
        if (event.keyCode == 13) {
            $searchButtonSelector.click();
        }
    });

});
