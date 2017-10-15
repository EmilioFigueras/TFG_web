			<div id="page-wrapper">
				<div class="graphs">
					<h3 class="blank1">Comentarios a valorar</h3>
					<?php
					if(!empty($msg_db)){
						echo "<div class='alert alert-danger' role='alert'>".$msg_db."</div>";
					}
					if(!empty($msg_ok)){
						echo "<div class='alert alert-success' role='alert'>".$msg_ok."</div>";
					} 
					if(empty($comments)){
						echo "<div class='alert alert-success' role='alert'>No quedan comentarios que valorar</div>";
					}else{

					?>
					<div class="xs tabls">
						<div class="bs-example4" data-example-id="contextual-table">
						<table class="table">
						  <thead>
							<tr>
							  <th>ID</th>
							  <th>Comentario</th>
							  <th>Valoración</th>
							  <th>¿Es positivo?</th>
							</tr>
						  </thead>
						  <tbody>
					<form class="form-horizontal" action="<?=base_url()?>index.php/admincustomer_controller/insert_rate" method="POST">
						<?php
							$i=1; //Name de cada linea
							foreach ($comments as $key => $value) { 
								?>
								<tr>
								  <th scope="row"><?=$value->autoid?></th>
								  <td><?=$value->comment?></td>
								  	<div class="form-group">
										<div class="col-sm-8">
											<td><div class="radio-inline"><input type="radio" name="comments_rate_<?=$i?>" value="Sí"
												<?php
													if(strcmp($value->personalRating, "Sí") === 0 )
														echo "checked";
												?>
												> Sí</div></td>
											<td><div class="radio-inline"><input type="radio" name="comments_rate_<?=$i?>" value="No"
												<?php
													if(strcmp($value->personalRating, "No") === 0 )
														echo "checked";
												?>
												> No</div></td>
											<input type="hidden" name="comments_idComment_<?=$i?>" value="<?=$value->autoid?>">
											<input type="hidden" name="comments_comment_<?=$i?>" value="<?=$value->comment?>">
											<?php $i++; ?>
										</div>
									</div>
								</tr>
								


						<?php
							}
							if(empty($offset))
								$offset = 0;
						?>
								</tbody>
							</table>
						</div>
						<input type="hidden" name="offset" value="<?=$offset?>">
						<div class="col-sm-11 jlkdfj1" align="right">
							<p class="help-block" ><input type="submit" class="btn-success btn" value="Enviar"></p>
						</div>
					</form>
				</div>
				<?php 
					} //Fin if(empty($comments))
				?>
			</div>

				


		</div>
		<!--footer section start in controller-->