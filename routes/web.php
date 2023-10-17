<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatatableAjaxController;
use App\Http\Controllers\Estatistica\EstatisticaController;
use App\Http\Controllers\Instancias\FederacaoController;
use App\Http\Controllers\Formularios\FormularioFederacaoController;
use App\Http\Controllers\Formularios\FormularioLocalController;
use App\Http\Controllers\Formularios\FormularioSinodalController;
use App\Http\Controllers\Instancias\LocalController;
use App\Http\Controllers\PesquisaController;
use App\Http\Controllers\Instancias\SinodalController;
use App\Http\Controllers\TutorialController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes(['register' => false]);



Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home');
Route::get('/estatistica', [EstatisticaController::class, 'externo'])
    ->name('estatistica');

Route::group(['prefix' => 'graficos'], function () {
    Route::post('/', [EstatisticaController::class, 'graficos'])
        ->name('graficos.index');
});

Route::group(['middleware' => ['auth', 'auth-sistema'], 'prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
    Route::get('/home', [DashboardController::class, 'index'])
        ->name('home');
    Route::post('/trocar-senha', [DashboardController::class, 'trocarSenha'])
        ->name('trocar-senha');

    Route::group(['modulo' => 'usuarios'], function () {
        Route::resource('usuarios', UserController::class)
            ->names('usuarios');
        Route::post('/usuarios-senha-reset/{usuario}', [UserController::class, 'resetSenha'])

            ->name('usuarios.reset-senha');
        Route::post('/check-usuario', [UserController::class, 'checkUser'])
            ->name('usuarios.check-usuario');
    });

    Route::group(['modulo' => 'sinodais'], function () {
        Route::resource('sinodais', SinodalController::class)
            ->parameters(['sinodais' => 'sinodal'])
            ->except('delete')
            ->names('sinodais');
        Route::get('/sinodais/{sinodal}/delete', [SinodalController::class, 'delete'])
            ->name('sinodais.delete');
        Route::put('/sinodais/{sinodal}/update-info', [SinodalController::class, 'updateInfo'])
            ->name('sinodais.update-info');
        Route::get('/sinodais/get-ranking', [SinodalController::class, 'getRanking'])
            ->name('sinodais.get-ranking');
    });
    Route::group(['modulo' => 'federacoes'], function () {
        Route::resource('federacoes', FederacaoController::class)
            ->parameters(['federacoes' => 'federacao'])
            ->names('federacoes')
            ->except('delete');
        Route::get('/federacoes/{federacao}/delete', [FederacaoController::class, 'delete'])
            ->name('federacoes.delete');
        Route::put('/federacoes/{federacao}/update-info', [FederacaoController::class, 'updateInfo'])
            ->name('federacoes.update-info');
    });
    Route::group(['modulo' => 'uphs-locais'], function () {
        Route::resource('uphs-locais', LocalController::class)
            ->parameters(['uphs-locais' => 'local'])
            ->names('locais')
            ->except('delete');
        Route::get('/uphs-locais/{local}/delete', [LocalController::class, 'delete'])
            ->name('locais.delete');
        Route::put('/uphs-locais/{local}/update-info', [LocalController::class, 'updateInfo'])
            ->name('locais.update-info');
    });

    Route::group(['modulo' => 'formularios-locais'], function () {
        Route::get('/formularios-locais', [FormularioLocalController::class, 'index'])
            ->name('formularios-locais.index');
        Route::post('/formularios-locais', [FormularioLocalController::class, 'store'])
            ->name('formularios-locais.store');
        Route::post('/formularios-locais-view', [FormularioLocalController::class, 'view'])
            ->name('formularios-locais.view');
        Route::get('/formularios-locais-export/{ano}', [FormularioLocalController::class, 'export'])
            ->name('formularios-locais.export');
        Route::get('/formularios-local-export/{local}', [FormularioLocalController::class, 'localExport'])
            ->name('formularios-local.export');

    });

    Route::group(['modulo' => 'formularios-sinodais'], function () {
        Route::get('/formularios-sinodais', [FormularioSinodalController::class, 'index'])
            ->name('formularios-sinodais.index');
        Route::post('/formularios-sinodais', [FormularioSinodalController::class, 'store'])
            ->name('formularios-sinodais.store');
        Route::post('/formularios-sinodais-view', [FormularioSinodalController::class, 'view'])
            ->name('formularios-sinodais.view');
        Route::post('/formularios-sinodais-resumo', [FormularioSinodalController::class, 'resumoTotalizador'])
            ->name('formularios-sinodais.resumo');
        Route::get('/formularios-sinodais-get-federacoes', [FormularioSinodalController::class, 'getFederacoes'])
            ->name('formularios-sinodais.get-federacoes');
        Route::get('/formularios-sinodais-export/{ano}', [FormularioSinodalController::class, 'export'])
            ->name('formularios-sinodais.export');
        Route::get('/formularios-sinodal-export/{sinodal}', [FormularioSinodalController::class, 'sinodalExport'])
            ->name('formularios-sinodal.export');

    });

    Route::group(['modulo' => 'formularios-federacoes'], function () {
        Route::get('/formularios-federacoes', [FormularioFederacaoController::class, 'index'])
            ->name('formularios-federacoes.index');
        Route::post('/formularios-federacoes', [FormularioFederacaoController::class, 'store'])
            ->name('formularios-federacoes.store');
        Route::post('/formularios-federacoes-view', [FormularioFederacaoController::class, 'view'])
            ->name('formularios-federacoes.view');
        Route::post('/formularios-federacoes-resumo', [FormularioFederacaoController::class, 'resumoTotalizador'])
            ->name('formularios-federacoes.resumo');
        Route::get('/formularios-federacoes-export/{ano}', [FormularioFederacaoController::class, 'export'])
            ->name('formularios-federacoes.export');
        Route::get(
            '/formularios-federacao-export/{federacao}',
            [FormularioFederacaoController::class, 'federacaoExport']
        )->name('formularios-federacao.export');
    });

    Route::group(['modulo' => 'pesquisas'], function () {
        Route::resource('/pesquisas', PesquisaController::class)
            ->names('pesquisas');
        Route::get('/pesquisas/{pesquisa}/status', [PesquisaController::class, 'status'])
            ->name('pesquisas.status');
        Route::get('/pesquisas/{pesquisa}/respostas', [PesquisaController::class, 'respostas'])
            ->name('pesquisas.respostas');
        Route::post('/pesquisas-responder', [PesquisaController::class, 'responder'])
            ->name('pesquisas.responder');
        Route::get('/pesquisas/{pesquisa}/configuracoes', [PesquisaController::class, 'configuracoes'])
            ->name('pesquisas.configuracoes');
        Route::get('/pesquisas/{pesquisa}/relatorio', [PesquisaController::class, 'relatorio'])
            ->name('pesquisas.relatorio');
        Route::get('/pesquisas/{pesquisa}/limpar-respostas', [PesquisaController::class, 'limparRespostas'])
            ->name('pesquisas.limpar-respostas');
        Route::put('/pesquisas-configuracoes/{pesquisa}/update', [PesquisaController::class, 'configuracoesUpdate'])
            ->name('pesquisas.configuracoes-update');
        Route::get('/pesquisas-configuracoes/{pesquisa}/export', [PesquisaController::class, 'exportExcel'])
            ->name('pesquisas.relatorio.excel');
        Route::get('/pesquisas-acompanhar/{pesquisa}', [PesquisaController::class, 'acompanhar'])
            ->name('pesquisas.acompanhar');
    });

    // PAINEL ESTATISTICA
    Route::group(['modulo' => 'secretaria-estatistica'], function () {
        Route::get('/estatistica', [EstatisticaController::class, 'index'])
            ->name('estatistica.index');
        Route::post('/estatistica/atualizarParametro', [EstatisticaController::class, 'atualizarParametro'])
            ->name('estatistica.atualizarParametro');
        Route::post('/estatistica/exportarExcel', [EstatisticaController::class, 'exportarExcel'])
            ->name('estatistica.exportarExcel');
        Route::get('/estatistica/atualizar-ranking', [EstatisticaController::class, 'atualizarRanking'])
            ->name('estatistica.atualizar-ranking');
    });

    Route::group(['modulo' => 'tutoriais'], function () {
        Route::get('/tutoriais', [TutorialController::class, 'index'])
            ->name('tutoriais.index');
    });

    // DATATABLES
    Route::group(['modulo' => 'datatables'], function () {
        Route::get('/datatables/log-erro', [DatatableAjaxController::class, 'logErros'])
            ->name('datatables.log-erros');
        Route::get(
            '/datatables/formularios-entregues/{instancia}/{id?}',
            [DatatableAjaxController::class, 'formulariosEntregues']
        )->name('datatables.formularios-entregues');
        Route::get(
            '/datatables/informacao-federacoes/{federacao}',
            [DatatableAjaxController::class, 'informacaoFederacao']
        )->name('datatables.informacao-federacoes');
        Route::get(
            '/datatables/pesquisas/{pesquisa}/sinodais',
            [DatatableAjaxController::class, 'acompanhamentoPesquisaSinodais']
        )->name('datatables.pesquisas.sinodais');
        Route::get(
            '/datatables/pesquisas/{pesquisa}/federacoes',
            [DatatableAjaxController::class, 'acompanhamentoPesquisaFederacoes']
        )->name('datatables.pesquisas.federacoes');
        Route::get(
            '/datatables/pesquisas/{pesquisa}/locais',
            [DatatableAjaxController::class, 'acompanhamentoPesquisaLocais']
        )->name('datatables.pesquisas.locais');

        Route::get(
            '/datatables/estatistica/formularios-sinodais',
            [DatatableAjaxController::class, 'estatisticaFormulariosSinodais']
        )->name('datatables.estatistica.formularios-sinodais');
        Route::get(
            '/datatables/estatistica/formularios-locais/{id}',
            [DatatableAjaxController::class, 'estatisticaFormulariosLocais']
        )->name('datatables.estatistica.formularios-locais');
    });

});
