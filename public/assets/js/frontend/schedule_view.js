$(document).ready(function(){

	var fn = {

    	initElements: function() {

        },

        bindEvents: function() {

            var self =  this;
            

        },

        render: function () {
        	var self =  this;

            // define star
            stars.init($('.client-score .stars'));

            $('#myCarousel .carousel-indicators').find('li').eq(0).addClass('active');
            $('#myCarousel .item').eq(0).addClass('active');
            $('#myCarousel').slideDown();
            $('#myCarousel').carousel();

            $(".files .photo a").fancybox();
        },

        init: function() {
            this.initElements();

            this.bindEvents();
            this.render();
        },

    };

    fn.init();
    

});