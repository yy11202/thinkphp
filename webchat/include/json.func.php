<?php
//将内容进行UNICODE编码    
function unicode_encode($name)    
{    
    $name = iconv('UTF-8', 'UCS-2', $name);    
    $len = strlen($name);    
    $str = '';    
    for ($i = 0; $i < $len - 1; $i = $i + 2)    
    {    
        $c = $name[$i];    
        $c2 = $name[$i + 1];    
        if (ord($c) > 0)    
        {   //两个字节的文字    
            $str .= '\u'.base_convert(ord($c), 10, 16).base_convert(ord($c2), 10, 16);    
        }    
        else   
        {    
            $str .= $c2;    
        }    
    }    
    return $str;    
}    
   
//将UNICODE编码后的内容进行解码    
function unicode_decode($name)    
{    
    //转换编码，将Unicode编码转换成可以浏览的utf-8编码    
    $pattern = '/(\\\u([\w]{4}))/i';    
    $name = preg_replace_callback( $pattern , '_unicode_decode' , $name );    
    return $name;    
}    
   
function _unicode_decode( $str ) {    
    $str = $str[0];    
    $name = '';    
    $code = base_convert(substr($str, 2, 2), 16, 10);    
    $code2 = base_convert(substr($str, 4), 16, 10);    
    $c = chr($code).chr($code2);    
    $c = iconv('UCS-2', 'UTF-8', $c);    
    $name .= $c;    
   
    return $name;    
}    
function json_encode($s){
  return onez_json($s);
}
   
function json_decode( $json , $to_encode = 'utf-8' , & $i = 0 ) {    
    if ( null === $i ) {    
        $i = 0;    
    }    
   
    for( ; $i < strlen( $json ) ; $i ++ ) {         
        $chr = $json[$i];    
   
        switch( $chr ) {    
            case '"' :    
            case "'" :   
                //字符串          
                $i ++;   
                $val = '';   
                while( $json[$i] != $chr || $lastChr == '\\' ) {   
                    $lastChr = $json[$i];   
                    $val .= $lastChr;   
                    $i ++;   
                       
                }   
                ++ $i;   
                   
                //字符串处理   
                   
                //unicode 转汉字   
                $val = unicode_decode( $val );   
                if ( strtolower( str_ireplace( '-' , '' , $to_encode ) ) !== 'utf8' ) {   
                    $val = mb_convert_encoding( $val , $to_encode , 'utf-8' );   
                }   
                $val = stripslashes( $val );                   
                return $val;   
                break;   
            case 'a' :   
            case 'b' :   
            case 'c' :   
            case 'd':   
            case 'e':   
            case 'f':   
            case 'g':   
            case 'h':   
            case 'i':   
            case 'j':   
            case 'k':   
            case 'l':   
            case 'm':   
            case 'n':   
            case 'o':   
            case 'p':   
            case 'q':   
            case 'r':   
            case 's':   
            case 't':   
            case 'u':   
            case 'v':   
            case 'w':   
            case 'x':   
            case 'y':   
            case 'z':   
            case 'A' :   
            case 'B' :   
            case 'C' :   
            case 'D':   
            case 'E':   
            case 'F':   
            case 'G':   
            case 'H':   
            case 'I':   
            case 'J':   
            case 'K':   
            case 'L':   
            case 'M':   
            case 'N':   
            case 'O':   
            case 'P':   
            case 'Q':   
            case 'R':   
            case 'S':   
            case 'T':   
            case 'U':   
            case 'V':   
            case 'W':   
            case 'X':   
            case 'Y':   
            case 'Z':      
            case '0':   
            case '1':   
            case '2':   
            case '3':   
            case '4':   
            case '5':   
            case '6':   
            case '7':   
            case '8':   
            case '9':   
               
                //字符串   
                if ( trim( $chr ) === '' ) {   
                    continue;   
                }   
                $val = $chr;   
                while( preg_match( '#^[a-zA-Z0-9\.]$#' , $json[++$i] ) ) {   
                    $val .= $json[$i];                                     
                }   
                $lVal = strtolower( $val );   
                if ( $lVal == 'true' ) {   
                    return true;   
                }   
                if ( $lVal == 'false' ) {   
                    return false;   
                }   
                if ( preg_match( '#^[0-9\.]+$#' , $lVal ) ) {   
                    return $val + 0;   
                }   
                return $val;   
            case '{' :   
                $val = array();   
                $i ++;   
                $key = '';   
                while( $json[$i] != '}' ) {   
                    $key .= $json[$i];   
                    $i ++;   
                    if ( $json[$i] == ':' ) {   
                        $key = ltrim( $key );   
                        $key = ltrim( $key , ',' ); //去除,   
                        $key = trim( $key );//去两边的空白   
                        if ( preg_match( '#^"(.+?)"$#' , $key , $m ) ) {   
                            $key = $m[1];   
                        }   
                        if ( preg_match( '#^\'(.+?)\'$#' , $key , $m ) ) {    
                            $key = $m[1];    
                        }    
                        ++ $i;    
                        $val[$key] = json_decode( $json , $to_encode , $i );    
                        $key = '';                          
                    }                       
                }    
                ++ $i;    
                return $val;    
                break;    
            case '[' :    
                $val = array();    
                $i ++;    
                $t = 0;    
                while( $json[$i] != ']' ) {    
                    if ( $json[$i] == ',' ) {    
                        $i ++;    
                        continue;    
                    }                       
                    $val[] = json_decode( $json , $to_encode  , $i );                        
                    
                }    
                    
                $i ++;    
                return $val;    
                break;    
        }    
    }    
    return NULL ;    
}    

?>