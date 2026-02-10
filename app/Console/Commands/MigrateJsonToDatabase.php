<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Tag;
use App\Models\Client;
use App\Models\Category;
use App\Models\Devis;
use Exception;

class MigrateJsonToDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:json-to-db {--fresh : Clear existing data before migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate data from JSON files to database tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting JSON to Database migration...');
        $this->newLine();

        if ($this->option('fresh')) {
            if ($this->confirm('⚠️  This will delete all existing data. Continue?', false)) {
                $this->clearTables();
            } else {
                $this->info('Migration cancelled.');
                return;
            }
        }

        DB::beginTransaction();
        
        try {
            // Migrate in order (to handle foreign keys)
            $this->migrateTags();
            $this->migrateCategories();
            $this->migrateProducts();
            $this->migrateClients();
            $this->migrateDevis();

            DB::commit();
            
            $this->newLine();
            $this->info('✅ Migration completed successfully!');
            
        } catch (Exception $e) {
            DB::rollBack();
            $this->error('❌ Migration failed: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }

        return 0;
    }

    /**
     * Clear all tables
     */
    private function clearTables()
    {
        $this->warn('🗑️  Clearing existing data...');
        
        DB::table('devis')->delete();
        DB::table('products')->delete();
        DB::table('clients')->delete();
        DB::table('categories')->delete();
        DB::table('tags')->delete();
        
        $this->info('   Tables cleared.');
    }

    /**
     * Migrate tags from JSON
     */
    private function migrateTags()
    {
        $this->info('📌 Migrating Tags...');
        
        $jsonPath = storage_path('app/tags.json');
        
        if (!file_exists($jsonPath)) {
            $this->warn('   ⚠️  tags.json not found. Skipping...');
            return;
        }

        $tags = json_decode(file_get_contents($jsonPath), true);
        
        if (!is_array($tags) || empty($tags)) {
            $this->warn('   No tags to migrate.');
            return;
        }

        $count = 0;
        foreach ($tags as $tag) {
            try {
                Tag::updateOrCreate(
                    ['id' => $tag['id'] ?? null],
                    [
                        'name' => $tag['name'],
                        'slug' => $tag['slug'] ?? \Illuminate\Support\Str::slug($tag['name']),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
                $count++;
            } catch (Exception $e) {
                $this->warn("   Failed to migrate tag: {$tag['name']} - {$e->getMessage()}");
            }
        }

        $this->info("   ✓ Migrated {$count} tags");
    }

    /**
     * Migrate categories from products (extract unique categories)
     */
    private function migrateCategories()
    {
        $this->info('📁 Migrating Categories...');
        
        $jsonPath = storage_path('app/products.json');
        
        if (!file_exists($jsonPath)) {
            $this->warn('   ⚠️  products.json not found. Skipping categories...');
            return;
        }

        $products = json_decode(file_get_contents($jsonPath), true);
        
        if (!is_array($products)) {
            $this->warn('   No products found to extract categories.');
            return;
        }

        // Extract unique categories with their subcategories
        $categoriesMap = [];
        foreach ($products as $product) {
            $categoryName = $product['category'] ?? null;
            $subcategory = $product['subcategory'] ?? $product['subcate'] ?? null;
            
            if ($categoryName) {
                if (!isset($categoriesMap[$categoryName])) {
                    $categoriesMap[$categoryName] = [];
                }
                if ($subcategory && !in_array($subcategory, $categoriesMap[$categoryName])) {
                    $categoriesMap[$categoryName][] = $subcategory;
                }
            }
        }

        $count = 0;
        foreach ($categoriesMap as $name => $subcategories) {
            try {
                Category::updateOrCreate(
                    ['name' => $name],
                    [
                        'subcate' => !empty($subcategories) ? $subcategories : null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
                $count++;
            } catch (Exception $e) {
                $this->warn("   Failed to migrate category: {$name} - {$e->getMessage()}");
            }
        }

        $this->info("   ✓ Migrated {$count} categories");
    }

    /**
     * Migrate products from JSON
     */
    private function migrateProducts()
    {
        $this->info('📦 Migrating Products...');
        
        $jsonPath = storage_path('app/products.json');
        
        if (!file_exists($jsonPath)) {
            $this->warn('   ⚠️  products.json not found. Skipping...');
            return;
        }

        $products = json_decode(file_get_contents($jsonPath), true);
        
        if (!is_array($products) || empty($products)) {
            $this->warn('   No products to migrate.');
            return;
        }

        $count = 0;
        foreach ($products as $product) {
            try {
                Product::updateOrCreate(
                    ['id' => $product['id'] ?? null],
                    [
                        'sku' => $product['sku'] ?? 'PRD-' . strtoupper(substr(uniqid(), -6)),
                        'name' => $product['name'],
                        'description' => $product['description'] ?? null,
                        'category' => $product['category'] ?? null,
                        'subcate' => $product['subcategory'] ?? $product['subcate'] ?? null,
                        'tags' => $product['tags'] ?? [],
                        'images' => $product['images'] ?? [],
                        'pdf' => $product['pdf'] ?? null,
                        'price' => $product['price'] ?? 0,
                        'stock' => $product['stock'] ?? 0,
                        'status' => $product['status'] ?? 'Actif',
                        'created_at' => $product['created_at'] ?? now(),
                        'updated_at' => $product['updated_at'] ?? now(),
                    ]
                );
                $count++;
            } catch (Exception $e) {
                $this->warn("   Failed to migrate product: {$product['name']} - {$e->getMessage()}");
            }
        }

        $this->info("   ✓ Migrated {$count} products");
    }

    /**
     * Migrate clients from JSON
     */
    private function migrateClients()
    {
        $this->info('👥 Migrating Clients...');
        
        $jsonPath = storage_path('app/private/clients.json');
        
        if (!file_exists($jsonPath)) {
            $this->warn('   ⚠️  clients.json not found. Skipping...');
            return;
        }

        $clients = json_decode(file_get_contents($jsonPath), true);
        
        if (!is_array($clients) || empty($clients)) {
            $this->warn('   No clients to migrate.');
            return;
        }

        $count = 0;
        foreach ($clients as $client) {
            try {
                Client::updateOrCreate(
                    ['id' => $client['id'] ?? null],
                    [
                        'name' => $client['name'],
                        'company' => $client['company'] ?? null,
                        'logo' => $client['logo'] ?? null,
                        'email' => $client['email'],
                        'phone' => $client['phone'] ?? null,
                        'siteweb' => $client['siteweb'] ?? $client['website'] ?? null,
                        'address' => $client['address'] ?? null,
                        'city' => $client['city'] ?? null,
                        'notes' => $client['notes'] ?? null,
                        'status' => $client['status'] ?? 'actif',
                        'created_at' => $client['created_at'] ?? now(),
                        'updated_at' => $client['updated_at'] ?? now(),
                    ]
                );
                $count++;
            } catch (Exception $e) {
                $this->warn("   Failed to migrate client: {$client['name']} - {$e->getMessage()}");
            }
        }

        $this->info("   ✓ Migrated {$count} clients");
    }

    /**
     * Migrate devis from JSON
     */
    private function migrateDevis()
    {
        $this->info('📋 Migrating Devis...');
        
        $jsonPath = storage_path('app/private/devis.json');
        
        if (!file_exists($jsonPath)) {
            $this->warn('   ⚠️  devis.json not found. Skipping...');
            return;
        }

        $devisList = json_decode(file_get_contents($jsonPath), true);
        
        if (!is_array($devisList) || empty($devisList)) {
            $this->warn('   No devis to migrate.');
            return;
        }

        $count = 0;
        foreach ($devisList as $devis) {
            try {
                Devis::updateOrCreate(
                    ['ref_id' => $devis['id'] ?? null],
                    [
                        'client' => $devis['client'] ?? $devis['name'] ?? null,
                        'email' => $devis['email'],
                        'phone' => $devis['phone'] ?? null,
                        'company' => $devis['company'] ?? null,
                        'type' => $devis['type'] ?? 'standard',
                        'product' => $devis['product'] ?? null,
                        'quantity' => $devis['quantity'] ?? null,
                        'budget' => $devis['budget'] ?? null,
                        'message' => $devis['message'] ?? null,
                        'requirements' => $devis['requirements'] ?? null,
                        'status' => $devis['status'] ?? 'nouveau',
                        'date' => $devis['date'] ?? $devis['created_at'] ?? now(),
                        'created_at' => $devis['created_at'] ?? now(),
                        'updated_at' => $devis['updated_at'] ?? now(),
                    ]
                );
                $count++;
            } catch (Exception $e) {
                $this->warn("   Failed to migrate devis: {$devis['id']} - {$e->getMessage()}");
            }
        }

        $this->info("   ✓ Migrated {$count} devis");
    }
}