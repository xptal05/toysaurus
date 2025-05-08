<h1>Registrace člena do spolku Toysaurus, z. s.</h1>
<p>Děkujeme vám za zájem stát se členem spolku Toysaurus. Protože Toysaurus knihovna hraček není běžná půjčovna, ale funguje na bázi komunity, věnujte prosím pozornost před vyplněním registrace následujícím dokumentům:</p>
<ul>
  <li><a href="https://web.archive.org/web/20231130041211/https://drive.google.com/file/d/1KeESBFT02ycmNjZXzygUsHuz49rW8JwZ/view" target="_blank" rel="noreferrer noopener nofollow">Stanovy spolku,</a> kde se dočtete, s jakými cíli byl Toysaurus založen a jaká jsou obecná práva a povinnosti členů spolku.</li>
  <li>Knihovní řád, kde se dozvíte, jak funguje knihovna hraček.</li>
  <li>Ceník, kde se seznámíte s aktuální výší členských příspěvků.</li>
  <li>Ustanovení o ochraně osobních údajů, kde je napsáno, jak Toysaurus nakládá s informacemi, které spolku poskytnete.</li>
</ul>
<p>Máte-li ke členství otázky, navštivte sekci <a href="<?= BASE_FILE; ?>/faq">nejčastější dotazy</a>. Nenajdete-li odpověď, neváhejte nás <a href="<?= BASE_FILE; ?>/contact">kontaktovat</a>. Pokud je vše jasné a váš zájem stále trvá, velice se těšíme, že rozšíříte komunitu členů spolku Toysaurus!</p>
<form action="/web/20231130041211/https://www.toysaurus.cz/register" method="post">

  <div class="row col-parent">
    <div class="column">
      <div>
        <label for="firstname">Jméno <strong>*</strong></label>
        <input type="text" name="fname" required="" id="name" value="">
      </div>
      <div>
        <label for="lname">Příjmení <strong>*</strong></label>
        <input type="text" name="lname" required="" id="lastname" value="">
      </div>

      <div>
        <label for="bday">Datum narození <strong>*</strong></label>
        <input type="date" name="bday" required="" id="bday" value="">
      </div>

      <div>
        <label for="email">E-mail <strong>*</strong></label>
        <input type="email" name="email" required="" id="email" value="">
      </div>
      <div>
        <label for="telefon">Telefon <strong>*</strong></label>
        <input type="text" name="telefon" required="" id="telefon" value="">
      </div>
    </div>
    <div class="column">
      <div>
        <label>Adresa <strong>*</strong></label>
        <input name="ulice" type="text" placeholder="č.p. a ulice" required="" id="ulice" value="">
        <br>
        <input name="billing_city" type="text" placeholder="Město" required="" id="billing_city" value="">
        <br>
        <input name="zip" type="text" placeholder="PSČ" required="" id="zip" value="">
      </div>
      <br><br>
      <div>
        <label for="username">Přihlašovací jméno <strong>*</strong></label>
        <input type="text" name="username" value="" placeholder="Minimálně 4 znaky. Pouze písmena a čísla.">
      </div>

      <div>
        <label for="password">Heslo <strong>*</strong></label>
        <input type="password" name="password" value="" placeholder="Minimálně 5 znaků">
      </div>
      <div>
        <br>
      </div>
    </div>
  </div>

  <h2><label>Další informace</label></h2>
  <div class="row col-parent">
    <div class="column">
      <label>Jak jste se o nás dozvěděli?</label>
      <input name="odkud" type="text" placeholder="Např.: Google, Facebook, doporučení, atd." value="">
      <br>
      <label>Jak se můžete / chcete angažovat ve spolku?</label>
      <input name="pomoc" type="text" placeholder="Např.: pořádání akcí, služba v knihovně, atd." value="">
    </div>
    <div class="column">
      <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike" required="">
      <label for="vehicle1"><i>Tímto jako zájemce o členství ve smyslu stanov Toysaurus, z.s. (dále jen „spolek“), svobodně a dobrovolně stvrzuji, že jsem se seznámil/a a souhlasím s platnými <a href="https://web.archive.org/web/20231130041211/https://drive.google.com/file/d/1KeESBFT02ycmNjZXzygUsHuz49rW8JwZ/view">stanovami spolku</a>, jehož členem se chci stát.</i></label><br><br>
      <input type="checkbox" id="vehicle2" name="vehicle2" value="Bike" required="">
      <label for="vehicle2"><i>Souhlasím s knihovním řádem.</i></label><br><br>
      <input type="radio" name="entry.1685809713Q" value="Ano" required=""><label><i>&nbsp;Souhlasím&nbsp;&nbsp;&nbsp;</i></label>
      <input type="radio" name="entry.1685809713Q" value="Ne">
      <label><i>&nbsp;Nesouhlasím</i></label>
      <label for="vehicle1"><i> se správou, zpracováním a uchováváním mých osobních údajů pro vnitřní potřeby spolku.</i></label><br><br>
      <input type="radio" name="entry.1685809713" value="Ano" required=""><label><i>&nbsp;Souhlasím&nbsp;&nbsp;&nbsp;</i></label>
      <input type="radio" name="entry.1685809713" value="Ne">
      <label><i>&nbsp;Nesouhlasím</i></label>
      <label for="entry.1074398510"><i>se zpracováním osobních údajů pro účely marketingu.
        </i></label>
    </div>
  </div>
  <div align="center"><br>
    <input type="submit" name="submit" value="Registrovat se" class="button">
  </div>
</form>