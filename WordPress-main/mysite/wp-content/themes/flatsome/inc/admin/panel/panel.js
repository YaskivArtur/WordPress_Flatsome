jQuery(function ($) {
  var errorMessage = window.flatsomePanelOptions.errorMessage

  $('.flatsome-registration-form').each(function (i, el) {
    var $code = $('.flatsome-registration-form__code', el)
    var $selector = $('.flatsome-token-migrator__select', el)
    var $errors = $('.flatsome-token-migrator__errors', el)
    var $spinner = $('.spinner')
    var isFetched = false

    $selector.on('click', function () {
      if (isFetched) return

      $spinner.addClass('is-active')

      $.getJSON(window.ajaxurl, {
        action: 'flatsome_purchase_codes'
      })
        .then(function (res) {
          if (Array.isArray(res.available)) {
            $errors.empty()
            $selector.append(
              res.available.map(function (data) {
                var purchasedAt = wp.date.date('F j, Y', data.purchasedAt)
                return $('<option value="' + data.id + '">' + data.code + ' (' + purchasedAt + ')</option>')
              })
            )
            $selector.append($('<option value="">Enter another purchase code</option>'))
          } else {
            $errors.html(
              '<div class="notice notice-error notice-alt inline" style="display:block!important;margin-bottom:15px!important"><p>' + (res.message || errorMessage) + '</p></div>'
            )
          }

          isFetched = Array.isArray(res.available)
          $spinner.removeClass('is-active')
        })
        .catch(function (err) {
          console.error(err)
          $errors.html(
            '<div class="notice notice-error notice-alt inline" style="display:block!important;margin-bottom:15px!important"><p>' + (err.message || errorMessage) + '</p></div>'
          )
          isFetched = false
          $spinner.removeClass('is-active')
        })
    })

    $selector.on('change', function (event) {
      if (event.target.value) {
        $code.slideUp(250)
      } else {
        $code.slideDown(250, function () {
          $code.find('input').trigger('focus')
        })
      }
    })
  })
})
