document.getElementById('formPropietario').addEventListener('submit', function(e) {
    e.preventDefault(); // Evitar envío tradicional

    const form = this;
    const formData = new FormData(form);
    
    const accion = e.submitter ? e.submitter.value : null;
    console.log("Acción (botón presionado):", accion);

    // Validación previa: verificar si ya existe el propietario
    fetch('crud/Validaciones/validarPropietario.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        console.log(data);
        if (data.existe) {
            // Mostrar alerta sin cerrar el modal ni enviar el formulario
            Swal.fire({
                icon: 'warning',
                title: 'Propietario ya registrado',
                text: 'El número de documento ingresado ya existe. Verifique los datos.',
                confirmButtonText: 'Aceptar'
            });
            return; // ⛔️ DETENER flujo aquí
        }

        // 🔓 Si NO existe, guardar propietario
        fetch('crud/guardarPropietario.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            Swal.fire({
                icon: 'success',
                title: 'Guardado',
                text: 'El propietario fue registrado exitosamente.'
            }).then(() => {
                // Cerrar el modal y limpiar el formulario
                var modal = bootstrap.Modal.getInstance(document.getElementById('nuevoPropietario'));
                modal.hide();
                form.reset();

                // si el botón presinado fue continuar, abrimos el siguiente modal
                console.log("Respuesta del servidor:", data);            
                console.log("Acción:", accion);
                if (accion === 'continuar') {
                    const nuevoId = data.id;
                    const nom_propietario = data.nom_propietario;
                    const ape_propietario = data.ape_propietario;
                    console.log(nuevoId, nom_propietario, ape_propietario); 

                    const modalSiguiente = new bootstrap.Modal(document.getElementById('nuevoEstablecimiento'));
                    modalSiguiente.show();

                    // Espera un breve momento para asegurarte de que está cargado
                setTimeout(function() {
                const idPropietarioField = document.getElementById('idP');
                if (idPropietarioField) {
                    idPropietarioField.value = nuevoId;
                    document.getElementById('nomape_propietario').value = nom_propietario + ' ' + ape_propietario + ' ' + nuevoId;
                    console.log("Valor asignado al campo idP:", idPropietarioField.value);
                } else {
                    console.error("Campo id_propietario no encontrado.");
                }
                }, 500);
                }
            });
        });
    })
    .catch(error => {
        console.error('Error:', error);
    });
});
