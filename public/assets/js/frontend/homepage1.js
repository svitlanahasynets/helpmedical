$(document).ready(function(){

	var fn = {

    	initElements: function() {
            this.$container = $('div.homepage-container');
            this.$wrapper   = $('div.homepage-content-wrapper');
            this.$form 	    = $('#search_form', this.$container);

            this.$page      = $('[name="page"]', this.$form);
            this.$category  = $('select#category');
            this.$searchBtn = $('#search_btn', this.$form);
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
                    $('#start_date', self.$form).val(start.format('YYYY-MM-DD'));
                    $('#end_date', self.$form).val(end.format('YYYY-MM-DD'));

                    $('#page', self.$form).val('1');
                    self.filter();
                }
            );

            //Set the initial state of the picker label
            $('#date_range input').val('');
            $('#start_date', self.$form).val('');
            $('#end_date', self.$form).val('');
        },

        handleDatepicker: function () {
            $('.date-picker').datepicker({
                rtl: Metronic.isRTL(),
                orientation: "left",
                autoclose: true
            });
        },

        filter: function() {
            var self = this;

            Metronic.blockUI({
                target: self.$wrapper,
                iconOnly: true
            });

            self.$form.ajaxSubmit({
                success: function(data) {
                    Metronic.unblockUI(self.$wrapper);
                    self.$resultSection.html(data);
                    self.render();

                    if ( $('#category').val() == 'schedule_view' ) {
                        $('div.events-view').css('display', 'block');
                    } else {
                        $('div.events-view').css('display', 'none');
                    }
                },
                error: function(xhr) {
                    Metronic.unblockUI(self.$wrapper);
                    toastr["error"]('관리자체계와 접속할수 없습니다. <br/>망 접속을 확인하여주십시오.');
                },
                dataType: 'html',
            });

            return false;
        },

        pagination: function() {
            var self = this;

            self.$resultSection.on('click', function(e) {
                var $this = $(e.target);

                if ( $this.get(0).nodeName == 'A' && $this.parent().parent().hasClass('pagination') ) {
                    e.stopPropagation();

                    var page = $this.text();

                    if ( $this.attr('rel') == 'prev' ) {
                        page = fn.$page.val() - 1;
                    } else if ( $this.attr('rel') == 'next' ) {
                        page = fn.$page.val() + 1;
                    }

                    if ( page < 1 ) {
                        page = 1;
                    }

                    self.$page.val(page);
                    self.filter();

                    return false;
                }
            });
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

            self.$category.on('change', function() {
                var $this = $(this);
                $('#page', self.$form).val('1');
                self.filter();
            });

            self.$searchBtn.on('click', function() {
                $('#page', self.$form).val('1');
                self.filter();
            });

            self.$eventIds.on('change', function() {
                $('#page', self.$form).val('1');
                self.filter();
            });

            self.$sort.on('change', function() {
                var $this = $(this);
                $('#page', self.$form).val('1');
                self.filter();
            });

        },

        render: function () {
        	var self =  this;

            // define star
            stars.init($('.client-score .stars'));
            $(".files .photo .img-wrapper a").fancybox();
        },

        init: function() {
            this.initElements();
            this.handleDaterangepicker();
            this.handleDatepicker();
            this.pagination();

            this.bindEvents();
            this.render();
        },

    };

    fn.init();
    

});