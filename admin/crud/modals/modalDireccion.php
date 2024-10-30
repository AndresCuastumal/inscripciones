<div class="modal fade" id="modalcapturadireccion" tabindex="10" aria-labelledby="modalcapturadireccion" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" style="z-index: 15000; background-color: rgba(0, 0, 0, 1)">
	<div class="modal-dialog modal-lg" style="border: 1px solid white">
		<div class="modal-content" style="background-color:#1e1e1e">
			<div class="modal-header" style="height:50px">
				<h5 class="modal-title text-info" id="exampleModalLabel">Registre la Dirección</h5>
				<button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal" aria-label="Close">X</button>
			</div>
			<div id="direccionCompletaContainer">
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-3">
							<div class="d-flex align-items-center" style="color:RED">(*)
								<select class="form-select form-select-sm" name="dir1" id="dir1" style="width:150px">
									<option selected value=""> </option>
									<option value="Calle">Calle</option>
									<option value="Carrera">Carrera</option>
									<option value="Diagonal">Diagonal</option>
									<option value="Transversal">Transversal</option>
									<option value="Avenida">Avenida</option>
									<option value="Manzana">Manzana</option>
									<option value="Callejón">Callejón</option>
									<option value="Carretera">Carretera</option>
									<option value="Circunvalar">Circunvalar</option>
									<option value="Kilómetro">Kilómetro</option>
									<option value="Finca">Finca</option>
									<option value="Vereda">Vereda</option>
									<option value="Via">Via</option>
									<option value="Hacienda">Hacienda</option>
									<option value="Corregimiento">Corregimiento</option>
								</select>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="d-flex align-items-center" style="color:RED">(*)
								<input type="text" maxlength="2" id="dir2" name="dir2" style="width:150px" class="form-control form-control-sm" placeholder="#" autocomplete="off">
							</div>
						</div>
						<div class="col-sm-3">
							<select class="form-select form-select-sm" name="dir3" id="dir3" style="width:150px">
								<option selected value=""> </option>
								<option value="A">A</option>
								<option value="B">B</option>
								<option value="C">C</option>
								<option value="D">D</option>
								<option value="E">E</option>
								<option value="F">F</option>
								<option value="G">G</option>
								<option value=" Casa"> Casa</option>
							</select>
						</div>
						<div class="col-sm-3">
							<select class="form-select form-select-sm" name="dir4" id="dir4" style="width:150px">
								<option selected value=""> </option>
								<option value="NORTE">Norte</option>
								<option value="SUR">Sur</option>
								<option value="ESTE">Este</option>
								<option value="OESTE">Oeste</option>
							</select>
						</div>
					</div>
					<div class="row" style="height:5px"></div>				
					<div class="row">
						<div class="col-sm-3"></div>
						<div class="col-sm-3">
							<div class="d-flex align-items-center">
								<font color="RED" >(*) </font> #
								<input type="text" maxlength="2" id="dir5" name="dir5" style="width:140px" class="form-control form-control-sm" placeholder="#" autocomplete="off">
							</div>
						</div>
						<div class="col-sm-3">
							<select class="form-select form-select-sm" name="dir6" id="dir6" style="width:150px">
								<option selected value=""> </option>
								<option value="A">A</option>
								<option value="B">B</option>
								<option value="C">C</option>
								<option value="D">D</option>
								<option value="E">E</option>
								<option value="F">F</option>
								<option value="G">G</option>
							</select>
						</div>	
						<div class="col-sm-3">
							<div class="d-flex align-items-center" style="color:RED">(*)						
								<input type="text" maxlength="2" id="dir7" name="dir7" style="width:150px" class="form-control form-control-sm" placeholder="#" autocomplete="off">
							</div>
						</div>	
					</div>
					<div class="row" style="height:5px"></div>
					<div class="row">
						<div class="col-sm-4">
								Barrio <font color="RED" >(*) </font>
								<select class="form-select form-select-sm" name="slbarrio" id="slbarrio">
									<option selected value="0"></option>
									<?php   
										$consultasql = "select * from barrio_vereda order by nombre";
										$consulta = mysqli_query($conexion,$consultasql);
										while($fila=mysqli_fetch_assoc($consulta)) {
											echo "<option value='".$fila['ID_barrio']."'>".$fila['nombre']."</option>";
										}
									?>						
								</select>
						</div>
						<div class="col-sm-8">
							<label class="form-label" for="dir9">Información Adicional </label>
							<br><strong><span id="contadordireccion" style="font-size:12px"></span></strong>
							<textarea maxlength="255" id="dir9" name="dir9" class="form-control form-control-sm" placeholder="Digite información adicional de la dirección" autocomplete="off"></textarea>
						</div>
					</div>				
					<div class="row" style="height:5px"></div>				
					<div class="row">
						<textarea id="direccion-completa" disabled name="direccion-completa"></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-info btn-sm" style="color:black" data-bs-dismiss="modal" id="botonGuardar" name="botonGuardar"><i class="fa-solid fa-floppy-disk"></i> Continuar</button>
				<button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal" style="color:black" id="botoncerrar" name="botoncerrar"><i class="fa-solid fa-circle-xmark"></i> Cerrar</button></center>
			</div>
		</div>
	</div>
