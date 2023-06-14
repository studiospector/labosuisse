function request(url, options) {
  const request = new XMLHttpRequest();
  request.onreadystatechange = function () {
    if (this.readyState === 4) {
      if (this.status >= 200 && this.status < 400) {
        if (options.onSuccess) options.onSuccess(this.responseText);
      } else if (options.onError) options.onError(this.status, this.responseText);
    }
  };
  request.open(options.method || 'GET', url, true);

  if (options.method && options.method.toUpperCase() === 'POST') {
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
  }

  request.send(options.data || {});
  return request;
}

module.exports = request;
