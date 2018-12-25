<?php

/*
● Se comprobará que la imagen sea de un tipo permitido: png o jpg.
● Se comprobará que el tamaño de la imagen, máximo (360x480px).
● Se guardarán dos versiones de la imagen:
		360x480px - se mostrará en la página de perfil
		72x96px - se mostrará junto al nombre de usuario
Los nombres de las imágenes pueden ser: idUserBig.png y idUserSmall.png
● El directorio donde se guardarán las imágenes será: /img/usuarios
● En la tabla de usuarios de la base de datos se deberá guardar la ruta a la imagen en un campo del usuario.
*/

list($width, $height, $type, $attr) = getimagesize($_FILES['imagen']['name']);

if($width>360 || $height>480){
   print "La imagen es mayor de 360x480";
   exit();
}

/*Change the size */
function fn_resize($image_resource_id,$width,$height,$target_width, $target_height) {   
   $target_layer=imagecreatetruecolor($target_width,$target_height);
   imagecopyresampled($target_layer,$image_resource_id,0,0,0,0,$target_width,$target_height, $width,$height);
   return $target_layer;
}

if ($_FILES['imagen']['error'] != UPLOAD_ERR_OK) { // Se comprueba si hay un error al subir el archivo
   echo 'Error: ';
   switch ($_FILES['imagen']['error']) {
      case UPLOAD_ERR_INI_SIZE:
      case UPLOAD_ERR_FORM_SIZE: echo 'El fichero es demasiado grande'; break;
      case UPLOAD_ERR_PARTIAL: echo 'El fichero no se ha podido subir entero'; break;
      case UPLOAD_ERR_NO_FILE: echo 'No se ha podido subir el fichero'; break;
      default: echo 'Error indeterminado.';
   }
   exit();
}

if ($_FILES['imagen']['type'] != 'image/jpeg') { // Se comprueba que sea del tipo esperado
   echo 'Error: No se trata de un fichero .JPG.';
   exit();
}

// Si se ha podido subir el fichero se guarda
if (is_uploaded_file($_FILES['imagen']['tmp_name']) === true) {

   //extraer extension del fichero subido
   $rawName = explode(".",$_FILES['imagen']['name']);
   $name = 'idUser';
   $ext = $rawName[1];

    // Se comprueba que ese nombre de archivo no exista
   $name = './img/usuarios/'.$name;

   $file = $_FILES['imagen']['tmp_name']; 
   $source_properties = getimagesize($file);
   $image_type = $source_properties[2]; 

   //Check if file is jpg
   if( $image_type == IMAGETYPE_JPEG ) {   
      $image_resource_id = imagecreatefromjpeg($file);  
      $target_layer = fn_resize($image_resource_id,$source_properties[0],$source_properties[1],72, 96);
      imagejpeg($target_layer,$name . "Small.jpg");
   }
   //check if file is png
   elseif( $image_type == IMAGETYPE_PNG ) {
      $image_resource_id = imagecreatefrompng($file); 
      $target_layer = fn_resize($image_resource_id,$source_properties[0],$source_properties[1],72,96);
      imagepng($target_layer,$name . "Small.png");
   }

   // Se mueve el fichero a su nueva ubicación
   if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $name."Big.".$ext)) {
      echo 'Error: No se puede mover el fichero a su destino';
   }else{
      echo "subido ok";
   }
}
else
   echo 'Error: posible ataque. Nombre: '.$_FILES['imagen']['name'];

?>
