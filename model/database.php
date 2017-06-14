<?php
class Database
{
	public static function Conectar()
	{
		$json = file_get_contents('config.json');
		$obj = json_decode($json);

		$pdo = new PDO('pgsql:host='.$obj->config->database_host.';port='.$obj->config->database_port.';dbname='.$obj->config->database_name.';user='.$obj->config->database_user.';password='.$obj->config->database_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
        return $pdo;

	}

}
