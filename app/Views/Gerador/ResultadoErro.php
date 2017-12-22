<div class="page-header">
    <h1><?=$title;?></h1>
</div>

<?php if($data['algoritmo'] == "3"){ ?>
    <table class="table table-bordered table-hover table-condensed">
        <thead>
                <tr>
                    <?php
                    foreach($data['retorno'][key($data['retorno'])] as $chave => $value){ ?>
                        <th class="active text-center"><?php echo $chave+ 1; ?></th>
                    <?php } ?>
                    <th class="active text-center">Status</th>
                    <th class="active text-center"><b>Letra</b></th>
                </tr>
        </thead>
    <?php foreach ($data['retorno'] as $letra => $binario) { ?>
        <tr>
            <?php $count = 0;
            foreach($binario as $chave => $value){ $p = $chave + 1;
                if(($p & ($p - 1)) == 0){
                    $efeitos = "text-danger danger";
                    if($data['retorno_original'][$letra][$chave] != $value){
                        $count = $count + $p;
                    }
                } else {
                    $efeitos = "";
                } ?>
                <td class="text-center <?php echo $efeitos; ?>"><?php echo $value; ?></td>
            <?php } ?>
            <td class="<?php echo ($count == 0 ? "success" : "danger"); ?> text-center"><?php echo ($count == 0 ? "Ok" : "Erro ".$count); ?></td>
            <td class="active text-center"><b><?php
                    $letra = "";
                    foreach($binario as $key => $b){
                        $posicao = $key + 1;
                        $letra .= (($posicao & ($posicao - 1)) == 0 ? "" : $b);
                    }
                    echo utf8_encode(\Helpers\Basicas::bin2str($letra));
                    ?></b></td>
        </tr>
    <?php } ?>
    </table>
<?php } else if($data['algoritmo'] == "1"){  ?>
    <table class="table table-bordered table-hover table-condensed">
        <thead>
            <tr>
                <?php foreach ($data['retorno']['original'][0] as $chave => $value) { ?>
                    <th class="active text-center"><?php echo $chave + 1; ?></th>
                <?php }
                if($data['tipo_paridade'] == "1" || $data['tipo_paridade'] == "3"){ ?>
                    <th class="active text-center" style="width:90px;"><b>Paridade</b></th>
                <?php } ?>
                <th class="active text-center" style="width:90px;"><b>Letra</b></th>
            </tr>
        </thead>
        <tbody>
        <?php $array_horizontal = array();
        foreach ($data['retorno']['original'] as $k => $v) { ?>
            <tr>
                <?php foreach($v as $kbin => $vbin) { ?>
                    <td class="text-center"><?php echo $vbin; ?></td>
                <?php
                }
                if($data['tipo_paridade'] == "1" || $data['tipo_paridade'] == "3"){
                    $bit = (($data['tipo_paridade_operador'] == 2 && $data['retorno']['horizontal'][$k] & 1) || ($data['tipo_paridade_operador'] == 1 && $data['retorno']['horizontal'][$k] % 2 == 0) ? 0 : 1 ); ?>
                    <td class="<?php echo ($bit != $data['horizontal'][$k] ? "danger" : "success"); ?> text-center">
                        <b><?php echo $bit; ?></b>
                    </td>
                <?php } ?>
                <td class="active text-center"><b><?php echo utf8_encode(\Helpers\Basicas::bin2str(implode("",$v))); ?></b></td>
            </tr>
        <?php } ?>
        </tbody>
        <?php if($data['tipo_paridade'] == "2" || $data['tipo_paridade'] == "3"){ ?>
            <tfoot>
            <tr>
                <?php foreach($data['retorno']['vertical'] as $chave => $row){
                    $bit = (($data['tipo_paridade_operador'] == 2 && $row & 1) || ($data['tipo_paridade_operador'] == 1 && $row % 2 == 0) ? 0 : 1 ); ?>
                    <th class="<?php echo ($bit != $data['vertical'][$chave] ? "danger" : "success"); ?> text-center">
                        <?php echo $bit; ?>
                    </th>
                <?php } if($data['tipo_paridade'] == "3"){ ?>
                    <th>  </th>
                    <th>  </th>
                <?php } ?>
            </tr>
            </tfoot>
        <?php } ?>
    </table>
<?php } else if($data['algoritmo'] == "2"){ ?>
    <h3>Bits de Dados</h3>
    <table class="table table-bordered table-hover table-condensed">
        <thead>
        <tr>
            <?php
            foreach (array_slice($data['resultado'][0], 0, 8) as $chave => $value) { ?>
                <th class="active text-center"><?php echo $chave + 1; ?></th>
            <?php } ?>
            <th class="active text-center"><b>Letra</b></th>
        </tr>
        </thead>
        <?php foreach ($data['resultado'] as $letra => $binario) { //echo"<pre>"; var_dump($binario); ?>
            <tr>
                <?php foreach (array_slice($binario, 0, 8) as $chave => $value) {
                    $p = $chave + 1; ?>
                    <td class="text-center <?php echo ($chave > 7 ? "text-danger danger" : ""); ?>">
                       <?php echo $value; ?>
                    </td>
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
    <h3>Bits de Verificação - <?php echo $data['polinomio']; ?></h3>
    <?php foreach ($data['resultado'] as $letra => $binario) { //echo"<pre>"; var_dump($binario); ?>
    <table class="table table-bordered table-hover table-condensed">
        <thead>
        <tr>
            <th style="width:120px"></th>
            <?php foreach (array_slice($data['resultado'][0], $data['polinomio'] + 8) as $chave => $value) { ?>
                <th class="active text-center"><?php echo $chave + 1; ?></th>
            <?php } ?>
        </tr>
        </thead>

            <tr>
                <td class="text-right"><b>Gerado</b></td>
                <?php foreach (array_slice($binario, $data['polinomio'] + 8) as $v) { ?>
                    <td class="<?php echo ($v == 1 ? "danger" : "success"); ?> text-center">
                        <?php echo $v; ?>
                    </td>
                <?php } ?>
            </tr>
    </table>
    <?php } ?>
<?php } else {
    echo '<div class="alert alert-danger">Houve um erro. <a href="'.DIR.'">Clique aqui</a> para volta para a pagína inicial</div>';
 } ?>