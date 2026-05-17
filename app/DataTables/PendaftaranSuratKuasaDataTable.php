<?php

namespace App\DataTables;

use App\Enum\RoleEnum;
use App\Enum\StatusSuratKuasaEnum;
use App\Models\Suratkuasa\PendaftaranSuratKuasaModel;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PendaftaranSuratKuasaDataTable extends DataTable
{
    private function buildActionButtons(PendaftaranSuratKuasaModel $row): string
    {
        $user = Auth::user();
        $buttons = [
            $this->detailButton($row),
        ];

        if ($this->canEdit($row, $user->role)) {
            $buttons[] = $this->editButton($row);
        }

        if ($row->canBeDeletedBy($user)) {
            $buttons[] = $this->deleteButton($row);
        }

        return '<div class="dt-action-group">'.implode('', $buttons).'</div>';
    }

    private function canEdit(PendaftaranSuratKuasaModel $row, string $role): bool
    {
        return $role !== RoleEnum::User->value
            || $row->status === StatusSuratKuasaEnum::Ditolak->value;
    }

    private function detailButton(PendaftaranSuratKuasaModel $row): string
    {
        $detailUrl = route('surat-kuasa.detail', ['id' => Crypt::encrypt($row->id)]);

        return '<a href="'.$detailUrl.'" class="btn btn-soft-primary btn-sm" title="Detail"><i class="ti ti-eye"></i></a>';
    }

    private function editButton(PendaftaranSuratKuasaModel $row): string
    {
        $editUrl = route('surat-kuasa.form', [
            'param' => 'edit',
            'klasifikasi' => $row->klasifikasi,
            'id' => Crypt::encrypt($row->id),
        ]);

        return '<a href="'.$editUrl.'" class="btn btn-soft-warning btn-sm" title="Edit"><i class="ti ti-edit"></i></a>';
    }

    private function deleteButton(PendaftaranSuratKuasaModel $row): string
    {
        $deleteUrl = route('surat-kuasa.destroy', ['id' => Crypt::encrypt($row->id)]);

        return '<a href="javascript:void(0);" onclick="deleteData(\''.$deleteUrl.'\')" class="btn btn-danger btn-sm" title="Hapus"><i class="ti ti-trash"></i></a>';
    }

    /**
     * Censors a string if the user role is not 'User'.
     *
     * @param  string|null  $data  The data to be censored.
     * @param  string  $role  The user's role.
     * @param  int  $visibleChars  The number of characters to keep visible.
     * @return string|null The censored or original data.
     */
    private function sensorData(?string $data, string $role, int $visibleChars = 4): ?string
    {
        if (is_null($data)) {
            return null;
        }

        return ($role !== RoleEnum::User->value) ? substr($data, 0, $visibleChars).str_repeat('*', max(0, strlen($data) - $visibleChars)) : $data;
    }

    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder<PendaftaranSuratKuasaModel>  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('expand', function ($row) {
                return '<button type="button" class="dt-expand-btn" title="Detail"><span class="arrow-icon">▼</span></button>';
            })
            ->addColumn('action', fn (PendaftaranSuratKuasaModel $row) => $this->buildActionButtons($row))
            ->editColumn('id_daftar', function ($row) {
                $nomorSurat = ($row->register && $row->register->nomor_surat_kuasa) ? '<br><small class="text-muted">No: '.$row->register->nomor_surat_kuasa.'</small>' : '';

                return '<a href="'.route('surat-kuasa.detail', ['id' => Crypt::encrypt($row->id)]).'" title="Detail Pendaftaran" style="font-weight:600;" class="text-dark">'.$row->id_daftar.'</a>'.$nomorSurat;
            })
            ->editColumn('updated_at', function ($row) {
                return $row->updated_at ? $row->updated_at->format('d-m-Y H:i:s') : '';
            })
            ->editColumn('jenis_surat', function ($row) {
                return $row->klasifikasi.' - Perkara ('.$row->jenis_surat.')';
            })
            ->editColumn('status', function ($row) {
                $badgeClass = match ($row->status) {
                    StatusSuratKuasaEnum::Ditolak->value => 'bg-danger',
                    StatusSuratKuasaEnum::Disetujui->value => 'bg-success',
                    default => 'bg-warning',
                };

                return '<span class="badge '.$badgeClass.'">'.$row->status.'</span>';
            })
            ->rawColumns(['expand', 'action', 'id_daftar', 'status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<PendaftaranSuratKuasaModel>
     */
    public function query(PendaftaranSuratKuasaModel $model): QueryBuilder
    {
        $query = $model->newQuery()->with(['register', 'pihak']);

        $roleBased = Auth::user()->role;
        if ($roleBased === RoleEnum::User->value) {
            $query->where('user_id', Auth::user()->id);
        }

        if ($this->request->filled('klasifikasi') && $this->request->get('klasifikasi') !== '') {
            $query->where('klasifikasi', $this->request->get('klasifikasi'));
        }

        if ($this->request->filled('tahapan') && $this->request->get('tahapan') !== '') {
            $query->where('tahapan', $this->request->get('tahapan'));
        }

        if ($this->request->filled('status') && $this->request->get('status') !== '') {
            $query->where('status', $this->request->get('status'));
        }

        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('pendaftaransuratkuasa-table')
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
            'url' => route('surat-kuasa.index'),
            'type' => 'GET',
            'data' => 'function(d) {
                d.klasifikasi = $("#klasifikasiFilter").val();
                d.tahapan = $("#tahapanFilter").val();
                d.status = $("#statusFilter").val();
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
            Column::make('id_daftar')
                ->title('ID Daftar')
                ->addClass('align-middle'),
            Column::make('tanggal_daftar')
                ->title('Tgl Daftar')
                ->addClass('align-middle'),
            Column::make('pemohon')
                ->title('Pemohon')
                ->addClass('align-middle'),
            // Hidden columns – data sent to client but hidden via JS on desktop, shown in child row
            Column::make('perihal')
                ->title('Perihal')
                ->addClass('align-middle'),
            Column::make('jenis_surat')
                ->title('Jenis Surat')
                ->addClass('align-middle'),
            // Active columns on desktop, hidden on mobile
            Column::make('tahapan')
                ->title('Tahapan')
                ->addClass('align-middle text-center'),
            Column::make('status')
                ->title('Status')
                ->addClass('align-middle text-center'),
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
        return 'PendaftaranSuratKuasa_'.date('YmdHis');
    }
}
