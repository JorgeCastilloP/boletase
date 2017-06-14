<?php
session_start();
date_default_timezone_set("America/Lima");
require_once 'model/database.php';
require_once 'util/const.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$controller = 'login';
/*
    try{
        */
        /*

        */
        if(!isset($_REQUEST['c']))
        {
            require_once "controller/$controller.controller.php";
            $controller = ucwords($controller) . 'Controller';
            $controller = new $controller;
            $controller->Index();    
        }
        else
        {
    /*
            if (!(isset($_SESSION['usuario']) && $_SESSION['usuario'] != '')) {

            header ("Location: unk.php");

            }else{
                */
                

                
                    // Obtenemos el controlador que queremos cargar
                $controller = strtolower($_REQUEST['c']);
                $accion = isset($_REQUEST['a']) ? $_REQUEST['a'] : 'Index';
                    
                    // Instanciamos el controlador
                require_once "controller/$controller.controller.php";
                $controller = ucwords($controller) . 'Controller';
                $controller = new $controller;
                    //echo "asd";
                    // Llama la accion
                if(is_callable(array( $controller, $accion ))){
                    call_user_func( array( $controller, $accion ) );
                }else{
               
                    header('Location: ?c=Login&a=error');
                        //header('eTrust/error.php');
                }
                    //var_dump(is_callable(array( $controller, $accion )));
                    //$valor = 
                    //echo $valor;
                    /*
                }
                */            
            

        }
        /*
    }catch(Exception $e){
        die($e->getMessage());
        echo "wtf";
    }
    */


