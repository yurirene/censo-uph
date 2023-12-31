<?php

namespace App\Services\Instancias;

use App\Models\Estado;
use App\Models\Federacao;
use App\Models\FormularioFederacao;
use App\Models\FormularioSinodal;
use App\Models\Local;
use App\Models\Parametro;
use App\Models\Sinodal;
use App\Models\User;
use App\Services\LogErroService;
use App\Services\UserService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SinodalService
{

    public static function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $sinodal = Sinodal::create([
                'nome' => $request->nome,
                'sigla' => $request->sigla,
                'regiao_id' => $request->regiao_id,
                'status' => $request->status == 'A' ? true : false
            ]);


            if ($request->has('email_usuario')) {
                $usuario = UserService::usuarioVinculado($request, $sinodal, 'sinodal', 'sinodais');
                if ($request->has('resetar_senha')) {
                    UserService::resetarSenha($usuario);
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            LogErroService::registrar([
                'message' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile()
            ]);
            throw new Exception("Erro ao Salvar");

        }
    }

    public static function update(Sinodal $sinodal, Request $request)
    {
        DB::beginTransaction();
        try {
            $sinodal->update([
                'nome' => $request->nome,
                'sigla' => $request->sigla,
                'regiao_id' => $request->regiao_id,
                'status' => $request->status == 'A' ? true : false
            ]);

            if ($request->has('email_usuario')) {
                $usuario = UserService::usuarioVinculado($request, $sinodal, 'sinodal', 'sinodais');
                if ($request->has('resetar_senha')) {
                    UserService::resetarSenha($usuario);
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            LogErroService::registrar([
                'message' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile()
            ]);
            throw new Exception("Erro ao Atualizar");

        }
    }


    public static function updateInfo(Sinodal $sinodal, Request $request)
    {
        DB::beginTransaction();
        try {
            $sinodal->update([
                'nome' => $request->nome,
                'sinodo' => $request->sinodo,
                'data_organizacao' => Carbon::createFromFormat('d/m/Y', $request->data_organizacao)->format('Y-m-d'),
                'midias_sociais' => $request->midias_sociais
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            LogErroService::registrar([
                'message' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile()
            ]);
            throw new Exception("Erro ao Atualizar");

        }
    }
    public static function getEstados()
    {
        try {
            $usuario = User::find(Auth::id());
            $regioes = Estado::whereIn('regiao_id', $usuario->regioes->pluck('id'))
                ->get()
                ->pluck('nome', 'id');
            return $regioes;
        } catch (\Throwable $th) {
            LogErroService::registrar([
                'message' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile()
            ]);
        }
    }

    public static function getTotalizadores()
    {
        try {
            $sinodal = auth()->user()->sinodais->first();
            $federacoes = Federacao::where('sinodal_id', $sinodal->id)->get();
            $uphs = Local::whereIn('federacao_id', $federacoes->pluck('id'))->get();
            $formularios = FormularioFederacao::whereIn('federacao_id', $federacoes->pluck('id'))->where('ano_referencia', Parametro::where('nome', 'ano_referencia')->first()->valor)->get();
            if (!$formularios) {
                return [
                    'total_presbiterios' => $federacoes->count(),
                    'total_igrejas' => $uphs->count(),
                    'total_n_sociedades_internas' => $uphs->where('outro_modelo', true)->count(),
                    'total_federacoes' => $federacoes->where('status', true)->count(),
                    'total_uphs' => $uphs->where('status', true)->count(),
                    'total_socios' => 0,
                ];
            }
            $total_socios = 0;
            $total_uphs = 0;
            foreach ($formularios as $formulario) {
                $total_uphs += intval($formulario->estrutura['uph_organizada']);
                $total_socios += intval($formulario->perfil['ativos']) + intval($formulario->perfil['cooperadores']);
            }
            return [
                'total_presbiterios' => $federacoes->count(),
                'total_igrejas' => $uphs->count(),
                'total_n_sociedades_internas' => $uphs->where('outro_modelo', true)->count(),
                'total_federacoes' => $federacoes->where('status', true)->count(),
                'total_uphs' => ($total_uphs == 0 && $uphs->where('status', true)->count() > 0) ? $uphs->where('status', true)->count() : $total_uphs . ' <small style="font-size: 9px;">(Retirado do Formulário Estatístico)</small>',
                'total_socios' => $total_socios . ' <small style="font-size: 9px;">(Retirado do Formulário Estatístico)</small>'
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function getInfo()
    {
        try {
            return auth()->user()->sinodais->first();
        } catch (\Throwable $th) {
            LogErroService::registrar([
                'message' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile()
            ]);
            throw $th;
        }
    }

    public static function delete(Sinodal $sinodal)
    {
        DB::beginTransaction();
        try {
            $sinodal->usuario->first()->update([
                'email' => 'apagadoComASinodalEm'.date('dmyhms').'@apagado.com'
            ]);
            $usuario = $sinodal->usuario->first();
            $sinodal->usuario()->sync([]);
            $usuario->delete();
            $sinodal->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            LogErroService::registrar([
                'message' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile()
            ]);
            throw $th;
        }
    }

    public static function getInformacoesOrganizacao(Sinodal $sinodal) : array
    {
        try {
            $formulario = FormularioSinodal::where('sinodal_id', $sinodal->id)->where('ano_referencia', Parametro::where('nome', 'ano_referencia')->first()->valor)->first();

            $total_uphs_organizada = self::getTotalUmpsOrganizadas($sinodal, $formulario);
            $total_federacoes_organizada = self::getTotalFederacoesOrganizadas($sinodal, $formulario);


            $total_uphs_organizada = self::getPorcentagem($total_uphs_organizada['total'], $total_uphs_organizada['organizadas']);
            $total_federacoes_organizada = self::getPorcentagem($total_federacoes_organizada['total'], $total_federacoes_organizada['organizadas']);
            $total_igrejas_n_sociedades = self::getPorcentagem($sinodal->locais->count(), $sinodal->locais->where('outro_modelo', true)->count());

            return [
                'total_uphs_organizada' => $total_uphs_organizada,
                'total_federacoes_organizada' => $total_federacoes_organizada,
                'total_igrejas_n_sociedades' => $total_igrejas_n_sociedades
            ];
        } catch (\Throwable $th) {
            LogErroService::registrar([
                'message' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile()
            ]);
            throw $th;
        }
    }

    public static function getTotalUmpsOrganizadas(Sinodal $sinodal, FormularioSinodal $formulario = null) : array
    {
        if (!is_null($formulario)) {
            $total = ($formulario->estrutura['uph_organizada'] ?? 0) + ($formulario->estrutura['uph_nao_organizada'] ?? 0);
            return [
                'total' => $total,
                'organizadas' => $formulario->estrutura['uph_organizada'] ?? 0
            ];
        }
        return [
            'total' => $sinodal->locais->count(),
            'organizadas' => $sinodal->locais->where('status', true)->count()
        ];
    }
    public static function getTotalFederacoesOrganizadas(Sinodal $sinodal, FormularioSinodal $formulario = null) : array
    {
        if (!is_null($formulario)) {
            $total = ($formulario->estrutura['federacao_organizada'] ?? 0) + ($formulario->estrutura['federacao_nao_organizada'] ?? 0);
            return [
                'total' => $total,
                'organizadas' => $formulario->estrutura['federacao_organizada'] ?? 0
            ];
        }
        return [
            'total' => $sinodal->federacoes->count(),
            'organizadas' => $sinodal->federacoes->where('status', true)->count()
        ];
    }

    public static function getPorcentagem($total, $valor)
    {
        if ($total == 0) {
            return 0;
        }
        $resultado = ($valor * 100) / $total;
        return floatval(number_format($resultado, 2));
    }

    public static function getInformacoesFederacoesShow(Sinodal $sinodal) : array
    {
        try {
            $federacoes = $sinodal->federacoes;
            $info_federacao = [];
            foreach ($federacoes as $federacao) {
                $formulario = FormularioFederacao::where('federacao_id', $federacao->id)->where('ano_referencia', Parametro::where('nome', 'ano_referencia')->first()->valor)->first();
                $total_uphs_organizada = FederacaoService::getTotalUmpsOrganizadas($federacao, $formulario);;

                $utlimo_formulario = $federacao->relatorios->last();

                $total_socios = 0;
                if (!is_null($utlimo_formulario)) {
                    $total_socios = intval($utlimo_formulario->perfil['ativos'] ?? 0) + intval($utlimo_formulario->perfil['cooperadores'] ?? 0);
                }

                $info_federacao[] = [
                    'id' => $federacao->id,
                    'nome' => $federacao->nome,
                    'sigla' => $federacao->sigla,
                    'numero_uphs' => $total_uphs_organizada['organizadas'] . ' de ' . $total_uphs_organizada['total'],
                    'numero_socios' => $total_socios,
                    'status' => $federacao->status
                ];
            }
            return $info_federacao;

        } catch (\Throwable $th) {
            LogErroService::registrar([
                'message' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile()
            ]);
            throw $th;
        }
    }


}
