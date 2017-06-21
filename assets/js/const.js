
var g_ubicacion_banners = "";
var g_url_aplicacion = "";
var g_max_desc_masivas = 0;

$.getJSON( "config.json", function( data ) {
g_ubicacion_banners = data.config.ubicacion_banners;
g_url_aplicacion = data.config.url_app;
g_max_desc_masivas = data.config.max_desc_masivas;
})

var FLAG_CORRECTO = 1;
var FLAG_INCORRECTO = 0;

var m_001 = "Mensaje de boletas(e)";
var m_0000 = "000";
var m_0001 = "No ingreso algún campo obligatorio, por favor revise";
var m_0002 = "Error al cargar lista desplegable";
var m_0003 = "Seleccione registro activo";
var m_0004 = "En Proceso ...";
var m_0005 = "Código de incidencia: ";
var m_0006 = "No existe coincidencias de búsqueda";
//Números
var m_0035 = "Ingresar solo números";
var m_0065 = "Formato incorrecto, debe corresponder (12,2)";
//Decimales
var m_0047 = "Debe registrar números o decimales";
//Fecha hora
var m_0007 = "La fecha no es válida";
var m_0008 = "La hora no es válida";
var m_0009 = "La fecha de fin debe ser mayor a la fecha de Inicio";
//Confirmación
var m_0010 = "No ha seleccionado el archivo a cargar";
var m_0011 = "¿Está seguro de continuar?";
//Correos
var m_0012 = "Ingrese el correo electrónico";
var m_0013 = "Formato de correo incorrecto";
var m_0014 = "El correo ingresado no se encuentra registrado";
var m_0015 = "El correo ingresado se encuentra en uso";
var m_0016 = "Envío de correo satisfactorio, revise su bandeja";
var m_0017 = "ocurrió un error en el envío del correo, contacte con el administrador";
var m_0031 = "Correo(s) enviado(s) satisfactoriamente";
var m_0032 = "Correo validado satisfactoriamente";
//Alta baja
var m_0018 = "Se canceló la acción del registro";
var m_0019 = "Registro dado de alta/baja satisfactoriamente";
var m_0020 = "Error al dar de alta/baja registro";
var m_0021 = "¿Esta seguro de dar de alta/baja este registro?";
var m_0022 = "No es posible dar de baja a un perfil del tipo administrador";
var m_0042 = "No es posible dar de baja a un usuario del tipo administrador";
var m_0023 = "Ingrese su contraseña anterior";
//Contraseña
var m_0024 = "Ingrese su contraseña";
var m_0025 = "Ingrese su nueva contraseña";
var m_0026 = "Las contraseñas no coinciden";
var m_0029 = "Error al reiniciar contraseña, contacte con el administrador";
var m_0030 = "Contraseña reiniciada satisfactoriamente";
var m_0033 = "Contraseña cambiada satisfactoriamente";
var m_0034 = "Contraseña incorrecta, no se modificó la contraseña";
var m_0040 = "Se procederá a reiniciar la contraseña..";
var m_0041 = "Reinicio de contraseña cancelada";
//Nik de usuario
var m_0043 = "El nik de usuario se encuentra en uso";
//mensaje informativos
var m_0036 = "Ingrese el asunto del mensaje";
var m_0037 = "Ingrese el cuerpo del mensaje";
//tamaño del campo
var m_0027 = "La cantidad máxima de caracteres del campo es ";
var m_0028 = "La cantidad mínima de caracteres del campo es ";
//obligatoriedad del campo
var m_0038 = "Campo requerido";
//Perfiles
var m_0039 = "Nombre de perfil en uso";
//Descarga masiva
var m_0044 = "Procediendo a descargar los comprobantes seleccionados";
var m_0045 = "Debes seleccionar almenos un comprobante a descargar";
var m_0046 = "La cantidad de archivos seleccionados supera la cantidad máxima de descargas permitidas";
//Consulta de comprobante
var m_0048 = "No existe coincidencias con la busqueda del comprobante";
var m_0049 = "Error al realizar la consulta, contacte con el administrador";
// Codigo captcha
var m_0050 = "Codigo CAPTCHA incorrecto, vuelva a intentar";
// Creacion de archivos TXT
var m_0051 = "Archivo TXT creado satisfactoriamente";
var m_0052 = "Ocurrió un error donde la creación del archivo TXT, consulte con el administrador";
var m_0053 = "El documento a crear no tiene ITEMS";
var m_0054 = "El valor ingresado no coincide con el formato RAAA-NNNNNNNN";
var m_0055 = "El valor ingresado no coincide con el formato decimal Num(12,2)";
var m_0066 = "El valor ingresado no coincide con el formato decimal Num(4,6)";
var m_0056 = "El valor ingresado no coincide con el formato RUC";
var m_0060 = "Comprobante generado/enviado satisfactoriamente";
var m_0061 = "Error durante la generacion/enviao del comprobante";
//Informativo grabar registros
var m_0057 = "Registro(s) alamacenado(s) satisfactoriamente";
var m_0058 = "Error durante el almacenamiento de información";
var m_0059 = "Registro(s) actualizado(s) satisfactoriamente";
var m_0064 = "Registro(s) eliminado(s) satisfactoriamente";
var m_0067 = "¿Esta seguro de eliminar el registro?";
var m_0068 = "Se canceló la eliminación del registro";
//Validacion de Receptores
var m_0062 = "Receptor no se encuentra registrado";
//Validacion de Receptores
var m_0063 = "Serie no se encuentra registrada";
//Privilegios
var m_0069 = "Insuficientes privilegios para acceder a este opción";
var m_0070 = "";

