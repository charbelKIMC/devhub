document.addEventListener('DOMContentLoaded', () => {

    // ─── HAMBURGER ───────────────────────────────────────────
    const hamburger  = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');

    if (hamburger && mobileMenu) {

        hamburger.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = hamburger.classList.toggle('is-open');
            mobileMenu.classList.toggle('is-open', isOpen);
            document.body.style.overflow = isOpen ? 'hidden' : '';
        });

        document.addEventListener('click', (e) => {
            if (!mobileMenu.contains(e.target) && !hamburger.contains(e.target)) {
                hamburger.classList.remove('is-open');
                mobileMenu.classList.remove('is-open');
                document.body.style.overflow = '';
            }
        });

        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('is-open');
                mobileMenu.classList.remove('is-open');
                document.body.style.overflow = '';
            });
        });
    }

    // ─── DROPDOWNS DESKTOP ───────────────────────────────────
    document.querySelectorAll('.nav-dropdown').forEach(dropdown => {
        const toggle = dropdown.querySelector('.nav-dropdown-toggle');
        const menu   = dropdown.querySelector('.nav-dropdown-menu');
        if (!toggle || !menu) return;

        toggle.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const isActive = menu.classList.contains('active');
            document.querySelectorAll('.nav-dropdown-menu').forEach(m => m.classList.remove('active'));
            document.querySelectorAll('.nav-dropdown-toggle').forEach(t => t.classList.remove('active'));
            if (!isActive) {
                menu.classList.add('active');
                toggle.classList.add('active');
            }
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.nav-dropdown-menu').forEach(m => m.classList.remove('active'));
        document.querySelectorAll('.nav-dropdown-toggle').forEach(t => t.classList.remove('active'));
    });

    // ─── SMOOTH SCROLL ───────────────────────────────────────
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', (e) => {
            const href = anchor.getAttribute('href');
            if (href === '#') return;
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // ─── STACK CAROUSEL ──────────────────────────────────────
    class StackCarousel {
        constructor() {
            this.viewport = document.querySelector('.stack-viewport');
            this.cards    = [...document.querySelectorAll('.stack-card')];
            this.prevBtn  = document.getElementById('stackPrev');
            this.nextBtn  = document.getElementById('stackNext');
            this.dotsWrap = document.getElementById('stackDots');
            if (!this.viewport || !this.cards.length) return;
            this.current   = 0;
            this.total     = this.cards.length;
            this.autoTimer = null;
            this.dots = this.cards.map((_, i) => {
                const d = document.createElement('span');
                d.className = 'stack-dot' + (i === 0 ? ' active' : '');
                d.addEventListener('click', () => this.goTo(i));
                this.dotsWrap?.appendChild(d);
                return d;
            });
            this.init();
        }
        init() {
            this.positionCards();
            this.prevBtn?.addEventListener('click', () => this.goPrev());
            this.nextBtn?.addEventListener('click', () => this.goNext());
            document.querySelector('.stack-wrapper')?.addEventListener('mouseenter', () => this.stopAuto());
            document.querySelector('.stack-wrapper')?.addEventListener('mouseleave', () => this.startAuto());
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') this.goPrev();
                if (e.key === 'ArrowRight') this.goNext();
            });
            this.startAuto();
        }
        positionCards() {
            const vw = this.viewport.offsetWidth;
            const cardW = this.cards[0].offsetWidth;
            const centerX = (vw - cardW) / 2;
            const sideOffset = cardW * 0.72;
            const sideDropY = 40;
            this.cards.forEach((card, i) => {
                let posX, posY, zIndex;
                if (i === this.current) { posX = centerX; posY = 10; zIndex = 3; card.classList.add('active'); }
                else if (i === (this.current + 1) % this.total) { posX = centerX + sideOffset; posY = sideDropY; zIndex = 1; card.classList.remove('active'); }
                else if (i === (this.current - 1 + this.total) % this.total) { posX = centerX - sideOffset; posY = sideDropY; zIndex = 1; card.classList.remove('active'); }
                else { const d = (i - this.current + this.total) % this.total; posX = d <= this.total/2 ? centerX + sideOffset + cardW : centerX - sideOffset - cardW; posY = sideDropY; zIndex = 0; card.classList.remove('active'); }
                card.style.left = posX + 'px';
                card.style.top = posY + 'px';
                card.style.zIndex = zIndex;
            });
            this.dots.forEach((d, i) => d.classList.toggle('active', i === this.current));
        }
        goNext() { this.cards[this.current].style.zIndex = 2; this.current = (this.current + 1) % this.total; requestAnimationFrame(() => this.positionCards()); }
        goPrev() { this.cards[this.current].style.zIndex = 2; this.current = (this.current - 1 + this.total) % this.total; requestAnimationFrame(() => this.positionCards()); }
        goTo(i) { this.current = i; this.positionCards(); }
        startAuto() { this.stopAuto(); this.autoTimer = setInterval(() => this.goNext(), 4000); }
        stopAuto()  { clearInterval(this.autoTimer); }
    }

    new StackCarousel();
    window.addEventListener('resize', () => { if (window.resizeTimer) clearTimeout(window.resizeTimer); window.resizeTimer = setTimeout(() => new StackCarousel(), 200); });

    // ─── ANIMATIONS SCROLL ───────────────────────────────────
    const animateObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, i * 80);
                animateObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.pub-recente-card, .dev-card, .mentor-condition-item').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.45s ease, transform 0.45s ease';
        animateObserver.observe(el);
    });

});