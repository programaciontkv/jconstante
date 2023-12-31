<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<section class="content-header">

     <!--  <h1>
        Formas de Pago
      </h1> -->
</section>
<section class="content">

	<div class="box box-solid">

		<div class="box box-body">
				<form id="exp_excel" style="float:right;padding:0px;margin: 0px;" method="post" action="<?php echo base_url();?>forma_pago/excel/<?php echo $permisos->opc_id?>" onsubmit="return exportar_excel()"  >
        	<input type="submit" value="EXCEL" class="btn btn-success" />
        	<input type="hidden" id="datatodisplay" name="datatodisplay">
       	</form>
			<div class="row">
				<div class="col-md-12">
					<?php 
					if($permisos->rop_insertar){
					?>
						<a href="<?php echo base_url();?>forma_pago/nuevo/<?php echo $permisos->opc_id?>" class="btn btn-success btn-flat"><span class="fa fa-plus"></span> Crear forma de pago</a>
					<?php 
					}
					?>
				</div>	
			</div>
			<br>
			<div class="row">
				<div class="col-md-12">
					<table id="tbl_list" class="table table-bordered table-list table-hover">
						<thead>
							<!-- <th>No</th> -->
							<th>Codigo SRI</th>
							<th>Descripcion</th>
							<th>Descripcion SRI</th>
							<th>Tipo</th>
						<!-- 	<th>Banco</th>
							<th>Tarjeta</th> -->
							<th>Estado</th>
							<th>Ajustes</th>
						</thead>
						<tbody>
						<?php 
						$n=0;
						if(!empty($formas_pago)){
							foreach ($formas_pago as $forma_pago) {
								$n++;
								switch ($forma_pago->fpg_tipo) {
									case "0": $tipo=""; break;
									case "1": $tipo="TARJETA DE CREDITO"; break;
				                    case "2": $tipo="TARJETA DE DEBITO"; break;
				                    case "3": $tipo="CHEQUE"; break;
				                    case "4": $tipo="EFECTIVO"; break;
				                    case "5": $tipo="CERTIFICADOS"; break;
				                    case "6": $tipo="TRANSFERENCIA"; break;
				                    case "7": $tipo="RETENCION"; break;
				                    case "8": $tipo="NOTA CREDITO"; break;
				                    case "9": $tipo="CREDITO"; break;
				                }
								

						?>
							<tr>
								<!-- <td><?php echo $n?></td> -->
								<td style="mso-number-format:'@'"><?php echo $forma_pago->fpg_codigo?></td>
								<td><?php echo $forma_pago->fpg_descripcion?></td>
								<td><?php echo $forma_pago->fpg_descripcion_sri?></td>
								<td><?php echo $tipo?></td>
							<!-- 	<td><?php echo $forma_pago->banco?></td>
								<td><?php echo $forma_pago->tarjeta?></td> -->
								<!-- <td><?php echo $forma_pago->est_descripcion?></td> -->

								<?php
								if($forma_pago->fpg_estado == 1){

								?>
								<td>
								
									 <img title="Inactivar Forma de pago" width="40px" height="40px" onclick="cambiar_es(2,<?php echo $forma_pago->fpg_id?>)" src="../imagenes/activo.png"> 
									
								</td>
								<?php
								}else{
								?>
								<td>
									 <img title="Activar Forma de pago" width="40px" height="40px" onclick="cambiar_es(1,<?php echo $forma_pago->fpg_id?>)" src="../imagenes/inactivo.png"> 
									
								</td>
								<?php
								}
								?>
								<td align="center">
									<div class="btn-group">
										<?php 
										if($permisos->rop_reporte){
										?>
											<!-- <button type="button" class="btn btn-info btn-view" data-toggle="modal" data-target="#modal-default" value="<?php echo base_url();?>forma_pago/visualizar/<?php echo $forma_pago->fpg_id?>"><span class="fa fa-eye"></span>
								            </button> -->
							            <?php
							        	}
										if($permisos->rop_actualizar){
										?>
											<a title="Editar Forma de pago" href="<?php echo base_url();?>forma_pago/editar/<?php echo $forma_pago->fpg_id?>/<?php echo $permisos->opc_id?>" class="btn btn-primary"> <span class="fa fa-edit"></span></a>
										<?php 
										}
										if($permisos->rop_eliminar){
										?>
									<!-- 	<a href="<?php echo base_url();?>forma_pago/eliminar/<?php echo $forma_pago->fpg_id?>/<?php echo $forma_pago->fpg_descripcion?>" class="btn btn-danger btn-remove"><span class="fa fa-trash"></span></a> -->
										<?php 
										}
										?>
									</div>
								</td>
							</tr>
						<?php
							}
						}
						?>
						</tbody>
					</table>
				</div>	
			</div>
		</div>
	</div>


</section>

<div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">forma_pago</h4>
              </div>
              <div class="modal-body">
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
</div>
<script type="text/javascript">
	function cambiar_es(estado,id){
		 var base_url='<?php echo base_url();?>';
		 var op = <?php echo $actual_opc; ?>;
		
		Swal.fire({
		  title: 'Desea cambiar de estado a la forma de pago?',
		  showCancelButton: true,
		  confirmButtonText: 'Guardar',
		  denyButtonText: `Cancelar`,
		}).then((result) => {
		  /* Read more about isConfirmed, isDenied below */
		  if (result.isConfirmed) {

		    var  uri=base_url+"forma_pago/cambiar_estado/"+estado+"/"+id+"/"+op;
				      $.ajax({
				              url: uri,
				              type: 'POST',
				              success: function(dt){
				              	if(dt==1){
				              	   window.location.href = window.location.href;
				              	}else{
				              		swal("Error!", "No se pudo modificar .!", "warning");
				              	}
				                
				              } 
				        });

		  } else if (result.isDenied) {
		    // Swal.fire('No ha registrado cambios', '', 'info');
		  }
		})
	   
		 
	}
	
</script>