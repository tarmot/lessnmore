(function() {

  const liveVersion = $('#footer-update-area').data('version');

  const showFailure = function() {
    $('#footer-update-area').text(
      'Failed while checking for a newer version of Lessn More.'
    );
  };

  fetch("?current_release", {
    credentials: "include"
  }).then((response) => {
    if (response.ok) {
      response.text().then((version) => {
        if (!version.match(/^\d+\.\d/)) {
          showFailure();
          return;
        }

        const versionSpan = $('<span />').text(version);

        if (liveVersion === version) {
          $('#footer-update-area').text(
            'You’re on the latest version of Lessn More!'
          );
        } else {
          $('#footer-update-area').html(
            "You’re using Lessn More " + liveVersion +
            ". The latest version is " + versionSpan.html() +
            ". <a href='https://lessnmore.net/'>Get it from LessnMore.net</a>"
          );
        }
      }, () => {
        showFailure()
      });
    } else {
      showFailure();
    }
  }, (error) => {
    showFailure();
  });

})();
