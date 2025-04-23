<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Enums;

enum CountryDelivery
{
    case ACORES;
    case AFGHANISTAN;
    case AFRIQUE_DU_SUD;
    case ALBANIE;
    case ALGERIE;
    case ALLEMAGNE;
    case ANDORRE;
    case ANGOLA;
    case ANGUILLA;
    case ANTIGUA_ET_BARBUDA;
    case ARABIE_SAOUDITE;
    case ARGENTINE;
    case ARMENIE;
    case ARUBA;
    case AUSTRALIE;
    case AUTRICHE;
    case AZERBAIDJAN;
    case BAHAMAS;
    case BAHREIN;
    case BANGLADESH;
    case BARBADE;
    case BELGIQUE;
    case BELIZE;
    case BENIN;
    case BERMUDES;
    case BIELORUSSIE;
    case BOLIVIE;
    case BONAIRE;
    case BOSNIE_HERZEGOVINE;
    case BOTSWANA;
    case BRESIL;
    case BHOUTAN;
    case BRUNEI_DARUSSALAM;
    case BULGARIE;
    case BURKINA_FASO;
    case BURUNDI;
    case CAMBODGE;
    case CAMEROUN;
    case CANADA;
    case CANARIES_ILES;
    case CAP_VERT;
    case CAYMAN_ILES;
    case REPUBLIQUE_DU_CONGO;
    case CENTRAFRIQUE;
    case CHILI;
    case CHINE;
    case CHYPRE;
    case COLOMBIE;
    case COMORES;
    case CONGO;
    case COOK_ILES;
    case COREE_DU_NORD;
    case COREE_DU_SUD;
    case COSTA_RICA;
    case COTE_D_IVOIRE;
    case CROATIE;
    case CUBA;
    case DANEMARK;
    case DJIBOUTI;
    case DOMINIQUE_ILE_DE_LA;
    case EGYPTE;
    case EL_SALVADOR;
    case EMIRATS_ARABE_UNIS;
    case EQUATEUR;
    case ERYTHREE;
    case ESPAGNE;
    case ESTONIE;
    case ETATS_UNIS_D_AMERIQUE;
    case ETHIOPIE;
    case FEROE_ILES;
    case FIDJI;
    case FINLANDE;
    case FRANCE;
    case GABON;
    case GAMBIE;
    case GEORGIE;
    case GHANA;
    case GIBRALTAR;
    case GRANDE_BRETAGNE;
    case GROENLAND;
    case GRECE;
    case GRENADE_ILE_DE_LA;
    case GUADELOUPE;
    case GUAM_ILE_DE;
    case GUATEMALA;
    case GUERNESEY_ILE_DE;
    case GEORGIE_DU_SUD;
    case GUINEE;
    case GUINEE_BISSAU;
    case GUINEE_EQUATORIALE;
    case GUYANA;
    case GUYANE;
    case HAITI;
    case HONDURAS;
    case HONG_KONG;
    case HONGRIE;
    case INDE;
    case INDONESIE;
    case IRAN;
    case IRAQ;
    case IRLANDE;
    case ISLANDE;
    case ISRAEL;
    case ITALIE;
    case JAMAIQUE;
    case JAPON;
    case JERSEY_ILE_DE;
    case JORDANIE;
    case KAZAKHSTAN;
    case KENYA;
    case KIRGHIZISTAN;
    case KIRIBATI_ILES;
    case KOWEIT;
    case LAOS;
    case LESOTHO;
    case LETTONIE;
    case LIBAN;
    case LIBERIA;
    case LIBYE;
    case LICHTENSTEIN;
    case LITUANIE;
    case LUXEMBOURG;
    case MARSHALL_ILES;
    case MACAO;
    case MACEDOINE;
    case MADAGASCAR;
    case MADERE_ILE_DE;
    case MALAISIE;
    case MALAWI;
    case MALDIVES;
    case MALI;
    case MALTE;
    case MAROC;
    case MARTINIQUE;
    case MAURICE_ILE;
    case MAURITANIE;
    case MAYOTTE;
    case MEXIQUE;
    case MICRONESIE;
    case MOLDAVIE;
    case MONACO;
    case MONGOLIE;
    case MONTSERRAT_ILE_DE;
    case MOZAMBIQUE;
    case MYANMAR_BIRMANIE;
    case NAMIBIE;
    case NAURU_ILES;
    case NEPAL;
    case NICARAGUA;
    case NIGER;
    case NIGERIA;
    case NORVEGE;
    case NOUVELLE_CALEDONIE;
    case NOUVELLE_ZELANDE;
    case OMAN_SULTANAT;
    case OUGANDA;
    case OUZBEKISTAN;
    case PAKISTAN;
    case PANAMA;
    case PALESTINE;
    case PALAU_ILES;
    case PAPOUASIE_NLLE_GUINEE;
    case PARAGUAY;
    case PAYS_BAS;
    case PEROU;
    case PHILIPPINES;
    case POLOGNE;
    case POLYNESIE_FRANCAISE;
    case PORTO_RICO;
    case PORTUGAL;
    case QATAR;
    case REPUBLIQUE_TCHEQUE;
    case REUNION_ILE_DE_LA;
    case ROUMANIE;
    case RUSSIE;
    case RWANDA;
    case SAINT_CHRISTOPHE_ET_NEVIS;
    case SAINT_DOMINGUE;
    case SAINT_MARTIN_FRANCAISE;
    case SAINT_MARTIN_HOLLANDAISE;
    case SAINT_PIERRE_ET_MIQUELON;
    case SAINT_VINCENT_ILES;
    case SAINTE_LUCIE;
    case SALOMON_ILES;
    case SAIPAN_ILE_DE;
    case SAMOA_AMERICAINES_ILES;
    case SAMOA_OCCIDENTALES;
    case SAN_MARIN;
    case SAO_TOME_ET_PRINCIPE;
    case SENEGAL;
    case SERBIE;
    case SEYCHELLES_ILES;
    case SIERRA_LEONE;
    case SINGAPOUR;
    case SLOVAQUIE;
    case SLOVENIE;
    case SOMALIE;
    case SOUDAN;
    case SRI_LANKA;
    case SUEDE;
    case SUISSE;
    case SURINAME;
    case SWAZILAND;
    case SYRIE;
    case TADJIKISTAN;
    case TAIWAN;
    case TANZANIE;
    case TCHAD;
    case THAILANDE;
    case TOGO;
    case TIMOR_ORIENTAL;
    case TONGA_ILES_DU;
    case TRINITE_ET_TOBAGO;
    case TUNISIE;
    case TURKMENISTAN;
    case TURQUES_ET_CAIQUES_ILES;
    case TURQUIE;
    case TUVALU_ILE_DE;
    case UKRAINE;
    case URUGUAY;
    case VANUATU_ILES_VU;
    case VATICAN;
    case VENEZUELA;
    case VIERGES_AMERICAINES_ILES;
    case VIERGES_BRITANNIQUES_ILE;
    case VIETNAM;
    case WALLIS_ET_FUTUNA;
    case YEMEN;
    case ZAMBIE;
    case ZIMBABWE;

