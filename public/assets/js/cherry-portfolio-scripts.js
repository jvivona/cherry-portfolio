/**
 * Cherry_Portfolio
 */
(function( $ ) {

	'use strict';

	CHERRY_API.utilites.namespace( 'cherry_portfolio' );
	CHERRY_API.cherry_portfolio = {
		init: function () {
			var self = this;

			if ( CHERRY_API.status.document_ready ) {
				self.render();
			} else {
				CHERRY_API.variable.$document.on( 'ready', self.render() );
			}
		},
		render: function () {
			var self = this;

			jQuery( '.portfolio-wrap' ).cherryPortfolioLayoutPlugin();

			jQuery( '.swiper-container' ).each( function() {
				var swiper,
					slidesPerView = parseFloat( jQuery( this ).data( 'slides-per-view' ) ),
					slidesPerColumn = parseFloat( jQuery(this).data('slides-per-column') ),
					spaceBetweenSlides = parseFloat( jQuery(this).data('space-between-slides') ),
					swiperLoop = jQuery(this).data('swiper-loop'),
					durationSpeed = parseFloat( jQuery(this).data('duration-speed') ),
					freeMode = jQuery(this).data('free-mode'),
					grabCursor = jQuery(this).data('grab-cursor'),
					mouseWheel = jQuery(this).data('mouse-wheel'),
					swiperEffect = jQuery(this).data('swiper-effect'),
					uniqId = jQuery(this).data('uniq-id');

				switch ( swiperEffect ) {

					case 'swiper-effect-slide':
						swiperEffect = 'slide';
						break

					case 'swiper-effect-fade':
						swiperEffect = 'fade';
						slidesPerView = 1;
						slidesPerColumn = 1;
						break

					case 'swiper-effect-cube':
						swiperEffect = 'cube';
						break

					case 'swiper-effect-coverflow':
						swiperEffect = 'coverflow';
						break
				}

				swiper = new Swiper( $( '#' + uniqId ), {
						speed: durationSpeed,
						slidesPerView: slidesPerView,
						spaceBetween: spaceBetweenSlides,
						grabCursor: grabCursor,
						loop: swiperLoop,
						freeMode: freeMode,
						nextButton: '#' + uniqId + '-next',
						prevButton: '#' + uniqId + '-prev',
						pagination: '#' + uniqId + '-pagination',
						paginationClickable: true,
						mousewheelControl: mouseWheel,
						slidesPerColumn: slidesPerColumn,
						effect: swiperEffect,
					}
				);
			})

			self.singleGalleryInit();
			self.magnificPopapInit();
		},
		magnificPopapInit: function () {

			if ( $( '.magnific-popup-link')[0] ) {
				$( '.magnific-popup-link' ).magnificPopup({
					type: 'image',
					removalDelay: 500,
					callbacks: {
						beforeOpen: function() {
							this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
							this.st.mainClass = 'mfp-zoom-in';
						}
					},
					closeOnContentClick: true,
					midClick: true
				});
			}

			if ( $( '.magnific-popup-zoom')[0] ) {
				$( '.magnific-popup-zoom' ).magnificPopup({
					type: 'image'
				});
			}

			if ( $( '.thumbnailset' )[0] ) {
				$( '.thumbnailset' ).each(function() {
					$( this ).magnificPopup({
						delegate: 'a',
						type: 'image',
						removalDelay: 500,
						callbacks: {
							beforeOpen: function() {
								this.st.image.markup = this.st.image.markup.replace( 'mfp-figure', 'mfp-figure mfp-with-anim' );
								this.st.mainClass = this.st.el.attr( 'data-effect' );
							}
						},
						closeOnContentClick: true,
						midClick: true,
						gallery: {
							enabled: true
						}
					});
				});
			}

			if ( $( '.gallery-list' )[0] ) {
				$( '.gallery-list' ).each( function() {
					$( this ).magnificPopup({
						delegate: 'a',
						type: 'image',
						removalDelay: 500,
						callbacks: {
							beforeOpen: function() {
								this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
								this.st.mainClass = this.st.el.attr('data-effect');
							}
						},
						closeOnContentClick: true,
						midClick: true,
						gallery: {
							enabled: true
						}
					});
				});
			}
		},
		singleGalleryInit: function () {
			var $masonryList = $( '.masonry-list' ),
				$gridList = $( '.grid-list' ),
				$gridItems = $( '.grid-item', $gridList ),
				$justifiedList = $( '.justified-list' ),
				$justifiedItems = $( '.justified-item', $justifiedList ),
				masonryColumns = parseInt( $masonryList.data( 'columns' ) ),
				masonryGutter = parseInt( $masonryList.data( 'gutter' ) );

			if ( $masonryList[0] ) {
				$masonryList.css({
					'column-count': masonryColumns,
					'-webkit-column-count': masonryColumns,
					'-moz-column-count': masonryColumns,
					'column-gap': masonryGutter,
					'-webkit-column-gap': masonryGutter,
					'-moz-column-gap': masonryGutter,
				});
			}

			if ( $justifiedList[0] ) {
				$justifiedItems.each( function() {
					var $this = $(this),
						imageSrc = $this.data('image-src'),
						imageWidth = $this.data('image-width'),
						imageHeight = $this.data('image-height'),
						imageRatio = $this.data('image-ratio'),
						flexValue = Math.round( image_ratio * 100 ),
						newWidth = Math.round( $this.height() * image_ratio );

					$this.css({
						'width': newWidth + 'px',
						'-webkit-flex': flexValue + ' 1 ' + newWidth + 'px',
						'-ms-flex': flexValue + ' 1 ' + newWidth + 'px',
						'flex': flexValue + ' 1 ' + newWidth + 'px'
					});

					$('.justified-image', $this).css({
						'background-image': 'url(' + imageSrc + ')'
					})
				})
			}
		}
	}
	CHERRY_API.cherry_portfolio.init();
}(jQuery));
