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
    $(".js-FriendsListBody").empty();
    var request = $.ajax({
        url: source,
        type: "POST",
        dataType: "json"
    });
    request.done(function(data) {
        var _prototype_table = data.view;
        $(".js-FriendsListBody").html(_prototype_table);
        $(".js-refreshLoader").addClass('hide');
    });
}

function addFriend(source) {
    var _parent_row = source.parent().parent();
    var request = $.ajax({
        url: source.data("add-friend-url"),
        data: { 'id': source.data("friend-id") },
        type: "POST",
        dataType: "json"
    });
    request.done(function(data) {
        _parent_row.find(".js-AddCell").addClass('hide');
        _parent_row.find(".js-FriendCell").removeClass('hide');
    });
}