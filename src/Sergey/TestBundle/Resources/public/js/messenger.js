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
            url: $(this).attr('action'),
            type: "POST",
            data: data,
            dataType: "json"
        });
        request.done(function(data) {
            $(".js-messageFormSubmit").prop('disabled', false);
            form.reset;
            im_append(data);
            $('.js-closeModel').trigger('click');
        });
        request.fail(function( jqXHR, textStatus ) {
            $(".js-messageFormSubmit").prop('disabled', false);
            $('.js-closeModel').trigger('click');
        });
    });

    $('.js-imEdit').on('click', on_edit);

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
    var _edit_button = _prototype.find('.js-imEdit');
    _prototype.find(".js-imAvatar").attr("src", value.user.photo_filename);
    _prototype.find(".js-imAvatar").attr("alt", value.user.first_name + " " + value.user.last_name);
    _prototype.find(".js-imCreated").html(created.toDateString() + " " + created.toLocaleTimeString());
    _prototype.find(".js-imMessage").html(value.message);
    if (_prototype.data('user-id') == value.user.id){
        _edit_button.removeClass('hide');
        _edit_button.on('click', on_edit);
    }
    _prototype.attr("data-message-id", value.id);
    _prototype.removeClass('hide');
    $(".js-imContent").prepend(_prototype);
}

function on_edit() {
    var _parent = $(this).parent().parent();
    var _edit_container = _parent.find('.js-EditMessageContainer');
    var message_id = _parent.data('message-id');

    var request = $.ajax({
        url: _parent.data('edit-url'),
        type: "POST",
        data: {'message_id': message_id},
        dataType: "json"
    });

    request.done(function(data) {
        $(this).addClass('hide');
        var _message_container = _parent.find('.js-MessageContainer').addClass('hide');
        var _prototype_form = $.parseHTML(data.form);
//            var _submit_edit_form = $(_prototype_form).find(".js-messageEditFormSubmit");
        _edit_container.html(_prototype_form);
        tinymce.init({selector:'textarea'});

        $(_prototype_form).on('submit', function(e) {
            e.preventDefault();

            data = $(this).serializeArray();
            var request = $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: data,
                dataType: "json"
            });
            request.done(function(data) {
                var created = new Date(data.created);
                _edit_container.empty();
                _message_container.find(".js-imCreated").html(created.toDateString() + " " + created.toLocaleTimeString());
                _message_container.find(".js-imMessage").html(data.message);
                _message_container.removeClass('hide')
            });
        });

        _edit_container.removeClass('hide')
    });
}