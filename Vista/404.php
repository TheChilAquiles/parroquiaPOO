<!DOCTYPE html> <!-- This is an HTML5 document -->
<html lang="es"> <!-- HTML document, language is Spanish -->
<head> <!-- Head section: page data and imports -->
    <meta charset="UTF-8"> <!-- Use UTF-8 for correct characters -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Make page responsive -->
    <title>404 - Página no encontrada</title> <!-- Browser tab title -->
    <script src="https://cdn.tailwindcss.com"></script> <!-- Load Tailwind CSS from CDN -->
</head>

<body class="bg-[#F8EDE3]"> <!-- Body with soft background color -->

    <div class="min-h-screen flex items-center justify-center"> <!-- Full height box, centered content -->
        <div class="text-center"> <!-- Center all text inside -->

            <h1 class="text-6xl font-bold text-gray-900 mb-4">404</h1> <!-- Big main title "404" -->

            <p class="text-2xl text-gray-700 mb-6">Página no encontrada</p> <!-- Show "Page not found" message -->

            <p class="text-gray-600 mb-8"> <!-- Text with light gray color -->
                <!-- Show the route using PHP and htmlspecialchars (escape unsafe characters) -->
                La ruta "<strong><?php echo htmlspecialchars($_GET['route'] ?? 'desconocida', ENT_QUOTES, 'UTF-8'); ?></strong>" no existe
            </p>

            <!-- Link to homepage using PHP short echo -->
            <a href="<?= url('inicio') ?>" 
                class="px-6 py-3 bg-[#DFD3C3] text-gray-900 font-semibold rounded hover:bg-[#D0C3B3] transition-colors"> <!-- Styled button -->
                Volver al inicio <!-- Button text -->
            </a>

        </div> <!-- Close text-center box -->
    </div> <!-- Close main container -->

</body> <!-- Close body -->
</html> <!-- Close HTML document -->