</div>
<script>
	document.getElementById("dir9").addEventListener("input", function(){ actualizarContadorDireccion("dir9")}); // Conteo de carácteres del campo Información Adicional del modal "capturadireccion"
	function actualizarContadorDireccion(nombreInput){ // Actualizar dinámicamente el número de caracteres restantes del campo información adicional del modal "modalcapturadireccion"
		const ddireccion_ = document.getElementById(nombreInput);
		const contadordireccion_ = document.getElementById("contadordireccion");
		const longitudTexto = ddireccion_.value.length;
		const caracteresRestantes = 255 - longitudTexto;
		contadordireccion_.textContent = "Caracteres Restantes: "+caracteresRestantes;
	}
	//asigno los inputs del Modal a las variables que voy a utilizar para construir la dirección completa
	var dir1Input = document.getElementById('dir1');
	var dir2Input = document.getElementById('dir2');
	var dir3Input = document.getElementById('dir3');
	var dir4Input = document.getElementById('dir4');
	var dir5Input = document.getElementById('dir5');
	var dir6Input = document.getElementById('dir6');
	var dir7Input = document.getElementById('dir7');
	var dir8Input = document.getElementById('slbarrio');
	var dir9Input = document.getElementById('dir9');
	var barrioguardarInput = document.getElementById('slbarrio');
	var direccionCompletaInput = document.getElementById('direccion-completa');
	//Evaluo el evento "change" a cada input del Modal para que se vaya construyendo la dirección completa
	dir1Input.addEventListener('change', construirDireccionCompleta);
	dir2Input.addEventListener('change', construirDireccionCompleta);
	dir3Input.addEventListener('change', construirDireccionCompleta);
	dir4Input.addEventListener('change', construirDireccionCompleta);
	dir5Input.addEventListener('change', construirDireccionCompleta);
	dir6Input.addEventListener('change', construirDireccionCompleta);
	dir7Input.addEventListener('change', construirDireccionCompleta);
	dir8Input.addEventListener('change', construirDireccionCompleta);
	dir9Input.addEventListener('change', construirDireccionCompleta);
	function construirDireccionCompleta() { //Construye la dirección completa a partir del contenido de los inputs del modal "modalcapturadireccion"
		direccionCompletaInput.value = dir1Input.value+ " "+dir2Input.value+dir3Input.value+" "+dir4Input.value+" # "+dir5Input.value+dir6Input.value+" - "+dir7Input.value+", "+dir8Input.selectedOptions[0].textContent+", "+dir9Input.value;
		direccionCompletasinbarrio = dir1Input.value+ " "+dir2Input.value+dir3Input.value+" "+dir4Input.value+" # "+dir5Input.value+dir6Input.value+" - "+dir7Input.value+", "+dir9Input.value;
	}
</script>