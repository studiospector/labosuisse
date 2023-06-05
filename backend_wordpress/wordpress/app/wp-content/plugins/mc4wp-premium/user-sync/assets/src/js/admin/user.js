/**
 * User Model
 *
 * @param {object} data
 * @constructor
 */
function User(data) {
  this.id = data.ID;
  this.username = data.user_login;
  this.email = data.user_email;
}

module.exports = User;
