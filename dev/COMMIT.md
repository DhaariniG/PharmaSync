# Commit notes — feature/Customer

## This commit

```
feat(customer): MySQL for the customer CRUD

database/003_add_customer_profile_tables.sql now imports on its own:
it creates the pharmasync database, users and customer_profiles (only if
missing, same definitions as 001_schema.sql), the demo accounts
(INSERT IGNORE), and rebuilds family_members,
family_member_health_flags and addresses with demo rows. Running it
after the full schema skips the account tables, so nothing clashes.

New flag CUSTOMER_DB_ENABLED (default false) puts FamilyMember, Address
and User on MySQL while the rest of the project stays on sample data
(UsesCustomerDatabase trait overrides Model::db() / hasDb()).

- New accounts get their 'Self' family member created automatically
- 001a_seed_demo_users.sql: added USE pharmasync (failed on import
  without a selected database)
- README: import steps and table diagram
```

new:      app/models/UsesCustomerDatabase.php
modified: database/003_add_customer_profile_tables.sql
modified: database/001a_seed_demo_users.sql
modified: app/models/FamilyMember.php
modified: app/models/Address.php
modified: app/models/User.php
modified: config/config.php          (SHARED - adds CUSTOMER_DB_ENABLED, default false)
modified: README.md, dev/COMMIT.md

## Commands

1. In your clone: `git checkout feature/Customer`
2. Unzip this zip into a NEW folder and move the hidden `.git` folder from
   the old clone into the new `PharmaSync` folder.
3. In the new folder:

```
git add -A
git status
git commit          # paste the message above
git push origin feature/Customer
```

## Check before pushing

1. phpMyAdmin > Import > database/003_add_customer_profile_tables.sql
2. config/config.php: CUSTOMER_DB_ENABLED = true
3. Log in as customer@pharmasync.com / Demo@1234 > Profile:
   add, edit and delete a family member, add and remove an allergy,
   add, edit, set default and delete an address.
   Each change appears in phpMyAdmin.
4. Set CUSTOMER_DB_ENABLED back to false before committing, unless the
   group has agreed everyone imports the file.
