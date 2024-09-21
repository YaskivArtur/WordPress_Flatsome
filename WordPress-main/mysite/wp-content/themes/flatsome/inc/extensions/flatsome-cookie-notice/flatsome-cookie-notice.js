/* global flatsomeVars, cookie */
jQuery(document).ready(function () {
  'use strict'
  var $notice = jQuery('.flatsome-cookies')
  var cookieId = 'flatsome_cookie_notice'
  var cookieValue = flatsomeVars.options.cookie_notice_version

  if (cookie(cookieId) !== cookieValue) {
    setTimeout(function () {
      $notice.addClass('flatsome-cookies--active')

      $notice.on('click', '.flatsome-cookies__accept-btn', function (e) {
        e.preventDefault()
        $notice.removeClass('flatsome-cookies--active').addClass('flatsome-cookies--inactive')
        // set cookie
        cookie(cookieId, cookieValue, 365)
      })
    }, 2500)
  }
})
