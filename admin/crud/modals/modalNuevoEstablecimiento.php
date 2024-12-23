
<div class="modal fade" id="nuevoEstablecimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white d-flex justify-content-between align-items-center">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Nuevo Establecimiento</h1>
                <button type="button" class="btn btn-info" data-bs-dismiss="modal">
                    <i class="fa-solid fa-circle-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEstablecimiento" action ="crud/guardarNuevoEstablecimiento.php" method="post">
                    <div class="mb-2">
                        <table class="table">
                            <tr>
                                <input type="hidden" id="idP" name="idP"> <!-- Campo oculto para el ID del propietario -->
                                <td colspan="4">
                                    <label for="nomape_propietario" class="form-label">Representante Legal:</label>
                                    <input type="text" name="nomape_propietario" id="nomape_propietario" class="form-control-plaintext" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <label for="id_sujeto" class="form-label">Seleccione Sujeto:</label>
                                    <select name="id_sujeto" id="id_sujeto" class="form-control" required>
                                        <?php
                                        $consultaSujeto = $conn->prepare("SELECT * FROM sujeto");
                                        $consultaSujeto->execute();

                                        ?>
                                        <option value="">Seleccione...</option>
                                        <?php
                                        while ($row_registro = $consultaSujeto->fetch(PDO::FETCH_ASSOC)) { ?>
                                            <option value="<?= $row_registro['id']; ?>"><?= $row_registro['nom_sujeto']; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td colspan="2">
                                    <label for="id_clase" class="form-label">Seleccione clase:</label>
                                    <select name="id_clase" id="id_clase" class="form-control" required>                                        
                                    </select>
                                </td>                                
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <label for="nom_comercial" class="form-label">Nombre comercial:</label>
                                    <input type="text" name="nom_comercial" id="nom_comercial" class="form-control" required>
                                </td>
                                <td colspan="2">
                                    <label for="razon_social" class="form-label">Razón social:</label>                                        
                                    <input type="text" name="razon_social" id="razon_social" class="form-control" required>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <label for="nit" class="form-label">NIT Establecimiento:</label>                                        
                                    <input type="text" name="nit" id="nit" class="form-control" required>
                                </td>
                                <td>
                                    <label for="dv" class="form-label">DV:</label>                                        
                                    <input type="number" min="0" step="1" name="dv" id="dv" class="form-control" required>
                                </td>
                                <td>
                                    <label for="sucursal" class="form-label">Sucursal:</label>
                                    <input type="number" min="0" step="1" name="sucursal" id="sucursal" class="form-control" value="1" required>
                                </td>
                            </tr>                                
                            <tr>
                                <td colspan="2">
                                    <label for="dir_establecimiento" class="form-label">Dirección del establecimiento:</label>
                                    <input type="text" name="dir_establecimiento" id="dir_establecimiento" class="form-control" required>
                                </td>
                                <td colspan="2">
                                    <label for="id_barrio_vereda" class="form-label">Barrio establecimiento:</label>
                                    <select name="id_barrio_vereda" id="id_barrio_vereda" class="form-control" required onchange="obtenerDatos()">
                                        <option value="">Seleccione...</option>
                                        <?php
                                        $consultaBarrioVereda = $conn->prepare("SELECT id, nom_barrio, id_comuna FROM barrio");
                                        $consultaBarrioVereda->execute();

                                        while ($row_registro = $consultaBarrioVereda->fetch(PDO::FETCH_ASSOC)) { ?>
                                            <option value="<?= $row_registro['id']; ?>" data-id-comuna="<?= $row_registro['id_comuna']; ?>">
                                                <?= $row_registro['nom_barrio']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <input type="hidden" id="id_comuna" name="id_comuna">

                                    <!-- SCRIPT QUE ASIGNA UN VALOR CONSULTADO EN UNA TABLA PARA ALMACENARLA EN EL HIDDEN -->
                                    <script>
                                    document.getElementById("id_barrio_vereda").addEventListener("change", function() {
                                        console.log("La función obtenerDatos() se está ejecutando.");
                                        const select = document.getElementById("id_barrio_vereda");
                                        const id_comuna = select.options[select.selectedIndex].getAttribute("data-id-comuna");
                                        document.getElementById("id_comuna").value = id_comuna;
                                        console.log("ID Comuna seleccionado:", id_comuna);
                                    });
                                    </script>
                                </td>
                            </tr>
                            <tr>     
                                <td colspan="2">
                                    <label for="tel_establecimiento" class="form-label">Teléfono establecimiento:</label>
                                    <input type="number" min="0" step="1" name="tel_establecimiento" id="tel_establecimiento" class="form-control">
                                </td>
                                <td colspan="2">
                                    <label for="correo_establecimiento" class="form-label">Correo electrónico del establecimiento:</label>
                                    <input type="email" name="correo_establecimiento" id="correo_establecimiento" class="form-control">
                                </td>                           
                            </tr>
                        </table>
                    </div>
                    <div class="">
                        <button type="submit" name="accion" value="guardar" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-circle-xmark"></i> Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPT QUE AYUDA A CARGAR UN SELECT LIST DEPENDIENTE DE OTRO ANTERIOR EN ESTE CASO DEPENDIENDO DEL SUJETO ESCOGIDO -->
<script>
$(document).ready(function(){
    console.log("Document ready. El script está cargado.");

    // Carga de clases según el sujeto seleccionado
    $(document).on('change', '#id_sujeto', function(){
        var selectedSujeto = $(this).val(); 
        console.log("Sujeto seleccionado:", selectedSujeto);               
        $.ajax({
            type: "POST",
            url: "crud/cargar_clase.php", // Archivo PHP para cargar clase
            data: { id_sujeto: selectedSujeto },
            success: function(data){
                console.log("Respuesta de la carga:", data);
                $("#id_clase").html(data);
            },
        });
    });

    // Validación antes de enviar el formulario
    $('#formEstablecimiento').on('submit', function(event) {
        var idPropietario = $('#idP').val();
        console.log('ID Propietario:', idPropietario);
        
        if (!idPropietario) {
            event.preventDefault(); // Evita el envío si no hay valor
            alert('El ID del propietario no está definido.');
        }
    });
});
</script>


