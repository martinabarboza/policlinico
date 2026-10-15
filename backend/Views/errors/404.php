<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Página no encontrada</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            padding: 24px;
            background: #f4f8f6;
            color: #193b30;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, class="Segoe UI", sans-serif;
        }

        main {
            width: min(100%, 560px);
            padding: 48px 32px;
            border: 1px solid #dce9e2;
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 20px 60px rgb(25 59 48 / 10%);
            text-align: center;
        }

        .codigo-error {
            margin: 0;
            color: #04714c;
            font-size: clamp(5rem, 20vw, 8rem);
            font-weight: 800;
            letter-spacing: -0.08em;
            line-height: 1;
        }

        h1 {
            margin: 20px 0 12px;
            font-size: clamp(1.5rem, 5vw, 2rem);
        }

        p {
            margin: 0;
            color: #587067;
            line-height: 1.6;
        }

        a {
            display: inline-block;
            margin-top: 28px;
            padding: 12px 20px;
            border-radius: 8px;
            background: #04714c;
            color: #fff;
            font-weight: 600;
            text-decoration: none;
        }

        a:hover,
        a:focus-visible {
            background: #035b3d;
        }
    </style>
</head>

<body>
    <main>
        <p class="codigo-error" aria-hidden="true">404</p>
        <h1>Esta página no está disponible</h1>
        <p>La dirección puede ser incorrecta o la página puede haberse movido.</p>
        <a href="<?= htmlspecialchars(url(), ENT_QUOTES, 'UTF-8') ?>">Volver al inicio</a>
        <!--  Con javascript verifica el historial y regresa a la pagina anterior -->
        <a href="/" onclick="if (history.length > 1) { history.back(); return false; }">
            Volver a la página anterior
        </a>
    </main>
</body>

</html>