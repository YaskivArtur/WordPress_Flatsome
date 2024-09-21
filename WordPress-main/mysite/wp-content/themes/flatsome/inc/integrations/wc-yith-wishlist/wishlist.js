Flatsome.behavior('wishlist', {
  attach: function (context) {
    jQuery('.wishlist-button', context).each(function (index, element) {
      'use strict'

      jQuery(element).on('click', function (e) {
        var $this = jQuery(this)
        // Browse wishlist
        if ($this.parent().find('.yith-wcwl-wishlistexistsbrowse, .yith-wcwl-wishlistaddedbrowse').length) {
          window.location.href = $this.parent().find('.yith-wcwl-wishlistexistsbrowse a, .yith-wcwl-wishlistaddedbrowse a').attr('href')
          return
        }
        $this.addClass('loading')
        // Delete or add item (only one of both is present).
        $this.parent().find('.delete_item').click()
        $this.parent().find('.add_to_wishlist').click()

        e.preventDefault()
      })
    })

    markAdded()
  }
})

jQuery(document).ready(function () {
  var flatsomeAddToWishlist = function () {
    jQuery('.wishlist-button').removeClass('loading')
    markAdded()

    jQuery.ajax({
      beforeSend: function () {

      },
      complete: function () {

      },
      data: {
        action: 'flatsome_update_wishlist_count',
      },
      success: function (data) {
        var $icon = jQuery('i.wishlist-icon')
        $icon.addClass('added')
        if (data == 0) {
          $icon.removeAttr('data-icon-label')
        }
        else if (data == 1) {
          $icon.attr('data-icon-label', '1')
        }
        else {
          $icon.attr('data-icon-label', data)
        }
        setTimeout(function () {
          $icon.removeClass('added')
        }, 500)
      },

      url: yith_wcwl_l10n.ajax_url,
    })
  }

  jQuery('body').on('added_to_wishlist removed_from_wishlist', flatsomeAddToWishlist)
})

function markAdded () {
  jQuery('.wishlist-icon').each(function () {
    var $this = jQuery(this)
    if ($this.find('.yith-wcwl-wishlistexistsbrowse, .yith-wcwl-wishlistaddedbrowse').length) {
      $this.find('.wishlist-button').addClass('wishlist-added')
    }
  })
}
