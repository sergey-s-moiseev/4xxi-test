/**
 * Created by Sergey on 16.08.14.
 */

$( document ).ready(function() {
    var _friends_title = $('.js-FriendsTitle');

    $(".js-FriendsWindow").on('click', function() {
        switch ($(this).data("source")) {
            case "4xxi":
                _friends_title.html("4XXI Test");
                break;
            case "facebook":
                _friends_title.html("Facebook");
                break;
        }
        getList($(this).data("url"));
    });
});

function getList(source) {
    $(".js-refreshLoader").removeClass('hide');
    var request = $.ajax({
        url: source,
        type: "POST",
        dataType: "json"
    });
    request.done(function(data) {
        $(".js-FriendsListBody").html(data.view);
        $(".js-refreshLoader").addClass('hide');
    });
}