$(document).ready(function(){

	var fn = {

		carouselInterval: null,

        initCarousel: function() {

            $('#slogan p.item').each(function(i, o){
                var txtvalue = $(o).text();
                if (txtvalue.trim() == '') $(o).remove(0);
            });

            $('#slogan .item').eq(0).addClass('active');
            $('#slogan').slideDown();
            $('#slogan').carousel();
        },

        render: function () {
        	var self =  this;
        	setTimeout(function(){fn.initCarousel();}, 1000);
        },

        init: function() {
            this.render();
        },

    };

    fn.init();
    

});