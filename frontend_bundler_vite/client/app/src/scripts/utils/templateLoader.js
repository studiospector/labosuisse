import Twig from 'twig'

function getTemplate(path) {
    return new Promise(async (resolve, reject) => {
        if (!path) {
            reject('[TemplateLoader] missing template path')
            return
        }

        Twig.twig({
            id: path,
            href: '/wp-content/themes/caffeina-theme/views/' + path,
            namespaces: { 'PathViews': '/wp-content/themes/caffeina-theme/views/' },
            allowInlineIncludes: true,
            load: (template) => {
                resolve(template)
            }
        })
    })
}

export default function templateLoader(path) {
    return new Promise(async (resolve, reject) => {
        const [template] = await Promise.all([
            getTemplate(path)
        ])

        resolve(template)
    })
}


