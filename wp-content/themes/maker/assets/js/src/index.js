import $ from 'jquery'

// Logo

const $body = $('body')
const $footer = $('.mkr-footer')
const $nextEntry = $('.mkr-next-entry')
const stickyClass = 'mkr--sticky-logo'

const footerObserver = new IntersectionObserver(
	([entry]) =>
		entry.isIntersecting
			? $body.addClass(stickyClass)
			: $body.removeClass(stickyClass),
	{ threshold: 0 }
)
footerObserver.observe(
	$nextEntry.length > 0 ? $nextEntry.get(0) : $footer.get(0)
)

if ($nextEntry.length > 0) {
	$('.mkr-footer__logo')
		.remove()
		.appendTo($nextEntry)
}

// Menu

const $toggle = $('.mkr-nav__toggle')
const $work = $('.mkr-nav').find(
	'a:contains("Work"), a:contains("work"), a:contains("WORK")'
)
const showMenuMod = 'mkr--show-menu'

$toggle.add($work).on('click', event => {
	event.preventDefault()
	$body.toggleClass(showMenuMod)
})

// Case

const $thumbnail = $('.mkr-case__thumbnail')
const showArrowMod = 'mkr-case__thumbnail--show-arrow'

if ($thumbnail.length) {
	const scrolledClass = 'mkr--scrolled'

	const thumbObserver = new IntersectionObserver(
		([entry]) =>
			!entry.isIntersecting
				? $body.addClass(scrolledClass)
				: $body.removeClass(scrolledClass),
		{ threshold: 0.95 }
	)
	thumbObserver.observe($thumbnail.get(0))

	setTimeout(() => $thumbnail.addClass(showArrowMod), 2000)
}

// Accordion

const expandedMod = 'mkr-accordion--active'

$('dt').on('click', function() {
	$(this)
		.attr('aria-expanded', (_, attr) =>
			attr === 'true' ? 'false' : 'true'
		)
		.next('dd')
		.attr('aria-hidden', (_, attr) => (attr === 'true' ? 'false' : 'true'))
	$(this)
		.parent()
		.toggleClass(expandedMod)
})

// Press

$('.mkr-press').slick({
	arrows: false,
	autoplay: true,
	autoplaySpeed: 5000,
	fade: true,
	infinite: true,
	slidesToScroll: 1,
	slidesToShow: 1,
	mobileFirst: true,
	responsive: [
		{
			breakpoint: 600,
			settings: { fade: false, slidesToScroll: 3, slidesToShow: 3 },
		},
		{
			breakpoint: 1860,
			settings: { fade: false, slidesToScroll: 5, slidesToShow: 5 },
		},
	],
})

// Carousel: Slick

const $slickCarousel = $('.wp-block-gallery.is-style-carousel-slick')
const slideFullClass = 'blocks-gallery-item--full'
const slideLeftClass = 'blocks-gallery-item--left'
const slideBottomClass = 'blocks-gallery-item--bottom-off'
const slideCenterClass = 'blocks-gallery-item--center-off'
const altLogoClass = 'mkr--alt-logo'
const altMenuClass = 'mkr--alt-menu'

const getViewportWidth = () =>
	Math.max(document.documentElement.clientWidth, window.innerWidth, 0)
const isMobile = () => getViewportWidth() < 600
const isTablet = () => getViewportWidth() >= 600 && getViewportWidth() < 1200
const isDesktop = () => getViewportWidth() >= 1200

$slickCarousel
	.find('> .blocks-gallery-grid')
	.on('beforeChange.mkr', (_, slick, __, nextSlide) => {
		const $slide = slick
			? $(slick.$slides[nextSlide])
			: $slickCarousel.find('.blocks-gallery-item:first')

		if (isMobile()) {
			/* eslint-disable no-unused-expressions */
			$slide.hasClass(slideFullClass)
				? $body.addClass(altMenuClass)
				: $body.removeClass(altMenuClass)

			$slide.hasClass(slideFullClass) ||
			$slide.hasClass(slideBottomClass) ||
			$slide.hasClass(slideCenterClass)
				? $body.addClass(altLogoClass)
				: $body.removeClass(altLogoClass)
			/* eslint-enable no-unused-expressions */
		} else if (isTablet()) {
			/* eslint-disable no-unused-expressions */
			$slide.hasClass(slideFullClass)
				? $body.addClass(altMenuClass)
				: $body.removeClass(altMenuClass)

			$slide.hasClass(slideFullClass) || $slide.hasClass(slideBottomClass)
				? $body.addClass(altLogoClass)
				: $body.removeClass(altLogoClass)
			/* eslint-enable no-unused-expressions */
		} else if (isDesktop()) {
			/* eslint-disable no-unused-expressions */
			$slide.hasClass(slideFullClass) || $slide.hasClass(slideLeftClass)
				? $body.addClass(altMenuClass)
				: $body.removeClass(altMenuClass)

			$slide.hasClass(slideFullClass) ||
			$slide.hasClass(slideLeftClass) ||
			$slide.hasClass(slideBottomClass)
				? $body.addClass(altLogoClass)
				: $body.removeClass(altLogoClass)
			/* eslint-enable no-unused-expressions */
		}
	})
	.trigger('beforeChange.mkr')
	.slick({
		arrows: false,
		autoplay: true,
		autoplaySpeed: 5000,
		fade: true,
		pauseOnFocus: false,
		pauseOnHover: false,
		slidesToScroll: 1,
		slidesToShow: 1,
	})

