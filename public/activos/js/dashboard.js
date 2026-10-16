(function ($) {
    "use strict";

    // Oculta el cargador cuando termina de cargar el documento.
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('visible');
            }
        }, 1);
    };
    $(function () {
        spinner();

        // Este archivo se carga en el <head>; esperamos a que exista el botón del navbar.
        $('.boton-menu-lateral').on('click', function (event) {
            event.preventDefault();
            $('.menu-lateral, .contenido-principal').toggleClass('abierto');
        });
    });
})(jQuery);
