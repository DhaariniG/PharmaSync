<?php

/**
 * DeliverySlot - the date and time window a customer picks for home
 * delivery at checkout. Owner: customer module.
 *
 * Each day has four windows. A window is:
 *   available  can be booked
 *   full       CAPACITY orders are already booked into it
 *   past       starts less than LEAD_HOURS from now (no time to pack and send)
 *   closed     the store is shut then (Sunday evening - see STORE_HOURS -
 *              or a day in CLOSED_DATES)
 *
 * A slot is posted from the checkout form as one string, "Y-m-d|H:i",
 * e.g. "2026-09-29|09:00". Never trust it: checkout calls findBookable(),
 * which rebuilds the schedule and only accepts a window that is available
 * right now.
 *
 * Bookings are counted from orders (Order::countForDeliverySlot). While
 * orders live in the session, one browser only sees its own orders, so
 * otherBookings() adds a fixed sample number per window to stand in for
 * other customers. When orders move to MySQL, delete otherBookings() and
 * count straight from the orders table instead - the rest stays the same.
 * That needs two columns on online_orders: delivery_date DATE and
 * delivery_window TIME (the window's start). Group decision, not done here.
 */
class DeliverySlot extends Model
{
    /** Delivery windows, the same every day. 13:00-14:00 is the riders' lunch. */
    public const WINDOWS = [
        ['start' => '09:00', 'end' => '11:00'],
        ['start' => '11:00', 'end' => '13:00'],
        ['start' => '14:00', 'end' => '16:00'],
        ['start' => '17:00', 'end' => '19:00'],
    ];

    /** Orders one window can take - riders and vehicles are limited. */
    public const CAPACITY = 5;

    /** How many days the customer can choose from, starting today. */
    public const DAYS_SHOWN = 7;

    /** A window can be booked until this many hours before it starts. */
    public const LEAD_HOURS = 2;

    /**
     * Orders with prescription medicine get a pharmacist check before they
     * leave, so they need a longer lead time.
     */
    public const LEAD_HOURS_RX = 4;

    /**
     * Days the store does not deliver at all, 'Y-m-d' => reason. The store's
     * own decision - add public holidays or closures here.
     */
    public const CLOSED_DATES = [
        '2026-12-25' => 'Christmas Day',
    ];

    /** The store closes at 5 PM on Sunday, so no window may end after this. */
    public const SUNDAY_CLOSE = '17:00';

    /**
     * The days the customer can pick from, each with its windows.
     *
     *   [ ['date' => '2026-09-29', 'free' => 3, 'windows' => [
     *        ['value' => '2026-09-29|09:00', 'date' => ..., 'start' => '09:00',
     *         'end' => '11:00', 'status' => 'available', 'left' => 2], ...
     *   ]], ... ]
     *
     * A day whose windows have all passed (today, late in the evening) is
     * skipped, so the list always holds DAYS_SHOWN days.
     */
    public function schedule(?int $now = null, int $leadHours = self::LEAD_HOURS): array
    {
        $now  = $now ?? time();
        $days = [];

        for ($i = 0; count($days) < self::DAYS_SHOWN && $i <= self::DAYS_SHOWN; $i++) {
            $date    = date('Y-m-d', strtotime("+$i day", $now));
            $windows = [];
            $free    = 0;
            $allPast = true;

            foreach (self::WINDOWS as $index => $w) {
                $window = $this->window($date, $index, $now, $leadHours);
                $windows[] = $window;

                if ($window['status'] !== 'past') {
                    $allPast = false;
                }
                if ($window['status'] === 'available') {
                    $free++;
                }
            }

            if ($allPast) {
                continue;
            }

            $days[] = ['date' => $date, 'free' => $free, 'windows' => $windows];
        }

        return $days;
    }

    /**
     * The window for a posted "Y-m-d|H:i" value, but only if it can be
     * booked right now. Null for anything else - a made-up date, a time
     * that is not one of our windows, a full or past window.
     */
    public function findBookable(string $value, ?int $now = null, int $leadHours = self::LEAD_HOURS): ?array
    {
        foreach ($this->schedule($now, $leadHours) as $day) {
            foreach ($day['windows'] as $window) {
                if ($window['value'] === $value) {
                    return $window['status'] === 'available' ? $window : null;
                }
            }
        }
        return null;
    }

    /** The first window that can be booked - pre-selected at checkout. */
    public function firstAvailable(array $schedule): ?string
    {
        foreach ($schedule as $day) {
            foreach ($day['windows'] as $window) {
                if ($window['status'] === 'available') {
                    return $window['value'];
                }
            }
        }
        return null;
    }

    /**
     * How a saved slot reads on the order pages.
     *   ['date' => '2026-09-29', 'start' => '09:00', 'end' => '11:00']
     *   -> "Tue, 29 Sep 2026, 09:00 – 11:00"
     * Null for orders placed before slots existed.
     */
    public static function describe(?array $slot, string $dateFormat = 'D, j M Y'): ?string
    {
        if (empty($slot['date']) || empty($slot['start']) || empty($slot['end'])) {
            return null;
        }
        return date($dateFormat, strtotime($slot['date'])) . ', ' . $slot['start'] . ' – ' . $slot['end'];
    }

    /* ------------------------------------------------------------------ */

    private function window(string $date, int $index, int $now, int $leadHours): array
    {
        $w     = self::WINDOWS[$index];
        $start = strtotime($date . ' ' . $w['start']);
        $left  = max(0, self::CAPACITY - $this->bookings($date, $index));

        if (isset(self::CLOSED_DATES[$date])
            || (date('w', $start) === '0' && $w['end'] > self::SUNDAY_CLOSE)) {
            $status = 'closed';
        } elseif ($start - $leadHours * 3600 < $now) {
            $status = 'past';
        } elseif ($left === 0) {
            $status = 'full';
        } else {
            $status = 'available';
        }

        return [
            'value'  => $date . '|' . $w['start'],
            'date'   => $date,
            'start'  => $w['start'],
            'end'    => $w['end'],
            'status' => $status,
            'left'   => $left,
            'closed_reason' => self::CLOSED_DATES[$date] ?? null,
        ];
    }

    /** Orders already booked into one window. */
    private function bookings(string $date, int $index): int
    {
        $start = self::WINDOWS[$index]['start'];
        return (new Order())->countForDeliverySlot($date, $start) + $this->otherBookings($date, $index);
    }

    /**
     * SAMPLE DATA - other customers' bookings, until orders are in MySQL.
     * A fixed number per date and window (crc32 gives the same number every
     * time), so the page is stable between reloads. Evenings fill up first,
     * like a real after-work rush, which is where "Fully Booked" shows up.
     */
    private function otherBookings(string $date, int $index): int
    {
        $rush = [1, 0, 1, 3][$index] ?? 0;
        return min(self::CAPACITY, crc32($date . '#' . $index) % self::CAPACITY + $rush);
    }
}
