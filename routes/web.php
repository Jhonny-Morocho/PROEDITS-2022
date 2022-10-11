<?php
use Carbon\Carbon;
/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    echo  date_default_timezone_get();
    echo "<br>";
    $hoy = date("Y-m-d H:i:s");
    echo $hoy;
    echo "<br>";  
    echo "CARBON ".Carbon::now();
    return $router->app->version();
});


try {
    // Auth
    //$router->post('register','AuthController@register');
    //$router->post('/proveedor/login','ProveedorController@login');
     $router->group(['prefix' => 'api'], function () use ($router) 
    {

        $router->post('register', 'AuthController@register');
         $router->post('login', 'AuthController@login');  
    }); 
 /*    
    $router->post('/proveedor/login','ProveedorController@login');
    $router->post('/clientes/login','ClienteController@login');
    $router->post('/clientes/loginSocial','ClienteController@loginSocial');
    $router->post('/clientes/recuperarPassword','ClienteController@recuperarPassword');

    //Client
    $router->get('/clientes/listasClientesPanel/{idAdmin}','ClienteController@listasClientesPanel');
    $router->post('/clientes/registrarCliente','ClienteController@registrarCliente');
    $router->get('/clientes/saldoActualCliente/{idCliente}','ClienteController@saldoActualCliente');
    $router->post('/clientes/editarClientePanel/{idAdmin}','ClienteController@editarClientePanel');
    $router->post('/clientes/editarClientePerfil/{idCliente}','ClienteController@editarClientePerfil');


    //Provider
    $router->get('/proveedor/listasProveedores','ProveedorController@listasProveedores');
    $router->post('/proveedor/registrarProveedor/{idAdmin}','ProveedorController@registrarProveedor');
    $router->post('/proveedor/editarProveedor/{idAdmin}/{idProveedor}','ProveedorController@editarProveedor');
    $router->get('/proveedor/listasProveedores/{idAdmin}','ProveedorController@listasProveedores');
    $router->get('/proveedor/listarProvedoresPublico','ProveedorController@listarProvedoresPublico');
    $router->post('/proveedor/subirCaratula/{idAdmin}','ProveedorController@subirCaratula');
    $router->post('/proveedor/editarCaratula/{idAdmin}','ProveedorController@editarCaratula');
    $router->post('/proveedor/eliminarProveedor/{idAdmin}/{idProveedor}','ProveedorController@eliminarProveedor');

    //Product

    $router->post('/producto/obtenerDemoProducto','ProductoController@obtenerDemoProducto');
    $router->get('/proveedor/obtenerRemixerPublico','ProveedorController@obtenerRemixerPublico');
    $router->get('/productos/listasProductos','ProductoController@listasProductos');
    $router->post('/productos/agregarProducto/{idProveedor}','ProductoController@agregarProducto');
    $router->post('/productos/subirDemo/{idProveedor}','ProductoController@subirDemo');
    $router->post('/productos/subirRemix/{idProveedor}','ProductoController@subirRemix');
    $router->post('/carrusel/subirImgCarrusel/{idProveedor}','CarruselController@subirImgCarrusel');
    $router->post('/productos/subirCaratula/{idProveedor}','ProductoController@subirCaratula');
    $router->post('/productos/editarProductoProveedor/{idProveedor}','ProductoController@editarProductoProveedor');
    $router->post('/productos/eliminarArchivoUrlDirectorio/{idProveedor}','ProductoController@eliminarArchivoUrlDirectorio');
    $router->post('/productos/eliminarArchivoUrlDescarga/{idProveedor}','ProductoController@eliminarArchivoUrlDescarga');
    $router->post('/productos/eliminarProductoLogicamente/{idProveedor}','ProductoController@eliminarProductoLogicamente');
    $router->post('/productos/descargarProductoProveedor/{idProveedor}','ProductoController@descargarProductoProveedor');
    $router->get('/productos/listarTodoProductosPanel/{idProveedor}','ProductoController@listarTodoProductosPanel');

    //Ofert
    $router->post('/oferta/aplicarOferta','OfertaController@aplicarOferta');


    //Carrusel
    $router->get('/carrusel/listarCarrusel','CarruselController@listarCarrusel');
    $router->post('/carrusel/eliminarImgCarrusel/{idAdmin}','CarruselController@eliminarImgCarrusel');
    $router->post('/carrusel/editarImgCarrusel/{idAdmin}','CarruselController@editarImgCarrusel');

    //Countries
    $router->get('/paises/listarPaises','PaisesController@listarPaises');


    //Gender
    $router->get('/genero/listarGenero','GeneroController@listarGenero');
    $router->get('/genero/obtenerGenero','GeneroController@obtenerGenero');
    $router->get('/genero/listasGeneroPanel/{idAdmin}','GeneroController@listasGeneroPanel');
    $router->post('/genero/editarGeneroPanel/{idAdmin}','GeneroController@editarGeneroPanel');
    $router->post('/genero/eliminarGeneroPanel/{idAdmin}','GeneroController@eliminarGeneroPanel');
    $router->post('/genero/registrarGeneroPanel/{idAdmin}','GeneroController@registrarGeneroPanel');

    //Order Product
    $router->get('/pedidoProducto/resultadoCompraPaypal','PedidoProductoController@resultadoCompraPaypal');
    $router->post('/pedidoProducto/resultadoCompraPaymentez','PedidoProductoController@resultadoCompraPaymentez');
    $router->post('/pedidoProducto/descargarPedidoProductoPanelCliente/{idCliente}','PedidoProductoController@descargarPedidoProductoPanelCliente');
    $router->get('/pedidoProducto/listarProductosVendidosTop','PedidoProductoController@listarProductosVendidosTop');
    $router->post('/pedidoProducto/listarPedidoClienteUnidadPanelCliente/{idCliente}','PedidoProductoController@listarPedidoClienteUnidadPanelCliente');
    $router->post('/pedidoProducto/listarPedidoClienteUnidadPanelAdmin/{idAdmin}','PedidoProductoController@listarPedidoClienteUnidadPanelAdmin');
    $router->get('/pedidoProducto/listarProductosVendidosProveedorIndividual/{idProveedor}','PedidoProductoController@listarProductosVendidosProveedorIndividual');
    $router->get('/pedidoProducto/productoVendidosProveedor/{idProveedor}','PedidoProductoController@productoVendidosProveedor');
    $router->get('/pedidoProducto/productosVendidosReporte/{idProveedor}','PedidoProductoController@productosVendidosReporte');
    $router->post('/pedidoProducto/pagarProductosProveedor/{idAdmin}','PedidoProductoController@pagarProductosProveedor');
    $router->post('/pedidoProducto/productoVendidosProveedorFiltro/{idAdmin}','PedidoProductoController@productoVendidosProveedorFiltro');

    //Order
    $router->get('/pedido/listarPedidoPanelCliente/{idCliente}','PedidoController@listarPedidoPanelCliente');
    $router->get('/pedido/listarPedidoPanelAdmin/{idAdmin}','PedidoController@listarPedidoPanelAdmin');
    $router->post('/pedido/crearPedidoPaypal/{idCliente}','PedidoController@crearPedidoPaypal');
    $router->post('/pedido/crearPedidoMembresia/{idCliente}','PedidoController@crearPedidoMembresia');
    $router->post('/pedido/crearPedidoPaymentez/{idCliente}','PedidoController@crearPedidoPaymentez');
    $router->post('/pedido/crearPedidoMonedero/{idCliente}','PedidoController@crearPedidoMonedero');

    //Cupon
    $router->get('/cupon/listarCuponPanel','CuponController@listarCuponPanel');
    $router->post('/cupon/editarCuponPanel/{idAdmin}','CuponController@editarCuponPanel');

    //Cliente & Membership
    $router->post('/clienteMembresia/crearPedidoPaypalMembresia/{idCliente}','ClienteMembresiaController@crearPedidoPaypalMembresia');
    $router->post('/clienteMembresia/crearPedidoPaymentezMembresia/{idCliente}','ClienteMembresiaController@crearPedidoPaymentezMembresia');
    $router->post('/clienteMembresia/crearPedidoDepositoMembresia/{idCliente}','ClienteMembresiaController@crearPedidoDepositoMembresia');
    $router->get('/clienteMembresia/resultadoCompraPaypal','ClienteMembresiaController@resultadoCompraPaypal');
    $router->post('/clienteMembresia/resultadoCompraPaymentez','ClienteMembresiaController@resultadoCompraPaymentez');
    $router->get('/clienteMembresia/listarMembresiaClientePanelCliente/{idCliente}','ClienteMembresiaController@listarMembresiaClientePanelCliente');
    $router->get('/clienteMembresia/listarClienteMembresiaPanelAdmin/{idAdmin}','ClienteMembresiaController@listarClienteMembresiaPanelAdmin');

    //Membership
    $router->post('/membresia/editarMembresia/{idAdmin}','MembresiaController@editarMembresia');
    $router->get('/membresia/listarMembresiasPanel/{idAdmin}','MembresiaController@listarMembresiaPanel');
    $router->get('/membresia/listarMembresiaPublico','MembresiaController@listarMembresiaPublico'); */

} catch (\Throwable $th) {
    dd( $th->getMessage());
}
