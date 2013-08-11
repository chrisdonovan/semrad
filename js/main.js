/* Main JS and JQuery functions */
$(document).ready(function() {
	/* Clickable rows in portfolio */
    $("#proj-table tr").click(function() {
        var href = $(this).find("a").attr("href");
        if(href) {
            window.location = href;
        }
    });
	
	/* Change CSS for focused form elements */
	$("input, textarea").focus(function(){
		$(this).css("background-color","#dddddd");
	});
	$("input, textarea").blur(function(){
		$(this).css("background-color","#ffffff");
	});
	
	/* Contact form submit */
	$("#contactForm").submit(function() {
		var name = $("#name").val();
		var email = $("#email").val();
		var message = $("#message").val();
		var email_check = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,6}$/i;

		if(name=='' || !email_check.test(email) || message==''){
			if(name==''){
				$(".err-name").html("<small>Please enter a name.</small>").show();
			}
			
			if(!email_check.test(email)){
				$(".err-email").html("<small>Please enter a valid email address.</small>").show();
			}
			
			if(message==''){
				$(".err-mess").html("<small>Please enter a message.</small>").show();
			}
			
			$("textarea").focus(function(){
				$(".err-mess").hide();
			});
			
			$("input#name").focus(function(){
				$(".err-name").hide();
			});
			
			$("input#email").focus(function(){
				$(".err-email").hide();
			});
		}
		
		else {
			var dataString = $("form").serialize();
			$.ajax({
				type: "post",
				url: "/contact/contact_action.php",
				data: dataString,
				cache: false,
				success: function(){
					$("#name").val("");
					$("#email").val("");
					$("#message").val("");
					$('.success').html("<small>Your message was successfully sent.</small>")
					.fadeIn(400).show();
					setTimeout(function(){$(".success").fadeOut(500);}, 2000);
				}
			});
		}
		return false;
	});
        
        /* Zebra Datepicker ID handlers */
        $('#wkbegin').Zebra_DatePicker({
          view: 'months'
          ,direction: [false, '2013-04-01']
          ,always_visible: $('#bgncal')
          ,pair: $('#wkend')
        });
        
        $('#wkend').Zebra_DatePicker({
          view: 'months'
          //,direction: [false, true]
          ,always_visible: $('#endcal')
        });
});