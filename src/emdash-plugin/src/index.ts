import { definePlugin } from "emdash";
import type { PluginDescriptor } from "emdash";

export interface D3vLegalOptions extends Record<string, unknown> {
  /** Default ISO3 country code (e.g. ZAF, GBR). */
  country?: string;
  /** Default three-letter language code (e.g. eng, afr). */
  language?: string;
}

/**
 * Descriptor factory used in astro.config.mjs.
 */
export function d3vLegalNoticesPlugin(options: D3vLegalOptions = {}): PluginDescriptor {
  return {
    id: "d3v-legal-notices",
    version: "2026.07.29",
    format: "native",
    entrypoint: "@d3vdigital/emdash-legal-notices",
    options,
  };
}

/**
 * Runtime factory called by EmDash for every request.
 */
export function createPlugin(options: D3vLegalOptions = {}) {
  return definePlugin({
    id: "d3v-legal-notices",
    version: "2026.07.29",

    capabilities: ["hooks.page-fragments:register"],
    allowedHosts: [],
    storage: {},

    hooks: {
      "page:fragments": async () => {
        // Inject the D3V Legal Notices stylesheet on every public page.
        // Consumers can opt out by overriding this hook in their own plugin.
        return {
          kind: "html",
          placement: "head",
          html: `<style data-d3v-legal-styles>
.d3v-legal {
  --d3v-legal-font: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
  --d3v-legal-text: #1a1a1a;
  --d3v-legal-muted: #595959;
  --d3v-legal-border: #e0e0e0;
  --d3v-legal-surface: #ffffff;
  --d3v-legal-radius: 0.5rem;
  --d3v-legal-spacing: 1rem;
  --d3v-legal-link: #005fcc;
  font-family: var(--d3v-legal-font);
  color: var(--d3v-legal-text);
  background-color: var(--d3v-legal-surface);
  border: 1px solid var(--d3v-legal-border);
  border-radius: var(--d3v-legal-radius);
  padding: var(--d3v-legal-spacing);
  margin-block: var(--d3v-legal-spacing);
  line-height: 1.6;
}
.d3v-legal p, .d3v-legal ul { margin-block: 0 1rem; }
.d3v-legal p:last-child, .d3v-legal ul:last-child { margin-bottom: 0; }
.d3v-legal strong { font-weight: 600; }
.d3v-legal ul { padding-left: 1.5rem; }
.d3v-legal li { margin-block: 0.25rem; }
.d3v-legal a { color: var(--d3v-legal-link); text-decoration: underline; text-underline-offset: 0.15em; }
.d3v-legal a:hover, .d3v-legal a:focus { text-decoration: none; }
.d3v-legal__sources { margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--d3v-legal-border); font-size: 0.875rem; color: var(--d3v-legal-muted); }
.d3v-legal__sources summary { cursor: pointer; font-weight: 500; }
.d3v-legal__sources ul { margin-top: 0.5rem; padding-left: 1.25rem; }
@media (prefers-color-scheme: dark) {
  .d3v-legal { --d3v-legal-text: #e6e6e6; --d3v-legal-muted: #a3a3a3; --d3v-legal-border: #404040; --d3v-legal-surface: #1a1a1a; --d3v-legal-link: #6ab8ff; }
}
</style>`,
          key: "d3v-legal-styles",
        };
      },
    },
  });
}

export default createPlugin;
