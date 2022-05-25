jQuery(document).ready(function ($) {
  var mediaUploader;
  $('.upload_image_button').click(function (e) {
    e.preventDefault();
    if (mediaUploader) {
      mediaUploader.open();
      return;
    }
    var button = $(this);
    mediaUploader = wp.media.frames.file_frame = wp.media({
      title: cargo_tracking_for_woocommerce.selectImage,
      button: {
        text: cargo_tracking_for_woocommerce.selectImage,
      },
      multiple: false,
    });
    mediaUploader.on('select', function () {
      var attachment = mediaUploader.state().get('selection').first().toJSON();
      $(button).parent().prev().attr('src', attachment.url);
      $('#img').val(attachment.url);
    });
    mediaUploader.open();
    return false;
  });

  $('.remove_image_button').click(function () {
    var answer = confirm(cargo_tracking_for_woocommerce.areYouSure);
    if (answer == true) {
      var src = $(this).parent().prev().attr('data-src');
      $(this).parent().prev().attr('src', src);
      $(this).prev().prev().val('');
    }
    return false;
  });

  $('#cargo-tracking-for-woocommerce-link-delete').click(function () {
    if (!confirm(cargo_tracking_for_woocommerce.areYouSure)) {
      return false;
    }
  });

  $('#order_status').change(function () {
    $('#cargo_tracking_for_woocommerce-change_order_type').prop(
      'checked',
      false
    );
  });
});
