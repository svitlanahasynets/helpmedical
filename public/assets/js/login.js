var bgs = [];
for (var i = 1; i <= 3; i++) {
	bgs.push('/assets/img/login/' + i + '.jpg');
}
$.backstretch(bgs, {
	fade: 1000,
	duration: 8000
});

$(document).ready(function(){
	$('.login-form').validate({
	    errorElement: 'span', //default input error message container
	    errorClass: 'help-block', // default input error message class
	    focusInvalid: false, // do not focus the last invalid input
	    rules: {
	        username: {
	            required: true
	        },
	        password: {
	            required: true
	        },
	        remember: {
	            required: false
	        }
	    },

	    messages: {
	        username: {
	            required: "사용자 아이디를 입력하십시오."
	        },
	        password: {
	            required: "암호를 입력하십시오."
	        }
	    },

	    highlight: function (element) {
	        $(element)
	            .closest('.form-group').addClass('has-error');
	    },

	    success: function (label) {
	        label.closest('.form-group').removeClass('has-error');
	        label.remove();
	    },

	    errorPlacement: function (error, element) {
	        error.insertAfter(element.closest('.input-icon'));
	    },

	    submitHandler: function (form) {
	        hideError();

	        Metronic.blockUI({
              	target: $('.login-container .main-box'),
             	iconOnly: true
        	});

        	$(form).ajaxSubmit({
				dataType: 'json',
	        	success: function(data, status, xhr) {
	        		if (data.ok) {
	        			document.location.href = data.url;
	        		} else {
	        			Metronic.unblockUI($('.login-container .main-box'));
	        			showError(data.error);
	        		}
	        	},
	        	error: function(xhr, status, error) {
	        		Metronic.unblockUI($('.login-container .main-box'));
	        		showError(xhr.responseJSON.username);
	        	}
			});
	    }
	});

	var showError = function(msg) {
		$('.alert span').html(msg);
		$('.alert').show();
	}

	var hideError = function(msg) {
		$('.alert').hide();
	}
});
