(function ($) {
    $.Redactor.prototype.codecleanup = function () {
        return {
            init: function () {
                var button = this.button.add('codecleanup', 'Очистить код от HTML');
                this.button.addCallback(button, this.codecleanup.clear);
                this.button.setIcon(button, '<i class="fa fa-eraser"></i>');
            },
            clear: function () {
                this.inline.removeFormat();
                this.selection.remove();
            }
        };
    };
})(jQuery);
