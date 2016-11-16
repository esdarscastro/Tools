<?php
/**
 * Created by Grupo B+M
 * User: Esdras Castro
 * Date: 17/10/2016
 */

namespace Lib\Tools;


class Tool
{
    public static function isUrl($string='')
    {
        $string = filter_var($string, FILTER_SANITIZE_URL);

        if(!empty($string)){
            $preg = str_replace(array('http://','https://'), '', $string);
            if(strlen($string) != strlen($preg)) return true;
        }

        return false;
    }

    /**
     * Verifica se um número de CPF é válido
     *
     * @param string $cpf
     * @return bool
     */
    public static function validaCpf($cpf='')
    {
        $cpf = filter_var($cpf, FILTER_SANITIZE_STRING);
        $cpf = preg_replace("/[^0-9]/", "", $cpf);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
        $invalid = array(00000000000,11111111111,22222222222,44444444444,55555555555,66666666666,77777777777,88888888888,99999999999);

        if(!empty($cpf)){
            /* verifica se CPF tem 11 números */
            if(strlen($cpf) != 11) return false;
            else{
                if(in_array($cpf, $invalid)) return false;
                else{
                    /* Valida o número de CPF */
                    for ($t = 9; $t < 11; $t++) {
                        for ($d = 0, $c = 0; $c < $t; $c++) {
                            $d += $cpf{$c} * (($t + 1) - $c);
                        }
                        $d = ((10 * $d) % 11) % 10;
                        if ($cpf{$c} != $d) {
                            return false;
                        }
                    }

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Verifica se um número CPNJ é válido
     *
     * @param $cnpj
     * @return bool
     */
    public static function validaCnpj($cnpj='')
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        $cpf = str_pad($cnpj, 14, '0', STR_PAD_LEFT);
        /* Valida tamanho */
        if (strlen($cnpj) != 14)
            return false;
        /* Valida primeiro dígito verificador */
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
        {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto))
            return false;
        /* Valida segundo dígito verificador */
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
        {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        return $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);
    }

    /**
     * Retorna o IP do usuário
     *
     * @return string
     */
    public static function getClientIp() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}