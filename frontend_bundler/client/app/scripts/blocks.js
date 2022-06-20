import 'lazysizes'

import Application from '~/components/app'

const lbSetExtraPropsToBlockType = (props, blockType, attributes) => {
    const notDefined = (typeof props.className === 'undefined' || !props.className) ? true : false

    console.log(blockType.name);

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

    return props
}

window.addEventListener('load', (event) => {
    wp.hooks.addFilter(
        'blocks.getSaveContent.extraProps',
        'labo-suisse-theme/block-filters',
        lbSetExtraPropsToBlockType
    )

    // Disable default Separator block
    wp.blocks.unregisterBlockType('core/separator')

    new Application()
})
