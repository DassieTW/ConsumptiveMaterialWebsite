<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Hello world!!!</title>
    </head>
    <body>
      Hello World!!!
      <?php echo getenv("username"); ?>
      <?php echo getenv("HOMEDRIVE") . getenv("HOMEPATH"); ?>
    </body>
</html>