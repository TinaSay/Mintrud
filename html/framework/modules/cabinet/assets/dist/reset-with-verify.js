$(document).ready(function () {
    var $form = jQuery("#reset-with-verify-form"),
        $verifyCodeForm = jQuery('#reset-with-verify-code-form'),
        $modal = jQuery("#reset-with-verify-code-modal"),
        $btn = jQuery("#reset-retry-verify-codes-button"),
        $runner = jQuery("#reset-retry-verify-codes-runner"),
        $succesDiv = jQuery('.success-restore'),
        formGroupPrefix = '.field-resetwithverifyform-';

    $form.on("submit", function (e) {
        e.preventDefault();
        if ($form.data('loading') === true) {
            return false;
        }
        $form.data('loading', true);
        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            dataType: 'JSON',
            success: function (data) {
                $form.data('loading', false);
                console.log(data);
                if (data.success === true) {
                    $form.find('.form-scenario').val(data.scenario);
                    // first step: check email & captcha
                    if (data.scenario === 'verifyCode') {
                        $modal.modal("show");
                    } else if (data.scenario === 'reset') {// password is changed, so show ok message
                        $form.addClass('hidden');
                        $succesDiv.removeClass('hidden');
                    }
                } else if (data.errors) {
                    for (var e in data.errors) {
                        if (!data.errors.hasOwnProperty(e)) {
                            continue;
                        }
                        var group = $(formGroupPrefix + e),
                            errorStr = '',
                            errors = data.errors[e];
                        if (e === 'captcha'/* || e === 'password'*/) {
                            $(formGroupPrefix + 'captcha').removeClass('has-success')
                                .find('img')
                                .trigger('click');
                        }
                        group.removeClass('has-success')
                            .addClass('has-error');
                        for (var i in errors) {
                            if (errors.hasOwnProperty(i)) {
                                errorStr += (errorStr > '' ? '<br>' : '') + errors[i];
                            }
                        }
                        group.find('.help-block').html(errorStr);
                    }
                }
            }
        });
    });

    // check verify code
    $verifyCodeForm.on("afterValidateAttribute", function (event, attribute, messages) {
        if (!messages.length && attribute.name === "code") {
            $form.find(".form-group.hidden")
                .removeClass("hidden")
                .removeClass('has-success');
            $form.find('.form-scenario').val('reset');
            $modal.modal("hide");
            // refresh captcha image
            $form.find(formGroupPrefix + 'captcha').removeClass('has-success');
            $form.find(formGroupPrefix + 'captcha img').trigger('click');
            $form.find(formGroupPrefix + 'captcha input').val('');
        }
    });

    $btn.on("ajax.beforeSend", function () {
        $runner.runner("start");
    });

    $runner.runner({
        autostart: false,
        countdown: true,
        startAt: 60 * 1000,
        stopAt: 0,
        format: function (value) {
            return "Повторить через " + Math.floor(value / 1000) + " секунд";
        }
    }).on("runnerStart", function () {
        jQuery(this).removeClass("hidden");
        $btn.prop("disabled", true);
    }).on("runnerFinish", function () {
        $btn.prop("disabled", false);
        jQuery(this).addClass("hidden");
    });
});