/**
 * Created by krok on 10.07.17.
 */

var validCode = {
    items: [],
    values: {},
    count: 0,
    amount: 0,
    init: function () {
        var _class = this;
        this.amount = 4;
        this.container = jQuery('[data-action~="confirm-email"]');
        this.container.find('.confirm-email__elem').each(function () {
            _class.items.push(jQuery(this));
        });
        this.items[0].find('input[type="text"]').trigger('focus');
        this.container.on('input', '[data-id]', function () {
            var $input = jQuery(this),
                value = $input.val(),
                id = $input.data('id'),
                prev = $input.data('value');

            if (value) {
                var number = parseInt(value);
                if (isNaN(number) || number > 9 && number < 0) {
                    $input.val('');
                    _class.resetInput($input, prev);
                } else {
                    _class.setInput($input, id, number);
                }
            } else {
                _class.resetInput($input, prev);
            }
        });
        this.container.on('paste', 'input', '[data-id]', function (e) {
            var $input = jQuery(this),
                pasteData = e.originalEvent.clipboardData.getData('text');
            var i = 0, j = $input.data('id');
            // перебор вставленных символов
            for (i; i < pasteData.length; i++) {
                if (!(isNaN(pasteData[i]) || pasteData[i] > 9 && pasteData[i] < 0)) {
                    // записываем
                    jQuery('.confirm-email__elem input[data-id="' + j + '"]').val(pasteData[i]);
                    j++;
                }
            }
            jQuery('.confirm-email__elem input[type="text"]').last().focus();
        });
    },
    setInput: function ($input, id, number) {
        this.count = $input.data('id');
        $input.data('value', number).addClass('done');

        if (this.count === this.amount) {
            this.done($input);
        } else {
            this.triggerInput(id);
            $input.data('value', '');
        }
    },
    done: function ($input) {
        code = '';
        $input.trigger('blur');
        this.container.find('input[type="text"]').each(function () {
            code += String(jQuery(this).val());
        });
        jQuery('#verifycodeform-code').val(code).trigger('blur');
    },
    triggerInput: function (id) {
        var next = this.findInput(id, this.amount);
        if (!next) {
            this.findInput(0, id - 1);
        }
    },
    findInput: function (start, end) {
        for (var i = start; i < end; i++) {
            if (!this.items[i].data('value')) {
                this.items[i].find('input[type="text"]').trigger('focus');
                return i;
            }
        }
    },
    resetInput: function ($input, prev) {
        if (prev) {
            this.count--;
            $input.removeData('value').removeClass('done');
        }
    },
    reset: function () {
        this.items[0].find('input[type="text"]').trigger('focus');
        jQuery('.confirm-email__elem input').removeData('value').removeClass('done').val('');
    }
};

var code;
containerResultText = jQuery('.confirm-email__result');

validCode.init();

jQuery("#retry-verify-codes-button").on("ajax.beforeSend", function () {
    validCode.reset();
});
