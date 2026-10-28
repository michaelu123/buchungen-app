<!DOCTYPE html>
<html>

<head>
  <base target="_top" />
</head>

<body>
  <p>
    @foreach($lehrer as $lr)
      Hallo {{$lr}},<br>
    @endforeach
  </p>
  <p>
    hier ist die Begrüßungs-Email für Deinen Kurs unten. Bitte korrigiere vor dem Versenden noch die
    xxxxxx-Stellen.<br>
    Viele Grüsse und einen schönen Tag,<br>
    Asja<br>
    ..............................................................................................
  </p>

  <p>
    (please see English version below)<br>

    Liebe Kursteilnehmer:innen,<br>

    Am {{ $startdatum }} startet unser Kurs:<br><br>
    <strong>{{ $kursDetails }}</strong><br><br>

    Die Ersatztermine bitte ebenfalls freihalten!<br>

    @if (str_contains($kurs->nummer, "RIEM"))
      Der Kurs findet in Riem statt. Treffpunkt ist die Willy-Brandt-Allee 26 / Ecke Heinrich-Böll-Str., 81829
      München.<br />
      Die nächste U-Bahn-Haltestelle ist die “Messestadt Ost", Ausgang „Heinrich-Böll-Str.“
      <a href="https://w3w.co/herd.ansprechend.lebhaft" style="text-decoration: underline;"> (Karte)</a><br>
    @else
      Der Kurs findet auf der Theresienwiese statt.
      Treffpunkt ist das ADFC Radlerhaus, Platenstraße 4, 80336 München
      <a href="https://w3w.co/neuerung.faden.meinen" style="text-decoration: underline;"> (Karte)</a><br>
    @endif

    Bitte sei spätestens 15 Minuten vor Kursbeginn am Treffpunkt. Dann können wir pünktlich mit dem Radeln anfangen.<br>

    Wir, Eure ADFC Radfahrlehrer:innen xxxxxx freuen uns schon auf Euch.<br>

    Bei kurzfristigen Änderungen, z. B. Krankheit oder Verspätung schreibt mir bitte eine E-Mail (Antwort an diese
    Adresse) oder ruft mich an unter: xxxxxx<br>

    Was müsst Ihr mitbringen?<br>
  <ul>
    <li>
      Ihr benötigt Kleidung, in der Ihr Euch gut und bequem bewegen könnt, am besten dunkle Hosen, die eng am Bein
      anliegen.
    </li>
    <li>
      Vor allem feste Schuhe ohne Absätze sind wichtig, damit Ihr auf den Pedalen Halt findet.
    </li>
    <li>
      Wir sind mehrere Stunden an der frischen Luft. Bitte zieht Euch dem Wetter entsprechend an (bei Regen: Regenjacke
      und Regenhose, bei Sonne: Sonnenschutz). Auf der Theresienwiese ist es häufig sehr windig, ggf. auch an eine Jacke
      zum Überziehen denken. Bei starkem Regen wird die Schulung unterbrochen.
    </li>
    <li>
      Wenn Ihr schon einen eigenen Fahrradhelm besitzt, bringt ihn gerne mit. Ansonsten leihen wir Euch einen Helm.
    </li>
    <li>
      Bringt Euch ein Getränk und etwas zu Essen mit. Wir haben große Taschen dabei und transportieren den Proviant
      für Euch. Wir machen Pausen.
    </li>
  </ul><br>

  Das Schulungsfahrrad bekommt Ihr von uns gestellt. Eure Wertsachen können wir im Radlerhaus einschließen.<br>

  Weitere <a href="https://muenchen.adfc.de/artikel/tipps-fuer-teilnehmende-an-adfc-radfahrkursen-fuer-anfaengerinnen"
    style="text-decoration: underline;">Tipps</a>,
  unsere <a href="https://muenchen.adfc.de/artikel/teilnahmebedingungen-fuer-alle-kurse-der-radfahrschule"
    style="text-decoration: underline;">
    Teilnahmebedingungen</a>
  und die <a href="https://muenchen.adfc.de/artikel/kursangebot-fuer-anfaengerinnen-3"
    style="text-decoration: underline;">ausführliche Kursbeschreibung</a>
  könnt Ihr auf unserer Homepage nochmal nachlesen. Dort ist auch eine <a
    href="https://muenchen.adfc.de/artikel/ag-soziales" style="text-decoration: underline;">Zusammenfassung der
    Verkehrsregeln für Radler:innen</a> in vielen Sprachen
  verfügbar (blaue Box).<br>

  Bitte schreibt mir, wenn Ihr noch Fragen habt.<br>

  Bis bald,<br>

  xxxxxx + Telefonnummer xxxxxx<br>

  Allgemeiner Deutscher Fahrrad-Club (ADFC) Kreisverband München e.V.<br>

  Postanschrift: Platenstraße 4, 80336 München<br>
  Eingetragen im Vereinsregister München, VR 16930<br>
  <a href="https://muenchen.adfc.de/radfahrschule"
    style="text-decoration: underline;">https://muenchen.adfc.de/radfahrschule</a><br>




  <br><br>
  —------------------------------------------------<br><br><br>
  Dear participants,<br>

  Our bicycle training will start soon. Your instructors are looking forward to meeting you. Our first training is on
  {{ $startdate }}. <br><br>
  <strong>{{ $kursDetails_en }}</strong><br><br>

  Please keep the replacement days free as well!<br>

  @if (str_contains($kurs->nummer, "RIEM"))
    The course takes place in Riem. The meeting point is Willy-Brandt-Allee 26 / Corner Heinrich-Böll-Str., 81829
    München.<br />
    The next U-Bahn-station is “Messestadt Ost", Exit „Heinrich-Böll-Str.“
    <a href="https://w3w.co/herd.ansprechend.lebhaft" style="text-decoration: underline;"> (Map)</a><br>
  @else
    The course takes place at the Theresienwiese.
    The meeting point is the ADFC Radlerhaus, Platenstraße 4, 80336 München
    <a href="https://w3w.co/neuerung.faden.meinen" style="text-decoration: underline;"> (Map)</a><br>
  @endif

  Please arrive at least 15 minutes before the course starts.<br>

  If you cannot attend, please call me on xxxxxx, or write an email (answer to this address).<br>
  If you are delayed, please give me a call, too.<br>

  Please bring along:
  <ul>
    <li>
      workout clothes, wear either jeans, sweatpants or biking shorts.
    </li>
    <li>
      proper shoes without heels for grip on the pedals
    </li>
    <li>
      we will be outdoors for several hours. Please dress according to the weather forecast: rain jacket and rain
      trousers in the case of a light rain/ sunscreen in the case of the sun. Often it’s pretty windy at the training
      site. If it rains heavily, we will take a break and find a good place to stay dry.
    </li>
    <li>
      if you already own a bicycle helmet, please bring it. Otherwise you can borrow a helmet from us.
    </li>
    <li>
      something to drink and some food. We will carry it for you in our bags. We will make breaks.
    </li>
  </ul>
  You will receive a suitable training bike at our office (lending fees for bike and helmet are already included in
  the course fee). We can lock your valuable items (smartphones, money, handbags) in our office.<br>

  Please click here for <a
    href="https://muenchen.adfc.de/artikel/tipps-fuer-teilnehmende-an-adfc-radfahrkursen-fuer-anfaengerinnen"
    style="text-decoration: underline;">tips for the beginners (in German)</a>, the <a
    href="https://muenchen.adfc.de/artikel/kursangebot-fuer-anfaengerinnen-3" style="text-decoration: underline;">course
    description</a> and our <a
    href="https://muenchen.adfc.de/artikel/teilnahmebedingungen-fuer-alle-kurse-der-radfahrschule"
    style="text-decoration: underline;">terms and conditions</a> or
  visit our website. You can find the summary of german traffic regulations for bikers in different languages <a
    href="https://muenchen.adfc.de/artikel/ag-soziales" style="text-decoration: underline;">here</a>
  (blue box on the right side).<br>

  If you have any further questions, don't hesitate to write an email to me.<br>

  See you soon<br>


  xxxxxx + telefon number xxxxxx<br>

  Allgemeiner Deutscher Fahrrad-Club (ADFC) Kreisverband München e.V.<br>

  Postanschrift: Platenstraße 4, 80336 München<br>
  Eingetragen im Vereinsregister München, VR 16930<br>
  <a href="https://muenchen.adfc.de/radfahrschule"
    style="text-decoration: underline;">https://muenchen.adfc.de/radfahrschule</a><br>

  </p>

</body>

</html>