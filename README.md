# Chronopost API PHP

This project is a PHP library for interacting with the Chronopost API. It simplifies the integration of Chronopost services into your PHP applications.

We make use of modern PHP features and currency values are given using `moneyphp/money`.

## Features

- [x] Delivery time estimation.
- [x] Parcel tracking.
- [ ] Generation of shipping labels.
- [ ] Management of pickup points.

## Requirements

- PHP >= 8.3
- The following PHP extensions must be enabled:
  - `ext-soap`
  - `ext-bcmath`
  - `ext-filter`
  - `ext-json`
- Composer
- A valid Chronopost API `user` and `password`.

## Installation

Install the library via Composer:

```bash
composer kwaadpepper/chronopost-api-php
```

## Tests

To run the tests, use PHPUnit:

```bash
vendor/bin/phpunit
```

## Contribution

Contributions are welcome! Please submit a pull request or open an issue to report a problem.

## Licence

This project is licensed under the [MIT](LICENSE).

## Aide

For any questions or assistance, contact us at <github@jeremydev.ovh>.
