<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Page Not Found</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" type="image/svg+xml" href="/images/Favicon_alcoil.svg">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:arial}
body{
  height:100vh;
  background:#0B3954;
  display:flex;
  align-items:center;
  justify-content:center;
  color:#fff;
}
.container{
  text-align:center;
}
.glow{
  font-size:120px;
  font-weight:900;
  letter-spacing:5px;
  color:#56b447;
  text-shadow:0 0 10px #56b447,0 0 20px #56b447,0 0 40px #56b447;
}
h2{
  margin-top:10px;
  font-size:28px;
}
p{
  margin-top:8px;
  opacity:.8;
}
button{
  margin-top:25px;
  padding:12px 30px;
  border:none;
  border-radius:30px;
  background:#56b447;
  color:#fff;
  font-size:16px;
  font-weight:600;
  cursor:pointer;
  transition:.3s;
}
button:hover{
  background:#34682b;
}
</style>
</head>

<body>
<div class="container">
  <div class="glow">404</div>
  <h2>Oups ! Page non trouvée</h2>
  <p>La page que vous recherchez n'existe pas ou a été déplacée.</p>

  <button onclick="window.location.href='/'">Retour à l'accueil</button>
</div>
</body>
</html>
