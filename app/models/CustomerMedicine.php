<?php

/**
 * CustomerMedicine - medicines as the customer module browses them.
 * Owner: customer module.
 *
 * Read-only helpers for the shop: featured, search, filters, alternatives,
 * recently viewed, ratings. seed() holds the sample list until the DB is on.
 *
 * Why not "Medicine": the Inventory Manager module owns app/models/Medicine.php
 * (create/update/delete). PHP allows one class per name, so the two cannot
 * both be called Medicine.
 *
 * After the interim, when DB_ENABLED is true:
 *   1. change this line to   class CustomerMedicine extends Medicine
 *   2. delete all(), find() and byCategory() here - Medicine's versions read
 *      the real table
 *   3. switch to the schema's names: medicine_id, unit_price,
 *      requires_prescription, and the stock value Medicine calculates
 *      from batches
 *
 * Pack size - what "quantity 1" means.
 *   Every price in the shop is the price of ONE PACK, not one tablet:
 *   Paracetamol 500mg is Rs. 250 for a strip (card) of 10 tablets. So the
 *   quantity in the cart counts packs. Quantity 2 = 2 strips = 20 tablets.
 *     pack_unit  what the customer buys      'strip', 'bottle', 'tube'
 *     pack_size  how much is in one pack     20
 *     pack_item  what pack_size counts       'tablets', 'ml', 'g'
 *   The shared `medicines` table has no pack columns yet. When the DB is
 *   on, the Inventory Manager's table needs these three (group decision).
 */
