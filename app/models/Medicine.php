<?php

// Medicine data. seed() holds the sample list until the DB is connected.
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
            ['id' => 1, 'name' => 'Paracetamol 500mg', 'image' => 'paracetamol-500mg.jpg', 'images' => ['paracetamol-500mg-2.jpg', 'paracetamol-500mg-3.jpg'], 'category_id' => 1, 'price' => 250.00, 'stock' => 120, 'requires_rx' => false, 'manufacturer' => 'HealthCare Labs', 'description' => 'Fast-acting pain and fever relief, 20 tablets per strip.'],
            ['id' => 2, 'name' => 'Ibuprofen 400mg', 'image' => 'ibuprofen-400mg.jpg', 'images' => ['ibuprofen-400mg-2.jpg', 'ibuprofen-400mg-3.jpg'], 'category_id' => 1, 'price' => 320.00, 'stock' => 80, 'requires_rx' => false, 'manufacturer' => 'MedPlus', 'description' => 'Anti-inflammatory pain reliever for headaches and muscle pain.'],
            ['id' => 3, 'name' => 'Amoxicillin 500mg', 'image' => 'amoxicillin-500mg.jpg', 'images' => ['amoxicillin-500mg-2.jpg', 'amoxicillin-500mg-3.jpg'], 'category_id' => 4, 'price' => 540.00, 'stock' => 0, 'requires_rx' => true, 'manufacturer' => 'BioGenix', 'description' => 'Broad-spectrum antibiotic. Prescription required.'],
            ['id' => 4, 'name' => 'Cetirizine 10mg', 'image' => 'cetirizine-10mg.jpg', 'images' => ['cetirizine-10mg-2.jpg', 'cetirizine-10mg-3.jpg'], 'category_id' => 2, 'price' => 180.00, 'stock' => 60, 'requires_rx' => false, 'manufacturer' => 'AllerCare', 'description' => 'Antihistamine for allergy and cold symptom relief.'],
            ['id' => 5, 'name' => 'Vitamin C 1000mg', 'image' => 'vitamin-c-1000mg.jpg', 'images' => ['vitamin-c-1000mg-2.jpg', 'vitamin-c-1000mg-3.jpg'], 'category_id' => 3, 'price' => 690.00, 'stock' => 45, 'requires_rx' => false, 'manufacturer' => 'NutriWell', 'description' => 'Immune support effervescent tablets, 20 count.'],
            ['id' => 6, 'name' => 'Multivitamin Daily', 'image' => 'multivitamin-daily.jpg', 'images' => ['multivitamin-daily-2.jpg', 'multivitamin-daily-3.jpg'], 'category_id' => 3, 'price' => 950.00, 'stock' => 30, 'requires_rx' => false, 'manufacturer' => 'NutriWell', 'description' => 'Complete daily vitamin and mineral supplement.'],
            ['id' => 7, 'name' => 'Metformin 500mg', 'image' => 'metformin-500mg.jpg', 'images' => ['metformin-500mg-2.jpg', 'metformin-500mg-3.jpg'], 'category_id' => 6, 'price' => 410.00, 'stock' => 25, 'requires_rx' => true, 'manufacturer' => 'GlucoCare', 'description' => 'Blood sugar management. Prescription required.'],
            ['id' => 8, 'name' => 'Hydrocortisone Cream 1%', 'image' => 'hydrocortisone-cream-1.jpg', 'images' => ['hydrocortisone-cream-1-2.jpg', 'hydrocortisone-cream-1-3.jpg'], 'category_id' => 5, 'price' => 380.00, 'stock' => 15, 'requires_rx' => false, 'manufacturer' => 'DermaPlus', 'description' => 'Topical relief for skin irritation and itching.'],
            ['id' => 9, 'name' => 'Cough Syrup 100ml', 'image' => 'cough-syrup-100ml.jpg', 'images' => ['cough-syrup-100ml-2.jpg', 'cough-syrup-100ml-3.jpg'], 'category_id' => 2, 'price' => 460.00, 'stock' => 0, 'requires_rx' => false, 'manufacturer' => 'HealthCare Labs', 'description' => 'Soothing relief for dry and chesty cough.'],
            ['id' => 10, 'name' => 'Baby Paracetamol Drops', 'image' => 'baby-paracetamol-drops.jpg', 'images' => ['baby-paracetamol-drops-2.jpg', 'baby-paracetamol-drops-3.jpg'], 'category_id' => 7, 'price' => 290.00, 'stock' => 40, 'requires_rx' => false, 'manufacturer' => 'KidCare', 'description' => 'Gentle fever and pain relief for infants.'],
            ['id' => 11, 'name' => 'Azithromycin 250mg', 'image' => 'azithromycin-250mg.jpg', 'images' => ['azithromycin-250mg-2.jpg', 'azithromycin-250mg-3.jpg'], 'category_id' => 4, 'price' => 620.00, 'stock' => 18, 'requires_rx' => true, 'manufacturer' => 'BioGenix', 'description' => 'Antibiotic for bacterial infections. Prescription required.'],
            ['id' => 12, 'name' => 'Omega-3 Fish Oil', 'image' => 'omega-3-fish-oil.jpg', 'images' => ['omega-3-fish-oil-2.jpg', 'omega-3-fish-oil-3.jpg'], 'category_id' => 3, 'price' => 1250.00, 'stock' => 22, 'requires_rx' => false, 'manufacturer' => 'NutriWell', 'description' => 'Heart and brain health support, 60 softgels.'],
            ['id' => 13, 'name' => 'Clarithromycin 500mg', 'image' => 'clarithromycin-500mg.jpg', 'images' => ['clarithromycin-500mg-2.jpg', 'clarithromycin-500mg-3.jpg'], 'category_id' => 4, 'price' => 980.00, 'stock' => 20, 'requires_rx' => true, 'manufacturer' => 'Abbott', 'description' => 'Substitute antibiotic for resistant bacterial infections. Prescription required.'],
            ['id' => 14, 'name' => 'Doxycycline 100mg', 'image' => 'doxycycline-100mg.jpg', 'images' => ['doxycycline-100mg-2.jpg', 'doxycycline-100mg-3.jpg'], 'category_id' => 4, 'price' => 410.00, 'stock' => 35, 'requires_rx' => true, 'manufacturer' => 'Sandoz', 'description' => 'Broad-spectrum antibiotic alternative. Prescription required.'],
        ];
    }

    // Dashboard demo: a medicine the customer takes that's out of stock,
    // plus its in-stock alternates.
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

    // Alternates: same category, in stock, excluding the medicine itself.
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

    // Medicines the customer viewed recently (kept in the session).
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

    // Fixed sample rating so the product page has something to show.
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

    // Apply brand / price / sort filters to a list of medicines.
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
