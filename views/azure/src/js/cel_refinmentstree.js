jQuery(function($) {
    $('.categoryBox [type="checkbox"]').click(function() {
        var wrap = $(this).closest('a');
        window.location.href = wrap.attr('href');
    });
    $('.extra-answers-button').click(function() {
        var link = $(this).find('a');
        oldVal = link.html();
        newVal = link.attr('data-altval');
        link.attr('data-altval',oldVal);
        link.html(newVal);
        $('.extra-answers').toggleClass('hidden');        
        return false;
    });
});