$(document).ready(function(){

	var fn = {

    	initElements: function() {

            this.$container     = $('div#user_profile_container');
            this.$form          = $('form#user_profile_form', this.$container);
        },

        bindEvents: function() {
            var self =  this;
            self.$form.on('submit', function() {
            	if ( self.$form.valid() ) {
            		Metronic.blockUI({
			            target: $(this).parent(),
			            iconOnly: true
			        });
            	} else {
            		return false;	// form submit동작이 진행되지 못하게 해준다.
            	}
		    });
        },

        render: function () {
        	var self =  this;
        	self.$form.validate({
		        errorElement: 'span', 
		        errorClass: 'help-block help-block-error', 
		        focusInvalid: false, 
		        rules: {
		        	username: {
		                required: true,
		                minlength: 2,
		            },
		            password: {
		                required: true,
		                minlength: 3,
		            },
		            password_confirmation: {
		                required: true,
		                minlength: 3,
		                equalTo: "#password"
		            },
		        },

		        messages: {
		        	username: {
		                required: "아이디를 입력하십시오.",
		                minlength: "아이디는 {0}글자이상 되여야 합니다.",
		            },
		            password: {
		                required: "암호를 입력하십시오.",
		                minlength: "암호는 {0}글자이상 되여야 합니다.",
		            },
		            password_confirmation: {
		                required: "확인암호를 입력하십시오.",
		                minlength: "암호는 {0}글자이상 되여야 합니다.",
		                equalTo: "암호가 일치하지 않습니다."
		            },
		        },
		        highlight: function (element) {
		            $(element)
		                .closest('.form-group').addClass('has-error'); 
		        },

		        unhighlight: function (element) { 
		            $(element)
		                .closest('.form-group').removeClass('has-error'); 
		        },

		        success: function (label) {
		            label
		                .closest('.form-group').removeClass('has-error'); 
		        },
		    });
        },

        init: function() {
            this.initElements();

            this.bindEvents();
            this.render();
        },

    };

    fn.init();
    

});