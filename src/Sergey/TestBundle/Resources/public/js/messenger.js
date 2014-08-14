/**
 * Created by Sergey on 14.08.14.
 */
function im_update() {
    var ajaxUrl = $(".js-imContent").data("ajax-url");

    var request = $.ajax({
        url: ajaxUrl,
        type: "POST",
        data: { id : 12 },
        dataType: "json"
    });

    request.done(function(request) {
        $.each(request, function(key, value)
            {
                var _prototype = $("#imRow-prototype").clone();
                _prototype.find(".js-imAvatar").attr("src", value.user.photo);
                _prototype.find(".js-imAvatar").attr("alt", value.user.name);
                _prototype.find(".js-imMessage").html(value.message);
                _prototype.removeClass('hide');
                console.log(_prototype);
                $(".js-imContent").append(_prototype);
            }
        );
    });

    request.fail(function( jqXHR, textStatus ) {
        console.log("Request failed: " + textStatus );
    });
}