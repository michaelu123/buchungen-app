# Buchungen-App Beta (Stand 27.02.2026)

Die Testphase der Buchungen-App beginnt. Ziel ist es, ohne Google auszukommen. Einerseits aus den bekannten Gründen: Trump kill switch, Google kann uns jederzeit die Freundschaft kündigen, mancher mag vielleicht nicht persönliche Daten in ein Google-Formular eingeben. Aber auch, weil das Zusammenspiel von Google Forms, Sheets, AppsScript recht fragil ist. Außerdem braucht man noch ein zusätzliches Programm, um die Datei zu erzeugen, mit der Martin Stasnik die Abbuchungen durchführt.

Was verloren geht, ist die Fähigkeit, leicht neue Spalten einzuführen. Was früher Spalten in Google Sheets waren, sind jetzt Datenbankfelder. Neue Felter bedeuten Datenbank-Änderungen und erhebliche Änderungen im Programmcode. Deshalb wäre es wünschenswert, wenn die Tester frühzeitig ihre Wünsche in Bezug auf zusätzliche Felder kundtun würden.

## URL, Login, Rollen

Die URL ist https://buchungen.adfc-muenchen.de für eine Übersicht aller Anmeldeformulare,
https://buchungen.adfc-muenchen.de/rfsabuchung (bzw. rfsf, rfsfp, tk) für das jeweilige Formular für Anfänger, Fahrsicherheitstraining, Fahrpraxis der RFS und für Technikkurse. Diese URLS verlangen keine Anmeldung.
Die Administration der Buchungen erfolgt über die URL https://buchungen.adfc-muenchen.de/admin. Hier wird eine Anmeldung verlangt. Für die Testphase habe ich die Benutzer admin@admin.com, rfs@admin.com, rfsa@admin.com, rfsf@admin.com, rfsfp@admin.com, tk@admin.com eingerichtet. Diese haben jeweils die Benutzerrollen ADMIN, RFS, RFSA, RFSF, RFSFP, TK. Die Rolle ADMIN darf alles bearbeiten, RFSA, RFSF, RFSFP nur die jeweiligen Anmeldungen, und RFS alle 3. Im Regelbetrieb würdet Ihr Euch mit Eurer ADFC-Email-Adresse anmelden, und bekämt die passende Rolle zugewiesen.
Das Passwort bei allen Benutzern ist xxxx1234. Probiert es aus, was sich ändert, wenn Ihr Euch mit unterschiedlichen Email-Adressen anmeldet.

## Notiz

Bei Google hatte eine Notiz in der 1. Spalte den Effekt, daß diese Zeile ungültig wurde. Der Effekt war ähnlich, als ob die Zeile gelöscht wurde, aber sie war noch sichtbar. Damit konnten Kurse und Buchungen z.B. storniert werden, ober abgelaufene Kurse als solche markiert werden. Diese Funktion erfüllt jetzt das Feld Notiz.

## Kommentare

Das Kommentarfeld kann auch für längere Kommentare genutzt werden. Die Suchfunktion durchsucht auch die Kommentare. Bevor der Wunsch nach neuen Feldern aufkommt, könnte man auch prüfen, ob nicht das Kommentarfeld ausreicht.

## Kurse, Buchungen

Indem Ihr in Kurse verschiedene Kurse hinzufügt oder ändert, ändert sich das Buchungsformular. Indem Ihr Buchungen löscht oder eine Notiz hinzufügt, oder über das Formular neue Buchungen erstellt, ändern sich die Restplätze und damit auch das Buchungsformular. Der Knopf "Update Restplätze" ist i.a. nicht notwendig.

## Excel-Exporte

Für Kurse bedeutet "Excel Export" oberhalb der Tabelle den Export aller Kurse. Am Ende jeder Zeile gibt es unter Aktionen den Menüpunkt Excel, mit dem eine Tabelle aller Buchungen für diesen Kurs erstellt wird.
Für Buchungen bedeutet "Excel Export" oberhalb der Tabelle den Export aller Buchungen.

