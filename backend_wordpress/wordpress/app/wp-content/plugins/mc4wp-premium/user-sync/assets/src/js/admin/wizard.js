const m = require('mithril');
const logger = require('./logger');
const User = require('./user');

const { ajaxurl } = window;
let started = false;
let running = false;
let done = false;
let userCount = 0;
let usersProcessed = 0;
let progress = 0;
let userBatch = [];
let hasUnsavedChanges = false;

function askToStart() {
  const sure = window.confirm("Are you sure you want to start synchronising all of your users? This can take a while if you have many users, please don't close your browser window.");
  if (sure) {
    start();
  }
}

function start() {
  started = true;
  running = true;

  fetchTotalUserCount()
    .then(prepareBatch)
    .then(subscribeFromBatch);
}

function resume() {
  running = true;
  subscribeFromBatch();
}

function pause() {
  running = false;
}

function finish() {
  done = true;
  logger.log('Done');
}

function fetchTotalUserCount() {
  return m.request({
    method: 'GET',
    url: ajaxurl,
    params: {
      action: 'mc4wp_user_sync_get_user_count',
    },
  }).then((data) => {
    logger.log(`Found ${data} users.`);
    userCount = data;
  }).catch((error) => {
    logger.log(`Error fetching user count. Error: ${error}`);
  });
}

function prepareBatch() {
  return m.request({
    method: 'GET',
    url: ajaxurl,
    params: {
      action: 'mc4wp_user_sync_get_users',
      offset: usersProcessed,
      limit: 100,
    },
    type: User,
  }).then((data) => {
    userBatch = data;
    logger.log(`Fetched ${userBatch.length} users.`);

    // finish if we didn't get any users
    if (userBatch.length === 0) {
      finish();
    }
  }).catch((error) => {
    logger.log(`Error fetching users. Error: ${error}`);
  });
}

function subscribeFromBatch() {
  if (!running || done) {
    return;
  }

  // do we have users left in this batch
  if (userBatch.length === 0) {
    prepareBatch().then(subscribeFromBatch);
    return;
  }

  // Get next user
  const user = userBatch.shift();

  // Add line to log
  logger.log(`Handling <strong> #${user.id} ${user.username} &lt;${user.email}&gt;</strong>`);

  // Perform subscribe request
  m.request({
    method: 'GET',
    params: {
      action: 'mc4wp_user_sync_handle_user',
      user_id: user.id,
    },
    url: ajaxurl,
  }).then((response) => {
    usersProcessed += 1;
    logger.log(response.message);
  }).catch((e) => {
    usersProcessed += 1;
    logger.log(e);
  }).then(updateProgress)
    .then(subscribeFromBatch);
}

// calculate new progress & update progress bar.
function updateProgress() {
  // calculate % progress
  progress = Math.round(usersProcessed / userCount * 100);

  // finish after processing all users
  if (usersProcessed >= userCount) {
    finish();
  }
}

/**
 * View
 *
 * @returns {*}
 */
function view() {
  // Wizard isn't running, show button to start it
  if (!started) {
    return m('p', [
      m('input', {
        type: 'button',
        class: 'button',
        value: 'Synchronise All',
        onclick: askToStart,
        disabled: hasUnsavedChanges,
      }),
      hasUnsavedChanges ? m('span.help', ' — Please save your changes first.') : '',
    ]);
  }

  // Show progress
  return [
    done ? '' : m(
      'p',
      m('input', {
        type: 'button',
        class: 'button',
        value: (running ? 'Pause' : 'Resume'),
        onclick: (running ? pause : resume),
      }),
    ),
    m('div.progress-bar', [
      m('div.value', {
        style: `width: ${progress}%`,
      }),
      m('div.text', (done ? 'Done!' : `Working: ${progress}%`)),
    ]),
    m(logger),
  ];
}

document.getElementById('settings-form').addEventListener('change', () => {
  hasUnsavedChanges = true;
  m.redraw();
});

module.exports = { view };
