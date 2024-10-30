
<div class="modal fade" id="nuevoEstablecimiento2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white d-flex justify-content-between align-items-center">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Nuevo Establecimiento</h1>
                <button type="button" class="btn btn-info" data-bs-dismiss="modal">
                    <i class="fa-solid fa-circle-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEstablecimiento2" action ="crud/guardarNuevoEstablecimiento.php" method="post">
                    <div class="mb-2">
                        <table class="table">
                            <tr>
                                <input type="hidden" id="id_propietario2" name="id_propietario2"> <!-- Campo oculto para el ID del propietario -->
                                <td colspan = "4">
                                    <label for="nomape_propietario" class="form-label">Representante legal:</label>
                                    <input type="text" name="nomape_propietario" id="nomape_propietario" class="form-control-plaintext"  readonly>
                                </td>
                            </tr>
                            <tr>    
                                <td colspan="2">
                                    <label for="id_sujeto2" class="form-label">Seleccione Sujeto:</label>
                                    <select name="id_sujeto2" id="id_sujeto2" class="form-control" required>
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
                                    <label for="id_clase2" class="form-label">Seleccione clase:</label>
                                    <select name="id_clase2" id="id_clase2" class="form-control" required>                                        
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
                                    <input type="number" name="dv" id="dv" class="form-control" required>
                                </td>
                                <td>
                                    <label for="sucursal" class="form-label">Sucursal:</label>
                                    <input type="number" name="sucursal" id="sucursal" class="form-control" value="1" required>
                                </td>
                            </tr>                                
                            <tr>
                                <td colspan="2">
                                    <label for="dir_establecimiento" class="form-label">Dirección del establecimiento:</label>
                                    <input type="text" name="dir_establecimiento" id="dir_establecimiento" class="form-control" required>
                                </td>
                                <td colspan="2">
                                    <label for="id_barrio_vereda" class="form-label">Barrio establecimiento:</label>
                                    <select name="id_barrio_vereda" id="id_barrio_vereda" class="form-control" required>
                                        <?php
                                        $consultaBarrioVereda = $conn->prepare("SELECT * FROM barrio");
                                        $consultaBarrioVereda->execute();

                                        ?>
                                        <option value="">Seleccione...</option>
                                        <?php
                                        while ($row_registro = $consultaBarrioVereda->fetch(PDO::FETCH_ASSOC)) { ?>
                                            <option value="<?= $row_registro['id']; ?>"><?= $row_registro['nom_barrio']; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <label for="tel_establecimiento" class="form-label">Teléfono establecimiento:</label>
                                    <input type="text" name="tel_establecimiento" id="tel_establecimiento" class="form-control">
                                </td>     
                                <td colspan="2">
                                    <label for="correo_establecimiento" class="form-label">Correo del establecimiento:</label>
                                    <input type="text" name="correo_establecimiento" id="correo_establecimiento" class="form-control">
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
<script>
    console.log(document.getElementById('id_clase'));
    console.log("Script cargado correctamente"); 
        $(document).ready(function(){
            console.log("Document ready. El script está cargado.");
            $(document).on('change', '#id_sujeto2', function(){
                console.log("El evento change se disparó");
                var selectedSujeto = $(this).val(); 
                console.log("Sujeto seleccionado:", selectedSujeto);               
                $.ajax({
                    type: "POST",
                    url: "crud/cargar_clase.php", // Archivo PHP para cargar clase
                    data: { id_sujeto: selectedSujeto                            
                    },

                    success: function(data){
                        console.log($("#id_clase2"));
                        console.log("Respuesta de la carga:", data);                        
                        $("#id_clase2").html(data);
                    },
                    
                });
            });            
        });
    </script>
