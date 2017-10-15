			<div id="page-wrapper">
				<div class="graphs">
					<h3 class="blank1">Descargar comentarios</h3>
					<form class="form-horizontal" action="<?=base_url()?>index.php/usercustomer_controller/comments_download" method="POST">
							<div class="form-group">
								<div class="col-sm-8">
									<div class="radio block">
										<input type="radio" name="comments_download" value="not_valued" checked> Comentarios no valorados
									</div>
									<div class="radio block">
										<input type="radio" name="comments_download" value="valued"> Comentarios valorados
									</div>
									<div class="form-group has-warning">
								        <label class="control-label" for="inputWarning1">Palabras prohibidas (palabras que serán ignoradas)</label>
								        <input type="text" class="form-control1" id="inputWarning1" name="words" value="Seperar por comas (los espacios serán tenidos en cuenta)"
								        onfocus="if (this.value == 'Seperar por comas (los espacios serán tenidos en cuenta)') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Seperar por comas (los espacios serán tenidos en cuenta)';}">
								    </div>

								    <div class="form-group has-warning">
								        <label for="selector1">
								        	<input type="checkbox" name="products_active"></input>
								        	Cuya/o
								        	<select name="products_field">
								        	<?php
												foreach ($structure['products'] as $key => $value) { 
													?>
													<option><?=$value?></option>
												<?php } ?>
											</select> de los productos <select name="products_symbol">
												<option value="=">sea igual</option>
												<option value="!=">sea distinto</option>
												<option value="like">contenga</option>
												<option value="not like">no contenga</option>
											</select>  <input type="text" name="products_text"></input>
								        </label>
								    </div>

								    <div class="form-group has-warning">
								        <label for="selector1">
								        	<input type="checkbox" name="comments_active"></input>
								        	Cuya/o <select name="comments_field">
								        	<?php
												foreach ($structure['comments'] as $key => $value) { 
													?>
													<option><?=$value?></option>
												<?php } ?>
											</select> de los comentarios <select name="comments_symbol">
												<option value="=">sea igual</option>
												<option value="!=">sea distinto</option>
												<option value="like">contenga</option>
												<option value="not like">no contenga</option>
											</select>  <input type="text" name="comments_text"></input>
								        </label>
								    </div>
								    

									<div class="col-sm-2 jlkdfj1">
										<p class="help-block"><input type="submit" class="btn-success btn" value="Descargar"></p>
									</div>


								</div>

							</div>
						</form>


				</div>
			</div>
