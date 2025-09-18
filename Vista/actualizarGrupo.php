<?php
// Mostrar mensajes de la sesión
if (isset($_SESSION['mensaje'])) {
    $tipo_clase = $_SESSION['tipo_mensaje'] == 'success' ? 'bg-green-100 border-green-400 text-green-700' : 
                    ($_SESSION['tipo_mensaje'] == 'error' ? 'bg-red-100 border-red-400 text-red-700' : 
                    'bg-blue-100 border-blue-400 text-blue-700');
    echo '<div class="' . $tipo_clase . ' border px-4 py-3 rounded mb-4">' . htmlspecialchars($_SESSION['mensaje']) . '</div>';
    unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']);
}
?>

<main class="mx-auto max-w-7xl px-4 py-8">
    <div class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900">Grupos Parroquiales</h1>
            <p class="text-gray-600 mt-2">Gestiona los grupos y comunidades de la parroquia</p>
        </div>
        <button id="btnCrearGrupo" class="px-6 py-3 bg-purple-600 text-white rounded-lg shadow-md hover:bg-purple-700 transition duration-200 font-medium">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>