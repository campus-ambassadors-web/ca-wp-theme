/*
Bones Scripts File
Author: Eddie Machado

This file should contain any js scripts you want to add to the site.
Instead of calling it in the header or throwing it inside wp_head()
this file will be called automatically in the footer so as not to
slow the page load.

*/
$ = jQuery;

// IE8 ployfill for GetComputed Style (for Responsive Script below)
if (!window.getComputedStyle) {
    window.getComputedStyle = function(el, pseudo) {
        this.el = el;
        this.getPropertyValue = function(prop) {
            var re = /(\-([a-z]){1})/g;
            if (prop == 'float') prop = 'styleFloat';
            if (re.test(prop)) {
                prop = prop.replace(re, function () {
                    return arguments[2].toUpperCase();
                });
            }
            return el.currentStyle[prop] ? el.currentStyle[prop] : null;
        }
        return this;
    }
}

// as the page loads, call these scripts
$(document).ready(function($) {

    /*
    Responsive jQuery is a tricky thing.
    There's a bunch of different ways to handle
    it, so be sure to research and find the one
    that works for you best.
    */
    
    /* getting viewport width */
    var responsive_viewport = viewport().width;
    
    /* if is below 481px */
    if (responsive_viewport < 481) {
    
    } /* end smallest screen */
    
    /* if is larger than 481px */
    if (responsive_viewport > 481) {
		//$('#container').css('margin-top', $('#mobile-header').outerHeight() + 'px');
		
    } /* end larger than 481px */
    
    /* if is above or equal to 768px */
    if (responsive_viewport >= 768) {
    
        /* load gravatars */
        $('.comment img[data-gravatar]').each(function(){
            $(this).attr('src',$(this).attr('data-gravatar'));
        });
        
    }
    
    /* off the bat large screen actions */
    if (responsive_viewport > 1030) {
        
    }
    
	// mobile nav
	$('#mobile-nav .page_item_has_children a').click( function(e) {
		e.stopImmediatePropagation();
	});
	$('#mobile-nav .page_item_has_children').click( function(e) {
		$(this).find('> ul.children').slideToggle( 100 );
		$(this).toggleClass('open');
		e.stopImmediatePropagation();
	});
	
	// remove borders from <a>'s which contain images
	$('a:has( > img )').css('border-bottom', 'none');
	
	// mobile header fixes
	$(window).scroll( function() {
		adjustFixedHeader();
	});
	$(window).resize( function() {
		adjustFixedHeader();
	});
	function adjustFixedHeader() {
		if ( $('#wpadminbar').length > 0 && $('#wpadminbar').css('position') !== 'fixed' ) {
			$('#mobile-header-fixed').css('margin-top', - Math.min( $(window).scrollTop(), parseInt( $('html').css('margin-top') ) ) );
		} else {
			$('#mobile-header-fixed').css('margin-top', '0');
		}
	}
	adjustFixedHeader();
 	
	
	// apply fancybox to galleries
	$('.gallery').each( function( index ) {
		$(this).find('dl').each( function() {
			// enable captions in fancybox
			var dd = $(this).find('dd');
			if ( dd ) {
				$(this).find('a').attr('title', dd.text());
			}
			// add all links in this caption to a group and call fancybox
			$(this).find('a').attr('data-fancybox-group', index).fancybox();
		});
	});
	
}); /* end of as page load scripts */


function hideSection( button ) {
	var section = $(button).find(' + .revealable-section');
	if ( section.length == 0 ) section = $(button).parent().find(' + .revealable-section');
	section.slideToggle(200);
}


// Navigation menu button for mobile devices
function toggleMobileNav() {
	if ( $('#mobile-nav').css('display') == 'none' ) {
		// set the height and hide overflow for the body
		window.setTimeout( function() {
			$('#mobile-nav').css( 'height', $(window).innerHeight() - $('#mobile-nav').offset().top + $('#mobile-header-fixed').offset().top - parseInt( $('html').css('margin-top') ) - parseInt( $('#mobile-header-fixed').css('margin-top') ) + 'px' );
		}, 0 );
		$('body').css('overflow', 'hidden');
	} else {
		$('body').css('overflow', 'auto');
	}
	$('#mobile-nav, #mobile-menu-bg').fadeToggle();
}



