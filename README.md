# PharmaSync

Pharmacy management system. Group 46, Academic Year 2026/2027.

PHP with MVC, MySQL, XAMPP. **No frameworks, no CDN links.**

---

## Setup

1. Clone into `C:\xampp\htdocs\`. The folder can be named anything -
   `BASE_URL` is detected automatically.
2. Start Apache and MySQL in the XAMPP control panel.
3. Open `http://localhost/<your-folder-name>/public`

Every role already has a working starter page at `/<yourrole>/dashboard`.
Set `AUTO_SIGN_IN_DEMO_ROLE` in `config/config.php` to your own role first.

The project runs immediately on sample data - `DB_ENABLED` is `false`, so no
database import is needed yet. When the group is ready for MySQL, import
`database/001_schema.sql` then `database/002_add_cart_tables.sql` in
phpMyAdmin and flip `DB_ENABLED` to `true`.

If every page 404s, `mod_rewrite` is off. Open `C:\xampp\apache\conf\httpd.conf`,
uncomment `LoadModule rewrite_module modules/mod_rewrite.so`, set
`AllowOverride All`, restart Apache.

**Do not edit `config/config.php` for your own machine.** If something there
is wrong, it is wrong for everyone - tell the group.

---

## Which files are yours

```
config/
  config.php           SHARED. Group agreement only.
  helpers.php          SHARED. Add a helper here only if all five roles use it.
  routes.php           SHARED loader. You never edit this.
  routes/
    customer.php       Mithun
    pharmacist.php     <name>
    admin.php          <name>
    inventoryManager.php  <name>
    deliveryPartner.php   <name>
    authentication.php    <name>
    shared.php         SHARED - the site root only

app/
  core/                FROZEN. Router, Controller, Model, Database, Session,
                       Mailer. Never edit these on a feature branch.
  controllers/         FLAT. Your files are <Role><Feature>Controller.php
  models/              FLAT. Shared - see "Models are shared" below.
  views/
    layouts/           shared pieces
    errors/            404, 403
    authentication/    login, register
    customer/          Mithun
    pharmacist/        <name>
    admin/             <name>
    InventoryManager/  <name>
    deliveryPartner/   <name>

public/
  index.php            Front controller. Every request enters here.
  assets/
    css/main.css       SHARED base styles
    css/<Role>/        Your own stylesheets. PascalCase folder.
    icons/             Lucide SVGs, inlined by icon(). Shared.
    images/, js/, videos/

database/              SQL files, numbered in run order.
storage/prescriptions/ Uploads. NOT reachable from a browser.
lib/                   Third-party code. See "PHPMailer" below.
dev/                   Scratch space. Notes, sketches, throwaway scripts.
```

Controllers and models are **flat**, not in role folders. The role is part of
the filename: `CustomerCartController.php`, `PharmacistQueueController.php`.
This matches what is already committed to GitHub.

---

## URLs

Every page lives under its role:

```
/customer/cart              /pharmacist/queue
/customer/orders/17         /admin/users
/InventoryManager/stock     /deliveryPartner/routes
/authentication/login
```

That prefix is the whole point. Without it, five people's `/dashboard`,
`/notifications` and `/settings` routes overwrite each other the day we merge.

You do **not** type the prefix. In `config/routes/<yours>.php` you write:

```php
'GET  /dashboard'     => ['PharmacistDashboardController', 'index'],
'GET  /orders/{id}'   => ['PharmacistOrderController', 'show'],
```

and the loader turns those into `/pharmacist/dashboard` and
`/pharmacist/orders/17`. `{id}` is passed to the method as an argument.

**The first matching route wins**, so put longer paths above shorter ones.

---

## Adding a page - the whole loop

**1. Route** - in your own file, `config/routes/<yours>.php`:

```php
'GET  /stock/{id}' => ['InventoryManagerStockController', 'show'],
```

**2. Controller** - `app/controllers/InventoryManagerStockController.php`:

```php
class InventoryManagerStockController extends Controller
{
    protected string $viewBase = 'InventoryManager';   // your views folder

    public function show(int $id): void
    {
        $this->requireRole('Inventory_Manager');       // first line, always

        $batch = (new StockBatch())->find($id);

        if (!$batch) {
            $this->notFound();
        }

        $this->render('stock/show', ['batch' => $batch]);
    }
}
```

**3. View** - `app/views/InventoryManager/stock/show.php`. Print with `e()`:

```php
<h1><?= e($batch['medicine_name']) ?></h1>
<p><?= money($batch['unit_price']) ?> - expires <?= dt($batch['expiry_date'], 'd M Y') ?></p>
<a href="<?= url('/InventoryManager/stock') ?>">Back to stock</a>
```

