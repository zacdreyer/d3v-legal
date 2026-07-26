/**
 * D3V Legal Notices — Native ES6 renderer.
 *
 * Renders legal notices into HTML elements from shared JSON libraries.
 * Supports any framework or static site via data-* attributes and a
 * centralized config.js defaults file.
 */

import { config, getLibraryBaseCandidates } from './config.js';

const KNOWN_FIELDS = new Set([
  'notice',
  'country',
  'language',
  'company',
  'email',
  'support_email',
  'officer_email',
  'address',
  'tel',
  'smp',
  'websiteurl',
  'officer',
  'regno',
  'vatno',
  'returnwindow',
  'policyurl',
]);

/**
 * Escape a string for safe insertion into HTML text content.
 *
 * @param {string} text
 * @returns {string}
 */
function escapeHtml(text) {
  return String(text)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

/**
 * Normalize a URL for use in an HTML attribute.
 *
 * @param {string} url
 * @returns {string}
 */
function escapeUrl(url) {
  url = String(url).trim();
  if (!url) return '';

  if (
    !/^https?:\/\//i.test(url) &&
    !/^mailto:/i.test(url) &&
    !/^tel:/i.test(url) &&
    url.charAt(0) !== '/' &&
    url.charAt(0) !== '#'
  ) {
    url = `https://${url}`;
  }

  return url
    .replace(/&amp;/g, '&')
    .replace(/&/g, '&amp;');
}

/**
 * Resolve the effective value of an attribute.
 *
 * @param {Object} atts
 * @param {string} key
 * @returns {string}
 */
function getAtt(atts, key) {
  if (key === 'year') {
    return String(new Date().getFullYear());
  }

  if (atts[key] !== undefined && atts[key] !== '') {
    return String(atts[key]);
  }

  return '';
}

/**
 * Extract top-level placeholder names from a template string.
 *
 * @param {string} template
 * @returns {string[]}
 */
function extractPlaceholders(template) {
  const matches = String(template).matchAll(/\{\{([a-z0-9_]+)/gi);
  return Array.from(new Set(Array.from(matches, (m) => m[1])));
}

/**
 * Resolve a single placeholder against the attributes.
 *
 * @param {string} key
 * @param {Object} atts
 * @returns {string}
 */
function getPlaceholderValue(key, atts) {
  return getAtt(atts, key);
}

/**
 * Replace placeholders in a template, supporting fallback syntax.
 *
 * @param {string} template
 * @param {Object} atts
 * @returns {string}
 */
function replacePlaceholders(template, atts) {
  let rendered = String(template);

  for (let i = 0; i < 5; i += 1) {
    const previous = rendered;
    rendered = rendered.replace(
      /\{\{([a-z0-9_]+)(?:\|\|([^}]*)?)?\}\}/gi,
      (match, key, fallback = '') => {
        const value = getPlaceholderValue(key, atts);
        return value !== '' ? escapeHtml(value) : escapeHtml(fallback);
      }
    );
    if (rendered === previous) break;
  }

  return rendered;
}

/**
 * Determine whether a template would receive a non-empty value.
 *
 * @param {string} template
 * @param {Object} atts
 * @returns {boolean}
 */
function hasReplacementValue(template, atts) {
  return extractPlaceholders(template).some(
    (key) => getPlaceholderValue(key, atts) !== ''
  );
}

/**
 * Determine whether a section should be rendered.
 *
 * @param {Object} section
 * @param {Object} atts
 * @returns {boolean}
 */
function isSectionVisible(section, atts) {
  if (!section.condition) return true;

  if (section.condition === 'any') {
    if (!Array.isArray(section.fields)) return true;
    return section.fields.some((field) =>
      hasReplacementValue(field.value ?? '', atts)
    );
  }

  return getAtt(atts, section.condition) !== '';
}

/**
 * Render a single section based on its type.
 *
 * @param {Object} section
 * @param {Object} atts
 * @returns {string}
 */
function renderSection(section, atts) {
  if (!isSectionVisible(section, atts)) return '';

  const type = section.type ?? 'paragraph';

  switch (type) {
    case 'list':
      return renderListSection(section, atts);
    case 'contact_list':
      return renderContactListSection(section, atts);
    case 'joined_fields':
      return renderJoinedFieldsSection(section, atts);
    case 'policy_link':
      return renderPolicyLinkSection(section, atts);
    default:
      return renderParagraphSection(section, atts);
  }
}

function renderParagraphSection(section, atts) {
  let html = '';

  if (section.title && String(section.title).trim() !== '') {
    html += `<p><strong>${escapeHtml(section.title)}</strong></p>`;
  }

  if (section.template) {
    html += `<p>${replacePlaceholders(section.template, atts)}</p>`;
  }

  return html;
}

function renderListSection(section, atts) {
  let html = '';

  if (section.title && String(section.title).trim() !== '') {
    html += `<p><strong>${escapeHtml(section.title)}</strong></p>`;
  }

  if (Array.isArray(section.items)) {
    html += '<ul>';
    for (const item of section.items) {
      html += `<li>${replacePlaceholders(item, atts)}</li>`;
    }
    html += '</ul>';
  }

  return html;
}

function renderContactListSection(section, atts) {
  const items = [];

  if (Array.isArray(section.fields)) {
    for (const field of section.fields) {
      if (!field || typeof field.value !== 'string') continue;

      if (field.condition) {
        if (field.condition === 'any') {
          if (!hasReplacementValue(field.value, atts)) continue;
        } else if (getAtt(atts, field.condition) === '') {
          continue;
        }
      }

      const rendered = replacePlaceholders(field.value, atts);
      const text = rendered.replace(/<[^>]+>/g, '').trim();
      if (text !== '') items.push(rendered);
    }
  }

  if (items.length === 0) return '';

  let html = '';
  if (section.title && String(section.title).trim() !== '') {
    html += `<p><strong>${escapeHtml(section.title)}</strong></p>`;
  }

  html += '<ul>';
  for (const item of items) {
    html += `<li>${item}</li>`;
  }
  html += '</ul>';

  return html;
}

function renderJoinedFieldsSection(section, atts) {
  const separator = section.separator ?? ', ';
  const tokens = extractPlaceholders(section.template ?? '');
  const parts = tokens
    .map((token) => getPlaceholderValue(token, atts))
    .filter((value) => value !== '')
    .map((value) => escapeHtml(value));

  if (parts.length === 0) return '';

  let html = '';
  if (section.title && String(section.title).trim() !== '') {
    html += `<p><strong>${escapeHtml(section.title)}</strong></p>`;
  }

  html += `<p>${parts.join(escapeHtml(separator))}</p>`;
  return html;
}

function renderPolicyLinkSection(section, atts) {
  const policyurl = escapeUrl(getAtt(atts, 'policyurl'));
  if (policyurl === '') return '';

  const label = escapeHtml(section.label ?? 'Read full policy');
  return `<p><a href="${policyurl}" target="_blank" rel="noopener noreferrer">${label}</a></p>`;
}

/**
 * Render the legal sources disclosure for a notice.
 *
 * @param {string[]} sources
 * @returns {string}
 */
function renderLegalSources(sources) {
  if (!Array.isArray(sources) || sources.length === 0) return '';

  const items = sources.map((s) => `<li>${escapeHtml(s)}</li>`).join('');
  return `
    <details class="d3v-legal__sources">
      <summary>Legal sources</summary>
      <ul>${items}</ul>
    </details>
  `;
}

/**
 * Render a complete notice into HTML.
 *
 * @param {string} noticeKey
 * @param {Object} notice
 * @param {Object} atts
 * @returns {string}
 */
function renderNotice(noticeKey, notice, atts) {
  const id = `d3v-legal-${noticeKey.toLowerCase().replace(/[^a-z0-9]+/g, '-')}`;
  let html = `<div id="${id}" class="d3v-legal d3v-legal-${noticeKey.toLowerCase()}">`;

  if (Array.isArray(notice.sections)) {
    for (const section of notice.sections) {
      html += renderSection(section, atts);
    }
  }

  html += renderLegalSources(notice.legal_sources);
  html += '</div>';

  return html;
}

/**
 * Build candidate URLs for a legal library JSON file.
 *
 * @param {string} country
 * @param {string} language
 * @returns {string[]}
 */
function buildLibraryUrls(country, language) {
  const filename = `${String(country).toUpperCase()}-${String(language).toLowerCase()}-legals.json`;
  return getLibraryBaseCandidates().map((base) => new URL(filename, base).href);
}

/**
 * Load a legal library from the network.
 *
 * @param {string} country
 * @param {string} language
 * @returns {Promise<Object|null>}
 */
async function loadLibrary(country, language) {
  for (const url of buildLibraryUrls(country, language)) {
    try {
      const response = await fetch(url);
      if (response.ok) return await response.json();
    } catch {
      // Try next candidate.
    }
  }

  return null;
}

/**
 * Parse data-* attributes from a DOM element into the attribute map.
 *
 * @param {HTMLElement} element
 * @returns {Object}
 */
function parseElementAttributes(element) {
  const atts = { ...config };

  for (const key of KNOWN_FIELDS) {
    const value = element.dataset[key];
    if (value !== undefined) {
      atts[key] = String(value).trim();
    }
  }

  // Role-specific email fallbacks
  if (atts.support_email === '' && atts.email !== '') {
    atts.support_email = atts.email;
  }
  if (atts.officer_email === '' && atts.email !== '') {
    atts.officer_email = atts.email;
  }

  if (atts.returnwindow === '') {
    atts.returnwindow = '30';
  }

  return atts;
}

/**
 * Render a legal notice into the target element.
 *
 * @param {HTMLElement} element
 */
async function renderInto(element) {
  const atts = parseElementAttributes(element);
  const noticeKey = atts.notice;

  if (!noticeKey) {
    element.innerHTML = '<p class="d3v-legal__error">Missing data-notice attribute.</p>';
    return;
  }

  const country = String(atts.country || config.country).toUpperCase();
  const language = String(atts.language || config.language).toLowerCase();

  const library = await loadLibrary(country, language);
  if (!library || !library[country] || !library[country].notices || !library[country].notices[noticeKey]) {
    element.innerHTML = `<p class="d3v-legal__error">Notice "${escapeHtml(noticeKey)}" is not available for ${escapeHtml(country)}/${escapeHtml(language)}.</p>`;
    return;
  }

  element.innerHTML = renderNotice(noticeKey, library[country].notices[noticeKey], atts);
}

/**
 * Auto-initialize all elements with [data-d3v-legal] on DOM ready.
 */
function init() {
  const targets = document.querySelectorAll('[data-d3v-legal]');
  for (const element of targets) {
    renderInto(element);
  }
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', init);
} else {
  init();
}

export { renderInto, renderNotice, loadLibrary };
