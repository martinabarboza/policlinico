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

        $('#tipo-busqueda-navbar').on('change', function () {
            var placeholder = $(this).find(':selected').data('placeholder');
            $('#texto-busqueda-navbar').attr('placeholder', placeholder);
        });

        $('[data-back-history]').on('click', function (event) {
            if (!document.referrer) {
                return;
            }

            var urlAnterior = new URL(document.referrer);
            if (urlAnterior.origin === window.location.origin && window.history.length > 1) {
                event.preventDefault();
                window.history.back();
            }
        });
    });
})(jQuery);
