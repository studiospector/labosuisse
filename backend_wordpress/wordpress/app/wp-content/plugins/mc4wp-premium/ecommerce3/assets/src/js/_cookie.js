/**
 * Creates a cookie
 *
 * @param {string} name
 * @param {string} value
 * @param {int} days
 */
function create(name, value, days) {
  const expires = days ? `;max-age=${days * 86400}` : '';
  document.cookie = `${encodeURIComponent(name)}=${encodeURIComponent(value)}${expires};path=/;SameSite=lax`;
}

/**
 * Returns true if a cookie with the given name exists
 *
 * @param {string} name
 * @returns {boolean}
 */
function exists(name) {
  const re = new RegExp(`${name}=`);
  return re.test(document.cookie);
}

module.exports = { create, exists };
