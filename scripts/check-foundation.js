/**
 * CI check: the foundation stylesheet's Carbon partial list stays exactly
 * the documented "always-needed" set (spec: "CSS tree-shaking + per-block
 * CSS architecture"). Component CSS beyond the site chrome belongs in the
 * owning block's style.scss in awt-blocks, not here — this check makes
 * silent foundation growth a deliberate, reviewed decision.
 *
 * Run: npm run check:foundation
 */

const fs = require('fs');
const path = require('path');

const EXPECTED = [
	'config',
	'reset',
	'theme',
	'zone',
	'type',
	'spacing',
	'layout',
	'components/ui-shell',
	'components/link',
];

const scss = fs.readFileSync(
	path.resolve(__dirname, '..', 'src', 'foundation.scss'),
	'utf8'
);
const used = [
	...scss.matchAll(/@use\s+'@carbon\/styles\/scss\/([a-z0-9/-]+)'/g),
].map((m) => m[1]);

const missing = EXPECTED.filter((p) => !used.includes(p));
const extra = used.filter((p) => !EXPECTED.includes(p));

if (missing.length || extra.length) {
	if (missing.length) {
		console.error(
			`✖ foundation.scss is missing documented partials: ${missing.join(', ')}`
		);
	}
	if (extra.length) {
		console.error(
			`✖ foundation.scss has undocumented partials: ${extra.join(', ')} — move them to the owning block, or update EXPECTED + the spec.`
		);
	}
	process.exit(1);
}
console.log(
	'✓ foundation.scss matches the documented always-needed partial list.'
);
