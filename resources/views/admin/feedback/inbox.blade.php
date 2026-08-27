@extends('layouts.main.master')
@section('style')
    <style>
        .fb-inbox {
            position: relative;
            display: flex;
            height: calc(100vh - 140px);
            min-height: 560px;
            background: #fff;
            border: 1px solid #E5E9F0;
            border-radius: 14px;
            overflow: hidden;
        }

        /* ── List pane ── */
        .fb-list-pane {
            width: 360px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #E5E9F0;
            background: #FAFBFC;
        }

        .fb-list-header {
            padding: 1rem 1.1rem .75rem;
            border-bottom: 1px solid #E5E9F0;
            background: #fff;
        }

        .fb-list-title {
            font-family: 'Sora', sans-serif;
            font-weight: 800;
            font-size: 1.05rem;
            color: #0D1B2A;
            margin-bottom: .75rem;
        }

        .fb-search {
            width: 100%;
            height: 38px;
            border: 1.5px solid #E5E9F0;
            border-radius: 20px;
            padding: 0 1rem 0 2.2rem;
            font-size: .82rem;
            outline: none;
            background: #F5F7FA url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2394A3B8' stroke-width='2'><circle cx='11' cy='11' r='8'/><line x1='21' y1='21' x2='16.65' y2='16.65'/></svg>") no-repeat 12px center;
            transition: border-color .15s;
        }

        .fb-search:focus {
            border-color: #1565D8;
            background-color: #fff;
        }

        .fb-tabs {
            display: flex;
            gap: .4rem;
            margin-top: .75rem;
        }

        .fb-tab {
            flex: 1;
            text-align: center;
            padding: .45rem 0;
            border-radius: 8px;
            font-size: .78rem;
            font-weight: 700;
            color: #64748B;
            cursor: pointer;
            border: 1.5px solid transparent;
            transition: all .15s;
        }

        .fb-tab.active {
            background: #EFF6FF;
            color: #1565D8;
            border-color: #BFDBFE;
        }

        .fb-list-scroll {
            flex: 1;
            overflow-y: auto;
        }

        .fb-row {
            display: flex;
            gap: .65rem;
            padding: .85rem 1.1rem;
            border-bottom: 1px solid #EEF1F5;
            cursor: pointer;
            transition: background .12s;
            position: relative;
        }

        .fb-row:hover {
            background: #F5F8FF;
        }

        .fb-row.active {
            background: #EFF6FF;
        }

        .fb-row.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #1565D8;
        }

        .fb-row-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #E8F0FE;
            color: #1565D8;
            font-family: 'Sora', sans-serif;
            font-weight: 800;
            font-size: .82rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .fb-row-body {
            flex: 1;
            min-width: 0;
        }

        .fb-row-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: .5rem;
        }

        .fb-row-name {
            font-weight: 700;
            font-size: .85rem;
            color: #0D1B2A;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-decoration: none;
        }

        .fb-row-name:hover {
            color: #1565D8;
            text-decoration: underline;
        }

        .fb-row-time {
            font-size: .68rem;
            color: #94A3B8;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .fb-row-msg {
            font-size: .78rem;
            color: #64748B;
            margin-top: .15rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .fb-row-bottom {
            display: flex;
            align-items: center;
            gap: .4rem;
            margin-top: .4rem;
        }

        .fb-cat-pill {
            font-size: .65rem;
            font-weight: 700;
            padding: .1rem .55rem;
            border-radius: 20px;
            text-transform: capitalize;
        }

        .cat-report {
            background: #FEE2E2;
            color: #991B1B;
        }

        .cat-feedback {
            background: #D1FAE5;
            color: #065F46;
        }

        .cat-complaint {
            background: #FEF3C7;
            color: #92400E;
        }

        .cat-transfer_issue {
            background: #EDE9FE;
            color: #5B21B6;
        }

        .cat-others {
            background: #E5E7EB;
            color: #4B5563;
        }

        .fb-unread-badge {
            margin-left: auto;
            background: #EF4444;
            color: #fff;
            font-size: .68rem;
            font-weight: 800;
            min-width: 18px;
            height: 18px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 .35rem;
        }

        .fb-list-empty {
            text-align: center;
            padding: 3rem 1rem;
            color: #94A3B8;
            font-size: .82rem;
        }

        .fb-list-loading {
            text-align: center;
            padding: .85rem;
            font-size: .78rem;
            color: #94A3B8;
        }

        .fb-awaiting-pill {
            font-size: .65rem;
            font-weight: 700;
            padding: .1rem .55rem;
            border-radius: 20px;
            background: #FEF3C7;
            color: #92400E;
        }

        /* ── Thread pane ── */
        .fb-thread-pane {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .fb-thread-empty {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #94A3B8;
            gap: .75rem;
        }

        .fb-thread-empty svg {
            opacity: .4;
        }

        .fb-thread-empty p {
            font-size: .88rem;
        }

        .fb-thread-header {
            padding: 1rem 1.35rem;
            border-bottom: 1px solid #E5E9F0;
            display: flex;
            align-items: center;
            gap: .85rem;
        }

        .fb-thread-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #E8F0FE;
            color: #1565D8;
            font-family: 'Sora', sans-serif;
            font-weight: 800;
            font-size: .9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .fb-thread-name {
            font-weight: 700;
            font-size: .95rem;
            color: #0D1B2A;
            display: flex;
            align-items: center;
            gap: .4rem;
            text-decoration: none;
        }

        .fb-thread-name:hover {
            color: #1565D8;
            text-decoration: underline;
        }

        .fb-verified-tick {
            color: #1565D8;
        }

        .fb-thread-email {
            font-size: .78rem;
            color: #64748B;
        }

        .fb-thread-meta {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .fb-thread-body {
            flex: 1;
            overflow-y: auto;
            padding: 1.35rem;
            display: flex;
            flex-direction: column;
            gap: .8rem;
            background: #FAFBFC;
        }

        .fb-original-card {
            background: #fff;
            border: 1px solid #E5E9F0;
            border-radius: 12px;
            padding: 1rem 1.15rem;
            margin-bottom: .5rem;
        }

        .fb-original-card .label {
            font-size: .68rem;
            font-weight: 700;
            color: #94A3B8;
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-bottom: .4rem;
        }

        .fb-original-card .msg {
            font-size: .85rem;
            color: #1E293B;
            line-height: 1.6;
        }

        .fb-original-card img {
            max-width: 220px;
            border-radius: 8px;
            margin-top: .65rem;
            display: block;
        }

        .fb-msg-wrap {
            display: flex;
            align-items: flex-end;
            gap: .5rem;
            width: 100%;
        }

        .fb-msg-wrap.mine {
            flex-direction: row-reverse;
        }

        .fb-msg-content {
            min-width: 0;
            max-width: 65%;
            display: flex;
            flex-direction: column;
        }

        .fb-msg-wrap.mine .fb-msg-content {
            align-items: flex-end;
        }

        .fb-msg-wrap:not(.mine) .fb-msg-content {
            align-items: flex-start;
        }

        .fb-msg-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #EDE9FE;
            color: #5B21B6;
            font-size: .65rem;
            font-weight: 800;
            font-family: 'Sora', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .fb-msg-wrap:not(.mine) .fb-msg-avatar {
            background: #E8F0FE;
            color: #1565D8;
        }

        .fb-msg-bubble {
            display: inline-block;
            width: fit-content;
            max-width: 100%;
            padding: .65rem 1rem;
            border-radius: 14px;
            font-size: .85rem;
            line-height: 1.55;
            overflow-wrap: break-word;
            word-break: normal;
        }

        .fb-msg-bubble.mine {
            background: #1565D8;
            color: #fff;
            border-radius: 14px 14px 3px 14px;
        }

        .fb-msg-bubble.other {
            background: #fff;
            color: #1E293B;
            border: 1px solid #E5E9F0;
            border-radius: 14px 14px 14px 3px;
        }

        .fb-msg-bubble img {
            max-width: 100%;
            border-radius: 8px;
            display: block;
        }

        .fb-msg-meta {
            font-size: .68rem;
            margin-top: .3rem;
            color: #94A3B8;
        }

        .fb-msg-wrap:not(.mine) .fb-msg-meta {
            text-align: left;
        }

        .fb-thread-footer {
            padding: .9rem 1.1rem;
            border-top: 1px solid #E5E9F0;
            background: #fff;
        }

        .fb-input-row {
            display: flex;
            align-items: flex-end;
            gap: .6rem;
        }

        .fb-textarea {
            flex: 1;
            padding: .65rem .95rem;
            border: 1.5px solid #E5E9F0;
            border-radius: 10px;
            font-family: inherit;
            font-size: .85rem;
            resize: none;
            min-height: 42px;
            max-height: 120px;
            outline: none;
            transition: border-color .15s;
        }

        .fb-textarea:focus {
            border-color: #1565D8;
        }

        .fb-send-btn {
            width: 42px;
            height: 42px;
            background: #1565D8;
            color: #fff;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: background .15s;
        }

        .fb-send-btn:hover {
            background: #0F4CAE;
        }

        .fb-send-btn:disabled {
            background: #93B4EC;
            cursor: not-allowed;
        }

        /* Mobile: stack panes, list-first with a back button */
        @media (max-width: 860px) {
            .fb-inbox {
                flex-direction: column;
                height: calc(100vh - 120px);
            }

            .fb-list-pane {
                width: 100%;
                height: 100%;
            }

            .fb-thread-pane {
                display: none;
                height: 100%;
            }

            .fb-inbox.thread-open .fb-list-pane {
                display: none;
            }

            .fb-inbox.thread-open .fb-thread-pane {
                display: flex;
            }

            .fb-thread-back {
                display: inline-flex !important;
            }
        }

        .fb-thread-back {
            display: none;
            align-items: center;
            gap: .4rem;
            background: none;
            border: none;
            cursor: pointer;
            color: #1565D8;
            font-size: .82rem;
            font-weight: 700;
            padding: .5rem .3rem;
            margin: -.5rem -.3rem -.5rem -.3rem;
        }
    </style>
@endsection

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Feedback Inbox</h1>
                <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item active" aria-current="page">Feedback</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="fb-inbox" id="fb-inbox">

            {{-- ── List pane ── --}}
            <div class="fb-list-pane">
                <div class="fb-list-header">
                    <div class="fb-list-title">Feedback Inbox</div>
                    <input type="text" class="fb-search" id="fb-search" placeholder="Search by name, category, or message…">
                    <div class="fb-tabs">
                        <div class="fb-tab active" data-tab="all">All</div>
                        <div class="fb-tab" data-tab="unread">Unread</div>
                        <div class="fb-tab" data-tab="unreplied">Unreplied</div>
                    </div>
                </div>
                <div class="fb-list-scroll" id="fb-list-scroll">
                    <div class="fb-list-empty">Loading…</div>
                </div>
            </div>

            {{-- ── Thread pane ── --}}
            <div class="fb-thread-pane" id="fb-thread-pane">
                <div class="fb-thread-empty" id="fb-thread-placeholder">
                    <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                    </svg>
                    <p>Select a ticket to view the conversation</p>
                </div>
            </div>

        </div>
    </div>

    <script>
        (function () {
            "use strict";

            const openIdInitial = @json($openId ?? null);
            const listScroll = document.getElementById('fb-list-scroll');
            const threadPane = document.getElementById('fb-thread-pane');
            const inbox = document.getElementById('fb-inbox');
            const searchInput = document.getElementById('fb-search');
            const tabs = document.querySelectorAll('.fb-tab');

            let currentTab = 'all';
            let currentSearch = '';
            let activeTicketId = null;
            let listPollTimer = null;
            let threadPollTimer = null;
            let searchDebounce = null;
            let isSending = false;

            // Pagination state
            let currentPage = 1;
            let lastPage = 1;
            let isLoadingMore = false;
            let allRows = [];

            const LIST_URL = '{{ route("admin.feedback.api.list") }}';
            const THREAD_URL_BASE = '{{ url("admin/feedback/api") }}'; // + /{id}/thread
            const POLL_URL_BASE = '{{ url("admin/feedback/api") }}';   // + /{id}/poll
            const STORE_URL = '{{ route("store.admin.feedbackreplies") }}';
            const USER_INFO_URL_BASE = '{{ url("user") }}'; // + /{id}/info
            const CSRF = '{{ csrf_token() }}';

            // ── List rendering ──
            function initials(name) {
                return (name || 'U').trim().split(' ').map(p => p[0]).join('').slice(0, 2).toUpperCase();
            }

            function escapeHtml(str) {
                return String(str ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            }

            async function loadList(reset = true) {
                if (reset) {
                    currentPage = 1;
                    allRows = [];
                }

                const params = new URLSearchParams({ tab: currentTab, search: currentSearch, page: currentPage });
                try {
                    const res = await fetch(`${LIST_URL}?${params}`);
                    const data = await res.json();
                    if (!data.status) return;

                    allRows = reset ? data.data : allRows.concat(data.data);
                    lastPage = data.pagination.last_page;
                    renderList(allRows);
                } catch (e) { /* silent */ }
            }

            async function loadMore() {
                if (isLoadingMore || currentPage >= lastPage) return;
                isLoadingMore = true;
                currentPage++;
                await loadList(false);
                isLoadingMore = false;
            }

            function renderList(rows) {
                if (!rows.length) {
                    listScroll.innerHTML = `<div class="fb-list-empty">No tickets found.</div>`;
                    return;
                }

                listScroll.innerHTML = rows.map(r => `
                    <div class="fb-row ${r.id == activeTicketId ? 'active' : ''}" data-id="${r.id}">
                        <div class="fb-row-avatar">${initials(r.user_name)}</div>
                        <div class="fb-row-body">
                            <div class="fb-row-top">
                                <a href="${USER_INFO_URL_BASE}/${r.user_id}/info" class="fb-row-name" onclick="event.stopPropagation()" target="_blank" rel="noopener">${escapeHtml(r.user_name)}</a>
                                <span class="fb-row-time">${escapeHtml(r.last_activity)}</span>
                            </div>
                            <div class="fb-row-msg">${escapeHtml(r.message)}</div>
                            <div class="fb-row-bottom">
                                <span class="fb-cat-pill cat-${escapeHtml(r.category)}">${escapeHtml((r.category || '').replace(/_/g, ' '))}</span>
                                ${r.awaiting_reply ? `<span class="fb-awaiting-pill">Awaiting reply</span>` : ''}
                                ${r.unread_count > 0 ? `<span class="fb-unread-badge">${r.unread_count}</span>` : ''}
                            </div>
                        </div>
                    </div>
                `).join('') + (currentPage < lastPage ? `<div class="fb-list-loading" id="fb-list-loading">Loading more…</div>` : '');

                listScroll.querySelectorAll('.fb-row').forEach(row => {
                    row.addEventListener('click', () => openTicket(row.dataset.id));
                });
            }

            // Infinite scroll trigger
            listScroll.addEventListener('scroll', () => {
                const nearBottom = listScroll.scrollTop + listScroll.clientHeight >= listScroll.scrollHeight - 120;
                if (nearBottom) loadMore();
            });

            // ── Thread rendering ──
            async function openTicket(id, pushHistory = true) {
                activeTicketId = id;
                inbox.classList.add('thread-open');

                if (pushHistory && window.innerWidth <= 860) {
                    history.pushState({ fbThreadOpen: true, ticketId: id }, '', location.pathname);
                }

                listScroll.querySelectorAll('.fb-row').forEach(r => r.classList.toggle('active', r.dataset.id == id));

                threadPane.innerHTML = `<div class="fb-thread-empty"><p>Loading conversation…</p></div>`;

                try {
                    const res = await fetch(`${THREAD_URL_BASE}/${id}/thread`);
                    const data = await res.json();
                    if (!data.status) {
                        threadPane.innerHTML = `<div class="fb-thread-empty"><p>Ticket not found.</p></div>`;
                        return;
                    }
                    renderThread(data.data.ticket, data.data.replies);
                    loadList(); // refresh unread badges now that this thread is read
                    restartThreadPolling(id);
                } catch (e) {
                    threadPane.innerHTML = `<div class="fb-thread-empty"><p>Failed to load conversation.</p></div>`;
                }
            }

            // Single source of truth for closing the thread pane — handles both the
            // visible back button AND the browser's native back gesture/button.
            window.__fbCloseThread = function (skipHistoryBack) {
                inbox.classList.remove('thread-open');
                stopThreadPolling();
                if (!skipHistoryBack && window.innerWidth <= 860 && history.state && history.state.fbThreadOpen) {
                    history.back();
                }
            };

            window.addEventListener('popstate', () => {
                if (inbox.classList.contains('thread-open')) {
                    window.__fbCloseThread(true); // true = we're already responding to a back nav, don't push another one
                }
            });

            function renderThread(ticket, replies) {
                const cat = (ticket.category || 'others').toLowerCase().replace(/ /g, '_');

                threadPane.innerHTML = `
                    <div class="fb-thread-header">
                        <button class="fb-thread-back" onclick="window.__fbCloseThread()">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                            All tickets
                        </button>
                        <div class="fb-thread-avatar">${initials(ticket.user.name)}</div>
                        <div>
                            <a href="${USER_INFO_URL_BASE}/${ticket.user.id}/info" class="fb-thread-name" target="_blank" rel="noopener">
                                ${escapeHtml(ticket.user.name)}
                                ${ticket.user.is_verified ? '<svg class="fb-verified-tick" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.4 2.4 3.4-.5 1 3.3 3.2 1.6-1 3.3 1.9 2.9-2.6 2.2.3 3.4-3.4.3-1.9 2.9-3.3-1.3-3.3 1.3-1.9-2.9-3.4-.3.3-3.4-2.6-2.2 1.9-2.9-1-3.3 3.2-1.6 1-3.3 3.4.5z"/></svg>' : ''}
                            </a>
                            <div class="fb-thread-email">${escapeHtml(ticket.user.email)}</div>
                        </div>
                        <div class="fb-thread-meta">
                            <span class="fb-cat-pill cat-${cat}">${escapeHtml((ticket.category || '').replace(/_/g, ' '))}</span>
                        </div>
                    </div>
                    <div class="fb-thread-body" id="fb-thread-body">
                        <div class="fb-original-card">
                            <div class="label">Original message · ${escapeHtml(ticket.created_at)}</div>
                            <div class="msg">${escapeHtml(ticket.message).replace(/\n/g, '<br>')}</div>
                            ${ticket.proof_url ? `<img src="${ticket.proof_url}" alt="Proof">` : ''}
                        </div>
                        <div id="fb-replies-list"></div>
                    </div>
                    <div class="fb-thread-footer">
                        <div class="fb-input-row">
                            <textarea class="fb-textarea" id="fb-reply-input" placeholder="Type a reply…" rows="1" onkeydown="window.__fbHandleKey(event)" oninput="window.__fbAutoResize(this)"></textarea>
                            <button class="fb-send-btn" id="fb-send-btn" onclick="window.__fbSendReply(${ticket.id})">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                            </button>
                        </div>
                    </div>
                `;

                // Guard against mobile keyboards firing a synthetic Enter mid-composition,
                // which otherwise splits a message into fragments (e.g. "hello" -> "he" / "llo").
                const replyInput = document.getElementById('fb-reply-input');
                replyInput.addEventListener('compositionstart', () => { window.__fbComposing = true; });
                replyInput.addEventListener('compositionend', () => { window.__fbComposing = false; });

                renderReplies(replies);
            }

            function dateLabel(dt) {
                const d = new Date(dt.split(' at ')[0] || dt);
                const today = new Date();
                const yesterday = new Date(today);
                yesterday.setDate(today.getDate() - 1);
                const sameDay = (a, b) => a.toDateString() === b.toDateString();
                if (sameDay(d, today)) return 'Today';
                if (sameDay(d, yesterday)) return 'Yesterday';
                return d.toLocaleDateString('en-US', { day: 'numeric', month: 'long', year: 'numeric' });
            }

            function renderReplies(replies) {
                const list = document.getElementById('fb-replies-list');
                if (!list) return;

                let html = '';
                let lastLabel = null;

                replies.forEach(r => {
                    const label = dateLabel(r.created_at);
                    if (label !== lastLabel) {
                        lastLabel = label;
                        html += `<div style="text-align:center;margin:.9rem 0;font-size:.72rem;color:#94A3B8">
                            <span style="background:#FAFBFC;padding:.2rem .75rem;border:1px solid #E5E9F0;border-radius:20px">${label}</span>
                        </div>`;
                    }

                    let bubble = '';
                    if ((r.type === 'image' || r.type === 'mixed') && r.image_url) {
                        bubble += `<img src="${r.image_url}" alt="Image" style="${r.type === 'mixed' ? 'margin-bottom:.5rem' : ''}">`;
                    }
                    if ((r.type === 'text' || r.type === 'mixed') && r.message) {
                        bubble += escapeHtml(r.message).replace(/\n/g, '<br>');
                    }
                    const readLabel = r.is_mine ? ` · ${r.is_read ? 'Seen' : 'Sent'}` : '';

                    html += `
                        <div class="fb-msg-wrap ${r.is_mine ? 'mine' : ''}">
                            <div class="fb-msg-avatar">${initials(r.sender_name)}</div>
                            <div class="fb-msg-content">
                                <div class="fb-msg-bubble ${r.is_mine ? 'mine' : 'other'}">${bubble}</div>
                                <div class="fb-msg-meta">${escapeHtml(r.created_at)}${readLabel}</div>
                            </div>
                        </div>
                    `;
                });

                list.innerHTML = html;

                const body = document.getElementById('fb-thread-body');
                if (body) setTimeout(() => body.scrollTop = body.scrollHeight, 30);
            }

            // ── Reply sending ──
            window.__fbAutoResize = function (el) {
                el.style.height = 'auto';
                el.style.height = Math.min(el.scrollHeight, 120) + 'px';
            };

            window.__fbHandleKey = function (e) {
                // isComposing / keyCode 229 = mobile keyboard autocomplete firing a
                // synthetic Enter mid-word. Must ignore both or messages get split.
                if (window.__fbComposing || e.isComposing || e.keyCode === 229) return;

                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    if (!isSending) window.__fbSendReply(activeTicketId);
                }
            };

            window.__fbSendReply = async function (ticketId) {
                if (isSending) return;

                const input = document.getElementById('fb-reply-input');
                const btn = document.getElementById('fb-send-btn');
                const msg = input.value.trim();
                if (!msg) return;

                isSending = true;
                btn.disabled = true;

                try {
                    const res = await fetch(STORE_URL, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ feedback_id: ticketId, message: msg }),
                    });
                    const data = await res.json();
                    if (data.status) {
                        input.value = '';
                        input.style.height = 'auto';
                        renderReplies(data.replies);
                        loadList();
                    }
                } catch (e) { /* silent */ }

                isSending = false;
                btn.disabled = false;
            };

            // ── Polling ──
            function restartThreadPolling(id) {
                stopThreadPolling();
                threadPollTimer = setInterval(async () => {
                    try {
                        const res = await fetch(`${POLL_URL_BASE}/${id}/poll`);
                        const data = await res.json();
                        if (data.status) renderReplies(data.replies);
                    } catch (e) { /* silent */ }
                }, 300000);
            }

            function stopThreadPolling() {
                if (threadPollTimer) clearInterval(threadPollTimer);
                threadPollTimer = null;
            }

            function startListPolling() {
                listPollTimer = setInterval(async () => {
                    // Refresh page 1 only, merged into whatever's already loaded —
                    // keeps unread badges live without discarding scrolled-in pages.
                    const params = new URLSearchParams({ tab: currentTab, search: currentSearch, page: 1 });
                    try {
                        const res = await fetch(`${LIST_URL}?${params}`);
                        const data = await res.json();
                        if (!data.status) return;

                        const freshById = Object.fromEntries(data.data.map(r => [r.id, r]));
                        allRows = allRows.map(r => freshById[r.id] || r);

                        const existingIds = new Set(allRows.map(r => r.id));
                        const newOnes = data.data.filter(r => !existingIds.has(r.id));
                        if (newOnes.length) allRows = [...newOnes, ...allRows];

                        renderList(allRows);
                    } catch (e) { /* silent */ }
                }, 60000);
            }

            // ── Tabs ──
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    currentTab = tab.dataset.tab;
                    loadList(true);
                });
            });

            // ── Search ──
            searchInput.addEventListener('input', () => {
                clearTimeout(searchDebounce);
                searchDebounce = setTimeout(() => {
                    currentSearch = searchInput.value.trim();
                    loadList(true);
                }, 350);
            });

            // ── Init ──
            loadList(true);
            startListPolling();

            if (openIdInitial) {
                setTimeout(() => openTicket(openIdInitial, false), 200);
            }
        })();
    </script>
@endsection
