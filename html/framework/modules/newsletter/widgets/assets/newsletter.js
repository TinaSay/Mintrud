$(function () {
    var newsletterForm = $('#newsletter-form');
    var emailInput = $('#newsletter-email');
    var color = '#ff3333';

    function fieldNormalize() {
        $('.field-newsletter-email').removeClass('error');
        $('.field-newsletter-email').data('aria-invalid', false);
        $('#newsletter-email').parent().find('.placeholder').show();
        $('#newsletter-email-error').remove();

    }

    function categoryNormalize() {
        $('.category-wrap').css('border', '0');
        $('.category-wrap .help-block').html('');
    }

    newsletterForm.validate({
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

        rules: {
            "Newsletter[email]": {
                required: true,
                is_email: true,
            }
        },
        submitHandler: function (form, e) {
            e.preventDefault();
            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                data: $(form).serialize(),
                success: function (data) {
                    if (data.success) {
                        $(form).find('.error-message').hide();
                        $(form).find('.error-summary').remove();

                        fieldNormalize();

                        $('#modalOk').modal();
                    } else if (data.errors) {
                        if (data.errors.email !== undefined) {

                            $('.field-newsletter-email').addClass('error');
                            $('.field-newsletter-email').data('aria-invalid', true);
                            $('#newsletter-email').parent().find('.placeholder').hide();

                            $.each(data.errors.email, function (index, value) {
                                $('#newsletter-email').after('<label id="newsletter-email-error" class="error"' +
                                    ' for="newsletter-email">' + value + '</label>');
                            });

                        }

                        if (data.errors.isNews !== undefined) {
                            $('.category-wrap').css('border', '1px solid ' + color);
                            $('.category-wrap .help-block').css('color', color);
                            $('.category-wrap .help-block').html(data.errors.isNews[0]);
                        }


                    }
                }
            });
        }
    });


    $('#newsletter-isnews, #newsletter-isevent').on('change', function () {
        categoryNormalize();
    });

    $('#newsletter-email').keydown(function () {
        fieldNormalize();
    });

    $('#modalOk').on('hidden.bs.modal', function () {
        emailInput.val('');
        $('.field-newsletter-email').removeClass('full');
    });

});