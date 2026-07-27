<?php

namespace App\Imports;

use App\Models\Believer;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class BelieversImport implements ToCollection, WithMultipleSheets, WithStartRow, SkipsEmptyRows
{
    public array $errors   = [];
    public int   $imported = 0;
    public int   $skipped  = 0;

    /**
     * Restreint l'import à la seule feuille "Fidèles" du classeur.
     * Toute autre feuille (Instructions, Feuil1, ou toute feuille
     * additionnelle que l'utilisateur aurait laissée dans son fichier)
     * est totalement ignorée.
     */
    public function sheets(): array
    {
        return [
            'Fidèles' => $this,
        ];
    }

    // Commencer à la ligne 4 (ligne 1=titre, 2=note, 3=en-têtes)
    public function startRow(): int
    {
        return 4;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $lineNumber = $index + 4;

            try {
                // Colonnes du template (0-indexé)
                $lastname  = $this->clean($row[0]);
                $firstname = $this->clean($row[1]);

                // Ignorer les lignes sans nom ou prénom
                if (empty($lastname) || empty($firstname)) {
                    $this->skipped++;
                    $this->errors[] = "Ligne {$lineNumber} : NOM ou PRÉNOMS manquant — ligne ignorée.";
                    continue;
                }

                // Ignorer les doublons
                if (Believer::where('lastname', $lastname)->where('firstname', $firstname)->exists()) {
                    $this->skipped++;
                    $this->errors[] = "Ligne {$lineNumber} : {$lastname} {$firstname} existe déjà — ignoré.";
                    continue;
                }

                // ── Infos principales ──
                $gender        = $this->parseGender($row[2] ?? null);
                $birthDate     = $this->parseDate($row[3] ?? null);
                $birthPlace    = $this->clean($row[4] ?? null);
                $nationality   = $this->clean($row[5] ?? null) ?: 'Ivoirienne';
                $cniNumber     = $this->clean($row[6] ?? null);
                $maritalStatus = $this->parseMaritalStatus($row[7] ?? null);
                $nbChildren    = $this->parseInt($row[8] ?? null);

                $believer = Believer::create([
                    'lastname'           => $lastname,
                    'firstname'          => $firstname,
                    'gender'             => $gender,
                    'birth_date'         => $birthDate,
                    'birth_place'        => $birthPlace,
                    'nationality'        => $nationality,
                    'cni_number'         => $cniNumber,
                    'marital_status'     => $maritalStatus,
                    'number_of_children' => $nbChildren ?? 0,
                    'status'             => 'actif',
                    'is_active'          => true,
                ]);

                // ── Adresse ──
                $commune      = $this->clean($row[9] ?? null);
                $quartier     = $this->clean($row[10] ?? null);
                $sousQuartier = $this->clean($row[11] ?? null);
                $phone        = $this->clean($row[12] ?? null);
                $whatsapp     = $this->clean($row[13] ?? null);
                $email        = $this->cleanEmail($row[14] ?? null);

                if ($commune || $phone || $email) {
                    $believer->address()->create([
                        'commune'       => $commune,
                        'quartier'      => $quartier,
                        'sous_quartier' => $sousQuartier,
                        'phone'         => $phone,
                        'whatsapp'      => $whatsapp,
                        'email'         => $email,
                    ]);
                }

                // ── Infos église ──
                $connaissanceEglise = $this->clean($row[15] ?? null);
                $originalChurch     = $this->clean($row[16] ?? null);
                $arrivalYear        = $this->parseInt($row[17] ?? null);
                $conversionDate     = $this->parseDate($row[18] ?? null);
                $conversionPlace    = $this->clean($row[19] ?? null);
                $baptised           = $this->parseBool($row[20] ?? null);
                $baptismYear        = $this->parseInt($row[21] ?? null);
                $baptismPlace       = $this->clean($row[22] ?? null);
                $baptismPastor      = $this->clean($row[23] ?? null);
                $baptismCard        = $this->clean($row[24] ?? null);

                $baptismDate = $baptismYear ? Carbon::createFromDate($baptismYear, 1, 1) : null;

                $believer->churchInformation()->create([
                    'connaissance_eglise'  => $connaissanceEglise,
                    'original_church'      => $originalChurch,
                    'arrival_year'         => $arrivalYear,
                    'conversion_date'      => $conversionDate,
                    'conversion_place'     => $conversionPlace,
                    'baptised'             => $baptised,
                    'baptism_date'         => $baptismDate,
                    'baptism_place'        => $baptismPlace,
                    'baptism_pastor'       => $baptismPastor,
                    'baptism_card_number'  => $baptismCard,
                ]);

                // ── Éducation ──
                $niveau        = $this->clean($row[25] ?? null);
                $diploma       = $this->clean($row[26] ?? null);
                $qualification = $this->clean($row[27] ?? null);

                if ($niveau || $diploma) {
                    $believer->education()->create([
                        'niveau_etude'  => $niveau,
                        'diploma'       => $diploma,
                        'qualification' => $qualification,
                    ]);
                }

                // ── Profession ──
                $profession          = $this->clean($row[28] ?? null);
                $function            = $this->clean($row[29] ?? null);
                $company             = $this->clean($row[30] ?? null);
                $professionalContact = $this->clean($row[31] ?? null);

                if ($profession || $company) {
                    $believer->profession()->create([
                        'profession'           => $profession,
                        'function'             => $function,
                        'company'              => $company,
                        'professional_contact' => $professionalContact,
                    ]);
                }

                // ── Responsabilités ──
                $respOld     = $this->clean($row[32] ?? null);
                $respCurrent = $this->clean($row[33] ?? null);
                $respDesire  = $this->clean($row[34] ?? null);

                $believer->responsibility()->create([
                    'old'     => $respOld,
                    'current' => $respCurrent,
                    'desire'  => $respDesire,
                ]);

                $this->imported++;

            } catch (\Exception $e) {
                $this->errors[] = "Ligne {$lineNumber} : Erreur — " . $e->getMessage();
                $this->skipped++;
            }
        }
    }

    // -------------------------------------------------------
    // Helpers de nettoyage
    // -------------------------------------------------------

    private function clean($value): ?string
    {
        if ($value === null) return null;
        $value = trim((string) $value);
        if (in_array(strtoupper($value), ['NEANT', 'N/A', 'NA', '-', ''])) return null;
        return $value;
    }

    private function cleanEmail($value): ?string
    {
        $val = $this->clean($value);
        if (!$val) return null;
        return filter_var($val, FILTER_VALIDATE_EMAIL) ? strtolower($val) : null;
    }

    private function parseGender($value): ?string
    {
        $v = mb_strtoupper(trim((string) $value));
        return match($v) {
            'M', 'MASCULIN', 'HOMME' => 'M',
            'F', 'FEMININ', 'FEMME'  => 'F',
            default                   => null,
        };
    }

    private function parseMaritalStatus($value): ?string
    {
        $v = mb_strtolower(trim((string) $value));

        return match(true) {
            str_contains($v, 'celibat') || str_contains($v, 'célibat') => 'Célibataire',
            str_contains($v, 'mari')                                   => 'Marié(e)',
            str_contains($v, 'veuf') || str_contains($v, 'veuve')      => 'Veuf(ve)',
            str_contains($v, 'divorc')                                 => 'Divorcé',
            default                                                     => null,
        };
    }

    private function parseBool($value): bool
    {
        $v = strtoupper(trim((string) $value));
        return in_array($v, ['OUI', 'YES', '1', 'TRUE']);
    }

    private function parseInt($value): ?int
    {
        $cleaned = $this->clean($value);
        if (!$cleaned) return null;
        if (preg_match('/\b(19|20)\d{2}\b/', $cleaned, $matches)) {
            return (int) $matches[0];
        }
        return is_numeric($cleaned) ? (int) $cleaned : null;
    }

    private function parseDate($value): ?Carbon
    {
        if ($value === null) return null;

        // Cellule déjà reconnue comme date/heure par Laravel Excel
        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance($value);
        }

        $cleaned = $this->clean((string) $value);
        if (!$cleaned) return null;

        if (is_numeric($cleaned) && (int)$cleaned > 10000 && (int)$cleaned < 60000) {
            try {
                return Carbon::createFromTimestamp(((int)$cleaned - 25569) * 86400);
            } catch (\Exception $e) {}
        }

        $formats = ['d/m/Y', 'Y-m-d', 'd-m-Y', 'm/d/Y', 'Y', 'd/m/y'];
        foreach ($formats as $format) {
            try {
                $date = Carbon::createFromFormat($format, $cleaned);
                if ($date && $date->year > 1900 && $date->year <= now()->year) {
                    return $date;
                }
            } catch (\Exception $e) {}
        }

        try {
            $date = Carbon::parse($cleaned);
            if ($date->year > 1900 && $date->year <= now()->year) {
                return $date;
            }
        } catch (\Exception $e) {}

        return null;
    }
}