import 'lazysizes'

import Application from '~/components/app'

const lbSetExtraPropsToBlockType = (props, blockType, attributes) => {
    const notDefined = (typeof props.className === 'undefined' || !props.className) ? true : false

    if (
        blockType.name === 'core/heading'
        || blockType.name === 'core/paragraph'
        || blockType.name === 'core/list'
        || blockType.name === 'core/image'
        || blockType.name === 'core/file'
    ) {
        return Object.assign(props, {
            // className: notDefined ? `post__heading-${props.tagName}` : `post__heading-${props.tagName} ${props.className}`,
            className: notDefined ? `lb-post-content` : `lb-post-content ${props.className}`,
        })
    }
    else if (
        blockType.name === 'core/embed'
        || blockType.name === 'core/table'
    ) {
        return Object.assign(props, {
            className: notDefined ? `lb-custom-table container` : `lb-custom-table container ${props.className}`,
        })
    }

    return props
}

window.addEventListener('load', (event) => {
    let postType = null
    const body = document.querySelector('body')

    body.classList.forEach((attr) => {
        if ('post-type-' === attr.slice(0, 10)) {
            postType = attr.split('post-type-')
            postType = postType[postType.length - 1]

            return
        }
    })

    if (postType != 'lb-job') {
        wp.hooks.addFilter(
            'blocks.getSaveContent.extraProps',
            'labo-suisse-theme/block-filters',
            lbSetExtraPropsToBlockType
        )
    }

    // Disable default Separator block
    wp.blocks.unregisterBlockType('core/separator')

    new Application()
})
