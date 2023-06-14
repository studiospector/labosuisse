// vars
const listSelector = document.getElementById('mc4wp-activity-mailchimp-list');
const chartElement = document.getElementById('mc4wp-activity-chart');
const viewSelector = document.getElementById('mc4wp-activity-view');
const periodSelector = document.getElementById('mc4wp-activity-period');

// init
viewSelector.addEventListener('change', getRowData);
listSelector.addEventListener('change', getRowData);
periodSelector.addEventListener('change', getRowData);

getRememberedValues();

// load Google JS API if not already loaded
// but only if listSelector element is visible
// this allows people to hide the activity widget through their screen options
// and then not loading the entire Google JS suite for something not even visible
if (window.google === undefined && listSelector.offsetParent !== null) {
  const script = document.createElement('script');
  script.onload = function () {
    window.google.load('visualization', '1', { packages: ['corechart', 'bar', 'line'] });
    window.google.setOnLoadCallback(getRowData);
  };
  script.src = 'https://www.google.com/jsapi';
  document.head.appendChild(script);
}

// functions
function getRememberedValues() {
  const previouslySelectedListValue = localStorage.getItem('mc4wp_activity_list');
  if (typeof (previouslySelectedListValue) === 'string' && previouslySelectedListValue.length) {
    listSelector.value = previouslySelectedListValue;
  }

  const previouslySelectedViewValue = localStorage.getItem('mc4wp_activity_view');
  if (typeof (previouslySelectedViewValue) === 'string' && previouslySelectedViewValue.length) {
    viewSelector.value = previouslySelectedViewValue;
  }

  const previouslySelectedPeriodValue = localStorage.getItem('mc4wp_activity_period');
  if (previouslySelectedPeriodValue && previouslySelectedPeriodValue.length) {
    periodSelector.value = previouslySelectedPeriodValue;
  }
}

function rememberValues() {
  localStorage.setItem('mc4wp_activity_list', listSelector.value);
  localStorage.setItem('mc4wp_activity_view', viewSelector.value);
  localStorage.setItem('mc4wp_activity_period', periodSelector.value);
}

function getRowData() {
  rememberValues();

  window.jQuery.getJSON(window.ajaxurl, {
    action: 'mc4wp_get_activity',
    mailchimp_list_id: listSelector.value,
    period: periodSelector.value,
    view: viewSelector.value,
  }, (res) => {
    const rows = res.data;

    if (!res.data || !res.data.length) {
      chartElement.innerHTML = 'Oops. Something went wrong while fetching data from Mailchimp.';
      return;
    }

    for (let i = 0; i < rows.length; i++) {
      // convert strings to JavaScript Date object
      rows[i][0].v = new Date(rows[i][0].v);
    }

    drawChart(rows);
  });
}

/**
 * @param {array} rows
 */
function drawChart(rows) {
  let chart;
  const options = {
    hAxis: {
      title: 'Date',
      format: 'MMM d',
    },
    vAxis: {},
    explorer: {
      maxZoomOut: 2,
      keepInBounds: true,
    },
    animation: {
      duration: 1000,
      easing: 'linear',
      startup: true,
    },
    height: 400,
  };

  if (viewSelector.value === 'size') {
    chart = new SizeChart(rows, options);
  } else {
    chart = new ActivityChart(rows, options);
  }

  chart.draw();
}

/**
 * @param {array} rows
 * @param {object} options
 * @returns {{draw: draw}}
 * @constructor
 */
function ActivityChart(rows, options) {
  const data = new window.google.visualization.DataTable();
  data.addColumn('date', 'Date');
  data.addColumn('number', 'New Subscribers');
  data.addColumn('number', 'Unsubscribes');
  data.addRows(rows);

  options.isStacked = true;
  options.title = `Activity for list ${listSelector.options[listSelector.selectedIndex].innerHTML}`;
  options.vAxis.title = 'Subscriber Activity';

  function draw() {
    const chart = new window.google.visualization.ColumnChart(chartElement);
    chart.draw(data, options);
  }

  return {
    draw,
  };
}

/**
 * @param {array} rows
 * @param {object} options
 * @returns {{draw: draw}}
 * @constructor
 */
function SizeChart(rows, options) {
  const data = new window.google.visualization.DataTable();
  data.addColumn('date', 'Date');
  data.addColumn('number', 'Subscribers');
  data.addRows(rows);

  options.title = `List size for list ${listSelector.options[listSelector.selectedIndex].innerHTML}`;
  options.vAxis.title = 'Number of Subscribers';
  options.legend = { position: 'none' };

  function draw() {
    const chart = new window.google.charts.Line(chartElement);
    chart.draw(data, options);
  }

  return {
    draw,
  };
}
