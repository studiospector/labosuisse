import { qs } from '@okiba/dom';

const getHeaderFullHeight = el => {
    const header = qs('.lb-header')
    const headerProduct = qs('.lb-header-sticky-product')

    const fullHeaderHeight = (header ? header.getBoundingClientRect().height : 0) + (headerProduct ? headerProduct.getBoundingClientRect().height : 0)

    return fullHeaderHeight
}

export {
    getHeaderFullHeight
}
