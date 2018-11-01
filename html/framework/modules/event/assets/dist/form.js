$(document).ready(function () {

    // разрешаю
    $('#inputDeal').change(function () {
        if ($(this).prop('checked') === true) {
            $('#technicalSupportFormBtnSubmit').prop('disabled', false);
        }
        else {
            $('#technicalSupportFormBtnSubmit').prop('disabled', true);
        }
    });


    $('#accreditationForm').validate({
        // добавляем класс к обертке у элементов формы при валидации
        highlight: function (element, errorClass, validClass) {
            $(element).closest(".form-group")
                .removeClass(validClass)
                .addClass(errorClass);
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).closest(".form-group")
                .removeClass(errorClass)
                .addClass(validClass);
        },
        // выводим сообщение в шапку формы о наличии ошибок
        invalidHandler: function (event, validator) {
            // 'this' refers to the form
            var errors = validator.numberOfInvalids();
            if (errors) {
                var message = 'Проверьте, пожалуйста, правильность заполнения формы.';
                $(this).find("div.error-message").html(message);
                $(this).find("div.error-message").show();
            } else {
                $(this).find("div.error-message").hide();
            }
        },
        rules: {
            "AccreditationForm[name]": {
                required: true
            },
            "AccreditationForm[surname]": {
                required: true
            },
            "AccreditationForm[middle_name]": {
                required: true
            },
            "AccreditationForm[passport_series]": {
                required: true
            },
            "AccreditationForm[passport_number]": {
                required: true
            },
            "AccreditationForm[passport_burthday]": {
                required: true
            },
            "AccreditationForm[passport_burthplace]": {
                required: true
            },
            "AccreditationForm[passport_issued]": {
                required: true
            },
            "AccreditationForm[org]": {
                required: true
            },
            "AccreditationForm[job]": {
                required: true
            },
            "AccreditationForm[phone]": {
                required: true
            },
            "AccreditationForm[email]": {
                required: true,
                is_email: true
            }
        },
        submitHandler: function (form, e) {
            e.preventDefault();
            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                dataType: 'JSON',
                data: $(form).serialize(),
                success: function (data) {
                    if (data.success) {
                        $(form).find('.error-message').hide();
                        $(form).find('.error-summary').remove();
                        $('.modal').modal('hide');
                        $('#modalOk').modal();
                    } else if (data.errors) {
                        var summary = '';
                        for (var i in data.errors) {
                            if (data.errors.hasOwnProperty(i)) {
                                summary += '<span class="error">' + data.errors[i].join('<br>') + '</span><br>';
                            }
                        }
                        $(form).find('.error-message').text('Проверьте, пожалуйста, правильность заполнения формы.').show().after(
                            '<div class="error-summary">' + summary + '</div>'
                        );
                    }
                }
            });
        }
    });
});