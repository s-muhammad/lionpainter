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
