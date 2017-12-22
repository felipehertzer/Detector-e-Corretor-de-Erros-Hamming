<?php

namespace App\Controllers;

use Core\View;
use Core\Controller;
use Helpers\Session;


class Gerador extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->language->load('Gerador');
    }

    public function index()
    {
        $data['title'] = "Controle de Erros";
        View::renderTemplate('header', $data);
        View::render('Gerador/Inicio', $data);
        View::renderTemplate('footer', $data);
    }

    public function resultado()
    {
        $data['title'] = "Resultado Parcial";
        $array_binary = str_split(trim($_REQUEST['palavra']));
        if (!empty($_REQUEST['tipo'])) {
            foreach ($_REQUEST['tipo'] as $t) {
                $data['resultado']['tipo'][] = $t;
                switch ($t) {
                    case 1:
                        // Bit de Paridade
                        $data['resultado']['nome'][] = "Bit de Paridade";
                        $data['resultado']['retorno'][] = self::paridade($array_binary, $_REQUEST['tipo_paridade']);
                        $data['tipo_paridade'] = (!empty($_REQUEST['tipo_paridade']) ? $_REQUEST['tipo_paridade'] : 1);
                        $data['tipo_paridade_operador'] = (!empty($_REQUEST['tipo_paridade_operador']) ? $_REQUEST['tipo_paridade_operador'] : 1);
                        break;
                    case 2:
                        // CRC
                        $data['resultado']['nome'][] = "CRC";
                        $data['resultado']['retorno'][] = self::crc($array_binary, $_REQUEST['polinomio']);
                        $data['polinomio'] = $_REQUEST['polinomio'];
                        break;
                    case 3:
                        // Hamming
                        $data['resultado']['nome'][] = "Hamming";
                        $data['resultado']['retorno'][] = self::hamming($array_binary);
                        break;
                }
            }
        } else {
            $data['resultado']['nome'][] = "Hamming";
            $data['resultado']['retorno'][] = self::hamming($array_binary);
            $data['resultado']['tipo'][] = 3;
        }
        View::renderTemplate('header', $data);
        View::render('Gerador/Resultado', $data);
        View::renderTemplate('footer', $data);
    }

    public function resultado_verificacao()
    {
        $data['title'] = "Resultado da Verificação de Erros";
        if (!empty($_REQUEST['algoritmo'])) {
            $data['algoritmo'] = $_REQUEST['algoritmo'];
            switch ($_REQUEST['algoritmo']) {
                case 1:
                    $data['retorno'] = self::calcula_paridade($_REQUEST['matriz'], $_REQUEST['tipo_paridade']);
                    $data['vertical'] = $_REQUEST['vertical'];
                    $data['horizontal'] = $_REQUEST['horizontal'];
                    $data['tipo_paridade_operador'] = $_REQUEST['tipo_operador'];
                    $data['tipo_paridade'] = $_REQUEST['tipo_paridade'];
                    break;
                case 2:
                    $retorno = self::verificar_erro_crc($_REQUEST['matriz'], $_REQUEST['polinomio']);
                    $data['resultado'] = $retorno['matriz'];
                    $data['checknum'] = $retorno['checknum'];
                    $data['polinomio'] = $_REQUEST['polinomio'];
                    break;
                    break;
                case 3:
                    $retorno = self::verificar_erro_hamming($_REQUEST['matriz']);
                    $data['retorno'] = $retorno['verificado'];
                    $data['retorno_original'] = $retorno['original'];
                    break;
            }
        } else {

        }
        View::renderTemplate('header', $data);
        View::render('Gerador/ResultadoErro', $data);
        View::renderTemplate('footer', $data);
    }

    // Implementação da codificação de Hamming – valor 2.
    function hamming($array_string){
        $array_retorno= array();
        foreach($array_string as $k => $a){
            $binario = self::str2bin($a);
            $array_binario = str_split($binario);
            // Adiciona os bits de paridade com zero
            foreach(self::asciitoArrayBin($array_binario) as $key => $value) {
                $posicao = $key + 1;
                if(($posicao & ($posicao - 1)) == 0){
                    self::array_insert($array_binario, $key, 0);
                }
            };
            // Faz o encoding
            $array_retorno[$k] = self::hamming_verifica($array_binario);
        }
        return $array_retorno;
    }

    function paridade($array_string, $tipo_paridade)
    {
        $array_string = self::asciitoArrayBin($array_string);
        return self::calcula_paridade($array_string, $tipo_paridade);
    }

    // Itens 1 a 7 mais implementar modelo de detecção de erros com CRC – valor 2 pontos extra
    function crc($array_string, $polinomio){
        $polinomios = self::polinomio($polinomio);
        $array_retorno = array();
        foreach($array_string as $key => $a) {
            $binario = self::str2bin($a);
            $mensagem = ltrim($binario.str_pad("", strlen($polinomios) - 1, "0", STR_PAD_LEFT), "0");
            $array_retorno[$key] = str_split($binario.self::calcula_crc($mensagem, $polinomios));
        }
        return $array_retorno;
    }

    public function verificar_erro_crc($array_binary, $polinomio)
    {
        $polinomios = self::polinomio($polinomio);
        $array_retorno = array();
        foreach($array_binary as $k => $v){
            $binary = implode("", $v);
            $mensagem = ltrim($binary, "0");
            $array_retorno['matriz'][$k] = str_split($binary.self::calcula_crc($mensagem, $polinomios));
            $array_retorno['checknum'][$k] = array_slice($v, 8);
        }
        return $array_retorno;
    }

    public function verificar_erro_hamming($matriz)
    {
        $array_retorno= $array_retorno_original= array();
        foreach($matriz as $key => $a){
            $retorno = array_map('intval', self::hamming_verifica($a));
            $array_retorno[$key] = $retorno;
            $array_retorno_original[$key] = array_map('intval',$a);
        }
        return array("verificado" => $array_retorno, "original" => $array_retorno_original);
    }

    function hamming_verifica($array_binario){
        $array_editado = $array_binario;
        foreach($array_binario as $k => $b){
            $x = $k + 1;
            if(($x & ($x - 1)) == 0){
                $resultado = 0;
                $last = $count = 0;
                $proximo = $x - 1;
                foreach($array_binario as $t => $m){
                    $resultado = ($last != $x ? 0 : $resultado);
                    if($x == 1 && ($t+1) & 1){
                        if($t > 0){
                            $resultado = $resultado + ($m == 1 ? 1 : 0);
                        }
                    } else if($x != 1 && $t >= ($x - 1)){
                        if($count < $x && $t >= $proximo){
                            if($x != ($t + 1)){
                                $resultado = $resultado + ($m == 1 ? 1 : 0);
                            }
                            $count++;
                        } else if($count > 0){
                            $count = 0;
                            $proximo = $t + $x;
                        }
                    }
                    $last = $x;
                }
                $array_editado[$k] = $resultado & 1 ? 1 : 0;
            }
        }
        $array_editado = array_map('intval', $array_editado);
        return $array_editado;
    }
    function calcula_paridade($array_string, $tipo_paridade){
        $array_horizontal = $array_vertical = array();
        foreach ($array_string as $k => $v) {
            $bit = 0;
            foreach ($v as $chave => $valor) {
                if (($tipo_paridade == "1" || $tipo_paridade == "3") && $valor == 1) {
                    $bit++;
                }
                if ($tipo_paridade == "2" || $tipo_paridade == "3") {
                    $array_vertical[$chave] = $array_vertical[$chave] + ($valor == 1 ? 1 : 0);
                }
            }
            $array_horizontal[$k] = $bit;
        }
        return array_merge(array("original" => $array_string),($tipo_paridade == "2" ?
            array("vertical" => $array_vertical) : ($tipo_paridade == "1" ? array("horizontal" => $array_horizontal) :
                array("vertical" => $array_vertical, "horizontal" => $array_horizontal))));

    }
    function calcula_crc($mensagem, $polinomio){
        $mensagem_array = str_split($mensagem);
        $polinomio_array = str_split($polinomio);
        $count = 0;
        $resultado = "";
        foreach($mensagem_array as $m){
            if(strlen($polinomio) == $count){
                $resto = ltrim($resultado.substr($mensagem, $count), "0");
                $resultado = ltrim($resultado.substr($mensagem, $count), "0");
                if(strlen($resto) >= strlen($polinomio))
                    $resultado = self::calcula_crc($resto, $polinomio);
                break;
            } else {
                $resultado .= ($m == $polinomio_array[$count] ? "0" : "1");
                $count++;
            }
        }
        return str_pad(ltrim($resultado, "0") , strlen($polinomio) -1, "0", STR_PAD_LEFT);
    }

    // Implementação da conversão BINÁRIO - ASCII - valor 1.
    function bin2str($input)
    {
        if (!is_string($input)) return null;
        return pack('H*', base_convert($input, 2, 16));
    }

    // Implementação da conversão ASCII - BINÁRIO – valor 1.
    function str2bin($input)
    {
        if (!is_string($input)) return null;
        $value = unpack('H*', $input);
        return str_pad(base_convert($value[1], 16, 2), 8, "0", STR_PAD_LEFT);
    }

    function asciitoArrayBin($array_string){
        $array_retorno = array();
        foreach($array_string as $key => $a) {
            $binario = self::str2bin($a);
            $array_retorno[$key] = str_split($binario);
        }
        return $array_retorno;
    }

    function array_insert(&$array, $position, $insert)
    {
        if (is_int($position)) {
            array_splice($array, $position, 0, $insert);
        } else {
            $pos   = array_search($position, array_keys($array));
            $array = array_merge(
                array_slice($array, 0, $pos),
                $insert,
                array_slice($array, $pos)
            );
        }
    }

    function polinomio($p){
        $polinomios = array(
            "5" => "110101",
            "8" => "100000111",
            "12" => "1000000001011",
            "16" => "11000000000000101",
            "32" => "100000100110000010001110110110111",
        );
        return (isset($polinomios[$p]) ? $polinomios[$p] : $polinomios[8]);
    }
}
