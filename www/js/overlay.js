$('#hamburger').click(function() {
    $(this).toggleClass('toggle-active');
    $('#full_menu').toggleClass('nav-active');

    $('.navbar-brand').css('z-index','9');
});