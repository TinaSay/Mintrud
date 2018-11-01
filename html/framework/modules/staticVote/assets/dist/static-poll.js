/**
 * Created by elfuvo on 29.06.17.
 */

$(document).ready(function () {
    $('.form-group').on('change', 'input[type="checkbox"], input[type="radio"]', function () {
        var input = $(this),
            group = input.closest('.form-group'),
            min = group.data('min') > '0' ? parseInt(group.data('min')) - 1 : 0,
            max = group.data('max') > '0' ? parseInt(group.data('max')) - 1 : 0,
            checked = group.find('input[type="checkbox"]').length > 0 ?
                group.find('input[type="checkbox"]:checked').length :
                group.find('input[type="radio"]').length;


        if (checked === 0) {
            group.addClass('error');
        } else if (checked > min/* && (max === 0 || (checked - 1) <= max)*/) {
            group.removeClass('error');
        } else {
            group.addClass('error');
        }
        // lock others inputs
        if (max > 0 && (checked - 1) > max) {
            /*group.find('input:not(:checked)')
                .prop('disabled', true)
                .prop('required', false)
                .trigger('refresh');*/
            // uncheck other checkboxes
            var checkBoxId = group.data('prev-input');
            if (checkBoxId) {
                $('#' + checkBoxId)
                    .prop('checked', false)
                    .trigger('refresh');
            }
        } else {
            group.find('input')
                .prop('disabled', false)
                .prop('required', true)
                .trigger('refresh');
        }
        group.data('prev-input', input.attr('id'));
    });


    $('.form-group input[type="text"], ' +
        '.form-group input[type="number"], ' +
        '.form-group textarea').on('keyup', function () {
        var val = $(this).val(),
            block = $(this).closest('.form-group'),
            min = block.data('min') > '0' ? parseInt(block.data('min')) - 1 : 0,
            max = block.data('min') > '0' ? parseInt(block.data('min')) - 1 : 0;
        if (val.length > min && (max === 0 || val.length <= max)) {
            block.removeClass('error');
        } else {
            block.addClass('error');
        }
    });

    $(".wrap-check").on("click", function () {
        var $input = $(this).find('input'),
            $question = $(this).closest('.form-group');

        var q_id = $input.data("id"),
            question = q_id.split('_'),
            show = $input.is(":checked");
        if ($input.attr('type') === 'checkbox') {
            q_id = '';
            $question.find('input:checked').each(function (index, elem) {
                q_id += (q_id > '' ? '\\\||' : '') + $(elem).data('id');
                show = true;
            });
        }
        if (show && q_id > '') {
            $('.form-group[data-parent="' + question[0] + '"]').each(function (index, elem) {
                if ($(elem).data('show') > '') {
                    var regExp = new RegExp(q_id + '\\\|+', 'i');
                    if ($(elem).data('show').match(regExp)) {
                        if ($(elem).is(':visible')) {
                            return true;
                        }
                        $(elem).removeClass('hidden')
                            .css('display', 'none')
                            .slideDown(300).find('input, textarea')
                            .prop('disabled', false)
                            .prop('required', true)
                            .trigger('refresh');
                        $(elem).find('select')
                            .prop('disabled', false)
                            .prop('required', true)
                            .selectpicker('refresh');
                    } else {
                        $(elem).slideUp(300).find('input, textarea')
                            .prop('disabled', true)
                            .prop('required', false)
                            .trigger('refresh');
                        $(elem).find('select')
                            .prop('disabled', true)
                            .prop('required', false)
                            .selectpicker('refresh');
                    }
                }
            });
        } else {
            $('.form-group[data-parent="' + question[0] + '"]').each(function (index, elem) {
                if ($(elem).data('show') > '') {
                    var regExp = new RegExp(q_id, 'i');
                    if ($(elem).data('show').match(regExp)) {
                        $(elem).slideUp(300).find('input, textarea')
                            .prop('disabled', true)
                            .prop('required', false)
                            .trigger('refresh');
                        $(elem).find('select')
                            .prop('disabled', true)
                            .prop('required', false)
                            .selectpicker('refresh');
                    }
                }
            });
        }
    });

});