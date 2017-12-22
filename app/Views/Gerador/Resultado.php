<div class="page-header">
	<h1><?=$title;?></h1>
</div>
<?php
foreach($data['resultado']['nome'] as $key => $nome) {
	if ($data['resultado']['tipo'][$key] == 3) {
		echo "<h2>" . $nome . "</h2>"; ?>
		<form method="post" action="<?php echo DIR; ?>ResultadoErro">
			<input type="hidden" name="algoritmo"  value="3">
			<table class="table table-bordered table-hover table-condensed">
				<thead>
				<tr>
					<?php
					foreach ($data['resultado']['retorno'][$key][key($data['resultado']['retorno'][$key])] as $chave => $value) { ?>
						<th class="active text-center"><?php echo $chave + 1; ?></th>
					<?php } ?>
					<th class="active text-center"><b>Letra</b></th>
				</tr>
				</thead>
				<?php foreach ($data['resultado']['retorno'][$key] as $letra => $binario) { ?>
					<tr>
						<?php foreach ($binario as $chave => $value) {
							$p = $chave + 1; ?>
							<td class="text-center <?php echo (($p & ($p - 1)) == 0) ? "text-danger danger" : ""; ?>">
								<input type="text" class="form-control"
									   name="matriz<?php echo "[" . $letra . "][" . $chave . "]"; ?>"
									   value="<?php echo $value; ?>"></td>
						<?php } ?>
						<td class="active text-center"><b><?php
								$letra = "";
								foreach ($binario as $key => $b) {
									$posicao = $key + 1;
									$letra .= (($posicao & ($posicao - 1)) == 0 ? "" : $b);
								}
								echo utf8_encode(\Helpers\Basicas::bin2str($letra));
								?></b></td>
					</tr>
				<?php } ?>
			</table>
			<button name="submit" type="submit" class="btn btn-success">Verificar erros</button>
		</form>
<?php
	} else if ($data['resultado']['tipo'][$key] == 1) {
		echo "<h2>" . $nome .($data['tipo_paridade_operador'] == 1 ? " - Par" : " - Ímpar");
		if($data['tipo_paridade'] == "2") echo " - VRC"; else if ($data['tipo_paridade'] == "1") echo " - HRC"; else echo  " - VRC + HRC"; echo "</h2>";
		?>
		<form method="post" action="<?php echo DIR; ?>ResultadoErro">
			<input type="hidden" name="algoritmo" value="1">
			<input type="hidden" name="tipo_paridade" value="<?php echo $data['tipo_paridade']; ?>">
			<input type="hidden" name="tipo_operador" value="<?php echo $data['tipo_paridade_operador']; ?>">
			<table class="table table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<?php foreach ($data['resultado']['retorno'][$key]['original'][0] as $chave => $value) { ?>
							<th class="active text-center"><?php echo $chave + 1; ?></th>
						<?php }
						if($data['tipo_paridade'] == "1" || $data['tipo_paridade'] == "3"){ ?>
							<th class="active text-center"><b>Paridade</b></th>
						<?php } ?>
						<th class="active text-center"><b>Letra</b></th>
					</tr>
				</thead>
				<tbody>
				<?php $array_horizontal = array();
				foreach ($data['resultado']['retorno'][$key]['original'] as $k => $v) { ?>
					<tr>
						<?php foreach($v as $kbin => $vbin) { ?>
							<td class="text-center">
								<input type="text" class="form-control" name="matriz<?php echo "[" . $k . "][]"; ?>"
									   value="<?php echo $vbin; ?>">
							</td>
							<?php
						}
						if($data['tipo_paridade'] == "1" || $data['tipo_paridade'] == "3"){ ?>
							<td class="active text-center">
								<b>
									<input type="text" class="form-control" name="horizontal<?php echo "[".$k."]"; ?>"
										   value="<?php echo (($data['tipo_paridade_operador'] == 2 && $data['resultado']['retorno'][$key]['horizontal'][$k] & 1) || ($data['tipo_paridade_operador'] == 1 && $data['resultado']['retorno'][$key]['horizontal'][$k] % 2 == 0) ? 0 : 1 ); ?>">
								</b>
							</td>
						<?php } ?>
						<td class="active text-center"><b><?php echo utf8_encode(\Helpers\Basicas::bin2str(implode("",$v))); ?></b></td>
					</tr>
				<?php } ?>
				</tbody>
				<?php if($data['tipo_paridade'] == "2" || $data['tipo_paridade'] == "3"){ ?>
				<tfoot>
					<tr>
						<?php foreach($data['resultado']['retorno'][$key]['vertical'] as $chave => $row){ ?>
							<th class="active text-center"><input type="text" class="form-control" name="vertical<?php echo "[".$chave."]"; ?>" value="<?php echo (($data['tipo_paridade_operador'] == 2 && $row & 1) || ($data['tipo_paridade_operador'] == 1 && $row % 2 == 0) ? 0 : 1 ); ?>"></th>
						<?php } if($data['tipo_paridade'] == "3"){ ?>
							<th>  </th>
							<th>  </th>
						<?php } ?>
					</tr>
				</tfoot>
				<?php } ?>
			</table>
			<button name="submit" type="submit" class="btn btn-success">Verificar erros</button>
		</form>
	<?php } else if ($data['resultado']['tipo'][$key] == 2) {
		echo "<h2>" . $nome ." - ".$data['polinomio']." Bits</h2>"; ?>
		<form method="post" action="<?php echo DIR; ?>ResultadoErro">
			<input type="hidden" name="algoritmo"  value="2">
			<input type="hidden" name="polinomio" value="<?php echo $data['polinomio']; ?>">
			<table class="table table-bordered table-hover table-condensed">
				<thead>
				<tr>
					<?php
					foreach ($data['resultado']['retorno'][$key][key($data['resultado']['retorno'][$key])] as $chave => $value) { ?>
						<th class="active text-center"><?php echo $chave + 1; ?></th>
					<?php } ?>
					<th class="active text-center"><b>Letra</b></th>
				</tr>
				</thead>
				<?php foreach ($data['resultado']['retorno'][$key] as $letra => $binario) { ?>
					<tr>
						<?php foreach ($binario as $chave => $value) { ?>
							<td class="text-center <?php echo ($chave > 7 ? "text-danger danger" : ""); ?>">
								<input type="text" class="form-control" <?php echo ($data['polinomio'] == "32" ? "style='padding:0px;text-align:center;'" : ""); ?>
									   name="matriz<?php echo "[" . $letra . "][" . $chave . "]"; ?>"
									   value="<?php echo $value; ?>"></td>
						<?php } ?>
						<td class="active text-center"><b><?php
							$letra = "";
							for($b=0;$b<8;$b++) {
								$letra .= $binario[$b];
							}
							echo utf8_encode(\Helpers\Basicas::bin2str($letra));
							?></b></td>
					</tr>
				<?php } ?>
			</table>
			<button name="submit" type="submit" class="btn btn-success">Verificar erros</button>
		</form>
<?php
	}
}
?>