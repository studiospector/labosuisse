# Flusso 🚰

* [Sviluppo](#sviluppo)
* [Ambienti](#ambienti)
* [Branches](#branches)
* [Release Flow](#release-flow)
* [Product Category Hierarchy](#product-category-hierarchy)
* [Workflow Blocks and Components Development](#workflow-blocks-and-components-development)
* [WP Plugins](#wp-plugins)
* [Images Guidelines](#images-guidelines)

<br><br>

## Sviluppo

Worklow di sviluppo:

### Reverse proxy

Assicurati di avere tirato su il reverse proxy globale che serve per risolvere i domini in ambiente locale.

Tramite `nador-cli` inizializzare il load balancer

```sh
nador setup
```

Per poi tirarlo su semplicemente con il comando

```sh
nador loadbalancer
```

### Avviare container Docker

Una volta creato il progetto con [nador-cli](https://git.caffeina.co/devops/nador-cli) puoi avviare l'ambiente di sviluppo con:

```sh
./cmd/dev.sh --build
```
oppure in modalità detached
```sh
./cmd/dev.sh -d
```

Questo avvierà l'intero stack di sviluppo definito dai servizi scelti durante la creazione del progetto.

### Installare dipendenze via `npm`

> **Tip**: per lanciare comandi `npm` puoi usare il comando:

```sh
./cmd/npm.sh <service> <args>
```

<br><br>

## Ambienti

| Ambiente    | Server      | Branch      | Link                                                                                                                         |
| ----------- | ----------- | ----------- | ---------------------------------------------------------------------------------------------------------------------------- |
| PROD        | AWS         | `master`    |                                                                                                                              |
| PREPROD     | AWS         | `preprod`   | [https://labosuisse.caffeina.host/caffadmin](https://labosuisse.caffeina.host/caffadmin)                                     |
| DEV         | N3          | `develop`   | [https://develop.labo-website-2021.n3.caffeina.host/caffadmin](https://develop.labo-website-2021.n3.caffeina.host/caffadmin) |
| LOCAL       |             |             | [http://labo-website-2021.localhost/caffadmin](http://labo-website-2021.localhost/caffadmin)                                 |

<br><br>

## Branches

- MASTER: deploys on *aws production*
- PREPROD: deploys on *aws preproduction*
- DEVELOP: deploys on *n3 stage*

Branch produzione: master\
Hotfix partono da: master\
Feature partono da: preprod\
Release partono da: preprod

Develop: branch che contiene feature non complete e altre cose WIP. Questo branch è "sporco", **NON** deve essere mergiato dentro altri branch e può essere eliminato in ogni momento perché il codice che contiene esiste anche in altri branch (features/master/preprod).

#### Branch Protections:

Protezione branch abilitata per:
- master
- preprod

<br><br>

## Release Flow

1. Crea nuovo branch `release/` partendo da `preprod`
2. Fai fix dell'ultimo minuto (eg bump del numero di versione)
3. Crea tag di versione
4. Chiudi la release facendo merge in `preprod` e `master`
5. Cancella branch di release
6. _bonus_: usa `git flow release start` e  `git flow release finish` per ottimizzare il processo dei punti precedenti.

### Q&A

#### Posso fare il merge della mia feature dentro `preprod`?
> Sì, ma solo se è finita ed è stata testata (dai tester e/o PM)

#### Posso fare il merge di `preprod` nella mia feature?
> Sì.

#### La mia feature è terminata e deve essere testata:
> Fai il merge del branch dentro a `develop` e sposta il task in `Ready for Test` nella kanban.

#### Posso fare il merge di `develop` nel mio branch?
> NO.

#### Posso fare il merge di `develop` in `master`?
> ASSOLUTAMENTE NO.

#### Posso fare il merge di `develop` in `preprod`?
> NO.

<br><br>

## Product Category Hierarchy

Le categorie dei prodotti hanno una suddivisione per livelli:
1. Macro
2. Zona
3. Esigenza
4. Tipologia di Prodotto

```
Viso [MACRO]
|
└--- Labbra [ZONA]
│   │
│   └--- Anti-rughe [ESIGENZA]
|   |   |
|   |   └--- Crema [TIPOLOGIA]
|   |   
|   └--- Effetto filler [ESIGENZA]
|       |
|       └--- Trattamento intensivo [TIPOLOGIA]
│
└─── Occhi e sguardo [ZONA]
│   │
│   └--- Ciglia e sopracciglia folte [ESIGENZA]
|   |   |
|   |   └--- Eyeliner [TIPOLOGIA]
|   |   └--- Mascara [TIPOLOGIA]
|   |   
|   └--- Anti-rughe [ESIGENZA]
|       |
|       └--- Crema [TIPOLOGIA]
|       └--- Gocce [TIPOLOGIA]
│   
└─── Volto [ZONA]
    |
    └--- Anti-età [ESIGENZA]
    |   |
    |   └--- Creama giorno [TIPOLOGIA]
    |   └--- Creama notte [TIPOLOGIA]
    |   
    └--- Rassodare [ESIGENZA]
        |
        └--- Maschera [TIPOLOGIA]
        └--- Crema di proseguimento [TIPOLOGIA]
```

<br><br>

## Workflow Blocks and Components Development

Il workflow da seguire per aggiungere un Componente o un Blocco è il seguente.

> Sviluppare prima il componente nello **Storybook**, e quindi:

> ATTENZIONE: se il componente che si va a sviluppare contiene funzioni che rimandano strettamente a WP oppure fa uso di include dinamici, non è possibile passare per lo sviluppo con lo storybook per via di incompatibilità con `twig-js`.

1. **Twig** - Creazione file in `frontend_static/client/app/views/components/{component-name}.twig`
```twig
{% set classes = [] %}
{% for variant in variants|default([]) %}
    {% set classes = classes|merge(['lb-component-name--' ~ variant]) %}
{% endfor %}
{% if class %}
    {% set classes = classes|merge([class]) %}
{% endif %}

<section class="lb-component-name{{ classes|length ? ' ' ~ classes|join(' ') : '' }} js-component-name">
    <h3>{{ title }}</h3>
</section>
```

2. **SCSS** - Creazione file in `frontend_bundler_vite/client/app/styles/components/{component-name}.scss`
```scss
.lb-component-name {
    position: relative;
}
```

3. **JS** - Creazione file in `frontend_bundler_vite/client/app/scripts/components/{ComponentName}/{ComponentName}.js`
```js
import Component from '@okiba/component'
import { qs, qsa, on, off } from '@okiba/dom'
import { debounce } from '@okiba/functions'

const ui = {
    test: '.lb-component-name__test'
}

class ComponentName extends Component {

    constructor({ options, ...props }) {
        super({ ...props, ui })

        this.init()
    }

    init = () => {
        console.log('Component Init')
    }
}

export default ComponentName
```

4. **Okiba Component Init** - Inizializzare il componente tramite Okiba in `frontend_bundler_vite/client/app/scripts/components/app.js`
```js
import Component from '@okiba/component'

import ComponentName from './ComponentName'

const components = {
    componentName: {
        selector: '.js-component-name',
        type: ComponentName,
        optional: true
    },
}

export default class Application extends Component {
    constructor() {
        super({ el: document.body, components })

        this.el.classList.add('ready')
    }
}
```

5. **Storybook** - Creazione story del componente in `frontend_storybook/storybook/app/stories/components/{component-name}.stories.js`
```js
// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Component Twig
import render from '../../views/components/component-name.twig'
// Component JS
import ComponentName from '../../scripts/components/ComponentName'

const dataDefault = {
    title: 'Test',
}

storiesOf('Components|Component Name', module)
    .addDecorator(storyFn => {
        useEffect(() => {
            const app = new Component({
                el: document.body,
                components: [
                    {
                        selector: '.js-component-name',
                        type: ComponentName
                    }
                ]
            })
            return () => app.destroy()
        }, [])

        return storyFn()
    })
    .add('Default', () => render(dataDefault))
```

<br><br>

> Dopodichè creare e integrare il componnete nell'editor di Wordpress, **Gutenberg**:

6. **ACF Block** - Registrare il blocco in `backend_wordpress/wordpress/app/wp-content/themes/caffeina-theme/acf-config/blocks/acf-block-{component-name}.php`
```php
<?php

add_action('acf/init', 'lb_init_block_component_name');

function lb_init_block_component_name()
{
    if (function_exists('acf_register_block_type')) {

        acf_register_block_type(array(
            'name'              => 'lb-component-name',
            'title'             => __('Component Name'),
            'description'       => __('Caffeina Block - Component Name.'),
            'render_template'   => 'gutenberg-blocks/component-name.php',
            'category'          => 'caffeina-theme',
            'keywords'          => array('lorem', 'ipsum', 'component name'),
            'example' => array(
                'attributes' => array(
                    'data' => array(
                        'is_preview' => true
                    )
                )
            )
        ));
    }
}
```

7. **Payload e Render** - Gestire il payload del blocco e il render in
`backend_wordpress/wordpress/app/wp-content/themes/caffeina-theme/gutenberg-blocks/block-{component-name}.php`
```php
<?php

use Caffeina\LaboSuisse\Blocks\ComponentName;

$blockComponentName = (new ComponentName($block, null))
    ->render();
```

e in `backend_wordpress/wordpress/app/wp-content/themes/caffeina-theme/packages/caffeina/labo-suisse/src/Blocks/{ComponentName}.php`
```php
<?php

namespace Caffeina\LaboSuisse\Blocks;

class ComponentName extends BaseBlock
{
    public function __construct($block, $name)
    {
        parent::__construct($block, $name);

        $payload = [
            'title' => get_field('field_name'),
        ];

        $this->setContext($payload);
    }
}
```

8. **ACF Fields** - Aggiungere i campi personalizzati ACF relativi al blocco tramite la dashboard sul CMS

<br><br>

## WP Plugins

Custom Fields
- Advanced Custom Fields PRO

Form di Contatto
- Contact Form 7

Security
- iThemes Security

Mailchimp
- MC4WP: Mailchimp for WordPress
- MC4WP: Mailchimp for WordPress Premium

User Roles Management
- User Role Editor

Cache
- W3 Total Cache

E-commerce Management
- WooCommerce

Import/Export
- WP All Import Pro
- - Support plugin
- - - ACF
- - - - WP All Import - ACF Add-On
- WP All Export Pro
- - Support plugin
- - - ACF
- - - - WP All Export - ACF Export Add-On Pro

Mail
- WP Mail SMTP

Media
- WP Offload Media

Multilanguage
- WPML String Translation
- WPML Multilingual CMS
- - Support plugin
- - - Media
- - - - WPML Media
- - - ACF
- - - - Advanced Custom Fields Multilingual
- - - CF7
- - - - Contact Form 7 Multilingual
- - - WooCommerce
- - - - WooCommerce Multilingual & Multicurrency
- - - Yoast
- - - - WPML SEO

SEO
- Yoast SEO

Other
- Regenerate Thumbnails
- YIKES Simple Taxonomy Ordering

<br><br>

## Images Guidelines

### Hero & Carousel Hero

**Dimensioni:**
| Variante      | Width       | Height             |
| ------------- | ----------- | ------------------ |
| Large         | `2500px`    | `700px`            |
| Medium        | `2500px`    | `520px`            |
| Small         | `2500px`    | `420px`            |
| Containerized | `1200px`    | Large/Medium/Small |

**Safe Area:**
| Variante       | Top         | Bottom      | Left        | Right       |
| -------------- | ----------- | ----------- | ----------- | ----------- |
| Infobox Left   | `100px`     | `100px`     | `1250px`    | `0px`       |
| Infobox Right  | `100px`     | `100px`     | `0px`       | `1250px`    |
| Infobox Center | `100px`     | `100px`     | `0px`       | `0px`       |

<br>

### Banner

**Note:**

Il testo non deve superare un certo numero di righe:
- `Tagline` max 1 riga
- `Titolo` max 2 righe
- `Paragrafo` max 3 righe

**Dimensioni:**
| Variante    | Width       | Height      |
| ----------- | ----------- | ----------- |
| Default     | `1200px`    | `400px`     |

**Safe Area:**
| Variante       | Top         | Bottom      | Left        | Right       |
| -------------- | ----------- | ----------- | ----------- | ----------- |
| Infobox Left   | `60px`      | `60px`      | `600px`     | `0px`       |
| Infobox Right  | `60px`      | `60px`      | `0px`       | `600px`     |
| Infobox Center | `60px`      | `60px`      | `0px`       | `0px`       |

<br>

### Banner Alternate & Carousel Banner Alternate

**Dimensioni:**
| Variante    | Width       | Height      |
| ----------- | ----------- | ----------- |
| Default     | `570px`     | `400px`     |
| Image big   | `800px`     | `560px`     |

**Safe Area:**
| Variante    | Top         | Bottom      | Left        | Right       |
| ----------- | ----------- | ----------- | ----------- | ----------- |
| Default     | `60px`      | `60px`      | `60px`      | `60px`      |

<br>

### Block Launch two Images

**Dimensioni:**
| Variante    | Width       | Height      |
| ----------- | ----------- | ----------- |
| Default     | `570px`     | `700px`     |

**Safe Area:**
| Variante    | Top         | Bottom      | Left        | Right       |
| ----------- | ----------- | ----------- | ----------- | ----------- |
| Default     | `60px`      | `60px`      | `60px`      | `60px`      |

<br>

### Block Image and Card

**Dimensioni:**
| Variante    | Width       | Height      |
| ----------- | ----------- | ----------- |
| Image Left  | `535px`     | `500px`     |
| Image Right | `535px`     | `160px`     |

**Safe Area:**
| Variante    | Top         | Bottom      | Left        | Right       |
| ----------- | ----------- | ----------- | ----------- | ----------- |
| Image Left  | `60px`      | `60px`      | `60px`      | `60px`      |
| Image Right |             |             |             |             |

<br>

### Block Launch two Cards

**Dimensioni:**
| Variante    | Width       | Height      |
| ----------- | ----------- | ----------- |
| Horizontal  | `570px`     | `310px`     |
| Vertical    | `1200px`    | `470px`     |

**Safe Area:**
| Variante    | Top         | Bottom      | Left        | Right       |
| ----------- | ----------- | ----------- | ----------- | ----------- |
| Default     | `60px`      | `60px`      | `60px`      | `60px`      |

<br>

### Block Love Labo

**Dimensioni:**
| Variante    | Width       | Height      |
| ----------- | ----------- | ----------- |
| Default     | `160px`     | `160px`     |

<br>

### Carousel Centered

**Dimensioni:**
| Variante    | Width       | Height      |
| ----------- | ----------- | ----------- |
| Default     | `560px`     | `410px`     |

<br>

### Number List with Image

**Dimensioni:**
| Variante    | Width       | Height      |
| ----------- | ----------- | ----------- |
| Default     | `570px`     | `420px`     |

**Safe Area:**
| Variante    | Top         | Bottom      | Left        | Right       |
| ----------- | ----------- | ----------- | ----------- | ----------- |
| Default     | `60px`      | `60px`      | `60px`      | `60px`      |
