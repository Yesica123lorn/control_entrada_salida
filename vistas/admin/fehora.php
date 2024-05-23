<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Fecha y hora</title>

    <link href="img1/logo9.png" rel="icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Fechas y Horas<br>De Reportes</h1>
                            </div>
                            <form class="user" method="post" action="excel_usu.php
                            ">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <label>Fecha De Inicio:</label>
                                        <input type="Date" class="form-control" id="exampleFirstName" name="Fecha_sus">
                                    </div>
                                    <div class="col-sm-6">
                                    <label>Hora De Inicio:</label>
                                        <input type="time" class="form-control" id="exampleLastName" name="hora_sus">
                                    </div>
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <label>Fecha De Hasta Cuando </label>
                                        <input type="date" class="form-control" id="exampleFirstName" name="fecha_exp">
                                    </div>
                                    <div class="col-sm-6">
                                    <label>Hora De Hasta Cuando  </label>
                                        <input type="time" class="form-control" id="exampleLastName" name="hora_exp">
                                    </div>
                                    
                                   
                                </div>
                                
                                <input type="submit" class="btn btn-primary btn-block" name="Suscribir">
                                <input type="hidden" name="validar_V" value="user">
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="index.php">Cancelar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>