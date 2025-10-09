<?php

/**
 * Helper function untuk mengecek hari libur nasional Indonesia
 * HANYA untuk hari libur nasional (tidak termasuk hari raya daerah)
 * 
 * File: app/Helpers/HolidayHelper.php
 */

if (!function_exists('getHolidaysIndonesia')) {
    /**
     * Ambil data hari libur nasional Indonesia untuk tahun tertentu
     * FILTER: Hanya hari libur nasional (Jakarta), tanpa hari raya daerah
     * 
     * @param int $year Tahun yang ingin dicek
     * @return array Array berisi tanggal libur dalam format Y-m-d
     */
    function getHolidaysIndonesia($year)
    {
        try {
            // Cek cache terlebih dahulu untuk performa
            $cacheKey = "holidays_indonesia_national_{$year}";
            $cached = cache()->get($cacheKey);

            if ($cached !== null) {
                return $cached;
            }

            // API hari libur Indonesia
            $apiUrl = "https://api-harilibur.vercel.app/api?year={$year}";

            // Gunakan cURL untuk fetch data
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200 || !$response) {
                \Log::warning("Failed to fetch holidays for year {$year}");
                return [];
            }

            $data = json_decode($response, true);

            if (!$data || !is_array($data)) {
                return [];
            }

            // Daftar kata kunci untuk hari raya DAERAH yang harus di-EXCLUDE
            $regionalKeywords = [
                'galungan',
                'kuningan',
                'saraswati',
                'pagerwesi',
                'nyepi', // Tetap nasional tapi mungkin ingin di-exclude
                'siwaratri',
                'tumpek',
                'banyu pinaruh',
                'purnama',
                'tilem',
            ];

            // Extract tanggal libur NASIONAL saja (exclude regional)
            $holidays = [];
            foreach ($data as $holiday) {
                if (!isset($holiday['holiday_date']) || !isset($holiday['holiday_name'])) {
                    continue;
                }

                $holidayName = strtolower($holiday['holiday_name']);

                // Cek apakah mengandung kata kunci regional
                $isRegional = false;
                foreach ($regionalKeywords as $keyword) {
                    if (stripos($holidayName, $keyword) !== false) {
                        $isRegional = true;
                        \Log::info("Excluding regional holiday: {$holiday['holiday_name']} on {$holiday['holiday_date']}");
                        break;
                    }
                }

                // Hanya tambahkan jika BUKAN hari raya daerah
                if (!$isRegional) {
                    $holidays[] = $holiday['holiday_date'];
                }
            }

            \Log::info("National holidays for {$year}: " . count($holidays) . " days");
            \Log::debug("Holiday dates: " . json_encode($holidays));

            // Cache hasil selama 30 hari
            cache()->put($cacheKey, $holidays, now()->addDays(30));

            return $holidays;
        } catch (\Exception $e) {
            \Log::error("Error fetching holidays: " . $e->getMessage());
            return [];
        }
    }
}

