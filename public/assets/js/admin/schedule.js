$(document).ready(function(){

    var fn = {

        initElements: function() {

            this.$container     = $('div.container');
            this.$form          = $('form#new_competition_form', this.$container);
            this.$table         = $('table#event_editable_1', this.$form);
            this.$addEventTable        = $('table#additional_events', this.$form);
            this.$createNewCompButton  = $('button#create_new_competition_button', this.$form);
            this.$createTeamButton     = $('button#create_team_button', this.$form);
            this.$modal = $('#edit_schedule_modal');
        },

        handleDaterangepicker: function () {
            var self = this;
            $('#reportrange').daterangepicker({
                    opens: (Metronic.isRTL() ? 'left' : 'right'),
                    startDate: moment(),
                    endDate: moment().subtract(-29, 'days'),
                    minDate: '01/01/2018',
                    maxDate: '12/31/2024',
                    dateLimit: {
                        days: 30
                    },
                    showDropdowns: true,
                    showWeekNumbers: true,
                    timePicker: false,
                    timePickerIncrement: 1,
                    timePicker12Hour: true,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    buttonClasses: ['btn'],
                    applyClass: 'green',
                    cancelClass: 'default',
                    format: 'MM/DD/YYYY',
                    separator: ' to ',
                    locale: {
                        applyLabel: 'Apply',
                        fromLabel: 'From',
                        toLabel: 'To',
                        customRangeLabel: 'Custom Range',
                        daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                        monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                        firstDay: 1
                    }
                },
                function (start, end) {
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    $('#start_date', self.$form).val(start.format('YYYY-MM-DD'));
                    $('#end_date', self.$form).val(end.format('YYYY-MM-DD'));
                }
            );

            //Set the initial state of the picker label
            $('#reportrange span').html(moment().format('MMMM D, YYYY') + ' - ' + moment().subtract(-29, 'days').format('MMMM D, YYYY'));
            $('#start_date', self.$form).val(moment().format('YYYY-MM-DD'));
            $('#end_date', self.$form).val(moment().subtract(-29, 'days').format('YYYY-MM-DD'));
        },

        changeTableRowClass: function($this) {
             var checked = $this.is(':checked');
            // if checked, change background color of row
            if (checked)
                $this.closest('tr').addClass('selected');
            else
                $this.closest('tr').removeClass('selected');
        },

        handleCheckboxChange: function ($table) {
            var self = this;

            // Handler when changing checkbox on header
            $table.off('change', 'thead input[type="checkbox"]');
            $table.on('change', 'thead input[type="checkbox"]', function() {
                var checked = $(this).is(":checked");
                $('tbody input[type="checkbox"]:enabled', $table).prop('checked', checked);
                $('tbody input[type="checkbox"]:enabled', $table).trigger('change');                

                $.uniform.update($('tbody input[type="checkbox"]', $table));
            });

            // Handler when changing checkbox on each row
            $table.off('change', 'tbody input[type="checkbox"]');
            $table.on('change', 'tbody input[type="checkbox"]', function(e) {
                var checked = $(this).is(':checked');
                var all_checked = false;
                $('tbody input[type="checkbox"]:enabled', $table).each(function() {
                    if (!$(this).is(":checked")) {
                        all_checked = false;
                        return false;
                    }

                    all_checked = true;
                });

                $('thead input[type="checkbox"]', $table).prop('checked', all_checked);
                $.uniform.update($('table.table thead input[type="checkbox"]'));
                
                self.changeTableRowClass($(this));                
            });
        },

        handleUniform: function () {
            if (!$().uniform) {
                return;
            }
            var test = $("input[type=checkbox]:not(.toggle, .make-switch), input[type=radio]:not(.toggle, .star, .make-switch)");
            if (test.size() > 0) {
                test.each(function () {
                    if ($(this).parents(".checker").size() == 0) {
                        $(this).show();
                        $(this).uniform();
                    }
                });
            }
        },

        handleSubmit: function () {
            var self = this;

            var message = '<span class="help-block help-block-error">입력자료가 충분치 못합니다.</span>';

            // create new competition
            self.$createNewCompButton.on('click', function () {

                var error_flag = 0;

                // form validation start
                self.$form.find('.has-error').removeClass('has-error');
                self.$form.find('.help-block').remove();
                
                if ( $('input#competition_name').val().trim() == '' ) {
                   $('input#competition_name').closest('div.form-group').addClass('has-error');
                   error_flag = 1; 
                }

                if ( $('tbody input[type="checkbox"]:checked', self.$table).length == 0 ) {
                    self.$table.closest('div.form-group').addClass('has-error');
                    error_flag = 1;
                } 

                $('tr.add-event', self.addEventTable).each(function() {
                    $('input[type="text"]', $(this)).each(function() {
                        if ( $(this).val() == '' ) {
                            self.$addEventTable.closest('div.form-group').addClass('has-error');
                            error_flag = 1;
                        }
                    });
                });

                if ( error_flag == 1 ) {
                    $('.alert-comp-message').closest('div.form-group').addClass('has-error');
                    $('.alert-comp-message').html(message);
                    return false;
                }
                // form validation end

                Metronic.blockUI({
                    target: self.$container,
                    iconOnly: true
                });

                $('input#current_step', self.$form).val('create_new_competition');

                // form ajaxsubmit
                self.$form.ajaxSubmit({
                    dataType: 'json',
                    success: function(data, status, xhr) {
                        Metronic.unblockUI(self.$container);
                        if ( data.success ) {
                            $current_step = $('.stepper li.current');
                            $current_step.removeClass('current');
                            $current_step.next().trigger('click');

                            $('input#competition_id', self.$form).val(data.id);

                        } else {
                            if ( data.message ) {
                                toastr["error"](data.message);
                            } else {
                                toastr["error"](' 오유입니다 ! ');
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        Metronic.unblockUI(self.$container);
                        toastr["error"]('관리자체계와 접속할수 없습니다. <br/>망 접속을 확인하여주십시오.');
                    }
                });
            });

            self.$createTeamButton.on('click', function () {

                // form validation
                self.$form.find('.has-error').removeClass('has-error');
                self.$form.find('.help-block').remove();

                var validate_failure = false;

                $('.team-name').each(function() {

                    if ( $(this).val() == '' ) {
                        validate_failure = true;
                    }
                });

                if ( $('#team_setting_mode').val() == '' || $('#team_count').val() == '' || validate_failure || $('li.snippet', $('.team-block-body')).length == 0 ) {

                    $('.alert-team-message').closest('div.form-group').addClass('has-error');
                    $('.alert-team-message').html(message);
                    return false;
                }

                Metronic.blockUI({
                    target: self.$container,
                    iconOnly: true
                });

                $('input#current_step', self.$form).val('create_team');

                // form ajaxsubmit
                self.$form.ajaxSubmit({
                    dataType: 'json',
                    success: function(data, status, xhr) {
                        Metronic.unblockUI(self.$container);
                        if ( data.success ) {
                            $current_step = $('.stepper li.current');
                            $current_step.removeClass('current');
                            $current_step.next().trigger('click');

                            var event_content = new Array();

                            self.initCalendar(event_content);

                            $('input#competition_id', self.$form).val(data.id);

                        } else {
                            if ( data.message ) {
                                toastr["error"](data.message);
                            } else {
                                toastr["error"](' 오유입니다 ! ');
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        Metronic.unblockUI(self.$container);
                        toastr["error"]('관리자체계와 접속할수 없습니다. <br/>망 접속을 확인하여주십시오.');
                    }
                });

            });
        },

        handleDragDrop: function () {

            var self =  this;

            var $team_block_body = $('div.team-block-body', self.$form);

            // 
            function calculateSnippetIds($target) {

                var current_snippet_ids_str = '';

                $('li', $target).each(function() {
                    if ( current_snippet_ids_str == '' ) {
                        current_snippet_ids_str = $(this).data('sid');
                    } else {
                        current_snippet_ids_str += ',' + $(this).data('sid');
                    }
                });

                $target.find('input.snippet-ids').val(current_snippet_ids_str);

            }

            // calculate member counts
            function calculateSnippetCounts($target) {

                var current_snippet_counts = 0;

                $('li', $target).each(function() {
                    current_snippet_counts += $(this).data('count');
                });

                $target.closest('.block').find('label.team-footer-value').text(current_snippet_counts);

            }

            function dropSnippetToBlock($target, $item) {

                var recycle_icon = '&nbsp;&nbsp;<a href="javascript:;" title="제거" class="remove"><i class="fa fa-times"></i></a>';
                $item.fadeOut(function() {
                    var $list = $('ul',$target).length ? $('ul',$target) : $('<ul class="snippet-wrapper"/>').appendTo($target);

                    $item.append(recycle_icon).appendTo($list).fadeIn(function() {
                        $item.animate({ fontSize: "10px", });
                        calculateSnippetIds($target);
                        calculateSnippetCounts($target);
                    });
                });
            }  

            function recycleSnippet($item) {
                var $target = $item.closest('div.team-block-body');
                $item.fadeOut(function() {
                    $item.find('a.remove').remove();
                    $item.animate({ fontSize: "12px", }).appendTo($('ul.snippet-ul')).fadeIn();
                    calculateSnippetIds($target);
                    calculateSnippetCounts($target);
                });
            }

            // let the snippet items be draggable
            $('li', $('ul.snippet-ul')).draggable({
                cancel: 'a.ui-icon',// clicking an icon won't initiate dragging
                revert: 'invalid', // when not dropped, the item will revert back to its initial position
                helper: 'clone',
                cursor: 'move'
            });
            

            // let the team-block be droppable, accepting the gallery items
            $('.team-block-body').droppable({
                accept: '.snippet-ul > li',
                activeClass: 'ui-state-hover',
                hoverClass: 'ui-state-active',
                drop: function(e, ui) {
                    var $target = $(e.target);
                    dropSnippetToBlock($target, ui.draggable);
                }
            });

            // let the team-block be droppable, accepting the gallery items
            $('ul.snippet-ul').droppable({
                accept: '.snippet-wrapper > li',
                drop: function(e, ui) {
                    var $target = $(e.target);
                    recycleSnippet(ui.draggable);
                }
            });

            $('.team-block').on('click', 'a.remove', function (ev) {
                $item = $(this).closest('.snippet');
                $team_block_body = $(this).closest('div.team-block-body');
                var old_snippet_ids_str = $team_block_body.find('input.snippet-ids').val();
                var old_snippet_ids_array = old_snippet_ids_str.split(',');


                recycleSnippet($item);
                return false;
            });
        },

        handleEditSchedule: function () {

            var self =  this;
            $('body').off('click', '.fc-day-number');
            $('body').on('click', '.fc-day-number', function(e){
                var date = $(this).closest('td.fc-day').data('date');
                $('button.proxy').data('date', date);
                $('button.proxy').trigger('click');
            });

            self.$modal.unbind('show.bs.modal').on('show.bs.modal', function (e) {
                var $btn = $(e.relatedTarget);
                var date = $btn.data('date');

                $('button.edit-modal-save').data('date', date);

                var token = self.$container.data('token');

                var data = {
                    action: 'edit_schedule',
                    competition_id: $('input#competition_id', self.$form).val(),
                    date: date,
                    _token: token
                };

                data.dataType = 'json';

                $.post(self.$form.attr('action'), data, function (json) { 
                
                    if ( json.success ) {

                        var date_array = json.date.split('-');
                        var text = date_array[0] + '년 ' + date_array[1] + '월 ' + date_array[2] + '일 경기일정';
                        $('.schedule-name').find('label').text(text);

                        var html = '';
                        $('#schedule_edit_table').find('tbody').html(html);

                        if (json.content.length > 0) {

                            for(var i=0; i<json.content.length; i++)
                            {
                                html += '<tr class="schedule-tr" data-schedule="' + json.content[i].schedule_id + '" data-date="' + json.content[i].game_date + '" data-event="' + json.content[i].event_id + '" data-team1="' + json.content[i].team1_id + '" data-team2="' + json.content[i].team2_id + '">';
                                html += '<td align="center" class="schedule-event">' + json.content[i].event_name + '</td><td align="center" class="schedule-team1">';
                                html += json.content[i].team1_name + '</td><td align="center" class="schedule-team2">';
                                html += json.content[i].team2_name + '</td><td align="center" class="schedule-action"><button type="button" class="schedule-remove">경기삭제</button></td></tr>';
                            }

                        } else {

                            html = '<tr class="odd gradeX"><td colspan="4" align="center">계획된 경기가 없습니다.</td></tr>';
                        }

                        $('#schedule_edit_table').find('tbody').html(html);

                        var addhtml = '';
                        $('#schedule_addition_table').find('tbody').html(addhtml);

                        if (json.addition.length > 0) {

                            for(var i=0; i<json.addition.length; i++)
                            {
                                addhtml += '<tr class="schedule-add" data-schedule="' + json.addition[i].schedule_id + '" data-date="' + json.addition[i].game_date + '" data-event="' + json.addition[i].event_id + '" data-team1="' + json.addition[i].team1_id + '" data-team2="' + json.addition[i].team2_id + '">';
                                addhtml += '<td align="center" class="schedule-event">' + json.addition[i].event_name + '</td><td align="center" class="schedule-team1">';
                                addhtml += json.addition[i].team1_name + '</td><td align="center" class="schedule-team2">';
                                addhtml += json.addition[i].team2_name + '</td><td align="center" class="schedule-action"><button type="button" class="schedule-add">경기추가</button></td></tr>';
                            }

                        } else {

                            addhtml = '<tr class="odd gradeX"><td colspan="4" align="center">추가할수 있는 경기가 없습니다.</td></tr>';
                        } 

                        $('#schedule_addition_table').find('tbody').html(addhtml); 
                        
                    } else {
                        toastr["error"](' Ajax 오유입니다 ! ');
                    }
                });

            });

        },

        modalAjaxHandler: function () {

            var self = this;

            // tr remove button click handler
            $('body').on('click', 'button.schedule-remove', function(e) {
                $tr = $(e.target).closest('tr.schedule-tr');
                $addtr = $tr;
                $addtr.removeClass('schedule-tr').addClass('schedule-add');
                $tr.remove();
                $('#schedule_addition_table').find('tr.gradeX').remove();
                $addtr.find('button').removeClass('schedule-remove').addClass('schedule-add');
                $addtr.find('button').text('경기추가');
                $('#schedule_addition_table').find('tbody').append($addtr);
            });

            // tr add button click handler
            $('body').on('click', 'button.schedule-add', function(e) {
                $tr = $(e.target).closest('tr.schedule-add');
                $addtr = $tr;
                $addtr.removeClass('schedule-add').addClass('schedule-tr');
                $tr.remove();
                if ( $('#schedule_edit_table').find('tr.gradeX').length != 0 ) {
                   $('#schedule_edit_table').find('tr.gradeX').remove(); 
                }
                $addtr.find('button').removeClass('schedule-add').addClass('schedule-remove');
                $addtr.find('button').text('경기삭제');
                $('#schedule_edit_table').find('tbody').append($addtr);
            });

            $('body').on('click', 'button.edit-modal-save', function(e) {

                var schedule_ids = '';
                var date = $('button.edit-modal-save').data('date');

                if ( $('tr.schedule-tr', self.$modal).length > 0 ) {

                    $('tr.schedule-tr', self.$modal).each(function() {
                        if ( schedule_ids == '' ) {
                            schedule_ids = $(this).data('schedule');
                        } else {
                            schedule_ids += ',' + $(this).data('schedule');
                        }
                    });

                } else {
                    schedule_ids == '';
                }

                var token = self.$container.data('token');

                var data = {
                    action: 'get_updated_schedule',
                    competition_id: $('input#competition_id', self.$form).val(),
                    schedule_ids: schedule_ids,
                    date: date,
                    _token: token
                };

                data.dataType = 'json';

                $.post(self.$form.attr('action'), data, function (json) { 
                
                    if ( json.success ) {

                        var event_content = new Array();
                        var dt = new Date();
                        var y = dt.getFullYear();
                        var m = dt.getMonth();
                        var d = dt.getDate();

                        for(var i=0; i<json.content.length; i++)
                        {
                            event_content.push({
                                title: json.content[i].title,
                                start: new Date(y-json.content[i].diff_year, m-json.content[i].diff_month, d-json.content[i].diff_day),
                                backgroundColor: Metronic.getBrandColor('blue')
                            });
                        }

                        self.initAjaxCalendar(event_content);
                        self.$modal.modal('hide');
                        
                    } else {
                        toastr["error"](' Ajax 오유입니다 ! ');
                    }

                });
                
            });

        },

        initCalendar: function (event_content) {
            if (!jQuery().fullCalendar) {
                return;
            }

            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();

            var h = {};

            if ($('#calendar').width() <= 400) {
                $('#calendar').addClass("mobile");
                h = {
                    left: 'title, prev, next',
                    center: '',
                    right: 'today,month,agendaWeek,agendaDay'
                };
            } else {
                $('#calendar').removeClass("mobile");
                if (Metronic.isRTL()) {
                    h = {
                        right: 'title',
                        center: '',
                        left: 'prev,next,today,month,agendaWeek,agendaDay'
                    };
                } else {
                    h = {
                        left: 'title',
                        center: '',
                        right: 'prev,next,today,month,agendaWeek,agendaDay'
                    };
                }
            }

            $('#calendar').fullCalendar('destroy'); // destroy the calendar
            $('#calendar').fullCalendar({ //re-initialize the calendar
                disableDragging: false,
                header: h,
                editable: false,
                events: [{
                    title: '종목1-팀1:팀2',
                    start: new Date(y, m, 1),
                    backgroundColor: Metronic.getBrandColor('yellow')
                }, 
                {
                    title: '종목2-팀3:팀4',
                    start: new Date(y, m, 1),
                    backgroundColor: Metronic.getBrandColor('blue')
                }, {
                    title: '종목3-팀2:팀4',
                    start: new Date(y, m, 1),
                    backgroundColor: Metronic.getBrandColor('red')
                },]
            });
        },

        initAjaxCalendar: function (event_content) {
            
            var self = this;

            if (!jQuery().fullCalendar) {
                return;
            }

            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();

            var h = {};

            if ($('#calendar').width() <= 400) {
                $('#calendar').addClass("mobile");
                h = {
                    left: 'title, prev, next',
                    center: '',
                    right: 'today,month,agendaWeek,agendaDay'
                };
            } else {
                $('#calendar').removeClass("mobile");
                if (Metronic.isRTL()) {
                    h = {
                        right: 'title',
                        center: '',
                        left: 'prev,next,today,month,agendaWeek,agendaDay'
                    };
                } else {
                    h = {
                        left: 'title',
                        center: '',
                        right: 'prev,next,today,month,agendaWeek,agendaDay'
                    };
                }
            }

            $('#calendar').fullCalendar('destroy'); // destroy the calendar
            $('#calendar').fullCalendar({ //re-initialize the calendar
                disableDragging: false,
                header: h,
                editable: false,
                events: event_content,
            });

            self.handleEditSchedule();
        },

        bindEvents: function() {

            var self =  this;

            $('#competition_name', self.$form).on('keydown', function(e) {
                if (e.ctrlKey && e.keyCode == 13) {
                    self.$createNewCompButton.trigger('click');
                }
            });

            // Handler when clicking event add button
            $('.addition').on('click', function() {
                $(this).siblings('table#additional_events').css('display', 'block');
                var html = '<tr class="add-event">';
                html += '<td align="center"><input type="text" class="form-control add-event-name" name="add_event_name[]" value="" /></td>';
                html += '<td align="center"><input type="text" class="form-control only-number add-event-weight" name="add_event_weight[]" value="" /></td>';
                html += '<td align="center"><input type="text" class="form-control only-number add-event-slalom" name="add_event_slalom[]" value="" /></td>';
                html += '<td align="center"><a href="javascript:;" class="btn default event-remove"><i class="fa fa-times"></i> 삭제</a></td>';
                html += '</tr>';
                $(this).closest('.form-group').find('tbody').append(html);
                self.handleUniform();
                self.$addEventTable.closest('div.form-group').removeClass('has-error');
            });

            // Handler when clicking event remove button
            $('body').on('click', '.event-remove', function() {
                $(this).closest('.add-event').remove();
                if ( $('tr.add-event', self.$addEventTable).length == 0 ) {
                    self.$addEventTable.css('display', 'none');
                    self.$addEventTable.closest('div.form-group').removeClass('has-error');
                }
            });

            // Handler when selecting team setting mode
            self.$form.on('change', 'select#team_setting_mode', function(e) {

                if ( $(this).val() == '' ) {

                    self.$form.find('.has-error').removeClass('has-error');
                    self.$form.find('.help-block').remove();

                    var alert = '<span class="help-block help-block-error">팀지정방식을 선택하십시오.</span>';

                    $('.team-setting-alert').closest('div.form-group').addClass('has-error');
                    $('.team-setting-alert').html(alert);
                    return false;
                }

                $('input#current_step', self.$form).val('create_team');

                // 초기화
                $('.team-snippet').html('');
                $('.team-block').html('');
                $('input#team_count', self.$form).val('');

                var token = self.$container.data('token');

                var data = {
                    competition_id: $('input#competition_id', self.$form).val(), 
                    action: 'require_snippet',
                    team_mode: $(this).val(),
                    _token: token
                };

                data.dataType = 'json';

                $.post(self.$form.attr('action'), data, function (json) { 
                
                    if ( json.success ) {

                        self.$form.find('.has-error').removeClass('has-error');
                        self.$form.find('.help-block').remove();

                        var snippet_str = json.snippet;
                        var snippet_array = snippet_str.split(',');

                        var html = '<div class="snippet-alert"> <i class="fa fa-warning"></i> 팀들을 끌어서 가져다 놓으십시오.</div>';

                        html += '<ul class="snippet-ul">';

                        // snippet making!
                        for ( var i = 0; i < snippet_array.length; i++ ) {
                            var snippet_array_el = snippet_array[i].split('-');
                            var snippet_name     = snippet_array_el[0];
                            var snippet_id       = snippet_array_el[1];
                            var snippet_count    = snippet_array_el[2];
                            html += '<li class="snippet" data-sid="' + snippet_id + '" data-count="' + snippet_count + '"> <i class="fa fa-star"></i> ' + snippet_name + '</li>';
                        }

                        html += '</ul>';

                        $('.team-snippet', self.$form).addClass('active');
                        $('.team-snippet', self.$form).html(html);

                        // self.handleDragDrop();
                        
                    } else {
                        toastr["error"](' Ajax 오유입니다 ! ');
                    }
                });
            });

            // Handler when typing only number
            self.$form.on('keydown', 'input.only-number', function(e) {
                if ( ( e.keyCode >= 48 && e.keyCode <= 57 ) || ( e.keyCode >= 96 && e.keyCode <= 105 ) || e.keyCode == 110 || e.keyCode == 190 || e.keyCode == 46 || e.keyCode == 8 ){
                    return true;
                }else{
                    e.preventDefault();
                }
            });

            self.$form.on('keyup', 'input#team_count', function(e) {

                var team_count = parseInt($(this).val());

                self.$form.find('.has-error').removeClass('has-error');
                self.$form.find('.help-block').remove();

                if ( team_count == '' || team_count > 6 || team_count < 2 ) {

                    var alert = '<span class="help-block help-block-error">팀개수를 정확히 지적하십시오.</span>';

                    $('.team-count-alert').closest('div.form-group').addClass('has-error');
                    $('.team-count-alert').html(alert);
                    return false;
                }

                $('input#current_step', self.$form).val('create_team');

                var space = Math.floor(12 / team_count);

                var html = '<div class="row">';

                for ( var i = 0; i < team_count; i++ ) {
                    html += '<div class="col-sm-' + space + '"><div id="team_' + i + '" class="block"><div class="team-block-header"><label class="team-name-label">팀이름 : </label> <input type="text" name="team_name_' + i + '" class="team-name" value="" /></div><div class="team-block-body"><input type="hidden" name="snippet_ids_' + i + '" class="snippet-ids" value=""></div><div class="team-block-footer">인원수 :<label class="team-footer-value"> </label></div></div></div>';
                }

                html += '</div>';

                $('.team-block', self.$form).html(html);

                self.handleDragDrop();

            });

            $('#auto_create_schedule').on('click', function () {

                Metronic.blockUI({
                    target: self.$container,
                    iconOnly: true
                });

                $('input#current_step', self.$form).val('auto_create_schedule');

                // form ajaxsubmit
                self.$form.ajaxSubmit({
                    dataType: 'json',
                    success: function(json, status, xhr) {
                        Metronic.unblockUI(self.$container);
                        if ( json.success ) {

                            var event_content = new Array();
                            var dt = new Date();
                            var y = dt.getFullYear();
                            var m = dt.getMonth();
                            var d = dt.getDate();

                            for(var i=0; i<json.content.length; i++)
                            {
                                event_content.push({
                                    title: json.content[i].title,
                                    start: new Date(y-json.content[i].diff_year, m-json.content[i].diff_month, d-json.content[i].diff_day),
                                    backgroundColor: Metronic.getBrandColor('blue')
                                });
                            }

                            self.initAjaxCalendar(event_content);

                        } else {
                            toastr["error"](' 오유입니다 ! ');
                        }
                    },
                    error: function(xhr, status, error) {
                        Metronic.unblockUI(self.$container);
                        toastr["error"]('관리자체계와 접속할수 없습니다. <br/>망 접속을 확인하여주십시오.');
                    }
                });

            });

            // schedule publish handler!
            $('button.btn-finish').on('click', function() {

                Metronic.blockUI({
                    target: self.$container,
                    iconOnly: true
                });

                var token = self.$container.data('token');
                var data = {
                    action: 'publish_schedule',
                    competition_id: $('input#competition_id', self.$form).val(),
                    _token: token
                };
                data.dataType = 'json';

                $.post(self.$form.attr('action'), data, function (json) { 
                    Metronic.unblockUI(self.$container);
                    if ( json.success ) {

                        toastr["success"](' 일정이 정확히 공개되였습니다! ');
                        var href = $('input#next_url', self.$container).val();
                        window.location.href = href;

                    } else {
                        toastr["error"](json.error);
                    }
                });
            });

        },

        render: function () {
            $('#team_setting_mode').select2();
        },

        init: function() {
            this.initElements();
            this.handleDaterangepicker();
            this.handleCheckboxChange(this.$table);
            this.handleUniform();
            this.handleSubmit();
            this.modalAjaxHandler();

            this.bindEvents();
            this.render();
        },
    };

    fn.init();

});