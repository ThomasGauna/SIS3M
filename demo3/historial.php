<?php
require_once 'conexion.php';

$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');

$stmt = $conn->prepare("
    SELECT v.marca, v.modelo, v.patente,
           c.nombre AS conductor,
           m.hora_salida,
           m.hora_regreso,
           m.observaciones,
           m.firma_path
    FROM movimientos m
    JOIN vehiculos v ON m.vehiculo_id = v.id
    JOIN conductores c ON m.conductor_id = c.id
    WHERE DATE(m.hora_salida) = ?
    ORDER BY m.hora_salida DESC
");
$stmt->bind_param("s", $fecha);
$stmt->execute();
$res = $stmt->get_result();
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
    content=""
    name=keywords>
  <meta
    content=""
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
            <div class="rd-navbar-collapse-toggle rd-navbar-fixed-element-1" data-rd-navbar-toggle=".rd-navbar-collapse"></div>
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
                  <li>
                    <a href="https://mundogenerador.com/servicios-y-repuestos.html" style="font-size: 14px; color: #FFCC00;">SOPORTE T&Eacute;CNICO</a>
                  </li>
                  <li>
                    <a href="https://mundogenerador.com/contactos.html" style="font-size: 14px; color: #FFCC00;">CONTACTO</a>
                  </li>
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
                    <a class="brand" href="https://mundogenerador.com"><img alt="Logo Generac" class="brand-logo-dark" height="50" src="images/logo-default-200x34.png" width="199" /><img alt="Logo Generac" class="brand-logo-light" height="82" src="images/logo-inverse-200x34.png" width="230" /> </a>
                  </div>
                </div>
                <div class="rd-navbar-main-element">
                  <div class="rd-navbar-nav-wrap">
                    <div class="dropdown">
                      <button class="dropbtn">
                        <a href="https://mundogenerador.com">Home</a>
                      </button>
                    </div>
                    <div class="dropdown">
                      <button class="dropbtn">
                        <a href="https://mundogenerador.com/residencial.html" >Residencial <i class="fa fa-caret-down"></i></a>
                      </button>
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
                      <button class="dropbtn parpadea">
                        <a href="#"><span style="text-shadow: 2px 2px 12px #ff8904; text-decoration: #ff8904;"><strong>Portátiles<i class="fa fa-caret-down"></i></strong></span></a>
                      </button>
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

      <section class="section main-section parallax-scene-js" style="background:url('images/banner-soporte-1920x918.jpg') no-repeat center center; background-size:cover;">
        <div class="container">
          <div class="row justify-content-center"> 
            <div class="col-xl-8 col-12">
              <div class="main-decorated-box text-center text-xl-left">
                <p class="text-white  wow slideInRight" data-wow-delay=".3s">
                  <span class="big font-weight-bold d-inline-flex ">
                    <img alt="Logo Mundo Generador" height="211" src="./images/logo-11anios.png" width="534"/>
                  </span>
                </p>
              </div>
            </div>
            <div class="col-12 text-center offset-top-75" data-wow-delay=".2s"></div>
          </div>
        </div>
        <div class="decorate-layer">
          <div class="layer-1">
            <div class="layer" data-depth=".20"><img alt="Imagenes triangulos" height="266" src="images/parallax-item-1-563x532.png" width="563"/></div>
          </div>
          <div class="layer-2">
            <div class="layer" data-depth=".30"><img alt="Imagenes triangulos" height="171" src="images/parallax-item-2-276x343.png" width="276"/></div>
          </div>
          <div class="layer-3">
            <div class="layer" data-depth=".40"><img alt="Imagenes triangulos" height="72" src="images/parallax-item-3-153x144.png" width="153"/></div>
          </div>
          <div class="layer-4">
            <div class="layer" data-depth=".20"><img alt="Imagenes triangulos" height="37" src="images/parallax-item-4-69x74.png" width="69"/></div>
          </div>
          <div class="layer-5">
            <div class="layer" data-depth=".40"><img alt="Imagenes triangulos" height="37" src="images/parallax-item-5-72x75.png" width="72"/></div>
          </div>
        </div>
      </section>

    <section class="section section-md fondo" style="padding-bottom: 0px;">
     <div class="contenedor-general" style="padding: 0px;">
            <h2>Historial de Movimientos</h2>
        </div>
    </section>

    <section class="section section-md fondo" style="padding-top: 0px;">
          <div class="contenedor-general">
            <form method="GET">
              <label for="fecha">Seleccionar fecha: <br>
                <input type="date" name="fecha" id="fecha" value="<?php echo $fecha; ?>"><button type="submit">Ver historial</button>
              </label>
            </form>

            <table class="responsive-table">
              <thead>
                <tr>
                  <th>Marca</th>
                  <th>Modelo</th>
                  <th>Patente</th>
                  <th>Conductor</th>
                  <th>Hora salida</th>
                  <th>Hora regreso</th>
                  <th>Observación</th>
                  <th>Firma</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($res->num_rows > 0) {
                    while ($row = $res->fetch_assoc()) {
                        echo "<tr>
                          <td data-label='Marca:'>{$row['marca']}</td>
                          <td data-label='Modelo:'>{$row['modelo']}</td>
                          <td data-label='Patente:'>{$row['patente']}</td>
                          <td data-label='Conductor:'>{$row['conductor']}</td>
                          <td data-label='Hora salida:'>" . date('d-m-Y H:i', strtotime($row['hora_salida'])) . "</td>
                          <td data-label='Hora regreso:'>" . ($row['hora_regreso'] ? date('d-m-Y H:i', strtotime($row['hora_regreso'])) : '-') . "</td>
                          <td data-label='Observación:'>" . ($row['observaciones'] ?? '-') . "</td>
                          <td data-label='Firma:'><a class='btn' href='{$row['firma_path']}' target='_blank'>Ver firma</a></td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No hay movimientos registrados en esta fecha.</td></tr>";
                }
                ?>
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
            <a href="https://mundogenerador.com/comercial-50kva.html" target="_blank">Grupo Electr&oacute;genos <strong>GENERAC&reg;</strong> 50 kva</a> - 
            <a href="https://mundogenerador.com/residencial/residencial-guardian-13-kva.html" target="_blank">Grupo Electr&oacute;geno <strong>GENERAC&reg;</strong> Guardian 13 kva. Monofasico</a> - 
            <a href="https://mundogenerador.com/residencial/residencial-guardian-17-kva.html" target="_blank">Grupo Electr&oacute;geno <strong>GENERAC&reg;</strong> 17 kva.Trifasico</a> - 
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
    
  </body>
</html>