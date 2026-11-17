import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

/* Sticky header state */
Alpine.data('siteHeader', () => ({
    scrolled: false,
    open: false,
    init() {
        this.update();
        window.addEventListener('scroll', () => this.update(), { passive: true });
    },
    update() {
        this.scrolled = window.scrollY > 24;
    },
}));

/* Testimonial carousel with gentle autoplay */
Alpine.data('testimonials', (total = 1) => ({
    active: 0,
    total,
    timer: null,
    init() {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        this.timer = setInterval(() => this.next(), 8000);
    },
    next() {
        this.active = (this.active + 1) % this.total;
    },
    previous() {
        this.active = (this.active - 1 + this.total) % this.total;
    },
    go(index) {
        this.active = index;
        clearInterval(this.timer);
    },
}));

/* Before / after paint comparison */
Alpine.data('beforeAfter', () => ({
    position: 50,
    dragging: false,
    rect: null,
    start(event) {
        this.dragging = true;
        this.rect = this.$refs.frame.getBoundingClientRect();
        this.move(event);
    },
    move(event) {
        if (!this.dragging || !this.rect) return;
        const point = event.touches ? event.touches[0] : event;
        const x = Math.min(Math.max(point.clientX - this.rect.left, 0), this.rect.width);
        this.position = Math.round((x / this.rect.width) * 100);
    },
    stop() {
        this.dragging = false;
    },
    keyboard(event) {
        if (event.key === 'ArrowLeft') this.position = Math.max(0, this.position - 4);
        if (event.key === 'ArrowRight') this.position = Math.min(100, this.position + 4);
    },
}));

/* Accessible FAQ accordion */
Alpine.data('faqAccordion', (initial = null) => ({
    open: initial,
    toggle(index) {
        this.open = this.open === index ? null : index;
    },
}));

/* Quote request wizard */
Alpine.data('quoteWizard', (initial = 1) => ({
    step: initial,
    total: 3,
    errors: {},
    get progress() {
        return (this.step / this.total) * 100;
    },
    validateStep() {
        this.errors = {};
        const form = this.$refs.form;

        if (this.step === 1) {
            if (!form.querySelector('input[name="service"]:checked')) this.errors.service = 'Please choose a service.';
            if (!this.$refs.property_type?.value) this.errors.property_type = 'Please choose a property type.';
            if (!this.$refs.suburb?.value.trim()) this.errors.suburb = 'Please tell us your suburb.';
        }
        if (this.step === 2) {
            if (!form.querySelector('input[name="timeline"]:checked')) this.errors.timeline = 'Please choose a timeline.';
        }
        if (this.step === 3) {
            if (!this.$refs.name?.value.trim()) this.errors.name = 'Please enter your name.';
            if (!this.$refs.email?.value.includes('@')) this.errors.email = 'Please enter a valid email.';
            if (!this.$refs.phone?.value.trim()) this.errors.phone = 'Please enter a phone number.';
        }
        return Object.keys(this.errors).length === 0;
    },
    next() {
        if (this.validateStep()) {
            this.step = Math.min(this.step + 1, this.total);
            this.scrollTop();
        }
    },
    previous() {
        this.step = Math.max(this.step - 1, 1);
        this.scrollTop();
    },
    submit(event) {
        if (!this.validateStep()) {
            event.preventDefault();
        }
    },
    scrollTop() {
        this.$root.scrollIntoView({ behavior: 'smooth', block: 'start' });
    },
}));

/* Portfolio filtering */
Alpine.data('portfolioFilter', (initial = 'All') => ({
    active: initial,
    matches(category) {
        return this.active === 'All' || this.active === category;
    },
    choose(category) {
        this.active = category;
    },
}));

/* Admin SEO notebook — keyword add/edit modal */
Alpine.data('seoKeywordModal', (config = {}) => ({
    open: Boolean(config.open),
    fields: { ...(config.defaults ?? {}), ...(config.fields ?? {}) },
    defaults: config.defaults ?? {},
    storeUrl: config.storeUrl ?? '',
    updateUrl: config.updateUrl ?? '',
    get editing() {
        return Boolean(this.fields.id);
    },
    get action() {
        return this.editing ? this.updateUrl.replace('__ID__', this.fields.id) : this.storeUrl;
    },
    init() {
        if (this.open) this.focus();
    },
    showAdd() {
        this.fields = { ...this.defaults };
        this.open = true;
        this.focus();
    },
    showEdit(fields) {
        this.fields = { ...this.defaults, ...fields };
        this.open = true;
        this.focus();
    },
    close() {
        this.open = false;
    },
    focus() {
        this.$nextTick(() => this.$refs.firstInput?.focus());
    },
}));

