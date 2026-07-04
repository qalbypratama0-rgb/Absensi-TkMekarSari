<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QrController extends Controller
{
    public function generateQrIndex()
    {
        $siswa = Siswa::with('kelas')->orderBy('nama_siswa')->get();
        
        $options = new QROptions([
            'outputType' => 'png',
            'eccLevel'   => QRCode::ECC_L,
            'scale'      => 4,
        ]);
        
        $siswa->each(function ($item) use ($options) {
            $item->qr_base64 = (new QRCode($options))->render($item->nis);
        });

        return view('generate-qr.index', compact('siswa'));
    }

    public function downloadQr(Siswa $siswa)
    {
        $options = new QROptions([
            'outputType'   => 'png',
            'outputBase64' => false,
            'eccLevel'     => QRCode::ECC_L,
            'scale'        => 10,
        ]);

        $qrCodeBinary = (new QRCode($options))->render($siswa->nis);

        return response($qrCodeBinary)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="qr-' . $siswa->nis . '.png"');
    }

    public function cetakLanyardIndex(Request $request)
    {
        $ids = $request->input('ids');
        if ($ids) {
            $siswa = Siswa::with('kelas')->whereIn('id', $ids)->get();
        } else {
            $siswa = Siswa::with('kelas')->get();
        }

        $options = new QROptions([
            'outputType' => 'png',
            'eccLevel'   => QRCode::ECC_L,
            'scale'      => 5,
        ]);

        $siswa->each(function ($item) use ($options) {
            $item->qr_base64 = (new QRCode($options))->render($item->nis);
        });

        return view('cetak-lanyard.index', compact('siswa'));
    }
}
