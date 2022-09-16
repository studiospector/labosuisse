<?php

namespace Caffeina\LaboSuisse\Services;

class Province
{
    const LIST = [
        "AG" => "Agrigento",
        "AL" => "Alessandria",
        "AN" => "Ancona",
        "AO" => "Valle d'Aosta",
        "AP" => "Ascoli Piceno",
        "AQ" => "L'Aquila",
        "AR" => "Arezzo",
        "AT" => "Asti",
        "AV" => "Avellino",
        "BA" => "Bari",
        "BG" => "Bergamo",
        "BI" => "Biella",
        "BL" => "Belluno",
        "BN" => "Benevento",
        "BO" => "Bologna",
        "BR" => "Brindisi",
        "BS" => "Brescia",
        "BT" => "Barletta-Andria-Trani",
        "BZ" => "Bolzano",
        "CA" => "Cagliari",
        "CB" => "Campobasso",
        "CE" => "Caserta",
        "CH" => "Chieti",
        "CL" => "Caltanissetta",
        "CN" => "Cuneo",
        "CO" => "Como",
        "CR" => "Cremona",
        "CS" => "Cosenza",
        "CT" => "Catania",
        "CZ" => "Catanzaro",
        "EN" => "Enna",
        "FC" => "Forlì-Cesena",
        "FE" => "Ferrara",
        "FG" => "Foggia",
        "FI" => "Firenze",
        "FM" => "Fermo",
        "FR" => "Frosinone",
        "GE" => "Genova",
        "GO" => "Gorizia",
        "GR" => "Grosseto",
        "IM" => "Imperia",
        "IS" => "Isernia",
        "KR" => "Crotone",
        "LC" => "Lecco",
        "LE" => "Lecce",
        "LI" => "Livorno",
        "LO" => "Lodi",
        "LT" => "Latina",
        "LU" => "Lucca",
        "MB" => "Monza e della Brianza",
        "MC" => "Macerata",
        "ME" => "Messina",
        "MI" => "Milano",
        "MN" => "Mantova",
        "MO" => "Modena",
        "MS" => "Massa-Carrara",
        "MT" => "Matera",
        "NA" => "Napoli",
        "NO" => "Novara",
        "NU" => "Nuoro",
        "OR" => "Oristano",
        "PA" => "Palermo",
        "PC" => "Piacenza",
        "PD" => "Padova",
        "PE" => "Pescara",
        "PG" => "Perugia",
        "PI" => "Pisa",
        "PN" => "Pordenone",
        "PO" => "Prato",
        "PR" => "Parma",
        "PT" => "Pistoia",
        "PU" => "Pesaro e Urbino",
        "PV" => "Pavia",
        "PZ" => "Potenza",
        "RA" => "Ravenna",
        "RC" => "Reggio Calabria",
        "RE" => "Reggio nell'Emilia",
        "RG" => "Ragusa",
        "RI" => "Rieti",
        "RM" => "Roma",
        "RN" => "Rimini",
        "RO" => "Rovigo",
        "SA" => "Salerno",
        "SI" => "Siena",
        "SO" => "Sondrio",
        "SP" => "La Spezia",
        "SR" => "Siracusa",
        "SS" => "Sassari",
        "SU" => "Sud Sardegna",
        "SV" => "Savona",
        "TA" => "Taranto",
        "TE" => "Teramo",
        "TN" => "Trento",
        "TO" => "Torino",
        "TP" => "Trapani",
        "TR" => "Terni",
        "TS" => "Trieste",
        "TV" => "Treviso",
        "UD" => "Udine",
        "VA" => "Varese",
        "VB" => "Verbano-Cusio-Ossola",
        "VC" => "Vercelli",
        "VE" => "Venezia",
        "VI" => "Vicenza",
        "VR" => "Verona",
        "VT" => "Viterbo",
        "VV" => "Vibo Valentia"
    ];

    public static function getByShortCode($shortCode)
    {
        return self::LIST[strtoupper($shortCode)];
    }

    public static function getShortCode($province)
    {
        $province = strtolower(preg_replace("/[^A-Za-z0-9 ]/", '', $province));

        return array_key_first(
            array_filter(self::LIST, function ($item) use ($province) {
                return strtolower(preg_replace("/[^A-Za-z0-9 ]/", '', $item)) == $province;
            })
        );
    }
}
