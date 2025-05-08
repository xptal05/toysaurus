Web neziskové organizace Půjčovna hraček se statickou částí, s e-shopem (výpůjčky) a s klientským a admin rozhraním. Aplikace automatizuje výpůjčky a následné vratky, propojení s APP Sheet (scan hraček), ale také s PACKETA (zaslání zásilek přes zásilkovnu) a STRIPE (platby kartou).

V budoucnu se plánuje možnost přihlášení přes FIREBASE.

Technologie: PHP (OOP), JS, HTML, CSS, DB v MySQL + JSON
Knihovny: PHP Mailer, Packeta API, Stripe API


TO DO

- main page - css layout
- o nás css
- jak to funguje css
- nejčastější dotazy css
- katalog hraček
  - filters agregate from diferent categories
  - seřadit od....
  - přidat do košíku pouze, když je přihlášený uživatel
  - error when adding the first item to cart after login
  - animation add to cart -> from btn to cart
- Product page
  - animation add to cart -> from btn to cart
  - css
  - předchozí Product
  - similar produkts
- Cart
  - unable to click on proceed if the toysaurus points are insuficient
- Checkout
  - zásilkovna plugin - pop up window
  - links
  - checkout btn - if bacs -> direct to a page recap
  - checkou btn - if stripe -> verify functionality -> direct to recap page
- LOGIN page - swith to register - maybe a popup?
- Darovat hračky - css + google forms
- vše o členství - css
- stát se členem
  - links
  - css
  - add a new client php
- podpořte nás
  - css
- sponzorství
  - css
- footer
  - css
  - links
  - givt
- Rezervovat si termín
- kontakt form -> google forms + send email? php mailer?
- header - toysaurus points only visible if logged in

MY ACCOUNT

- dashbord
  - css
  - stav účtu
- moje členství
  - invoices
  - změnit členství / zrušit členství
- půjčené hračky
  - form to send back toys
- redo Photo.php to Media.php and delet photo
  TRANSLATION TO ENGLISH and FRENCH

ADMIN

- finnish media uplaod
- ORDER STATUS
  DRAFT -> NEW ORDER
