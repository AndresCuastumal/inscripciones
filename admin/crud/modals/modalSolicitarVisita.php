<div class="modal fade" id="solicitarVisita" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white d-flex justify-content-between align-items-center">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Solicitud de Visita / Inscripción de establecimiento</h1> 
                <button type="button" class="btn btn-success" data-bs-dismiss="modal"><i class="fa-solid fa-circle-xmark"></i></button>      
            </div>
            <div class="modal-body">
                <form action ="crud/guardarSolicitudVisita.php" method="post">
                    <input type="hidden" name="id" id="id">                
                    <div class="mb-2">
                        <table class="table table-sm mt-4">                
                            <tr>
                                <td class="w-50">                                        
                                    <label for="nom_comercial" class="form-label text-primary">Nombre del establecimiento:</label>
                                    <input type="text" name="nom_comercial" id="nom_comercial" class="form-control-plaintext" readonly >                                   
                                </td>
                                <td class="w-50">
                                    <label for="nit" class="form-label text-primary">NIT Establecimiento:</label>
                                    <div class="w-30">
                                        <input type="text" name="nit" id="nit" class ="form-control-plaintext" readonly >
                                    </div>
                                </td>
                                <td class="w-25">    
                                    <label for="dv" class="form-label text-primary">DV:</label>
                                    <input type="text" name="dv" id="dv" class ="form-control-plaintext" readonly>
                                </td>                                                                                    
                            </tr>
                            <tr>                    
                                <td>
                                    <label for="dir_establecimiento" class="form-label text-primary">Dirección:</label>
                                    <div class="w-30">
                                        <input type="text" name="dir_establecimiento" id="dir_establecimiento" class ="form-control-plaintext" readonly>
                                    </div>
                                </td>
                                <td>
                                    <label for="nom_barrio" class="form-label text-primary">Barrio/Vereda:</label>
                                    <div class="w-30">
                                        <input type="text" name="nom_barrio" id="nom_barrio" class ="form-control-plaintext" readonly>
                                    </div>
                                </td>
                                <td>
                                    <label for="nom_comuna" class="form-label text-primary">Comuna/Corregimiento:</label>
                                    <div class="w-30">
                                        <input type="text" name="nom_comuna" id="nom_comuna" class ="form-control-plaintext" readonly>
                                        <input type="hidden" id="id_comuna" name="id_comuna">
                                    </div>
                                </td>                                                    
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <label for="tel_establecimiento" class="form-label text-primary">Número Telefónico:</label>
                                    <div class="w-30">
                                        <input type="text" name="tel_establecimiento" id="tel_establecimiento" class ="form-control-plaintext" readonly>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="nom_propietario" class="form-label text-primary">Representante legal:</label>
                                    <div class="w-30">
                                        <input type="text" name="nom_propietario" id="nom_propietario" class ="form-control-plaintext" readonly>
                                    </div>
                                </td>
                                <td >
                                    <label for="doc_propietario" class="form-label text-primary">No de identificación:</label>
                                    <div class="w-30">
                                        <input type="text" name="doc_propietario" id="doc_propietario" class ="form-control-plaintext" readonly>
                                    </div>
                                </td>
                                <td class="w-50">
                                    <label for="nom_clase" class="form-label text-primary">Sujeto:</label>
                                    <div class="w-30">
                                        <input type="text" name="nom_clase" id="nom_clase" class ="form-control-plaintext" readonly>                                        
                                        <input type="hidden" id="id_sujeto_visita" name="id_sujeto_visita">
                                    </div>
                                </td>
                                <td></td>                                     
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <label for="tipo_solicitud" class="form-label text-primary">Tipo solicitud:</label>
                                    <div>
                                        <select id="tipo_solicitud" name="tipo_solicitud" class="form-control" required>
                                            <option value= "" disabled selection selected>Seleccione tipo de solicitud</option>
                                            <option value="Visita por primera vez">Visita por primera vez</option>
                                            <option value="Renovación de concepto sanitario">Renovación de concepto sanitario</option>
                                            <option value="Para levantamiento de medida sanitaria">Para levantamiento de medida sanitaria</option>                                                      
                                        </select>
                                    </div>
                                </td>
                                <td rowspan= "2" class="w-50">
                                    <label for="observacion" class="form-label text-primary">Observación:</label>
                                    <div class="w-30">
                                        <textarea id="observacion" name="observacion" rows="6" cols="30" placeholder="Escribe tus comentarios aquí..."></textarea>
                                    </div>
                                </td>
                            <tr>
                                <td colspan = "2" class="w-50">
                                    <label for="nom_solicitante" class="form-label text-primary">Solicitado por:</label>
                                    <span 
                                        class="ms-2" 
                                        data-bs-toggle="tooltip" 
                                        data-bs-placement="right" 
                                        title="Escriba el nombre del solicitante si es diferente al representante legal">
                                        <i class="bi bi-info-circle-fill text-primary"></i>
                                    </span> 
                                    <div>
                                        <input type="text" name="nom_solicitante" id="nom_solicitante" class ="form-control" >
                                    </div>
                                </td>
                            </tr>                                                                                                       
                            </tr>                            
                        </table>
                    </div>          
                    <div class="">
                        <button type="submit" name="accion" value ="guardar" class="btn btn-success"><i class="fa-solid fa-print"></i> Imprimir</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-circle-xmark"></i> Cancelar</button>                
                    </div>
                </form>  
            </div>
        </div>
    </div>    
</div>
