<?php
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Server\RequestHandlerInterface;
    use Slim\Factory\AppFactory;
    use Slim\Routing\RouteCollectorProxy;
    use Slim\Routing\RouteContext;
    
    require __DIR__ . '/../vendor/autoload.php';
    require './controllers/cuentaController.php';
    require './controllers/depositoController.php';
    require './controllers/retiroController.php';
    require './controllers/ajusteController.php';
    require './controllers/consultaController.php';
    require './middlewares/cuentaMiddleware.php';
    require './middlewares/depositoMiddleware.php';
    require './middlewares/retiroMiddleware.php';
    require './middlewares/ajusteMiddleware.php';
    require './middlewares/consultaMiddleware.php';

    $app = AppFactory::create();

    $app->group('/cuenta', function (RouteCollectorProxy $group) {
        $group->post('[/]', \CuentaController::class . ':altaCuenta')->add(new CargarCuenta());
        $group->post('/consultaSaldo', \CuentaController::class . ':consultarCuenta');
        $group->delete('/{nroCuenta}', \CuentaController::class . ':eliminarCuenta');
        $group->put('/{nroCuenta}', \CuentaController::class . ':modificarCuenta')->add(new ModificarCuenta());
    });

    $app->group('/deposito', function (RouteCollectorProxy $group) {
        $group->post('[/]', \DepositoController::class . ':altaDeposito')->add(new CargarDeposito());
    });

    $app->group('/retiro', function (RouteCollectorProxy $group) {
        $group->post('[/]', \RetiroController::class . ':realizarRetiro')->add(new CargarRetiro());
    });

    $app->group('/ajuste', function (RouteCollectorProxy $group) {
        $group->post('[/]', \AjusteController::class . ':realizarAjuste')->add(new CargarAjuste());
    });

    $app->group('/consultas', function (RouteCollectorProxy $group) {
        $group->get('/deposito', \ConsultaController::class . ':consultaDeposito')->add(new ConsultaFecha());
        $group->get('/deposito/cuenta', \ConsultaController::class . ':consultaDepositoCuenta')->add(new ConsultaCuenta());
        $group->get('/deposito/tipoCuenta', \ConsultaController::class . ':consultaDepositoTipoCuenta')->add(new ConsultaTipoCuenta());
        $group->get('/deposito/moneda', \ConsultaController::class . ':consultaDepositoMoneda')->add(new ConsultaMoneda());
        $group->get('/retiro', \ConsultaController::class . ':consultaRetiro')->add(new ConsultaFecha());
        $group->get('/retiro/cuenta', \ConsultaController::class . ':consultaRetiroCuenta')->add(new ConsultaCuenta());
        $group->get('/retiro/tipoCuenta', \ConsultaController::class . ':consultaRetiroTipoCuenta')->add(new ConsultaTipoCuenta());
        $group->get('/retiro/moneda', \ConsultaController::class . ':consultaRetiroMoneda')->add(new ConsultaMoneda());
        $group->get('/retiroDeposito', \ConsultaController::class . ':consultaRetiroDepositoPorCuenta')->add(new ConsultaCuenta());
    });

    $app->run();
?>