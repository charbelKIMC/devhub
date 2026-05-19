// ============================================================
//  DevHub — dashboard.js
// ============================================================

document.addEventListener('DOMContentLoaded', () => {

    // ─── Filtres publications ───
    document.querySelectorAll('.filter-item[data-filter]').forEach(item => {
        item.addEventListener('click', () => {
            const url = new URL(window.location.href);
            url.searchParams.set('filter', item.dataset.filter);
            window.location.href = url.toString();
        });
    });

    // ─── Tabs collaborations ───
    document.querySelectorAll('.collab-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.collab-tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            const status = tab.dataset.tab;
            let visible = 0;
            document.querySelectorAll('.collab-card').forEach(card => {
                const show = status === 'all' || card.dataset.status === status;
                card.style.display = show ? '' : 'none';
                if (show) visible++;
            });
            const empty = document.getElementById('collabEmpty');
            if (empty) empty.style.display = visible === 0 ? 'block' : 'none';
        });
    });

    // ─── Filtre rôle ───
    document.getElementById('roleFilter')?.addEventListener('change', function() {
        const role = this.value;
        document.querySelectorAll('.collab-card').forEach(card => {
            card.style.display = (role === 'all' || card.dataset.role === role) ? '' : 'none';
        });
    });

    // ─── Commentaires inline toggle ───
    document.querySelectorAll('.comment-toggle-btn').forEach(btn => {
        if (btn.dataset.toggleInit) return;
        btn.dataset.toggleInit = '1';
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const form = document.getElementById('commentForm' + this.dataset.id);
            if (!form) return;
            const visible = form.style.display !== 'none';
            form.style.display = visible ? 'none' : 'block';
            if (!visible) form.querySelector('textarea')?.focus();
        });
    });

    // ─── Animation barres stats ───
    const topBars = document.querySelectorAll('.top-bar-fill');
    if (topBars.length > 0) {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    topBars.forEach(bar => {
                        const w = bar.style.width;
                        bar.style.width = '0';
                        setTimeout(() => { bar.style.width = w; }, 150);
                    });
                    observer.disconnect();
                }
            });
        }, { threshold: 0.3 });
        const container = topBars[0].closest('.stats-top-projects') || topBars[0];
        observer.observe(container);
    }

    // ─── Animation entrée cards ───
    const pubCards = document.querySelectorAll('.publication-card');
    if (pubCards.length > 0) {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.05 });
        pubCards.forEach((card, i) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(12px)';
            card.style.transition = `opacity 0.35s ease ${i * 0.06}s, transform 0.35s ease ${i * 0.06}s`;
            observer.observe(card);
        });
    }

});

// ─── CHAT RAPIDE ───────────────────────────────────────────
let quickChatPolling = null;
let quickLastId = 0;

document.querySelectorAll('.quick-chat-contact').forEach(contact => {
    contact.addEventListener('click', () => {
        const convId = contact.dataset.convId;
        const name   = contact.dataset.name;

        document.getElementById('quickChatConvId').value = convId;
        document.getElementById('quickChatName').textContent = name;
        document.getElementById('quickChatAvatar').textContent = name.charAt(0).toUpperCase();
        document.getElementById('quickChatBox').style.display = 'block';
        document.getElementById('quickChatMessages').innerHTML = '';
        document.getElementById('quickChatInput').focus();
        quickLastId = 0;

        document.querySelectorAll('.quick-chat-contact').forEach(c => c.style.background = '');
        contact.style.background = 'var(--purple-light)';

        clearInterval(quickChatPolling);
        pollQuickChat(convId);
        quickChatPolling = setInterval(() => pollQuickChat(convId), 3000);
    });
});

function pollQuickChat(convId) {
    fetch(`/messagerie/${convId}/messages/poll?last_id=${quickLastId}`, {
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(r => r.json())
    .then(data => {
        const box = document.getElementById('quickChatMessages');
        if (!box) return;
        data.messages.forEach(msg => {
            const div = document.createElement('div');
            div.style.cssText = `
                max-width:85%;padding:0.4rem 0.75rem;
                border-radius:12px;font-size:0.78rem;line-height:1.4;word-break:break-word;
                ${msg.moi
                    ? 'background:var(--purple);color:white;align-self:flex-end;border-radius:12px 12px 4px 12px;'
                    : 'background:#F3F4F6;color:#1F2937;align-self:flex-start;border-radius:12px 12px 12px 4px;'
                }
            `;
            div.textContent = msg.contenu;
            box.appendChild(div);
            quickLastId = msg.id;
        });
        if (data.messages.length) box.scrollTop = box.scrollHeight;
    })
    .catch(() => {});
}

document.getElementById('quickChatForm')?.addEventListener('submit', e => {
    e.preventDefault();
    const convId  = document.getElementById('quickChatConvId').value;
    const input   = document.getElementById('quickChatInput');
    const contenu = input.value.trim();
    if (!contenu || !convId) return;

    fetch(`/messagerie/${convId}/messages`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ contenu }),
    })
    .then(() => {
        input.value = '';
        pollQuickChat(convId);
    })
    .catch(() => {});
});

function closeQuickChat() {
    document.getElementById('quickChatBox').style.display = 'none';
    clearInterval(quickChatPolling);
    quickChatPolling = null;
    document.querySelectorAll('.quick-chat-contact').forEach(c => c.style.background = '');
}

// Nettoyage polling au déchargement de la page
window.addEventListener('beforeunload', () => {
    if (quickChatPolling) clearInterval(quickChatPolling);
});