`render()` wraps your page in `app/views/<yourfolder>/partials/header.php` and
`footer.php` if those two files exist, and renders the page on its own if they
do not. So build your layout whenever you like. Use `renderBare()` for print
pages and AJAX fragments.

---

## Rules that stop merge conflicts

**Never hardcode a URL.** Use `url('/customer/cart')` and
`asset('assets/css/main.css')`. Hardcoded paths break the moment someone
clones into a differently named folder.

**Never edit `app/core/`.** If you need something from it, ask the group and
it gets changed once, on `develop`.

**Every POST form** carries `<?= csrf_field() ?>`, and **every POST handler**
starts with `$this->verifyCsrf();`. The field is named `_csrf`.

**Every page method** starts with `$this->requireRole('Your_Role')` - the
database spelling (`Inventory_Manager`), not the URL spelling. That is what
stops a customer from opening the admin pages.

**All SQL lives in models.** Never in a controller, never in a view. Always a
prepared statement, never a variable concatenated into a query string.

**Escape everything you print** with `e()`. Forgetting it is how XSS happens.

---

## Sessions and the signed-in user

One array, `$_SESSION['user']`, holding at least `id`, `role` and `name`.
Read it through `Session`, not directly:

```php
Session::isLoggedIn();    Session::user();     Session::id();
Session::role();          Session::roleSlug(); Session::name();
```

Inside a controller, `$this->currentUser()` gives you the same array.

Authentication is a **stub**. `AuthenticationController` signs in a demo user
per role so every module can be opened today. `AUTO_SIGN_IN_DEMO_USER` in
`config.php` signs you in automatically - set `AUTO_SIGN_IN_DEMO_ROLE` to
your own role while you work. Whoever builds real login only has to hand
`Session::login()` an array with `id`, `role` and `name`; nothing else in the
project changes.

---

## Models are shared

`app/models/` is flat and shared on purpose. `Medicine` is the same medicine
for the customer browsing it, the pharmacist dispensing it and the inventory
manager restocking it. Before writing a new model, check whether one already
exists and add a method to it instead of writing a second one beside it.

`User` is the only model in here so far, and it is the worked example - read
it before writing your first one. Note how it aliases `user_id` to `id` and
`full_name` to `name` inside the model, so the rest of the project never has
to remember that difference. Do the same in yours.

More models arrive as branches merge. The customer branch brings `Medicine`,
`Order`, `Prescription`, `Notification`, `Cart`, `Address`, `FamilyMember`
and `Customer`. If you need one of those before it lands, say so in the group
rather than writing your own copy.

The base `Model` gives you `fetchAll()`, `fetchOne()`, `fetchValue()`,
`exec()`, `insertRow()` and `updateRow()`. They are named that way, and not
`all()` / `one()` / `insert()` / `update()`, because models already use the
short names for their own business methods and PHP will not allow a child
class to redeclare a parent method with a different signature. Do not rename
them back.

Rows come back as **associative arrays** (`$row['name']`), not objects. Every
view in the project is written that way.

---

## Sample data while there is no database

`DB_ENABLED` is `false`, so `Model::db()` returns `null` and each model seeds
its own sample data into the session. Two things to remember:

- List your session keys in `SEEDED_SESSION_KEYS` in `config.php`.
- **Bump `SEED_VERSION`** whenever you change the shape of a seed array.
  Otherwise an old browser session keeps serving the old shape and fields you
  just added come out blank. There is no logout button to clear it by hand.

Keep your sample data shaped like the columns in `database/001_schema.sql`,
so that switching `DB_ENABLED` on later changes the model and nothing else.

---

## Icons

`icon('shopping-cart')` inlines an SVG from `public/assets/icons/`. No icon
font, no JavaScript, no CDN. They inherit the surrounding text colour and are
sized in `em`. An unknown name renders nothing rather than a broken box.
Drop new Lucide SVGs into that folder to use them.

---

## Open group decisions

1. **Database schema.** `database/001_schema.sql` is the shared one: `users`
   plus role profile tables, stock, purchase orders, deliveries. The customer
   branch was built against an earlier customer-only draft whose table names
   differ, so the two have to be reconciled before anyone turns `DB_ENABLED`
   on. Shape your sample data like the columns in `001_schema.sql` and you
   will not be affected.
2. **PHPMailer.** `lib/PHPMailer/` holds a README, not the library. `Mailer`
   checks for the files and is also gated behind `MAIL_ENABLED` (false), so
   checkout works with email simply skipped. Decide whether we bundle the
   library or drop back to `mail()`.
3. **Route file owners.** Each file in `config/routes/` says
   `Owner: <put your name here>`. Fill yours in.
