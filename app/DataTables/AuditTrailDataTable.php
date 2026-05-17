<?php

namespace App\DataTables;

use App\Models\AuditTrail\AuditTrailModel;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AuditTrailDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder<AuditTrailModel>  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $actionBtn = '<button type="button" onclick="showDetail(\''.Crypt::encrypt($row->id).'\')" class="btn btn-soft-primary btn-sm"><i class="ti ti-eye"></i></button>';

                return $actionBtn;
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('d-m-Y H:i:s') : '';
            })
            ->editColumn('user.name', function ($row) {
                return $row->user->name ?? 'Sistem/Tidak Diketahui';
            })
            ->editColumn('payload', function ($row) {
                return $row->payload;
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<AuditTrailModel>
     */
    public function query(AuditTrailModel $model): QueryBuilder
    {
        $tableName = $model->getTable();
        $query = $model->with('user')->select($tableName.'.*')->orderBy($tableName.'.created_at', 'desc');

        if (Auth::user()->role === 'User') {
            $query->where('user_id', Auth::id());
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('audittrail-table')
            ->columns($this->getColumns())
            ->ajax(route('audit-trail.index'))
            ->orderBy(1)
            ->selectStyleSingle()
            ->processing(true)
            ->serverSide();
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')
                ->title('#')
                ->orderable(false)
                ->searchable(false)
                ->width(30)
                ->addClass('text-center'),
            Column::make('user.name')->title('Pengguna'),
            Column::make('payload'),
            Column::make('ip_address'),
            Column::make('created_at'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center')
                ->titleAttr(['class' => 'text-center']),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'AuditTrail_'.date('YmdHis');
    }
}
