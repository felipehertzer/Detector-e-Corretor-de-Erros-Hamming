<div class="page-header">
	<h1><?=$title;?></h1>
</div>
<?php
$form = new \Helpers\Form();
echo $form->open(array("action" => "resultado", "method" => "GET"));
echo '<div class="form-group">';
echo $form->input(array("name"=>"palavra", "placeholder" => "Digite a palavra em ASCII", "class" => "form-control", "required" => true)); ?>
</div>
<div class="form-group">
	<div class="checkbox">
		<input type="checkbox" id="0" name="tipo[]" value="3">
		<label style="padding-left: 0px;" for="0">Hamming</label>
	</div>
	<div class="checkbox">
		<input type="checkbox" id="1" name="tipo[]" value="1">
		<label style="padding-left: 0px;" for="1">Bit de Paridade</label>
	</div>
	<fieldset title="Tipo">
		<legend style="margin-bottom: 0px; font-size: 15px;">Tipo</legend>
		<div class="radio">
			<label><input type="radio" id="1" name="tipo_paridade" value="1">HRC - Horizontal Redundancy Check</label>
		</div>
		<div class="radio">
			<label><input type="radio" id="2" name="tipo_paridade" value="2">VRC: Vertical Redundancy Check</label>
		</div>
		<div class="radio">
			<label><input type="radio" id="3" name="tipo_paridade" value="3">VRC & HRC</label>
		</div>
	</fieldset>
	<fieldset title="Operador">
		<legend style="margin-bottom: 0px; font-size: 15px;">Operador</legend>
		<div class="radio">
			<label><input type="radio" id="1" name="tipo_paridade_operador" value="1">Par</label>
		</div>
		<div class="radio">
			<label><input type="radio" id="2" name="tipo_paridade_operador" value="2">Impar</label>
		</div>
	</fieldset>

	<div class="checkbox">
		<input type="checkbox" id="2" name="tipo[]" value="2">
		<label style="padding-left: 0px;" for="2">CRC</label>
		<fieldset title="Tipo">
			<legend style="margin-bottom: 0px; font-size: 15px;">Tipo</legend>
			<div class="radio">
				<label><input type="radio" id="8" name="polinomio" value="8">8</label>
			</div>
			<div class="radio">
				<label><input type="radio" id="12" name="polinomio" value="12">12</label>
			</div>
			<div class="radio">
				<label><input type="radio" id="16" name="polinomio" value="16">16</label>
			</div>
			<div class="radio">
				<label><input type="radio" id="32" name="polinomio" value="32">32</label>
			</div>
		</fieldset>
	</div>
<?php
echo '</div>';
echo $form->submit(array("name" => "submit", "value" => "Verificar", "class" =>"btn btn-success"));
echo $form->close(); ?>
