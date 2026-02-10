<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Devis;
use Illuminate\Support\Facades\DB;

class MigrateDevisJsonToDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devis:migrate-json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate devis data from JSON file to SQL database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting devis migration from JSON to database...');

        $jsonPath = storage_path('app/private/devis.json');

        if (!file_exists($jsonPath)) {
            $this->warn('JSON file not found at: ' . $jsonPath);
            return 0;
        }

        $jsonData = json_decode(file_get_contents($jsonPath), true);

        if (!is_array($jsonData) || empty($jsonData)) {
            $this->warn('No devis data found in JSON file.');
            return 0;
        }

        $this->info('Found ' . count($jsonData) . ' devis records in JSON file.');

        $imported = 0;
        $skipped = 0;

        DB::beginTransaction();

        try {
            foreach ($jsonData as $item) {
                // Check if this devis already exists
                $exists = Devis::where('ref_id', $item['id'])->exists();

                if ($exists) {
                    $this->warn('Skipping existing devis: ' . $item['id']);
                    $skipped++;
                    continue;
                }

                Devis::create([
                    'ref_id' => $item['id'],
                    'name' => $item['name'] ?? null,
                    'email' => $item['email'],
                    'phone' => $item['phone'] ?? null,
                    'company' => $item['company'] ?? null,
                    'type' => $item['type'] ?? 'standard',
                    'product' => $item['product'] ?? null,
                    'quantity' => $item['quantity'] ?? 1,
                    'message' => $item['message'] ?? null,
                    'requirements' => $item['requirements'] ?? null,
                    'budget' => $item['budget'] ?? null,
                    'status' => $item['status'] ?? 'nouveau',
                    'created_at' => $item['created_at'] ?? now(),
                    'updated_at' => $item['updated_at'] ?? now(),
                ]);

                $this->info('✓ Imported: ' . $item['id']);
                $imported++;
            }

            DB::commit();

            $this->info('');
            $this->info('Migration completed successfully!');
            $this->info('Imported: ' . $imported);
            $this->info('Skipped: ' . $skipped);
            $this->info('Total: ' . ($imported + $skipped));

            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Migration failed: ' . $e->getMessage());
            return 1;
        }
    }
}