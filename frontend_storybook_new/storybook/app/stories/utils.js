export function createElementFromHTML(htmlString) {
  const div = document.createElement('div')
  div.innerHTML = htmlString.trim()

  // Change this to div.childNodes to support multiple top-level nodes
  return div.firstChild
}

export function appendChildren(htmlString, parent) {
  const div = document.createElement('div')
  div.innerHTML = htmlString
  Array.from(div.children).forEach(c => parent.appendChild(c))
}
