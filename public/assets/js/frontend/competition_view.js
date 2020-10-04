$(document).ready(function(){

	var fn = {

    	initElements: function() {

        },

        bindEvents: function() {

            var self =  this;
            

        },

        render: function () {
        	var self =  this;
            stars.init($('.client-score .stars'));
        },

        renderBarChart: function() {
            var chart = AmCharts.makeChart("bar_chart", {
                "type": "serial",
                "theme": "light",
                "autoMargins": true,
                "marginLeft": 30,
                "marginRight": 8,
                "marginTop": 10,
                "marginBottom": 26,

                "fontFamily": 'WKLGM',            
                "color":    '#000',
                
                "dataProvider": barGraphData,
                "startDuration": 1,
                "graphs": [{
                    "alphaField": "alpha",
                    "balloonText": "<span style='font-size:13px;'>[[category]]팀의 [[title]] :<b>[[value]]</b> [[additional]]</span>",
                    "dashLengthField": "dashLengthColumn",
                    "fillAlphas": 1,
                    "title": "종합성적",
                    "type": "column",
                    "valueField": "score"
                }, {
                    "balloonText": "<span style='font-size:13px;'>[[category]]팀의 [[title]] :<b>[[value]]</b> [[additional]]</span>",
                    "bullet": "round",
                    "dashLengthField": "dashLengthLine",
                    "lineThickness": 3,
                    "bulletSize": 7,
                    "bulletBorderAlpha": 1,
                    "bulletColor": "#FFFFFF",
                    "useLineColorForBulletBorder": true,
                    "bulletBorderThickness": 3,
                    "fillAlphas": 0,
                    "lineAlpha": 1,
                    "title": "Expenses",
                    "valueField": "expenses"
                }],
                "categoryField": "team_name",
                "categoryAxis": {
                    "gridPosition": "start",
                    "axisAlpha": 0,
                    "tickLength": 0
                }
            });
        },

        renderPieChart: function() {
            var chart = AmCharts.makeChart("pie_chart", {
                "type": "pie",
                "theme": "light",
                "fontFamily" : 'WKLGM',             
                "color":    '#000',
                "dataProvider": pieGraphData,
                "valueField": "value",
                "titleField": "type",
                "outlineAlpha": 0.5,
                "depth3D": 11,
                "balloonText": "[[type]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
                "angle": 30
            });
        },

        renderEasyPieChart: function() {
           $('.easy-pie-chart .number.progress-percent').easyPieChart({
                animate: 1000,
                size: 120,
                lineWidth: 7,
                barColor: Metronic.getBrandColor('red')
            });
        },

        initCalendar: function () {
            var self = this;

            if (!jQuery().fullCalendar) {
                return;
            }

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

        init: function() {
            this.initElements();

            this.bindEvents();
            this.render();

            this.renderBarChart();
            this.renderPieChart();
            this.renderEasyPieChart();

            this.initCalendar();
        },

    };

    fn.init();
    

});