if ( $("#proposal-guideline").length > 0 ) {
  $("#guideline-link").click(function() {
    $("html, body").animate({ scrollTop: $("#proposal-guideline").offset().top }, "slow");
  });
}

$(document).ready(function(){
  applicationFormHandler();
  removeExtraStringFromBlogList();
});

function applicationFormHandler() {
  var theForm = $("#fringe-event-form");
  var $theForm = theForm;
  var formURL = $theForm.attr("action");
  var privacyCheckboxName = "entry.774776501";

  $("#fringe-event-thank-you").hide();

  $theForm.submit(function(event) {
    event.preventDefault();
  });

  $theForm.validate({
    messages: {
      "entry.774776501": "To submit this form you must agree with our privacy policy."  // privacy policy checkbox
    },
    errorPlacement: function(error, element) {
      console.log(element.attr("name"));
      if (element.attr("name") == privacyCheckboxName ) { // privacy policy checkbox
        error.text("To submit this form you must agree with our privacy policy.");
        $("#pp-error-msg").text(error.text());
      } else {
        error.hide(); // highlight other missing fields with red border instead showing error messages
      }
    },
    submitHandler: function(form) {
      $.ajax({
        url: formURL,
        data: $(form).serialize(),
        type: "POST",
        crossDomain: true,
        dataType: "json",
        complete: function(jqXHR, textStatus) {
          // redirect to thank you page if Google has successfully received the data
          if ( jqXHR.status == "0" || jqXHR.status == "200" ) {
            $theForm.hide();
            $("#fringe-event-thank-you").fadeIn("slow").delay("3000").fadeOut("slow", function() {
              $theForm.find("input[type=text], input[type=checkbox], select").val("");
              $theForm.show();
            });
          }
        }
      });
    }
  });
}

// some hacks for the Webmaker RSS feed
function removeExtraStringFromBlogList() {
  var blogExcerpt = $("#home-sidebar").html();
  $("#home-sidebar").html(blogExcerpt.replace(/Continue reading/g, ""));
}
