// Guardar posición antes de recargar
let currentPosition;

function saveCurrentPosition() {
    currentPosition = $('#tabla').DataTable().page.info().page; // Guarda la página actual
}

// Función para recargar la tabla (puedes ajustar según tu implementación)
function reloadDataTable() {
    // Realiza la lógica para recargar la tabla, por ejemplo, usando ajax.reload
    $('#tabla').DataTable().ajax.reload(function () {
        // Restaura la posición después de la recarga
        restorePosition();
    });
}

// Restaurar posición después de la recarga
function restorePosition() {
    $('#tabla').DataTable().page(currentPosition).draw('page'); // Vuelve a la página guardada
}

// Evento al hacer clic en "Guardar cambios"
$('#guardar').on('click', function () {
    saveCurrentPosition(); // Guarda la posición antes de la recarga
    // Realiza la lógica para guardar cambios y recargar la tabla
    // ...

    // Luego de modificar el registro, recarga la tabla
    reloadDataTable();
});
