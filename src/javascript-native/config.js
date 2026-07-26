/**
 * Default configuration values for D3V Legal Notices (native ES6).
 *
 * These values are merged with data-* attributes on each notice element.
 * Shortcode-style attributes take priority over these defaults.
 */
export const config = {
  // Legal library defaults
  country: 'ZAF',
  language: 'ENG',

  // Business details
  company: '',
  email: '',
  support_email: '',
  officer_email: '',
  address: '',
  tel: '',
  websiteurl: '',
  officer: '',
  regno: '',
  vatno: '',
  smp: '',
  returnwindow: '30',
  policyurl: '',
};

/**
 * Return candidate base URLs for the legal-libraries directory.
 *
 * In development the adapter lives under src/javascript-native/ and the shared
 * libraries are two levels up at the repository root. In a distribution package
 * the libraries are copied next to the entry files, so only one level up.
 * Fallbacks based on the page URL cover static demo pages served from different
 * depths.
 *
 * @returns {URL[]}
 */
export function getLibraryBaseCandidates() {
  const candidates = [
    new URL('../../legal-libraries/', import.meta.url),
    new URL('../legal-libraries/', import.meta.url),
  ];

  if (typeof location !== 'undefined') {
    candidates.push(
      new URL('../legal-libraries/', location.href),
      new URL('./legal-libraries/', location.href)
    );
  }

  return candidates;
}
