# D3V Legal Notices — Todo / Release Checklist

## Release 2026.07.30

- [x] Create shared PHP renderer (`src/php-shared/D3vLegalRenderer.php`).
- [x] Create Laravel adapter (`src/laravel/`).
- [x] Create Symfony adapter (`src/symfony/`).
- [x] Create Drupal adapter (`src/drupal/`).
- [x] Create Joomla adapter (`src/joomla/`).
- [x] Create Magento adapter (`src/magento/`).
- [x] Create PrestaShop adapter (`src/prestashop/`).
- [x] Bump `VERSION` to trigger a new unified release.
- [x] Update release workflow to sync version into every new adapter package.
- [x] Update root `README.md` with the new platform list and links to per-platform READMEs.
- [x] Update `docs/SDD.md` architecture/packaging sections for the new adapters.
- [x] Update `docs/AGENT_MEMORY.md` with the new adapter locations and validation commands.
- [x] Lint every new PHP file (`php -l`).
- [x] Build EmDash plugin (`npm run build`).
- [x] Syntax-check native ES6 module (`node --check`).
- [x] Simulate release archives locally and confirm every ZIP contains `legal-libraries/` and the platform entry file.
- [x] Commit, tag, and push.

## General Rules

- The `VERSION` file is the single source of truth.
- Every adapter must be versioned identically and released together.
- Every release archive must include a self-contained copy of `legal-libraries/` and `LICENSE`.
- Documentation must be updated before any release is considered complete.
