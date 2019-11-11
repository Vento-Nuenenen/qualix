<?php
return array(
	"auth" => array(
		"failed" => "Dieses Login ist uns nicht bekannt. Meldest du dich vielleicht normalerweise mit MiData an?",
		"throttle" => "Zu viele Login-Versuche. Bitte probiere es nochmals in :seconds Sekunden.",
	),
	"pagination" => array(
		"next" => "Weiter &raquo;",
		"previous" => "&laquo; Zurück",
	),
	"passwords" => array(
		"password" => "Passwort muss mindestens 8 Zeichen lang und in beiden Feldern gleich sein.",
		"reset" => "Dein Passwort wurde zurückgesetzt!",
		"sent" => "Wir haben dir einen Link zum zurücksetzen des Passworts gesendet.",
		"throttled" => "Bitte warte bevor du es nochmals versuchst.",
		"token" => "Dieses Token ist zum Zurücksetzen des Passworts ungültig.",
		"user" => "Wir können keinen Benutzer mit dieser E-Mail-Adresse finden. Meldest du dich vielleicht normalerweise mit MiData an?",
	),
	"validation" => array(
		"accepted" => ":attribute muss akzeptiert werden.",
		"active_url" => ":attribute ist keine gültige Internet-Adresse.",
		"after" => ":attribute muss ein Datum nach dem :date sein.",
		"after_or_equal" => ":attribute muss ein Datum nach dem :date oder gleich dem :date sein.",
		"alpha" => ":attribute darf nur aus Buchstaben bestehen.",
		"alpha_dash" => ":attribute darf nur aus Buchstaben, Zahlen, Binde- und Unterstrichen bestehen.",
		"alpha_num" => ":attribute darf nur aus Buchstaben und Zahlen bestehen.",
		"array" => ":attribute muss ein Array sein.",
		"attributes" => array(
			"address" => "Adresse",
			"age" => "Alter",
			"available" => "verfügbar",
			"city" => "Stadt",
			"content" => "Inhalt",
			"country" => "Land",
			"date" => "Datum",
			"day" => "Tag",
			"description" => "Beschreibung",
			"email" => "E-Mail-Adresse",
			"excerpt" => "Auszug",
			"first_name" => "Vorname",
			"gender" => "Geschlecht",
			"hour" => "Stunde",
			"last_name" => "Nachname",
			"minute" => "Minute",
			"mobile" => "Handynummer",
			"month" => "Monat",
			"name" => "Name",
			"password" => "Passwort",
			"password_confirmation" => "Passwort-Bestätigung",
			"phone" => "Telefonnummer",
			"second" => "Sekunde",
			"sex" => "Geschlecht",
			"size" => "Grösse",
			"time" => "Uhrzeit",
			"title" => "Titel",
			"username" => "Benutzername",
			"year" => "Jahr",
		),
		"before" => ":attribute muss ein Datum vor dem :date sein.",
		"before_or_equal" => ":attribute muss ein Datum vor dem :date oder gleich dem :date sein.",
		"between" => array(
			"array" => ":attribute muss zwischen :min & :max Elemente haben.",
			"file" => ":attribute muss zwischen :min & :max Kilobytes gross sein.",
			"numeric" => ":attribute muss zwischen :min & :max liegen.",
			"string" => ":attribute muss zwischen :min & :max Zeichen lang sein.",
		),
		"boolean" => ":attribute muss entweder 'true' oder 'false' sein.",
		"confirmed" => ":attribute stimmt nicht mit der Bestätigung überein.",
		"custom" => array(
			"attribute-name" => array(
				"rule-name" => "custom-message",
			),
		),
		"date" => ":attribute muss ein gültiges Datum sein.",
		"date_equals" => "The :attribute must be a date equal to :date.",
		"date_format" => ":attribute entspricht nicht dem gültigen Format für :format.",
		"different" => ":attribute und :other müssen sich unterscheiden.",
		"digits" => ":attribute muss :digits Stellen haben.",
		"digits_between" => ":attribute muss zwischen :min und :max Stellen haben.",
		"dimensions" => ":attribute hat ungültige Bildabmessungen.",
		"distinct" => ":attribute beinhaltet einen bereits vorhandenen Wert.",
		"email" => ":attribute muss eine gültige E-Mail-Adresse sein.",
		"ends_with" => ":attribute muss auf einen der folgenden Werte enden: :values",
		"exists" => "Der gewählte Wert für :attribute ist ungültig.",
		"file" => ":attribute muss eine Datei sein.",
		"filled" => ":attribute muss ausgefüllt sein.",
		"gt" => array(
			"array" => ":attribute muss mindestens :value Elemente haben.",
			"file" => ":attribute muss mindestens :value Kilobytes gross sein.",
			"numeric" => ":attribute muss mindestens :value sein.",
			"string" => ":attribute muss mindestens :value Zeichen lang sein.",
		),
		"gte" => array(
			"array" => ":attribute muss grösser oder gleich :value Elemente haben.",
			"file" => ":attribute muss grösser oder gleich :value Kilobytes sein.",
			"numeric" => ":attribute muss grösser oder gleich :value sein.",
			"string" => ":attribute muss grösser oder gleich :value Zeichen lang sein.",
		),
		"image" => ":attribute muss ein Bild sein.",
		"in" => "Der gewählte Wert für :attribute ist ungültig.",
		"integer" => ":attribute muss eine ganze Zahl sein.",
		"in_array" => "Der gewählte Wert für :attribute kommt nicht in :other vor.",
		"ip" => ":attribute muss eine gültige IP-Adresse sein.",
		"ipv4" => ":attribute muss eine gültige IPv4-Adresse sein.",
		"ipv6" => ":attribute muss eine gültige IPv6-Adresse sein.",
		"json" => ":attribute muss ein gültiger JSON-String sein.",
		"lt" => array(
			"array" => ":attribute muss kleiner :value Elemente haben.",
			"file" => ":attribute muss kleiner :value Kilobytes gross sein.",
			"numeric" => ":attribute muss kleiner :value sein.",
			"string" => ":attribute muss kleiner :value Zeichen lang sein.",
		),
		"lte" => array(
			"array" => ":attribute muss kleiner oder gleich :value Elemente haben.",
			"file" => ":attribute muss kleiner oder gleich :value Kilobytes sein.",
			"numeric" => ":attribute muss kleiner oder gleich :value sein.",
			"string" => ":attribute muss kleiner oder gleich :value Zeichen lang sein.",
		),
		"max" => array(
			"array" => ":attribute darf nicht mehr als :max Elemente haben.",
			"file" => ":attribute darf maximal :max Kilobytes gross sein.",
			"numeric" => ":attribute darf maximal :max sein.",
			"string" => ":attribute darf maximal :max Zeichen haben.",
		),
		"mimes" => ":attribute muss den Dateityp :values haben.",
		"mimetypes" => ":attribute muss den Dateityp :values haben.",
		"min" => array(
			"array" => ":attribute muss mindestens :min Elemente haben.",
			"file" => ":attribute muss mindestens :min Kilobytes gross sein.",
			"numeric" => ":attribute muss mindestens :min sein.",
			"string" => ":attribute muss mindestens :min Zeichen lang sein.",
		),
		"not_in" => "Der gewählte Wert für :attribute ist ungültig.",
		"not_regex" => ":attribute hat ein ungültiges Format.",
		"numeric" => ":attribute muss eine Zahl sein.",
		"password" => "Das Passwort ist nicht korrekt.",
		"present" => ":attribute muss vorhanden sein.",
		"regex" => ":attribute Format ist ungültig.",
		"required" => ":attribute muss ausgefüllt sein.",
		"required_if" => ":attribute muss ausgefüllt sein, wenn :other :value ist.",
		"required_unless" => ":attribute muss ausgefüllt sein, wenn :other nicht :values ist.",
		"required_with" => ":attribute muss angegeben werden, wenn :values ausgefüllt wurde.",
		"required_without" => ":attribute muss angegeben werden, wenn :values nicht ausgefüllt wurde.",
		"required_without_all" => ":attribute muss angegeben werden, wenn keines der Felder :values ausgefüllt wurde.",
		"required_with_all" => ":attribute muss angegeben werden, wenn :values ausgefüllt wurde.",
		"same" => ":attribute und :other müssen übereinstimmen.",
		"size" => array(
			"array" => ":attribute muss genau :size Elemente haben.",
			"file" => ":attribute muss :size Kilobyte gross sein.",
			"numeric" => ":attribute muss gleich :size sein.",
			"string" => ":attribute muss :size Zeichen lang sein.",
		),
		"starts_with" => ":attribute muss mit einem der folgenden Werte beginnen: :values",
		"string" => ":attribute muss ein String sein.",
		"timezone" => ":attribute muss eine gültige Zeitzone sein.",
		"unique" => ":attribute ist schon vergeben.",
		"uploaded" => ":attribute konnte nicht hochgeladen werden.",
		"url" => ":attribute muss eine URL sein.",
		"uuid" => ":attribute muss eine gültige UUID sein.",
	),
);