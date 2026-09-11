<!-- Theme Customizer Modal & Trigger -->
<div id="themeBackdrop" class="thm-backdrop"></div>

<div id="themeCustomizerModal" class="thm-drawer" role="dialog" aria-modal="true" aria-labelledby="thmTitle">
  <div class="thm-drawer-header">
    <div class="thm-title-wrap">
      <div class="thm-title-icon">🎨</div>
      <div>
        <h3 id="thmTitle" class="thm-title">Customize Appearance</h3>
        <p class="thm-subtitle">Pick a theme mode and color palette</p>
      </div>
    </div>
    <button class="thm-close-btn" aria-label="Close customizer">✕</button>
  </div>

  <div class="thm-drawer-body">
    <!-- Mode Selection (Light / Dark / Device) -->
    <div class="thm-section">
      <div class="thm-section-title">Mode</div>
      <div class="thm-mode-toggle">
        <button type="button" class="thm-mode-btn" data-mode="light">
          <span class="thm-mode-icon">☀️</span>
          <span>Light</span>
        </button>
        <button type="button" class="thm-mode-btn" data-mode="dark">
          <span class="thm-mode-icon">🌙</span>
          <span>Dark</span>
        </button>
        <button type="button" class="thm-mode-btn" data-mode="device">
          <span class="thm-mode-icon">💻</span>
          <span>Device</span>
        </button>
      </div>
    </div>

    <!-- Theme Palettes Grid -->
    <div class="thm-section">
      <div class="thm-section-title">Color Palettes</div>
      <div class="thm-swatches-grid">
        <!-- Forest Emerald (Default) -->
        <button type="button" class="thm-swatch" data-palette="forest" title="Forest Emerald">
          <div class="thm-swatch-circle" style="background: linear-gradient(135deg, #0F5E2E 50%, #4A8333 50%);">
            <span class="thm-swatch-half" style="background:#EEF5EA;"></span>
          </div>
          <span class="thm-check">✓</span>
        </button>

        <!-- Ocean Blue -->
        <button type="button" class="thm-swatch" data-palette="blue" title="Ocean Blue">
          <div class="thm-swatch-circle" style="background: linear-gradient(135deg, #155EEF 50%, #2E90FA 50%);">
            <span class="thm-swatch-half" style="background:#EFF8FF;"></span>
          </div>
          <span class="thm-check">✓</span>
        </button>

        <!-- Teal Marina -->
        <button type="button" class="thm-swatch" data-palette="teal" title="Teal Marina">
          <div class="thm-swatch-circle" style="background: linear-gradient(135deg, #0E7490 50%, #06B6D4 50%);">
            <span class="thm-swatch-half" style="background:#ECFEFF;"></span>
          </div>
          <span class="thm-check">✓</span>
        </button>

        <!-- Royal Indigo -->
        <button type="button" class="thm-swatch" data-palette="indigo" title="Royal Indigo">
          <div class="thm-swatch-circle" style="background: linear-gradient(135deg, #4338CA 50%, #6366F1 50%);">
            <span class="thm-swatch-half" style="background:#EEF2FF;"></span>
          </div>
          <span class="thm-check">✓</span>
        </button>

        <!-- Midnight Slate -->
        <button type="button" class="thm-swatch" data-palette="slate" title="Midnight Slate">
          <div class="thm-swatch-circle" style="background: linear-gradient(135deg, #334155 50%, #475569 50%);">
            <span class="thm-swatch-half" style="background:#F1F5F9;"></span>
          </div>
          <span class="thm-check">✓</span>
        </button>

        <!-- Sunset Amber -->
        <button type="button" class="thm-swatch" data-palette="amber" title="Sunset Amber">
          <div class="thm-swatch-circle" style="background: linear-gradient(135deg, #B45309 50%, #D97706 50%);">
            <span class="thm-swatch-half" style="background:#FFFBEB;"></span>
          </div>
          <span class="thm-check">✓</span>
        </button>

        <!-- Terracotta Peach -->
        <button type="button" class="thm-swatch" data-palette="peach" title="Terracotta Peach">
          <div class="thm-swatch-circle" style="background: linear-gradient(135deg, #C2410C 50%, #EA580C 50%);">
            <span class="thm-swatch-half" style="background:#FFF7ED;"></span>
          </div>
          <span class="thm-check">✓</span>
        </button>

        <!-- Crimson Rose -->
        <button type="button" class="thm-swatch" data-palette="rose" title="Crimson Rose">
          <div class="thm-swatch-circle" style="background: linear-gradient(135deg, #BE123C 50%, #E11D48 50%);">
            <span class="thm-swatch-half" style="background:#FFF1F2;"></span>
          </div>
          <span class="thm-check">✓</span>
        </button>

        <!-- Amethyst Lilac -->
        <button type="button" class="thm-swatch" data-palette="purple" title="Amethyst Lilac">
          <div class="thm-swatch-circle" style="background: linear-gradient(135deg, #7E22CE 50%, #9333EA 50%);">
            <span class="thm-swatch-half" style="background:#FAF5FF;"></span>
          </div>
          <span class="thm-check">✓</span>
        </button>

        <!-- Neon Cyberpunk -->
        <button type="button" class="thm-swatch" data-palette="cyberpunk" title="Neon Cyberpunk">
          <div class="thm-swatch-circle" style="background: linear-gradient(135deg, #2563EB 50%, #8B5CF6 50%);">
            <span class="thm-swatch-half" style="background:#F0FDFA;"></span>
          </div>
          <span class="thm-check">✓</span>
        </button>

        <!-- Custom Eyedropper / Color Swatch -->
        <button type="button" class="thm-swatch thm-custom-swatch" id="thmCustomSwatch" title="Custom Color">
          <div class="thm-custom-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="m14 7 3 3L8.5 18.5a2.12 2.12 0 0 1-1.5.5H4v-3l10-9Z"/>
              <path d="m16 5 2-2 3 3-2 2"/>
              <path d="m19 2 2 2"/>
            </svg>
          </div>
          <span class="thm-check">✓</span>
        </button>
      </div>
    </div>

    <!-- Custom Color Picker Box -->
    <div class="thm-section thm-custom-color-box">
      <div class="thm-section-title">Custom Color Code</div>
      <p class="thm-subhint">Pick any color code or enter a HEX value to theme the whole system</p>
      
      <div class="thm-color-inputs">
        <div class="thm-native-picker-wrapper">
          <input type="color" id="thmColorPickerInput" value="#4F46E5" class="thm-native-picker" title="Open Color Wheel">
        </div>
        <div class="thm-hex-input-wrap">
          <span class="thm-hex-prefix">HEX</span>
          <input type="text" id="thmHexInput" value="#4F46E5" maxlength="7" placeholder="#4F46E5" class="thm-hex-text">
        </div>
        <button type="button" id="thmEyedropperBtn" class="thm-btn-icon" title="Pick color with Eyedropper">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="m14 7 3 3L8.5 18.5a2.12 2.12 0 0 1-1.5.5H4v-3l10-9Z"/>
            <path d="m16 5 2-2 3 3-2 2"/>
          </svg>
        </button>
      </div>
    </div>
  </div>

  <div class="thm-drawer-footer">
    <button type="button" id="thmResetBtn" class="thm-footer-btn thm-btn-ghost">Reset Default</button>
    <button type="button" class="thm-footer-btn thm-btn-done thm-close-btn">Done</button>
  </div>
</div>

<!-- Floating Action Button for easy access on any page -->
<button type="button" class="thm-floating-trigger thm-trigger-btn" aria-label="Customize Theme & Colors" title="Customize Theme & Colors">
  <span class="thm-fab-icon">🎨</span>
  <span class="thm-fab-label">Theme</span>
</button>
