/*
Mean Menu Responsive
============================*/		
jQuery('nav#main-menu').meanmenu();

/*
Stickey Header
============================*/
$("#sticky_menu").sticky({topSpacing:0});
/*
Stikey Js
============================*/ 
(function () {
	var nav = $('.mnmenu-sec');
	var scrolled = false;
	$(window).scroll(function () {
		if (120 < $(window).scrollTop() && !scrolled) {
			nav.addClass('sticky_menu animated fadeInDown').animate({ 'margin-top': '0px' });
			scrolled = true;
		}
		if (120 > $(window).scrollTop() && scrolled) {
			nav.removeClass('sticky_menu animated fadeInDown').css('margin-top', '0px');
			scrolled = false;
		}
	});
}());

/*
Preeloader
============================*/
$(document).ready(function() {
	$('#preloader').fadeOut();
	$('#preloader-status').delay(200).fadeOut('slow');
	$('body').delay(200).css({'overflow-x':'hidden'});
});

/*
Slider Carousel
============================*/ 
$(".slider-headline").owlCarousel({
	items: 1,
	nav: true,
	navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
	dots: false,
	autoplay: true,
	loop: true,
	animateOut: 'slideOutDown',
	animateIn: 'slideInDown',
	mouseDrag: false,
	touchDrag: false
});

$(".owl-carousel").on("translate.owl.carousel", function(){
	$(".slider-text h1").removeClass("animated fadeInLeft").css("opacity", "0");
	$(".slider-text p").removeClass("animated fadeInDown").css("opacity", "0");
	$(".slider-text ul li a").removeClass("animated fadeInUp").css("opacity", "0");
});

$(".owl-carousel").on("translated.owl.carousel", function(){
	$(".slider-text h1").addClass("animated fadeInLeft").css("opacity", "1");
	$(".slider-text p").addClass("animated fadeInDown").css("opacity", "1");
	$(".slider-text ul li a").addClass("animated fadeInUp").css("opacity", "1");
});

// Testimoni
$(".slider-testimonial").owlCarousel({
	autoplay: true, 
	pagination:false,
	nav:true, 
	navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
	dots:true, 
	items :1,
	responsive:{
		0:{
			items:1
		},
		600:{
			items:1
		},
		768:{
			items:1
		},
		1000:{
			items:1
		}
	}
});

/*
scrollUp
============================*/	
$.scrollUp({
	scrollText: '<i class="fa fa-angle-up"></i>',
	easingType: 'linear',
	scrollSpeed: 900,
	animation: 'fade'
});

/*Magnific Popup
============================*/
$('.gallery-photo').magnificPopup({
	type: 'image',
	gallery: {
		enabled: true
	},
});
/*
Project Gallery Js
============================*/	
$(".project-gallery").imagesLoaded( function() {
	$(".filtr-container").isotope({
		itemSelector: '.filtr-item',
		layoutMode: 'fitRows',
	});
	$("ul.simplefilter li").on("click",function(){
	$("ul.simplefilter li").removeClass("active");
	$(this).addClass("active");
	 
	var selector = $(this).attr('data-filter');
	$(".filtr-container").isotope({
		filter: selector,
		animationOptions: {
			duration: 750,
			easing: 'linear',
			queue: false,
		}
	});
	return false;
	});
});
/*
Wow Js
============================*/
new WOW().init();