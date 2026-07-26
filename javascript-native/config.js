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
 * Base path to the shared legal-libraries directory.
 *
 * In development this points to the repository root. In a distribution
 * package the libraries are copied next to the entry file.
 */
export const libraryBasePath = new URL(
  '../legal-libraries/',
  import.meta.url
).pathname;
