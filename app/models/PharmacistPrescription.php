<?php
/**
 * PharmacistPrescription - read-only prescription lists for the Pharmacist
 * queue and history screens.
 *
 * Tables: prescriptions, users, family_members, online_orders, payments.
 * Every value comes from a real column or a join - nothing is invented.
 * The patient is the family member when the prescription is for one,
 * otherwise the customer who uploaded it.
 */
class PharmacistPrescription extends Model
{
    /** Prescriptions still waiting for the pharmacist, newest first. */
    public function getPendingQueue(): array
    {
        return $this->fetchAll(
            "SELECT p.prescription_id,
                    p.status,
                    p.uploaded_at AS uploaded_date,
                    IF(p.patient_type = 'Family_Member', fm.full_name, u.full_name) AS patient_name
               FROM prescriptions p
               JOIN users u ON u.user_id = p.customer_id
               LEFT JOIN family_members fm ON fm.family_member_id = p.family_member_id
              WHERE p.status = 'Pending'
              ORDER BY p.prescription_id DESC"
        );
    }

    /**
     * Every prescription for the history screen. total_amount and
     * payment_method come from the online order made from the prescription;
     * they are NULL when no order exists yet.
     */
    public function getAllForHistory(): array
    {
        return $this->fetchAll(
            "SELECT p.prescription_id,
                    p.status,
                    p.uploaded_at AS order_date,
                    IF(p.patient_type = 'Family_Member', fm.full_name, u.full_name) AS patient_name,
                    oo.total_amount,
                    pay.payment_method
               FROM prescriptions p
               JOIN users u ON u.user_id = p.customer_id
               LEFT JOIN family_members fm ON fm.family_member_id = p.family_member_id
               LEFT JOIN online_orders oo ON oo.prescription_id = p.prescription_id
               LEFT JOIN payments pay ON pay.online_order_id = oo.order_id
              ORDER BY p.prescription_id DESC"
        );
    }
}
