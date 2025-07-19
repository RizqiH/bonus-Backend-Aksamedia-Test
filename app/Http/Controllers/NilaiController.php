<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    /**
     * Endpoint untuk mendapatkan nilai RT (Requirement Test)
     * Menggunakan materi_uji_id = 7, tanpa pelajaran khusus
     */
    public function getNilaiRT()
    {
        $query = "
            SELECT
                nama,
                nisn,
                MAX(CASE WHEN nama_pelajaran = 'REALISTIC' THEN skor ELSE 0 END) as realistic,
                MAX(CASE WHEN nama_pelajaran = 'INVESTIGATIVE' THEN skor ELSE 0 END) as investigative,
                MAX(CASE WHEN nama_pelajaran = 'ARTISTIC' THEN skor ELSE 0 END) as artistic,
                MAX(CASE WHEN nama_pelajaran = 'SOCIAL' THEN skor ELSE 0 END) as social,
                MAX(CASE WHEN nama_pelajaran = 'ENTERPRISING' THEN skor ELSE 0 END) as enterprising,
                MAX(CASE WHEN nama_pelajaran = 'CONVENTIONAL' THEN skor ELSE 0 END) as conventional
            FROM nilai
            WHERE materi_uji_id = 7
            AND nama_pelajaran NOT IN ('Pelajaran Khusus')
            AND nama_pelajaran IN ('REALISTIC', 'INVESTIGATIVE', 'ARTISTIC', 'SOCIAL', 'ENTERPRISING', 'CONVENTIONAL')
            GROUP BY nama, nisn
            ORDER BY nama ASC
        ";

        $hasilQuery = DB::select($query);

        // Mengorganisasi data sesuai format yang diinginkan
        $dataRT = [];
        foreach ($hasilQuery as $row) {
            $dataRT[] = [
                'nama' => $row->nama,
                'nisn' => $row->nisn,
                'nilaiRT' => [
                    'realistic' => (int)$row->realistic,
                    'investigative' => (int)$row->investigative,
                    'artistic' => (int)$row->artistic,
                    'social' => (int)$row->social,
                    'enterprising' => (int)$row->enterprising,
                    'conventional' => (int)$row->conventional
                ]
            ];
        }

        return response()->json($dataRT);
    }

    /**
     * Endpoint untuk mendapatkan nilai ST (Scholastic Test)
     * Menggunakan materi_uji_id = 4 dengan perhitungan khusus
     */
    public function getNilaiST()
    {
        $query = "
            SELECT
                nama,
                nisn,
                MAX(CASE WHEN pelajaran_id = 44 THEN skor * 41.67 ELSE 0 END) as verbal,
                MAX(CASE WHEN pelajaran_id = 45 THEN skor * 29.67 ELSE 0 END) as kuantitatif,
                MAX(CASE WHEN pelajaran_id = 46 THEN skor * 100 ELSE 0 END) as penalaran,
                MAX(CASE WHEN pelajaran_id = 47 THEN skor * 23.81 ELSE 0 END) as figural,
                (
                    MAX(CASE WHEN pelajaran_id = 44 THEN skor * 41.67 ELSE 0 END) +
                    MAX(CASE WHEN pelajaran_id = 45 THEN skor * 29.67 ELSE 0 END) +
                    MAX(CASE WHEN pelajaran_id = 46 THEN skor * 100 ELSE 0 END) +
                    MAX(CASE WHEN pelajaran_id = 47 THEN skor * 23.81 ELSE 0 END)
                ) as total_nilai
            FROM nilai
            WHERE materi_uji_id = 4
            AND pelajaran_id IN (44, 45, 46, 47)
            GROUP BY nama, nisn
            ORDER BY total_nilai DESC
        ";

        $hasilQuery = DB::select($query);

        // Mengorganisasi data sesuai format yang diinginkan
        $dataST = [];
        foreach ($hasilQuery as $row) {
            $dataST[] = [
                'nama' => $row->nama,
                'nisn' => $row->nisn,
                'listNilai' => [
                    'verbal' => round($row->verbal, 2),
                    'kuantitatif' => round($row->kuantitatif, 2),
                    'penalaran' => round($row->penalaran, 2),
                    'figural' => round($row->figural, 2)
                ],
                'total' => round($row->total_nilai, 2)
            ];
        }

        return response()->json($dataST);
    }
}
