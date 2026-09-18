/**
 * jquery.windy.js v1.0.0
 * https://www.codrops.com
 *
 * Licensed under the MIT license.
 * https://www.opensource.org/licenses/mit-license.php
 * 
 * Copyright 2012, Codrops
 * https://www.codrops.com
 */
;( function( $, window, undefined ) {
	
	'use strict';

	// global
    var Modernizr = window.Modernizr;

	$.Windy = function( options, element ) {
		
		this.$el = $( element );
		this._init( options );
		
	};

	// the options
	$.Windy.defaults = {
		// if we want to specify a selector that triggers the next() function. example: '#wi-nav-next'.
		nextEl : '',
		// if we want to specify a selector that triggers the prev() function.
		prevEl : '',
		// rotation and translation boundaries for the items transitions
		boundaries : {
			rotateX : { min : 40 , max : 90 },
			rotateY : { min : -15 , max : 15 },
			rotateZ : { min : -10 , max : 10 },
			translateX : { min : -200 , max : 200 },
			translateY : { min : -400 , max : -200 },
			translateZ : { min : 250 , max : 550 }
		},
	    endBoundaries: {
			rotateX : { min : 5 , max : 15 },
			rotateY : { min : -2 , max : 2 },
			rotateZ : { min : -2 , max : 2 },
			translateX : { min : -10 , max : -2 },
			translateY : { min : -20 , max : -2 },
			translateZ : { min : 5 , max : 15 }
	    },
		items: '.wi-item',
		itemsContainer: '.wi-container',
		controlNav: false,
		directionNav: true
	};

	$.Windy.prototype = {
        $controlNavItems: [],
		_init : function( options ) {
			
			// options
			this.options = $.extend( true, {}, $.Windy.defaults, options );
			if (this.$el.data('control-nav') !== undefined) this.options.controlNav = Boolean(this.$el.data('control-nav'));
			if (this.$el.data('direction-nav') !== undefined) this.options.directionNav = Boolean(this.$el.data('direction-nav'));
			if (this.options.directionNav === false) {
			    this.options.prevEl = '';
			    this.options.nextEl = '';
			}
			
			// itemsContainer
		    this.$itemsContainer = this.$el.find(this.options.itemsContainer);

			// https://github.com/twitter/bootstrap/issues/2870
			this.transEndEventNames = {
				'WebkitTransition' : 'webkitTransitionEnd',
				'MozTransition' : 'transitionend',
				'OTransition' : 'oTransitionEnd',
				'msTransition' : 'MSTransitionEnd',
				'transition' : 'transitionend'
			};
			this.transEndEventName = this.transEndEventNames[ Modernizr.prefixed( 'transition' ) ];

			this.$items = this.$itemsContainer.children(this.options.items);
			
			// custom position setup
			var _maxHeight = 0;
			this.$items.each(function(i, ele){
			    $(ele).css({
			        float: 'left',
			        display: 'block',
			        visibility: 'hidden',
			        position: 'relative'
			    });

			    if ($(ele).outerHeight() > _maxHeight) _maxHeight = $(ele).outerHeight();
			    $(ele).css({
			        float: 'none',
			        display: 'none',
			        visibility: 'visible'
			    });
			});
            // this.$itemsContainer.height(_maxHeight);
            // this.$items.css({position: 'absolute'});
			// custom position setup end
			
			this.itemsCount = this.$items.length;

			this.resetTransformStr = 'translateX( 0px ) translateY( 0px ) translateZ( 0px ) rotateX( 0deg ) rotateY( 0deg ) rotateZ( 0deg )';

			this.supportTransitions = Modernizr.csstransitions;
			this.support3d = Modernizr.csstransforms3d;

			// show first item
			this.current = 0;
			this.$items.eq( this.current ).show();
			
			// init control navigation
			var _self = this;
			if (this.options.controlNav) {
			    this.$controlNavContainer = $('<ul class="slide-pagination"></ul>');
			    _self.$items.each(function(i, item){
			        var controlItem = $('<li><a class="roki-color-link" href="#"></a></li>');
			        if (i == _self.current) controlItem.addClass('current');
			        _self.$controlNavItems.push(controlItem);
			        _self.$controlNavContainer.append(controlItem);
			    });
			    _self.$el.append(_self.$controlNavContainer);
			}
			
			this._initEvents();

		},
		_getRandTransform : function(boundaries) {
            var boundaries = $.extend(true, {}, this.options.boundaries, boundaries);
            
			return {
				rx : Math.floor( Math.random() * ( boundaries.rotateX.max - boundaries.rotateX.min + 1 ) + boundaries.rotateX.min ),
				ry : Math.floor( Math.random() * ( boundaries.rotateY.max - boundaries.rotateY.min + 1 ) + boundaries.rotateY.min ),
				rz : Math.floor( Math.random() * ( boundaries.rotateZ.max - boundaries.rotateZ.min + 1 ) + boundaries.rotateZ.min ),
				tx : Math.floor( Math.random() * ( boundaries.translateX.max - boundaries.translateX.min + 1 ) + boundaries.translateX.min ),
				ty : Math.floor( Math.random() * ( boundaries.translateY.max - boundaries.translateY.min + 1 ) + boundaries.translateY.min ),
				tz : Math.floor( Math.random() * ( boundaries.translateZ.max - boundaries.translateZ.min + 1 ) + boundaries.translateZ.min )
			};

		},
		_initEvents : function() {

			var self = this;

			this.$items.on( this.transEndEventName, function( event ) {

				self._onTransEnd( $( this ) );

			} );


			if( this.options.nextEl !== '' ) {
                this.options.nextEl = this.$el.find(this.options.nextEl);
				$( this.options.nextEl ).on( 'click.windy', function() {

					self.next();
					return false;

				} );

			}
			
			if( this.options.prevEl !== '' ) {
                this.options.prevEl = this.$el.find(this.options.prevEl);
				$( this.options.prevEl ).on( 'click.windy', function() {

					self.prev();
					return false;

				} );

			}
			
			if ( this.options.controlNav ) {
			    this.$controlNavContainer.children().on('click', function(e){
			        e.preventDefault();
			        
                    var indexDiff = self.current - $(this).index();
                    var indexDiffAbs = Math.abs(indexDiff);
                    
                    for (var i=0; i<indexDiffAbs; i++) {
                        var itemNumAppend = (indexDiff < 0) ? 1 : -1;
                        setTimeout(function(){
                            self.navigate(self.current + itemNumAppend);
                        }, i*.4*150);
                    }
			    });
			}
			
			$(window).resize(function(){
                // console.log(self.$items.eq(self.current).outerHeight());
                // self.$itemsContainer.height(self.$items.eq(self.current).children().children().outerHeight());
			});

		},
		_onTransEnd : function( el ) {

			el.removeClass( 'wi-move' );

			if( el.data( 'dir' ) === 'right' ) {

				var styleStart = {
					zIndex : 1,
					opacity : 1,
					position: 'relative'
				};

				if( this.support3d ) {

					styleStart.transform = this.resetTransformStr;

				}
				else if( this.supportTransitions ) {

					styleStart.left = 0;
					styleStart.top = 0;

				}

				el.hide().css( styleStart );

			} else {
                el.next().hide();
			}

		},
		// public method: shows item with index idx
		navigate : function( idx ) {

			var self = this,
				// current item
				$current = this.$items.eq( this.current ),
				// next item to be shown
				$next = this.$items.eq( idx ),
				// random transformation configuration
				randTranform = this._getRandTransform(),
				// the z-index is higher for the first items so that the ordering is correct if more items are moving at the same time
				styleEnd = {
					zIndex : this.itemsCount + 20 - idx,
					opacity : 0
				},
				styleStart = {
					opacity : 1
				};

			if( this.support3d ) {

				styleStart.transform = self.resetTransformStr;
				styleEnd.transform = 'translateX(' + randTranform.tx + 'px) translateY(' + randTranform.ty + 'px) translateZ(' + randTranform.tz + 'px) rotateX(' + randTranform.rx + 'deg) rotateY(' + randTranform.ry + 'deg) rotateZ(' + randTranform.rz + 'deg)';

			}
			else if( this.supportTransitions ) {

				styleStart.left = 0;
				styleStart.top = 0;
				styleEnd.left = randTranform.tx;
				styleEnd.top = randTranform.ty;

			}

			// if navigating to the right..
			if( idx > this.current ) {

				// if last step was to go to the left..
				if( this.dir === 'left') {

					// reset all z-indexes and hide items except the current
					this.$items.not( $current ).css( 'z-index', 1 ).hide();
				
				}
				
				this.dir = 'right';

				$current.addClass( 'wi-move' )
						.data( 'dir', 'right' )
						.css( styleEnd )
						.css( 'position', 'absolute' );
                $next.css( 'position', 'relative' );
                
				if( $next.hasClass( 'wi-move' ) ) {

					$next.removeClass( 'wi-move' );	
				
				}
				
				// apply styleStart just to make sure..
				$next.css( styleStart ).show();

				if( !this.supportTransitions ) {
			
					this._onTransEnd( $current );

				}
			
			}
			else if( idx < this.current ) {

				this.dir = 'left';

				$next.data( 'dir', 'left' ).css( styleEnd ).show().css({
				    position: 'relative'
				});
                $current.css({
                    position: 'absolute'
                });
				setTimeout( function() {

					$next.addClass( 'wi-move' )
						 .data( 'dir', 'left' )
						 .css( styleStart );

					if( !self.supportTransitions ) {
			
						self._onTransEnd( $next );
			
					}

				}, 20 );

			}

			this.current = idx;
			
			if ( this.options.controlNav ) {
			    this.$controlNavContainer.children('.current').removeClass('current');
			    this.$controlNavContainer.children().eq(idx).addClass('current');
			}

		},
		// public method: returns total number of items
		getItemsCount : function() {

			return this.itemsCount;

		},
		// public method: shows next item
		next : function() {

			if( this.current < this.itemsCount - 1 ) {
				
				var idx = this.current + 1;
				this.navigate( idx );

			} else {
			    var $current = this.$items.eq( this.current );
			    var tr = {};
			    var randTranform = this._getRandTransform(this.options.endBoundaries);
                tr.transform = 'translateX(' + randTranform.tx + 'px) translateY(' + randTranform.ty + 'px) translateZ(' + randTranform.tz + 'px) rotateX(' + randTranform.rx + 'deg) rotateY(' + randTranform.ry + 'deg) rotateZ(' + randTranform.rz + 'deg)';
                
                $current.addClass( 'wi-move' )
                  .css( tr );
                setTimeout(function(){
                    tr.transform = 'translateX(' + 0 + 'px) translateY(' + 0 + 'px) translateZ(' + 0 + 'px) rotateX(' + 0 + 'deg) rotateY(' + 0 + 'deg) rotateZ(' + 0 + 'deg)';
                    $current.addClass( 'wi-move' )
                      .css( tr );
                }, 200);
			}

		},
		// public method: shows previous item
		prev : function() {

			if( this.current > 0 ) {
				
				var idx = this.current - 1;
				this.navigate( idx );

			} else {
			    var $current = this.$items.eq( this.current );
			    var tr = {};
			    var randTranform = this._getRandTransform(this.options.endBoundaries);
                tr.transform = 'translateX(' + randTranform.tx + 'px) translateY(' + randTranform.ty + 'px) translateZ(' + randTranform.tz + 'px) rotateX(' + randTranform.rx + 'deg) rotateY(' + randTranform.ry + 'deg) rotateZ(' + randTranform.rz + 'deg)';

                $current.addClass( 'wi-move' )
                  .css( tr );
                setTimeout(function(){
                    tr.transform = 'translateX(' + 0 + 'px) translateY(' + 0 + 'px) translateZ(' + 0 + 'px) rotateX(' + 0 + 'deg) rotateY(' + 0 + 'deg) rotateZ(' + 0 + 'deg)';
                    $current.addClass( 'wi-move' )
                      .css( tr );
                }, 200);
			}

		}

	};
	
	var logError = function( message ) {

		if ( window.console ) {

			window.console.error( message );
		
		}

	};
	
	$.fn.windy = function( options ) {

		var instance = $.data( this, 'windy' );
		
		if ( typeof options === 'string' ) {
			
			var args = Array.prototype.slice.call( arguments, 1 );
			
			this.each(function() {
			
				if ( !instance ) {

					logError( "cannot call methods on windy prior to initialization; " +
					"attempted to call method '" + options + "'" );
					return;
				
				}
				
				if ( !$.isFunction( instance[options] ) || options.charAt(0) === "_" ) {

					logError( "no such method '" + options + "' for windy instance" );
					return;
				
				}
				
				instance[ options ].apply( instance, args );
			
			});
		
		} 
		else {
		
			this.each(function() {
				
				if ( instance ) {

					instance._init();
				
				}
				else {

					instance = $.data( this, 'windy', new $.Windy( options, this ) );
				
				}

			});
		
		}
		
		return instance;
		
	};
	
} )( jQuery, window );