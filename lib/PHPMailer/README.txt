PHPMailer is used for order-confirmation emails but its source isn't bundled
here (this environment's sandbox couldn't reach GitHub to download it).

To enable email sending:

1. Download the official PHPMailer library (no Composer needed):
   https://github.com/PHPMailer/PHPMailer  (use the "Download ZIP" button,
   or grab the `src/` folder from a tagged release, e.g. v6.9.x)

2. Copy these 3 files into this folder as:
     lib/PHPMailer/src/PHPMailer.php
     lib/PHPMailer/src/SMTP.php
     lib/PHPMailer/src/Exception.php

3. Fill in real SMTP credentials in config/config.php (MAIL_HOST,
   MAIL_USERNAME, MAIL_PASSWORD, etc).

app/Core/Mailer.php already requires these files and sends the order
confirmation email — it checks whether they exist first, so the app keeps
working (just skips emailing) until you add them.
