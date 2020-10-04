$(document).ready(function(){

    var fn = {

    	initElements: function() {

            this.$container     = $('div.container');
            this.$form          = $('form#edit_result_form', this.$container);
            this.$modal         = $('div#resultModal');
            this.$modalform     = $('form#result_modal_form');
        },

        initCalendar: function () {
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

        modalAjaxHandler: function () {

            var self =  this;
            
            $('body').off('click', '.fc-day-number');
            $('body').on('click', '.fc-day-number', function(e){

                var date = $(this).closest('td.fc-day').data('date');

                $('button.proxy').data('date', date);
                $('button.proxy').trigger('click');

            });

            $('#resultModal').unbind('show.bs.modal').on('show.bs.modal', function (e) {
                var $btn = $(e.relatedTarget);
                var date = $btn.data('date');

                $('button.edit-modal-save').data('date', date);

                var token = self.$container.data('token');

                var data = {
                    action: 'edit_result',
                    competition_id: $('input#competition_id', self.$form).val(),
                    date: date,
                    _token: token
                };

                data.dataType = 'json';

                $.post(self.$form.attr('action'), data, function (json) { 
                
                    if ( json.success ) {

                        var date_array = json.date.split('-');
                        var text = date_array[0] + '년 ' + date_array[1] + '월 ' + date_array[2] + '일 경기결과편집';
                        $('.result-name').find('label').text(text);

                        var html = '';
                        $('#result_edit_table').find('tbody').html(html);

                        if (json.content.length > 0) {

                            for(var i=0; i<json.content.length; i++)
                            {
                                var already_desc = '';
                                var already_team1_score = '';
                                var already_team2_score = '';
                                var progress_class = '';
                                var already_files = '';                                                                    
                                if ( json.content[i].progress == 1 ) {
                                    already_desc = json.content[i].desc;
                                    already_team1_score = json.content[i].team1_score;
                                    already_team2_score = json.content[i].team2_score;
                                    progress_class = 'progressed';

                                    if ( json.content[i].file_ids != undefined && json.content[i].file_ids != '' ) {
                                        for (var j = 0; j < json.content[i].files.length; j++) {
                                            already_files += '<div class="file" data-id="' + json.content[i].files[j].id + '"><a class="link-delete"><i class="fa fa-trash-o"></i></a> <a href="/files/' + json.content[i].files[j].id + '/' + json.content[i].files[j].hash + '" target="_blank">' + json.content[i].files[j].name + '</a></div>';
                                        }
                                    }
                                }

                                var event_slalom_count = parseInt(json.content[i].event_slalom_count);
                                html += '<tr class="score-tr ' + progress_class + '" data-schedule-id="' + json.content[i].schedule_id + '"><td rowspan="2" align="center">' + json.content[i].event_name + '(' + event_slalom_count + '회전)' + '</td>';
                                html += '<input type="hidden" name="' + json.content[i].schedule_id + '_uploaded_files" class="uploaded-files" value="">';
                                html += '<td align="center">' + json.content[i].team1_name + '팀' + '</td>';
                                html += '<td align="center"><input type="text" class="form-control score-result" name="' + json.content[i].schedule_id + '_1_result_score" value="' + already_team1_score + '" /></td>';
                                html += '<td rowspan="2" align="center"><textarea class="form-control maxlength-handler score-desc" name="' + json.content[i].schedule_id +  '_result_desc" rows="5" maxlength="5000" >' + already_desc + '</textarea></td>';
                                html += '<td rowspan="2" align="center"><span class="btn green fileinput-button"><i class="fa fa-plus"></i><span> 파일추가... </span><input type="file" data-required="1" has-valuable-data="yes" class="form-control multimedia" name="' + json.content[i].schedule_id +  '_files[]" title="다매체를 삽입하려면 여기를 누르십시오." value=""></span><div class="attachments">' + already_files + '</div></td>';

                                html += '<tr class="score-tr ' + progress_class + '"><td align="center">' + json.content[i].team2_name + '팀' + '</td>';
                                html += '<td align="center"><input type="text" class="form-control score-result" name="' + json.content[i].schedule_id + '_2_result_score" value="' + already_team2_score + '" /></td>';
                                html += '</tr>';
                            }

                        } else {
                            html = '<tr class="odd gradeX"><td colspan="5" align="center">편집할 경기가 없습니다.</td></tr>';
                        }

                        $('#result_edit_table').find('tbody').html(html); 
                        
                    } else {
                        toastr["error"](' Ajax 오유입니다 ! ');
                    }
                });

                self.initKeydown();

            });

            $('body').on('click', 'button.edit-modal-save', function() {

                $('#modal_event', self.$modalform).val('modalSave');

                var date = $('button.edit-modal-save').data('date');
                $('#modal_date', self.$modalform).val(date);

                var competition_id = $('input#competition_id', self.$form).val();
                $('#modal_competition_id', self.$modalform).val(competition_id);

                self.getAttachments();

                Metronic.blockUI({
                    target: self.$container,
                    iconOnly: true
                });

                // form ajaxsubmit
                self.$modalform.ajaxSubmit({
                    dataType: 'json',
                    success: function(json, status, xhr) {
                        Metronic.unblockUI(self.$container);
                        if ( json.success ) {

                            self.$modal.modal('hide');

                            if ( json.message.success ) {
                                location.reload(true);
                                toastr["success"](json.message.success);
                            } else if ( json.message.error ) {
                                toastr["error"](json.message.error);
                            }

                        } else {
                            toastr["error"](' 경기회전점수 입력오유입니다 ! ');
                        }
                    },
                    error: function(xhr, status, error) {
                        Metronic.unblockUI(self.$container);
                        toastr["error"]('관리자체계와 접속할수 없습니다. <br/>망 접속을 확인하여주십시오 !');
                    }
                });
                
            });

        },

        initKeydown: function() {
            var self =  this;
            self.$modal.on('keydown', 'input.score-result', function(e) {
                if ( ( e.keyCode >= 48 && e.keyCode <= 57 ) || ( e.keyCode >= 96 && e.keyCode <= 105 ) || e.keyCode == 110 || e.keyCode == 190 || e.keyCode == 46 || e.keyCode == 8 ){
                    return true;
                }else{
                    e.preventDefault();
                }
            });
        },

        getAttachments: function() {
            $('body').find('.attachments').each(function() {
                var fileIds = [];
                $(this).find('.file').each(function() {
                    fileIds.push($(this).data('id'));
                });
                $(this).closest('.score-tr').find('.uploaded-files').val(fileIds.join(','));
            });
        },

        bindEvents: function() {
            var self =  this;

            $('#progressed_competitions').on('change', function() {
                $('#competition_id', self.$form).val($(this).val());
                self.$form.submit();
            });

            $('body').on('change', '.multimedia', function (e) {
                $this = $(e.target);
                $('#modal_event', self.$modalform).val('uploadfiles');
                $('#schedule_id', self.$modalform).val($this.closest('.score-tr').data('schedule-id'));

                self.$modalform.ajaxSubmit({
                    success: function(json) {
                        if ( !json.success ) {
                            if ( json.error != '' ) {
                                $this.parent().addClass('has-error');
                                $this.after('<span class="help-block help-block-error">' + json.error + '</span>');
                            }
                            return false;
                        }

                        $.each(json.files, function(i, file){
                            $('.attachments', $this.closest('.score-tr')).append('<div class="file" data-id="' + file.id + '"><a class="link-delete"><i class="fa fa-trash-o"></i></a> <a href="/files/' + file.id + '/' + file.hash + '" target="_blank">' + file.name + '</a></div>');
                        })
                    },

                    error: function(xhr) {
                        $this.val('');
                        console.log(xhr);
                    },

                    dataType: 'json',
                });
            });

            $('body').on('click', function(e) {
                if ( $(e.target).parent().hasClass('link-delete') ) {
                    var $file = $(e.target).closest('.file');
                    var token = self.$container.data('token');
                    var data = {
                        id: $file.data('id'),
                        _token: token
                    };
                    $.post('/admin/delete-file', data, function (json) {
                        $file.remove();
                    });                 
                }
            });

        },

        render: function () {
            $('select.select2').select2({
                allowClear: true,
                minimumResultsForSearch: -1
            });
        },

        init: function() {
            this.initElements();
            this.initCalendar();
            this.modalAjaxHandler();

            this.bindEvents();
            this.render();
        },

    };

    fn.init();

});