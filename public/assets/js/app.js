$(document).ready(function() {    
   Metronic.init(); // init metronic core componets
   Layout.init(); // init layout
   QuickSidebar.init(); // init quick sidebar

   	// $('.date-picker').datepicker({
    //     rtl: Metronic.isRTL(),
    //     orientation: "left",
    //     autoclose: true,
    //     todayHighlight: true,
    //     format: "yyyy-mm-dd"
    // });

    //$('.date-picker').val(moment().format('YYYY-MM-D'));

    $('select#owner_company_id').change(function(){
    	location = $(this).find('option:selected').data('url');
    });

    $('.desktop-currency .title').click(function(){
    	$('.desktop-currency').toggleClass('active');
    });

    $('#add-new-client').click(function(){
        $('#new-client-modal').modal();
    });

    var _new_client_addable = true;

    $('#create-client-form').validate({
        errorElement: 'span', 
        errorClass: 'help-block help-block-error', 
        focusInvalid: false, 
        rules: {
            name: {
                required: true
            },
            type: {
                required: true
            }
        },
        messages: {
            name: {
                required: "고객명을 입력하십시오."
            },
            type: {
                required: "고객형태를 선택하십시오."
            }
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
        submitHandler: function (form) {
            if (_new_client_addable) {
                _new_client_addable = false;
            } else {
                return false;
            }

            Metronic.blockUI({
                target: $('#create-client-form .modal-content'),
                iconOnly: true
            });

            var added_value = $(form).find('#name').val();

            $.ajax({
                type: 'POST',
                url: $(form).attr('action'),
                data: $(form).serialize(),
                dataType: 'JSON',
                success: function(res) {
                    $('#client_id option').remove();
                    $('#client_id').append('<option value="1">---</option>');
                    $(res).each(function(i, client) {
                        var selected = (added_value == client['name']) ? ('selected') : ('');
                        $('#client_id').append('<option value="' + client['client_id'] + '" ' + selected + '>' + client['name'] + '</option>');
                    });
                    $('#create-client-form')[0].reset();
                    //$('#new-client-modal').modal('hide');
                    toastr['success']("새 고객이 성공적으로 추가되였습니다.", "성공");
                    _new_client_addable = true;
                    Metronic.unblockUI($('#create-client-form .modal-content'));
                    $('#new-client-modal').modal('hide');
                },
                error: function(error) {
                    toastr['error']("새 고객등록이 실패하였습니다.", "오유");
                    _new_client_addable = true;
                    Metronic.unblockUI($('#create-client-form .modal-content'));
                }
            });
        }
    });

});

$.validator.addMethod(
    "valid", 
    function(value, element) { 
        return $(element).data('valid');
    }, 
    "The value is not valid."
);

$.validator.addMethod(
    "validTicket", 
    function(value, element) { 
        var _tickets = $(element).val().split(/\n/g);        
        var _valid = true;
        if (_tickets.length == 0) 
        {
            return false;
        } else {            
            for (var i=0; i < _tickets.length; i++) {
                var _ticket = _tickets[i].split(",");
                if (_ticket.length != 2) {
                    _valid = false;
                    break;
                }
                if (!_ticket[0] || !_ticket[1] || isNaN(_ticket[1])) {
                    _valid = false;
                    break;
                }
            }
        }

        return _valid;
    }, 
    "The value is not valid."
);

$.validator.addMethod(
    "validTicketPrice", 
    function(value, element) { 
        var _tickets = $(element).val().split(/\n/g);        
        var _valid = true;
        if (_tickets.length == 0) 
        {
            return false;
        } else {            
            var _total = 0;
            for (var i=0; i < _tickets.length; i++) {
                var _ticket = _tickets[i].split(",");
                _total += parseFloat(_ticket[1]);
            }
            console.log(_total);
            if (_valid) {
                if ($('#new_payment_form').find('#grand_total_price_won').length > 0) {
                    _total_won = parseFloat($('#new_payment_form #grand_total_price_won').val());
                } else {
                    _total_won = parseFloat($('#new_payment_form #total_price_won').val());
                }
                if (_total != _total_won) {
                    _valid = false;
                }
            }
        }

        return _valid;
    }, 
    "The value is not valid."
);


function addTempRow(containerId, data, del_func) {
	var _container = $('#' + containerId);
	var _tbody = $(_container).find('tbody');
	var _count = $(_tbody).find('tr').length;
	var _rowIndex = ($(_container).data('index')) ? ($(_container).data('index') + 1) : (1);
	$(_container).data('index', _rowIndex);

	var html = '';
	html += '<tr>';
	html += '<td>' + (_count + 1) + '</td>';

	$.each(data, function(key, value) {
		if (value == undefined) return;
		if (typeof value == 'object') {
            if (typeof value['is_hidden'] == "undefined")
			    html += '<td>' + value['title'] + '<input type="hidden" class="row-' + key + '" name="data[' + _rowIndex + '][' + key + ']" value="' + value['value'] + '"/></td>';
		    else  
                html += '<input type="hidden" class="row-' + key + '" name="data[' + _rowIndex + '][' + key + ']" value="' + value['value'] + '"/>';
        } else {
			html += '<td>' + value + '<input type="hidden" class="row-' + key + '" name="data[' + _rowIndex + '][' + key + ']" value="' + value + '"/></td>';
		}
	});

    if (typeof(del_func) != "undefined" && del_func != '') {
        del_func += '();';
    } else {
        del_func = '';
    }

	html += '<td><a class="btn btn-sm red btn-delete" onclick="removeTempRow(this); ' + del_func + '"><i class="fa fa-trash-o"></i></a></td>';
	html += '</tr>';

	$(_tbody).append(html);
}

function removeTempRow(e) {
	$(e).parent().parent().remove();

	var _index = 1;
	$(e).parents('table').find('tbody tr').each(function(){
		$(this).find('td:eq(0)').html(_index);
		_index++;
	});

}