class CustomerMedicine extends Model
{
    /** Plural of each pack unit, for "2 strips", "3 boxes". */
    private const UNIT_PLURALS = [
        'strip'  => 'strips',
        'bottle' => 'bottles',
        'tube'   => 'tubes',
        'box'    => 'boxes',
        'pack'   => 'packs',
    ];

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
            ['id' => 1, 'name' => 'Paracetamol 500mg', 'generic' => 'Paracetamol', 'strength' => '500mg', 'allergens' => ['paracetamol', 'acetaminophen'], 'max_per_order' => 5, 'pack_unit' => 'strip', 'pack_size' => 10, 'pack_item' => 'tablets', 'image' => 'paracetamol-500mg.jpg', 'images' => ['paracetamol-500mg-2.jpg', 'paracetamol-500mg-3.jpg'], 'category_id' => 1, 'price' => 250.00, 'stock' => 120, 'requires_rx' => false, 'manufacturer' => 'HealthCare Labs', 'description' => 'Fast-acting pain and fever relief, 10 tablets per strip.'],
            ['id' => 2, 'name' => 'Ibuprofen 400mg', 'generic' => 'Ibuprofen', 'strength' => '400mg', 'allergens' => ['ibuprofen', 'nsaid', 'nsaids', 'aspirin'], 'pack_unit' => 'strip', 'pack_size' => 10, 'pack_item' => 'tablets', 'image' => 'ibuprofen-400mg.jpg', 'images' => ['ibuprofen-400mg-2.jpg', 'ibuprofen-400mg-3.jpg'], 'category_id' => 1, 'price' => 320.00, 'stock' => 80, 'requires_rx' => false, 'manufacturer' => 'MedPlus', 'description' => 'Anti-inflammatory pain reliever for headaches and muscle pain.'],
            ['id' => 3, 'name' => 'Amoxicillin 500mg', 'generic' => 'Amoxicillin', 'strength' => '500mg', 'allergens' => ['amoxicillin', 'penicillin'], 'pack_unit' => 'strip', 'pack_size' => 10, 'pack_item' => 'capsules', 'image' => 'amoxicillin-500mg.jpg', 'images' => ['amoxicillin-500mg-2.jpg', 'amoxicillin-500mg-3.jpg'], 'category_id' => 4, 'price' => 540.00, 'stock' => 0, 'requires_rx' => true, 'manufacturer' => 'BioGenix', 'description' => 'Broad-spectrum antibiotic. Prescription required.'],
            ['id' => 4, 'name' => 'Cetirizine 10mg', 'generic' => 'Cetirizine', 'strength' => '10mg', 'allergens' => ['cetirizine', 'antihistamine'], 'pack_unit' => 'strip', 'pack_size' => 10, 'pack_item' => 'tablets', 'image' => 'cetirizine-10mg.jpg', 'images' => ['cetirizine-10mg-2.jpg', 'cetirizine-10mg-3.jpg'], 'category_id' => 2, 'price' => 180.00, 'stock' => 60, 'requires_rx' => false, 'manufacturer' => 'AllerCare', 'description' => 'Antihistamine for allergy and cold symptom relief.'],
            ['id' => 5, 'name' => 'Vitamin C 1000mg', 'generic' => 'Ascorbic acid (Vitamin C)', 'strength' => '1000mg', 'allergens' => ['vitamin c', 'ascorbic acid'], 'pack_unit' => 'tube', 'pack_size' => 20, 'pack_item' => 'effervescent tablets', 'image' => 'vitamin-c-1000mg.jpg', 'images' => ['vitamin-c-1000mg-2.jpg', 'vitamin-c-1000mg-3.jpg'], 'category_id' => 3, 'price' => 690.00, 'stock' => 45, 'requires_rx' => false, 'manufacturer' => 'NutriWell', 'description' => 'Immune support effervescent tablets, 20 count.'],
            ['id' => 6, 'name' => 'Multivitamin Daily', 'generic' => 'Multivitamin', 'strength' => 'daily', 'allergens' => ['multivitamin'], 'pack_unit' => 'bottle', 'pack_size' => 30, 'pack_item' => 'tablets', 'image' => 'multivitamin-daily.jpg', 'images' => ['multivitamin-daily-2.jpg', 'multivitamin-daily-3.jpg'], 'category_id' => 3, 'price' => 950.00, 'stock' => 30, 'requires_rx' => false, 'manufacturer' => 'NutriWell', 'description' => 'Complete daily vitamin and mineral supplement.'],
            ['id' => 7, 'name' => 'Metformin 500mg', 'generic' => 'Metformin', 'strength' => '500mg', 'allergens' => ['metformin'], 'pack_unit' => 'strip', 'pack_size' => 10, 'pack_item' => 'tablets', 'image' => 'metformin-500mg.jpg', 'images' => ['metformin-500mg-2.jpg', 'metformin-500mg-3.jpg'], 'category_id' => 6, 'price' => 410.00, 'stock' => 25, 'requires_rx' => true, 'manufacturer' => 'GlucoCare', 'description' => 'Blood sugar management. Prescription required.'],
            ['id' => 8, 'name' => 'Hydrocortisone Cream 1%', 'generic' => 'Hydrocortisone', 'strength' => '1%', 'allergens' => ['hydrocortisone', 'corticosteroid', 'steroid'], 'pack_unit' => 'tube', 'pack_size' => 15, 'pack_item' => 'g', 'image' => 'hydrocortisone-cream-1.jpg', 'images' => ['hydrocortisone-cream-1-2.jpg', 'hydrocortisone-cream-1-3.jpg'], 'category_id' => 5, 'price' => 380.00, 'stock' => 15, 'requires_rx' => false, 'manufacturer' => 'DermaPlus', 'description' => 'Topical relief for skin irritation and itching.'],
            ['id' => 9, 'name' => 'Cough Syrup 100ml', 'generic' => 'Dextromethorphan + Guaifenesin', 'strength' => '10mg/100mg per 5 ml', 'allergens' => ['dextromethorphan', 'guaifenesin'], 'pack_unit' => 'bottle', 'pack_size' => 100, 'pack_item' => 'ml', 'image' => 'cough-syrup-100ml.jpg', 'images' => ['cough-syrup-100ml-2.jpg', 'cough-syrup-100ml-3.jpg'], 'category_id' => 2, 'price' => 460.00, 'stock' => 0, 'requires_rx' => false, 'manufacturer' => 'HealthCare Labs', 'description' => 'Soothing relief for dry and chesty cough.'],
            ['id' => 10, 'name' => 'Baby Paracetamol Drops', 'generic' => 'Paracetamol', 'strength' => '120mg per 5 ml', 'allergens' => ['paracetamol', 'acetaminophen'], 'pack_unit' => 'bottle', 'pack_size' => 15, 'pack_item' => 'ml', 'image' => 'baby-paracetamol-drops.jpg', 'images' => ['baby-paracetamol-drops-2.jpg', 'baby-paracetamol-drops-3.jpg'], 'category_id' => 7, 'price' => 290.00, 'stock' => 40, 'requires_rx' => false, 'manufacturer' => 'KidCare', 'description' => 'Gentle fever and pain relief for infants.'],
            ['id' => 11, 'name' => 'Azithromycin 250mg', 'generic' => 'Azithromycin', 'strength' => '250mg', 'allergens' => ['azithromycin', 'macrolide'], 'pack_unit' => 'strip', 'pack_size' => 6, 'pack_item' => 'tablets', 'image' => 'azithromycin-250mg.jpg', 'images' => ['azithromycin-250mg-2.jpg', 'azithromycin-250mg-3.jpg'], 'category_id' => 4, 'price' => 620.00, 'stock' => 18, 'requires_rx' => true, 'manufacturer' => 'BioGenix', 'description' => 'Antibiotic for bacterial infections. Prescription required.'],
            ['id' => 12, 'name' => 'Omega-3 Fish Oil', 'generic' => 'Omega-3 fatty acids (fish oil)', 'strength' => '1000mg', 'allergens' => ['fish', 'fish oil'], 'pack_unit' => 'bottle', 'pack_size' => 60, 'pack_item' => 'softgels', 'image' => 'omega-3-fish-oil.jpg', 'images' => ['omega-3-fish-oil-2.jpg', 'omega-3-fish-oil-3.jpg'], 'category_id' => 3, 'price' => 1250.00, 'stock' => 22, 'requires_rx' => false, 'manufacturer' => 'NutriWell', 'description' => 'Heart and brain health support, 60 softgels.'],
            ['id' => 13, 'name' => 'Clarithromycin 500mg', 'generic' => 'Clarithromycin', 'strength' => '500mg', 'allergens' => ['clarithromycin', 'macrolide'], 'pack_unit' => 'strip', 'pack_size' => 10, 'pack_item' => 'tablets', 'image' => 'clarithromycin-500mg.jpg', 'images' => ['clarithromycin-500mg-2.jpg', 'clarithromycin-500mg-3.jpg'], 'category_id' => 4, 'price' => 980.00, 'stock' => 20, 'requires_rx' => true, 'manufacturer' => 'Abbott', 'description' => 'Substitute antibiotic for resistant bacterial infections. Prescription required.'],
            ['id' => 14, 'name' => 'Doxycycline 100mg', 'generic' => 'Doxycycline', 'strength' => '100mg', 'allergens' => ['doxycycline', 'tetracycline'], 'pack_unit' => 'strip', 'pack_size' => 10, 'pack_item' => 'capsules', 'image' => 'doxycycline-100mg.jpg', 'images' => ['doxycycline-100mg-2.jpg', 'doxycycline-100mg-3.jpg'], 'category_id' => 4, 'price' => 410.00, 'stock' => 35, 'requires_rx' => true, 'manufacturer' => 'Sandoz', 'description' => 'Broad-spectrum antibiotic alternative. Prescription required.'],
            // Same medicine, different maker - what the "alternatives" feature offers.
            ['id' => 15, 'name' => 'Amoxicillin 500mg (CareGen)', 'generic' => 'Amoxicillin', 'strength' => '500mg', 'allergens' => ['amoxicillin', 'penicillin'], 'pack_unit' => 'strip', 'pack_size' => 10, 'pack_item' => 'capsules', 'image' => 'amoxicillin-500mg-caregen.jpg', 'images' => [], 'category_id' => 4, 'price' => 560.00, 'stock' => 40, 'requires_rx' => true, 'manufacturer' => 'CareGen', 'description' => 'Amoxicillin 500mg capsules from CareGen - the same medicine and strength as other amoxicillin 500mg brands. Prescription required.'],
            ['id' => 16, 'name' => 'Cough Syrup 100ml (Tussicare)', 'generic' => 'Dextromethorphan + Guaifenesin', 'strength' => '10mg/100mg per 5 ml', 'allergens' => ['dextromethorphan', 'guaifenesin'], 'pack_unit' => 'bottle', 'pack_size' => 100, 'pack_item' => 'ml', 'image' => 'cough-syrup-tussicare.jpg', 'images' => [], 'category_id' => 2, 'price' => 480.00, 'stock' => 25, 'requires_rx' => false, 'manufacturer' => 'Tussicare', 'description' => 'Same active ingredients and strength as our Cough Syrup 100ml, from a different maker.'],
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

