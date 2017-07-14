(function($) {
    "use strict";
    $(document).ready(function () {

        var owl = $(".tz-home-products ul.products");

        $('.tabs').tabslet();

        owl.owlCarousel({
            loop:true,
            margin:10,
            smartSpeed:700,
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            responsiveClass:true,
            responsive:{
                0:{
                    items:1,
                    nav:true
                },
                600:{
                    items:2,
                    nav:true
                },
                1000:{
                    items:3,
                    nav:true,
                    loop:true
                },
                1200:{
                    items:4,
                    nav:true,
                    loop:true
                },
                1550:{
                    items:4,
                    nav:true,
                    loop:true,
                    margin:10,
                },
                1860:{
                    items:4,
                    nav:true,
                    loop:false,
                    margin:10,
                },
                2000:{
                    items:4,
                    nav:true,
                    loop:false,
                    margin:10,
                }
            }
        });



    });
})(jQuery);
