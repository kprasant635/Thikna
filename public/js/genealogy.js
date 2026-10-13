/**
 * ============================================================================
 * GenealogyTreeApp — Interactive Genealogy Tree Visualization for SkopX
 * ============================================================================
 */
class GenealogyTreeApp {
  constructor(config) {
    this.config = Object.assign({
      containerId: 'genealogyApp',
      originalRootId: null,
      currentRootId: null,
      initialGenerations: 3,
      treeApiUrl: '/dashboard/genealogy/tree',
      childrenApiUrl: '/dashboard/genealogy/node',
      searchApiUrl: '/dashboard/genealogy/search',
      memberApiUrl: '/dashboard/genealogy/member',
      isAdmin: false
    }, config);

    this.scale = 1.0;
    this.translateX = 0;
    this.translateY = 0;
    this.isDragging = false;
    this.startX = 0;
    this.startY = 0;
    this.currentGenerations = this.config.initialGenerations;
    this.currentRootId = this.config.currentRootId;
    this.originalRootId = this.config.originalRootId;
    this.cache = new Map();

    this.initElements();
    this.initEvents();
    this.loadTree(this.currentRootId, this.currentGenerations);
  }

  initElements() {
    this.viewport = document.getElementById('gtViewport');
    this.content = document.getElementById('gtContent');
    this.loadingOverlay = document.getElementById('gtLoadingOverlay');
    this.zoomBadge = document.getElementById('gtZoomBadge');
    this.memberCountBadge = document.getElementById('gtMemberCountBadge');

    // Search elements
    this.searchInput = document.getElementById('gtSearchInput');
    this.searchClearBtn = document.getElementById('gtSearchClear');
    this.searchSpinner = document.getElementById('gtSearchSpinner');
    this.searchDropdown = document.getElementById('gtSearchDropdown');

    // Breadcrumbs & Root summary
    this.breadcrumbsWrap = document.getElementById('gtBreadcrumbsWrap');
    this.returnRootBtn = document.getElementById('gtReturnRootBtn');
    this.rootSummaryBar = document.getElementById('gtRootSummaryBar');

    // Modal elements
    this.modalBackdrop = document.getElementById('gtModalBackdrop');
    this.modalCloseBtn = document.getElementById('gtModalCloseBtn');
    this.modalCancelBtn = document.getElementById('gtModalCancelBtn');
    this.modalSetRootBtn = document.getElementById('gtModalSetRootBtn');
  }

