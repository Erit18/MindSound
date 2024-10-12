<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documento</title>
    <style>

:root {
  --border-width: 7px;
}

* {
  margin: 0;
  padding: 0;
}

body {
  background-color: #730F16;
}
.logo {
  position: absolute;
  left: 50%;
  top: 60%;
  transform: translate(-50%, -50%);
  width: 100px;
  height: 80px;
}

.sec-loading {
  height: 100vh;
  width: 100vw;
  display: flex;
  align-items: center;
  justify-content: center;
}

.sec-loading .one {
  height: 80px;
  width: 80px;
  border: var(--border-width) solid white;
  transform: rotate(45deg);
  border-radius: 0 50% 50% 50%;
  position: relative;
  animation: move 0.5s linear infinite alternate-reverse;
}
.sec-loading .one::before {
  content: "";
  position: absolute;
  height: 55%;
  width: 55%;
  border-radius: 50%;
  border: var(--border-width) solid transparent;
  border-top-color: white;
  border-bottom-color: white;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  animation: rotate 1s linear infinite;
}

@keyframes rotate {
  to {
    transform: translate(-50%, -50%) rotate(360deg);
  }
}
@keyframes move {
  to {
    transform: translateY(15px) rotate(45deg);
  }
}


    </style>
</head>
<body>
  <img src="Project/img/logo/logo.png" class="logo" alt="Logotipo">
    <section class="sec-loading">
        <div class="one">
        </div>
      </section>

    <script>
        
// Establecer un tiempo de espera de 3 segundos para simular el tiempo de carga
setTimeout(function() {
  // Redirigir a la página de inicio
  window.location.href = "Project/Home.php";
}, 3000);

    </script>
</body>
</html>