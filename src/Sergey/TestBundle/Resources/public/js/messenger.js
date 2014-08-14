/**
 * Created by Sergey on 14.08.14.
 */

$( document ).ready(function() {
    var form = $(".js-messageForm");

    $(".js-messageFormSubmit").on('click', function() {
        form.submit();
    });

    form.on('submit', function(e) {
        e.preventDefault();

        data = $(this).serializeArray();
        var request = $.ajax({
            url: form.attr('action'),
            type: "POST",
            data: data,
            dataType: "json"
        });
        request.done(function(data) {
            $('.js-closeModel').trigger('click');
        });
    });

});

function im_update() {
    var ajaxUrl = $(".js-imContent").data("ajax-url");

    var request = $.ajax({
        url: ajaxUrl,
        type: "POST",
        data: { id : 12 },
        dataType: "json"
    });

    request.done(function(data) {
        $.each(data, function(key, value)
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