  initEvents() {
    // Zoom & Pan Mouse Events
    if (this.viewport) {
      this.viewport.addEventListener('mousedown', (e) => this.onMouseDown(e));
      window.addEventListener('mousemove', (e) => this.onMouseMove(e));
      window.addEventListener('mouseup', () => this.onMouseUp());
      this.viewport.addEventListener('wheel', (e) => this.onWheel(e), { passive: false });

      // Touch Events for Mobile / Tablet
      this.viewport.addEventListener('touchstart', (e) => this.onTouchStart(e), { passive: false });
      this.viewport.addEventListener('touchmove', (e) => this.onTouchMove(e), { passive: false });
      this.viewport.addEventListener('touchend', () => this.onTouchEnd());
    }

    // Toolbar buttons
    document.getElementById('gtBtnZoomIn')?.addEventListener('click', () => this.zoomBy(1.18));
    document.getElementById('gtBtnZoomOut')?.addEventListener('click', () => this.zoomBy(0.82));
    document.getElementById('gtBtnFitScreen')?.addEventListener('click', () => this.fitToScreen());
    document.getElementById('gtBtnResetView')?.addEventListener('click', () => this.resetView());
    document.getElementById('gtBtnExpandAll')?.addEventListener('click', () => this.expandAll());
    document.getElementById('gtBtnCollapseAll')?.addEventListener('click', () => this.collapseAll());
    document.getElementById('gtBtnRefresh')?.addEventListener('click', () => this.refresh());

    // Generation selector buttons
    document.querySelectorAll('.gt-gen-btn').forEach((btn) => {
      btn.addEventListener('click', (e) => {
        document.querySelectorAll('.gt-gen-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const genVal = btn.dataset.gen;
        this.currentGenerations = genVal === 'all' ? 10 : parseInt(genVal, 10);
        this.loadTree(this.currentRootId, this.currentGenerations);
      });
    });

    // Return to original root button
    if (this.returnRootBtn) {
      this.returnRootBtn.addEventListener('click', () => {
        this.changeRoot(this.originalRootId);
      });
    }

    // Search events
    if (this.searchInput) {
      let debounceTimeout = null;
      this.searchInput.addEventListener('input', () => {
        const query = this.searchInput.value.trim();
        if (this.searchClearBtn) {
          this.searchClearBtn.style.display = query ? 'block' : 'none';
        }
        clearTimeout(debounceTimeout);
        if (query.length === 0) {
          this.hideSearchDropdown();
          return;
        }
        debounceTimeout = setTimeout(() => this.performSearch(query), 260);
      });

      if (this.searchClearBtn) {
        this.searchClearBtn.addEventListener('click', () => {
          this.searchInput.value = '';
          this.searchClearBtn.style.display = 'none';
          this.hideSearchDropdown();
        });
      }

      // Close search dropdown on click outside
      document.addEventListener('click', (e) => {
        if (!e.target.closest('.gt-search-wrapper')) {
          this.hideSearchDropdown();
        }
      });
    }

    // Modal Close events
    const closeModal = () => this.closeMemberModal();
    this.modalCloseBtn?.addEventListener('click', closeModal);
    this.modalCancelBtn?.addEventListener('click', closeModal);
    this.modalBackdrop?.addEventListener('click', (e) => {
      if (e.target === this.modalBackdrop) closeModal();
    });
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeModal();
    });
  }

  // --------------------------------------------------------------------------
  // Pan and Zoom Mechanics
  // --------------------------------------------------------------------------
  onMouseDown(e) {
    // Only drag on left click and when clicking on background or canvas, not buttons
    if (e.button !== 0 || e.target.closest('.gt-btn-toggle-expand')) return;
    this.isDragging = true;
    this.startX = e.clientX - this.translateX;
    this.startY = e.clientY - this.translateY;
    this.viewport.classList.add('is-dragging');
  }

  onMouseMove(e) {
    if (!this.isDragging) return;
    this.translateX = e.clientX - this.startX;
    this.translateY = e.clientY - this.startY;
    this.applyTransform();
  }

  onMouseUp() {
    if (this.isDragging) {
      this.isDragging = false;
      this.viewport.classList.remove('is-dragging');
    }
  }

  onTouchStart(e) {
    if (e.touches.length === 1) {
      if (e.target.closest('.gt-btn-toggle-expand')) return;
      this.isDragging = true;
      this.startX = e.touches[0].clientX - this.translateX;
      this.startY = e.touches[0].clientY - this.translateY;
    } else if (e.touches.length === 2) {
      this.isDragging = false;
      this.initialPinchDistance = Math.hypot(
        e.touches[0].clientX - e.touches[1].clientX,
        e.touches[0].clientY - e.touches[1].clientY
      );
      this.initialScale = this.scale;
    }
  }

  onTouchMove(e) {
    if (this.isDragging && e.touches.length === 1) {
      e.preventDefault();
      this.translateX = e.touches[0].clientX - this.startX;
      this.translateY = e.touches[0].clientY - this.startY;
      this.applyTransform();
    } else if (e.touches.length === 2 && this.initialPinchDistance) {
      e.preventDefault();
      const currentDist = Math.hypot(
        e.touches[0].clientX - e.touches[1].clientX,
        e.touches[0].clientY - e.touches[1].clientY
      );
      const factor = currentDist / this.initialPinchDistance;
      this.scale = Math.min(2.5, Math.max(0.25, this.initialScale * factor));
      this.applyTransform();
      this.updateZoomBadge();
    }
  }

  onTouchEnd() {
    this.isDragging = false;
    this.initialPinchDistance = null;
  }

  onWheel(e) {
    e.preventDefault();
    const zoomFactor = e.deltaY < 0 ? 1.12 : 0.89;
    const rect = this.viewport.getBoundingClientRect();
    const mouseX = e.clientX - rect.left;
    const mouseY = e.clientY - rect.top;

    const newScale = Math.min(2.5, Math.max(0.25, this.scale * zoomFactor));

    // Zoom centered around cursor position
    this.translateX = mouseX - (mouseX - this.translateX) * (newScale / this.scale);
    this.translateY = mouseY - (mouseY - this.translateY) * (newScale / this.scale);
    this.scale = newScale;

    this.applyTransform();
    this.updateZoomBadge();
  }

  zoomBy(factor) {
    const rect = this.viewport.getBoundingClientRect();
    const centerX = rect.width / 2;
    const centerY = rect.height / 2;

    const newScale = Math.min(2.5, Math.max(0.25, this.scale * factor));
    this.translateX = centerX - (centerX - this.translateX) * (newScale / this.scale);
    this.translateY = centerY - (centerY - this.translateY) * (newScale / this.scale);
    this.scale = newScale;

    this.applyTransform();
    this.updateZoomBadge();
  }

  applyTransform() {
    if (this.content) {
      this.content.style.transform = `translate(${this.translateX}px, ${this.translateY}px) scale(${this.scale})`;
    }
  }

  updateZoomBadge() {
    if (this.zoomBadge) {
      this.zoomBadge.textContent = `${Math.round(this.scale * 100)}%`;
    }
  }

  fitToScreen() {
    if (!this.content || !this.viewport) return;
    const treeEl = this.content.querySelector('.gt-tree-root');
    if (!treeEl) return;

    const viewportRect = this.viewport.getBoundingClientRect();
    const treeRect = treeEl.getBoundingClientRect();

    // Calculate actual unscaled dimensions of tree
    const treeWidth = treeRect.width / this.scale;
    const treeHeight = treeRect.height / this.scale;

    const padding = 60;
    const availWidth = Math.max(100, viewportRect.width - padding * 2);
    const availHeight = Math.max(100, viewportRect.height - padding * 2);

    const scaleX = availWidth / treeWidth;
    const scaleY = availHeight / treeHeight;
    this.scale = Math.min(1.0, Math.max(0.3, Math.min(scaleX, scaleY)));

    // Center horizontally and top-align with comfortable margin
    this.translateX = (viewportRect.width - treeWidth * this.scale) / 2;
    this.translateY = Math.max(25, (viewportRect.height - treeHeight * this.scale) / 4);

    this.applyTransform();
    this.updateZoomBadge();
  }

  resetView() {
    if (!this.content || !this.viewport) return;
    const treeEl = this.content.querySelector('.gt-tree-root');
    const viewportRect = this.viewport.getBoundingClientRect();
    const treeWidth = treeEl ? (treeEl.getBoundingClientRect().width / this.scale) : 220;

    this.scale = 1.0;
    this.translateX = (viewportRect.width - treeWidth) / 2;
    this.translateY = 40;

    this.applyTransform();
    this.updateZoomBadge();
  }

  // --------------------------------------------------------------------------
  // Data Fetching & Tree Rendering
  // --------------------------------------------------------------------------
  showLoading() {
    if (this.loadingOverlay) this.loadingOverlay.classList.add('active');
  }

  hideLoading() {
    if (this.loadingOverlay) this.loadingOverlay.classList.remove('active');
  }

  async loadTree(rootId, generations, preservePosition = false) {
    this.showLoading();
    try {
      const params = new URLSearchParams({
        root_id: rootId || '',
        generations: generations,
        original_root_id: this.originalRootId || ''
      });

      const response = await fetch(`${this.config.treeApiUrl}?${params.toString()}`, {
        headers: { 'Accept': 'application/json' }
      });

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.message || 'Failed to load tree data.');
      }

      const data = await response.json();
      if (!data.success) {
        throw new Error(data.message || 'Failed to load tree.');
      }

      this.currentRootId = data.tree.id;
      this.renderTree(data.tree);
      this.updateBreadcrumbs(data.breadcrumbs);
      this.updateRootSummary(data.root_member);

      if (!preservePosition) {
        // Automatically position view neatly
        setTimeout(() => this.resetView(), 50);
      }
    } catch (err) {
      console.error('Genealogy Tree Load Error:', err);
      if (this.content) {
        this.content.innerHTML = `
          <div class="gt-no-referrals-banner" style="border-color:#fca5a5;background:#fef2f2;">
            <div class="gt-no-ref-icon">⚠️</div>
            <h4 class="gt-no-ref-title" style="color:#b91c1c;">Unable to Load Tree</h4>
            <p class="gt-no-ref-desc" style="color:#991b1b;">${this.escapeHtml(err.message)}</p>
            <button type="button" class="gt-btn-primary" onclick="window.location.reload()" style="margin-top:8px;">Retry</button>
          </div>
        `;
      }
    } finally {
      this.hideLoading();
    }
  }

  refresh() {
    this.loadTree(this.currentRootId, this.currentGenerations, true);
  }

  changeRoot(newRootId) {
    this.currentRootId = newRootId;
    this.loadTree(newRootId, this.currentGenerations, false);
  }

  renderTree(rootNode) {
    this.content.innerHTML = '';
    let totalNodesCount = 0;

    const countNodes = (node) => {
      totalNodesCount++;
      if (node.children && node.children.length > 0) {
        node.children.forEach(countNodes);
      }
    };
    countNodes(rootNode);

    if (this.memberCountBadge) {
      this.memberCountBadge.textContent = `${totalNodesCount} Members Displayed`;
    }

    const rootWrapper = document.createElement('div');
    rootWrapper.className = 'gt-tree-root';

    const rootNodeEl = this.buildNodeElement(rootNode, true);
    rootWrapper.appendChild(rootNodeEl);

    // If root has 0 referrals, show helpful message banner
    if (rootNode.direct_referrals_count === 0) {
      const emptyBanner = document.createElement('div');
      emptyBanner.className = 'gt-no-referrals-banner';
      emptyBanner.innerHTML = `
        <div class="gt-no-ref-icon">🌱</div>
        <h4 class="gt-no-ref-title">No Direct Referrals Yet</h4>
        <p class="gt-no-ref-desc">This member has not referred any partners yet. Share referral code <strong style="color:var(--gt-primary);">${this.escapeHtml(rootNode.referral_code || '—')}</strong> to begin expanding the network.</p>
      `;
      rootWrapper.appendChild(emptyBanner);
    }

    this.content.appendChild(rootWrapper);
  }

  buildNodeElement(node, isRoot = false) {
    const wrapper = document.createElement('div');
    wrapper.className = 'gt-tree-node-wrapper';
    wrapper.dataset.nodeId = node.id;

    // Card Element
    const card = document.createElement('div');
    card.className = `gt-card ${isRoot ? 'is-current-root' : ''}`;
    card.title = `Click to view details for ${node.name}`;

    // Status pill & ID
    const isActive = (node.status === 'active');
    const statusClass = isActive ? 'status-active' : 'status-pending';
    const statusLabel = isActive ? 'Active' : 'Pending';

    // Avatar
    let avatarHtml = '';
    if (node.profile_photo_url) {
      avatarHtml = `<img src="${this.escapeHtml(node.profile_photo_url)}" alt="${this.escapeHtml(node.name)}" class="gt-card-avatar" onerror="this.outerHTML='<div class=\\'gt-card-avatar\\'>${this.escapeHtml(node.initials || 'U')}</div>'">`;
    } else {
      avatarHtml = `<div class="gt-card-avatar">${this.escapeHtml(node.initials || 'U')}</div>`;
    }

    // Expand Toggle Button
    let toggleBtnHtml = '';
    const visualCount = node.visual_children_count ?? (node.children ? node.children.length : 0);
    if (node.has_children && visualCount > 0) {
      const isExpanded = node.children_loaded && node.children && node.children.length > 0;
      toggleBtnHtml = `
        <button type="button" class="gt-btn-toggle-expand" title="${isExpanded ? 'Collapse descendants' : 'Expand descendants'}">
          ${isExpanded ? `− ${visualCount}` : `+ ${visualCount}`}
        </button>
      `;
    }

    const spilloverHtml = node.is_spillover
      ? `<span class="gt-spillover-tag" title="Placed under visual parent #${node.visual_parent_id}">Spillover</span>`
      : '';

    card.innerHTML = `
      <div class="gt-card-header">
        <div>
          <span class="gt-status-pill ${statusClass}">
            <span class="gt-status-dot"></span>
            ${statusLabel}
          </span>
          ${spilloverHtml}
        </div>
        <span class="gt-card-id">#${node.id}</span>
      </div>
      <div class="gt-card-body">
        ${avatarHtml}
        <div class="gt-card-name" title="${this.escapeHtml(node.name)}">${this.escapeHtml(node.name)}</div>
        ${node.username ? `<div class="gt-card-username">@${this.escapeHtml(node.username)}</div>` : ''}
        ${node.referral_code ? `<span class="gt-card-ref-badge" title="Referral Code">Ref: ${this.escapeHtml(node.referral_code)}</span>` : ''}
      </div>
      <div class="gt-card-footer">
        <span class="gt-card-team-count" title="Actual direct referrals in database">
          👥 ${node.direct_referrals_count}
        </span>
        <span class="gt-card-visual-count" title="Direct visual positions filled in tree (max 10)">
          🌿 ${visualCount}/10
        </span>
        ${toggleBtnHtml}
      </div>
    `;

    // Card click opens modal
    card.addEventListener('click', (e) => {
      // If clicking expand button, don't trigger modal
      if (e.target.closest('.gt-btn-toggle-expand')) return;
      this.openMemberModal(node);
    });

    // Expand / Collapse button click
    const toggleBtn = card.querySelector('.gt-btn-toggle-expand');
    if (toggleBtn) {
      toggleBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        this.toggleNodeExpansion(node, wrapper, toggleBtn);
      });
    }

    wrapper.appendChild(card);

    // If node has children loaded, build children branch
    if (node.children_loaded && node.children && node.children.length > 0) {
      const stemDown = document.createElement('div');
      stemDown.className = 'gt-node-stem-down';
      wrapper.appendChild(stemDown);

      const branch = document.createElement('div');
      branch.className = 'gt-children-branch';

      node.children.forEach((child) => {
        const childItem = document.createElement('div');
        childItem.className = 'gt-child-item';

        const stemUp = document.createElement('div');
        stemUp.className = 'gt-child-stem-up';
        childItem.appendChild(stemUp);

        childItem.appendChild(this.buildNodeElement(child, false));
        branch.appendChild(childItem);
      });

      wrapper.appendChild(branch);
    }

    return wrapper;
  }

  async toggleNodeExpansion(node, wrapper, toggleBtn) {
    const branch = wrapper.querySelector(':scope > .gt-children-branch');
    const stem = wrapper.querySelector(':scope > .gt-node-stem-down');
    const visualCount = node.visual_children_count ?? (node.children ? node.children.length : 0);

    // If branch already rendered in DOM, toggle visibility
    if (branch) {
      const isCollapsed = branch.classList.toggle('is-collapsed');
      if (stem) {
        stem.classList.toggle('is-collapsed', isCollapsed);
      }
      toggleBtn.textContent = isCollapsed
        ? `+ ${visualCount}`
        : `− ${visualCount}`;
      toggleBtn.title = isCollapsed ? 'Expand descendants' : 'Collapse descendants';
      return;
    }

    // Lazy load children from server
    toggleBtn.classList.add('is-loading');
    toggleBtn.textContent = '...';

    try {
      const response = await fetch(`${this.config.childrenApiUrl}/${node.id}/children?root_id=${this.currentRootId}`, {
        headers: { 'Accept': 'application/json' }
      });
      const data = await response.json();

      if (!data.success) {
        throw new Error(data.message || 'Failed to load children.');
      }

      node.children = data.children;
      node.children_loaded = true;

      // Create stem and branch
      const stemDown = document.createElement('div');
      stemDown.className = 'gt-node-stem-down';
      wrapper.appendChild(stemDown);

      const newBranch = document.createElement('div');
      newBranch.className = 'gt-children-branch';

      data.children.forEach((child) => {
        const childItem = document.createElement('div');
        childItem.className = 'gt-child-item';

        const stemUp = document.createElement('div');
        stemUp.className = 'gt-child-stem-up';
        childItem.appendChild(stemUp);

        childItem.appendChild(this.buildNodeElement(child, false));
        newBranch.appendChild(childItem);
      });

      wrapper.appendChild(newBranch);
      toggleBtn.textContent = `− ${visualCount}`;
      toggleBtn.title = 'Collapse descendants';
    } catch (err) {
      alert(`Could not load descendants: ${err.message}`);
      toggleBtn.textContent = `+ ${visualCount}`;
    } finally {
      toggleBtn.classList.remove('is-loading');
    }
  }

  expandAll() {
    document.querySelectorAll('.gt-children-branch.is-collapsed, .gt-node-stem-down.is-collapsed').forEach((el) => {
      el.classList.remove('is-collapsed');
    });
    document.querySelectorAll('.gt-btn-toggle-expand').forEach((btn) => {
      const text = btn.textContent.trim();
      if (text.startsWith('+')) {
        btn.textContent = '−' + text.substring(1);
      }
    });
  }

  collapseAll() {
    // Collapse all branches and stems below the root node
    document.querySelectorAll('.gt-children-branch, .gt-node-stem-down').forEach((el) => {
      el.classList.add('is-collapsed');
    });
    document.querySelectorAll('.gt-btn-toggle-expand').forEach((btn) => {
      const text = btn.textContent.trim();
      if (text.startsWith('−') || text.startsWith('-')) {
        btn.textContent = '+' + text.substring(1);
      }
    });
  }

  // --------------------------------------------------------------------------
  // Breadcrumbs & Top Section Updates
  // --------------------------------------------------------------------------
  updateBreadcrumbs(breadcrumbs) {
    if (!this.breadcrumbsWrap) return;
    this.breadcrumbsWrap.innerHTML = '';

    breadcrumbs.forEach((item, index) => {
      if (index > 0) {
        const sep = document.createElement('span');
        sep.className = 'gt-bc-separator';
        sep.textContent = '›';
        this.breadcrumbsWrap.appendChild(sep);
      }

      const isLast = (index === breadcrumbs.length - 1);
      const link = document.createElement('span');
      link.className = `gt-bc-item ${isLast ? 'active' : ''}`;
      link.textContent = item.name + (item.referral_code ? ` (${item.referral_code})` : '');

      if (!isLast) {
        link.addEventListener('click', () => this.changeRoot(item.id));
      }

      this.breadcrumbsWrap.appendChild(link);
    });

    // Show/hide return button
    if (this.returnRootBtn) {
      const isOriginal = (this.currentRootId === this.originalRootId);
      this.returnRootBtn.style.display = isOriginal ? 'none' : 'inline-flex';
    }
  }

  updateRootSummary(rootMember) {
    if (!this.rootSummaryBar || !rootMember) return;

    let avatarHtml = '';
    if (rootMember.profile_photo_url) {
      avatarHtml = `<img src="${this.escapeHtml(rootMember.profile_photo_url)}" alt="${this.escapeHtml(rootMember.name)}" class="gt-root-avatar" onerror="this.outerHTML='<div class=\\'gt-root-avatar\\'>${this.escapeHtml(rootMember.initials || 'U')}</div>'">`;
    } else {
      avatarHtml = `<div class="gt-root-avatar">${this.escapeHtml(rootMember.initials || 'U')}</div>`;
    }

    const isActive = (rootMember.status === 'active');
    const statusClass = isActive ? 'status-active' : 'status-pending';
    const statusLabel = isActive ? 'Active' : 'Pending';

    this.rootSummaryBar.innerHTML = `
      <div class="gt-root-summary-left">
        <div class="gt-root-avatar-wrap">
          ${avatarHtml}
          <span class="gt-root-crown-badge" title="Root Member">👑</span>
        </div>
        <div class="gt-root-info">
          <h3>
            ${this.escapeHtml(rootMember.name)}
            <span class="gt-status-pill ${statusClass}">
              <span class="gt-status-dot"></span> ${statusLabel}
            </span>
          </h3>
          <div class="gt-root-info-sub">
            <span><strong>ID:</strong> #${rootMember.id}</span>
            ${rootMember.username ? `<span><strong>User:</strong> @${this.escapeHtml(rootMember.username)}</span>` : ''}
            <span><strong>Referral Code:</strong> <code class="gt-pill-code">${this.escapeHtml(rootMember.referral_code || '—')}</code></span>
            <span><strong>Direct Team:</strong> 👥 ${rootMember.direct_referrals_count} Members</span>
          </div>
        </div>
      </div>
    `;
  }

  // --------------------------------------------------------------------------
  // Search
  // --------------------------------------------------------------------------
  async performSearch(query) {
    if (this.searchSpinner) this.searchSpinner.style.display = 'block';

    try {
      const response = await fetch(`${this.config.searchApiUrl}?q=${encodeURIComponent(query)}`, {
        headers: { 'Accept': 'application/json' }
      });
      const data = await response.json();

      this.renderSearchResults(data.results || []);
    } catch (err) {
      this.renderSearchResults([]);
    } finally {
      if (this.searchSpinner) this.searchSpinner.style.display = 'none';
    }
  }

  renderSearchResults(results) {
    if (!this.searchDropdown) return;
    this.searchDropdown.innerHTML = '';

    if (results.length === 0) {
      this.searchDropdown.innerHTML = '<div class="gt-search-empty">🔍 No matching members found.</div>';
      this.searchDropdown.style.display = 'block';
      return;
    }

    results.forEach((user) => {
      const item = document.createElement('div');
      item.className = 'gt-search-item';

      let avatarHtml = '';
      if (user.profile_photo_url) {
        avatarHtml = `<img src="${this.escapeHtml(user.profile_photo_url)}" alt="${this.escapeHtml(user.name)}" class="gt-search-avatar" onerror="this.outerHTML='<div class=\\'gt-search-avatar\\'>${this.escapeHtml(user.initials || 'U')}</div>'">`;
      } else {
        avatarHtml = `<div class="gt-search-avatar">${this.escapeHtml(user.initials || 'U')}</div>`;
      }

      item.innerHTML = `
        ${avatarHtml}
        <div class="gt-search-info">
          <div class="gt-search-name">${this.escapeHtml(user.name)}</div>
          <div class="gt-search-meta">
            <span>#${user.id}</span>
            ${user.username ? `<span>@${this.escapeHtml(user.username)}</span>` : ''}
            <span>Ref: ${this.escapeHtml(user.referral_code || '—')}</span>
            <span>👥 ${user.direct_referrals_count}</span>
          </div>
        </div>
      `;

      item.addEventListener('click', () => {
        this.hideSearchDropdown();
        this.changeRoot(user.id);
      });

      this.searchDropdown.appendChild(item);
    });

    this.searchDropdown.style.display = 'block';
  }

  hideSearchDropdown() {
    if (this.searchDropdown) {
      this.searchDropdown.style.display = 'none';
    }
  }

  // --------------------------------------------------------------------------
  // Member Details Modal
  // --------------------------------------------------------------------------
  async openMemberModal(node) {
    if (!this.modalBackdrop) return;

    // Prefill modal header
    document.getElementById('gtModalName').textContent = node.name || 'Member Details';
    document.getElementById('gtModalUsername').textContent = node.username ? `@${node.username}` : '';
    document.getElementById('gtModalId').textContent = `#${node.id}`;
    document.getElementById('gtModalRefCode').textContent = node.referral_code || '—';
    document.getElementById('gtModalDirectCount').textContent = `${node.direct_referrals_count} Members`;

    const visualCount = node.visual_children_count ?? (node.children ? node.children.length : 0);
    const modalVisualCountEl = document.getElementById('gtModalVisualCount');
    if (modalVisualCountEl) {
      modalVisualCountEl.textContent = `${visualCount} / 10 Positions Filled`;
    }

    const modalPlacementTypeEl = document.getElementById('gtModalPlacementType');
    if (modalPlacementTypeEl) {
      modalPlacementTypeEl.textContent = node.is_spillover ? 'Spillover (BFS Vacancy Filled)' : 'Direct Placement';
    }

    const modalVisualParentEl = document.getElementById('gtModalVisualParent');
    if (modalVisualParentEl) {
      modalVisualParentEl.textContent = node.visual_parent_name
        ? `${node.visual_parent_name} (#${node.visual_parent_id})`
        : (node.id === this.currentRootId ? 'None (Current Root)' : 'Same as Sponsor');
    }

    const statusEl = document.getElementById('gtModalStatus');
    if (statusEl) {
      const isActive = (node.status === 'active');
      statusEl.textContent = isActive ? 'Active' : 'Pending';
      statusEl.className = `gt-status-pill ${isActive ? 'status-active' : 'status-pending'}`;
    }

    const avatarBox = document.getElementById('gtModalAvatarBox');
    if (avatarBox) {
      if (node.profile_photo_url) {
        avatarBox.innerHTML = `<img src="${this.escapeHtml(node.profile_photo_url)}" alt="${this.escapeHtml(node.name)}" class="gt-modal-avatar" onerror="this.outerHTML='<div class=\\'gt-modal-avatar\\'>${this.escapeHtml(node.initials || 'U')}</div>'">`;
      } else {
        avatarBox.innerHTML = `<div class="gt-modal-avatar">${this.escapeHtml(node.initials || 'U')}</div>`;
      }
    }

    // Configure "Set as Root" button
    if (this.modalSetRootBtn) {
      if (node.id === this.currentRootId) {
        this.modalSetRootBtn.style.display = 'none';
      } else {
        this.modalSetRootBtn.style.display = 'inline-flex';
        this.modalSetRootBtn.onclick = () => {
          this.closeMemberModal();
          this.changeRoot(node.id);
        };
      }
    }

    this.modalBackdrop.classList.add('is-open');

    // Fetch full details asynchronously for extra fields (joining date, referrer, visual parent)
    try {
      const response = await fetch(`${this.config.memberApiUrl}/${node.id}?root_id=${this.currentRootId}`, {
        headers: { 'Accept': 'application/json' }
      });
      const data = await response.json();
      if (data.success && data.member) {
        const m = data.member;
        document.getElementById('gtModalJoiningDate').textContent = m.joining_date || 'N/A';
        document.getElementById('gtModalReferrer').textContent = m.referrer
          ? `${m.referrer.name} (#${m.referrer.id})`
          : 'None (System Root)';

        if (modalVisualParentEl) {
          if (m.visual_parent) {
            modalVisualParentEl.textContent = `${m.visual_parent.name} (#${m.visual_parent.id})`;
          } else {
            modalVisualParentEl.textContent = m.referrer ? 'Same as Sponsor (Direct)' : 'None (System Root)';
          }
        }

        if (modalPlacementTypeEl) {
          modalPlacementTypeEl.textContent = m.is_spillover ? 'Spillover (BFS Vacancy Filled)' : 'Direct Placement';
        }

        if (modalVisualCountEl) {
          modalVisualCountEl.textContent = `${m.visual_children_count} / 10 Positions Filled`;
        }
      }
    } catch (e) {
      // Graceful fallback to already populated fields
    }
  }

  closeMemberModal() {
    if (this.modalBackdrop) {
      this.modalBackdrop.classList.remove('is-open');
    }
  }

  escapeHtml(str) {
    if (!str) return '';
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }
}

// Global exposure
window.GenealogyTreeApp = GenealogyTreeApp;
