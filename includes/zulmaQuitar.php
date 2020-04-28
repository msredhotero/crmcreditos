<?php

function columnaTablaModificar($id,$lblid,$tabla,$nombreCampo,$columnaSize,$catalogos) {
		$serviciosFunciones =   new Servicios();
		switch ($tabla) {
			default:
				$sqlMod = "select * from ".$tabla." where ".$lblid." = ".$id;
				$resMod = $this->query($sqlMod,0);
		}

		

		

		$lblcambio = $serviciosFunciones->traerLblCambioReemplazo($tabla,'lblCambio');
		$lblreemplazo =$serviciosFunciones->traerLblCambioReemplazo($tabla,'lblreemplazo');
		
		$sql	=	"show columns from ".$tabla;
		$res 	=	$this->query($sql,0);

		$ocultar = array("fechacrea","fechamodi","usuacrea","usuamodi");

		$camposEscondido = "";
		

		if ($res == false) {
			return 'Error al traer datos';
		} else {

			$camposFormulario= array();

			$form	=	'';

			while ($row = mysql_fetch_array($res)) {
				$label = $row[0];
				$i = 0;

				$refdescripcion = array();
				$refCampo = array();
				$lblcambio = array();
				$lblreemplazo = array();

				if(preg_match("/^ref/", $label)){
					// se trata de un catalogo se debe buscar su tabla
					$resCatalogo = $this->traerDatosCatalogo($catalogos[$label]);
					$cadRef2 = $serviciosFunciones->devolverSelectBox($resCatalogo,array(1),'');
					$refdescripcion[]=$cadRef2;
					$refCampo[]=$label;
				}


				foreach ($lblcambio as $cambio) {
					if ($row[0] == $cambio) {
						$label = $lblreemplazo[$i];
						$i = 0;
						break;
					} else {
						$label = $row[0];
					}
					$i = $i + 1;
				}

				if (in_array($row[0],$ocultar)) {
					$lblOculta = "none";
				} else {
					$lblOculta = "block";
				}

				if ($row[3] != 'PRI') {
					if (strpos($row[1],"decimal") !== false) {
						$camposFormulario[$nombreCampo] = '
						<div class="form-group col-md-6" style="display:'.$lblOculta.'">
							<label for="'.$label.'" class="control-label" style="text-align:left">'.ucwords($label).'</label>
							<div class="input-group col-md-12">
								<span class="input-group-addon">€</span>
								<input type="text" class="form-control" id="'.strtolower($row[0]).'" name="'.strtolower($row[0]).'" value="'.mysql_result($resMod,0,$row[0]).'" required>
								<span class="input-group-addon">.00</span>
							</div>
						</div>
						';
					} else {
						if ( in_array($row[0],$refCampo) ) {

							$campo = strtolower($row[0]);

							$option = $refdescripcion[array_search($row[0], $refCampo)];
							$form1	='
							<div class="form-group col-md-6" style="display:'.$lblOculta.'">
								<label for="'.$campo.'" class="control-label" style="text-align:left">'.$label.'</label>
								<div class="input-group col-md-12">
									<select class="form-control" id="'.strtolower($campo).'" name="'.strtolower($campo).'">
										';

							$form1	.=	$option;

							$form1	=	'</select>
								</div>
							</div>
							';
							$camposFormulario[$nombreCampo] = $form1;

						} else {

							if (strpos($row[1],"bit") !== false) {
								$label = ucwords($label);
								$campo = strtolower($row[0]);

								$activo = '';
								if (mysql_result($resMod,0,$row[0])==1){
									$activo = 'checked';
								}

								$camposFormulario[$nombreCampo] ='
								<div class="form-group col-md-6" style="display:'.$lblOculta.'">
									<label for="'.$campo.'" class="control-label" style="text-align:left">'.$label.'</label>
									<div class="input-group col-md-12 fontcheck">
										<input type="checkbox" '.$activo.' class="form-control" id="'.$campo.'" name="'.$campo.'" style="width:50px;" required> <p>Si/No</p>
									</div>
								</div>';


							} else {

								if (strpos($row[1],"date") !== false) {
									$label = ucwords($label);
									$campo = strtolower($row[0]);

									$camposFormulario[$nombreCampo]= '

									<div class="form-group col-md-6" style="display:'.$lblOculta.'">
										<label for="'.$campo.'" class="control-label" style="text-align:left">'.$label.'</label>
										<div class="input-group date form_date col-md-6" data-date="" data-date-format="dd MM yyyy" data-link-field="'.$campo.'" data-link-format="yyyy-mm-dd">
											<input class="form-control" value="'.mysql_result($resMod,0,$row[0]).'" size="50" type="text" value="" readonly>
											<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
										</div>
										<input type="hidden" name="'.$campo.'" id="'.$campo.'" value="'.mysql_result($resMod,0,$row[0]).'" />
									</div>
									';
									
								} else {

									if (strpos($row[1],"time") !== false) {
										$label = ucwords($label);
										$campo = strtolower($row[0]);

										$camposFormulario[$nombreCampo]= '

										<div class="form-group col-md-6" style="display:'.$lblOculta.'">
											<label for="'.$campo.'" class="control-label" style="text-align:left">'.$label.'</label>
											<div class="input-group bootstrap-timepicker col-md-6">
												<input id="timepicker2" value="'.mysql_result($resMod,0,$row[0]).'" name="'.$campo.'" class="form-control">
												<span class="input-group-addon">
<span class="glyphicon glyphicon-time"></span>
</span>
											</div>

										</div>

										';

									} else {
										if ((integer)(str_replace('varchar(','',$row[1])) > 200) {
											$label = ucwords($label);
											$campo = strtolower($row[0]);

											$camposFormulario[$nombreCampo]= '
											<div class="form-group col-md-6" style="display:'.$lblOculta.'">
												<label for="'.$campo.'" class="control-label" style="text-align:left">'.$label.'</label>
												<div class="input-group col-md-12">
													<textarea type="text" rows="10" cols="6" class="form-control" id="'.$campo.'" name="'.$campo.'" placeholder="Ingrese el '.$label.'..." required>'.utf8_encode(mysql_result($resMod,0,$row[0])).'</textarea>
												</div>
											</div>
											';

										} else {

											if ($row[1] == 'MEDIUMTEXT') {
											$label = ucwords($label);
											$campo = strtolower($row[0]);

											$camposFormulario[$nombreCampo]= '
											<div class="form-group col-md-12" style="display:'.$lblOculta.'">
												<label for="'.$campo.'" class="control-label" style="text-align:left">'.$label.'</label>
												<div class="input-group col-md-12">
													<textarea name="'.$campo.'" id="'.$campo.'" rows="200" cols="160">
														Ingrese la noticia.
													</textarea>
												</div>
											</div>';

											} else {
												$label = ucwords($label);
												$campo = strtolower($row[0]);

												$camposFormulario[$nombreCampo] = '
												<div class="form-group col-md-6" style="display:'.$lblOculta.'">
													<label for="'.$campo.'" class="control-label" style="text-align:left">'.$label.'</label>
													<div class="input-group col-md-12">
														<input type="text" value="'.(mysql_result($resMod,0,$row[0])).'" class="form-control" id="'.$campo.'" name="'.$campo.'" placeholder="Ingrese el '.$label.'..." required>
													</div>
												</div>';
											}
										}
									}
								}
							}
						}


					}
				} else {

					$camposFormulario[$nombreCampo] = '<input type="hidden" id="accion" name="accion" value="'.$accion.'"/>'.'<input type="hidden" id="id" name="id" value="'.$id.'"/>';
				}
			}
			
			$formulario = $form."<br><br>".$camposEscondido;
			return $camposFormulario;
		}
	}

?>