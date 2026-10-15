(function ($) {
    "use strict";

    // Cargador de página
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('visible');
            }
        }, 1);
    };
    spinner();
    
    

    // Sidebar desplegable
    $('.boton-menu-lateral').click(function () {
        $('.menu-lateral, .contenido-principal').toggleClass("abierto");
        return false;
    });


})(jQuery);
