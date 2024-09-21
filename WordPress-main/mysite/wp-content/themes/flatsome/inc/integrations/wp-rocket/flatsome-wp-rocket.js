/* eslint-disable semi, no-var */
Flatsome.behavior('wp-rocket-lazy-load-packery', {
  attach: function (context) {
    var $lazyLoad = jQuery('.has-packery .lazy-load', context)

    if (!$lazyLoad.length) return

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.intersectionRatio > 0) {
          observer.unobserve(entry.target)
          jQuery(entry.target).imagesLoaded(function () {
            jQuery('.has-packery').packery('layout')
          })
        }
      })
    }, {
      rootMargin: '0px',
      threshold: 0.1
    })

    $lazyLoad.each(function (i, el) {
      observer.observe(el)
    })
  }
});
