function ProgressBar(element, count) {
  const wrapper = element;
  const bar = document.createElement('div');
  const stepSize = 100 / count;
  let progress = 0;

  wrapper.style.height = '40px';
  wrapper.style.width = '100%';
  wrapper.style.border = '1px solid #ccc';
  wrapper.style.lineHeight = '40px';

  bar.style.boxSizing = 'border-box';
  bar.style.backgroundColor = '#cc4444';
  bar.style.textAlign = 'center';
  bar.style.fontWeight = 'bold';
  bar.style.height = '100%';
  bar.style.color = 'white';
  bar.style.fontSize = '16px';
  bar.style.width = `${progress}%`;
  wrapper.appendChild(bar);

  function tick(ticks) {
    if (done()) { return; }

    ticks = ticks === undefined ? 1 : ticks;
    progress += (stepSize * ticks);
    bar.style.width = `${progress}%`;

    bar.innerText = `${parseInt(progress, 10)}%`;

    if (done()) {
      bar.innerText = 'Done!';
    }
  }

  function done() {
    return progress >= 100;
  }

  return {
    tick,
    done,
  };
}

module.exports = ProgressBar;
