if ( $("#proposal-guideline").length > 0 ) {
  $("#guideline-link").click(function() {
    $("html, body").animate({ scrollTop: $("#proposal-guideline").offset().top }, "slow");
  });
}
