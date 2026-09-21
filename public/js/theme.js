/**
 * SkopX Theme & Appearance Customizer
 * Supports: Light / Dark / Device modes, 10+ Curated Palettes, Custom HEX Color Picker & Eyedropper API.
 */

(function () {
  const STORAGE_KEY = 'SkopX_theme_config';

  const PALETTES = {
    forest: {
      name: 'SKOP-X Royal',
      deep: '#1a3a8f',
      mid: '#2563eb',
      bright: '#3b82f6',
      tint: '#eff6ff',
      tint2: '#dbeafe',
      gold: '#ff9900',
      goldTint: '#fff7ed',
      swatchColors: ['#1a3a8f', '#2563eb', '#eff6ff', '#dbeafe']
    },
    blue: {
      name: 'Ocean Blue',
      deep: '#155EEF',
      mid: '#2E90FA',
      bright: '#0086C9',
      tint: '#EFF8FF',
      tint2: '#D1E9FF',
      gold: '#F79009',
      goldTint: '#FEF0C7',
      swatchColors: ['#155EEF', '#2E90FA', '#EFF8FF', '#D1E9FF']
    },
    teal: {
      name: 'Teal Marina',
      deep: '#0E7490',
      mid: '#06B6D4',
      bright: '#14B8A6',
      tint: '#ECFEFF',
      tint2: '#CFFAFE',
      gold: '#F59E0B',
      goldTint: '#FEF3C7',
      swatchColors: ['#0E7490', '#06B6D4', '#ECFEFF', '#CFFAFE']
    },
    indigo: {
      name: 'Royal Indigo',
      deep: '#4338CA',
      mid: '#6366F1',
      bright: '#7C3AED',
      tint: '#EEF2FF',
      tint2: '#E0E7FF',
      gold: '#F59E0B',
      goldTint: '#FEF3C7',
      swatchColors: ['#4338CA', '#6366F1', '#EEF2FF', '#E0E7FF']
    },
    slate: {
      name: 'Midnight Slate',
      deep: '#334155',
      mid: '#475569',
      bright: '#0284C7',
      tint: '#F1F5F9',
      tint2: '#E2E8F0',
      gold: '#D97706',
      goldTint: '#FEF3C7',
      swatchColors: ['#334155', '#475569', '#F1F5F9', '#E2E8F0']
    },
    amber: {
      name: 'Sunset Amber',
      deep: '#B45309',
      mid: '#D97706',
      bright: '#F59E0B',
      tint: '#FFFBEB',
      tint2: '#FEF3C7',
      gold: '#D97706',
      goldTint: '#FEF3C7',
      swatchColors: ['#B45309', '#D97706', '#FFFBEB', '#FEF3C7']
    },
    peach: {
      name: 'Terracotta Peach',
      deep: '#C2410C',
      mid: '#EA580C',
      bright: '#F97316',
      tint: '#FFF7ED',
      tint2: '#FFEDD5',
      gold: '#EAB308',
      goldTint: '#FEF9C3',
      swatchColors: ['#C2410C', '#EA580C', '#FFF7ED', '#FFEDD5']
    },
    rose: {
      name: 'Crimson Rose',
      deep: '#BE123C',
      mid: '#E11D48',
      bright: '#F43F5E',
      tint: '#FFF1F2',
      tint2: '#FFE4E6',
      gold: '#D97706',
      goldTint: '#FEF3C7',
      swatchColors: ['#BE123C', '#E11D48', '#FFF1F2', '#FFE4E6']
    },
    purple: {
      name: 'Amethyst Lilac',
      deep: '#7E22CE',
      mid: '#9333EA',
      bright: '#A855F7',
      tint: '#FAF5FF',
      tint2: '#F3E8FF',
      gold: '#F59E0B',
      goldTint: '#FEF3C7',
      swatchColors: ['#7E22CE', '#9333EA', '#FAF5FF', '#F3E8FF']
    },
    cyberpunk: {
      name: 'Neon Cyberpunk',
      deep: '#2563EB',
      mid: '#8B5CF6',
      bright: '#06B6D4',
      tint: '#F0FDFA',
      tint2: '#CCFBF1',
      gold: '#EC4899',
      goldTint: '#FCE7F3',
      swatchColors: ['#2563EB', '#8B5CF6', '#F0FDFA', '#CCFBF1']
    }
  };

  // Helper: Hex to HSL
  function hexToHsl(hex) {
    hex = hex.replace(/^#/, '');
    if (hex.length === 3) {
      hex = hex.split('').map(c => c + c).join('');
    }
    const num = parseInt(hex, 16);
    const r = (num >> 16) / 255;
    const g = ((num >> 8) & 0xff) / 255;
    const b = (num & 0xff) / 255;

    const max = Math.max(r, g, b);
    const min = Math.min(r, g, b);
    let h, s, l = (max + min) / 2;

    if (max === min) {
      h = s = 0;
    } else {
      const d = max - min;
      s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
      switch (max) {
        case r: h = (g - b) / d + (g < b ? 6 : 0); break;
        case g: h = (b - r) / d + 2; break;
        case b: h = (r - g) / d + 4; break;
      }
      h /= 6;
    }
    return {
      h: Math.round(h * 360),
      s: Math.round(s * 100),
      l: Math.round(l * 100)
    };
  }

  function hslToHex(h, s, l) {
    s /= 100;
    l /= 100;
    const c = (1 - Math.abs(2 * l - 1)) * s;
    const x = c * (1 - Math.abs(((h / 60) % 2) - 1));
    const m = l - c / 2;
    let r = 0, g = 0, b = 0;

    if (h >= 0 && h < 60) { r = c; g = x; b = 0; }
    else if (h >= 60 && h < 120) { r = x; g = c; b = 0; }
    else if (h >= 120 && h < 180) { r = 0; g = c; b = x; }
    else if (h >= 180 && h < 240) { r = 0; g = x; b = c; }
    else if (h >= 240 && h < 300) { r = x; g = 0; b = c; }
    else if (h >= 300 && h < 360) { r = c; g = 0; b = x; }

    const toHex = val => {
      const hex = Math.round((val + m) * 255).toString(16);
      return hex.length === 1 ? '0' + hex : hex;
    };
    return `#${toHex(r)}${toHex(g)}${toHex(b)}`;
  }

  // Generate complete palette from any single HEX
  function generatePaletteFromHex(hex) {
    const hsl = hexToHsl(hex);
    const deepL = Math.max(16, hsl.l - 16);
    const midL = hsl.l;
    const brightL = Math.min(65, hsl.l + 12);

    const deep = hslToHex(hsl.h, Math.min(95, hsl.s + 10), deepL);
    const mid = hslToHex(hsl.h, hsl.s, midL);
    const bright = hslToHex(hsl.h, Math.min(100, hsl.s + 15), brightL);
    const tint = hslToHex(hsl.h, Math.min(30, hsl.s), 96);
    const tint2 = hslToHex(hsl.h, Math.min(35, hsl.s), 91);

    // Complementary gold/accent
    const compH = (hsl.h + 180) % 360;
    const gold = hslToHex(compH, 75, 52);
    const goldTint = hslToHex(compH, 40, 94);

    return {
      name: 'Custom (' + hex.toUpperCase() + ')',
      deep,
      mid,
      bright,
      tint,
      tint2,
      gold,
      goldTint,
      swatchColors: [deep, mid, tint, tint2]
    };
  }

  // Load config
  function loadConfig() {
    try {
      const saved = localStorage.getItem(STORAGE_KEY);
      if (saved) {
        return JSON.parse(saved);
      }
    } catch (e) {
      console.warn('Failed to load theme config from localStorage', e);
    }
    return {
      mode: 'device', // 'light' | 'dark' | 'device'
      palette: 'forest', // key of PALETTES or 'custom'
      customHex: '#4F46E5'
    };
  }

  function saveConfig(cfg) {
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(cfg));
    } catch (e) { }
  }

  let currentConfig = loadConfig();

  // Apply theme to DOM
  function applyTheme() {
    const root = document.documentElement;
    const isDarkOS = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    const activeDark = currentConfig.mode === 'dark' || (currentConfig.mode === 'device' && isDarkOS);

    // Set theme attribute
    root.setAttribute('data-theme', activeDark ? 'dark' : 'light');
    root.setAttribute('data-theme-mode', currentConfig.mode);

    // Determine palette data
    let pData;
    if (currentConfig.palette === 'custom' && currentConfig.customHex) {
      pData = generatePaletteFromHex(currentConfig.customHex);
    } else {
      pData = PALETTES[currentConfig.palette] || PALETTES.forest;
    }

    // Apply color variables
    root.style.setProperty('--green-deep', pData.deep);
    root.style.setProperty('--green-mid', pData.mid);
    root.style.setProperty('--green-bright', pData.bright);

    if (activeDark) {
      // In dark mode, tints are adjusted with translucent dark overlays
      root.style.setProperty('--green-tint', `rgba(${hexToRgbValues(pData.deep)}, 0.22)`);
      root.style.setProperty('--green-tint-2', `rgba(${hexToRgbValues(pData.mid)}, 0.32)`);
      root.style.setProperty('--gold-tint', `rgba(${hexToRgbValues(pData.gold)}, 0.20)`);
    } else {
      root.style.setProperty('--green-tint', pData.tint);
      root.style.setProperty('--green-tint-2', pData.tint2);
      root.style.setProperty('--gold-tint', pData.goldTint);
    }

    root.style.setProperty('--gold', pData.gold);

    // Sync UI elements if drawer is rendered
    updateCustomizerUI();
  }

  function hexToRgbValues(hex) {
    hex = hex.replace(/^#/, '');
    if (hex.length === 3) hex = hex.split('').map(c => c + c).join('');
    const num = parseInt(hex, 16);
    return `${(num >> 16) & 255}, ${(num >> 8) & 255}, ${num & 255}`;
  }

  // Update Customizer UI states
  function updateCustomizerUI() {
    // Mode buttons
    document.querySelectorAll('.thm-mode-btn').forEach(btn => {
      const mode = btn.getAttribute('data-mode');
      if (mode === currentConfig.mode) {
        btn.classList.add('active');
      } else {
        btn.classList.remove('active');
      }
    });

    // Palette swatches
    document.querySelectorAll('.thm-swatch').forEach(swatch => {
      const pKey = swatch.getAttribute('data-palette');
      if (pKey === currentConfig.palette) {
        swatch.classList.add('active');
      } else {
        swatch.classList.remove('active');
      }
    });

    // Custom color input / preview
    const customSwatch = document.getElementById('thmCustomSwatch');
    const colorInput = document.getElementById('thmColorPickerInput');
    const hexInput = document.getElementById('thmHexInput');

    if (customSwatch && currentConfig.customHex) {
      customSwatch.style.background = currentConfig.customHex;
      if (currentConfig.palette === 'custom') {
        customSwatch.classList.add('active');
      } else {
        customSwatch.classList.remove('active');
      }
    }

    if (colorInput && currentConfig.customHex) {
      colorInput.value = currentConfig.customHex;
    }
    if (hexInput && currentConfig.customHex) {
      hexInput.value = currentConfig.customHex.toUpperCase();
    }
  }

  // Listen to OS Dark mode change
  if (window.matchMedia) {
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
      if (currentConfig.mode === 'device') {
        applyTheme();
      }
    });
  }

  // Apply immediately before DOM fully loads to prevent flash
  applyTheme();

  // Expose global methods
  window.SkopXTheme = {
    open: function () {
      const drawer = document.getElementById('themeCustomizerModal');
      const backdrop = document.getElementById('themeBackdrop');
      if (drawer) drawer.classList.add('open');
      if (backdrop) backdrop.classList.add('open');
      document.body.classList.add('thm-open');
    },
    close: function () {
      const drawer = document.getElementById('themeCustomizerModal');
      const backdrop = document.getElementById('themeBackdrop');
      if (drawer) drawer.classList.remove('open');
      if (backdrop) backdrop.classList.remove('open');
      document.body.classList.remove('thm-open');
    },
    setMode: function (mode) {
      currentConfig.mode = mode;
      saveConfig(currentConfig);
      applyTheme();
    },
    setPalette: function (key) {
      currentConfig.palette = key;
      saveConfig(currentConfig);
      applyTheme();
    },
    setCustomColor: function (hex) {
      if (!/^#[0-9a-fA-F]{6}$/.test(hex) && !/^#[0-9a-fA-F]{3}$/.test(hex)) return;
      currentConfig.palette = 'custom';
      currentConfig.customHex = hex;
      saveConfig(currentConfig);
      applyTheme();
    },
    reset: function () {
      currentConfig = {
        mode: 'device',
        palette: 'forest',
        customHex: '#4F46E5'
      };
      saveConfig(currentConfig);
      applyTheme();
    },
    getConfig: function () {
      return currentConfig;
    },
    PALETTES: PALETTES
  };

  // Initialize UI event handlers once DOM is ready
  document.addEventListener('DOMContentLoaded', function () {
    // Mode Buttons
    document.querySelectorAll('.thm-mode-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        const mode = this.getAttribute('data-mode');
        window.SkopXTheme.setMode(mode);
      });
    });

    // Palette Swatches
    document.querySelectorAll('.thm-swatch[data-palette]').forEach(swatch => {
      swatch.addEventListener('click', function () {
        const pKey = this.getAttribute('data-palette');
        window.SkopXTheme.setPalette(pKey);
      });
    });

    // Custom Color picker
    const colorPicker = document.getElementById('thmColorPickerInput');
    if (colorPicker) {
      colorPicker.addEventListener('input', function (e) {
        window.SkopXTheme.setCustomColor(e.target.value);
      });
      colorPicker.addEventListener('change', function (e) {
        window.SkopXTheme.setCustomColor(e.target.value);
      });
    }

    // Custom HEX text input
    const hexInput = document.getElementById('thmHexInput');
    if (hexInput) {
      hexInput.addEventListener('input', function (e) {
        let val = e.target.value.trim();
        if (!val.startsWith('#')) val = '#' + val;
        if (/^#[0-9a-fA-F]{6}$/.test(val)) {
          window.SkopXTheme.setCustomColor(val);
        }
      });
    }

    // Eyedropper API
    const eyedropperBtn = document.getElementById('thmEyedropperBtn');
    if (eyedropperBtn) {
      if (window.EyeDropper) {
        eyedropperBtn.addEventListener('click', async () => {
          try {
            const eyeDropper = new window.EyeDropper();
            const result = await eyeDropper.open();
            if (result && result.sRGBHex) {
              window.SkopXTheme.setCustomColor(result.sRGBHex);
            }
          } catch (e) {
            console.log('EyeDropper closed or not supported', e);
          }
        });
      } else {
        // Fallback: trigger color picker click
        eyedropperBtn.addEventListener('click', () => {
          if (colorPicker) colorPicker.click();
        });
      }
    }

    // Reset button
    const resetBtn = document.getElementById('thmResetBtn');
    if (resetBtn) {
      resetBtn.addEventListener('click', function () {
        window.SkopXTheme.reset();
      });
    }

    // Backdrop & Close buttons
    const backdrop = document.getElementById('themeBackdrop');
    if (backdrop) {
      backdrop.addEventListener('click', window.SkopXTheme.close);
    }
    document.querySelectorAll('.thm-close-btn').forEach(btn => {
      btn.addEventListener('click', window.SkopXTheme.close);
    });

    // Open triggers
    document.querySelectorAll('.thm-trigger-btn').forEach(btn => {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        window.SkopXTheme.open();
      });
    });

    updateCustomizerUI();
  });
})();
