/**
 * Created by elfuvo on 12.07.17.
 */

$(document).ready(function () {
    var validator = $('.form-add-email').validate({
        rules: {
            "email": {
                required: true,
                is_email: true
            }
        },
        submitHandler: function (form, e) {
            e.preventDefault();
            var $btn = $(form).find("[type=submit]");
            $btn.addClass('disabled').prop('disabled', true);
            $.ajax({
                url: $(form).attr('action'),
                dataType: 'JSON',
                type: "POST",
                data: $(form).serialize(),
                success: function (data) {
                    $btn.removeClass('disabled').prop('disabled', false);
                    if (data.success) {
                        $(form).find("[name=email]").val('').blur();
                        $(form).find('.error-message').hide();
                        validator.resetForm();
                        var list = $('.email-list'),
                            template = list.find('.template'),
                            clone = template.clone();
                        if (data.added) {
                            clone.removeClass('hidden').find('.email-list__email span:first').html(data.email);
                            clone.appendTo(list);
                        }
                    }
                }
            });
        }
    });

    $('.email-list').on('click', '.link-delete', function (e) {
        e.preventDefault();
        var container = $(this).closest('li'),
            _csrfName = jQuery('meta[name="csrf-param"]').attr('content'),
            data = {};

        container.remove();
        data[_csrfName] = jQuery('meta[name="csrf-token"]').attr('content');
        data['email'] = container.find('.email-list__email span:first').text();
        console.log(data);
        $.ajax({
            url: $('.email-list').data('url'),
            dataType: 'JSON',
            type: "POST",
            data: data,
            success: function (data) {
                if (data.success) {
                    // ok
                }
            }
        });
    });

    $('#formChangePass').validate({
        rules: {
            "CouncilMember[password]": {
                required: true,
                minlength: 8
            },
            "CouncilMember[password_repeat]": {
                equalTo: "#password"
            }
        },
        messages: {
            "CouncilMember[password_repeat]": {
                equalTo: "Значение не совпадает"
            }
        }
    });

    $('#formLkLogin').validate({
        rules: {
            "LoginForm[login]": {
                required: true,
                minlength: 8
            },
            "LoginForm[password]": {
                required: true,
                minlength: 8
            }/*,
            "LoginForm[verifyCode]": {
                required: true
            }*/
        }
    });
});