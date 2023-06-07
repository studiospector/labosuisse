const $ = window.jQuery;
const Accordion = require('./_accordion.js');
const FormPreview = require('./_form-preview.js');

const iframeElement = document.getElementById('mc4wp-css-preview');
const originalSendToEditor = window.send_to_editor;
let $imageUploadTarget;

// init
const preview = new FormPreview(iframeElement);
iframeElement.addEventListener('load', () => {
  preview.init();
  preview.applyStyles();
});

// turn settings page into accordion
// eslint-disable-next-line no-new
new Accordion(document.querySelector('.mc4wp-accordion'));

// reload page with new form ID after changing form in <select> element
document.querySelector('.mc4wp-form-select').addEventListener('change', (evt) => {
  evt.target.form.submit();
});

// show thickbox when clicking on "upload-image" buttons
document.querySelector('.upload-image').addEventListener('click', () => {
  $imageUploadTarget = $(this).siblings('input');
  window.tb_show('', 'media-upload.php?type=image&TB_iframe=true');
});

// run HTML5 validation on every change event
document.getElementById('form-css-settings').addEventListener('change', (evt) => {
  evt.target.checkValidity();
});

// attach handler to "send to editor" button
window.send_to_editor = (html) => {
  if ($imageUploadTarget) {
    const imgurl = $(html).attr('src');
    $imageUploadTarget.val(imgurl);
    window.tb_remove();
  } else {
    originalSendToEditor(html);
  }

  preview.applyStyles();
};