const $homeElements = $(
	'.home .mkr-main > *:gt(1), .home .mkr-main ~ .mkr-footer'
)

$homeElements.css('opacity', 0)

if ($homeElements.add($slickCarousel).length > 0) {
	$(document).on('scroll.mkr click.mkr', () => {
		$(document).scrollTop(0)
		$body
			.removeClass(`${altMenuClass} ${altLogoClass}`)
			.css('overflow-y', 'hidden')
		$slickCarousel.fadeOut('slow', function() {
			$(this)
				.find('> .blocks-gallery-grid')
				.slick('unslick')
			$homeElements.delay('slow').animate({ opacity: 1 }, 'slow', () => {
				$(document).off('scroll.mkr click.mkr')
				$body.css('overflow-y', 'auto')
			})
		})
	})
}

// Carousel: Default

const $defaultCarousel = $(
	'.wp-block-gallery.is-style-carousel-default > .blocks-gallery-grid'
)
const defaultCarouselArgs = {
	arrows: true,
	autoplay: false,
	fade: true,
	pauseOnFocus: false,
	pauseOnHover: false,
	slidesToScroll: 1,
	slidesToShow: 1,
	adaptiveHeight: true,
}

let captionText = null

function galleryExtraMarkup(_, $slick) {
	const { $slider } = $slick
	const hasCaption = $slider.next().length > 0
	if (!hasCaption) {
		$('<figcaption class="blocks-gallery-caption" />').insertAfter($slider)
	}
	$slider.append(
		'<div class="wp-block-gallery__prev" /><div class="wp-block-gallery__next" />'
	)
}

function setupStickyArrows(_, $slick) {
	const arrowsClass = 'mkr--sticky-arrows'
	const gallery = $slick.$list.get(0)
	let isSticky = false

	$(window).on('scroll.mkr', () => {
		const { bottom } = gallery.getBoundingClientRect()
		const offset = 20
		const viewportHeight =
			window.innerHeight || document.documentElement.clientHeight
		if (!isSticky && bottom - offset < viewportHeight / 2) {
			isSticky = true
			$body.addClass(arrowsClass)
		} else if (isSticky && bottom - offset > viewportHeight / 2) {
			isSticky = false
			$body.removeClass(arrowsClass)
		}
	})
}

function galleryNumbers(_, $slick) {
	const { $slider, slideCount, currentSlide } = $slick
	const caption = $slider.next()
	if (captionText === null) {
		captionText = caption.html()
	}
	const text = [captionText, `${currentSlide + 1} / ${slideCount}`]
	caption.html(text.filter(Boolean).join(' — '))
}

function galleryPreview(_, $slick) {
	const { $slider, $slides, slideCount, currentSlide } = $slick
	const $prevFigure = $slides
		.eq(currentSlide <= 0 ? slideCount - 1 : currentSlide - 1)
		.children()
	const $nextFigure = $slides
		.eq(currentSlide >= slideCount - 1 ? 0 : currentSlide + 1)
		.children()
	$slider.find('.wp-block-gallery__prev').html($prevFigure.clone())
	$slider.find('.wp-block-gallery__next').html($nextFigure.clone())
}

function disableArrows() {
	$('.slick-prev,.slick-next').attr('disabled', true)
}

function enableArrows() {
	setTimeout(() => $('.slick-prev,.slick-next').removeAttr('disabled'), 1000)
}

$defaultCarousel
	.on('init.mkr', galleryExtraMarkup)
	.on('init.mkr', setupStickyArrows)
	.on('init.mkr afterChange.mkr', galleryNumbers)
	.on('init.mkr afterChange.mkr', galleryPreview)
	.on('beforeChange.mkr', disableArrows)
	.on('afterChange.mkr', enableArrows)
	.slick(defaultCarouselArgs)
