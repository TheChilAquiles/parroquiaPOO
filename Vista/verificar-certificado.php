<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Certificado - Parroquia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .certificate-card {
            box-shadow: 0 20px 60px -15px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen py-12 px-4">

    <div class="max-w-2xl mx-auto fade-in">

        <!-- Logo y título -->
        <div class="text-center mb-8">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT-As_mLQ9e2pUmMq1yfIbaHVeZ43CPnSnOOg&s"
                 alt="Logo Parroquia"
                 class="w-24 h-24 mx-auto mb-4 rounded-full shadow-lg">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">
                <i class="fas fa-certificate text-purple-600"></i>
                Verificación de Certificado
            </h1>
            <p class="text-gray-600">Parroquia San Francisco de Asís</p>
        </div>

        <?php if (isset($valido) && $valido === true): ?>
            <!-- Certificado VÁLIDO -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 certificate-card">
                <!-- Header de éxito -->
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl p-6 mb-6 text-center">
                    <i class="fas fa-check-circle text-6xl mb-4"></i>
                    <h2 class="text-2xl font-bold">Certificado Válido</h2>
                    <p class="text-green-100 mt-2"><?php echo htmlspecialchars($mensaje); ?></p>
                </div>

                <!-- Información del certificado -->
                <div class="space-y-4">
                    <div class="border-l-4 border-purple-500 pl-4 py-2">
                        <p class="text-sm text-gray-500 uppercase tracking-wide">Código de Verificación</p>
                        <p class="text-2xl font-mono font-bold text-gray-800"><?php echo htmlspecialchars($datos['id']); ?></p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500 font-semibold mb-1">Tipo de Certificado</p>
                            <p class="text-lg text-gray-900"><?php echo htmlspecialchars($datos['tipo']); ?></p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500 font-semibold mb-1">Feligrés</p>
                            <p class="text-lg text-gray-900"><?php echo htmlspecialchars($datos['feligres']); ?></p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500 font-semibold mb-1">Fecha de Emisión</p>
                            <p class="text-lg text-gray-900">
                                <i class="far fa-calendar-alt text-purple-600"></i>
                                <?php echo htmlspecialchars($datos['fecha_emision']); ?>
                            </p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500 font-semibold mb-1">Fecha del Sacramento</p>
                            <p class="text-lg text-gray-900">
                                <i class="far fa-calendar-check text-purple-600"></i>
                                <?php echo htmlspecialchars($datos['fecha_sacramento']); ?>
                            </p>
                        </div>
                    </div>

                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mt-6">
                        <p class="text-sm text-purple-700 font-semibold mb-1">Estado del Certificado</p>
                        <p class="text-lg text-purple-900 font-bold uppercase"><?php echo htmlspecialchars($datos['estado']); ?></p>
                    </div>
                </div>

                <!-- Nota de seguridad -->
                <div class="mt-8 p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-900 rounded-r-lg">
                    <p class="text-sm">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Certificado Auténtico:</strong> Este certificado ha sido emitido por la Parroquia San Francisco de Asís
                        y es completamente válido.
                    </p>
                </div>
            </div>

        <?php elseif (isset($valido) && $valido === false): ?>
            <!-- Certificado INVÁLIDO o NO ENCONTRADO -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 certificate-card">
                <!-- Header de error -->
                <div class="bg-gradient-to-r from-red-500 to-pink-600 text-white rounded-xl p-6 mb-6 text-center">
                    <i class="fas fa-exclamation-triangle text-6xl mb-4"></i>
                    <h2 class="text-2xl font-bold">Certificado No Válido</h2>
                    <p class="text-red-100 mt-2"><?php echo htmlspecialchars($mensaje); ?></p>
                </div>

                <!-- Información de ayuda -->
                <div class="space-y-4">
                    <p class="text-gray-700">
                        El código de verificación proporcionado no corresponde a un certificado válido en nuestro sistema.
                    </p>

                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg">
                        <p class="text-sm text-yellow-800">
                            <i class="fas fa-lightbulb mr-2"></i>
                            <strong>Posibles causas:</strong>
                        </p>
                        <ul class="list-disc list-inside text-sm text-yellow-700 mt-2 ml-4">
                            <li>El código fue escrito incorrectamente</li>
                            <li>El certificado aún no ha sido generado</li>
                            <li>El código QR está dañado o ilegible</li>
                        </ul>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-900">
                            <i class="fas fa-phone mr-2"></i>
                            <strong>¿Necesita ayuda?</strong>
                            Comuníquese con la secretaría de la parroquia para verificar el estado de su certificado.
                        </p>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <!-- Sin código proporcionado -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 certificate-card">
                <div class="text-center">
                    <i class="fas fa-search text-6xl text-purple-600 mb-4"></i>
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Ingrese un Código de Verificación</h2>
                    <p class="text-gray-600 mb-6">
                        Para verificar un certificado, escanee el código QR o ingrese el código manualmente en la URL.
                    </p>

                    <div class="bg-gray-50 rounded-lg p-6 text-left">
                        <p class="text-sm font-semibold text-gray-700 mb-2">Formato de URL:</p>
                        <code class="block bg-gray-800 text-green-400 p-3 rounded text-xs overflow-x-auto">
                            ?route=certificados/verificar&codigo=XXXXX
                        </code>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Botón volver -->
        <div class="text-center mt-8">
            <a href="?route=inicio" class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                <i class="fas fa-home mr-2"></i>
                Volver al Inicio
            </a>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-gray-500 text-sm">
            <p>© <?php echo date('Y'); ?> Parroquia San Francisco de Asís. Todos los derechos reservados.</p>
        </div>

    </div>

</body>
</html>
