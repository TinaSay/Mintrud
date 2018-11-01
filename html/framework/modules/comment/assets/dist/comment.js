/**
 * Created by elfuvo on 13.07.17.
 */
$(document).ready(function () {

    $('.sub-title-and-link a[data-ajax]').on('ajax.success', function (e, data) {
        $('.comment-container-list').html(data);
        $('.sub-title-and-link li').removeClass('active');
        $(this).parent().addClass('active');
    });

    var validator = $('.form-comment.form-discussion-comment').validate({
        rules: {
            "Comment[text]": {
                required: true
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
                        $(form).find("textarea").val('').blur();
                        $(form).find('.error-message').hide();
                        validator.resetForm();
                        if (data.record_id) {
                            $('.sub-title-and-link a[data-ajax]').one('ajax.success', function () {
                                setTimeout(function () {
                                    var target = $('#comment' + data.record_id);
                                    if (target.length > 0) {
                                        jQuery("html:not(:animated),body:not(:animated)").animate({
                                            scrollTop: target.offset().top
                                        }, 800);
                                    }
                                }, 100);
                            });

                            // load list of comments
                            $('.sub-title-and-link li.active a').trigger('click');
                        }
                    }
                }
            });
        }
    });
});