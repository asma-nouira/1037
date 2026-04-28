jQuery(document).ready(function($) {
    $('.hero-slider').slick({
        dots: true,
        arrows: false,
        infinite: true,
        speed: 800,
        fade: true,
        cssEase: 'ease-in-out',
        autoplay: true,
        autoplaySpeed: 5000,
        pauseOnHover: false
    });

    $(".center-slider-full").slick({
        centerMode: true,
        centerPadding: '0',
        slidesToShow: 1,
        autoplay: false,
        autoplaySpeed: 20000,
        infinite: true,
        dots: true,
        arrows: false,
        responsive: [
            {
                breakpoint: 1366,
                settings: {
                    arrows: true,
                    centerMode: true,
                    centerPadding: '0',
                    slidesToShow: 1
                }
            },
            {
                breakpoint: 766,
                settings: {
                    arrows: true,
                    centerMode: true,
                    centerPadding: '0',
                    slidesToShow: 1
                }
            },
            {
                breakpoint: 400,
                settings: {
                    arrows: true,
                    centerMode: true,
                    centerPadding: '0',
                    slidesToShow: 1
                }
            }
        ]
    }).on('setPosition', function (event, slick) {
        slick.$slides.css('height', slick.$slideTrack.height() + 'px');
    });
});