    /**
     * Get Chronopost country code on two letters.
     *
     * @return string
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity.MaxExceeded
     */
    public function getCode(): string
    {
        // phpcs:enable
        return match ($this) {
            self::ACORES                    => 'AC',
            self::AFGHANISTAN               => 'AF',
            self::AFRIQUE_DU_SUD            => 'ZA',
            self::ALBANIE                   => 'AL',
            self::ALGERIE                   => 'DZ',
            self::ALLEMAGNE                 => 'DE',
            self::ANDORRE                   => 'AD',
            self::ANGOLA                    => 'AO',
            self::ANGUILLA                  => 'AI',
            self::ANTIGUA_ET_BARBUDA        => 'AG',
            self::ARABIE_SAOUDITE           => 'SA',
            self::ARGENTINE                 => 'AR',
            self::ARMENIE                   => 'AM',
            self::ARUBA                     => 'AW',
            self::AUSTRALIE                 => 'AU',
            self::AUTRICHE                  => 'AT',
            self::AZERBAIDJAN               => 'AZ',
            self::BAHAMAS                   => 'BS',
            self::BAHREIN                   => 'BH',
            self::BANGLADESH                => 'BD',
            self::BARBADE                   => 'BB',
            self::BELGIQUE                  => 'BE',
            self::BELIZE                    => 'BZ',
            self::BENIN                     => 'BJ',
            self::BERMUDES                  => 'BM',
            self::BIELORUSSIE               => 'BY',
            self::BOLIVIE                   => 'BO',
            self::BONAIRE                   => 'AN',
            self::BOSNIE_HERZEGOVINE        => 'BA',
            self::BOTSWANA                  => 'BW',
            self::BRESIL                    => 'BR',
            self::BHOUTAN                   => 'BT',
            self::BRUNEI_DARUSSALAM         => 'BN',
            self::BULGARIE                  => 'BG',
            self::BURKINA_FASO              => 'BF',
            self::BURUNDI                   => 'BI',
            self::CAMBODGE                  => 'KH',
            self::CAMEROUN                  => 'CM',
            self::CANADA                    => 'CA',
            self::CANARIES_ILES             => 'IC',
            self::CAP_VERT                  => 'CV',
            self::CAYMAN_ILES               => 'KY',
            self::REPUBLIQUE_DU_CONGO       => 'CD',
            self::CENTRAFRIQUE              => 'CF',
            self::CHILI                     => 'CL',
            self::CHINE                     => 'CN',
            self::CHYPRE                    => 'CY',
            self::COLOMBIE                  => 'CO',
            self::COMORES                   => 'KM',
            self::CONGO                     => 'CG',
            self::COOK_ILES                 => 'CK',
            self::COREE_DU_NORD             => 'KP',
            self::COREE_DU_SUD              => 'KR',
            self::COSTA_RICA                => 'CR',
            self::COTE_D_IVOIRE             => 'CI',
            self::CROATIE                   => 'HR',
            self::CUBA                      => 'CU',
            self::DANEMARK                  => 'DK',
            self::DJIBOUTI                  => 'DJ',
            self::DOMINIQUE_ILE_DE_LA       => 'DM',
            self::EGYPTE                    => 'EG',
            self::EL_SALVADOR               => 'SV',
            self::EMIRATS_ARABE_UNIS        => 'AE',
            self::EQUATEUR                  => 'EC',
            self::ERYTHREE                  => 'ER',
            self::ESPAGNE                   => 'ES',
            self::ESTONIE                   => 'EE',
            self::ETATS_UNIS_D_AMERIQUE     => 'US',
            self::ETHIOPIE                  => 'ET',
            self::FEROE_ILES                => 'FO',
            self::FIDJI                     => 'FJ',
            self::FINLANDE                  => 'FI',
            self::FRANCE                    => 'FR',
            self::GABON                     => 'GA',
            self::GAMBIE                    => 'GM',
            self::GEORGIE                   => 'GE',
            self::GHANA                     => 'GH',
            self::GIBRALTAR                 => 'GI',
            self::GRANDE_BRETAGNE           => 'GB',
            self::GROENLAND                 => 'GL',
            self::GRECE                     => 'GR',
            self::GRENADE_ILE_DE_LA         => 'GD',
            self::GUADELOUPE                => 'GP',
            self::GUAM_ILE_DE               => 'GU',
            self::GUATEMALA                 => 'GT',
            self::GUERNESEY_ILE_DE          => 'GG',
            self::GEORGIE_DU_SUD            => 'GS',
            self::GUINEE                    => 'GN',
            self::GUINEE_BISSAU             => 'GW',
            self::GUINEE_EQUATORIALE        => 'GQ',
            self::GUYANA                    => 'GY',
            self::GUYANE                    => 'GF',
            self::HAITI                     => 'HT',
            self::HONDURAS                  => 'HN',
            self::HONG_KONG                 => 'HK',
            self::HONGRIE                   => 'HU',
            self::INDE                      => 'IN',
            self::INDONESIE                 => 'ID',
            self::IRAN                      => 'IR',
            self::IRAQ                      => 'IQ',
            self::IRLANDE                   => 'IE',
            self::ISLANDE                   => 'IS',
            self::ISRAEL                    => 'IL',
            self::ITALIE                    => 'IT',
            self::JAMAIQUE                  => 'JM',
            self::JAPON                     => 'JP',
            self::JERSEY_ILE_DE             => 'JE',
            self::JORDANIE                  => 'JO',
            self::KAZAKHSTAN                => 'KZ',
            self::KENYA                     => 'KE',
            self::KIRGHIZISTAN              => 'KG',
            self::KIRIBATI_ILES             => 'KI',
            self::KOWEIT                    => 'KW',
            self::LAOS                      => 'LA',
            self::LESOTHO                   => 'LS',
            self::LETTONIE                  => 'LV',
            self::LIBAN                     => 'LB',
            self::LIBERIA                   => 'LR',
            self::LIBYE                     => 'LY',
            self::LICHTENSTEIN              => 'LI',
            self::LITUANIE                  => 'LT',
            self::LUXEMBOURG                => 'LU',
            self::MARSHALL_ILES             => 'MH',
            self::MACAO                     => 'MO',
            self::MACEDOINE                 => 'MK',
            self::MADAGASCAR                => 'MG',
            self::MADERE_ILE_DE             => 'ME',
            self::MALAISIE                  => 'MY',
            self::MALAWI                    => 'MW',
            self::MALDIVES                  => 'MV',
            self::MALI                      => 'ML',
            self::MALTE                     => 'MT',
            self::MAROC                     => 'MA',
            self::MARTINIQUE                => 'MQ',
            self::MAURICE_ILE               => 'MU',
            self::MAURITANIE                => 'MR',
            self::MAYOTTE                   => 'YT',
            self::MEXIQUE                   => 'MX',
            self::MICRONESIE                => 'FM',
            self::MOLDAVIE                  => 'MD',
            self::MONACO                    => 'FR',
            self::MONGOLIE                  => 'MN',
            self::MONTSERRAT_ILE_DE         => 'MS',
            self::MOZAMBIQUE                => 'MZ',
            self::MYANMAR_BIRMANIE          => 'MM',
            self::NAMIBIE                   => 'NA',
            self::NAURU_ILES                => 'NR',
            self::NEPAL                     => 'NP',
            self::NICARAGUA                 => 'NI',
            self::NIGER                     => 'NE',
            self::NIGERIA                   => 'NG',
            self::NORVEGE                   => 'NO',
            self::NOUVELLE_CALEDONIE        => 'NC',
            self::NOUVELLE_ZELANDE          => 'NZ',
            self::OMAN_SULTANAT             => 'OM',
            self::OUGANDA                   => 'UG',
            self::OUZBEKISTAN               => 'UZ',
            self::PAKISTAN                  => 'PK',
            self::PANAMA                    => 'PA',
            self::PALESTINE                 => 'PS',
            self::PALAU_ILES                => 'PW',
            self::PAPOUASIE_NLLE_GUINEE     => 'PG',
            self::PARAGUAY                  => 'PY',
            self::PAYS_BAS                  => 'NL',
            self::PEROU                     => 'PE',
            self::PHILIPPINES               => 'PH',
            self::POLOGNE                   => 'PL',
            self::POLYNESIE_FRANCAISE       => 'PF',
            self::PORTO_RICO                => 'PR',
            self::PORTUGAL                  => 'PT',
            self::QATAR                     => 'QA',
            self::REPUBLIQUE_TCHEQUE        => 'CZ',
            self::REUNION_ILE_DE_LA         => 'RE',
            self::ROUMANIE                  => 'RO',
            self::RUSSIE                    => 'RU',
            self::RWANDA                    => 'RW',
            self::SAINT_CHRISTOPHE_ET_NEVIS => 'KN',
            self::SAINT_DOMINGUE            => 'DO',
            self::SAINT_MARTIN_FRANCAISE    => 'GP',
            self::SAINT_MARTIN_HOLLANDAISE  => 'AN',
            self::SAINT_PIERRE_ET_MIQUELON  => 'PM',
            self::SAINT_VINCENT_ILES        => 'VC',
            self::SAINTE_LUCIE              => 'LC',
            self::SALOMON_ILES              => 'SB',
            self::SAIPAN_ILE_DE             => 'MP',
            self::SAMOA_AMERICAINES_ILES    => 'AS',
            self::SAMOA_OCCIDENTALES        => 'WS',
            self::SAN_MARIN                 => 'SM',
            self::SAO_TOME_ET_PRINCIPE      => 'ST',
            self::SENEGAL                   => 'SN',
            self::SERBIE                    => 'RS',
            self::SEYCHELLES_ILES           => 'SC',
            self::SIERRA_LEONE              => 'SL',
            self::SINGAPOUR                 => 'SG',
            self::SLOVAQUIE                 => 'SK',
            self::SLOVENIE                  => 'SI',
            self::SOMALIE                   => 'SO',
            self::SOUDAN                    => 'SD',
            self::SRI_LANKA                 => 'LK',
            self::SUEDE                     => 'SE',
            self::SUISSE                    => 'CH',
            self::SURINAME                  => 'SR',
            self::SWAZILAND                 => 'SZ',
            self::SYRIE                     => 'SY',
            self::TADJIKISTAN               => 'TJ',
            self::TAIWAN                    => 'TW',
            self::TANZANIE                  => 'TZ',
            self::TCHAD                     => 'TD',
            self::THAILANDE                 => 'TH',
            self::TOGO                      => 'TG',
            self::TIMOR_ORIENTAL            => 'TL',
            self::TONGA_ILES_DU             => 'TO',
            self::TRINITE_ET_TOBAGO         => 'TT',
            self::TUNISIE                   => 'TN',
            self::TURKMENISTAN              => 'TM',
            self::TURQUES_ET_CAIQUES_ILES   => 'TC',
            self::TURQUIE                   => 'TR',
            self::TUVALU_ILE_DE             => 'TV',
            self::UKRAINE                   => 'UA',
            self::URUGUAY                   => 'UY',
            self::VANUATU_ILES_VU           => 'VU',
            self::VATICAN                   => 'VA',
            self::VENEZUELA                 => 'VE',
            self::VIERGES_AMERICAINES_ILES  => 'VI',
            self::VIERGES_BRITANNIQUES_ILE  => 'VG',
            self::VIETNAM                   => 'VN',
            self::WALLIS_ET_FUTUNA          => 'WF',
            self::YEMEN                     => 'YE',
            self::ZAMBIE                    => 'ZM',
            self::ZIMBABWE                  => 'ZW'
        };//end match
    }

