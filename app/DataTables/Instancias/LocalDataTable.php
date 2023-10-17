<?php

namespace App\DataTables\Instancias;

use App\Helpers\FormHelper;
use App\Models\AcessoExterno;
use App\Models\Local;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LocalDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($sql) {
                return view('includes.actions', [
                    'route' => 'dashboard.locais',
                    'id' => $sql->id,
                    'show' => false
                ]);
            })
            ->editColumn('status', function ($sql) {
                return FormHelper::statusFormatado($sql->status, 'Ativo', 'Inativo');
            })
            ->editColumn('regiao_id', function ($sql) {
                return $sql->regiao->nome;
            })
            ->editColumn('estado_id', function ($sql) {
                return $sql->estado->nome;
            })
            ->addColumn('estatistica', function ($sql) {

                $relatorio = $sql->relatorios()->orderBy('created_at', 'desc')->get()->first();
                if (!$relatorio) {
                    return 'Sem Relatório';
                }


                return $relatorio->ano_referencia;
            })
            ->editColumn('sinodal_id', function ($sql) {
                return $sql->sinodal->sigla;
            })
            ->editColumn('federacao_id', function ($sql) {
                return $sql->federacao->sigla;
            })
            ->rawColumns(['status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\AcessoExterno $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Local $model)
    {
        if (auth()->user()->admin == true) {
            return $model->newQuery();
        }
        return $model->newQuery()->minhaFederacao();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('uph-local-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons(
                        Button::make('create')->text('<i class="fas fa-plus"></i> Nova UPH')
                    )
                    ->parameters([
                        "language" => [
                            "url" => "/vendor/datatables/portugues.json"
                        ]
                    ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center')
                  ->title('Ação'),
            Column::make('nome')->title('Nome'),
            Column::make('estatistica')->title('Estatística')->orderable(false),
            Column::make('federacao_id')->title('Federação'),
            Column::make('sinodal_id')->title('Sinodal'),
            Column::make('estado_id')->title('Estado'),
            Column::make('status')->title('Status'),
            Column::make('regiao_id')->title('Região'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'UPH_LOCAL_' . date('YmdHis');
    }
}
