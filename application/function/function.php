<?php
/**
 * Created by PhpStorm.
 * User: peak
 * Date: 2016/7/8
 * Time: 10:42
 */
function encrypt($input, $key) {
    $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
    $input = pkcs5_pad($input, $size);
    $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
    $iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    mcrypt_generic_init($td, $key, $iv);
    $data = mcrypt_generic($td, $input);
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);
    $data = base64_encode($data);
    return $data;
}

function pkcs5_pad ($text, $blocksize) {
    $pad = $blocksize - (strlen($text) % $blocksize);
    return $text . str_repeat(chr($pad), $pad);
}

function decrypt($sStr, $sKey) {
    $decrypted= mcrypt_decrypt(
        MCRYPT_RIJNDAEL_128,
        $sKey,
        base64_decode($sStr),
        MCRYPT_MODE_ECB
    );
    $dec_s = strlen($decrypted);
    $padding = ord($decrypted[$dec_s-1]);
    $decrypted = substr($decrypted, 0, -$padding);
    return $decrypted;
}

function ajax_massage($date){
    if(is_array($date)){
        $massage = json_encode($date);
    }else{
        $massage = $date;
    }
    exit($massage);
}