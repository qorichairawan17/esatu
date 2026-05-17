<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AdvokatDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder<User>  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('expand', function ($row) {
                return '<button type="button" class="dt-expand-btn" title="Detail"><span class="arrow-icon">▼</span></button>';
            })
            ->addColumn('action', function ($row) {
                $detailUrl = route('advokat.detail', ['id' => Crypt::encrypt($row->id)]);
                $editUrl = route('advokat.form', ['param' => 'edit', 'id' => Crypt::encrypt($row->id)]);
                $deleteUrl = route('advokat.destroy', ['id' => Crypt::encrypt($row->id)]);
                $actionBtn = '';
                $actionBtn .= '<a href="'.$detailUrl.'" class="btn btn-soft-primary btn-sm" title="Detail"><i class="ti ti-eye"></i></a>';
                $actionBtn .= '<a href="'.$editUrl.'" class="btn btn-soft-warning btn-sm" title="Edit"><i class="ti ti-edit"></i></a>';
                if (Auth::id() === $row->id) {
                    $actionBtn .= '<button type="button" disabled class="btn btn-danger btn-sm" title="Hapus"><i class="ti ti-trash"></i></button>';
                } else {
                    $actionBtn .= '<button type="button" onclick="deleteData(\''.$deleteUrl.'\')" class="btn btn-danger btn-sm" title="Hapus"><i class="ti ti-trash"></i></button>';
                }

                return '<div class="dt-action-group">'.$actionBtn.'</div>';
            })
            ->editColumn('block', function ($row) {
                return $row->block == 1 ? '<span class="badge bg-success">Ya</span>' : '<span class="badge bg-danger">Tidak</span>';
            })
            ->addColumn('surat_kuasa_count', function ($row) {
                return '<span class="badge bg-soft-primary text-primary">'.$row->surat_kuasa_count.'</span>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('d-m-Y H:i:s') : '';
            })
            ->editColumn('updated_at', function ($row) {
                return $row->updated_at ? $row->updated_at->format('d-m-Y H:i:s') : '';
            })
            ->rawColumns(['expand', 'block', 'surat_kuasa_count', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<User>
     */
    public function query(User $model): QueryBuilder
    {
        $query = $model->where('role', 'User')->withCount('suratKuasa')->orderBy('updated_at', 'desc')->newQuery();

        // Menerapkan filter status dari request
        if ($this->request->filled('status') && $this->request->get('status') !== '') {
            $query->where('block', $this->request->get('status'));
        }

        // Menerapkan filter profile status dari request
        if ($this->request->filled('profile_status') && $this->request->get('profile_status') !== '') {
            $query->where('profile_status', $this->request->get('profile_status'));
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('advokat-table')
            ->columns($this->getColumns())
            ->ajax($this->getAjaxOptions())
            ->orderBy(2)
            ->selectStyleSingle()
            ->processing(true)
            ->serverSide();
    }

    /**
     * Get the ajax options for DataTables.
     */
    protected function getAjaxOptions(): array
    {
        return [
            'url' => route('advokat.index'),
            'type' => 'GET',
            'data' => 'function(d) {
                d.status = $("#statusFilter").val();
                d.profile_status = $("#profileStatusFilter").val();
            }',
        ];
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('expand')
                ->title('')
                ->orderable(false)
                ->searchable(false)
                ->width(36)
                ->addClass('text-center align-middle'),
            Column::make('DT_RowIndex')
                ->title('#')
                ->orderable(false)
                ->searchable(false)
                ->width(36)
                ->addClass('text-center align-middle'),
            Column::make('name')
                ->title('Nama')
                ->addClass('align-middle'),
            Column::make('email')
                ->title('Email')
                ->addClass('align-middle'),
            Column::make('block')
                ->title('Block')
                ->addClass('align-middle text-center'),
            Column::computed('surat_kuasa_count')
                ->title('Surat Kuasa')
                ->addClass('align-middle text-center')
                ->orderable(false)
                ->searchable(false)
                ->width(100),
            // Hidden columns – data sent to client but hidden via JS, shown in child row
            Column::make('created_at')
                ->title('Dibuat')
                ->addClass('align-middle'),
            Column::make('updated_at')
                ->title('Diperbarui')
                ->addClass('align-middle'),
            Column::computed('action')
                ->title('Aksi')
                ->addClass('align-middle text-center')
                ->width(120)
                ->exportable(false)
                ->printable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Advokat_'.date('YmdHis');
    }
}
