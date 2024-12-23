<div class="modal fade" id="editPropietario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white d-flex justify-content-between align-items-center">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Registrar Nuevo Representante legal</h1>
                <button type="button" class="btn btn-info" data-bs-dismiss="modal">
                    <i class="fa-solid fa-circle-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditPropietario">
                    
                        <table class="table">
                            <tr>
                                <td>
                                    <label for="edit_nom_propietario" class="form-label">Nombre(s) representante legal:</label>
                                    <input type="text" name="edit_nom_propietario" id="edit_nom_propietario" class="form-control" required>
                                </td>
                                <td>
                                    <label for="edit_ape_propietario" class="form-label">Apellido(s) representante legal:</label>
                                    <input type="text" name="edit_ape_propietario" id="edit_ape_propietario" class="form-control" required>
                                </td>
                                
                            </tr>
                            <tr>
                                <td>
                                    <label for="edit_tipo_doc" class="form-label">Tipo de documento:</label>
                                    <div>
                                    <select id="edit_tipo_doc" name="edit_tipo_doc" class="form-control" required>
                                        <option value= "" disabled selection selected>Seleccione tipo de documento</option>
                                        <option value="cc">Cédula de Ciudadanía</option>
                                        <option value="ce">Cédula de Exranjería</option>
                                        <option value="nit">Número de Identificación Tributaria</option> 
                                        <option value="ti">Tarjeta de Identidad</option>           
                                    </select>
                                    </div>
                                </td>
                                <td>
                                    <label for="edit_doc" class="form-label">Número de documento:</label>                                    
                                    <input type="text" name="edit_doc" id="edit_doc" class="form-control" required>                                    
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
                    <button type="submit" name="accion" value="guardar" class="btn btn-info"><i class="fa-solid fa-floppy-disk"></i> Guardar y cerrar</button>
                        <button type="submit" name="accion" value="continuar" class="btn btn-success"><i class="fa-solid fa-forward"></i> Guardar y continuar</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-circle-xmark"></i> Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include "crud/modals/modalNuevoEstablecimiento.php";  ?>   