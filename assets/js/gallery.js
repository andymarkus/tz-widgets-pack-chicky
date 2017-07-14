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
            var select = $( '.gallery-filters' );
            if ( select.length ) {
                $(select).select2();
            }
        }

        var gallery = jQuery( '.gallery' );

        $(gallery).each(function(){

            var gallery_mode = ( $(this).data('mode') == 'slider' ) ? 'slider' : 'full';
            var gallery_cols = ( $(this).data('cols') == '' ) ? 3 : $(this).data('cols');

            var gal_items = $(this).find('.gallery-item.classic');
            if ( gal_items.length ) {
                $(gal_items).find('.gallery-item-links a').on('mouseover', function(){
                    $(this).animateCss('pulse');
                });

                $(gal_items).hover(
                    function(){
                        $(this).find('.gallery-item-links a').animateCss('fadeInUp');
                    },
                    function(){
                        $(this).find('.gallery-item-links a').animateCss('fadeOutDown');
                    }
                );
            }

            if ( gallery_mode == 'slider' ) {

                if ( typeof jQuery().owlCarousel == 'undefined' ) {
                    console.log('owlCarousel required!');
                    return;
                }

                $(this).owlCarousel({
                    nav : true,
                    dots : false,
                    smartSpeed: 700,
                    fluidSpeed: 700,
                    items: gallery_cols,
                    margin:0,
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
                            items: gallery_cols,
                        },

                    }

                });



            } else {

                if ( typeof jQuery().isotope == 'undefined' ) {
                    console.log('Isotope required!');
                    return;
                }

                var gallery_layout = ( $(this).data('layout') != '' ) ? $(this).data('layout') : 'masonry';

                if (typeof imagesLoaded == 'function') {

                    var _gallery_container = this;

                    $(_gallery_container).imagesLoaded().done( function( instance ) {
                        $(_gallery_container).isotope({
                            itemSelector: '.gallery-item',
                            layoutMode:  gallery_layout,
                            transitionDuration: 600,
                        });
                    });

                }

                var filter = $(this).parent().find('.gallery-filters');
                if (filter.length){
                    var cont = this;
                    $(filter).on('change', function(){
                        $(cont).isotope({ filter: $(this).val() });
                    });
                }
            }


        });

    });

    (function() {
        var tiltSettings = [
            {},
            {
                movement: {
                    imgWrapper : {
                        translation : {x: 10, y: 10, z: 30},
                        rotation : {x: 0, y: -10, z: 0},
                        reverseAnimation : {duration : 200, easing : 'easeOutQuad'}
                    },
                    lines : {
                        translation : {x: 10, y: 10, z: [0,70]},
                        rotation : {x: 0, y: 0, z: -2},
                        reverseAnimation : {duration : 2000, easing : 'easeOutExpo'}
                    },
                    caption : {
                        rotation : {x: 0, y: 0, z: 2},
                        reverseAnimation : {duration : 200, easing : 'easeOutQuad'}
                    },
                    overlay : {
                        translation : {x: 10, y: -10, z: 0},
                        rotation : {x: 0, y: 0, z: 2},
                        reverseAnimation : {duration : 2000, easing : 'easeOutExpo'}
                    },
                    shine : {
                        translation : {x: 100, y: 100, z: 0},
                        reverseAnimation : {duration : 200, easing : 'easeOutQuad'}
                    }
                }
            },
            {
                movement: {
                    imgWrapper : {
                        rotation : {x: -5, y: 10, z: 0},
                        reverseAnimation : {duration : 900, easing : 'easeOutCubic'}
                    },
                    caption : {
                        translation : {x: 30, y: 30, z: [0,40]},
                        rotation : {x: [0,15], y: 0, z: 0},
                        reverseAnimation : {duration : 1200, easing : 'easeOutExpo'}
                    },
                    overlay : {
                        translation : {x: 10, y: 10, z: [0,20]},
                        reverseAnimation : {duration : 1000, easing : 'easeOutExpo'}
                    },
                    shine : {
                        translation : {x: 100, y: 100, z: 0},
                        reverseAnimation : {duration : 900, easing : 'easeOutCubic'}
                    }
                }
            },
            {
                movement: {
                    imgWrapper : {
                        rotation : {x: -5, y: 10, z: 0},
                        reverseAnimation : {duration : 50, easing : 'easeOutQuad'}
                    },
                    caption : {
                        translation : {x: 20, y: 20, z: 0},
                        reverseAnimation : {duration : 200, easing : 'easeOutQuad'}
                    },
                    overlay : {
                        translation : {x: 5, y: -5, z: 0},
                        rotation : {x: 0, y: 0, z: 6},
                        reverseAnimation : {duration : 1000, easing : 'easeOutQuad'}
                    },
                    shine : {
                        translation : {x: 50, y: 50, z: 0},
                        reverseAnimation : {duration : 50, easing : 'easeOutQuad'}
                    }
                }
            },
            {
                movement: {
                    imgWrapper : {
                        translation : {x: 0, y: -8, z: 0},
                        rotation : {x: 3, y: 3, z: 0},
                        reverseAnimation : {duration : 1200, easing : 'easeOutExpo'}
                    },
                    lines : {
                        translation : {x: 15, y: 15, z: [0,15]},
                        reverseAnimation : {duration : 1200, easing : 'easeOutExpo'}
                    },
                    overlay : {
                        translation : {x: 0, y: 8, z: 0},
                        reverseAnimation : {duration : 600, easing : 'easeOutExpo'}
                    },
                    caption : {
                        translation : {x: 10, y: -15, z: 0},
                        reverseAnimation : {duration : 900, easing : 'easeOutExpo'}
                    },
                    shine : {
                        translation : {x: 50, y: 50, z: 0},
                        reverseAnimation : {duration : 1200, easing : 'easeOutExpo'}
                    }
                }
            },
            {
                movement: {
                    lines : {
                        translation : {x: -5, y: 5, z: 0},
                        reverseAnimation : {duration : 1000, easing : 'easeOutExpo'}
                    },
                    caption : {
                        translation : {x: 15, y: 15, z: 0},
                        rotation : {x: 0, y: 0, z: 3},
                        reverseAnimation : {duration : 1500, easing : 'easeOutElastic', elasticity : 700}
                    },
                    overlay : {
                        translation : {x: 15, y: -15, z: 0},
                        reverseAnimation : {duration : 500,easing : 'easeOutExpo'}
                    },
                    shine : {
                        translation : {x: 50, y: 50, z: 0},
                        reverseAnimation : {duration : 500, easing : 'easeOutExpo'}
                    }
                }
            },
            {
                movement: {
                    imgWrapper : {
                        translation : {x: 5, y: 5, z: 0},
                        reverseAnimation : {duration : 800, easing : 'easeOutQuart'}
                    },
                    caption : {
                        translation : {x: 10, y: 10, z: [0,50]},
                        reverseAnimation : {duration : 1000, easing : 'easeOutQuart'}
                    },
                    shine : {
                        translation : {x: 50, y: 50, z: 0},
                        reverseAnimation : {duration : 800, easing : 'easeOutQuart'}
                    }
                }
            },
            {
                movement: {
                    lines : {
                        translation : {x: 40, y: 40, z: 0},
                        reverseAnimation : {duration : 1500, easing : 'easeOutElastic'}
                    },
                    caption : {
                        translation : {x: 20, y: 20, z: 0},
                        rotation : {x: 0, y: 0, z: -5},
                        reverseAnimation : {duration : 1000, easing : 'easeOutExpo'}
                    },
                    overlay : {
                        translation : {x: -30, y: -30, z: 0},
                        rotation : {x: 0, y: 0, z: 3},
                        reverseAnimation : {duration : 750, easing : 'easeOutExpo'}
                    },
                    shine : {
                        translation : {x: 100, y: 100, z: 0},
                        reverseAnimation : {duration : 750, easing : 'easeOutExpo'}
                    }
                }
            }
        ];

        function init() {
            var idx = 0;
            [].slice.call(document.querySelectorAll('.gallery-item.modern')).forEach(function(el, pos) {
                new TiltFx(el, tiltSettings[6]);
            });
        }

        if (typeof $('.sortable-gallery.modern') != 'undefined')
        imagesLoaded($('.sortable-gallery.modern'), function() {
            document.body.classList.remove('loading');
            init();
        });

        $('.sortable-gallery').magnificPopup({
            type: 'image',
            delegate: 'a.popup',
            gallery:{
                enabled:true
            }
        });




    })();


})(jQuery);
