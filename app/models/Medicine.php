<?php

/**
 * MOCK MODEL — no real database yet (DB_ENABLED = false).
 * See config/schema-reference.sql for the `medicines` / `categories`
 * table shape this mirrors. Swap seed() for a PDO query once ready.
 */
class Medicine extends Model
{
    public static function categories(): array
    {
        return [
            1 => 'Pain Relief',
            2 => 'Cold & Flu',
            3 => 'Vitamins & Supplements',
            4 => 'Antibiotics',
            5 => 'Skin Care',
            6 => 'Diabetes Care',
            7 => 'Baby & Child Care',
        ];
    }

    public static function seed(): array
    {
        return [
            ['id' => 1, 'name' => 'Paracetamol 500mg', 'category_id' => 1, 'price' => 250.00, 'stock' => 120, 'requires_rx' => false, 'manufacturer' => 'HealthCare Labs', 'description' => 'Fast-acting pain and fever relief, 20 tablets per strip.'],
            ['id' => 2, 'name' => 'Ibuprofen 400mg', 'category_id' => 1, 'price' => 320.00, 'stock' => 80, 'requires_rx' => false, 'manufacturer' => 'MedPlus', 'description' => 'Anti-inflammatory pain reliever for headaches and muscle pain.'],
            ['id' => 3, 'name' => 'Amoxicillin 500mg', 'category_id' => 4, 'price' => 540.00, 'stock' => 0, 'requires_rx' => true, 'manufacturer' => 'BioGenix', 'description' => 'Broad-spectrum antibiotic. Prescription required.'],
            ['id' => 4, 'name' => 'Cetirizine 10mg', 'category_id' => 2, 'price' => 180.00, 'stock' => 60, 'requires_rx' => false, 'manufacturer' => 'AllerCare', 'description' => 'Antihistamine for allergy and cold symptom relief.'],
            ['id' => 5, 'name' => 'Vitamin C 1000mg', 'category_id' => 3, 'price' => 690.00, 'stock' => 45, 'requires_rx' => false, 'manufacturer' => 'NutriWell', 'description' => 'Immune support effervescent tablets, 20 count.'],
            ['id' => 6, 'name' => 'Multivitamin Daily', 'category_id' => 3, 'price' => 950.00, 'stock' => 30, 'requires_rx' => false, 'manufacturer' => 'NutriWell', 'description' => 'Complete daily vitamin and mineral supplement.'],
            ['id' => 7, 'name' => 'Metformin 500mg', 'category_id' => 6, 'price' => 410.00, 'stock' => 25, 'requires_rx' => true, 'manufacturer' => 'GlucoCare', 'description' => 'Blood sugar management. Prescription required.'],
            ['id' => 8, 'name' => 'Hydrocortisone Cream 1%', 'category_id' => 5, 'price' => 380.00, 'stock' => 15, 'requires_rx' => false, 'manufacturer' => 'DermaPlus', 'description' => 'Topical relief for skin irritation and itching.'],
            ['id' => 9, 'name' => 'Cough Syrup 100ml', 'category_id' => 2, 'price' => 460.00, 'stock' => 0, 'requires_rx' => false, 'manufacturer' => 'HealthCare Labs', 'description' => 'Soothing relief for dry and chesty cough.'],
            ['id' => 10, 'name' => 'Baby Paracetamol Drops', 'category_id' => 7, 'price' => 290.00, 'stock' => 40, 'requires_rx' => false, 'manufacturer' => 'KidCare', 'description' => 'Gentle fever and pain relief for infants.'],
            ['id' => 11, 'name' => 'Azithromycin 250mg', 'category_id' => 4, 'price' => 620.00, 'stock' => 18, 'requires_rx' => true, 'manufacturer' => 'BioGenix', 'description' => 'Antibiotic for bacterial infections. Prescription required.'],
            ['id' => 12, 'name' => 'Omega-3 Fish Oil', 'category_id' => 3, 'price' => 1250.00, 'stock' => 22, 'requires_rx' => false, 'manufacturer' => 'NutriWell', 'description' => 'Heart and brain health support, 60 softgels.'],
            ['id' => 13, 'name' => 'Clarithromycin 500mg', 'category_id' => 4, 'price' => 980.00, 'stock' => 20, 'requires_rx' => true, 'manufacturer' => 'Abbott', 'description' => 'Substitute antibiotic for resistant bacterial infections. Prescription required.'],
            ['id' => 14, 'name' => 'Doxycycline 100mg', 'category_id' => 4, 'price' => 410.00, 'stock' => 35, 'requires_rx' => true, 'manufacturer' => 'Sandoz', 'description' => 'Broad-spectrum antibiotic alternative. Prescription required.'],
        ];
    }

