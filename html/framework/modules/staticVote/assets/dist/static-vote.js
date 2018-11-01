/**
 * Created by elfuvo on 29.06.17.
 */

$(document).ready(function () {
    $('.desc-item:has(input[type="checkbox"]), .desc-item:has(input[type="radio"])').on('click', function () {
        var min = $(this).data('min') > '0' ? parseInt($(this).data('min')) - 1 : 0,
            max = $(this).data('max') > '0' ? parseInt($(this).data('max')) - 1 : 0,
            checked = $(this).find('input[type="checkbox"]').length > 0 ?
                $(this).find('input[type="checkbox"]:checked').length :
                $(this).find('input[type="radio"]').length;

        if (checked === 0) {
            $(this).addClass('error');
        } else if (checked > min && (max === 0 || (checked - 1) <= max)) {
            $(this).removeClass('error');
        } else {
            $(this).addClass('error');
        }
        // lock others inputs
        if (max > 0 && (checked - 1) >= max) {
            $(this).find('input:not(:checked)').prop('disabled', true);
        } else {
            $(this).find('input').prop('disabled', false);
        }
    });

    $('.desc-item select').on('change', function () {
        var val = $(this).find('option:selected').val(),
            block = $(this).closest('.desc-item');
        if (val > '') {
            block.removeClass('error');
        } else {
            block.addClass('error');
        }
    });

    $('.desc-item input[type="text"], ' +
        '.desc-item input[type="number"], ' +
        '.desc-item textarea').on('keyup', function () {
        var val = $(this).val(),
            block = $(this).closest('.desc-item'),
            min = block.data('min') > '0' ? parseInt(block.data('min')) - 1 : 0,
            max = block.data('min') > '0' ? parseInt(block.data('min')) - 1 : 0;
        if (val.length > min && (max === 0 || val.length <= max)) {
            block.removeClass('error');
        } else {
            block.addClass('error');
        }
    });

    $("#form-questionnaire-answer").on("submit", function () {
        var questions = $(this).find('.desc-item:visible').length;
        var answers = 0;
        $(this).find(
            '.desc-item:visible input[type="text"], ' +
            '.desc-item:visible input[type="number"], ' +
            '.desc-item:visible textarea, ' +
            '.desc-item:visible select'
        ).each(function (index, elem) {
            if ($(elem).val() > '') {
                $(elem).closest('.desc-item').removeClass('error');
                answers++;
            } else {
                $(elem).closest('.desc-item').addClass('error');
            }
        });

        $(this).find(
            '.desc-item:visible:has(input[type="radio"])'
        ).each(function (index, elem) {
            if ($(elem).find('input:checked').length > 0) {
                answers++;
                $(elem).removeClass('error');
            } else {
                $(elem).addClass('error');
            }
        });

        $(this).find(
            '.desc-item:visible:has(input[type="checkbox"])'
        ).each(function (index, elem) {
            var min = $(elem).data('min') > '0' ? parseInt($(elem).data('min')) - 1 : 0;
            if ($(elem).find('input:checked').length > min) {
                answers++;
                $(elem).removeClass('error');
            } else {
                $(elem).addClass('error');
            }
        });

        if (answers < questions) {
            $(".invalid_form").show();
            return false;
        }
        else {
            $(".invalid_form").hide();
            return true;
        }
    });

    $("input[type=radio]").on("click", function () {
        if (!$(this).is(":checked")) {
            return true;
        }
        var q_id = $(this).data("id"),
            question = q_id.split('_');
        if (q_id > '') {
            $('.desc-item[data-parent="' + question[0] + '"]').each(function (index, elem) {
                if ($(elem).data('show') > '') {
                    var regExp = new RegExp(q_id + '\\\|+', 'i');
                    if ($(elem).data('show').match(regExp)) {
                        $(elem).slideDown(300).find('input, select, textarea').prop('disabled', false);
                    } else {
                        $(elem).slideUp(300).find('input, select, textarea').prop('disabled', true);
                    }
                }
            });
        }
    });

    $("input[type=checkbox]").on("click", function () {
        var q_id = $(this).data("id"),
            question = q_id.split('_');
        if ($(this).is(":checked") && q_id > '') {
            $('.desc-item[data-parent="' + question[0] + '"]').each(function (index, elem) {
                if ($(elem).data('show') > '') {
                    var regExp = new RegExp(q_id + '\\\|+', 'i');
                    if ($(elem).data('show').match(regExp)) {
                        $(elem).slideDown(300).find('input, select, textarea').prop('disabled', false);
                    }
                }
            });
        } else {
            $('.desc-item[data-parent="' + question[0] + '"]').each(function (index, elem) {
                if ($(elem).data('show') > '') {
                    var regExp = new RegExp(q_id, 'i');
                    if ($(elem).data('show').match(regExp)) {
                        $(elem).slideUp(300).find('input, select, textarea').prop('disabled', true);
                    }
                }
            });
        }

    });
});