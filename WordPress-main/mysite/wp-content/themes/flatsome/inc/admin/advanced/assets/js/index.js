/* eslint-disable no-undef, no-var */

wp.domReady(function () {
  var button = document.getElementById('flatsome-instagram-access-token-submit')
  var token = document.getElementById('flatsome-instagram-access-token-value')
  var errors = document.getElementById('flatsome-instagram-errors')

  var abortController = null

  if (button) {
    button.addEventListener('click', function () {
      addInstagramAccount()
    })
  }

  if (token) {
    token.addEventListener('keydown', function (event) {
      if (event.key === 'Enter') {
        event.stopPropagation()
        event.preventDefault()
        addInstagramAccount()
      }
    })
  }

  function addInstagramAccount () {
    if (abortController) abortController.abort()
    if (errors) errors.innerHTML = ''

    if (!token.value) return

    var data = new FormData()

    abortController = new AbortController()

    data.append('action', 'flatsome_validate_instagram_access_token')
    data.append('access_token', token.value)
    data.append('nonce', window.flatsomeAdvancedData.nonce)

    setEditable(false)

    fetch(window.ajaxurl, { method: 'POST', body: data, signal: abortController.signal })
      .then(function (response) {
        return response.json()
      })
      .then(function (response) {
        if (response.success) {
          addAccount(response.data)
          token.value = ''
        } else {
          showError('An error occured while adding the account: ' + response.data)
        }
      })
      .catch(showError)
      .finally(function () {
        setEditable(true)
      })
  }

  function setEditable (editable) {
    token.readOnly = !editable
    if (button) {
      button.classList.toggle('is-busy', !editable)
    }
  }

  function showError (error) {
    if (error.name === 'AbortError') return
    if (errors) {
      errors.innerHTML = '<div class="notice notice-error inline"><p>' + error + '</p></div>'
    } else {
      console.error(error)
    }
  }

  function addAccount (data) {
    var tbody = document.querySelector('.flatsome-instagram-accounts__body')
    var tr = document.querySelector('.instagram-account--' + data.username)
    var id = wp.escapeHtml.escapeAttribute(data.id)
    var username = wp.escapeHtml.escapeAttribute(data.username)
    var accessToken = wp.escapeHtml.escapeAttribute(data.access_token)
    var usernameDisplay = wp.escapeHtml.escapeHTML(data.username)
    var expiresAt = parseInt(data.expires_at, 10)

    var html = [
      '<tr class="instagram-account instagram-account-updated instagram-account--' + username + '">',
      '<td>',
      '<input type="hidden" name="facebook_accounts[' + username + '][type]" value="instagram">',
      '<input type="hidden" name="facebook_accounts[' + username + '][id]" value="' + id + '">',
      '<input type="hidden" name="facebook_accounts[' + username + '][username]" value="' + username + '">',
      '<input type="hidden" name="facebook_accounts[' + username + '][access_token]" value="' + accessToken + '">',
      '<input type="hidden" name="facebook_accounts[' + username + '][expires_at]" value="' + expiresAt + '">',
      '<a target="_blank" href="https://www.instagram.com/' + username + '/" rel="noopener noreferrer">' + usernameDisplay + '</a>',
      '</td>',
      '<td align="right">',
      '<button type="button" class="button button-small" onclick="jQuery(this).closest(\'.instagram-account\').remove()">Remove</button>',
      '</td>',
      '</tr>'
    ].join('\n')

    if (tr) {
      tr.outerHTML = html
    } else {
      tbody.innerHTML += html
    }
  }
})
