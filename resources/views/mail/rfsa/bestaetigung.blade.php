<!DOCTYPE html>
<html>

<head>
  <base target="_top" />
</head>

<body>
  <p>{{ $anrede }},</p>
  <p>
    hiermit bestätigen wir Ihnen, daß Sie für den Radfahrkurs <br />
    &nbsp;&nbsp;&nbsp;{{ $kursDetails }}<br>
    verbindlich angemeldet sind.
  </p>
  <p>
    {{ $zahlungsText }}
  </p>

  <p>
    @if (str_contains($kurs->name, "RIEM"))
      Ihr Kurs findet in Riem statt. Treffpunkt ist Willy-Brandt-Allee 26 / Ecke Heinrich-Böll-Str., 81829 München.<br />
      Die nächste U-Bahn-Haltestelle ist die “Messestadt Ost", Ausgang „Heinrich-Böll-Str.“<br />
      <a href="https://w3w.co/herd.ansprechend.lebhaft">Karte</a><br />
    @else
      Ihr Kurs findet auf der Theresienwiese statt.
      Treffpunkt ist das ADFC Radlerhaus, Platenstraße 4, 80336 München
      <a href="https://w3w.co/neuerung.faden.meinen"> (Karte)</a>
    @endif
  </p>
  <p>
    <b>
      Achtung: Falls der Trainer oder die Trainerin einen oder mehrere Termine wegen sehr schlechtem
      Wetter verschieben, wird das Training am Ersatztermin nachgeholt. Bei schlechtem Wetter
      entscheiden die Trainer, ob der Kurs verschoben wird.<br />
    </b>
  </p>
  <p>
    Von den Teilnahmebedingungen haben Sie bereits mit Ihrer Anmeldung
    Kenntnis genommen. Anregungen und Tipps für Teilnehmende sind im
    Anhang dieser Mail beigefügt. Bitte lesen Sie die
    Teilnahmebedingungen und die Tipps für Teilnehmende vor Kursbeginn
    nochmal kurz durch und seien Sie bitte am ersten Kurstag möglichst
    etwas früher da.
  </p>
  <p>
    Von Ihren Kursleitern werden Sie einige Tage vor Kursbeginn nochmals angeschrieben.<br />
  </p>
  <p>
    Drei Monate nach dem letzten Trainingstag werden wir Sie bitten, an einer Online-Befragung
    teilzunehmen. Mit einem anonymen Online-Fragebogen wollen wir wissen, wie Sie zu unserem Kurs
    gekommen sind und wie es bei Ihnen mit dem Fahrradfahren weitergegangen ist.
  </p>
  <p>
    Wir wünschen Ihnen viel Spaß und Erfolg in Ihrem Kurs.<br />
  </p>
  Allgemeiner Deutscher Fahrrad-Club München e.V.<br />
  Platenstraße 4<br />
  80336 München<br />
  radfahrschule@adfc-muenchen.de<br />
  https://muenchen.adfc.de/radfahrschule<br />
</body>

</html>