/* Admin SEO notebook — task add/edit modal */
Alpine.data('seoTaskModal', (config = {}) => ({
    open: Boolean(config.open),
    fields: { ...(config.defaults ?? {}), ...(config.fields ?? {}) },
    defaults: config.defaults ?? {},
    storeUrl: config.storeUrl ?? '',
    updateUrl: config.updateUrl ?? '',
    get editing() {
        return Boolean(this.fields.id);
    },
    get action() {
        return this.editing ? this.updateUrl.replace('__ID__', this.fields.id) : this.storeUrl;
    },
    init() {
        if (this.open) this.focus();
    },
    showAdd() {
        this.fields = { ...this.defaults };
        this.open = true;
        this.focus();
    },
    showEdit(fields) {
        this.fields = { ...this.defaults, ...fields };
        this.open = true;
        this.focus();
    },
    close() {
        this.open = false;
    },
    focus() {
        this.$nextTick(() => this.$refs.firstInput?.focus());
    },
}));

/* Admin SEO notebook — keyword relationship graph */
Alpine.data('seoGraph', () => {
    const svgNs = 'http://www.w3.org/2000/svg';

    let nodes = [];
    let edges = [];
    let nodeIndex = {};
    let nodeElements = {};
    let edgeElements = [];
    let width = 900;
    let height = 520;

    return {
        selected: null,
        count: 0,
        groupBy: 'target',
        showText: true,
        showLabels: true,
        zoom: 1,
        panX: 0,
        panY: 0,
        dragId: null,
        pan: null,
        palette: ['#c6a45f', '#0ea5e9', '#10b981', '#8b5cf6', '#f59e0b', '#6366f1', '#14b8a6', '#ef4444', '#64748b'],

        init() {
            let payload = { nodes: [], edges: [] };

            try {
                payload = JSON.parse(this.$el.dataset.graph || '{}');
            } catch (error) {
                payload = { nodes: [], edges: [] };
            }

            nodes = (payload.nodes ?? []).map((node) => ({
                ...node,
                radius: 6 + Math.min(node.degree || 0, 8) * 1.25,
                shortLabel: node.label.length > 30 ? `${node.label.slice(0, 29)}…` : node.label,
                x: 0,
                y: 0,
            }));
            edges = (payload.edges ?? []).map((edge) => ({ ...edge, key: `${edge.source}-${edge.target}` }));
            nodeIndex = Object.fromEntries(nodes.map((node) => [node.id, node]));
            this.count = nodes.length;
            this.selected = nodeIndex[payload.defaultId] ?? nodes[0] ?? null;

            this.buildScene();
            this.measure();

            window.addEventListener('resize', () => this.measure());
        },

        buildScene() {
            const edgeLayer = this.$refs.edgeLayer;
            const nodeLayer = this.$refs.nodeLayer;

            if (!edgeLayer || !nodeLayer) return;

            edgeLayer.replaceChildren();
            nodeLayer.replaceChildren();
            edgeElements = [];
            nodeElements = {};

            for (const edge of edges) {
                const line = document.createElementNS(svgNs, 'line');
                line.setAttribute('stroke-linecap', 'round');

                edgeLayer.appendChild(line);
                edgeElements.push({ edge, line });
            }

            for (const node of nodes) {
                const group = document.createElementNS(svgNs, 'g');
                group.setAttribute('role', 'button');
                group.setAttribute('tabindex', '0');
                group.style.cursor = 'pointer';
                group.style.outline = 'none';

                const halo = document.createElementNS(svgNs, 'circle');
                halo.setAttribute('fill', 'transparent');

                const bubble = document.createElementNS(svgNs, 'circle');
                bubble.setAttribute('fill', '#ffffff');

                const core = document.createElementNS(svgNs, 'circle');
                core.setAttribute('r', '2.6');

                const label = document.createElementNS(svgNs, 'text');
                label.setAttribute('text-anchor', 'middle');
                label.setAttribute('style', 'font-size:9px;pointer-events:none;user-select:none');
                label.textContent = node.shortLabel;

                group.append(halo, bubble, core, label);
                group.addEventListener('pointerdown', (event) => {
                    event.stopPropagation();
                    this.startDrag(event, node);
                });
                group.addEventListener('click', (event) => {
                    event.stopPropagation();
                    this.select(node);
                });
                group.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        this.select(node);
                    }
                });

                nodeLayer.appendChild(group);
                nodeElements[node.id] = { group, halo, bubble, core, label };
            }
        },

        select(node) {
            this.selected = node;
            this.paint();
        },

        groupOf(node) {
            return (node.groups && node.groups[this.groupBy]) || 'بدون گروه';
        },

        groupColor(node) {
            const key = this.groupOf(node);
            let hash = 0;

            for (let index = 0; index < key.length; index++) {
                hash = (hash * 31 + key.charCodeAt(index)) % 100000;
            }

            return this.palette[hash % this.palette.length];
        },

        measure() {
            const canvas = this.$refs.canvas;

            if (!canvas) return;

            const rect = canvas.getBoundingClientRect();

            if (rect.width > 120) width = rect.width;
            if (rect.height > 120) height = rect.height;

            this.relayout();
        },

        relayout() {
            this.layout();
            this.paint();
        },

        /* Simple force layout: repulsion between nodes, springs for textual
           links and light attraction towards each group's centre. */
        layout() {
            if (nodes.length === 0) return;

            const cx = width / 2;
            const cy = height / 2;
            const padding = 44;

            const points = nodes.map((node) => ({ id: node.id, x: node.x, y: node.y }));
            const byId = Object.fromEntries(points.map((point) => [point.id, point]));
            const keys = [...new Set(points.map((point) => this.groupOf(nodeIndex[point.id])))];
            const centres = {};

            keys.forEach((key, index) => {
                const angle = (index / Math.max(keys.length, 1)) * Math.PI * 2 - Math.PI / 2;
                const spread = Math.min(width, height) * (keys.length > 1 ? 0.26 : 0.1);

                centres[key] = { x: cx + Math.cos(angle) * spread, y: cy + Math.sin(angle) * spread };
            });

            points.forEach((point, index) => {
                const centre = centres[this.groupOf(nodeIndex[point.id])];
                const angle = index * 2.399963 + Math.random() * 0.7;
                const radius = 16 + Math.sqrt(index + 1) * 7;

                point.x = centre.x + Math.cos(angle) * radius;
                point.y = centre.y + Math.sin(angle) * radius;
            });

            const iterations = 240 + Math.min(points.length * 3, 200);

            for (let step = 0; step < iterations; step++) {
                const cooling = 1 - step / iterations;

                for (let i = 0; i < points.length; i++) {
                    const a = points[i];

                    for (let j = i + 1; j < points.length; j++) {
                        const b = points[j];
                        let dx = a.x - b.x;
                        let dy = a.y - b.y;
                        let distanceSq = dx * dx + dy * dy;

                        if (distanceSq < 0.5) {
                            dx = (Math.random() - 0.5) * 2;
                            dy = (Math.random() - 0.5) * 2;
                            distanceSq = dx * dx + dy * dy || 1;
                        }

                        const distance = Math.sqrt(distanceSq);
                        const force = Math.min(2600 / distanceSq, 12);
                        const fx = (dx / distance) * force;
                        const fy = (dy / distance) * force;

                        a.x += fx;
                        a.y += fy;
                        b.x -= fx;
                        b.y -= fy;
                    }
                }

                for (const edge of edges) {
                    const a = byId[edge.source];
                    const b = byId[edge.target];

                    if (!a || !b) continue;

                    const dx = b.x - a.x;
                    const dy = b.y - a.y;
                    const distance = Math.sqrt(dx * dx + dy * dy) || 1;
                    const ideal = 54 + 34 / Math.max(edge.weight, 1);
                    const force = (distance - ideal) * 0.02 * Math.min(edge.weight, 3);
                    const fx = (dx / distance) * force;
                    const fy = (dy / distance) * force;

                    a.x += fx;
                    a.y += fy;
                    b.x -= fx;
                    b.y -= fy;
                }

                for (const point of points) {
                    const centre = centres[this.groupOf(nodeIndex[point.id])];

                    point.x += (centre.x - point.x) * 0.014 * cooling;
                    point.y += (centre.y - point.y) * 0.014 * cooling;
                    point.x += (cx - point.x) * 0.0025;
                    point.y += (cy - point.y) * 0.0025;
                    point.x = Math.min(width - padding, Math.max(padding, point.x));
                    point.y = Math.min(height - padding, Math.max(padding, point.y));
                }
            }

            for (const point of points) {
                const node = nodeIndex[point.id];

                node.x = Math.round(point.x);
                node.y = Math.round(point.y);
            }
        },

        paint() {
            for (const { edge, line } of edgeElements) {
                const source = nodeIndex[edge.source];
                const target = nodeIndex[edge.target];

                if (!source || !target) continue;

                const active = this.selected?.id === edge.source || this.selected?.id === edge.target;

                line.setAttribute('x1', source.x);
                line.setAttribute('y1', source.y);
                line.setAttribute('x2', target.x);
                line.setAttribute('y2', target.y);
                line.setAttribute('stroke', active ? '#d6bc80' : '#e2e8f0');
                line.setAttribute('stroke-width', active ? 1.6 : 1);
                line.style.display = this.showText ? '' : 'none';
            }

            for (const node of nodes) {
                const element = nodeElements[node.id];

                if (!element) continue;

                const isSelected = this.selected?.id === node.id;

                element.group.setAttribute('transform', `translate(${node.x} ${node.y})`);
                element.group.setAttribute('aria-label', node.label);
                element.group.setAttribute('aria-pressed', isSelected ? 'true' : 'false');
                element.halo.setAttribute('r', node.radius + 5);
                element.bubble.setAttribute('r', node.radius);
                element.bubble.setAttribute('stroke', isSelected ? '#c6a45f' : '#cbd5e1');
                element.bubble.setAttribute('stroke-width', isSelected ? 2.5 : 1.5);
                element.core.setAttribute('fill', this.groupColor(node));
                element.label.setAttribute('y', node.radius + 11);
                element.label.setAttribute('fill', isSelected ? '#0c1e2e' : '#94a3b8');
                element.label.setAttribute('font-weight', isSelected ? '700' : '500');
                element.label.style.display = this.showLabels || isSelected ? '' : 'none';
            }
        },

        zoomBy(factor) {
            this.zoom = Math.min(2.6, Math.max(0.45, Number((this.zoom * factor).toFixed(2))));
        },

        resetView() {
            this.zoom = 1;
            this.panX = 0;
            this.panY = 0;
        },

        wheelZoom(event) {
            this.zoomBy(event.deltaY < 0 ? 1.1 : 0.9);
        },

        startPan(event) {
            if (event.pointerType === 'mouse' && event.button !== 0) return;

            this.pan = { x: event.clientX, y: event.clientY, panX: this.panX, panY: this.panY };
            this.capture(event);
        },

        startDrag(event, node) {
            this.dragId = node.id;
            this.select(node);
            this.capture(event);
        },

        movePointer(event) {
            if (this.dragId) {
                const rect = this.$refs.canvas.getBoundingClientRect();
                const node = nodeIndex[this.dragId];

                if (node) {
                    node.x = Math.round(Math.min(width - 24, Math.max(24, (event.clientX - rect.left - this.panX) / this.zoom)));
                    node.y = Math.round(Math.min(height - 24, Math.max(24, (event.clientY - rect.top - this.panY) / this.zoom)));
                    this.paint();
                }

                return;
            }

            if (!this.pan) return;

            this.panX = this.pan.panX + (event.clientX - this.pan.x);
            this.panY = this.pan.panY + (event.clientY - this.pan.y);
        },

        endPointer() {
            this.dragId = null;
            this.pan = null;
        },

        capture(event) {
            const svg = this.$refs.svg;

            if (!svg || event.pointerId === undefined) return;

            try {
                svg.setPointerCapture(event.pointerId);
            } catch (error) {
                /* Pointer capture is an enhancement; ignore unsupported events. */
            }
        },
    };
});

/* Scroll reveal */
const revealObserver = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                revealObserver.unobserve(entry.target);
            }
        });
    },
    { threshold: 0.12 },
);

document.querySelectorAll('[data-reveal]').forEach((element) => revealObserver.observe(element));

Alpine.start();
