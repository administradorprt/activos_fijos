<?php

use App\Http\Controllers\CargaController;
use App\Http\Controllers\CarritosController;
use App\Http\Controllers\EquipoComputoController;
use App\Http\Controllers\EquipoTransporteController;
use App\Http\Controllers\HerramientasController;
use App\Http\Controllers\ManteActivosController;
use App\Http\Controllers\MantenimientosController;
use App\Http\Controllers\MaquinariaEquipoController;
use App\Http\Controllers\MobiliarioEquipoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TipoController;
use App\Http\Controllers\UrlsPublicasController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if(Auth::check()){
        return to_route('dashboard');
    }else{
        return to_route('login');
    }
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->prefix('admin')->group(function () {
    /* por defecto de Laravel */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    /* --------------------- */

    /* Añadir rutas específicas para el administrador */
    Route::get('/', function(){
        return redirect('/dashboard');
    });
    Route::middleware(['auth'])->prefix('inventario')->group(function(){
        //tipos
        Route::resource('Tipo', TipoController::class);

        //transporte
        Route::controller(EquipoTransporteController::class)->group(function(){
            Route::get('EquipoTransporte/Responsivas','responsivas' );
            Route::post('EquipoTransporte/Responsivas','imprimir_responsiva')->name('EquipoTransporte.Responsivas.post');
            Route::get('EquipoTransporte/Reporte', 'reporte');
            Route::get('EquipoTransporte/Reporte/{id}', 'reportes');
            Route::post('EquipoTransporte/activar','activar')->name('EquipoTransporte.activar');
            Route::get('EquipoTransporte/Responsiva/{id}', 'responsiva')->name('EquipoTransporte.pdf');
            Route::post('EquipoTransporte/borrar_img','borrar_img');
        });
        Route::resource('EquipoTransporte', EquipoTransporteController::class);
        
        //computo
        Route::controller(EquipoComputoController::class)->group(function(){
            Route::get('EquipoComputo/Responsivas','responsivas' );
            Route::post('EquipoComputo/Responsivas','imprimir_responsiva');
            Route::get('EquipoComputo/Reporte', 'reporte');
            Route::get('EquipoComputo/Reporte/{id}', 'reportes');
            Route::post('EquipoComputo/activar','activar')->name('EquipoComputo.activar');
            Route::get('EquipoComputo/Responsiva/{id}', 'responsiva')->name('EquipoComputo.pdf');
            Route::post('EquipoComputo/borrar_img','borrar_img');
        });
        Route::Resource('EquipoComputo', EquipoComputoController::class);

        //mobiliario
        Route::controller(MobiliarioEquipoController::class)->group(function(){
            Route::get('MobiliarioEquipo/Responsivas','responsivas');
            Route::post('MobiliarioEquipo/Responsivas','imprimir_responsiva');
            Route::get('MobiliarioEquipo/Reporte', 'reporte');
            Route::get('MobiliarioEquipo/Reporte/{id}', 'reportes');
            Route::post('MobiliarioEquipo/activar','activar')->name('MobiliarioEquipo.activar');
            Route::get('MobiliarioEquipo/Responsiva/{id}', 'responsiva')->name('MobiliarioEquipo.pdf');
            Route::post('MobiliarioEquipo/borrar_img','borrar_img');
        });
        Route::Resource('MobiliarioEquipo', MobiliarioEquipoController::class);

        //maquinaria
        Route::controller(MaquinariaEquipoController::class)->group(function(){
            Route::get('MaquinariaEquipo/Responsivas','responsivas');
            Route::post('MaquinariaEquipo/Responsivas','imprimir_responsiva');
            Route::get('MaquinariaEquipo/Reporte', 'reporte');
            Route::get('MaquinariaEquipo/Reporte/{id}', 'reportes');
            Route::post('MaquinariaEquipo/activar','activar')->name('MaquinariaEquipo.activar');
            Route::get('MaquinariaEquipo/Responsiva/{id}', 'responsiva')->name('MaquinariaEquipo.pdf');
            Route::post('MaquinariaEquipo/borrar_img','borrar_img');
        });
        Route::Resource('MaquinariaEquipo', MaquinariaEquipoController::class);

        //herramientas
        Route::controller(HerramientasController::class)->group(function(){
            Route::get('Herramientas/Responsivas','responsivas');
            Route::post('Herramientas/Responsivas','imprimir_responsiva')->name('Herramientas.responsivas.post');
            Route::get('Herramientas/Reporte', 'reporte');
            Route::get('Herramientas/Reporte/{id}', 'reportes');
            Route::post('Herramientas/activar','activar')->name('Herramientas.activar');
            Route::get('Herramientas/Responsiva/{id}', 'responsiva')->name('Herramientas.pdf');
            Route::post('Herramientas/borrar_img','borrar_img');
        });
        Route::Resource('Herramientas', HerramientasController::class);

        //carga masiva
        Route::controller(CargaController::class)->group(function(){
            Route::get('CargarArchivoMasivaEQT','index_eqt');
            Route::post('EquipoTransporte/importar_transportes','importar_eqt')->name('ImportarTransporte');;
            Route::get('CargarArchivoMasivaEQC','index_eqc');
            Route::post('EquipoComputo/importar_computo','importar_eqc')->name('ImportarComputo');
            Route::get('CargarArchivoMasivaMOE','index_moe');
            Route::post('EquipoMobiliario/importar_mobiliario','importar_moe')->name('ImportarMobiliario');
            Route::get('CargarArchivoMasivaMAE','index_mae');
            Route::post('EquipoMaquinaria/importar_maquinaria','importar_mae')->name('ImportarMaquinaria');
            Route::get('CargarArchivoMasivaHER','index_her');
            Route::post('EquipoHeramienta/importar_heramienta','importar_her')->name('ImportarHerramientas');
        });

        Route::controller(ManteActivosController::class)->group(function(){
            Route::get('Mantenimiento/Activos/{tipo}','index')->name('mante');
            Route::get('Mantenimiento/Activos/{tipo}/create','create')->name('manteAct.create');
            Route::post('Mantenimiento/Activos/store','store')->name('manteAct.store');
            Route::get('Mantenimiento/Activos/show/{mante}','show')->name('manteAct.show');
            Route::delete('Mantenimiento/Activos/delete/{id}','destroy')->name('manteAct.delete');
            Route::post('Mantenimiento/Activos/activar/{id}','activar')->name('manteAct.activar');
        });
        //mantenimientos
        Route::controller(MantenimientosController::class)->group(function(){
            Route::get('Mantenimiento/{activo}/create','create')->name('mante.create');
            Route::post('Mantenimientos/store/{activo}','store')->name('mante.store');
            Route::patch('Mantenimiento/update_prox_mante/{activo}','updateProxMante')->name('mante.updateProx');
            Route::get('Mantenimiento/edit/{mante}','edit')->name('mante.edit');
            Route::patch('Mantenimiento/update/{mante}','update')->name('mante.update');
            Route::get('Mantenimiento/{activo}/historico','historico')->name('mante.history');
        });
        //carritos
        Route::controller(CarritosController::class)->group(function(){
            Route::get('Carritos/{tipo}','index')->name('carritos');
            Route::get('Carritos/{tipo}/create','create')->name('carritos.create');
            Route::post('Carritos/store/{tipo}','store')->name('carritos.store');
        });

        //controlador de servicios de petiones
        Route::controller(ServiceController::class)->group(function(){
            Route::get('service/get/activos','getActivos')->name('service.getActivos');
            Route::get('service/get/activos/{giro}','getActivosByType')->name('service.getActivosType');
            Route::get('service/get/activos/{sucursal}/{giro}','getActivosBySucursal')->name('service.getActivosBySuc');
            Route::get('service/mantenimiento/activos/{id}/qr','getQr')->name('service.manteAct.qr');
            Route::delete('service/mantenimiento/archivos/delete/{mante}','delArchivosMante')->name('service.mante.delArchivos');
            Route::get('service/empleados/{sucursal}','getEmpleadosBySucursal')->name('service.empleados.suc');
        });
    });
});

//rutas de servicios públicos
Route::controller(ServiceController::class)->group(function(){
    Route::get('/service/mantenimiento/{id}','getManto')->name('service.manto');
});

Route::controller(UrlsPublicasController::class)->group(function(){
    Route::get('/mantenimiento/show/{mante}/public','publicShow')->name('manteAct.show.public');
    Route::get('/public/Mantenimiento/{activo}/historico','historico')->name('public.mante.history');
});
require __DIR__.'/auth.php';
