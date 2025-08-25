<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title></title>
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="CONCESIONARIO OFICIAL GENERAC Y SERVICE OFICIAL GENERAC MAT. TECH. ID. 924906" name=description>
  <meta content="" name=keywords>
  <meta content="index, follow" name=robots>
  <meta content="diseño web TuPropiaMarca.com" name=copyright>
  <meta content=ES name=language>
  <meta content="diseño web TuPropiaMarca.com" name=author>
  <meta content=global name=distribution>
  <meta content=general name=rating>
  <script src="js/core.min.js"></script>
  <link href="css/bootstrap.css" rel="stylesheet" />
  <link href="css/fonts.css" rel="stylesheet" />
  <link href="css/style.css" rel="stylesheet" />
  <style>
    .button5 {
      background-color: white;
      color: black;
      border: 2px solid #555555;
    }
    .button5:hover {
      background-color: #555555;
      color: white;
    }
    @media (min-width: 992px) {
      .main-section {
        padding: 160px 0 25px 0;
      }
    }
  </style>
</head>

<body>
  <div>
    <section class="section section-md fondo">
      <div style="padding: 20px; margin: auto; text-align: center;" class="h4">
        <label for="numeroSerie">Ingrese el número de serie:</label>
      </div>
      <div style="padding: 20px; margin: auto; text-align: center;">
        <input type="text" id="numeroSerie" placeholder="Ingrese el número de serie" style="border: 2px solid #ccc; text-align: center; font-size: 16px; min-width: 25%;">
      </div>
      <div style="padding: 20px; margin: auto; text-align: center;">
        <button onclick="buscarCliente()" class="button button5">Buscar Cliente</button>
      </div>
      <div id='resultado' style="padding: 20px; margin: auto; text-align: center;"></div>
    </section>
  </div>

  <script src="js/script.js"></script>
  <script>
        function buscarCliente() {
          var numeroSerie = document.getElementById('numeroSerie').value;
          if (numeroSerie !== '') {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
              //alert('READYSTATUS ' + this.status + ' ' + this.readyState);
              if (this.readyState == 4) {
                if (this.status == 200) {
                  document.getElementById('resultado').innerHTML = this.responseText;
                }
              }
            }
            xhr.open('GET', 'search.php?numero_serie=' + numeroSerie, true);
            xhr.send();
          } else {
            alert('Ingrese el número de serie');
          }
        }
  </script>
</body>

</html>

<?php
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>