// quick email contact form widget, submit button
// requires fancybox
$(document).ready( function($) {
	$('.widget_quick_email_contact a').click( function(e) {
		e.preventDefault();
		$widget = $('.widget_quick_email_contact');
		var send_data = {
			action: 'quick_email_contact_submit', // defines the php handler
			message: $widget.find('.quick-message').val(), // the message typed in the textarea
			widget_id: $widget.find('input[name=widget_id]').val(), // the id number of this widget
			nonce: $widget.find('input[name^=_nonce]').val() // nonce
		};
		if ( $widget.find('.quick-message-email').length > 0 ) {
			send_data.return_email = $widget.find('.quick-message-email').val();
		}
		
		$.fancybox.showLoading();
		$.ajax({
			type: 'POST',
			cache: false,
			url: this.href,
			data: send_data,
			success: function( data ) {
				$.fancybox.open( '<div>'+data+'</div>' );
				// if it was a success, clear the text field.
				if ( data.indexOf( 'class="ajax-success"' ) != -1 ) {
					$widget.find('textarea').val('').animate({ height: '60px' }, 150);
					$widget.find('.quick-message, .quick-message-email').val('');
				}
			},
			error: function() {
				$.fancybox.open( '<div>Error: Could not send the request. Please try again later.</div>' );
			}
		});
	});
	
	$('.widget_quick_email_contact textarea').focus( function() {
		$(this).animate({
			height: '180px'
		}, 150);
	});
});


/********* parallax effect **************/

// adjust parallax whenever the screen is scrolled, or whenever the window is resized
jQuery(window).scroll( function() {
	moveParallax();
});
jQuery(window).resize( function() {
	adjustParallax();
});
jQuery(document).ready( function() {
	adjustParallax();
});

var bgspeed = 0.37;
function adjustParallax() {
	var $ = jQuery;
	if ( viewport().width >= 768 ) {
		$obj = $('.parallax-element');
		if ( $obj.length > 0 ) {
			/*
			// adjust the background height so as not to waste any background image.
			var bg_height = $obj.innerHeight() + ($(window).height() - $obj.height()) * ( 1 - bgspeed );
			// find the width and make sure the background image is wide enough to cover the window width
			var aspect_ratio = parseFloat( $obj.data('ratio') );
			var bg_width = aspect_ratio * bg_height;
			var winwidth = Math.min( $(window).width(), 1140 );
			// if bg width isn't wide enough, change the height
			if ( bg_width < winwidth ) bg_height = winwidth / aspect_ratio;
			$obj.css('background-size', 'auto ' + bg_height + 'px');
			*/
			var aspect_ratio = parseFloat( $obj.data('ratio') );
			var bg_width = $obj.width();
			var bg_height = bg_width / aspect_ratio;
			// if the element height is taller than the image, make the image bigger
			if ( $obj.innerHeight() > bg_height ) {
				bg_height = $obj.innerHeight();
				bg_width = bg_height * aspect_ratio;
			}
			
			$obj.css('background-size', bg_width + 'px ' + bg_height + 'px');
			
			moveParallax();
		}
	}
}

function moveParallax() {
	var $ = jQuery;
	if ( viewport().width >= 768 ) {
		$obj = $('.parallax-element');
		if ( $obj.length > 0 ) {
			var yPos = -( $(window).scrollTop() /*- $obj.offset().top*/ ) / (1/bgspeed) ;
			if ( $('body').hasClass('nav-above-header') ) yPos += $('#inner-header').height();
			if ( $('body').hasClass('admin-bar') ) yPos += $('#wpadminbar').height();
			$obj.css( 'background-position', 'center '+ yPos + 'px' );
		}
	}
}




function viewport() {
    var e = window, a = 'inner';
    if (!('innerWidth' in window )) {
        a = 'client';
        e = document.documentElement || document.body;
    }
    return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
}


