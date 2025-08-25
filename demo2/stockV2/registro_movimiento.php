<?php
include 'db_connection.php';
try {
    $stmt = $conn->prepare("
    SELECT ms.*, p.nombreProducto 
    FROM movimientos_stock ms 
    JOIN productos p ON ms.idProducto = p.idProducto 
    ORDER BY ms.fechaMovimiento DESC
    ");
    $stmt->execute();
    $movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener los movimientos: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>Generadores Electricos a Gas | GENERAC</title>
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="CONCESIONARIO OFICIAL GENERAC Y SERVICE OFICIAL GENERAC MAT. TECH. ID. 924906" name=description>
  <meta
    content="grupos electrogenos a gas, grupos electrogenos, generadores electricos a gas, generadores electricos, cortes de luz, generac, equipos generac, generac argentina, generadores industriales, grupos electrogenos industriales, generadores electricos a gas en argentina, generadores a gas en argentina, generadores en argentina, generador electrico argentina, venta de generador electrico a gas en argentina, venta de generadores a gas argentina, gruposelectrogenos a gas en argentina, gruposelectrogenos en argentina, gruposelectrojenos en argentina, gruposelectrojenos a gas en argentina, problemas de luz, problemas de energia en argentina, fallas en el suministro electrico, sin luz, sin energia, no tengo luz, cortes de luz programados, cortes programados de energia en argentina, argentina, Que generador electrico comprar, Que generador electrico comprar en argentina, Que grupo electrico comprar, Que grupo electrogeno comprar, donde comprar un generador electrico, donde comprar un grupo electrogeno, donde comprar un grupo de energia, que generador electrico debo comprar."
    name=keywords>
  <meta
    content="grupos electrogenos a gas, grupos electrogenos, generadores electricos a gas, generadores electricos, cortes de luz, generac, equipos generac, generac argentina, generadores industriales, grupos electrogenos industriales, generadores electricos a gas en argentina, generadores a gas en argentina, generadores en argentina, generador electrico argentina, venta de generador electrico a gas en argentina, venta de generadores a gas argentina, gruposelectrogenos a gas en argentina, gruposelectrogenos en argentina, gruposelectrojenos en argentina, gruposelectrojenos a gas en argentina, problemas de luz, problemas de energia en argentina, fallas en el suministro electrico, sin luz, sin energia, no tengo luz, cortes de luz programados, cortes programados de energia en argentina, argentina, Que generador electrico comprar, Que generador electrico comprar en argentina, Que grupo electrico comprar, Que grupo electrogeno comprar, donde comprar un generador electrico, donde comprar un grupo electrogeno, donde comprar un grupo de energia, que generador electrico debo comprar."
    name=keywords>
  <meta content="index, follow" name=robots>
  <meta content="diseño web TuPropiaMarca.com" name=copyright>
  <meta content=ES name=language>
  <meta content="diseño web TuPropiaMarca.com" name=author>
  <meta content=global name=distribution>
  <meta content=general name=rating>
  <meta name="google-site-verification" content="qP_IkzECrMTgd5ETCjC7eoR1qoZswJDeKOAmRyHqEi8" />
  <meta content="pfnynul6g5khvesbro6irsjohahd1z" name="facebook-domain-verification" />
  <script src="js/core.min.js"></script>
  <link href="images/favicon.ico" rel="icon" type="image/x-icon" />
  <link href="css/bootstrap.css" rel="stylesheet" />
  <link href="css/fonts.css" rel="stylesheet" />
  <link href="css/style.css" rel="stylesheet" />
  <script>
    (function (i, s, o, g, r, a, m) { i['GoogleAnalyticsObject'] = r; i[r] = i[r] || function () { (i[r].q = i[r].q || []).push(arguments) }, i[r].l = 1 * new Date(); a = s.createElement(o), m = s.getElementsByTagName(o)[0]; a.async = 1; a.src = g; m.parentNode.insertBefore(a, m) })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga'); ga('create', 'UA-49062637-1', '//www.mundogenerador.com'); ga('send', 'pageview');
  </script><!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-151440158-1"></script>
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-49062637-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag() { dataLayer.push(arguments); }
    gtag('js', new Date());

    gtag('config', 'UA-151440158-1');
    gtag('config', 'UA-49062637-1');
  </script><!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-LZ29XRDW6P"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag() { dataLayer.push(arguments); }
    gtag('js', new Date());

    gtag('config', 'G-LZ29XRDW6P');
  </script>

  <style>
      #Apu {
        width: 100%;
        border: 1px solid #8A8A8A;
        border-collapse: collapse;
        padding: 5px;
      }
      #tabla-stock {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
      }
      #Apu caption {
        caption-side: top;
        text-align: center;
      }
      #Apu th {
        border: 1px solid #8A8A8A;
        padding: 5px;
        text-align: center;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        color: #000;
        background: #E6E6E6;
      }
      #Apu td {
        border: 1px solid #8A8A8A;
        text-align: center;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        padding: 5px;
        background: #FFFFFF;
        color: #000000;
      }
      table {
        width: 100%;
        max-width: 100%;
        border-collapse: collapse;
        margin: 0 auto;
      }
      th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
        background-color: #FFFFFF;
      }
      th {
        background-color: #f2f2f2;
      }
      nav {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;
      }
      td img {
        display: block;
        margin: 0 auto;
        max-width: 300px;
        height: auto;
      }
      nav button,
      nav .btn-logout {
        margin: 0;
      }
      .buscador-producto input {
        padding: 10px;
        font-size: 18px;
        width: 250px;
      }
      .buscador-producto button {
        padding: 10px 20px;
        font-size: 18px;
        cursor: pointer;
      }
      .tabla-stock th, .tabla-stock td {
        padding: 12px;
        text-align: left;
        font-size: 1.1rem;
      }
      .tabla-stock img {
        max-width: 100%;
        height: auto;
      }
      .stock-controls span {
        font-size: 18px;
        font-weight: bold;
        min-width: 30px;
        text-align: center;
        display: inline-block;
      }
      .tituloSistema{
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;
      }
      .btn-logout {
        background-color: #FFCC00;
        font-size: 13px;
        color: #000;
        padding: 3px 6px;
        text-decoration: none;
        font-weight: bold;
        border-radius: 5px;
        transition: background-color 0.3s ease;
        margin-left: 40px;
      }
      .btn-logout:hover {
        background-color: #f0b400;
      }
      .buscador-producto {
        display: flex;
        gap: 10px;
        margin: 20px 0;
        justify-content: center;
        flex-wrap: wrap;
      }
      .tabla-responsive {
        overflow-x: auto;
        width: 100%;
      }
      .tabla-stock {
        width: 100%;
        border-collapse: collapse;
      }
      .stock-controls {
        display: flex;
        gap: 10px;
        align-items: center;
        justify-content: center;
      }
      .stock-controls button {
        font-size: 1.3rem;
        padding: 6px 12px;
      }
      .tabla-historial {
          font-size: 16px;
          width: 60%;
          border-collapse: collapse;
      }
      @media (max-width: 768px) {
        .btn-logout {
          width: auto; 
          font-size: 14px; 
          padding: 6px; 
        }
      }
      @media (max-width: 600px) {
        .tabla-stock th, .tabla-stock td {
          font-size: 1rem;
        }
        .stock-controls {
          flex-direction: column;
        }
        .stock-controls button {
          width: 100%;
        }
      }
      @media (min-width: 992px) {
        .main-section {
          padding: 160px 0 25px 0;
        }
      }

    </style>
  </head>

  <body>
    <div class="preloader">
      <div class="preloader-body">
        <div class="cssload-container">
          <div class="cssload-speeding-wheel"></div>
        </div>
        <p>Cargando...</p>
      </div>
    </div>

    <div class="page">
      <header class="section page-header">
        <div class="rd-navbar-wrap">
          <nav class="rd-navbar rd-navbar-classic" data-layout="rd-navbar-fixed" data-lg-device-layout="rd-navbar-static" data-lg-layout="rd-navbar-static" data-lg-stick-up="true" data-lg-stick-up-offset="46px" data-md-device-layout="rd-navbar-fixed" data-md-layout="rd-navbar-fixed" data-sm-layout="rd-navbar-fixed" data-xl-device-layout="rd-navbar-static" data-xl-layout="rd-navbar-static" data-xl-stick-up="true" data-xl-stick-up-offset="46px" data-xxl-stick-up="true" data-xxl-stick-up-offset="46px">
            <div class="rd-navbar-collapse-toggle rd-navbar-fixed-element-1" data-rd-navbar-toggle=".rd-navbar-collapse">
            </div>
            <div class="rd-navbar-aside-outer rd-navbar-collapse bg-gray-dark">
              <div class="rd-navbar-aside">
                <ul class="list-inline navbar-contact-list">
                  <li>
                    <div class="unit unit-spacing-xs align-items-center">
                      <div class="unit-left"><span class="icon text-middle fa-phone"> </span></div>
                      <div class="unit-body">WhatsApp <a href="tel:1162080698" onClick="gtag('event', 'Clic llamada', { event_category: 'Clic llamada', event_action: 'Clic'});">(011) 15-6208-0698</a></div>
                    </div>
                  </li>
                  <li>
                    <div class="unit unit-spacing-xs align-items-center">
                      <div class="unit-left"><span class="icon text-middle fa-phone"></span></div>
                      <div class="unit-body"><a href="tel:1164189217" onClick="gtag('event', 'Clic llamada', { event_category: 'Clic llamada', event_action: 'Clic'});">(011) 15-6418-9217</a></div>
                    </div>
                  </li>
                  <li>
                    <div class="unit unit-spacing-xs align-items-center">
                      <div class="unit-left"><span class="icon text-middle fa-phone"></span></div>
                      <div class="unit-body"><a href="tel:1162080684" onClick="gtag('event', 'Clic llamada', { event_category: 'Clic llamada', event_action: 'Clic'});">(011) 15-6208-0684</a></div>
                    </div>
                  </li>
                </ul>
                <ul class="social-links">
                  <li><a href="https://mundogenerador.com/servicios-y-repuestos.html" style="font-size: 14px; color: #FFCC00;">SOPORTE T&Eacute;CNICO</a></li>
                  <li><a href="https://mundogenerador.com/contactos.html" style="font-size: 14px; color: #FFCC00;">CONTACTO</a></li>
                  <li></li>
                  <li></li>
                  <li></li>
                </ul>
              </div>
            </div>

            <div class="rd-navbar-main-outer">
              <div class="rd-navbar-main">
                <div class="rd-navbar-panel"><button class="rd-navbar-toggle" data-rd-navbar-toggle=".rd-navbar-nav-wrap"></button>
                  <div class="rd-navbar-brand">
                    <h1><a class="brand" href="https://mundogenerador.com">MUNDO GENERADOR</a></h1>
                    <a class="brand" href="https://mundogenerador.com">
                      <img alt="Logo Generac" class="brand-logo-dark" height="50" src="images/logo-default-200x34.png" width="199"/>
                      <img alt="Logo Generac" class="brand-logo-light" height="82" src="images/logo-inverse-200x34.png" width="230"/> 
                    </a>
                  </div>
                </div>
                <div class="rd-navbar-main-element">
                  <div class="rd-navbar-nav-wrap">
                    <div class="dropdown">
                      <button class="dropbtn"><a href="https://mundogenerador.com">Home</a></button>
                    </div>
                    <div class="dropdown">
                      <button class="dropbtn"><a href="https://mundogenerador.com/residencial.html" >Residencial <i class="fa fa-caret-down"></i></a></button>
                      <div class="dropdown-content">
                        <a href="https://mundogenerador.com/residencial/residencial-guardian-8-kva.html" >Generador a Gas 8 kva</a>
                        <a href="https://mundogenerador.com/residencial/residencial-guardian-13-kva.html" >Generador a Gas 13 kva</a>
                        <a href="https://mundogenerador.com/residencial/residencial-guardian-17-kva.html" >Generador a Gas 17 kva</a>
                        <a href="https://mundogenerador.com/residencial/generadores-inverters.html" >INVERTERS <span style="text-shadow: 3px 3px 10px #f0d360; font-size: 18px;"><strong>NEW</strong></span></a>
                      </div>
                    </div>
                    <div class="dropdown">
                      <button class="dropbtn">
                        <a href="https://mundogenerador.com/comercial.html">Comercial <i class="fa fa-caret-down"></i></a>
                      </button>
                      <div class="dropdown-content">
                        <a href="https://mundogenerador.com/comercial-27kva.html">Generador a Gas 27 kva</a>
                        <a href="https://mundogenerador.com/comercial-50kva.html">Generador a Gas 50 kva</a>
                        <a href="https://mundogenerador.com/comercial/generador-diesel-generac.html">Generador Diesel GENERAC</a>
                      </div>
                    </div>
                    <div class="dropdown">
                      <button class="dropbtn">
                        <a href="https://mundogenerador.com/industrial.html">Industrial</a>
                      </button>
                    </div>
                    <div class="dropdown">
                      <button class="dropbtn parpadea"><a href="#"><span style="text-shadow: 2px 2px 12px #ff8904; text-decoration: #ff8904;"><strong>Portátiles<i class="fa fa-caret-down"></i></strong></span></a></button>
                      <div class="dropdown-content">
                        <a href="https://mundogenerador.com/residencial/generadores-inverters.html">INVERTERS <span style="text-shadow: 3px 3px 10px #f0d360; font-size: 18px;"><strong>NEW</strong></span></a>
                        <a href="https://mundogenerador.com/jackery/">ENERGÍA PORTÁTIL <span style="text-shadow: 3px 3px 10px #f0d360; font-size: 18px;"><strong>JACKERY</strong></span></a>
                      </div>
                    </div>
                    <div class="dropdown">
                      <button class="dropbtn">
                        <a href="https://www.facebook.com/mundogenerador" target="blank">Clientes <i class="fa fa-caret-down"></i></a>
                      </button>
                      <div class="dropdown-content">
                        <a href="https://www.facebook.com/mundogenerador" target="blank">Instalaciones</a>
                        <a href="https://www.youtube.com/channel/UC9b_fTQoeWKtGiGUpGpL8tA" target="blank">Vídeos Instalaciones</a>
                      </div>
                    </div>
                    <div class="dropdown">
                      <button class="dropbtn">
                        <a href="https://mundogenerador.com/prensa.html">Prensa</a>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </nav>
        </div>
      </header>

      <section class="section main-section parallax-scene-js"
        style="background:url('images/banner-soporte-1920x918.jpg') no-repeat center center; background-size:cover;">
        <div class="container">
          <div class="row justify-content-center"> 
            <div class="col-xl-8 col-12">
              <div class="main-decorated-box text-center text-xl-left">
                <p class="text-white  wow slideInRight" data-wow-delay=".3s">
                  <span class="big font-weight-bold d-inline-flex ">
                    <img alt="Logo Mundo Generador" height="211" src="./images/logo-11anios.png" width="534" />
                  </span>
                </p>
              </div>
            </div>
            <div class="col-12 text-center offset-top-75" data-wow-delay=".2s"></div>
          </div>
        </div>
        <div class="decorate-layer">
          <div class="layer-1">
            <div class="layer" data-depth=".20">
              <img alt="Imagenes triangulos" height="266" src="images/parallax-item-1-563x532.png" width="563"/>
            </div>
          </div>
          <div class="layer-2">
            <div class="layer" data-depth=".30">
              <img alt="Imagenes triangulos" height="171" src="images/parallax-item-2-276x343.png" width="276"/>
            </div>
          </div>
          <div class="layer-3">
            <div class="layer" data-depth=".40">
              <img alt="Imagenes triangulos" height="72" src="images/parallax-item-3-153x144.png" width="153"/>
            </div>
          </div>
          <div class="layer-4">
            <div class="layer" data-depth=".20">
              <img alt="Imagenes triangulos" height="37" src="images/parallax-item-4-69x74.png" width="69"/>
            </div>
          </div>
          <div class="layer-5">
            <div class="layer" data-depth=".40">
              <img alt="Imagenes triangulos" height="37" src="images/parallax-item-5-72x75.png" width="72"/>
            </div>
          </div>
        </div>
      </section>

          <section class="section section-md fondo">
            <h2 class="tituloSistema">Historial de Movimientos</h2>
            <nav>
                <a href="stock.php" class="btn-logout">Sistema Stock</a>
                <a href="logout.php" class="btn-logout">Cerrar sesión</a>
            </nav>

              <div id="tabla-stock">
            </div>
  
            <div class='tabla-responsive'>
            <table class='tabla-historial'>
              <thead>
                  <tr>
                      <th>Producto</th>
                      <th>Tipo de Movimiento</th>
                      <th>Cantidad</th>
                      <th>Fecha del Movimiento</th>
                  </tr>
              </thead>
              <tbody>
                  <?php if (!empty($movimientos)): ?>
                      <?php foreach ($movimientos as $mov): ?>
                          <tr>
                              <td><?= htmlspecialchars($mov['nombreProducto']) ?></td>
                              <td><?= $mov['tipoMovimiento'] ?></td>
                              <td><?= $mov['cantidad'] ?></td>
                              <td><?= $mov['fechaMovimiento'] ?></td>
                          </tr>
                      <?php endforeach; ?>
                  <?php else: ?>
                      <tr><td colspan="5">No hay movimientos registrados.</td></tr>
                  <?php endif; ?>
              </tbody>
          </table>
        </div>
      </section>

      <footer class="section footer-classic section-sm">
        <div class="container">
          <div class="row row-30">
            <div class="col-lg-3 wow fadeInLeft">
              <a class="brand" href="index.html">
                <img alt="" class="brand-logo-dark" height="17" src="images/logo-default-200x34.png" width="100"/>
                <img alt="" class="brand-logo-light" height="17" src="images/logo-inverse-200x34.png" width="100"/>
              </a>
              <p class="footer-classic-description offset-top-0 offset-right-25"><strong>Generadores eléctricos a Gas Natural</strong><br/><strong>¡LOS CORTES DE ENERGÍA YA NO SERÁN UN PROBLEMA!</strong></p>
            </div>

            <div class="col-lg-3 col-sm-8 wow fadeInUp">
              <p class="footer-classic-title">contacto info</p>
              <a class="d-inline-block accent-link" href="mailto:ventas@mundogenerador.com">ventas@mundogenerador.com</a>
              <ul>
                <li>L&iacute;nea gratuita<span class="d-inline-block offset-left-10 text-white"><a class="d-inline-block" href="tel:08006667394" onclick="gtag('event', 'Clic llamada', { event_category: 'Clic llamada', event_action: 'Clic'});">0800 666 7394 </a></span></li>
                <li>Ventas: +54 9 11 6208 0698<br/>+54 9 11 6418 9217</li>
                <li>Service:<span class="d-inline-block offset-left-10 text-white">+54 9 11 3088 4953</span></li>
                <li>Adm.:<span class="d-inline-block offset-left-10 text-white">+54 9 11 3088 4944</span></li>
              </ul>
            </div>

            <div class="col-lg-2 col-sm-4 wow fadeInUp" data-wow-delay=".3s">
              <p class="footer-classic-title">Links</p>
              <ul class="footer-classic-nav-list">
                <li><a href="https://mundogenerador.com/residencial.html">Residenciales</a></li>
                <li><a href="https://mundogenerador.com/comercial.html">Comerciales</a></li>
                <li><a href="https://mundogenerador.com/residencial/generadores-inverters.html">Port&aacute;tiles Inverters</a></li>
                <li><a href="https://mundogenerador.com/industrial.html">Industriales</a></li>
                <li><a href="https://mundogenerador.com/jackery/">Jackery</a></li>
              </ul>
            </div>

            <div class="col-lg-4 wow fadeInLeft" data-wow-delay=".2s">
              <p class="footer-classic-title">Link de Interes</p>
              <ul class="footer-classic-nav-list">
                <li><a href="https://www.facebook.com/mundogenerador" target="_blank">Clientes</a></li>
                <li><a href="https://www.facebook.com/mundogenerador" target="_blank">Instalaciones</a></li>
                <li><a href="https://mundogenerador.com/prensa.html">Prensa</a></li>
                <li><a href="https://workdefender.com" target="_blank">WorkDefender</a></li>
                <li><a href="https://mundogenerador.com/shop//" target="_blank">Shop</a></li>
              </ul>
            </div>
          </div>
        </div>

        <div class="container wow fadeInUp" data-wow-delay=".4s">
          <p style="font-size: 11px;">
            <a href="https://mundogenerador.com/contactos.html" target="_blank">Grupos electr&oacute;genos a Gas Natural y/o envasado</a> - 
            <a href="https://mundogenerador.com/residencial.html" target="_blank">Residencial</a> - 
            <a href="https://mundogenerador.com/comercial.html">Comercial</a> - 
            <a href="https://mundogenerador.com/industrial.html" target="_blank">Industrial</a> - 
            <a href="https://mundogenerador.com/index.html">Generador El&eacute;ctrico a Gas</a> - 
            <a href="https://generacgeneradores.com" target="_blank"> Energ&iacute;a Autom&aacute;tica</a> - 
            <a href="https://mundogenerador.com/residencial.html" target="_blank">Generadores serie Guardi&aacute;n </a> - 
            <a href="https://mundogenerador.com/comercial.html" target="_blank">Energ&iacute;a de respaldos&oacute;lida para la protecci&oacute;n de residencias grandes y empresas</a>  - 
            <a href="https://mundogenerador.com/industrial.html" target="_blank"> Energ&iacute;a de respaldos&oacute;lida para la protecci&oacute;n a nivel Industrial </a> - 
            <a href="https://mundogenerador.com/industrial.html" target="_blank">Grupo Electr&oacute;geno <strong>GENERAC&reg;</strong> Industrial</a> - 
            <a href="https://mundogenerador.com/comercial.html" target="_blank">Grupo Electr&oacute;genos <strong>GENERAC&reg;</strong> 27 kva</a> - 
            <a href="https://mundogenerador.com/comercial-50kva.html" target="_blank">Grupo Electr&oacute;genos<strong>GENERAC&reg;</strong> 50 kva</a> - 
            <a href="https://mundogenerador.com/residencial/residencial-guardian-13-kva.html" target="_blank">Grupo Electr&oacute;geno <strong>GENERAC&reg;</strong> Guardian 13 kva. Monofasico</a> - 
            <a href="https://mundogenerador.com/residencial/residencial-guardian-17-kva.html" target="_blank">Grupo Electr&oacute;geno<strong>GENERAC&reg;</strong>17 kva.Trifasico</a> - 
            <a href="https://mundogenerador.com/generadores-portatiles.html" target="_blank">Port&aacute;tiles</a> - 
            <a href="https://mundogenerador.com/contactos.html" target="_blank">Arranque Autom&aacute;tica</a> - 
            <a href="https://mundogenerador.com/contactos.html" target="_blank">Cero mantenimiento</a> - 
            <a href="https://mundogenerador.com/contactos.html" target="_blank"> Entrega inmediata</a> - 
            <a href="https://mundogenerador.com/contactos.html" target="_blank">Silenciosos.</a>
          </p>
          <div class="footer-classic-aside">
            <p class="rights"><span>&copy;&nbsp;</span>. Dise&ntilde;o web <a href="https://www.tupropiamarca.com" target="blank">TuPropiaMarca.com&reg;</a></p>
          </div>
        </div>
      </footer>
    </div>

        <script src="js/script.js"></script>
        <script>
          function mostrarDetallesStock(categoria) {
            const contenedor = document.getElementById('detalle-stock-' + categoria);
            const boton = document.getElementById('btn-detalle-' + categoria);

            if (contenedor.style.display === 'block') {
              contenedor.style.display = 'none';
              contenedor.innerHTML = '';
              if (boton) boton.textContent = 'Ver detalles';
              return;
            }
            fetch('obtener_actualizar_datos.php?detalles_stock_bajo=1&categoria=' + encodeURIComponent(categoria))
              .then(response => response.text())
              .then(data => {
                contenedor.innerHTML = data;
                contenedor.style.display = 'block';
                if (boton) boton.textContent = 'Ocultar detalles'; 
              })
              .catch(error => {
                contenedor.innerHTML = 'Error al cargar los detalles.';
                contenedor.style.display = 'block';
                if (boton) boton.textContent = 'Ver detalles';
              });
          }
      </script>

  </body>

</html>
