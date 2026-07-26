/**
 * Shared legal notice rendering logic for the EmDash plugin.
 *
 * Mirrors the behaviour of the WordPress and native ES6 adapters while
 * staying free of framework/runtime imports so it can run during Astro SSR.
 */

import { readFileSync } from 'node:fs';
import { join, dirname } from 'node:path';
import { fileURLToPath } from 'node:url';

const __dirname = dirname(fileURLToPath(import.meta.url));

export interface NoticeSection {
  type?: 'paragraph' | 'list' | 'contact_list' | 'joined_fields' | 'policy_link';
  title?: string;
  template?: string;
  items?: string[];
  fields?: Array<{ value: string; condition?: string }>;
  condition?: string;
  separator?: string;
  label?: string;
}

export interface NoticeDefinition {
  legal_sources?: string[];
  sections: NoticeSection[];
}

export interface LegalLibrary {
  [country: string]: {
    notices: Record<string, NoticeDefinition>;
  };
}

export interface NoticeAtts {
  notice: string;
  country: string;
  language: string;
  company: string;
  email: string;
  support_email: string;
  officer_email: string;
  address: string;
  tel: string;
  smp: string;
  websiteurl: string;
  officer: string;
  regno: string;
  vatno: string;
  returnwindow: string;
  policyurl: string;
}

const DEFAULTS: NoticeAtts = {
  notice: '',
  country: 'ZAF',
  language: 'ENG',
  company: '',
  email: '',
  support_email: '',
  officer_email: '',
  address: '',
  tel: '',
  smp: '',
  websiteurl: '',
  officer: '',
  regno: '',
  vatno: '',
  returnwindow: '30',
  policyurl: '',
};

/**
 * Resolve effective attribute values from component props.
 */
export function resolveAtts(props: Partial<NoticeAtts>): NoticeAtts {
  const atts = { ...DEFAULTS };

  for (const key of Object.keys(DEFAULTS) as Array<keyof NoticeAtts>) {
    const value = props[key];
    if (value !== undefined && value !== '') {
      atts[key] = String(value);
    }
  }

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
 * Resolve the directory containing shared legal libraries.
 *
 * Development: the plugin lives under src/emdash-plugin/, so the shared
 * libraries are four levels up at the repository root. Distribution: the
 * libraries are bundled two levels above the compiled lib file.
 */
export function getLibraryDirectory(): string {
  const candidates = [
    join(__dirname, '..', '..', '..', '..', 'legal-libraries'),
    join(__dirname, '..', '..', 'legal-libraries'),
  ];

  for (const dir of candidates) {
    try {
      readFileSync(join(dir, 'ZAF-eng-legals.json'));
      return dir;
    } catch {
      // Try the next candidate.
    }
  }

  // Default to the first candidate when neither exists so callers still get a
  // meaningful path for diagnostics.
  return candidates[0];
}

/**
 * Load a legal library JSON file at build/runtime.
 */
export function loadLibrary(country: string, language: string): LegalLibrary | null {
  const libraryDir = getLibraryDirectory();
  const filename = `${country.toUpperCase()}-${language.toLowerCase()}-legals.json`;

  try {
    const filePath = join(libraryDir, filename);
    const content = readFileSync(filePath, 'utf-8');
    return JSON.parse(content) as LegalLibrary;
  } catch {
    return null;
  }
}

function escapeHtml(text: string): string {
  return String(text)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

function escapeUrl(url: string): string {
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

  return url.replace(/&amp;/g, '&').replace(/&/g, '&amp;');
}

function getAtt(atts: NoticeAtts, key: string): string {
  if (key === 'year') return String(new Date().getFullYear());
  const value = atts[key as keyof NoticeAtts];
  return value !== undefined ? String(value) : '';
}

function extractPlaceholders(template: string): string[] {
  const matches = String(template).matchAll(/\{\{([a-z0-9_]+)/gi);
  return Array.from(new Set(Array.from(matches, (m) => m[1])));
}

function replacePlaceholders(template: string, atts: NoticeAtts): string {
  let rendered = String(template);

  for (let i = 0; i < 5; i += 1) {
    const previous = rendered;
    rendered = rendered.replace(
      /\{\{([a-z0-9_]+)(?:\|\|([^}]*)?)?\}\}/gi,
      (match, key, fallback = '') => {
        const value = getAtt(atts, key);
        return value !== '' ? escapeHtml(value) : escapeHtml(fallback);
      }
    );
    if (rendered === previous) break;
  }

  return rendered;
}

function hasReplacementValue(template: string, atts: NoticeAtts): boolean {
  return extractPlaceholders(template).some((key) => getAtt(atts, key) !== '');
}

function isSectionVisible(section: NoticeSection, atts: NoticeAtts): boolean {
  if (!section.condition) return true;

  if (section.condition === 'any') {
    if (!Array.isArray(section.fields)) return true;
    return section.fields.some((field) =>
      hasReplacementValue(field.value ?? '', atts)
    );
  }

  return getAtt(atts, section.condition) !== '';
}

function renderParagraphSection(section: NoticeSection, atts: NoticeAtts): string {
  let html = '';
  if (section.title && section.title.trim() !== '') {
    html += `<p><strong>${escapeHtml(section.title)}</strong></p>`;
  }
  if (section.template) {
    html += `<p>${replacePlaceholders(section.template, atts)}</p>`;
  }
  return html;
}

function renderListSection(section: NoticeSection, atts: NoticeAtts): string {
  let html = '';
  if (section.title && section.title.trim() !== '') {
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

function renderContactListSection(section: NoticeSection, atts: NoticeAtts): string {
  const items: string[] = [];

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
  if (section.title && section.title.trim() !== '') {
    html += `<p><strong>${escapeHtml(section.title)}</strong></p>`;
  }

  html += '<ul>';
  for (const item of items) {
    html += `<li>${item}</li>`;
  }
  html += '</ul>';

  return html;
}

function renderJoinedFieldsSection(section: NoticeSection, atts: NoticeAtts): string {
  const separator = section.separator ?? ', ';
  const tokens = extractPlaceholders(section.template ?? '');
  const parts = tokens
    .map((token) => getAtt(atts, token))
    .filter((value) => value !== '')
    .map((value) => escapeHtml(value));

  if (parts.length === 0) return '';

  let html = '';
  if (section.title && section.title.trim() !== '') {
    html += `<p><strong>${escapeHtml(section.title)}</strong></p>`;
  }

  html += `<p>${parts.join(escapeHtml(separator))}</p>`;
  return html;
}

function renderPolicyLinkSection(section: NoticeSection, atts: NoticeAtts): string {
  const policyurl = escapeUrl(atts.policyurl);
  if (policyurl === '') return '';

  const label = escapeHtml(section.label ?? 'Read full policy');
  return `<p><a href="${policyurl}" target="_blank" rel="noopener noreferrer">${label}</a></p>`;
}

function renderSection(section: NoticeSection, atts: NoticeAtts): string {
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

function renderLegalSources(sources: string[] | undefined): string {
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
 * Render a complete notice into an HTML string.
 */
export function renderNotice(noticeKey: string, notice: NoticeDefinition, atts: NoticeAtts): string {
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
