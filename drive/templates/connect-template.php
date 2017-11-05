<!DOCTYPE html>
<html>
  <head>
    <title>Setup credentials</title>
  </head>
  <body>         
    <div>
      Open the following link in your browser: <br/>
      <a target=_blank href='<?=$authUrl?>'> <?=$authUrl?> </a>
    </div>
    <div>Insert the code here: 
      <form method="get">
        <input type="text" name="code">            
        <input type="submit">            
      </form>
    </div>
  </body>
</html>
