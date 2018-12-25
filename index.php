
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <title>File Upload</title>
      <style>
         form{
            margin:1em 4em;
         }
         p{
            color: black;
         }
      </style>
   </head>
   <body>
      <form action="subida.php" method="post" enctype="multipart/form-data">
         Selecciona el archivo a subir:<br><br>
         <input type="file" name="imagen" id="imagen"><br><br>
         <input type="submit" value="Enviar"><br>
      </form>


   </body>
</html>


