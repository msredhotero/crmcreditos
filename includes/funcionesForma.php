<?php
class ServiciosForma{	
	
	function columnaTabla($tabla, $nombreCampo,$columnaSize,$catalogo) {
		$serviciosFunciones =   new Servicios();

		$refdescripcion = array();
		$refCampo = array();
		$lblcambio = array();
		$lblreemplazo = array();

		if(preg_match("/^ref/", $nombreCampo)){
			// se trata de un catalogo se debe buscar su tabla
			$resCatalogo = $this->traerDatosCatalogo($catalogo);
			$cadRef2 = $serviciosFunciones->devolverSelectBox($resCatalogo,array(1),'');
			$refdescripcion[]=$cadRef2;
			$refCampo[]=$nombreCampo;
		}

		$lblcambio = $serviciosFunciones->traerLblCambioReemplazo($tabla,'lblCambio');
		$lblreemplazo =$serviciosFunciones->traerLblCambioReemplazo($tabla,'lblreemplazo');

		#print_r($lblCambio);

		$sql	=	"show columns from ".$tabla;

		$sql	= "SHOW COLUMNS FROM ".$tabla." WHERE field LIKE '".$nombreCampo."'";
		$res 	=	$this->query($sql,0);
		$label  = '';
			#echo $sql."\n";

		//$ocultar =  $this->traerCamposParaOcultar($tabla);
		$geoposicionamiento = array("latitud","longitud");
		$ocultar =  array();
		$camposEscondido = "";
		/* Analizar para despues */
		/*if (count($refencias) > 0) {
			$j = 0;

			foreach ($refencias as $reftablas) {
				$sqlTablas = "select id".$reftablas.", ".$refdescripcion[$j]." from ".$reftablas." order by ".$refdescripcion[$j];
				$resultadoRef[$j][0] = $this->query($sqlTablas,0);
				$resultadoRef[$j][1] = $refcampos[$j];
			}
		}*/


		if ($res == false) {
			return 'Error al traer datos';
		} else {

			$form	=	'';
			$existeCampo = 0;	
			while ($row = mysql_fetch_array($res)) {
				#echo "entra a while". "\n";
				$label = $row[0];
				$i = 0;


					if($label == $nombreCampo){
					#echo "LABEL=>".$label. "campo=>".$nombreCampo."\n";
						$existeCampo = 1;
						if ($row[2]=='NO') {
							$lblObligatorio = ' required ';
						} else {
							$lblObligatorio = '';
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
						#echo "label despues del cambio=>".$label."n";

						if (in_array($row[0],$ocultar)) {
							$lblOculta = "none";
						} else {
							$lblOculta = "block";
						}

						if ($row[3] != 'PRI') {
							if (strpos($row[1],"decimal") !== false) {

								if (in_array($row[0],$geoposicionamiento)) {
									$form	=	$form.'

									<div class="col-lg-'.$columnaSize.' col-md-'.$columnaSize.' col-sm-'.$columnaSize.' col-xs-12" style="display:'.$lblOculta.'">
										<label for="'.$label.'" class="control-label" style="text-align:left">'.ucwords($label).'</label>
										<div class="input-group">
		                           <span class="input-group-addon">€</span>
		                           <div class="form-line">
		                              <input type="text" class="form-control" id="'.strtolower($row[0]).'" name="'.strtolower($row[0]).'" value="" '.$lblObligatorio.'>
		                           </div>
		                           <span class="input-group-addon">.00</span>
		                        </div>
									</div>

									';

								} else {

									$form	=	$form.'

									<div class="col-lg-'.$columnaSize.' col-md-'.$columnaSize.' col-sm-'.$columnaSize.' col-xs-12" style="display:'.$lblOculta.'">
										<label for="'.$label.'" class="control-label" style="text-align:left">'.ucwords($label).'</label>
										<div class="input-group">
		                           <span class="input-group-addon">€</span>
		                           <div class="form-line">
		                              <input type="text" class="form-control" id="'.strtolower($row[0]).'" name="'.strtolower($row[0]).'" value="" '.$lblObligatorio.'>
		                           </div>
		                           <span class="input-group-addon">.00</span>
		                        </div>
									</div>

									';
								}
							} else {
								if ( in_array($row[0],$refCampo) ) {
#echo "\n","el campo esta en refCampo "."\n";
									$campo = strtolower($row[0]);

									$option = $refdescripcion[array_search($row[0], $refCampo)];
									#echo "OPCTION\N".$row[0];
									#print_r($refCampo);

									#print_r($refdescripcion);
									/*
									$i = 0;
									foreach ($lblcambio as $cambio) {
										if ($row[0] == $cambio) {
											$label = $lblreemplazo[$i];
											$i = 0;
											break 2;
										} else {
											$label = $row[0];
										}
										$i = $i + 1;
									}*/

									$autocompletar = array("refclientevehiculos","refordenes");

									if (in_array($campo,$autocompletar)) {
										$form	=	$form.'

										<div class="form-group col-md-'.$columnaSize.' frmCont'.strtolower($campo).'" style="display:'.$lblOculta.'">
											<div class="form-line">
											<label for="'.$campo.'" class="control-label" style="text-align:left">'.$label.'</label>
											<div class="input-group col-md-12">

												<select data-placeholder="selecione el '.$label.'..." id="'.strtolower($campo).'" name="'.strtolower($campo).'" class="chosen-select" tabindex="2">
		            								<option value=""></option>
													';

										$form	=	$form.$option;

										$form	=	$form.'		</select>
											</div>
											</div>
										</div>

										';
									} else {
#echo "no esta en auto completar\n";
										$form	=	$form.'

										<div class="col-lg-'.$columnaSize.' col-md-'.$columnaSize.' col-sm-'.$columnaSize.' col-xs-12 frmCont'.strtolower($campo).'" style="display:'.$lblOculta.'">
										<label for="'.$campo.'" class="control-label" style="text-align:left">'.$label.'</label>
										<div class="input-group col-md-12">
											<select class="form-control" id="'.strtolower($campo).'" name="'.strtolower($campo).'" '.$lblObligatorio.'>

													';

										$form	=	$form.$option;

										$form	=	$form.'</select>
										</div>
										</div>



										';
										#echo "\n FROM\n";
										echo $form."\n";
									}

								} else {

									if (strpos($row[1],"bit") !== false) {
										$label = ucwords($label);
										$campo = strtolower($row[0]);

										$form	=	$form.'

										<div class="col-lg-'.$columnaSize.' col-md-'.$columnaSize.' col-sm-'.$columnaSize.' col-xs-12 frmCont'.strtolower($campo).'" style="display:'.$lblOculta.'">
											<label for="'.$campo.'" class="control-label" style="text-align:left">'.$label.'</label>
											<div class="switch">
												<label><input type="checkbox"  id="'.$campo.'" name="'.$campo.'"/><span class="lever switch-col-green"></span></label>
											</div>
										</div>

										';


									} else {

										if (strpos($row[1],"date") !== false) {
											$label = ucwords($label);
											$campo = strtolower($row[0]);

											/*if (($row[0] == "fechabaja2") || ($row[0] == "fechaalta2")){*/
												$form	=	$form.'
												<div class="col-lg-'.$columnaSize.' col-md-'.$columnaSize.' col-sm-'.$columnaSize.' col-xs-12 frmCont'.strtolower($campo).'" style="display:'.$lblOculta.'">
												<b>'.$label.'</b>
												<div class="input-group">

												<span class="input-group-addon">
													 <i class="material-icons">date_range</i>
												</span>
		                                <div class="form-line">

												   	<input readonly="readonly" style="width:200px;" type="text" class="datepicker form-control" id="'.$campo.'" name="'.$campo.'" '.$lblObligatorio.' />

		                                </div>
		                              </div>
		                              </div>
												';

										} else {

											if (strpos($row[1],"time") !== false) {
												$label = ucwords($label);
												$campo = strtolower($row[0]);

												$form	=	$form.'

												<div class="form-group col-md-'.$columnaSize.'" style="display:'.$lblOculta.'">
													<label for="'.$campo.'" class="control-label" style="text-align:left">'.$label.'</label>
													<div class="input-group col-md-'.$columnaSize.'">
														<input id="'.$campo.'" name="'.$campo.'" class="form-control">
														<span class="input-group-addon">
		<span class="glyphicon glyphicon-time"></span>
		</span>
													</div>

												</div>

												';

											} else {
												if ($row[1] == 'MEDIUMTEXT') {
													$label = ucwords($label);
													$campo = strtolower($row[0]);

													$form	=	$form.'

													<div class="form-group col-md-12" style="display:'.$lblOculta.'">
														<label for="'.$campo.'" class="control-label" style="text-align:left">'.$label.'</label>
														<div class="input-group col-md-12">
															<textarea name="'.$campo.'" id="'.$campo.'" rows="200" cols="160">
																Ingrese la noticia.
															</textarea>


														</div>

													</div>

													';

												} else {

													if ((integer)(str_replace('varchar(','',$row[1])) > 300) {
														$label = ucwords($label);
														$campo = strtolower($row[0]);

														$form	=	$form.'
														<div class="col-sm-12" style="display:'.$lblOculta.'">
														<label for="'.$campo.'" class="control-label" style="text-align:left">'.$label.'</label>
															<div class="form-group">
																<div class="form-line">
																	<textarea rows="2" class="form-control no-resize" id="'.$campo.'" name="'.$campo.'" placeholder="Ingrese el '.$label.'..."></textarea>
																</div>
															</div>
														</div>


														';

														} else {

														if ($row[0]=='imagen') {
															$label = ucwords($label);
															$campo = strtolower($row[0]);


															$form	=	$form.'

															<div class="col-md-12 col-xs-12" style="margin-left:-5px; margin-right:0px;">

																	<div class="row">
																		<div class="custom-file" id="customFile">
																			<input type="file" name="'.$campo.'" class="custom-file-input" id="exampleInputFile" aria-describedby="fileHelp" required>
																			<label class="custom-file-label" for="exampleInputFile">
																				Seleccionar Archivo (tamaño maximo del archivo 4 MB)
																			</label>
																		</div>

													            </div>
															</div>
															';
														}else {
															if (strpos($row[1],"int") !== false) {
																$label = ucwords($label);
																$campo = strtolower($row[0]);


																$form	=	$form.'
																<div class="col-lg-'.$columnaSize.' col-md-'.$columnaSize.' col-sm-'.$columnaSize.' col-xs-12" style="display:'.$lblOculta.'">
																	<label class="form-label">'.$label.'</label>
																	<div class="form-group">
																		<div class="form-line">
																			<input type="number" class="form-control" id="'.$campo.'" name="'.$campo.'" '.$lblObligatorio.'/>

																		</div>
																	</div>
																</div>

																';

															} else {
																if ($label == 'email') {
																	$label = ucwords($label);
																	$campo = strtolower($row[0]);


																	$form	=	$form.'
																	<div class="col-lg-'.$columnaSize.' col-md-6 col-sm-'.$columnaSize.' col-xs-12" style="display:'.$lblOculta.'">
																		<label class="form-label">'.$label.'</label>
																		<div class="form-group input-group">
																			<div class="form-line">
																				<input type="email" class="form-control" id="'.$campo.'" name="'.$campo.'" '.$lblObligatorio.'/>

																			</div>
																		</div>
																	</div>

																	';
																} else {
																	$label = ucwords($label);
																	$campo = strtolower($row[0]);


																	$form	=	$form.'
																	<div class="col-lg-'.$columnaSize.' col-md-6 col-sm-'.$columnaSize.' col-xs-12 frmCont'.strtolower($campo).'" style="display:'.$lblOculta.'">
																		<label class="form-label">'.$label.'</label>
																		<div class="form-group input-group">
																			<div class="form-line">
																				<input type="text" class="form-control" id="'.$campo.'" name="'.$campo.'" '.$lblObligatorio.'/>

																			</div>
																		</div>
																	</div>

																	';
																}

															}

														}

													}
												}
											}
										}
									}
								}


							}
						} else {

							$camposEscondido = $camposEscondido.'<input type="hidden" id="accion" name="accion" value="'.$accion.'"/>';
						}
				}// cierra elif campo	
			}// cierra while
			if(!$existeCampo){
				$form = "<div>El campo  <b><span style='color:#f00'>".$nombreCampo."</span></b> no existe en esta tabla por favor verifique la estructura de la tabla</div>";
			}

			//$formulario = $form."<br><br>".$camposEscondido;
			$formulario = $form;
			return $formulario;

		}//cierra else
	}// termina funcion


	// campos a modificar aqui/////////////////////////////////
function columnaTablaModificar($id,$lblid,$tabla,$nombreCampo,$columnaSize,$catalogos) {
		$serviciosFunciones =   new Servicios();
		switch ($tabla) {
			default:
				$sqlMod = "select * from ".$tabla." where ".$lblid." = ".$id;
				echo "\n".$sqlMod;
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

				echo "\n********".$label."n";

				$refdescripcion = array();
				$refCampo = array();
				$lblcambio = array();
				$lblreemplazo = array();

				if(preg_match("/^ref/", $label)){
					// se trata de un catalogo se debe buscar su tabla
					$resCatalogo = $this->traerDatosCatalogo($catalogos[$label]);
					//$cadRef2 = $serviciosFunciones->devolverSelectBox($resCatalogo,array(1),'');
					$cadRef2 = $serviciosFunciones->devolverSelectBoxActivo($resCatalogo, array(1), $delimitador, mysql_result($resMod,0,$row[0]));
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
					echo "\n NO llave primaria  	\n";
					if (strpos($row[1],"decimal") !== false) {
						$camposFormulario[$label] = '
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
								echo "ES UNA CATALOGO\n";
							$campo = strtolower($row[0]);

							$option = $refdescripcion[array_search($row[0], $refCampo)];
							print_r($option);
							$form1	='
							<div class="form-group col-md-6" style="display:'.$lblOculta.'">
								<label for="'.$campo.'" class="control-label" style="text-align:left">'.$label.'</label>
								<div class="input-group col-md-12">
									<select class="form-control" id="'.strtolower($campo).'" name="'.strtolower($campo).'">
										';

							$form1	.=	$option;

							$form1	.=	'</select>
								</div>
							</div>
							';

						echo  $form1;
							$camposFormulario[$label] = $form1;

						} else {

							if (strpos($row[1],"bit") !== false) {
								$label = ucwords($label);
								$campo = strtolower($row[0]);

								$activo = '';
								if (mysql_result($resMod,0,$row[0])==1){
									$activo = 'checked';
								}

								$camposFormulario[$label] ='
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

									$camposFormulario[$label]= '

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

										$camposFormulario[$label]= '

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

											$camposFormulario[$label]= '
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

											$camposFormulario[$label]= '
											<div class="form-group col-md-12" style="display:'.$lblOculta.'">
												<label for="'.$campo.'" class="control-label" style="text-align:left">'.$label.'</label>
												<div class="input-group col-md-12">
													<textarea name="'.$campo.'" id="'.$campo.'" rows="200" cols="160">
														Ingrese la noticia.
													</textarea>
												</div>
											</div>';

											} else {
												echo "\nstring=>".$row[1]. " ".$label;
												$label = ucwords($label);
												$campo = strtolower($row[0]);

												$camposFormulario[$label] = '
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

					$camposFormulario[$label] = '<input type="hidden" id="accion" name="accion" value="'.$accion.'"/>'.'<input type="hidden" id="id" name="id" value="'.$id.'"/>';
				}
			}
			
			$formulario = $form."<br><br>".$camposEscondido;
			return $camposFormulario;
		}
	}	



	////////////////////////////////////////////////////////////

		function query($sql,$accion) {

		require_once 'appconfig.php';

		$appconfig	= new appconfig();
		$datos		= $appconfig->conexion();
		$hostname	= $datos['hostname'];
		$database	= $datos['database'];
		$username	= $datos['username'];
		$password	= $datos['password'];


		$conex = mysql_connect($hostname,$username,$password) or die ("no se puede conectar".mysql_error());

		mysql_select_db($database);
		mysql_query("SET NAMES 'utf8'");
		        $error = 0;
		mysql_query("BEGIN");
		$result=mysql_query($sql,$conex);
		if ($accion && $result) {
			$result = mysql_insert_id();
		}
		if(!$result){
			$error=1;
		}
		if($error==1){
			mysql_query("ROLLBACK");
			return false;
		}
		 else{
			mysql_query("COMMIT");
			return $result;
		}

	}

	function generaFormGroup($inside){
		$formGroup = '';
		$formGroup ='<div class="row">';
		if(is_array($inside)) {
			foreach ($inside as $key => $value) {
				$formGroup .= $value;
			}
		}else{
			$formGroup .= $inside;
		}		
		$formGroup .= '</div>';
		return $formGroup;

	}
	function generaFormGroupEmcabezado($inside){
		$formGroup = '';
		$formGroup ='<div class="encabezadoDatos">';
		if(is_array($inside)) {
			foreach ($inside as $key => $value) {
				$formGroup .= $value;
			}
		}else{
			$formGroup .= $inside;
		}		
		$formGroup .= '</div>';
		return $formGroup;

	}

	function traerDatosCatalogo($tabla){
		$sql = "SELECT * FROM ".$tabla." WHERE 1";		
		$res = $this->query($sql,0);

		if ($res == false) {
			return 'Error al traer datos';
		} else {
			return $res;
		}
	}

	function formateaCol($campo, $size){
		$claseNueva = '';
		$cadNew = 'col-lg-'.$size.' col-md-'.$size.' col-sm-'.$size.' col-xs-12';
		$cadReplaced = 'class="form-group col-md-6"';		
		$claseNueva = str_replace($cadReplaced ,$cadNew,$campo);
	return $claseNueva;
	}


}
?>