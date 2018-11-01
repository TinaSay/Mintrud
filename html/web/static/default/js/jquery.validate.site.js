$(document).ready(function() {

	// общие действия при проверке
	jQuery.validator.setDefaults({
	  // добавляем класс к обертке у элементов формы при валидации
	  highlight: function(element, errorClass, validClass) {
	    $(element).closest(".form-group")
	    .removeClass(validClass)
	    .addClass(errorClass);
	  },
	  unhighlight: function(element, errorClass, validClass) {
	    $(element).closest(".form-group")
	    .removeClass(errorClass)
	    .addClass(validClass);
	  },
	  // выводим сообщение в шапку формы о наличии ошибок
	  invalidHandler: function(event, validator) {
	    // 'this' refers to the form
	    var errors = validator.numberOfInvalids();
	    if (errors) {
	      var message = 'Проверьте, пожалуйста, правильность заполнения формы.';
	      $(this).find("div.error-message").html(message);
	      $(this).find("div.error-message").show();
	    } else {
	      $(this).find("div.error-message").hide();
	    }
	  }
	});

	// password show
	$('.password-show').click(function(){
		var $this = $(this),
			element = $this.next('input');
		if(element.prop('type') === 'password') {
			element.prop('type', 'text');
			$this.addClass('show');
		}
		else {
			element.prop('type', 'password');
			$this.removeClass('show');
		}
	});

	$('.form-defoult-validate').validate();

    // валидация формы регистрации
    $("#formRegistration").validate({
        rules: {
            mail: {
                is_email: true
            }
        }
    });

    $('#formRecoveryPass').validate({
		rules: {
			password: {
				required: true,
				minlength: 8
			},
			password_again: {
				equalTo: "#password"
			}
		},
		messages: {
			password_again: {
				equalTo: "Значение не совпадает"
			}
		}
	});

    $('#formConfirmEmail').validate({
	  submitHandler: function(form) {
	    $(form).ajaxSubmit({
		       url:"lk-user_registration.php",
		       type:"POST",
		    success: function(){
		        // при успешной отправке
		        alert(1);
		    }
		});
	  }
	});
















/*




    // доделаю попозже
	var answers = {
		items: [],
		values: {},
		count: 0,
		amount: 0,
		code: '',
		init: function (amount) {
			var _class = this;

			this.amount = amount;
			this.container = $('[data-action~="confirm-email"]');

			this.container.find('.confirm-email__elem').each(function(){
				_class.items.push($(this));
			});

			this.items[0].find('input[type="text"]').trigger('focus');
			this.container
				.on('input', '[data-id]', function (e) {
					var $input = $(this),
						value = $input.val(),
						id = $input.data('id'),
						prev = $input.data('value');

					if (value) {
						var number = parseInt(value);

						if (!number || number > _class.amount || number < 1) {
							$input.val('');
							_class.resetInput($input, prev);
						} else {
							_class.setInput($input, id, number);
						}
					} else {
						_class.resetInput($input, prev);
					}
				});
		},
		setInput: function ($input, id, number) {
			this.count++;
			this.values['a' + number] = true;
			$input.data('value', number).addClass('done');

			if (this.count == this.amount) {
				this.done($input);
			} else {
				this.triggerInput(id);

				$input.data('value', )
			}
		},
		done: function ($input, id, number) {
			$input.trigger('blur');
			this.container
			.addClass('done')
			.find('.confirm-email__elem input').prop('disabled', true);
			this.container.find('input[type="text"]').each(function(el, i){
				code =+ $(this).val();
			});
		},
		triggerInput: function (id) {
			var next = this.findInput(id, this.amount);

			if (!next) {
				this.findInput (0, id - 1);
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
				delete this.values['a' + prev];
				this.count--;
				$input.removeData('value').removeClass('done');
			}
		}
	};

	$(function () {
		answers.init(4);
	});

*/




});