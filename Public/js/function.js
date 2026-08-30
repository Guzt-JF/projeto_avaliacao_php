function showToast(message, error = false) {
  if(!error){
    $('.toast_error').removeClass('show');
    setTimeout(() => {
      $('.toast_error').remove();
    }, 100);
  }
  const random_id = Math.floor(Math.random() * 999999);

  const toast = $(
    `<div id="toast_${random_id}" class="toast ${error ? 'toast_error' : ''}">
      ${message}
    </div>`
  );

  $('body').append(toast);

  void toast[0].offsetWidth;

  toast.addClass('show');

  setTimeout(() => {
    toast.removeClass('show');
  }, 3000);

  setTimeout(() => {
    toast.remove();
  }, 3200);
  
  $('.toast_error.show').each(function () {
    $(this).removeClass('shake');
    void this.offsetWidth;
    $(this).addClass('shake');
  });
}