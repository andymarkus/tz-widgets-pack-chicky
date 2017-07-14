(function($) {
    "use strict";

    $.fn.extend({
        animateCss: function (animationName) {
            var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
            this.addClass('animated ' + animationName).one(animationEnd, function() {
                $(this).removeClass('animated ' + animationName);
            });
        }
    });

    $(document).ready(function () {

        if ( typeof jQuery().select2() != 'undefined' ){
            var select = $( '.portfolio-filters' );
            if ( select.length ) {
                $(select).select2();
            }
        }

        var portfolio = jQuery( '.portfolio' );

        $(portfolio).each(function(){

            var portfolio_cols = ( $(this).data('cols') == '' ) ? 3 : $(this).data('cols');
            if ( typeof jQuery().isotope == 'undefined' ) {
                console.log('Isotope required!');
                return;
            }

            var gallery_layout = ( $(this).data('layout') != '' ) ? $(this).data('layout') : 'masonry';

            var _portfolio_container = this;

            if ( typeof imagesLoaded == 'function')
                $(_portfolio_container).imagesLoaded().done( function( instance ) {
                    $(_portfolio_container).isotope({
                        itemSelector: '.gallery-item',
                        layoutMode:  gallery_layout,
                        transitionDuration: 600,
                    });
                });



            var filter = $(this).prev().find('.portfolio-filters');
            if (filter.length){
                var cont = this;
                $(filter).on('change', function(){

                    $(cont).isotope({ filter: $(this).val() });
                });

            }
        });

    });

})(jQuery);
