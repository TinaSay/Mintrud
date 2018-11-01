$(document).ready(function () {

    // почтовым оправлением или по электронке
    $('input.reply').change(function () {
        $('.block-email-or-mail').removeClass('active');
        $('.block-email-or-mail[data-name="' + $(this).val() + '"]').addClass('active');
    });

    // ознакомлен с правилами приема
    $('#inputDeal').change(function () {
        if ($(this).prop('checked') === true) {
            $('#recourseFormBtnSubmit').prop('disabled', false);
        }
        else {
            $('#recourseFormBtnSubmit').prop('disabled', true);
        }
    });


    $('select.country').on('change', function () {
        $.getJSON($(this).data('url') + "?country=" + $(this).val(), function (data) {
            if (data.success) {
                var $regions = $('select.region');
                $regions.empty();
                $regions.append('<option value=""></option>');
                for (var i in data.list) {
                    if (data.list.hasOwnProperty(i)) {
                        if (i === '') {
                            continue;
                        }
                        $regions.append('<option value="' + i + '">' + data.list[i] + '</option>');
                    }
                }
                $regions.selectpicker('refresh');
            }
        });
    });

    function showInputErrors($input, errors) {
        var $label = $input.parent().find('.error'),
            html = '';
        for (var i in errors) {
            if (errors.hasOwnProperty(i)) {
                html += errors[i].join('<br>');
            }
        }
        if ($label.length === 0) {
            $label = $('<label class="error" for="' + $input.attr("id") + '"></label>');
            $label.insertAfter($input);
        }
        $label.html(html);
        if (html.length > 0) {
            $input.closest('.form-group--placeholder-fix').addClass('error');
        }
    }


    $('.btn-send-code').on('click', function (e) {
        e.preventDefault();
        var $btn = $(this),
            $input = $('.email-input'),
            _csrfName = jQuery('meta[name="csrf-param"]').attr('content'),
            data = {},
            $label = $input.parent().find('error');
        if (!$btn.hasClass('disabled')) {
            $btn.addClass('disabled');
            $label.empty();
            data[_csrfName] = jQuery('meta[name="csrf-token"]').attr('content');
            data['email'] = $input.val();
            $.ajax({
                url: $btn.data('url'),
                type: 'POST',
                data: data,
                dataType: 'JSON',
                success: function (data) {
                    if (data.success) {
                        $('.btn-send-code').prop('disabled', true).text('Код отправлен');
                        $('.code-container').show();
                        $(".retry-verify-codes-runner").removeClass('hidden').runner("start");
                        $input.prop('readonly', true);
                    } else if (data.errors) {
                        showInputErrors($input, data.errors)
                    }
                }
            });
        }
    });

    $('.verify-code-input').on('blur', function () {
        var $code = $(this),
            $input = $('.email-input'),
            _csrfName = jQuery('meta[name="csrf-param"]').attr('content'),
            data = {};
        if ($code.val() > '') {
            data[_csrfName] = jQuery('meta[name="csrf-token"]').attr('content');
            data['email'] = $input.val();
            data['code'] = $code.val();
            $.ajax({
                url: $code.data('url'),
                type: 'POST',
                data: data,
                dataType: 'JSON',
                success: function (data) {
                    if (data.registered) {
                        $('.confirm-email__error').empty().hide();
                        $input.prop('readonly', true)
                            .data('confirmed', true)
                            .closest('.form-group').removeClass('error')
                            .find('label.error').hide();
                        $('.email-accept').show();
                        $('.btn-send-code').hide();
                        $(".retry-verify-codes-runner").hide().runner("stop");
                        $('.confirm-email__result').show().find('strong').text($input.val());
                        setTimeout(function () {
                            $('.code-container').hide();
                        }, 3000);
                    } else if (data.errors) {
                        var html = '';
                        for (var i in data.errors) {
                            if (data.errors.hasOwnProperty(i)) {
                                html += data.errors[i].join('<br>');
                            }
                        }
                        $('.confirm-email__error').html(html).show();
                    }
                }
            });
        }
    });

    jQuery.validator.addMethod("confirmed", function (value, element) {
        return this.optional(element) || $(element).data('confirmed');
    }, "Подтвердите E-mail");

    jQuery.validator.addMethod("totalFileSize", function (value, element, params) {
        var totalFileSize = 0,
            maxSize = $(element).data('size-limit');
        if (params.list && maxSize > 0) {
            $(params.list).each(function (index, elem) {
                if (elem.files) {
                    $(elem.files).each(function (index2, file) {
                        totalFileSize += file.size;
                    })
                }
            });

            $('.old-attachments').each(function (index, elem) {
                totalFileSize += parseInt($(elem).data('size'));
            });

            return (totalFileSize <= maxSize);
        }

        return true;
    }, "Превышен размер прикрепляемых файлов");

    // буквы
    jQuery.validator.addMethod("is_letter", (function(value, element, param) {
        var letter;
        letter = value.match(/^[а-яёa-z ]+$/gi);
        return this.optional(element) || letter;
    }), "Это поле может содержать только буквы");

    $('#formAppeal').validate({
        rules: {
            "AppealForm[lastName]": {
                required: true,
                is_letter: true,
                minlength: 2,
                maxlength: 25
            },
            "AppealForm[firstName]": {
                required: true,
                is_letter: true,
                minlength: 2,
                maxlength: 25
            },
            "AppealForm[secondName]": {
                is_letter: true,
                minlength: 2,
                maxlength: 25
            },
            "AppealForm[email]": {
                email: true,
                required: function () {
                    return $('.jq-radio.reply.email').hasClass('checked');
                },
                confirmed: true
            },
            "AppealForm[index]": {
                digits: true,
                minlength: 6,
                maxlength: 6,
                required: function () {
                    return $('.jq-radio.reply.postal').hasClass('checked');
                }
            },
            "AppealForm[region]": {
                required: function () {
                    return $('.jq-radio.reply.postal').hasClass('checked');
                }
            },
            "AppealForm[city]": {
                required: function () {
                    return $('.jq-radio.reply.postal').hasClass('checked');
                }
            },
            "AppealForm[text]": {
                required: true
            },
            "AppealForm[attachments][]": {
                totalFileSize: {
                    list: '#formAppeal input[type="file"]'
                }
            }
        },
        showErrors: function (errorMap, errorList) {
            for (var i in errorList) {
                if (errorList.hasOwnProperty(i)) {
                    if ($(errorList[i].element).hasClass('email-input') && errorList[i].method !== 'confirmed') {
                        $('.btn-send-code').addClass('disabled').prop('disabled', true);
                    } else if ($('.confirm-email').is(':hidden')) {
                        $('.btn-send-code').removeClass('disabled').prop('disabled', false);
                    }
                }
            }
            this.defaultShowErrors();
        },
        success: function (label, input) {
            if ($(input).hasClass('email-input')) {
                $('.btn-send-code').removeClass('disabled').prop('disabled', false);
            }
        }
    });

    jQuery(".send-new-code").on("ajax.beforeSend", function () {
        jQuery(".retry-verify-codes-runner").runner("start");
    });

    jQuery(".retry-verify-codes-runner").runner({
        autostart: false,
        countdown: true,
        startAt: 60 * 1000,
        stopAt: 0,
        format: function (value) {
            return "Отправить код повторно можно через " + Math.floor(value / 1000) + " секунд";
        }
    }).on("runnerStart", function () {
        jQuery(this).removeClass("hidden");
        jQuery(".send-new-code").prop("disabled", true).hide();
    }).on("runnerFinish", function () {
        jQuery(".send-new-code").prop("disabled", false).show();
        jQuery(this).addClass("hidden");
    });

    var $filesContainer = $('#files'),
        idCounter = parseInt($filesContainer.data('count'));

    $('#appeal-attachment').bind('change', function () {
        var $clone = $(this).clone(true),
            fileInput = this;
        idCounter++;
        if (fileInput.files && fileInput.files.length > 0) {
            $.each(fileInput.files, function (index, file) {
                var li = $('<li/>'),
                    node = $('<span class="email-list__email" />');
                node.append($('<span/>').text(file.name));
                node.append("<a href='#' data-index='" + idCounter + "' type='button' class='delete link-delete'></a>");
                node.appendTo(li);
                li.appendTo('#files');
            });
        }
        $(this).unbind('click').unbind('change')
            .attr('class', 'hidden generated')
            .attr('id', 'generatedFileInput_' + idCounter);
        $clone.insertAfter($(this));
        $clone.val('');
    });

    // rem selected files
    $filesContainer.on('click', '.link-delete', function (e) {
        e.preventDefault();
        var li = $(this).closest('li'),
            fileInput = $('#generatedFileInput_' + $(this).data('index'));
        li.remove();
        if (fileInput.length > 0) {
            fileInput.remove();
        }
    });

    // refresh session every 5 min
    setInterval(function(){
        $.getJSON('/reception/form/refresh-session')
    }, 5 * 60 * 1000);
});