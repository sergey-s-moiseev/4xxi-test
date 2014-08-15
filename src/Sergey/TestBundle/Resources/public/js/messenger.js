/**
 * Created by Sergey on 14.08.14.
 */

$( document ).ready(function() {
    var form = $(".js-messageForm");

    $(".js-messageFormSubmit").on('click', function() {
        $(this).prop('disabled', true);
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
            $(".js-messageFormSubmit").prop('disabled', false);
            im_append(data);
            $('.js-closeModel').trigger('click');
        });
    });

    $('.js-imEdit').on('click', function() {
        var _parent = $(this).parent().parent();
        var _edit_container = _parent.find('.js-EditMessageContainer');

        var request = $.ajax({
            url: _parent.data('edit-url'),
            type: "POST",
            data: {'message_id': _parent.data('message-id')},
            dataType: "json"
        });

        request.done(function(data) {
            $(this).addClass('hide');
            _parent.find('.js-MessageContainer').addClass('hide');
            _edit_container.html(data);
            _edit_container.removeClass('hide')
        });
    });


    setInterval (im_update, 30000);
});

function im_update() {
    var ajaxUrl = $(".js-imContent").data("ajax-url");
    $(".js-refreshButton").addClass('hide');
    $(".js-refreshLoader").removeClass('hide');

    var _child = $(".js-imContent").find(".js-imCreated:first").html();

    var request = $.ajax({
        url: ajaxUrl,
        type: "POST",
        data: { 'last_update': _child },
        dataType: "json"
    });

    request.done(function(data) {
        $.each(data, function(key, value)
            {
                im_append(value)
            }
        );
        $(".js-refreshButton").removeClass('hide');
        $(".js-refreshLoader").addClass('hide');
    });

    request.fail(function( jqXHR, textStatus ) {
        $(".js-refreshButton").removeClass('hide');
        $(".js-refreshLoader").addClass('hide');
//        console.log("Request failed: " + textStatus );
    });
}

function im_append(value) {
    var _prototype = $("#imRow-prototype").clone();
    var created = new Date(value.created);
    _prototype.find(".js-imAvatar").attr("src", value.user.photo_filename);
    _prototype.find(".js-imAvatar").attr("alt", value.user.first_name + " " + value.user.last_name);
    _prototype.find(".js-imCreated").html(created.toDateString() + " " + created.toLocaleTimeString());
    _prototype.find(".js-imMessage").html(value.message);
    _prototype.find(".js-imMessageEdit").html(value.message);
    _prototype.attr("data-message-id", value.id);
    _prototype.removeClass('hide');
    $(".js-imContent").prepend(_prototype);
}

function im_edit(element) {
    var message = element.parent().find('js-imMessageEdit').html();
    console.log(message);
}