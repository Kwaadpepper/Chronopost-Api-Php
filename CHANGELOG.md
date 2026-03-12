##  (2026-03-12)

### Features

* add DeliverySlotService — search slots, confirm and geocode (Phase 7) ([2639634](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/26396341d6c0464c3e3bda6bc2ca1b956324492f))
* add DTOs and factories for V7 shipping, reservation, and ESD operations ([df03c15](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/df03c15d7956ff559d44961f9096a9f7921acf5a))
* add pickup/ESD service (Phase 4) ([89c9ffc](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/89c9ffcb719f42c5de67a372fade72a24f4f0711))
* add shipping label retrieval service ([715e78b](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/715e78b452d78d5ed6a6c6c75dda966710ffa783))
* extend QuickCost/Calculate services with calculateProductsV2 and getProducts (Phase 5) ([7f13f55](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/7f13f55b2b587b5e8b4ff4cde25461f362db936f))
* extend RelayPointService — coordinate search, ID lookup, detail & international detail (Phase 6) ([42835b9](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/42835b9bbc2202a9bb8fe4e7ca2e38ed01b065b5))
* **facade:** add Calculate, DeliverySlot, Pickup, Relay, Shipping, and Tracking facades ([95a6a65](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/95a6a65ab3d7f7bd604d7e5e17f5b32dd1da25d9))
* implement 6 V7 shipping operations (Phase 2) ([5ffa921](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/5ffa92177a009b29895e40da31553deda6b12ac7))
* **tracking:** add DTOs and factories for cancel, search, and POD\n\n10 new DTOs:\n- CancelResult, CancelListResult (cancel operations)\n- ParcelInfo, SearchTrackResult (track search)\n- ParcelEvents, SenderRefTrackResult (sender ref tracking)\n- EsdTrackResult (ESD tracking)\n- ProofOfDelivery, ParcelProofOfDelivery, ProofOfDeliveryByRef (POD)\n\n6 new factories:\n- TrackCancelResultFactory, TrackCancelListResultFactory\n- TrackSearchResultFactory, TrackWithSenderRefFactory\n- ProofOfDeliveryFactory, ProofOfDeliveryByRefFactory" ([8f8f349](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/8f8f34997dc941ab0812ca140aec5727a3f4694e))
* **tracking:** implement cancel, extended search, POD services and facade\n\nNew services:\n- TrackCancelService: cancelSkybill(), cancelListSkybill()\n- ProofOfDeliveryService: searchPod(), searchPodWithSenderRef()\n\nExtended TrackSearchService with:\n- trackSearch(), trackWithSenderRef(), trackEsd()\n- Constructor injection for testability\n\nUpdated TrackingServiceInterface with new method contracts.\n\nFacade (ChronopostApi) now exposes:\n- cancelShipment(), cancelMultipleShipments()\n- trackBySearchQuery(), trackBySenderReference(), trackEsd()\n- searchProofOfDelivery(), searchProofOfDeliveryBySenderRef()" ([01fbe08](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/01fbe08b8107ed06e4614f772c55db4f39b0a780))

### Bug Fixes

* handle unknown ESD error codes and nullable relay opening hours ([7bd33b6](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/7bd33b692e68f6718b1c1b0536770d8326717af5))
* **tracking:** correct TrackingException PSR-4 namespace\n\nThe namespace was Kwaadpepper\\ChronopostApiPhp\\Exceptions instead of\nKwaadpepper\\ChronopostApiPhp\\Exceptions\\Tracking, which violated PSR-4\nautoloading for the file location at src/Exceptions/Tracking/." ([86cc89c](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/86cc89c768e90e49cf521967f03574afcb409c5c))

## [3.0.1](https://github.com/Kwaadpepper/Chronopost-Api-Php/compare/3.0.0...3.0.1) (2026-03-10)

### Bug Fixes

* **parcel:** allow nullable mobile phone in value objects ([1eb8823](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/1eb8823268738a2eedf0472ff95d0d026f3161c9))

## [3.0.0](https://github.com/Kwaadpepper/Chronopost-Api-Php/compare/e52de5410a4978bbaa2e35b3e74aab9b938bc62f...3.0.0) (2026-02-20)

### Features

* add isShop2Shop method to ChronopostProductCode ([5c72adc](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/5c72adcde67e7ba083d937aeba6ba5fcabd0ba79))
* Add product calculation functionality ([acd21be](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/acd21be50ecdf303a6c78b175b620f3ef65aa131))
* Add QuickCostV3 feature ([e52de54](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/e52de5410a4978bbaa2e35b3e74aab9b938bc62f))
* Ajouter la classe MultiParcelPart pour gérer les parties d'un envoi multiparcel ([71d332a](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/71d332a5f72792378118a6a2b3ebfce287438d66))
* Ajouter la méthode singleParcelV4 pour créer un envoi de colis unique ([9206151](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/9206151526aef0dab280de9b28affac5b2f3f087))
* Ajouter le paramètre ShipperValue à la méthode multiParcelV4OneShipperToOneRecipient et mettre à jour la classe MultiParcelPart ([1611d71](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/1611d713c0659dc16aef081d36cbc9207fbaf1fc))
* **calculate:** implement calculation services and factories ([d763124](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/d76312499270852c5f4e555040280aea8a09e129))
* Enhance Product and ProductList DTOs ([378a86a](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/378a86a16fa55cd8d189834df5b9dea75ad9ac31))
* **relay:** add relay point search functionality ([afe3b76](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/afe3b76879da43543485ffe55fea965fc4a60535))
* Renommer la méthode multiParcelV4 en multiParcelV4ToOneRecipient et ajouter le paramètre RecipientValue ([446b0aa](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/446b0aa0f96a9709c1a7c8b1bed28dc67f1f17a6))
* Update ChronopostProductCode enumeration ([7af35df](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/7af35dfee440c4098a9b48bda8bb14cf559ffe7d))
* Update SkyBillOutputMode enum cases ([80ed82c](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/80ed82cbf5fb72f5416d962e28b5c4d7cfa7fb0c))

### Bug Fixes

* Ajouter la gestion de la nullité pour les paramètres optionnels dans singleParcelV4 ([76feeeb](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/76feeebff44e7982e8b8175e98dd0feb79ae7c9f))
* Ajouter la vérification de la nullité pour PDF Etiquette dans mapToMultiParcelValue ([b4090c0](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/b4090c08654274d004581a9fd1914deaf3893588))
* **exceptions:** allow nullable previous throwable in constructors ([0680909](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/0680909b2fa9c05f993396738b663ca48b2a275b))
* **factory:** improve pickup date parsing in EsdInfo ([1ff7e52](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/1ff7e529d1a119fcad21ff352ccc587249f98e3b))
* **ProductCode:** remove leading zero validation ([4c07921](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/4c07921c7af8656669d887b046ff0b58e23e9a0d))
* Utiliser le code à une lettre pour le type d'expédition dans QuickCostService ([65bfd63](https://github.com/Kwaadpepper/Chronopost-Api-Php/commit/65bfd6311c41a7c27bf9643de7c4063ae3dbad5a))
