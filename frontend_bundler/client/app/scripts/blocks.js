import 'lazysizes'

import Application from '~/components/app'

const lbSetExtraPropsToBlockType = (props, blockType, attributes) => {
    const notDefined = (typeof props.className === 'undefined' || !props.className) ? true : false

    if (blockType.name === 'core/heading') {
        return Object.assign(props, {
            // className: notDefined ? `post__heading-${props.tagName}` : `post__heading-${props.tagName} ${props.className}`,
            className: notDefined ? `lb-post-content` : `lb-post-content ${props.className}`,
        })
    }

    if (blockType.name === 'core/paragraph') {
        return Object.assign(props, {
            // className: notDefined ? 'post__paragraph' : `post__paragraph ${props.className}`,
            className: notDefined ? 'lb-post-content' : `lb-post-content ${props.className}`,
        })
    }

    return props
}

window.addEventListener('load', (event) => {
    wp.hooks.addFilter(
        'blocks.getSaveContent.extraProps',
        'your-namespace/block-filters',
        lbSetExtraPropsToBlockType
    )

    // Disable default Separator block
    wp.blocks.unregisterBlockType('core/separator')

    new Application()
})

// function init() {
//   console.log('initAPP')
//   return new Application()
// }

// if (!window.APP) window.APP = {}
// window.APP.init = init
