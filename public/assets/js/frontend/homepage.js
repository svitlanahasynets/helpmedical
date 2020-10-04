$(document).ready(function(){

	var fn = {

        $container: null,
        $wrapper: null,
        $form: null,
        $keyword: null,
        $page: null,
        $category: null,
        $startDate: null,
        $endDate: null,
        $searchBtn: null,
        $eventIds: null,
        $sort: null,
        $resultSection: null,
        url: '',

    	initElements: function() {
            this.$container = $('div.homepage-container');
            this.$wrapper   = $('div.homepage-content-wrapper');
            this.$form 	    = $('#search_form', this.$container);
            this.$keyword   = $('input[name="q"]', this.$form);

            this.$page      = $('[name="page"]', this.$form);
            this.$category  = $('select#category');
            this.$searchBtn = $('#search_btn', this.$form);
            this.$startDate = $('#start_date', this.$form);
            this.$endDate   = $('#end_date', this.$form);
            this.$eventIds  = $('.event-ids', this.$form);
            this.$sort      = $('#sort', this.$form);

            this.$resultSection = $('#result_section');
        },

        handleDaterangepicker: function () {
            var self = this;
            $('#date_range').daterangepicker({
                    opens: (Metronic.isRTL() ? 'left' : 'right'),
                    minDate: '01/01/2012',
                    maxDate: '12/31/2024',
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
                    $('#date_range input').val(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
                    self.$startDate.val(start.format('YYYY-MM-DD'));
                    self.$endDate.val(end.format('YYYY-MM-DD'));

                    self.makePrettyUrl();
                }
            );

            //Set the initial state of the picker label
            $('#date_range input').val(date_range);
            self.$startDate.val(start_date);
            self.$endDate.val(end_date);
        },

        handleDatepicker: function () {
            $('.date-picker').datepicker({
                rtl: Metronic.isRTL(),
                orientation: "left",
                autoclose: true
            });
        },

        makePrettyUrl: function() {
            fn.getUrlParams();

            location.href = currentURL + '?' + fn.url;
            return false;
        },

        getUrlParams: function() {

            if ( fn.$keyword.val().trim() != '' ) {
                fn.addUrlParam({name:'q', value:fn.$keyword.val().trim()});
            }

            if ( fn.$category.val() != '' ) {
                fn.addUrlParam({name:'category', value:fn.$category.val()});
            }

            if ( fn.$startDate.val() != '' ) {
                fn.addUrlParam({name:'start_date', value:fn.$startDate.val()});
            }

            if ( fn.$endDate.val() != '' ) {
                fn.addUrlParam({name:'end_date', value:fn.$endDate.val()});
            }

            if ( $('#date_range input').val() != '' ) {
                fn.addUrlParam({name:'date_range', value:$('#date_range input').val()});
            }

            var typesArray = [];
            var typesAllChecked = typesAllUnchecked = true;
            fn.$eventIds.each(function() {
                if ( $(this).prop('checked') ) {
                    typesArray.push($(this).val());
                    typesAllUnchecked = false;
                } else {
                    typesAllChecked = false;
                }
            });

            if ( typesArray.length ) {
                fn.addUrlParam({name:'event_ids', value:typesArray.join(',')});
            }

            fn.addUrlParam({name:'sort', value:(fn.$sort.val() == undefined ? 'desc' : fn.$sort.val())});
        },

        addUrlParam: function(param) {
            if ( fn.url != '' ) {
                fn.url += '&';
            }

            fn.url += param.name + '=' + param.value;

            return fn.url;
        },

        bindEvents: function() {

            var self =  this;

            $('body').off('click', 'a.clear-filter');
            $('body').on('click', 'a.clear-filter', function() {
                if (currentURL == undefined)
                    return true;
                
                document.location.href = currentURL;
                return false;
            });

            self.$form.on('submit', self.makePrettyUrl);

            self.$category.on('change', function() {
                self.makePrettyUrl();
            });

            self.$searchBtn.on('click', function() {
                self.makePrettyUrl();
            });

            self.$eventIds.on('change', function() {
                self.makePrettyUrl();
            });

            self.$sort.on('change', function() {
                self.makePrettyUrl();
            });

            $('.files').each(function(e) {
                var $this = $(this);
                if ( $('span.file', $this).length > 1 ) {

                }

            });

        },

        render: function () {
        	var self =  this;

            // define star
            stars.init($('.client-score .stars'));
            $(".files .photo .img-wrapper a").fancybox();

            if ( self.$category.val() == 'schedule_view' ) {
                $('div.events-view').css('display', 'block');
            } else {
                $('div.events-view').css('display', 'none');
            }
        },

        init: function() {
            this.initElements();
            this.handleDaterangepicker();
            this.handleDatepicker();

            this.bindEvents();
            this.render();
        },

    };

    fn.init();
    

});