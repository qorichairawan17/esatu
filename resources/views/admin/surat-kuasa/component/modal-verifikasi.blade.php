<!-- Modal Setujui -->
<div class="modal fade" id="setujui-surat-kuasa" tabindex="-1" aria-labelledby="setujui-surat-kuasa-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded shadow border-0">
            <form id="form-approve">
                @csrf
                <input type="hidden" name="id" value="{{ Crypt::encrypt($suratKuasa->id) }}">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="setujui-surat-kuasa-title">Setujui Surat Kuasa ID :
                        {{ $suratKuasa->id_daftar }}</h5>
                    <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i class="uil uil-times fs-4 text-dark"></i></button>
                </div>
                <div class="modal-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="manualNomorSwitch">
                        <label class="form-check-label" for="manualNomorSwitch">Gunakan Nomor Manual</label>
                    </div>
                    <div class="form-group mb-3">
                        <label for="nomor_surat_kuasa">
                            Nomor Surat Kuasa <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="nomor_surat_kuasa" name="nomor_surat_kuasa" value="{{ $nomorSuratKuasaBaru }}" readonly required>
                        <div class="invalid-feedback" id="nomor_surat_kuasa-error"></div>
                    </div>
                    <div class="form-group">
                        <label for="panitera_id">
                            Pilih Panitera <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="panitera_id" name="panitera_id" required>
                            <option value="" selected disabled>--- Pilih Panitera ---</option>
                            @foreach ($panitera as $row)
                                <option value="{{ $row->id }}">{{ $row->nama }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="panitera_id-error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btn-approve" class="btn btn-success btn-sm">Setujui
                        Pendaftaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tolak -->
<div class="modal fade" id="tolak-surat-kuasa" tabindex="-1" aria-labelledby="tolak-surat-kuasa-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded shadow border-0">
            <form id="form-reject">
                @csrf
                <input type="hidden" name="id" value="{{ Crypt::encrypt($suratKuasa->id) }}">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="tolak-surat-kuasa-title">Tolak Surat Kuasa ID :
                        {{ $suratKuasa->id_daftar }}</h5>
                    <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i class="uil uil-times fs-4 text-dark"></i></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="tahapan">Tolak Tahapan <span class="text-danger">*</span></label>
                        <select class="form-select" id="tahapan" name="tahapan" required>
                            <option value="" selected disabled>--- Pilih Tahapan ---</option>
                            <option value="{{ \App\Enum\TahapanSuratKuasaEnum::PerbaikanData->value }}">
                                Perbaikan Data</option>
                            <option value="{{ \App\Enum\TahapanSuratKuasaEnum::PerbaikanPembayaran->value }}">
                                Perbaikan Pembayaran</option>
                        </select>
                        <div class="invalid-feedback" id="tahapan-error"></div>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Alasan Ditolak <span class="text-danger">*</span></label>
                        <textarea class="form-control" required id="keterangan" name="keterangan" placeholder="Isi alasan penolakan pendaftaran surat kuasa..."></textarea>
                        <div class="invalid-feedback" id="keterangan-error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btn-reject" class="btn btn-danger btn-sm">Tolak
                        Pendaftaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
