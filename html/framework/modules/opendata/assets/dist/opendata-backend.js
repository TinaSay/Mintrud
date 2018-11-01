$(document).ready(function () {

    $('.delete-property').on('click', function (e) {
        e.preventDefault();
        var $row = $(this).closest('.property');
        $row.addClass('hidden');
        $row.find('.delete').val('1');
        if ($('.property:visible').length <= 1) {
            $('.property .delete-property').addClass('hidden');
        }
    });

    $('.create-property').on('click', function (e) {
        e.preventDefault();
        var $row = $('.property-container .property:first').clone(true),
            rowIndex = $('.property-container .property').length;
        $row.find('.property-id').remove();

        $row.find('input').each(function (index, elem) {
            $(elem).val('');
            var name = $(elem).attr('name');
            name = name.replace(/\[([\d]+)\]/i, '[a' + rowIndex + ']');
            $(elem).attr('name', name);
        });
        $row.find('select').each(function (index, elem) {
            $(elem).find('option:selected').prop('selected', false);
            var name = $(elem).attr('name');
            name = name.replace(/\[([\d]+)\]/i, '[a' + rowIndex + ']');
            $(elem).attr('name', name);
            var $select = $(elem).clone();
            $(elem).closest('.bootstrap-select').replaceWith($select);
            $select.selectpicker();
        });

        $('.property-container').append($row);
        $row.find('select').selectpicker('refresh');
        if ($('.property:visible').length > 1) {
            $('.property .delete').removeClass('hidden');
        }
    });
});