    /**
     * Get the displayable name of the country.
     *
     * @return string
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity.MaxExceeded
     */
    public function getDisplayableName(): string
    {
        // phpcs:enable
        return match ($this) {
            self::ACORES => 'Acores',
            self::AFGHANISTAN => 'Afghanistan',
            self::AFRIQUE_DU_SUD => 'Afrique du Sud',
            self::ALBANIE => 'Albanie',
            self::ALGERIE => 'Algerie',
            self::ALLEMAGNE => 'Allemagne',
            self::ANDORRE => 'Andorre',
            self::ANGOLA => 'Angola',
            self::ANGUILLA => 'Anguilla',
            self::ANTIGUA_ET_BARBUDA => 'Antigua et Barbuda',
            self::ARABIE_SAOUDITE => 'Arabie Saoudite',
            self::ARGENTINE => 'Argentine',
            self::ARMENIE => 'Armenie',
            self::ARUBA => 'Aruba',
            self::AUSTRALIE => 'Australie',
            self::AUTRICHE => 'Autriche',
            self::AZERBAIDJAN => 'Azerbaidjan',
            self::BAHAMAS => 'Bahamas',
            self::BAHREIN => 'Bahrein',
            self::BANGLADESH => 'Bangladesh',
            self::BARBADE => 'Barbade',
            self::BELGIQUE => 'Belgique',
            self::BELIZE => 'Belize',
            self::BENIN => 'Benin',
            self::BERMUDES => 'Bermudes',
            self::BIELORUSSIE => 'Bielorussie',
            self::BOLIVIE => 'Bolivie',
            self::BONAIRE => 'Bonaire',
            self::BOSNIE_HERZEGOVINE => 'Bosnie Herzegovine',
            self::BOTSWANA => 'Botswana',
            self::BRESIL => 'Bresil',
            self::BHOUTAN => 'Bhoutan',
            self::BRUNEI_DARUSSALAM => 'Brunei Darussalam',
            self::BULGARIE => 'Bulgarie',
            self::BURKINA_FASO => 'Burkina Faso',
            self::BURUNDI => 'Burundi',
            self::CAMBODGE => 'Cambodge',
            self::CAMEROUN => 'Cameroun',
            self::CANADA => 'Canada',
            self::CANARIES_ILES => 'Canaries Iles',
            self::CAP_VERT => 'Cap Vert',
            self::CAYMAN_ILES => 'Cayman Iles',
            self::REPUBLIQUE_DU_CONGO => 'Republique du Congo',
            self::CENTRAFRIQUE => 'Centrafrique',
            self::CHILI => 'Chili',
            self::CHINE => 'Chine',
            self::CHYPRE => 'Chypre',
            self::COLOMBIE => 'Colombie',
            self::COMORES => 'Comores',
            self::CONGO => 'Congo',
            self::COOK_ILES => 'Cook Iles',
            self::COREE_DU_NORD => 'Coree du Nord',
            self::COREE_DU_SUD => 'Coree du Sud',
            self::COSTA_RICA => 'Costa Rica',
            self::COTE_D_IVOIRE => 'Cote d\'Ivoire',
            self::CROATIE => 'Croatie',
            self::CUBA => 'Cuba',
            self::DANEMARK => 'Danemark',
            self::DJIBOUTI => 'Djibouti',
            self::DOMINIQUE_ILE_DE_LA => 'Dominique Iles de la',
            self::EGYPTE => 'Egypte',
            self::EL_SALVADOR => 'El Salvador',
            self::EMIRATS_ARABE_UNIS => 'Emirats Arabe Unis',
            self::EQUATEUR => 'Equateur',
            self::ERYTHREE => 'Erythree',
            self::ESPAGNE => 'Espagne',
            self::ESTONIE => 'Estonie',
            self::ETATS_UNIS_D_AMERIQUE => 'Etats Unis d\'Amerique',
            self::ETHIOPIE => 'Ethiopie',
            self::FEROE_ILES => 'Feroe Iles',
            self::FIDJI => 'Fidji',
            self::FINLANDE => 'Finlande',
            self::FRANCE => 'France',
            self::GABON => 'Gabon',
            self::GAMBIE => 'Gambie',
            self::GEORGIE => 'Georgie',
            self::GHANA => 'Ghana',
            self::GIBRALTAR => 'Gibraltar',
            self::GRANDE_BRETAGNE => 'Grande Bretagne',
            self::GROENLAND => 'Groenland',
            self::GRECE => 'Grece',
            self::GRENADE_ILE_DE_LA => 'Grenade Iles de la',
            self::GUADELOUPE => 'Guadeloupe',
            self::GUAM_ILE_DE => 'Guam Iles de',
            self::GUATEMALA => 'Guatemala',
            self::GUERNESEY_ILE_DE => 'Guernesey Iles de',
            self::GEORGIE_DU_SUD => 'Georgie du Sud',
            self::GUINEE => 'Guinee',
            self::GUINEE_BISSAU => 'Guinee Bissau',
            self::GUINEE_EQUATORIALE => 'Guinee Equatoriale',
            self::GUYANA => 'Guyana',
            self::GUYANE => 'Guyane',
            self::HAITI => 'Haiti',
            self::HONDURAS => 'Honduras',
            self::HONG_KONG => 'Hong Kong',
            self::HONGRIE => 'Hongrie',
            self::INDE => 'Inde',
            self::INDONESIE => 'Indonesie',
            self::IRAN => 'Iran',
            self::IRAQ => 'Iraq',
            self::IRLANDE => 'Irlande',
            self::ISLANDE => 'Islande',
            self::ISRAEL => 'Israël',
            self::ITALIE => 'Italie',
            self::JAMAIQUE => 'Jamaique',
            self::JAPON => 'Japon',
            self::JERSEY_ILE_DE => 'Jersey Iles de',
            self::JORDANIE => 'Jordanie',
            self::KAZAKHSTAN => 'Kazakhstan',
            self::KENYA => 'Kenya',
            self::KIRGHIZISTAN => 'Kirghizistan',
            self::KIRIBATI_ILES => 'Kiribati Iles',
            self::KOWEIT => 'Koweit',
            self::LAOS => 'Laos',
            self::LESOTHO => 'Lesotho',
            self::LETTONIE => 'Lettonie',
            self::LIBAN => 'Liban',
            self::LIBERIA => 'Liberia',
            self::LIBYE => 'Libye',
            self::LICHTENSTEIN => 'Lichtenstein',
            self::LITUANIE => 'Lituanie',
            self::LUXEMBOURG => 'Luxembourg',
            self::MARSHALL_ILES => 'Marshall Iles',
            self::MACAO => 'Macao',
            self::MACEDOINE => 'Macedoine',
            self::MADAGASCAR => 'Madagascar',
            self::MADERE_ILE_DE => 'Madere Iles de',
            self::MALAISIE => 'Malaisie',
            self::MALAWI => 'Malawi',
            self::MALDIVES => 'Maldives',
            self::MALI => 'Mali',
            self::MALTE => 'Malte',
            self::MAROC => 'Maroc',
            self::MARTINIQUE => 'Martinique',
            self::MAURICE_ILE => 'Maurice Iles de',
            self::MAURITANIE => 'Mauritanie',
            self::MAYOTTE => 'Mayotte',
            self::MEXIQUE => 'Mexique',
            self::MICRONESIE => 'Micronesie',
            self::MOLDAVIE => 'Moldavie',
            self::MONACO => 'Monaco',
            self::MONGOLIE => 'Mongolie',
            self::MONTSERRAT_ILE_DE => 'Montserrat Iles de',
            self::MOZAMBIQUE => 'Mozambique',
            self::MYANMAR_BIRMANIE => 'Myanmar Birmanie',
            self::NAMIBIE => 'Namibie',
            self::NAURU_ILES => 'Nauru Iles',
            self::NEPAL => 'Nepal',
            self::NICARAGUA => 'Nicaragua',
            self::NIGER => 'Niger',
            self::NIGERIA => 'Nigeria',
            self::NORVEGE => 'Norvege',
            self::NOUVELLE_CALEDONIE => 'Nouvelle Caledonie',
            self::NOUVELLE_ZELANDE => 'Nouvelle Zelande',
            self::OMAN_SULTANAT => 'Oman Sultanat',
            self::OUGANDA => 'Ouganda',
            self::OUZBEKISTAN => 'Ouzbekistan',
            self::PAKISTAN => 'Pakistan',
            self::PANAMA => 'Panama',
            self::PAPOUASIE_NLLE_GUINEE => 'Papouasie Nlle Guinee',
            self::PALESTINE => 'Palestine',
            self::PALAU_ILES => 'Palau Iles',
            self::PARAGUAY => 'Paraguay',
            self::PAYS_BAS => 'Pays-Bas',
            self::PEROU => 'Perou',
            self::PHILIPPINES => 'Philippines',
            self::POLOGNE => 'Pologne',
            self::POLYNESIE_FRANCAISE => 'Polynesie Francaise',
            self::PORTO_RICO => 'Porto Rico',
            self::PORTUGAL => 'Portugal',
            self::QATAR => 'Qatar',
            self::REPUBLIQUE_TCHEQUE => 'Republique Tcheque',
            self::REUNION_ILE_DE_LA => 'Reunion Iles de la',
            self::ROUMANIE => 'Roumanie',
            self::RUSSIE => 'Russie',
            self::RWANDA => 'Rwanda',
            self::SAINT_CHRISTOPHE_ET_NEVIS => 'Saint Christophe et Nevis',
            self::SAINT_DOMINGUE => 'Saint Domingue',
            self::SAINT_MARTIN_FRANCAISE => 'Saint Martin Francaise',
            self::SAINT_MARTIN_HOLLANDAISE => 'Saint Martin Hollandaise',
            self::SAINT_PIERRE_ET_MIQUELON => 'Saint Pierre et Miquelon',
            self::SAINT_VINCENT_ILES => 'Saint Vincent Iles',
            self::SAINTE_LUCIE => 'Sainte Lucie',
            self::SALOMON_ILES => 'Salomon Iles',
            self::SAIPAN_ILE_DE => 'Saipan Iles de',
            self::SAMOA_AMERICAINES_ILES => 'Samoa Americaines Iles',
            self::SAMOA_OCCIDENTALES => 'Samoa Occidentales',
            self::SAN_MARIN => 'San Marin',
            self::SAO_TOME_ET_PRINCIPE => 'Sao Tome et Principe',
            self::SENEGAL => 'Senegal',
            self::SERBIE => 'Serbie',
            self::SEYCHELLES_ILES => 'Seychelles Iles',
            self::SIERRA_LEONE => 'Sierra Leone',
            self::SINGAPOUR => 'Singapour',
            self::SLOVAQUIE => 'Slovaquie',
            self::SLOVENIE => 'Slovenie',
            self::SOMALIE => 'Somalie',
            self::SOUDAN => 'Soudan',
            self::SRI_LANKA => 'Sri Lanka',
            self::SUEDE => 'Suede',
            self::SUISSE => 'Suisse',
            self::SURINAME => 'Suriname',
            self::SWAZILAND => 'Swaziland',
            self::SYRIE => 'Syrie',
            self::TADJIKISTAN => 'Tadjikistan',
            self::TAIWAN => 'Taiwan',
            self::TANZANIE => 'Tanzanie',
            self::TCHAD => 'Tchad',
            self::THAILANDE => 'Thailande',
            self::TOGO => 'Togo',
            self::TIMOR_ORIENTAL => 'Timor Oriental',
            self::TONGA_ILES_DU => 'Tonga Iles du',
            self::TRINITE_ET_TOBAGO => 'Trinite et Tobago',
            self::TUNISIE => 'Tunisie',
            self::TURKMENISTAN => 'Turkmenistan',
            self::TURQUES_ET_CAIQUES_ILES => 'Turques et Caïques Iles',
            self::TURQUIE => 'Turquie',
            self::TUVALU_ILE_DE => 'Tuvalu Iles de',
            self::UKRAINE => 'Ukraine',
            self::URUGUAY => 'Uruguay',
            self::VANUATU_ILES_VU => 'Vanuatu Iles VU',
            self::VATICAN => 'Vatican',
            self::VENEZUELA => 'Venezuela',
            self::VIERGES_AMERICAINES_ILES => 'Vierges Americaines Iles',
            self::VIERGES_BRITANNIQUES_ILE => 'Vierges Britanniques Iles',
            self::VIETNAM => 'Vietnam',
            self::WALLIS_ET_FUTUNA => 'Wallis et Futuna',
            self::YEMEN => 'Yemen',
            self::ZAMBIE => 'Zambie',
            self::ZIMBABWE => 'Zimbabwe'
        };//end match
    }

