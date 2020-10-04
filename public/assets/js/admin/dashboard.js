$(document).ready(function(){

	var fn = {

    	initElements: function() {

            this.$container     = $('div#edit_employee_container');
            this.$form          = $('form#edit_employee_form', this.$container);
        },

        bindEvents: function() {
            var self =  this;

        },

        render: function () {
        	var self =  this;
            
        },

        renderBarChart: function() {
            var chart = AmCharts.makeChart("bar_chart", {
                "type": "serial",
                "theme": "light",
                "autoMargins": false,
                "marginLeft": 30,
                "marginRight": 8,
                "marginTop": 10,
                "marginBottom": 26,

                "fontFamily": 'WKLCHBO',            
                "color":    '#000',
                
                "dataProvider": barGraphData,
                "startDuration": 1,
                "graphs": [{
                    "alphaField": "alpha",
                    "balloonText": "<span style='font-size:14px;'>[[category]]팀의 [[title]] :<b>[[value]]</b> [[additional]]</span>",
                    "dashLengthField": "dashLengthColumn",
                    "fillAlphas": 1,
                    "title": "종합성적",
                    "type": "column",
                    "valueField": "score"
                }, {
                    "balloonText": "<span style='font-size:14px;'>[[category]]팀의 [[title]] :<b>[[value]]</b> [[additional]]</span>",
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
	            "fontFamily" : 'WKLCHBO',	            
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
                barColor: Metronic.getBrandColor('green')
            });
        },

        handleDatatables: function() {
            var table = $('#competition_list_table');

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
                },{
                    "orderable": false
                }],
                "lengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "모두"] // change per page values here
                ],
                // set the initial value
                "pageLength": 5,            
                "pagingType": "bootstrap_full_number",
                "language": {
                    "lengthMenu": "  _MENU_ 개의 결과",
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
                    "targets": []
                }],
                "order": [
                    [1, "asc"]
                ] // set first column as a default sort by asc
            });

            var tableWrapper = jQuery('#competition_list_table_wrapper');

            tableWrapper.find('.dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown
        },

        init: function() {
            this.initElements();

            this.bindEvents();
            this.render();
            this.renderBarChart();
            this.renderPieChart();
            this.renderEasyPieChart();
            this.handleDatatables();
        },

    };

    fn.init();
    

});