## Excel-Importe

Man kann eine Google-Backend-Tabelle als Excel-Datei downloaden. Diese kann man hier importieren, erst die Kurse, dann die Buchungen, mit der gleichen Excel-Datei, in der die Tabellenblätter Buchungen und Kurse vorkommen müssen. Die Beispieldaten der Kurse und Buchungen enthalten jeweils 10 Kurse und 100 Buchungen mit Fake-Daten, plus die Imports von Google vom 26.2.

## Aktionen bei Buchungen

Am Ende jeder Tabellenzeile stehen die möglichen Aktionen.

- Edit: Ändern der Daten. Man kann auch einfach in die Zeile klicken.
- Delete: Löschen der Zeile.
- Prüfen: bei Buchungen wird so getan, als ob die Buchung gerade frisch über das Formular erzeugt wurde.
- Bestätigung senden: Sendet eine Anmeldebestätigung. Bei den Technik-Buchungen wird diee Anmeldebestätigung sofort nach der Email-Verifikation gesendet, be RFS muß sie händisch gesendet werden.

## Aktionen bei Kursen

Am Ende jeder Tabellenzeile stehen die möglichen Aktionen.

- Edit, Delete s.o.
- Excel: Tabelle aller Buchungen für diesen Kurs.
- Ebics: Damit werden die Abbuchungen für diesen Kurs erstellt (und ein anderes Programm ebics3 überflüssig).

## Englisch

Leider wird nicht alles eingedeutscht. Z.B. sehe ich bei Kurse: New kurs. Manches werde ich vielleicht noch hinbekommen, aber wohl nicht alles. Für die Admin-Sichten ist es ja auch nicht sooo wichtig.

## Email-Verifikation

Wenn bei einer Buchung die Email-Adresse schon in der Tabelle Email-Verifikation steht, geht es gleich weiter, sonst bekommt der Anmelder erstmal eine Email mit einer URL. Wenn er die anklickt, erfolgt der Eintrag in der Tabelle Email-Verifikation, und er bekommt entweder gleich die Anmeldebestätigung, oder das Senden der Anmeldebestätigung wird jetzt möglich. Außerdem wird in der Buchungen-Tabelle die Spalte "Email verifiziert" gefüllt.

## Filter und Suche, nach Spalten sortieren

Ganz oben gibt es ein Suchfeld, was sich mir noch nicht so recht entschließt. Das Suchfeld über der Tabelle sucht in der Tabelle, probiert es aus. Wenn im Suchfeld was steht, erscheint auch ein Knopf "Search". Wenn Ihr auf den klickt, wird das Suchfeld gelöscht. Oder Ihr löscht das Suchfeld selber.

Über das Filter-Symbol lässt sich bei Buchungen nach Kurs oder Notiz oder beidem filtern. Wieder erscheinen Knöpfe, mit denen man die Filter einfach löschen kann.

Wenn man nach einer Spalte sortieren kann, erscheint neben dem Spaltennamen ein Winkel nach oben oder unten für auf- und absteigende Sortierung. Vielleicht sollte ich auch die Spalten für Lehrer/Trainer sortierbar machen? Bei der Technik ist es so...

## Emails

Im Testbetrieb lassen sich Emails nur an Adressen schicken, die auf @adfc-muenchen.de enden, am besten natürlich an Eure eigene Adresse, sonst seht Ihr sie ja nicht.

## Sourcecode

Derzeit auf https://github.com/michaelu123/buchungen-app .

## Zukünftig

Vielleicht die Anmeldungen für die Saisonkarten. Vielleicht Anmeldungen für Codierung oder Werkstatt-Termine, bei denen keine IBAN gefordert wird. Anlegen von Benutzern über UI fehlt noch. Email-Attachments gibt es derzeit nur für RFSA. In den Formularen müssen die Texte zu Teilnahmebedingungen und Datenschutz wahrscheinlich noch aktualisiert werden.
