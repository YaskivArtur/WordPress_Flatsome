/* global wp */
(function () {
  var el = wp.element.createElement
  var useDispatch = wp.data.useDispatch
  var InnerBlocks = wp.blockEditor.InnerBlocks
  var registerBlockType = wp.blocks.registerBlockType

  function FlatsomeIcon (props) {
    return el(
      'svg',
      {
        width: props.width || 24,
        height: props.height || 24,
        viewBox: '0 0 33 32',
        fill: 'none',
        xmlns: 'http://www.w3.org/2000/svg'
      },
      [
        el('path', { key: 0, fill: 'currentColor', d: 'M15.9433 4.47626L18.474 7.00169L7.01624 18.4357L4.48556 15.9103L15.9433 4.47626ZM15.9433 0L0 15.9103L7.01624 22.912L22.9596 7.00169L15.9433 0Z' }),
        el('path', { key: 1, fill: 'currentColor', d: 'M16.128 22.83L18.4798 25.1769L16.128 27.5239L13.7761 25.1769L16.128 22.83ZM16.128 18.3537L9.29039 25.1766L16.128 32L22.9655 25.1766L16.128 18.3532V18.3537Z' }),
        el('path', { key: 2, fill: 'currentColor', fillOpacity: '0.6', d: 'M25.229 13.7475L27.5808 16.0945L25.229 18.4414L22.8775 16.0946L25.2293 13.7477L25.229 13.7475ZM25.2293 9.27141L18.3914 16.0946L25.229 22.918L32.0666 16.0946L25.229 9.27124L25.2293 9.27141Z' })
      ]
    )
  }

  function gotoUxBuilder () {
    if (window.flatsome_gutenberg) {
      window.top.location.href = window.flatsome_gutenberg.edit_button.url
    }
  }

  function DefaultBlockEdit () {
    var editor = useDispatch('core/editor')

    return el(
      wp.components.Placeholder,
      {
        icon: el(FlatsomeIcon, { width: 21, height: 21 }),
        label: 'UX Builder content',
        instructions: 'This content can only be edited in UX Builder.'
      },
      el(
        wp.components.Button,
        {
          variant: 'secondary',
          onFocus: function (e) {
            e.stopPropagation()
          },
          onClick: function () {
            editor.savePost().then(gotoUxBuilder)
          }
        },
        'Edit with UX Builder'
      )
    )
  }

  function DefaultBlockSaveInnerBlocks () {
    return el(InnerBlocks.Content)
  }

  function DefaultBlockSaveRawHTML (props) {
    return el(wp.element.RawHTML, {}, props.attributes.content)
  }

  for (var name in window.flatsomeBlockSettings) {
    if (window.flatsomeBlockSettings.hasOwnProperty(name)) {
      var data = window.flatsomeBlockSettings[name]

      registerBlockType(name, {
        title: 'UX Builder content',
        description: 'This block contains content created with UX Builder.',
        category: 'common',
        attributes: data.attributes,
        supports: Object.assign({}, {
          html: false,
          align: false,
          anchor: false,
          reusable: false,
          inserter: false,
          alignWide: false,
          className: false,
          customClassName: false,
          defaultStylePicker: false
        }, data.supports),
        icon: FlatsomeIcon,
        edit: DefaultBlockEdit,
        save: name === 'flatsome/uxbuilder'
          ? DefaultBlockSaveRawHTML
          : DefaultBlockSaveInnerBlocks
      })
    }
  }
}())
