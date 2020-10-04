$(document).ready(function(){

    var fn = {

    	initElements: function() {

            this.$container     = $('div.container');
            this.$form          = $('form#view_schedule_form', this.$container);
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
            this.initCalendar();

            this.bindEvents();
            this.render();
        },

    };

    fn.init();

});