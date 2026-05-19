@include('mail.layouts.header')

<body>
    <div style="margin: 0; padding: 50px 16px; background-color: #f8fbf9;">
        <table cellpadding="0" cellspacing="0"
            style="font-family: Nunito, sans-serif; font-size: 15px; font-weight: 400; color: #637083; max-width: 600px; border: 1px solid rgba(19, 108, 52, 0.12); margin: 0 auto; border-radius: 8px; overflow: hidden; background-color: #ffffff; box-shadow: 0 18px 40px rgba(32, 41, 66, 0.08);">
            <thead>
                <tr style="background-color: #136c34; padding: 3px 0; border: none; line-height: 68px; text-align: center; color: #ffffff; font-size: 16px; letter-spacing: 0;">
                    <th scope="col">Pendaftaran Surat Kuasa Disetujui</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td style="padding: 48px 24px 0; color: #202942; font-size: 18px; font-weight: 600;">
                        Hallo, {{ $user->name ?? 'Pengguna' }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 15px 24px 15px;text-align: justify;">
                        Selamat! Pendaftaran surat kuasa Kamu dengan ID Pendaftaran:
                        <b>{{ $suratKuasa->id_daftar }}</b> telah disetujui dan diregistrasi dengan nomor:
                        <b>{{ $suratKuasa->register->nomor_surat_kuasa ?? '' }}</b>.
                    </td>
                </tr>
                <tr>
                    <td style="padding: 15px 24px 15px;text-align: justify;">
                        Kamu dapat mengunduh barcode pendaftaran elektronik yang terlampir pada email ini. Silakan
                        gunakan barcode tersebut untuk keperluan lebih lanjut.
                    </td>
                </tr>
                <tr>
                    <td style="padding: 15px 24px 15px;">
                        {{ config('app.name') }} <br> Dikembangkan Oleh {{ config('app.author') }} <br>
                        <span style="color: #637083; font-size: 12px;">Email ini dikirim otomatis oleh sistem, mohon untuk
                            tidak membalas email ini.</span>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 16px 8px; background-color: #f2fbf6; border-top: 1px solid rgba(19, 108, 52, 0.12); color: #637083; text-align: center;">
                        © 2026 - {{ date('Y') }} {{ config('app.name') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
