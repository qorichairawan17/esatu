@extends('landing.index')
@section('titlte', $title)
@section('content')
    <section class="section pt-5 mt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 p-0">
                    <div class="card map border-0">
                        <div class="card-body p-0">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.3147038051766!2d99.5320394744731!3d0.9103651628318868!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x302beab78e1958a9%3A0xc244745d6467e79d!2sPengadilan%20Negeri%20Mandailing%20Natal%20Kelas%20II!5e0!3m2!1sid!2sid!4v1779076587109!5m2!1sid!2sid"
                                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mt-100 mt-60">
            <div class="row align-items-center">

                <div class="col-lg-12 col-md-12 order-1 order-md-2">
                    <div class="title-heading ms-lg-4">
                        <h4 class="mb-4">Kontak Kami</h4>
                        <p class="text-muted">
                            Jika ada pertanyaan, silahkan hubungi kami melalui kontak dibawah ini
                        </p>
                        <div class="d-flex contact-detail align-items-center mt-3">
                            <div class="icon">
                                <i data-feather="mail" class="fea icon-m-md text-dark me-3"></i>
                            </div>
                            <div class="flex-1 content">
                                <h6 class="title fw-bold mb-0">Email</h6>
                                <a href="mailto:{{ $infoApp->email }}" class="text-success">{{ $infoApp->email }}</a>
                            </div>
                        </div>

                        <div class="d-flex contact-detail align-items-center mt-3">
                            <div class="icon">
                                <i data-feather="phone" class="fea icon-m-md text-dark me-3"></i>
                            </div>
                            <div class="flex-1 content">
                                <h6 class="title fw-bold mb-0">Kontak</h6>
                                <a href="tel:{{ $infoApp->kontak }}" class="text-success">{{ $infoApp->kontak }}</a>
                            </div>
                        </div>

                        <div class="d-flex contact-detail align-items-center mt-3">
                            <div class="icon">
                                <i data-feather="map-pin" class="fea icon-m-md text-dark me-3"></i>
                            </div>
                            <div class="flex-1 content">
                                <h6 class="title fw-bold mb-0">Alamat</h6>
                                <span class="text-success">
                                    {{ $infoApp->alamat . ' ' . $infoApp->kabupaten . ' ' . $infoApp->provinsi . ' ' . $infoApp->kode_pos }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
