$(document).ready(function(){

	var fn = {

    	initElements: function() {

            this.$container     = $('div#create_employee_container');
            this.$form          = $('form#create_employee_form', this.$container);
        },

    	handleDatePickers: function() {
    		if (jQuery().datepicker) {
	            $('.date-picker').datepicker({
	                rtl: Metronic.isRTL(),
	                orientation: "left",
	                autoclose: true
	            });
	        }
    	},

    	handleDatatables: function() {
    		var table = $('#employee_list_table');

	        // begin first table
	        table.dataTable({
	            "columns": [{
	                "orderable": false
	            }, {
	                "orderable": true
	            }, {
	                "orderable": false
	            }, {
	                "orderable": true
	            }, {
	                "orderable": true
	            }, {
	                "orderable": true
	            }],
	            "lengthMenu": [
	                [5, 15, 20, -1],
	                [5, 15, 20, "All"] // change per page values here
	            ],
	            // set the initial value
	            "pageLength": 5,            
	            "pagingType": "bootstrap_full_number",
	            "language": {
	                "lengthMenu": "  _MENU_ records",
	                "paginate": {
	                    "previous":"Prev",
	                    "next": "Next",
	                    "last": "Last",
	                    "first": "First"
	                }
	            },
	            "columnDefs": [{  // set default column settings
	                'orderable': false,
	                'targets': [0]
	            }, {
	                "searchable": false,
	                "targets": [0]
	            }],
	            "order": [
	                [0, "asc"]
	            ] // set first column as a default sort by asc
	        });

	        var tableWrapper = jQuery('#employee_list_table_wrapper');

	        tableWrapper.find('.dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown
    	},

    	skillInitSelection: function (element, callback) {
			var ids = $(element).val();
			if ( ids != '' ) {
				$.ajax('/admin/employee/search_skills', {data: {ids:ids}, dataType: 'json'}).done(function (data) {
					callback(data);
				});
			}
		},

		skillFormatResult: function(skill) {
			return skill.title;
		}, 

		skillFormatSelection: function(skill) {
			return skill.title;
		},

        bindEvents: function() {

            var self =  this;

            $('#belong').on('change', function() {
                var belong_value = $(this).val();
                var html = '<option value="">---</option>';
                if ( belong_value == '로동당' ) {
                	for (var i = 1; i <= 2; i++) {
                		html += '<option value="' + i + '">' + i + '세포</option>';
                	}
                } else if (  belong_value == '직맹' ) {
                	for (var i = 1; i <= 2; i++) {
                		html += '<option value="' + i + '">' + i + '초급단체</option>';
                	}
                } else if (  belong_value == '청년동맹' ) {
                	for (var i = 1; i <= 9; i++) {
                		html += '<option value="' + i + '">' + i + '초급단체</option>';
                	}
                }
                $('#community').html(html);
            });

        },

        render: function () {
        	var self =  this;

        	self.$form.validate({
		        errorElement: 'span', 
		        errorClass: 'help-block help-block-error', 
		        focusInvalid: false, 
		        rules: {
		        	name: {
		                required: true
		            },
		            username: {
		                required: true
		            },
		            sex: {
		                required: true
		            },
		            zone: {                
		                required: true
		            },
		            birthday: {                
		                required: true
		            },
		            room: {                
		                required: true
		            },
		            belong: {                
		                required: true
		            },
		            community: {                
		                required: true
		            },
		            role: {                
		                required: true
		            },
		        },

		        messages: {


		        	name: {
		                required: "이름을 입력하십시오."
		            },
		            username: {
		                required: "사용자 아이디를 입력하십시오.",
		            },
		            sex: {
		                required: "성별을 입력하십시오."
		            },
		            zone: {                
		                required: "사는곳을 입력하십시오."
		            },
		            birthday: {                
		                required: "생년월일을 입력하십시오."
		            },
		            room: {                
		                required: "실을 입력하십시오."
		            },
		            belong: {                
		                required: "소속을 입력하십시오."
		            },
		            community: {                
		                required: "단체을 입력하십시오."
		            },
		            role: {                
		                required: "권한을 입력하십시오."
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

		    $('#special_event').select2({
				placeholder: '특기종목을 입력하십시오.',
				minimumInputLength: 1,
				tags: true,
				ajax: {
					url: '/admin/employee/search_skills',
					dataType: 'json',
					data: function (term, page) {
						return {
							q: term
						};
					},
					results: function (data, page) {
						return {
							results: data
						};
					}
				},
				initSelection: self.skillInitSelection,
				formatResult: self.skillFormatResult,
				formatSelection: self.skillFormatSelection,
				dropdownCssClass: 'bigdrop',
				escapeMarkup: function (m) {
					return m;
				}
			});
            
        },

        init: function() {
            this.initElements();
            this.handleDatePickers();
            this.handleDatatables();

            this.bindEvents();
            this.render();
        },

    };

    fn.init();
    

});