jQuery(function ($) {
  $(document).on('yith-wcan-ajax-filtered', function () {
    var $container = jQuery('.shop-container')

    Flatsome.attach('lazy-load-images', $container)
    Flatsome.attach('quick-view', $container)
    Flatsome.attach('tooltips', $container)
    Flatsome.attach('add-qty', $container)
    Flatsome.attach('wishlist', $container)
    Flatsome.attach('equalize-box', $container)
  })
})
