# Family member + address CRUD — install notes

Customer module (Mithun). Drop these six files into the repo at the paths shown;
five replace an existing file, two are new.

| File | Action |
|---|---|
| `app/models/FamilyMember.php` | replace |
| `app/models/Address.php` | replace |
| `app/controllers/CustomerProfileController.php` | replace |
| `config/routes/customer.php` | replace (only the Profile block changed) |
| `app/views/customer/profile/index.php` | replace |
| `app/views/customer/profile/member.php` | **new** |
| `database/003_add_customer_profile_tables.sql` | **new** |

Nothing outside the customer module changes. `config/config.php` is untouched —
the models fill in any key an older browser session is missing, so no
`SEED_VERSION` bump and no merge conflict with the rest of the group.

## What it does

**Family member — the four CRUD operations**

| | Route | Method |
|---|---|---|
| Create | `POST /customer/profile/add-member` | `addFamilyMember()` |
| Read | `GET /customer/profile` and `GET /customer/profile/member/{id}` | `show()`, `editMember()` |
| Update | `POST /customer/profile/update-member/{id}` | `updateFamilyMember()` |
| Delete | `POST /customer/profile/delete-member/{id}` | `deleteFamilyMember()` |

**Address — the four CRUD operations**

| | Route | Method |
|---|---|---|
| Create | `POST /customer/profile/add-address` | `addAddress()` |
| Read | `GET /customer/profile` | `show()` |
| Update | `POST /customer/profile/update-address/{id}` | `updateAddress()` |
| Delete | `POST /customer/profile/delete-address/{id}` | `deleteAddress()` |

Plus `POST /customer/profile/default-address/{id}` to choose the address
checkout pre-selects.

**Health flags — allergies and chronic conditions, per family member**

Each person has their own list, not one list for the whole account, so a
prescription uploaded for your father is checked against *his* allergies.

- `POST /customer/profile/member/{id}/add-flag` — add (`type` is `allergy` or `condition`)
- `POST /customer/profile/delete-flag` — remove (`member_id` + `flag_id`)
- `POST /customer/profile/add-allergy` and `/add-condition` — unchanged routes,
  kept so the Health Profile card at the top of the page still works; they now
  write to the `Self` member, which is the account holder's own record.

## Rules the code enforces

- The account holder (`relationship = 'Self'`) cannot be deleted, cannot be
  demoted, and nobody else can be promoted into the role.
- A family member with prescriptions already on file cannot be deleted — the
  page says why instead of failing silently.
- The same allergy cannot be recorded twice for the same person
  (case-insensitive; `uq_member_flag` enforces it in MySQL too).
- Deleting the default address promotes the next one, so checkout always has a
  selection.
- Every id is scoped by `user_id`, so editing `/profile/member/9` when member 9
  belongs to another account returns "not found", not their data.
- Every POST verifies CSRF; every model method uses prepared statements.

## Switching to MySQL

Both models already contain the SQL path behind `hasDb()`. To demo real
persistence:

1. Import `database/001_schema.sql`, then `002_add_cart_tables.sql`, then
   `003_add_customer_profile_tables.sql` in phpMyAdmin.
2. Set `define('DB_ENABLED', true);` in `config/config.php`.

Only the models that check `hasDb()` change behaviour, so the other roles keep
running on their sample data. Flip it back to `false` if the demo machine has no
database.

## Demo path for the evaluation

Profile → Family Profiles → **Add family member** (Create) → **Manage** (Read) →
edit the name and save (Update) → add an allergy and a condition, delete one →
**Delete profile** (Delete). Then Saved Addresses: add, edit, make default,
delete.