    /**
     * Alternatives: the SAME medicine from another maker - same generic
     * (active ingredient), same strength, same form - and in stock.
     * Only these can be swapped without a doctor. A different antibiotic, or
     * an antihistamine instead of a cough syrup, is not an alternative, even
     * if it sits in the same category.
     */
    public function alternatesFor(int $id): array
    {
        $medicine = $this->find($id);
        if (!$medicine) {
            return [];
        }
        return array_values(array_filter(self::seed(), function ($m) use ($medicine, $id) {
            return $m['id'] !== $id
                && $m['stock'] > 0
                && strcasecmp($m['generic'] ?? '', $medicine['generic'] ?? '') === 0
                && strcasecmp($m['strength'] ?? '', $medicine['strength'] ?? '') === 0
                && ($m['pack_item'] ?? '') === ($medicine['pack_item'] ?? '');
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

    /* ==================================================================
     * Order limits and allergy checks
     * ================================================================== */

    /**
     * Most packs of one over-the-counter medicine a single order may hold.
     * Store policy, to stop stockpiling. A medicine can set its own lower
     * 'max_per_order' - paracetamol is 5 strips because of overdose risk.
     * Prescription medicines are limited by the prescription instead.
     */
    public const MAX_PER_ORDER = 10;

    public static function maxPerOrder(array $medicine): int
    {
        return (int) ($medicine['max_per_order'] ?? self::MAX_PER_ORDER);
    }

    /**
     * The patient's allergies that this medicine may trigger. Compares each
     * allergy the family profile lists (e.g. "Penicillin") with the
     * medicine's generic name and drug class ('allergens' in the seed), as
     * whole words, so "Shellfish" does not match fish oil.
     *
     * A warning, not a medical check: the pharmacist still reviews the order.
     *
     *   allergyMatches($amoxicillin, ['Penicillin', 'Peanuts'])  -> ['Penicillin']
     */
    public static function allergyMatches(array $medicine, array $allergies): array
    {
        $terms = array_map('strtolower', $medicine['allergens'] ?? []);
        $matches = [];

        foreach ($allergies as $allergy) {
            $allergy = trim((string) $allergy);
            if (mb_strlen($allergy) < 3) {
                continue;
            }
            foreach ($terms as $term) {
                $allergyHasTerm = preg_match('/\b' . preg_quote($term, '/') . 's?\b/i', $allergy);
                $termHasAllergy = preg_match('/\b' . preg_quote(strtolower($allergy), '/') . '\b/i', $term);
                if ($allergyHasTerm || $termHasAllergy) {
                    $matches[] = $allergy;
                    break;
                }
            }
        }
        return $matches;
    }

    /* ==================================================================
     * Pack size (see the note at the top of this file)
     * ================================================================== */

    /**
     * Pack details for a medicine OR an order line. Order lines keep their
     * own copy (snapshot); older lines without one are looked up by
     * medicine_id. Returns null if nothing is known.
     */
    private static function pack(array $row): ?array
    {
        if (!empty($row['pack_unit'])) {
            return [
                'unit' => (string) $row['pack_unit'],
                'size' => (int) ($row['pack_size'] ?? 0),
                'item' => (string) ($row['pack_item'] ?? ''),
            ];
        }
        if (isset($row['medicine_id'])) {
            $medicine = (new self())->find((int) $row['medicine_id']);
            if ($medicine && !empty($medicine['pack_unit'])) {
                return self::pack($medicine);
            }
        }
        return null;
    }

    /** The pack fields to copy onto an order line when the order is placed. */
    public static function packSnapshot(array $medicine): array
    {
        return [
            'pack_unit' => $medicine['pack_unit'] ?? null,
            'pack_size' => $medicine['pack_size'] ?? null,
            'pack_item' => $medicine['pack_item'] ?? null,
        ];
    }

    /** 'strip' for 1, 'strips' for anything else. */
    public static function unitName(array $row, int $qty = 1): string
    {
        $pack = self::pack($row);
        if ($pack === null) {
            return $qty === 1 ? 'pack' : 'packs';
        }
        if ($qty === 1) {
            return $pack['unit'];
        }
        return self::UNIT_PLURALS[$pack['unit']] ?? $pack['unit'] . 's';
    }

    /** "Strip of 10 tablets" - what one unit of quantity buys. */
    public static function packLabel(array $row): string
    {
        $pack = self::pack($row);
        if ($pack === null) {
            return '';
        }
        return ucfirst($pack['unit']) . ' of ' . $pack['size'] . ' ' . $pack['item'];
    }

    /** "20 tablets" - the total inside $qty packs. */
    public static function contentsFor(array $row, int $qty): string
    {
        $pack = self::pack($row);
        if ($pack === null || $pack['size'] < 1) {
            return '';
        }
        return ($pack['size'] * $qty) . ' ' . $pack['item'];
    }

    /** "2 strips (20 tablets)" - how every page shows a quantity. */
    public static function quantityLabel(array $row, int $qty): string
    {
        $contents = self::contentsFor($row, $qty);
        $label = $qty . ' ' . self::unitName($row, $qty);
        return $contents === '' ? $label : $label . ' (' . $contents . ')';
    }

    /** Medicines per category - the whole shop, or just the list given. */
    public static function categoryCounts(?array $items = null): array
    {
        $counts = [];
        foreach ($items ?? self::seed() as $m) {
            $counts[$m['category_id']] = ($counts[$m['category_id']] ?? 0) + 1;
        }
        return $counts;
    }

    /** Keep only the medicines in one category (used on search results). */
    public function inCategory(array $items, int $categoryId): array
    {
        return array_values(array_filter($items, fn($m) => $m['category_id'] === $categoryId));
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
