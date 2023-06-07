const Lucy = require('./third-party/lucy.js');

const config = {
  algoliaAppId: 'CGLHJ0181U',
  algoliaAppKey: '8fa2f724a6314f9a0b840c85b05b943e',
  algoliaIndexName: 'mc4wp_kb',
  links: [
    {
      text: '<span class="dashicons dashicons-book"></span> Knowledge Base',
      href: 'https://www.mc4wp.com/kb/',
    },
    {
      text: '<span class="dashicons dashicons-editor-code"></span> Sample Code Snippets',
      href: 'https://github.com/ibericode/mailchimp-for-wordpress/tree/master/sample-code-snippets',
    },
    {
      text: '<span class="dashicons dashicons-editor-break"></span> Changelog (free plugin)',
      href: 'https://wordpress.org/plugins/mailchimp-for-wp/#developers',
    },
    {
      text: '<span class="dashicons dashicons-editor-break"></span> Changelog (Premium plugin)',
      href: 'https://my.mc4wp.com/plugins/1/changelog',
    },
  ],
  contactLink: 'mailto:support@mc4wp.com',
};

if (window.lucy_config) {
  config.contactLink = window.lucy_config.email_link;
}

// Fire up Lucy
// This powers the "Need help?" section in the bottom-right corner
// of Mailchimp for WP related admin pages
// eslint-disable-next-line no-new
new Lucy(
  config.algoliaAppId,
  config.algoliaAppKey,
  config.algoliaIndexName,
  config.links,
  config.contactLink,
);
