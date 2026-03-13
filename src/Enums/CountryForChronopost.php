<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Enums;

/**
 * This file is part of the ChronopostApiPhp package.
 *
 * (c) Kwaadpepper <github.com/kwaadpepper>
 *
 * This file is licensed under the MIT License.
 *
 * From the Chronopost API documentation:
 * 4.2. LISTE DES CODES PAYS, FORMAT DES CODES POSTAUX, PRODUITS DE LIVRAISON
 */
enum CountryForChronopost: int
{
    case ACORES                    = 1;
    case AFGHANISTAN               = 2;
    case AFRIQUE_DU_SUD            = 3;
    case ALBANIE                   = 4;
    case ALGERIE                   = 5;
    case ALLEMAGNE                 = 6;
    case ANDORRE                   = 7;
    case ANGOLA                    = 8;
    case ANGUILLA                  = 9;
    case ANTIGUA_ET_BARBUDA        = 10;
    case ARABIE_SAOUDITE           = 11;
    case ARGENTINE                 = 12;
    case ARMENIE                   = 13;
    case ARUBA                     = 14;
    case AUSTRALIE                 = 15;
    case AUTRICHE                  = 16;
    case AZERBAIDJAN               = 17;
    case BAHAMAS                   = 18;
    case BAHREIN                   = 19;
    case BANGLADESH                = 20;
    case BARBADE                   = 21;
    case BELGIQUE                  = 22;
    case BELIZE                    = 23;
    case BENIN                     = 24;
    case BERMUDES                  = 25;
    case BIELORUSSIE               = 26;
    case BOLIVIE                   = 27;
    case BONAIRE                   = 28;
    case BOSNIE_HERZEGOVINE        = 29;
    case BOTSWANA                  = 30;
    case BRESIL                    = 31;
    case BHOUTAN                   = 32;
    case BRUNEI_DARUSSALAM         = 33;
    case BULGARIE                  = 34;
    case BURKINA_FASO              = 35;
    case BURUNDI                   = 36;
    case CAMBODGE                  = 37;
    case CAMEROUN                  = 38;
    case CANADA                    = 39;
    case CANARIES_ILES             = 40;
    case CAP_VERT                  = 41;
    case CAYMAN_ILES               = 42;
    case REPUBLIQUE_DU_CONGO       = 43;
    case CENTRAFRIQUE              = 44;
    case CHILI                     = 45;
    case CHINE                     = 46;
    case CHYPRE                    = 47;
    case COLOMBIE                  = 48;
    case COMORES                   = 49;
    case CONGO                     = 50;
    case COOK_ILES                 = 51;
    case COREE_DU_NORD             = 52;
    case COREE_DU_SUD              = 53;
    case COSTA_RICA                = 54;
    case COTE_D_IVOIRE             = 55;
    case CROATIE                   = 56;
    case CUBA                      = 57;
    case DANEMARK                  = 58;
    case DJIBOUTI                  = 59;
    case DOMINIQUE_ILE_DE_LA       = 60;
    case EGYPTE                    = 61;
    case EL_SALVADOR               = 62;
    case EMIRATS_ARABE_UNIS        = 63;
    case EQUATEUR                  = 64;
    case ERYTHREE                  = 65;
    case ESPAGNE                   = 66;
    case ESTONIE                   = 67;
    case ETATS_UNIS_D_AMERIQUE     = 68;
    case ETHIOPIE                  = 69;
    case FEROE_ILES                = 70;
    case FIDJI                     = 71;
    case FINLANDE                  = 72;
    case FRANCE                    = 73;
    case GABON                     = 74;
    case GAMBIE                    = 75;
    case GEORGIE                   = 76;
    case GHANA                     = 77;
    case GIBRALTAR                 = 78;
    case GRANDE_BRETAGNE           = 79;
    case GROENLAND                 = 80;
    case GRECE                     = 81;
    case GRENADE_ILE_DE_LA         = 82;
    case GUADELOUPE                = 83;
    case GUAM_ILE_DE               = 84;
    case GUATEMALA                 = 85;
    case GUERNESEY_ILE_DE          = 86;
    case GEORGIE_DU_SUD            = 87;
    case GUINEE                    = 88;
    case GUINEE_BISSAU             = 89;
    case GUINEE_EQUATORIALE        = 90;
    case GUYANA                    = 91;
    case GUYANE                    = 92;
    case HAITI                     = 93;
    case HONDURAS                  = 94;
    case HONG_KONG                 = 95;
    case HONGRIE                   = 96;
    case INDE                      = 97;
    case INDONESIE                 = 98;
    case IRAN                      = 99;
    case IRAQ                      = 100;
    case IRLANDE                   = 101;
    case ISLANDE                   = 102;
    case ISRAEL                    = 103;
    case ITALIE                    = 104;
    case JAMAIQUE                  = 105;
    case JAPON                     = 106;
    case JERSEY_ILE_DE             = 107;
    case JORDANIE                  = 108;
    case KAZAKHSTAN                = 109;
    case KENYA                     = 110;
    case KIRGHIZISTAN              = 111;
    case KIRIBATI_ILES             = 112;
    case KOWEIT                    = 113;
    case LAOS                      = 114;
    case LESOTHO                   = 115;
    case LETTONIE                  = 116;
    case LIBAN                     = 117;
    case LIBERIA                   = 118;
    case LIBYE                     = 119;
    case LICHTENSTEIN              = 120;
    case LITUANIE                  = 121;
    case LUXEMBOURG                = 122;
    case MARSHALL_ILES             = 123;
    case MACAO                     = 124;
    case MACEDOINE                 = 125;
    case MADAGASCAR                = 126;
    case MADERE_ILE_DE             = 127;
    case MALAISIE                  = 128;
    case MALAWI                    = 129;
    case MALDIVES                  = 130;
    case MALI                      = 131;
    case MALTE                     = 132;
    case MAROC                     = 133;
    case MARTINIQUE                = 134;
    case MAURICE_ILE               = 135;
    case MAURITANIE                = 136;
    case MAYOTTE                   = 137;
    case MEXIQUE                   = 138;
    case MICRONESIE                = 139;
    case MOLDAVIE                  = 140;
    case MONACO                    = 141;
    case MONGOLIE                  = 142;
    case MONTSERRAT_ILE_DE         = 143;
    case MOZAMBIQUE                = 144;
    case MYANMAR_BIRMANIE          = 145;
    case NAMIBIE                   = 146;
    case NAURU_ILES                = 147;
    case NEPAL                     = 148;
    case NICARAGUA                 = 149;
    case NIGER                     = 150;
    case NIGERIA                   = 151;
    case NORVEGE                   = 152;
    case NOUVELLE_CALEDONIE        = 153;
    case NOUVELLE_ZELANDE          = 154;
    case OMAN_SULTANAT             = 155;
    case OUGANDA                   = 156;
    case OUZBEKISTAN               = 157;
    case PAKISTAN                  = 158;
    case PANAMA                    = 159;
    case PALESTINE                 = 160;
    case PALAU_ILES                = 161;
    case PAPOUASIE_NLLE_GUINEE     = 162;
    case PARAGUAY                  = 163;
    case PAYS_BAS                  = 164;
    case PEROU                     = 165;
    case PHILIPPINES               = 166;
    case POLOGNE                   = 167;
    case POLYNESIE_FRANCAISE       = 168;
    case PORTO_RICO                = 169;
    case PORTUGAL                  = 170;
    case QATAR                     = 171;
    case REPUBLIQUE_TCHEQUE        = 172;
    case REUNION_ILE_DE_LA         = 173;
    case ROUMANIE                  = 174;
    case RUSSIE                    = 175;
    case RWANDA                    = 176;
    case SAINT_CHRISTOPHE_ET_NEVIS = 177;
    case SAINT_DOMINGUE            = 178;
    case SAINT_MARTIN_FRANCAISE    = 179;
    case SAINT_MARTIN_HOLLANDAISE  = 180;
    case SAINT_PIERRE_ET_MIQUELON  = 181;
    case SAINT_VINCENT_ILES        = 182;
    case SAINTE_LUCIE              = 183;
    case SALOMON_ILES              = 184;
    case SAIPAN_ILE_DE             = 185;
    case SAMOA_AMERICAINES_ILES    = 186;
    case SAMOA_OCCIDENTALES        = 187;
    case SAN_MARIN                 = 188;
    case SAO_TOME_ET_PRINCIPE      = 189;
    case SENEGAL                   = 190;
    case SERBIE                    = 191;
    case SEYCHELLES_ILES           = 192;
    case SIERRA_LEONE              = 193;
    case SINGAPOUR                 = 194;
    case SLOVAQUIE                 = 195;
    case SLOVENIE                  = 196;
    case SOMALIE                   = 197;
    case SOUDAN                    = 198;
    case SRI_LANKA                 = 199;
    case SUEDE                     = 200;
    case SUISSE                    = 201;
    case SURINAME                  = 202;
    case SWAZILAND                 = 203;
    case SYRIE                     = 204;
    case TADJIKISTAN               = 205;
    case TAIWAN                    = 206;
    case TANZANIE                  = 207;
    case TCHAD                     = 208;
    case THAILANDE                 = 209;
    case TOGO                      = 210;
    case TIMOR_ORIENTAL            = 211;
    case TONGA_ILES_DU             = 212;
    case TRINITE_ET_TOBAGO         = 213;
    case TUNISIE                   = 214;
    case TURKMENISTAN              = 215;
    case TURQUES_ET_CAIQUES_ILES   = 216;
    case TURQUIE                   = 217;
    case TUVALU_ILE_DE             = 218;
    case UKRAINE                   = 219;
    case URUGUAY                   = 220;
    case VANUATU_ILES_VU           = 221;
    case VATICAN                   = 222;
    case VENEZUELA                 = 223;
    case VIERGES_AMERICAINES_ILES  = 224;
    case VIERGES_BRITANNIQUES_ILE  = 225;
    case VIETNAM                   = 226;
    case WALLIS_ET_FUTUNA          = 227;
    case YEMEN                     = 228;
    case ZAMBIE                    = 229;
    case ZIMBABWE                  = 230;

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
            self::SAINT_MARTIN_FRANCAISE    => 'MF',
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
     * @return array<string>
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity.MaxExceeded
     */
    public function getPostCodeFormats(): array
    {
        // phpcs:enable
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
            self::BRUNEI_DARUSSALAM => ['/[A-Z]{2}\d{4}/'],
            self::BULGARIE => ['/\d{4}/'],
            self::BURKINA_FASO => [],
            self::BURUNDI => [],
            self::CAMBODGE => ['/\d{5}/'],
            self::CAMEROUN => [],
            self::CANADA => ['/[A-Z]\d[A-Z]\d[A-Z]\d/'],
            self::CANARIES_ILES => ['/\d{5}/'],
            self::CAP_VERT => ['/\d{4}/'],
            self::CAYMAN_ILES => ['/[A-Z]{2}\d\-\d{4}/'],
            self::REPUBLIQUE_DU_CONGO => [],
            self::CENTRAFRIQUE => [],
            self::CHILI => ['/\d{7}/'],
            self::CHINE => ['/\d{6}/'],
            self::CHYPRE => ['/\d{4}/'],
            self::COLOMBIE => ['/\d{6}/'],
            self::COMORES => [],
            self::CONGO => [],
            self::COOK_ILES => [],
            self::COREE_DU_NORD => ['/\d{2}/'],
            self::COREE_DU_SUD => ['/[A-Z]{2}\d{2}/'],
            self::COSTA_RICA => ['/\d{5}/'],
            self::COTE_D_IVOIRE => ['/\d{5}/'],
            self::CROATIE => ['/\d{5}/'],
            self::CUBA => ['/\d{5}/'],
            self::DANEMARK => ['/\d{4}/'],
            self::DJIBOUTI => [],
            self::DOMINIQUE_ILE_DE_LA => ['/\d{5}/'],
            self::EGYPTE => ['/\d{5}/'],
            self::EL_SALVADOR => ['/\d{4}/'],
            self::EMIRATS_ARABE_UNIS => [],
            self::EQUATEUR => ['/\d{6}/'],
            self::ERYTHREE => [],
            self::ESPAGNE => ['/\d{5}/'],
            self::ESTONIE => ['/\d{5}/'],
            self::ETATS_UNIS_D_AMERIQUE => ['/\d{5}/'],
            self::ETHIOPIE => ['/\d{4}/'],
            self::FEROE_ILES => ['/\d{3}/'],
            self::FIDJI => [],
            self::FINLANDE => ['/\d{5}/'],
            self::FRANCE => ['/\d{5}/'],
            self::GABON => [],
            self::GAMBIE => [],
            self::GEORGIE => ['/\d{4}/'],
            self::GHANA => [],
            self::GIBRALTAR => ['/[A-Z]{2}\d{2} \d[A-Z]{2}/'],
            self::GRANDE_BRETAGNE => [
            '/[A-Z]{2}\d{2} \d[A-Z]{2}/',
            '/[A-Z]{2}\d[A-Z] \d[A-Z]{2}/',
            '/[A-Z]\d[A-Z] \d[A-Z]{2}/',
            '/[A-Z]\d \d[A-Z]{2}/',
            '/[A-Z]\d{2} \d[A-Z]{2}/',
            '/[A-Z]{2}\d \d[A-Z]{2}/',
            ],
            self::GROENLAND => ['/\d{4}/'],
            self::GRECE => ['/\d{5}/'],
            self::GRENADE_ILE_DE_LA => [],
            self::GUADELOUPE => ['/\d{5}/'],
            self::GUAM_ILE_DE => ['/\d{5}/'],
            self::GUATEMALA => ['/\d{5}/'],
            self::GUERNESEY_ILE_DE => ['/[A-Z]{2}\d \d[A-Z]{2}/'],
            self::GEORGIE_DU_SUD => ['/[A-Z]{2}\d{2}[A-Z]{2}/'],
            self::GUINEE => ['/\d{3}/'],
            self::GUINEE_BISSAU => ['/\d{4}/'],
            self::GUINEE_EQUATORIALE => [],
            self::GUYANA => [],
            self::GUYANE => ['/\d{5}/'],
            self::HAITI => ['/[A-Z]{2}\d{4}/'],
            self::HONDURAS => ['/\d{5}/'],
            self::HONG_KONG => ['/\d{6}/'],
            self::HONGRIE => ['/\d{4}/'],
            self::INDE => ['/\d{6}/'],
            self::INDONESIE => ['/\d{5}/'],
            self::IRAN => ['/\d{5}/'],
            self::IRAQ => ['/\d{5}/'],
            self::IRLANDE => ['/[A-Z]\d{2}[A-Z] \d{3}/'],
            self::ISLANDE => ['/\d{3}/'],
            self::ISRAEL => ['/\d{5}/'],
            self::ITALIE => ['/\d{5}/'],
            self::JAMAIQUE => ['/[A-Z]{5}\d{2}/'],
            self::JAPON => ['/\d{7}/'],
            self::JERSEY_ILE_DE => ['/[A-Z]{2}\d{2}[A-Z]{2}/'],
            self::JORDANIE => ['/\d{5}/'],
            self::KAZAKHSTAN => ['/\d{6}/'],
            self::KENYA => ['/\d{5}/'],
            self::KIRGHIZISTAN => ['/\d{6}/'],
            self::KIRIBATI_ILES => [],
            self::KOWEIT => ['/\d{5}/'],
            self::LAOS => ['/\d{5}/'],
            self::LESOTHO => ['/\d{3}/'],
            self::LETTONIE => ['/\d{4}/'],
            self::LIBAN => ['/\d{4}/'],
            self::LIBERIA => ['/\d{4}/'],
            self::LIBYE => ['/\d{5}/'],
            self::LICHTENSTEIN => ['/\d{4}/'],
            self::LITUANIE => ['/\d{5}/'],
            self::LUXEMBOURG => ['/\d{4}/'],
            self::MARSHALL_ILES => ['/\d{5}/'],
            self::MACAO => ['/\d{6}/'],
            self::MACEDOINE => ['/\d{4}/'],
            self::MADAGASCAR => ['/\d{3}/'],
            self::MADERE_ILE_DE => ['/\d{7}/'],
            self::MALAISIE => ['/\d{5}/'],
            self::MALAWI => [],
            self::MALDIVES => ['/\d{5}/'],
            self::MALI => [],
            self::MALTE => ['/[A-Z]{2} \d{4}/'],
            self::MAROC => ['/\d{5}/'],
            self::MARTINIQUE => ['/\d{5}/'],
            self::MAURICE_ILE => ['/[A-Z]\d{4}/'],
            self::MAURITANIE => [],
            self::MAYOTTE => ['/\d{5}/'],
            self::MEXIQUE => ['/\d{5}/'],
            self::MICRONESIE => ['/\d{5}/'],
            self::MOLDAVIE => ['/\d{4}/'],
            self::MONACO => ['/\d{5}/'],
            self::MONGOLIE => ['/\d{5}/'],
            self::MONTSERRAT_ILE_DE => ['/[A-Z]{3}\d{4}/'],
            self::MOZAMBIQUE => ['/\d{4}/'],
            self::MYANMAR_BIRMANIE => ['/\d{5}/'],
            self::NAMIBIE => [],
            self::NAURU_ILES => [],
            self::NEPAL => ['/\d{5}/'],
            self::NICARAGUA => ['/\d{5}/'],
            self::NIGER => ['/\d{4}/'],
            self::NIGERIA => ['/\d{6}/'],
            self::NORVEGE => ['/\d{4}/'],
            self::NOUVELLE_CALEDONIE => ['/\d{5}/'],
            self::NOUVELLE_ZELANDE => ['/\d{4}/'],
            self::OMAN_SULTANAT => ['/\d{3}/'],
            self::OUGANDA => [],
            self::OUZBEKISTAN => ['/\d{6}/'],
            self::PAKISTAN => ['/\d{5}/'],
            self::PANAMA => ['/\d{4}/'],
            self::PALESTINE => ['/\d{5}/'],
            self::PALAU_ILES => ['/\d{5}/'],
            self::PAPOUASIE_NLLE_GUINEE => ['/\d{3}/'],
            self::PARAGUAY => ['/\d{4}/'],
            self::PAYS_BAS => ['/\d{4}[A-Z]{2}/'],
            self::PEROU => ['/\d{5}/'],
            self::PHILIPPINES => ['/\d{4}/'],
            self::POLOGNE => ['/\d{5}/'],
            self::POLYNESIE_FRANCAISE => ['/\d{5}/'],
            self::PORTO_RICO => ['/\d{5}/'],
            self::PORTUGAL => ['/\d{7}/'],
            self::QATAR => [],
            self::REPUBLIQUE_TCHEQUE => ['/\d{5}/'],
            self::REUNION_ILE_DE_LA => ['/\d{5}/'],
            self::ROUMANIE => ['/\d{6}/'],
            self::RUSSIE => ['/\d{6}/'],
            self::RWANDA => [],
            self::SAINT_CHRISTOPHE_ET_NEVIS => [],
            self::SAINT_DOMINGUE => [],
            self::SAINT_MARTIN_FRANCAISE => ['/\d{5}/'],
            self::SAINT_MARTIN_HOLLANDAISE => ['/\d{4}[A-Z]{2}/'],
            self::SAINT_PIERRE_ET_MIQUELON => ['/\d{5}/'],
            self::SAINT_VINCENT_ILES => ['/[A-Z]{2}\d{4}/'],
            self::SAINTE_LUCIE => [],
            self::SALOMON_ILES => [],
            self::SAIPAN_ILE_DE => [],
            self::SAMOA_AMERICAINES_ILES => ['/\d{5}/'],
            self::SAMOA_OCCIDENTALES => ['/\d{5}/'],
            self::SAN_MARIN => ['/\d{5}/'],
            self::SAO_TOME_ET_PRINCIPE => ['/\d{5}/'],
            self::SENEGAL => ['/\d{5}/'],
            self::SERBIE => ['/\d{5}/'],
            self::SEYCHELLES_ILES => ['/[A-Z]{2}\d{2}/'],
            self::SIERRA_LEONE => [],
            self::SINGAPOUR => ['/\d{6}/'],
            self::SLOVAQUIE => ['/\d{5}/'],
            self::SLOVENIE => ['/\d{4}/'],
            self::SOMALIE => [],
            self::SOUDAN => ['/\d{5}/'],
            self::SRI_LANKA => ['/\d{5}/'],
            self::SUEDE => ['/\d{5}/'],
            self::SUISSE => ['/\d{4}/'],
            self::SURINAME => [],
            self::SWAZILAND => ['/[A-Z]\d{3}/'],
            self::SYRIE => [],
            self::TADJIKISTAN => ['/\d{3}/'],
            self::TAIWAN => ['/\d{5}/'],
            self::TANZANIE => ['/\d{5}/'],
            self::TCHAD => ['/\d{5}/'],
            self::THAILANDE => ['/\d{5}/'],
            self::TOGO => [],
            self::TIMOR_ORIENTAL => [],
            self::TONGA_ILES_DU => [],
            self::TRINITE_ET_TOBAGO => [],
            self::TUNISIE => ['/\d{4}/'],
            self::TURKMENISTAN => ['/\d{6}/'],
            self::TURQUES_ET_CAIQUES_ILES => [],
            self::TURQUIE => ['/\d{5}/'],
            self::TUVALU_ILE_DE => [],
            self::UKRAINE => ['/\d{5}/'],
            self::URUGUAY => ['/\d{5}/'],
            self::VANUATU_ILES_VU => [],
            self::VATICAN => ['/\d{5}/'],
            self::VENEZUELA => ['/\d{4}/'],
            self::VIERGES_AMERICAINES_ILES => ['/\d{5}/'],
            self::VIERGES_BRITANNIQUES_ILE => ['/[A-Z]{2}\d{4}/'],
            self::VIETNAM => ['/\d{6}/'],
            self::WALLIS_ET_FUTUNA => ['/\d{5}/'],
            self::YEMEN => ['/[A-Z]{4}/'],
            self::ZAMBIE => ['/\d{5}/'],
            self::ZIMBABWE => [],
        };//end match
    }
}
