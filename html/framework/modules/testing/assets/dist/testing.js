/**
 * Created by elfuvo on 20.03.17.
 */
$(document).ready(function () {
    // АНКЕТИРОВАНИЕ
    var $countdown = $('#countdown'),
        $form = $('#testing_form'),
        $timer_value = $('#timer_value'),
        $modal = $('#modalAnswer');

    if ($countdown.length > 0) {
        var initTime = parseInt($countdown.data('time'));
        var currentTime = initTime;
        var funcCountdown = new (function () {
            var incrementTime = 1000, // Timer speed in milliseconds
                updateTimer = function () {

                    if (currentTime <= 0) {
                        //funcCountdown.Timer.stop().once();
                        $countdown.html(formatTime(0));
                        $form.submit();
                    } else {
                        $countdown.html(formatTime(currentTime));
                    }
                    $timer_value.val(Math.ceil((initTime - currentTime) / 100));
                    currentTime -= incrementTime / 10;
                },
                init = function () {
                    funcCountdown.Timer = $.timer(updateTimer, incrementTime, true);
                };
            this.resetStopwatch = function () {
                funcCountdown.currentTime = 0;
                this.Timer.stop().once();
            };
            $(init);
        });
    }

    function pad(number, length) {
        var str = '' + number;
        while (str.length < length) {
            str = '0' + str;
        }
        return str;
    }

    function formatTime(time) {
        var hour = parseInt(time / 360000),
            min = parseInt(time / 6000),
            sec = parseInt(time / 100) - (min * 60),
            hundredths = pad(time - (sec * 100) - (min * 6000), 2);
        return (min > 0 ? pad(hour, 2) : "00") + ":" + pad(min, 2) + ":" + pad(sec, 2);
    }

    var step,
        maxStep = $('.question-form-step').length;

    function checkComplete() {
        var answered = $('.question-form-step:has(input:checked)').length;
        if (answered < maxStep) {
            $modal.modal('show');
        } else {
            $form.submit();
        }
    }

    $modal.find('.btn-submit').on('click', function () {
        $form.submit();
    });

    $('.step-btn').click(function () {
        var $tab = $('.question-form-step.active');
        step = parseInt($tab.data('step'));
        if ($(this).hasClass('btn-next') || $(this).hasClass('btn-last')) {
            // check answer
            /*if ($tab.find('input:checked').length === 0) {
                return false;
            }*/

            if ($(this).hasClass('btn-last')) {
                checkComplete();
                return true;
            }

            step++;
            if (step === maxStep) {
                $('.btn-next').hide();
                $('.btn-last').show();
            }
        }
        else {
            step = step - 1;
            if (step <= 1) {
                step = 1;
                $(this).hide();
            }
        }
        changeStep();
        if (step > 1) {
            $('.step-btn.btn-prev').show();
        }
        if (step < maxStep) {
            $('.step-btn.btn-next').show();
           // $('.btn-last').hide();
        }
        return false;
    });

    function changeStep() {
        $('.question-form-step').addClass('hidden').removeClass('active');
        $('.question-active-step').html(step);
        $('.question-form-step[data-step=' + step + ']').removeClass('hidden').addClass('active');
    }

    // end АНКЕТИРОВАНИЕ

});
