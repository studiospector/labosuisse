const cookie = require('./_cookie.js');

/**
 * Get query parameter by name.
 * Returns an empty string if no there was no matching key in the query string.
 *
 * @param {string} key
 * @returns {string}
 */
function getUrlValue(key) {
  const regex = new RegExp(`${key}=([^&]+)`);
  const matches = regex.exec(window.location.search);
  if (matches && matches.length > 0) {
    return matches[1];
  }

  return '';
}

/**
 * Sets a cookie if URL params contained a value with the given name as key
 * @param {string} name
 */
function createCookieFromUrlValue(name) {
  const value = getUrlValue(name);
  if (value !== '') {
    cookie.create(name, value, 14);
  }
}

// set mc_cid, mc_eid & mc_tc cookies if url params are set
['mc_cid', 'mc_eid', 'mc_tc'].forEach(createCookieFromUrlValue);

// store landing site in mc_landing_site cookie, if not set
if (!cookie.exists('mc_landing_site')) {
  cookie.create('mc_landing_site', window.location.href, 7);
}
