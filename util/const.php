<?php

require_once 'model/auditoria.php';
//require_once 'dao/auditoriaDAO.php';

class Constants
{
/*Flag de estado*/
  const FLAG_CORRECTO = 1;      
  const FLAG_INCORRECTO = 0; 
/*Codigos de paginas*/
  
  const COD_PAG_DOC_BOLETA = 1;
  const COD_PAG_DOC_CTS = 2;
  const COD_PAG_DOC_OTROS = 3;
  const COD_PAG_COLABORADORES = 4;
  const COD_PAG_MAN_DOC_BOLETA = 5;
  const COD_PAG_MAN_PERFIL = 6;
  const COD_PAG_MAN_AREA = 7;
  const COD_PAG_MAN_COLABORA = 8;

/*Ubicación de los banners */

  public static function getDirBanners()
  {
    $json = file_get_contents('config.json');
    $obj = json_decode($json);
    return $_SERVER['DOCUMENT_ROOT'].$obj->config->ubicacion_banners;
  }
  public static function getDirTxtCreados()
  {
    $json = file_get_contents('config.json');
    $obj = json_decode($json);
    return $_SERVER['DOCUMENT_ROOT'].$obj->config->ubicacion_txt_creados;
  }    
  public static function getLinkApp()
  {
    $json = file_get_contents('config.json');
    $obj = json_decode($json);
    return $obj->config->url_app;    
  } 
  public static function getUrlWsPdf()
  {
    $json = file_get_contents('config.json');
    $obj = json_decode($json);
    return $obj->config->ws_download_pdf;   
  } 
  public static function getUrlWsXml()
  {
    $json = file_get_contents('config.json');
    $obj = json_decode($json);
    return $obj->config->ws_download_xml;  
  }   
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

  public function psenMailer(){

    require_once 'bower_components/phpmailer/class.phpmailer.php';
    
    $json = file_get_contents('config.json');
    $obj = json_decode($json);
    //datos de conexion SMTP
    $mail= new PHPMailer(); 
    $mail->IsSMTP();
    //$mail->SMTPAuth   = true;   
    //$mail->SMTPSecure = "ssl";              
    $mail->Host       = $obj->config->mail_host;//"email-smtp.us-east-1.amazonaws.com"; //servidor  
    $mail->Port       = $obj->config->mail_port;//465; //puerto                  
    $mail->Username   = $obj->config->mail_usuario;//"AKIAJP5VFPKSHCYUO5CA"; //usuario
    $mail->Password   = $obj->config->mail_password;//"AodvOA69+/aKWhmT01Ic/YSogI5lkCZIeXkibGAkrfg3"; //contrasena      
    $mail->SetFrom($obj->config->mail_from, $obj->config->mail_from_alias); //From
    //datos de conexion SMTP FIN
    return $mail;
  }

  public function psenEncriptar($password){

    $password = hash('sha256', $password); 
    for($round = 0; $round < 65536; $round++){ 
      $password = hash('sha256', $password); 
    }
     
    return $password;    
  }

  public function getAuditoria($auditoria){

    //$auditoria = new Auditoria();
    $auditoria->setUsucrea('usu');
    $auditoria->setIpcrea(self::getClientIp());
    $auditoria->setPccrea(gethostbyaddr($_SERVER['REMOTE_ADDR']));
    $auditoria->setFechacrea(date('Y-m-d H:i:s'));
    $auditoria->setUsuedita('usu');
    $auditoria->setIpedita(self::getClientIp());
    $auditoria->setPcedita(gethostbyaddr($_SERVER['REMOTE_ADDR']));
    $auditoria->setFechaedita(date('Y-m-d H:i:s'));
       
    return $auditoria;
  }

  public function to_pg_array($set) {
    settype($set, 'array'); // can be called with a scalar or array
    $result = array();
    foreach ($set as $t) {
        if (is_array($t)) {
            $result[] = to_pg_array($t);
        } else {
            $t = str_replace('"', '\\"', $t); // escape double quote
            if (! is_numeric($t)) // quote only non-numeric values
                $t = '"' . $t . '"';
            $result[] = $t;
        }
    }
    return '{' . implode(",", $result) . '}'; // format
}

}