    /**
     * Get the delivery products available for the country.
     *
     * @return array<\Kwaadpepper\ChronopostApiPhp\Enums\DeliveryProduct>
     */
    public function getDeliveryProducts(): array
    {
        return match ($this) {
            self::ALLEMAGNE,
            self::AUTRICHE,
            self::BELGIQUE,
            self::BULGARIE,
            self::CROATIE,
            self::DANEMARK,
            self::ESPAGNE,
            self::ESTONIE,
            self::FINLANDE,
            self::GRANDE_BRETAGNE,
            self::GRECE,
            self::HONGRIE,
            self::IRLANDE,
            self::ITALIE,
            self::LETTONIE,
            self::LICHTENSTEIN,
            self::LITUANIE,
            self::LUXEMBOURG,
            self::NORVEGE,
            self::PAYS_BAS,
            self::POLOGNE,
            self::PORTUGAL,
            self::REPUBLIQUE_TCHEQUE,
            self::ROUMANIE,
            self::SLOVAQUIE,
            self::SLOVENIE,
            self::SUEDE,
            self::SUISSE => [
                    DeliveryProduct::CLASSIC,
                    DeliveryProduct::EXPRESS,
                ],
            default => [ DeliveryProduct::EXPRESS ],
        };//end match
    }

    /**
     * Get the post code formats for the country.
     *
     * @return array<string>|null
     */
    public function getPostCodeFormats(): array|null
    {
        return match ($this) {
            self::ACORES => ['/\d{7}/'],
            self::AFGHANISTAN => ['/\d{4}/'],
            self::AFRIQUE_DU_SUD => ['/\d{5}/'],
            self::ALBANIE => ['/\d{4}/'],
            self::ALGERIE => ['/\d{5}/'],
            self::ALLEMAGNE => ['/\d{5}/'],
            self::ANDORRE => ['/[A-Z]{4}\d{3}/'],
            self::ANGOLA => ['/\d{6}/'],
            self::ANGUILLA => ['/[A-Z]{2}\d{4}/'],
            self::ANTIGUA_ET_BARBUDA => ['/\d{5}/'],
            self::ARABIE_SAOUDITE => ['/\d{5}/'],
            self::ARGENTINE => ['/[A-Z]{1}\d{4}/'],
            self::ARMENIE => ['/\d{4}/'],
            self::ARUBA => ['/\d{4}[A-Z]{2}/'],
            self::AUSTRALIE => ['/\d{4}/'],
            self::AUTRICHE => ['/\d{4}/'],
            self::AZERBAIDJAN => ['/[A-Z]{2}\d{4}/'],
            self::BAHAMAS => ['/[A-Z]{4}/'],
            self::BAHREIN => ['/\d{4}/'],
            self::BANGLADESH => ['/\d{4}/'],
            self::BARBADE => ['/[A-Z]{2}\d{5}/'],
            self::BELGIQUE => ['/\d{4}/'],
            self::BELIZE => [],
            self::BENIN => [],
            self::BERMUDES => ['/[A-Z]{2}\d{2}/'],
            self::BIELORUSSIE => ['/\d{6}/'],
            self::BOLIVIE => ['/\d{5}/'],
            self::BONAIRE => ['/\d{4}[A-Z]{2}/'],
            self::BOSNIE_HERZEGOVINE => ['/\d{5}/'],
            self::BOTSWANA => [],
            self::BRESIL => ['/\d{8}/'],
            self::BHOUTAN => ['/\d{5}/'],
            self::BRUNEI_DARUSSALAM => ['/\d{6}/'],
        };//end match
    }
}
