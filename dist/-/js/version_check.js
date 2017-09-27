(function() {

  const liveVersion = $('#footer-update-area').data('version');

  const showFailure = function() {
    $('#footer-update-area').html(
      'Failed while checking for a newer version of ' +
      '<a href="https://lessnmore.net/" ' +
      'title="Lessn More home page">Lessn More</a>.'
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
          $('#footer-update-area').html(
            'You’re on the latest version of ' +
            '<a href="https://lessnmore.net/" ' +
            'title="Lessn More home page">Lessn More</a>!'
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