if (!function_exists('isHoliday')) {
    /**
     * Cek apakah tanggal tertentu adalah hari libur nasional
     * 
     * @param string|Carbon $date Tanggal yang ingin dicek
     * @return bool True jika hari libur, false jika bukan
     */
    function isHoliday($date)
    {
        try {
            // Convert ke Carbon instance jika string
            if (is_string($date)) {
                $date = \Carbon\Carbon::parse($date);
            }

            // Cek weekend (Sabtu & Minggu)
            if ($date->isWeekend()) {
                return true;
            }

            // Ambil data hari libur NASIONAL untuk tahun tersebut
            $year = $date->year;
            $holidays = getHolidaysIndonesia($year);

            // Format tanggal untuk perbandingan
            $dateStr = $date->format('Y-m-d');

            // Cek apakah tanggal ada di list hari libur
            return in_array($dateStr, $holidays);
        } catch (\Exception $e) {
            \Log::error("Error checking holiday: " . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('getHolidayName')) {
    /**
     * Ambil nama hari libur untuk tanggal tertentu
     * 
     * @param string|Carbon $date Tanggal yang ingin dicek
     * @return string|null Nama hari libur atau null jika bukan hari libur
     */
    function getHolidayName($date)
    {
        try {
            if (is_string($date)) {
                $date = \Carbon\Carbon::parse($date);
            }

            // Cek weekend
            if ($date->isWeekend()) {
                return $date->isSaturday() ? 'Sabtu' : 'Minggu';
            }

            $year = $date->year;
            $dateStr = $date->format('Y-m-d');

            // Ambil data lengkap dari API
            $cacheKey = "holidays_indonesia_national_{$year}_full";
            $cached = cache()->get($cacheKey);

            if ($cached === null) {
                $apiUrl = "https://api-harilibur.vercel.app/api?year={$year}";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $apiUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                $response = curl_exec($ch);
                curl_close($ch);

                if (!$response) {
                    return null;
                }

                $data = json_decode($response, true);

                // Filter hanya hari libur nasional
                $regionalKeywords = [
                    'galungan',
                    'kuningan',
                    'saraswati',
                    'pagerwesi',
                    'nyepi',
                    'siwaratri',
                    'tumpek',
                    'banyu pinaruh',
                    'purnama',
                    'tilem',
                ];

                $filtered = [];
                foreach ($data as $holiday) {
                    $holidayName = strtolower($holiday['holiday_name']);
                    $isRegional = false;

                    foreach ($regionalKeywords as $keyword) {
                        if (stripos($holidayName, $keyword) !== false) {
                            $isRegional = true;
                            break;
                        }
                    }

                    if (!$isRegional) {
                        $filtered[] = $holiday;
                    }
                }

                $cached = $filtered;
                cache()->put($cacheKey, $cached, now()->addDays(30));
            }

            // Cari nama hari libur
            foreach ($cached as $holiday) {
                if ($holiday['holiday_date'] === $dateStr) {
                    return $holiday['holiday_name'];
                }
            }

            return null;
        } catch (\Exception $e) {
            \Log::error("Error getting holiday name: " . $e->getMessage());
            return null;
        }
    }
}

if (!function_exists('getWorkingDaysInMonth')) {
    /**
     * Hitung jumlah hari kerja dalam bulan tertentu
     * 
     * @param int $month Bulan (1-12)
     * @param int $year Tahun
     * @return int Jumlah hari kerja
     */
    function getWorkingDaysInMonth($month, $year)
    {
        $startDate = \Carbon\Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        $workingDays = 0;

        while ($startDate->lte($endDate)) {
            if (!isHoliday($startDate)) {
                $workingDays++;
            }
            $startDate->addDay();
        }

        return $workingDays;
    }
}

if (!function_exists('getHolidaysInMonth')) {
    /**
     * Ambil semua hari libur NASIONAL dalam bulan tertentu
     * 
     * @param int $month Bulan (1-12)
     * @param int $year Tahun
     * @return array Array berisi data hari libur [date, name, is_weekend]
     */
    function getHolidaysInMonth($month, $year)
    {
        $startDate = \Carbon\Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        $holidays = [];

        while ($startDate->lte($endDate)) {
            if (isHoliday($startDate)) {
                $holidays[] = [
                    'date' => $startDate->format('Y-m-d'),
                    'name' => getHolidayName($startDate),
                    'is_weekend' => $startDate->isWeekend(),
                    'day_name' => $startDate->locale('id')->isoFormat('dddd')
                ];
            }
            $startDate->addDay();
        }

        return $holidays;
    }
}

if (!function_exists('isWorkingDay')) {
    /**
     * Cek apakah tanggal tertentu adalah hari kerja
     * (kebalikan dari isHoliday)
     * 
     * @param string|Carbon $date Tanggal yang ingin dicek
     * @return bool True jika hari kerja, false jika libur
     */
    function isWorkingDay($date)
    {
        return !isHoliday($date);
    }
}

if (!function_exists('getAllHolidaysData')) {
    /**
     * Debug function: Ambil SEMUA data hari libur (termasuk regional) untuk analisis
     * 
     * @param int $year Tahun
     * @return array Array berisi semua data hari libur
     */
    function getAllHolidaysData($year)
    {
        try {
            $apiUrl = "https://api-harilibur.vercel.app/api?year={$year}";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);
            curl_close($ch);

            if (!$response) {
                return [];
            }

            return json_decode($response, true) ?: [];
        } catch (\Exception $e) {
            \Log::error("Error getting all holidays data: " . $e->getMessage());
            return [];
        }
    }
}