/*! A fix for the iOS orientationchange zoom bug.
 Script by @scottjehl, rebound by @wilto.
 MIT License.
*/
(function(w){
	// This fix addresses an iOS bug, so return early if the UA claims it's something else.
	if( !( /iPhone|iPad|iPod/.test( navigator.platform ) && navigator.userAgent.indexOf( "AppleWebKit" ) > -1 ) ){ return; }
    var doc = w.document;
    if( !doc.querySelector ){ return; }
    var meta = doc.querySelector( "meta[name=viewport]" ),
        initialContent = meta && meta.getAttribute( "content" ),
        disabledZoom = initialContent + ",maximum-scale=1",
        enabledZoom = initialContent + ",maximum-scale=10",
        enabled = true,
		x, y, z, aig;
    if( !meta ){ return; }
    function restoreZoom(){
        meta.setAttribute( "content", enabledZoom );
        enabled = true; }
    function disableZoom(){
        meta.setAttribute( "content", disabledZoom );
        enabled = false; }
    function checkTilt( e ){
		aig = e.accelerationIncludingGravity;
		x = Math.abs( aig.x );
		y = Math.abs( aig.y );
		z = Math.abs( aig.z );
		// If portrait orientation and in one of the danger zones
        if( !w.orientation && ( x > 7 || ( ( z > 6 && y < 8 || z < 8 && y > 6 ) && x > 5 ) ) ){
			if( enabled ){ disableZoom(); } }
		else if( !enabled ){ restoreZoom(); } }
	w.addEventListener( "orientationchange", restoreZoom, false );
	w.addEventListener( "devicemotion", checkTilt, false );
})( this );




/**
 * jQuery scrollTo
 * Copyright (c) 2007-2012 Ariel Flesler - aflesler(at)gmail(dot)com | http://flesler.blogspot.com
 * Dual licensed under MIT and GPL.
 * @author Ariel Flesler
 * @version 1.4.3.1
 */
(function($){var h=$.scrollTo=function(a,b,c){$(window).scrollTo(a,b,c)};h.defaults={axis:'xy',duration:parseFloat($.fn.jquery)>=1.3?0:1,limit:true};h.window=function(a){return $(window)._scrollable()};$.fn._scrollable=function(){return this.map(function(){var a=this,isWin=!a.nodeName||$.inArray(a.nodeName.toLowerCase(),['iframe','#document','html','body'])!=-1;if(!isWin)return a;var b=(a.contentWindow||a).document||a.ownerDocument||a;return/webkit/i.test(navigator.userAgent)||b.compatMode=='BackCompat'?b.body:b.documentElement})};$.fn.scrollTo=function(e,f,g){if(typeof f=='object'){g=f;f=0}if(typeof g=='function')g={onAfter:g};if(e=='max')e=9e9;g=$.extend({},h.defaults,g);f=f||g.duration;g.queue=g.queue&&g.axis.length>1;if(g.queue)f/=2;g.offset=both(g.offset);g.over=both(g.over);return this._scrollable().each(function(){if(e==null)return;var d=this,$elem=$(d),targ=e,toff,attr={},win=$elem.is('html,body');switch(typeof targ){case'number':case'string':if(/^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(targ)){targ=both(targ);break}targ=$(targ,this);if(!targ.length)return;case'object':if(targ.is||targ.style)toff=(targ=$(targ)).offset()}$.each(g.axis.split(''),function(i,a){var b=a=='x'?'Left':'Top',pos=b.toLowerCase(),key='scroll'+b,old=d[key],max=h.max(d,a);if(toff){attr[key]=toff[pos]+(win?0:old-$elem.offset()[pos]);if(g.margin){attr[key]-=parseInt(targ.css('margin'+b))||0;attr[key]-=parseInt(targ.css('border'+b+'Width'))||0}attr[key]+=g.offset[pos]||0;if(g.over[pos])attr[key]+=targ[a=='x'?'width':'height']()*g.over[pos]}else{var c=targ[pos];attr[key]=c.slice&&c.slice(-1)=='%'?parseFloat(c)/100*max:c}if(g.limit&&/^\d+$/.test(attr[key]))attr[key]=attr[key]<=0?0:Math.min(attr[key],max);if(!i&&g.queue){if(old!=attr[key])animate(g.onAfterFirst);delete attr[key]}});animate(g.onAfter);function animate(a){$elem.animate(attr,f,g.easing,a&&function(){a.call(this,e,g)})}}).end()};h.max=function(a,b){var c=b=='x'?'Width':'Height',scroll='scroll'+c;if(!$(a).is('html,body'))return a[scroll]-$(a)[c.toLowerCase()]();var d='client'+c,html=a.ownerDocument.documentElement,body=a.ownerDocument.body;return Math.max(html[scroll],body[scroll])-Math.min(html[d],body[d])};function both(a){return typeof a=='object'?a:{top:a,left:a}}})(jQuery);

