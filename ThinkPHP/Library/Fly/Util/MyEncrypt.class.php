<?php
class MyEncrypt
{
    private static $_instance; // 这个根据实际情况写
    private $key = "JIJX-DF144D-XLLDL-NHD3";

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self ();
        }
        return self::$_instance;
    }

    public function setKey($key = NULL)
    {
        $this->key = (!$key) ? $this->key : $key;
    }

    function encryptid($input)
    {
        return $this->encrypt($input, TRUE);
    }

    public function encrypt($input, $is_id)
    { // 数据加密
        static $_map = array();
        if ($is_id)
            $input = base_convert($input, 10, 36);
        $hashkey = md5($input . $this->key);
        if (isset ($_map [$hashkey]))
            return $_map [$hashkey];
        $size = mcrypt_get_block_size(MCRYPT_3DES, 'ecb');
        $input = $this->pkcs5_pad($input, $size);
        $key = str_pad($this->key, 24, '0');
        $td = mcrypt_module_open(MCRYPT_3DES, '', 'ecb', '');
        $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        @mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        if ($is_id) {
            $len = strlen($data);
            $tmp = '';
            for ($i = 0; $i < $len; $i++)
                $tmp = $tmp . str_pad(dechex(ord($data{$i})), 2, 0, STR_PAD_LEFT);
            $_map [$hashkey] = $tmp;
            return $tmp;
        }
        $_map [$hashkey] = $tmp;
        $data = base64_encode($data);
        return $data;
    }

    private function pkcs5_pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    public function decryptid($encrypted)
    {
        return $this->decrypt($encrypted, true);
    }

    public function decrypt($encrypted, $is_id = FALSE)
    { // 数据解密
        static $_map = array();
        if ($is_id) {
            $len = strlen($encrypted);
            $tmp = '';
            for ($i = 0; $i < $len; $i = $i + 2)
                $tmp = $tmp . chr(hexdec($encrypted{$i} . $encrypted{$i + 1}));
            $encrypted = $tmp;
        } else
            $encrypted = base64_decode($encrypted);
        $hashkey = md5($encrypted . $this->key);
        if (isset ($map [$hashkey]))
            return $_map [$hashkey];
        $key = str_pad($this->key, 24, '0');
        $td = mcrypt_module_open(MCRYPT_3DES, '', 'ecb', '');
        $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        $ks = mcrypt_enc_get_key_size($td);
        @mcrypt_generic_init($td, $key, $iv);
        $decrypted = mdecrypt_generic($td, $encrypted);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $y = $this->pkcs5_unpad($decrypted);
        if ($is_id)
            $y = base_convert($y, 36, 10);
        $_map [$hashkey] = $y;
        return $y;
    }

    private function pkcs5_unpad($text)
    {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text)) {
            return false;
        }
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
            return false;
        }
        return substr($text, 0, -1 * $pad);
    }

    public function encrypt_v1($val)
    {
        static $_map = array();
        $hashkey = md5($val);
        if (isset ($_map [$hashkey]))
            return $_map [$hashkey];
        $val += 60512868;
        $_map [$hashkey] = base_convert($val, 10, 36);
        return $_map [$hashkey];
    }

    public function decrypt_v1($val)
    {
        static $_map = array();
        $hashkey = md5($val);
        if (isset ($_map [$hashkey]))
            return $_map [$hashkey];
        $id = base_convert($val, 36, 10);
        $_map [$hashkey] = $id - 60512868;
        return $_map [$hashkey];
    }

    public function encrypt_v2($val)
    {
        static $_map = array();
        $e = "0123456789abcdefghijklmnopqrstuvwxyz";
        $e_1 = "abcdefghijklmnopqrstuvwxyz";
        $hashkey = md5($e . $e_1 . $val);
        if (isset ($_map [$hashkey]))
            return $_map [$hashkey];
        $str = $val;
        $str = strval($str);
        $len = strlen($str);
        $f_len = $len * 3;
        $ps = '';
        while (strlen($ps) <= $f_len) {
            $ps .= strtolower(md5(rand(0, 99999999)));
        }
        $ps = substr($ps, 0, $f_len);
        $en_3 = substr($ps, -1 * $len - (strlen(strval($len))) + 1);
        $en_3_int = array();
        for ($i = 0; $i < strlen($en_3); $i++) {
            $tmp_id_e = strpos($e, $str{$i}) + strpos($e, $en_3{$i});
            array_push($en_3_int, $e{$tmp_id_e % strlen($e)});
        }
        $ps_array = str_split($ps);
        for ($i = 0; $i <= count($ps_array); $i++) {
            if ($i % 2 == 1 && isset ($en_3_int [$i / 2])) {
                $ps_array [$i] = $en_3_int [$i / 2];
            }
        }
        $m_str = implode($ps_array, '');
        $pm_len = count(base_convert($len, 10, 26));
        $m_str = $m_str . substr(md5($m_str), 0, $pm_len);
        $_map [$hashkey] = $m_str;
        return $m_str;
    }

    public function decrypt_v2($val)
    {
        $e = "0123456789abcdefghijklmnopqrstuvwxyz";
        $e_1 = "abcdefghijklmnopqrstuvwxyz";
        $str = $val;
        static $_map = array();
        $hashkey = md5($e . $e_1 . $val);
        if (isset ($_map [$hashkey]))
            return $_map [$hashkey];
        $erclen = strlen($str) - floor(strlen($str) / 3) * 3;
        if ($str && substr(md5(substr($str, 0, -1 * $erclen)), 0, $erclen) == substr($str, -1 * $erclen)) {
            $t_len = floor(strlen($str) / 3);
            $en_1 = substr($str, -1 * $t_len - ceil($t_len / strlen($e_1)), -1 * ceil($t_len / strlen($e_1)));
            $s_str_array = array();
            for ($i = 0; $i <= $t_len * 2; $i++) {
                if ($i % 2 == 1 && $i / 2 < $t_len) {
                    $id_s = $str{$i};
                    $id_i = $e{((strpos($e, $id_s) + strlen($e)) - strpos($e, $en_1{$i / 2})) % strlen($e)};
                    array_push($s_str_array, $id_i);
                }
            }
            $s_str = implode($s_str_array, '');
        } else
            $s_str = null;
        $_map [$hashkey] = $s_str;
        return $s_str;
    }

    public function encrypt_v3($val)
    {
        static $_map = array();
        $hashkey = md5($val);
        if (isset ($_map [$hashkey]))
            return $_map [$hashkey];
        $str = $val;
        $str = base64_encode(gzcompress($str));
        $fix = substr($str, -4);
        $str = substr($str, 0, -4);
        $a = substr_count($fix, "=");
        if ($a > 0)
            $str = substr(substr($str, -1 * $a) . $str, 0, -1 * $a);
        $strarr = str_split($str, strlen($str) / 4);
        for ($i = 0; $i < strlen($strarr [0]); $i++) {
            $tmpstr .= $strarr [0]{$i} . $strarr [1]{$i} . $strarr [2]{$i} . $strarr [3]{$i};
        }
        $tmpstr .= $fix;
        $_map [$hashkey] = $tmpstr;
        return $tmpstr;
    }

    public function decrypt_v3($val)
    {
        static $_map = array();
        $hashkey = md5($val);
        if (isset ($_map [$hashkey]))
            return $_map [$hashkey];
        $str = $val;
        $fix = substr($str, -4);
        $str = substr($str, 0, -4);
        $a = substr_count($fix, "=");
        for ($i = 0; $i <= strlen($str); $i++) {
            $tmp [$i % 4] .= $str{$i};
        }
        $str = implode("", $tmp);
        if ($a > 0)
            $str = substr($str . substr($str, 0, $a), $a);
        $str .= $fix;
        $_map [$hashkey] = gzuncompress(base64_decode($str));
        return $_map [$hashkey];
    }

    function encrypt_v4($id)
    {
        static $_map = array();
        $hashkey = md5($id);
        if (isset ($_map [$hashkey]))
            return $_map [$hashkey];
        $s1 = ($id + 201341) * 7;
        $l = strlen($s1);
        $lb = intval($l / 2);
        $lc = $l - $lb;
        $a = substr($s1, 0, $lb);
        // echo $a."\n";
        $b = substr($s1, -1 * ($lc));
        // echo $b."\n";
        $tmpstr = '';
        for ($i = 0; $i < $lb; $i++) {
            if ($i % 2 == 0) {
                $tmpstr .= $a{intval($i / 2)} . $b{($lc - 1 - intval($i / 2))};
            } else {
                $tmpstr .= $b{intval($i / 2)} . $a{($lb - intval($i / 2) - 1)};
            }
        }
        if ($l % 2 == 1)
            $tmpstr .= $b{intval(($lc - 1) / 2)};
        $_map [$hashkey] = $tmpstr;
        return $tmpstr;
    }

    function decrypt_v4($str)
    {
        static $_map = array();
        $hashkey = md5($str);
        if (isset($_map[$hashkey]))
            return $_map[$hashkey];
        $l = strlen($str);
        $tmpstr = array();
        $flag = 1;
        $c = 0;
        for ($i = 0; $i < $l; $i++) {
            if ($i !== 0 && $i % 2 == 0) {
                $flag = -$flag;
                if ($flag == 1)
                    $c++;
            }
            if ($i == $l - 1) {
                for ($j = 0; $j <= $l; $j++)
                    if (!isset($tmpstr[$j])) {
                        $tmpstr[$j] = $str[$i];
                        break;
                    }
            } else {
                if ($i % 2 == 0) {
                    if ($flag == 1) {
                        $tmpstr [intval($i / 2) - $c] = $str [$i];
                        //echo "1:$flag:$c:".(intval($i/2)-$c)."\n";
                    } else {
                        $tmpstr [intval($l / 2) + $c] = $str [$i];
                        //echo "2:$flag:$c:".(intval ( $l / 2 ) +$c)."\n";
                    }
                } else {
                    if ($flag == 1) {
                        $tmpstr [$l - intval(($i - $c * 2) / 2) - 1] = $str [$i];
                        //echo "3:$flag:$c:".($l - intval($i/ 2 ) -$c-1)."\n";
                    } else {
                        $tmpstr [intval($l / 2) - 1 - $c] = $str [$i];
                        //echo "4:$flag:$c:".(intval ( $l / 2 ) - 1 - $c)."\n";
                    }
                }
            }

        }
        ksort($tmpstr);
        $str = implode("", $tmpstr);
        $str = $str / 7 - 201341;
        if (is_float($str) || $str < 0)
            $str = null;
        $_map[$hashkey] = $str;
        return $str;
        //print_r($tmpstr);
    }

    function encrypt_v5($a){
        static $_map = array();
        if(!isset($_map[$a])){
            $_map[$a] = $this->base_10262($a);
        }
        return $_map[$a];
    }

    function decrypt_v5($a){
        static $_map = array();
        if(!isset($_map[$a])){
            $_map[$a] = $this->base_62210($a);
        }
        return $_map[$a];
    }
    private function PaddingPKCS7($data)
    {
        $block_size = mcrypt_get_block_size(MCRYPT_3DES, MCRYPT_MODE_CBC);
        $padding_char = $block_size - (strlen($data) % $block_size);
        $data .= str_repeat(chr($padding_char), $padding_char);
        return $data;
    }

    private function base_10262($a){
        $_t = '';
        while($a!=0){
            $c = bcmod($a,62);
            if($c>=10&&$c<36){
                $_t = chr($c+55).$_t;
            }elseif($c>=36&&$c<62){
                $_t = chr($c+61).$_t;
            }else{
                $_t= $c.$_t;
            }
            $a=intval(bcdiv($a,62));
        }
        return $_t;
    }
    private function base_62210($a){
        $_t=0;
        $len = strlen($a);
        for($i=0;$i<$len;$i++){
            $_l = ord($a{$i});
            if($_l<=57){
                $_t = bcadd($_t,bcmul($a{$i},bcpow(62,$len-$i-1)));
            }elseif($_l>=65&&$_l<=90){
                $_t = bcadd($_t,bcmul($_l-55,bcpow(62,$len-$i-1)));
            }else{
                $_t = bcadd($_t,bcmul($_l-61,bcpow(62,$len-$i-1)));
            }
        }
        return $_t;
    }


    private function __clone()
    {
        return FALSE;
    }
}
