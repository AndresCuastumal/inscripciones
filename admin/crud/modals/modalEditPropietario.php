<div class="modal fade" id="editPropietario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Representante legal</h1>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-circle-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="editPropietarioForm" action ="crud/guardarUpdateRegPropietario.php" method="post">
                    <input type="hidden" name="id_edit_propietario" id="id_edit_propietario">    
                        <table class="table">
                        <tr>
                            <td>
                                <label for="edit_nom_propietario" class="form-label">* Nombre(s) representante legal:</label>
                                <input type="text" name="edit_nom_propietario" id="edit_nom_propietario" class="form-control" required>
                            </td>
                            <td>
                                <label for="edit_ape_propietario" class="form-label">* Apellido(s) representante legal:</label>
                                <input type="text" name="edit_ape_propietario" id="edit_ape_propietario" class="form-control" required>
                            </td>
                            
                        </tr>
                        <tr>
                            <td>
                                <label for="edit_td_propietario" class="form-label">* Tipo de documento:</label>
                                <div>
                                <select id="edit_td_propietario" name="edit_td_propietario" class="form-control" required>
                                    <option value= "" disabled selection selected>Seleccione tipo de documento</option>
                                    <option value="cc">Cédula de Ciudadanía</option>
                                    <option value="ce">Cédula de Exranjería</option>
                                    <option value="nit">Número de Identificación Tributaria</option> 
                                    <option value="ti">Tarjeta de Identidad</option>           
                                </select>
                                </div>
                            </td>
                            <td>
                                <label for="edit_doc_propietario" class="form-label">* Número de documento:</label>                                    
                                <input type="text" name="edit_doc_propietario" id="edit_doc_propietario" class="form-control" required>                                    
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="edit_tel_propietario" class="form-label">Número de teléfono representante legal</label>
                                <input type="text" name="edit_tel_propietario" id="edit_tel_propietario" class="form-control">
                            </td>
                            <td>
                                <label for="edit_correo_propietario" class="form-label">Correo personal</label>
                                <input type="email" name="edit_correo_propietario" id="edit_correo_propietario" class="form-control">
                            </td>                                
                        </tr>
                    </table>                    
                    <div class="">                    
                        <button type="submit" name="accion" value="continuar" class="btn btn-success"><i class="fa-solid fa-forward"></i> Guardar</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-circle-xmark"></i> Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>