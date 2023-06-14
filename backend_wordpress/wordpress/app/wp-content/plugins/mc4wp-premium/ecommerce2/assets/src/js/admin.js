const ProgressBar = require('./_progress-bar.js');
const request = require('./_request.js');

let count = window.mc4wp_ecommerce.untracked_order_count;
const form = document.getElementById('add-untracked-orders-form');
const progressBarMount = document.getElementById('add-untracked-orders-progress');
let progressBar;

// hook into form submit
if (form) {
  form.addEventListener('submit', start);
}

function start(e) {
  // prevent default form submit
  e.preventDefault();

  const button = form.querySelector('input[type="submit"]');
  button.setAttribute('disabled', true);

  // init progress bar
  progressBar = new ProgressBar(progressBarMount, count);
  window.setTimeout(fetchProgress, 500);
  work();
}

function work() {
  const limit = parseInt(form.elements.limit.value, 10);
  const offset = parseInt(form.elements.offset.value, 10);
  const url = `${window.ajaxurl}?action=mc4wp_ecommerce_add_untracked_orders&offset=${offset}&limit=${limit}`;

  request(url, {
    onSuccess(data) {
      updateProgress(data);

      if (count <= data) {
        // We're not making progress..
        const textElement = document.createElement('p');
        textElement.style.color = 'red';
        textElement.innerHTML = "We're stuck. Please <a href=\"admin.php?page=mailchimp-for-wp-other\">check the debug log</a> for errors.";
        progressBarMount.parentNode.appendChild(textElement);
      } else if (data > 0) {
        // Keep going if there's more
        work();
      }
    },

    onError(code) {
      // if we got a 504 Gateway Timeout, try again.
      if (code === 504) {
        work();
      }
    },
  });
}

function updateProgress(newCount) {
  progressBar.tick(count - newCount);
  count = newCount;
}

function fetchProgress() {
  if (progressBar.done()) {
    // refresh page
    window.setTimeout(() => {
      window.location.reload();
    }, 2500);

    return;
  }

  const url = `${window.ajaxurl}?action=mc4wp_ecommerce_get_untracked_orders_count`;
  request(url, {
    onSuccess(data) {
      updateProgress(data);
      window.setTimeout(fetchProgress, 2000);
    },
  });
}
