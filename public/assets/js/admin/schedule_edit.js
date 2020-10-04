$(document).ready(function(){

    var fn = {

    	initElements: function() {

            this.$container     = $('div.container');
            this.$form          = $('form#edit_schedule_form', this.$container);
            this.$modal         = $('div#edit_schedule_modal');
        },

        initCalendar: function (event_content) {
            
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
                year: start_year,
                month: start_month,
                day: start_day,
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

            // $('.fc-day-number').on('click', function(e){
            //     var date = $(this).closest('td.fc-day').data('date');
            //     $('button.proxy').data('date', date);
            //     $('button.proxy').trigger('click');
            // });

            $('#edit_schedule_modal').unbind('show.bs.modal').on('show.bs.modal', function (e) {
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

                if ( $('tr.schedule-tr', $('#edit_schedule_modal')).length > 0 ) {

                    $('tr.schedule-tr', $('#edit_schedule_modal')).each(function() {
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

                        self.$modal.modal('hide');
                        self.initCalendar(event_content);
                        self.handleEditSchedule();
                        
                    } else {
                        Metronic.unblockUI(self.$container);
                        toastr["error"](' Ajax 오유입니다 ! ');
                    }
                });
                
            });
        },

        bindEvents: function() {
            var self =  this;

        },

        render: function () {
            $('select.select2').select2({
                allowClear: true,
                minimumResultsForSearch: -1
            });
        },

        init: function() {
            this.initElements();
            this.initCalendar(event_content);
            this.handleEditSchedule();
            this.modalAjaxHandler();

            this.bindEvents();
            this.render();
        },

    };

    fn.init();

});