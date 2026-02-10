<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize,
    WithTitle
};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class DevisExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    protected Collection $devis;
    protected string $type;

    public function __construct(array $devis, string $type)
    {
        $this->devis = collect($devis);
        $this->type  = $type;
    }

    public function collection()
    {
        return $this->devis;
    }

    public function headings(): array
    {
        return $this->type === 'specific'
            ? ['ID','Nom','Email','Entreprise','Type d\'échangeur','Cuivre Ø','Pas ailette','Hauteur (mm)','Largeur (mm)','Longueur (mm)','Longueur totale (mm)','Nombre de tubes','Géométrie X (mm)','Géométrie Y (mm)','Collecteur 1','Ø C1','Collecteur 2','Ø C2','Exigences','Date','Statut']
            : ['ID','Nom','Email','Entreprise','Produit','Quantité','Date','Statut'];
    }

    public function map($d): array
    {
        return $this->type === 'specific'
            ? [
                $d['id'],
                $d['name'],
                $d['email'],
                $d['company'],
                $d['type_exchangeur'] ?? '',
                $d['cuivre_diametre'] ?? '',
                $d['pas_ailette'] ?? '',
                $d['hauteur_mm'] ?? '',
                $d['largeur_mm'] ?? '',
                $d['longueur_mm'] ?? '',
                $d['longueur_totale_mm'] ?? '',
                $d['nombre_tubes'] ?? '',
                $d['geometrie_x_mm'] ?? '',
                $d['geometrie_y_mm'] ?? '',
                $d['collecteur1_nb'] ?? '',
                $d['collecteur1_diametre'] ?? '',
                $d['collecteur2_nb'] ?? '',
                $d['collecteur2_diametre'] ?? '',
                $d['requirements'] ?? '',
                Carbon::parse($d['created_at'])->format('d/m/Y'),
                strtoupper(str_replace('_',' ', $d['status']))
              ]
            : [
                $d['id'],
                $d['name'],
                $d['email'],
                $d['company'],
                $d['product'],
                $d['quantity'],
                Carbon::parse($d['created_at'])->format('d/m/Y'),
                strtoupper(str_replace('_',' ', $d['status']))
              ];
    }

    public function title(): string
    {
        return $this->type === 'specific' ? 'Spécifique' : 'Standard';
    }

    public function styles(Worksheet $sheet)
    {
        // Header style: stop at the last used column
        $lastCol = $this->type === 'specific' ? 'W' : 'H';
        $sheet->getStyle('A1:'.$lastCol.'1')->applyFromArray([
            'font' => ['bold'=>true,'color'=>['rgb'=>'FFFFFF']],
            'fill' => [
                'fillType'=>Fill::FILL_SOLID,
                'startColor'=>['rgb'=>'1E3A8A']
            ],
        ]);

        // Status column (last column)
        foreach ($this->devis as $i => $d) {
            $row = $i + 2;
            $color = match ($d['status']) {
                'nouveau'   => '93C5FD',
                'en_cours'  => 'FCD34D',
                'envoye'    => 'A5B4FC',
                'confirme'  => '86EFAC',
                'annule'    => 'FCA5A5',
                default     => 'E5E7EB',
            };

            $sheet->getStyle($lastCol.$row)->applyFromArray([
                'font' => ['bold'=>true],
                'fill' => [
                    'fillType'=>Fill::FILL_SOLID,
                    'startColor'=>['rgb'=>$color]
                ],
            ]);
        }
    }
}
