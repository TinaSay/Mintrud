/**
 * Created by elfuvo on 27.06.17.
 */

$(document).ready(function () {
    $('.vote-variant input[type=radio]').on('click', function () {
        var val = parseInt($(this).val()),
            $comment = $('#vote-form-comment'),
            show = parseInt($comment.data('vote-show'));

        if (show === val) {
            $comment.removeClass('hidden')
                .find('textarea')
                .prop('disabled', false);
        } else {
            $comment.addClass('hidden')
                .find('textarea')
                .prop('disabled', true);
        }
    });

    $('.form-comment.form-popup').validate({
        rules: {
            "CouncilDiscussionVote[comment]": {
                required: true
            }
        },
        submitHandler: function (form, e) {
            e.preventDefault();
            sendVoteAjax($(form));
        }
    });

    $('.vote-form').on('submit', function (e) {
        e.preventDefault();
        var $form = $(this);
        sendVoteAjax($form);
    });

    $('.vote-btn.submit').on('click', function () {
        $(this).closest('form').submit();
    });

    function sendVoteAjax($form) {
        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            dataType: 'JSON',
            data: $form.serialize(),
            success: function (data) {
                var aside = $('#addVote');
                if (data.success) {
                    $('#modalComment').modal('hide');
                    aside.find('form').remove();
                    if (data.message) {
                        $('<p>' + data.message + '</p>').insertAfter(aside.find('h4'));
                    }
                    $('.comment-form-container .form-discussion-comment').removeClass('hidden');
                    $('.comment-form-container .comment-info-form').addClass('hidden');

                    $('.sub-title-and-link a[data-ajax]').one('ajax.success', function () {
                        setTimeout(function () {
                            var target = $('.comment-container-list .comment-list:last');
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
        });
    }
});