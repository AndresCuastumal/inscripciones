<div class="modal fade" id="editEstablecimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white d-flex justify-content-between align-items-center">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Establecimiento</h1> 
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal"><i class="fa-solid fa-circle-xmark"></i></button>      
            </div>
        <div class="modal-body">
            <form id="editEstablecimientoForm" action ="crud/guardarUpdateRegEstablecimiento.php" method="post">
            <input type="hidden" name="id" id="id">
            
            <div class="mb-2">
            <table class="table">                
                <tr>
                    <td colspan = "2">    
                        <label for="razon_social" class="form-label">* Razon social:</label>
                        <input type="text" name="razon_social" id="razon_social" class ="form-control">
                    </td>
                    <td colspan = "2">    
                        <label for="nom_comercial" class="form-label">* Nombre comercial:</label>
                        <input type="text" name="nom_comercial" id="nom_comercial" class ="form-control">
                    </td>
                </tr>
                <tr>    
                    <td colspan ="2">
                        <label for="nit" class="form-label">* NIT Establecimiento:</label>
                        <input type="text" name="nit" id="nit" class ="form-control" readonly style="background-color: #e3f1f1;">                    
                    </td>
                    <td>
                        <label for="dv" class="form-label">* DV:</label>
                        <input type="text" name="dv" id="dv" class ="form-control" readonly style="background-color: #e3f1f1;">
                    </td>
                    <td>
                        <label for="sucursal" class="form-label">Sucursal:</label>
                        <input type="number" name="sucursal" id="sucursal" class ="form-control" >
                    </td>                    
                </tr>
                <tr>                    
                    <td colspan="2">
                        <label for="dir_establecimiento" class="form-label">* Dirección:</label>
                        <input type="text" name="dir_establecimiento" id="dir_establecimiento" class ="form-control" >
                    </td>
                    <td colspan="2">
                        <label for="id_barrio_vereda" class="form-label">* Barrio establecimiento:</label>                        
                        <select name="id_barrio_vereda" id="id_barrio_vereda" class="form-control">
                            <option value="">Seleccione...</option>
                            <?php
                            $consultaBarrioVereda = $conn->prepare("SELECT * FROM barrio order by nom_barrio");
                            $consultaBarrioVereda->execute();
                            while ($row_registro = $consultaBarrioVereda->fetch(PDO::FETCH_ASSOC)) { ?>
                                <option value="<?= $row_registro['id']; ?>"><?= $row_registro['nom_barrio']; ?></option>
                            <?php } ?>
                        </select>                        
                    </td>
                </tr>
                <tr>    
                    <td colspan="2">
                        <label for="correo_establecimiento" class="form-label">Correo establecimiento:</label>
                        <input type="mail" name="correo_establecimiento" id="correo_establecimiento" class ="form-control" >
                    </td>
                    <td>
                        <label for="tel_establecimiento" class="form-label">Teléfono establecimiento:</label>
                        <input type="text" name="tel_establecimiento" id="tel_establecimiento" class ="form-control" >
                    </td>
                    <td>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="estado" name="estado" value="1">
                            <label class="form-check-label" for="estado">Activo</label>
                        </div>
                    </td>                                                                                          
                </tr>
                <tr>
                    <td colspan="2">
                        <label for="doc_propietario" class="form-label">
                            * Identificación Propietario:
                        <span 
                            class="ms-2" 
                            data-bs-toggle="tooltip" 
                            data-bs-placement="right" 
                            title="Presione Enter después de cambiar el número de documento para que se registre el cambio">
                            <i class="bi bi-info-circle-fill text-primary"></i>
                        </span>    
                        </label>                    
                        <input type="text" name="doc" id="doc_propietario" class ="form-control">
                        <input type="hidden" name="id_propietario" id="id_propietario">
                        
                    </td>
                    <td colspan="2">
                    <label for="nom_propietario" class="form-label">* Representante legal:</label>
                        <input type="text" name="nom_propietario" id="nom_propietario" class ="form-control" readonly style="background-color: #e3f1f1;">
                    </td>                                     
                </tr>
                
                </table>
            </div>          
            <div class="">
                <button type="submit" name="accion" value ="guardar" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-circle-xmark"></i> Cerrar</button>                
            </div>
            </form>  
        </div>
        </div>
    </div>    
</div>

<!-- ESTE SCRIPT SIRVE PARA CAPTURAR EL DOC DEL PROPIETARIO, CONSULTARLO EN LA TABLA PROPIETARIO Y DEVOLVER ID, NOMBRES Y APELLIDOS DEL PROPIETARIO NUEVO -->

<script>
    // Detectar cuando se presiona "Enter" en el campo de documento
    document.getElementById('doc_propietario').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Prevenir que el formulario se envíe al presionar Enter
            
            let docPropietario = document.getElementById('doc_propietario').value;
            
            // Crear la solicitud para obtener el nombre del propietario
            if (docPropietario !== "") {
                let formData = new FormData();
                formData.append('doc', docPropietario);

                // Realizar la solicitud AJAX (fetch) al servidor
                fetch('crud/getRegPropietario.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Cargar el nombre del propietario en el campo correspondiente
                        document.getElementById('nom_propietario').value = data.nom_propietario + ' ' + data.ape_propietario;
                        document.getElementById('id_propietario').value = data.id_propietario
                    } else {
                        // Manejar error, por ejemplo, mostrar un mensaje de "Propietario no encontrado"
                        alert('Propietario no encontrado.');
                        document.getElementById('nom_propietario').value = '';
                    }
                })
                .catch(error => console.error('Error en la solicitud:', error));
            }
        }
    });
</script>

<!-- SCRIPT PARA NO DEJAR PASAR EL CAMPO NOM_PROPIETARIO COMO VACÍO -->

<script>
    document.getElementById('editEstablecimientoForm').addEventListener('submit', function(event) {
    let nomPropietario = document.getElementById('nom_propietario').value;

    // Verifica si el campo está vacío
    if (nomPropietario.trim() === '') {
        event.preventDefault(); // Evita el envío del formulario
        alert('El campo "Representante legal" no puede estar vacío.');
    }
});
</script>