    /**
     * Mock "active therapy" scenario used to demo the alternative-medicine
     * suggestion modal on the Dashboard: a medicine the customer normally
     * takes that has gone out of stock, plus in-stock alternates.
     */
    public function activeTherapyAlert(): ?array
    {
        $medicine = $this->find(3); // Amoxicillin 500mg — seeded out of stock
        if (!$medicine || $medicine['stock'] > 0) {
            return null;
        }
        return [
            'medicine'   => $medicine,
            'alternates' => $this->alternatesFor(3),
        ];
    }

    public function all(): array
    {
        return self::seed();
    }

    public function find(int $id): ?array
    {
        foreach (self::seed() as $m) {
            if ($m['id'] === $id) {
                return $m;
            }
        }
        return null;
    }

    public function byCategory(int $categoryId): array
    {
        return array_values(array_filter(self::seed(), fn($m) => $m['category_id'] === $categoryId));
    }

    public function search(string $term): array
    {
        $term = strtolower(trim($term));
        if ($term === '') {
            return self::seed();
        }
        return array_values(array_filter(self::seed(), function ($m) use ($term) {
            return str_contains(strtolower($m['name']), $term)
                || str_contains(strtolower($m['description']), $term);
        }));
    }

    /**
     * Suggested alternates: same category, in stock, not the given medicine.
     */
    public function alternatesFor(int $id): array
    {
        $medicine = $this->find($id);
        if (!$medicine) {
            return [];
        }
        return array_values(array_filter(self::seed(), function ($m) use ($medicine, $id) {
            return $m['category_id'] === $medicine['category_id']
                && $m['id'] !== $id
                && $m['stock'] > 0;
        }));
    }

    public function featured(int $limit = 4): array
    {
        $items = array_values(array_filter(self::seed(), fn($m) => $m['stock'] > 0));
        return array_slice($items, 0, $limit);
    }

    /** Reads the session's viewed-medicine trail (set by ProductController). */
    public function recentlyViewed(?int $excludeId = null, int $limit = 6): array
    {
        $ids = $_SESSION['recently_viewed'] ?? [];
        $items = [];
        foreach ($ids as $id) {
            if ($id === $excludeId) {
                continue;
            }
            $m = $this->find($id);
            if ($m) {
                $items[] = $m;
            }
            if (count($items) >= $limit) {
                break;
            }
        }
        return $items;
    }

    public static function trackViewed(int $id): void
    {
        $ids = $_SESSION['recently_viewed'] ?? [];
        $ids = array_values(array_diff($ids, [$id]));
        array_unshift($ids, $id);
        $_SESSION['recently_viewed'] = array_slice($ids, 0, 10);
    }

    /** Deterministic mock rating so Product Details has something to show. */
    public static function ratingFor(int $id): array
    {
        $stars = round(4.2 + (($id * 7) % 8) / 10, 1);
        $count = 40 + (($id * 37) % 200);
        return ['stars' => min($stars, 5.0), 'count' => $count];
    }

    public static function categoryName(int $id): string
    {
        return self::categories()[$id] ?? 'General';
    }

    public static function categoryCounts(): array
    {
        $counts = [];
        foreach (self::seed() as $m) {
            $counts[$m['category_id']] = ($counts[$m['category_id']] ?? 0) + 1;
        }
        return $counts;
    }

    public static function brands(): array
    {
        $brands = array_unique(array_column(self::seed(), 'manufacturer'));
        sort($brands);
        return $brands;
    }

    public static function priceRange(): array
    {
        $prices = array_column(self::seed(), 'price');
        return ['min' => floor(min($prices) / 100) * 100, 'max' => ceil(max($prices) / 100) * 100];
    }

    /**
     * Apply optional category / brand / price-range / sort filters to a
     * result set already narrowed by search or category.
     */
    public function filterAndSort(array $items, array $brands = [], ?float $minPrice = null, ?float $maxPrice = null, string $sort = 'relevance'): array
    {
        if (!empty($brands)) {
            $items = array_values(array_filter($items, fn($m) => in_array($m['manufacturer'], $brands, true)));
        }
        if ($minPrice !== null) {
            $items = array_values(array_filter($items, fn($m) => $m['price'] >= $minPrice));
        }
        if ($maxPrice !== null) {
            $items = array_values(array_filter($items, fn($m) => $m['price'] <= $maxPrice));
        }

        switch ($sort) {
            case 'price_asc':
                usort($items, fn($a, $b) => $a['price'] <=> $b['price']);
                break;
            case 'price_desc':
                usort($items, fn($a, $b) => $b['price'] <=> $a['price']);
                break;
            case 'name':
                usort($items, fn($a, $b) => strcmp($a['name'], $b['name']));
                break;
        }

        return $items;
    }
}
