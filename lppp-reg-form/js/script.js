$(".next").click(function() {
    current_fs = $(this).parent();
    next_fs = $(this).parent().next();
    
    // Activate next step on progressbar using the index of next_fs
    $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

    // Show the next fieldset
    next_fs.show();
    
    // Hide the current fieldset without animation
    current_fs.hide();
});

$(".previous").click(function() {
    current_fs = $(this).parent();
    previous_fs = $(this).parent().prev();

    // Deactivate current step on progressbar
    $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

    // Show the previous fieldset
    previous_fs.show();

    // Hide the current fieldset without animation
    current_fs.hide();
});

$(".submit").click(function() {
    // Handle submit functionality here
});