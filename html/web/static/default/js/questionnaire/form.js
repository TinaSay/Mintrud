/**
 * Created by 1 on 09.07.2017.
 */
jQuery(function ($) {
    var form = $('#questionnaire-form');
    if (form.length) {
        var sub_questions = $('.sub-question');

        function SubQuestion(questions) {
            var self = this;
            this.questions = [];
            questions.each(function (index, elem) {
                self.questions[$(elem).data('id')] = elem;
            });
            this.handleRadio = function (id, answerId) {
                if (this.has(id)) {
                    var elem = $(this.getQuestion(id));
                    var answerIds = elem.data('parent_answer_id');
                    if ($.inArray(answerId, answerIds) != -1) {
                        this.enabled(elem);
                    } else {
                        this.disabled(elem);
                    }
                }
            };
            this.handleCheckbox = function (id, answerId, check) {
                if (this.has(id)) {
                    var elem = $(this.getQuestion(id));
                    var answerIds = elem.data('parent_answer_id');
                    if ($.inArray(answerId, answerIds) != -1) {
                        var activeCheckbox = elem.prop('activeCheckbox') || [];
                        if (check === 'checked') {
                            activeCheckbox.push(answerId);
                            this.enabled(elem);
                        } else {
                            var index;
                            index = $.inArray(answerId, activeCheckbox);
                            activeCheckbox.splice(index, 1);
                            if (!activeCheckbox.length) {
                                this.disabled(elem);
                            }
                        }
                        elem.prop('activeCheckbox', activeCheckbox);
                    }
                }
            };
            this.has = function (id) {
                return !!this.questions[id];
            };
            this.getQuestion = function (id) {
                return this.questions[id];
            };
            this.enabled = function (elem) {
                elem.removeClass('hidden');
                elem.find('input, textarea, select').each(function (index, elem) {
                    var $elem = $(elem);
                    if (!$elem.is('[type=checkbox]')) {
                        $elem.prop('required', true);
                    }
                    $elem.prop('disabled', false);
                });
            };
            this.disabled = function (elem) {
                elem.addClass('hidden');
                elem.find('input, textarea, select').each(function (index, elem) {
                    var $elem = $(elem);
                    $elem.prop('required', false);
                    $elem.prop('disabled', true);
                });
            };
        }

        var subQuestion = new SubQuestion(sub_questions);
        var parent_questions = $('.parent-question');

        function ParentQustion(questions) {
            this.questions = questions;
            this.onRadios = function () {
                this.questions.each(function (index, div) {
                    var $div = $(div);
                    var childrenIds = $div.data('children_id');
                    if (childrenIds.length) {
                        var answer = $div.find('input[type=radio]');
                        answer.on('click', {'childrenIds': childrenIds}, function (e) {
                            var answerId = $(this).data('id');
                            var childrenIds = e.data.childrenIds;
                            for (var i = 0, id; id = childrenIds[i]; i++) {
                                subQuestion.handleRadio(id, answerId);
                            }
                        });
                    }
                })
            };
            this.onCheckboxes = function () {
                this.questions.each(function (index, div) {
                    var $div = $(div);
                    var childrenIds = $div.data('children_id');
                    if (childrenIds.length) {
                        var answer = $div.find('input[type=checkbox]');
                        answer.on('click', {'childrenIds': childrenIds}, function (e) {
                            var $this = $(this);
                            var answerId = $(this).data('id');
                            var childrenIds = e.data.childrenIds;
                            for (var i = 0, id; id = childrenIds[i]; i++) {
                                if ($this.is(':checked')) {
                                    subQuestion.handleCheckbox(id, answerId, 'checked');
                                } else {
                                    subQuestion.handleCheckbox(id, answerId, 'unchecked');
                                }
                            }
                        });
                    }
                })
            }
        }

        var parentQuestion = new ParentQustion(parent_questions);
        parentQuestion.onRadios();
        parentQuestion.onCheckboxes();
    }
});