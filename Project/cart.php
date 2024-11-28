<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Dancing+Script:wght@700&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;family=Dancing+Script:wght@700&amp;display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Lato&amp;display=swap" rel="stylesheet">
  <link rel="icon" type="image/x-icon" href="img/logo/logo.ico">
    <link rel="stylesheet" href="style/styleForcart.css">
    <link rel="stylesheet" href="style/styleCart.css">
    <script src="https://kit.fontawesome.com/9a05771681.js" crossorigin="anonymous"></script>
    <title>Carrito</title>
</head>

<body>
  <div id="nav">
    <div class="topnav">
      <a href="Home.php">Inicio</a>
      <a href="BooksPage.php">Libros</a>
      <a href="likes.php">Me gusta</a>
      <a href="aboutus.php">Sobre nosotros</a>
      <a href="contact.php">Contacto</a>
      <a href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
    </div>
  </div>

  <div class="main-content">
    <div class="cart-container">
      <div class="cart-section">
        <h2>Carrito de Compras</h2>
        <div class="cart-table">
          <div class="table-header">
            <div class="header-row">
              <span>Imagen</span>
              <span>Nombre</span>
              <span>Precio</span>
              <span>Eliminar</span>
            </div>
          </div>
          <div id="cart-items"></div>
        </div>
      </div>

      <div class="order-summary">
        <h2>Resumen del Pedido</h2>
        <div class="summary-box">
          <div class="summary-total">
            <span id="itemB">0 Libros</span>
            <span id="total-price">0 $</span>
          </div>
        </div>
        <div class="shipping-section">
          <h3>Envío</h3>
          <input type="text" placeholder="Entrega estándar" class="shipping-input">
        </div>
        <div class="paypal-button-container"></div>
      </div>
    </div>
  </div>
</body>
</html>