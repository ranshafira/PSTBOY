<!--         use App\Models\Pelayanan;
        use App\Models\SurveyInternal;
        use Illuminate\Http\Request;
        use Illuminate\Support\Str;

        class SurveyInternalController extends Controller
        {
            public function show($id)
            {
                // Ambil data pelayanan berdasarkan id
                $pelayanan = Pelayanan::findOrFail($id);

                if (SurveyInternal::where('pelayanan_id', $pelayanan->id)->exists()) {
                    return redirect()->route('pelayanan.terimakasih', $pelayanan->id)
                        ->with('info', 'Anda sudah mengisi Survei Kepuasan Pelayanan.');
                }

                return view('survei.internal.show', compact('pelayanan'));
            }

            public function store(Request $request, $id)
            {
                $pelayanan = Pelayanan::findOrFail($id);


                $validated = $request->validate([
                    'skor_keseluruhan' => 'required|integer|min:1|max:5',
                    'skor_petugas' => 'required|integer|min:1|max:5',
                    'saran' => 'nullable|string|max:1000',
                ]);
                if ($validated['skor_keseluruhan'] < 4 || $validated['skor_petugas'] < 4) {
                    $request->validate(['saran' => 'required|string|max:1000']);
                }
                if (SurveyInternal::where('pelayanan_id', $pelayanan->id)->exists()) {
                    return redirect()->route('pelayanan.terimakasih', $pelayanan->id)
                        ->with('info', 'Survei sudah pernah diisi.');
                }
                SurveyInternal::create([
                    'pelayanan_id' => $pelayanan->id,
                    'skor_keseluruhan' => $validated['skor_keseluruhan'],
                    'skor_petugas' => $validated['skor_petugas'],
                    'saran' => $validated['saran'] ?? null,
                ]);
                return redirect()->route('pelayanan.terimakasih', $pelayanan->id)
                    ->with('success', 'Terima kasih telah mengisi survei kepuasan!');
            }
            public function showPublic($kode_unik)
            {
                $pelayanan = Pelayanan::where('kode_unik', $kode_unik)->firstOrFail();

                // Kalau survei sudah pernah diisi
                if ($pelayanan->surveyInternal) {
                    return redirect()->route('pelayanan.terimakasih', $pelayanan->id);
                }

                return view('survei.public', compact('pelayanan'));
            }

            public function storePublic(Request $request, $kode_unik)
            {
                $pelayanan = Pelayanan::where('kode_unik', $kode_unik)->firstOrFail();

                $request->validate([
                    'skor_keseluruhan' => 'required|integer|min:1|max:5',
                    'skor_petugas' => 'required|integer|min:1|max:5',
                    'saran' => 'nullable|string',
                ]);

                SurveyInternal::create([
                    'pelayanan_id' => $pelayanan->id,
                    'skor_keseluruhan' => $request->skor_keseluruhan,
                    'skor_petugas' => $request->skor_petugas,
                    'saran' => $request->saran,
                ]);

                return redirect()->route('pelayanan.terimakasih', $pelayanan->id)
                    ->with('success', 'Terima kasih telah mengisi survei!');
            }
        }