<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 316 316" fill="none" {{ $attributes }}>
  <defs>
    <!-- Light mode colors (default) -->
    <style>
      .bg         { fill: #f5f0e8; }
      .book-body  { fill: #2c1a0e; }
      .page       { fill: #fdf6e3; }
      .page-line  { stroke: #c9b99a; }
      .spine      { fill: #1a0f07; }
      .quill-body { fill: #d4a843; }
      .quill-tip  { fill: #2c1a0e; }
      .ink-drop   { fill: #1a0f07; }
      .nib        { fill: #8b6914; }
      .glow       { stop-color: #d4a843; }

      @media (prefers-color-scheme: dark) {
        .bg         { fill: #1a1208; }
        .book-body  { fill: #e8d5b0; }
        .page       { fill: #2a1f10; }
        .page-line  { stroke: #5a4228; }
        .spine      { fill: #f0e0c0; }
        .quill-body { fill: #f0c060; }
        .quill-tip  { fill: #f5e8cc; }
        .ink-drop   { fill: #f0e0c0; }
        .nib        { fill: #c8a030; }
        .glow       { stop-color: #f0c060; }
      }
    </style>

    <radialGradient id="glowGrad" cx="50%" cy="50%" r="50%">
      <stop offset="0%" class="glow" stop-opacity="0.18"/>
      <stop offset="100%" class="glow" stop-opacity="0"/>
    </radialGradient>

    <!-- Feather vane gradient -->
    <linearGradient id="featherGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#f5d980" stop-opacity="1"/>
      <stop offset="50%" stop-color="#d4a843" stop-opacity="1"/>
      <stop offset="100%" stop-color="#a07820" stop-opacity="1"/>
    </linearGradient>
    <linearGradient id="featherGradDark" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#ffe090" stop-opacity="1"/>
      <stop offset="50%" stop-color="#f0c060" stop-opacity="1"/>
      <stop offset="100%" stop-color="#c09030" stop-opacity="1"/>
    </linearGradient>
  </defs>

  <!-- Soft ambient glow -->
  <ellipse cx="158" cy="185" rx="110" ry="70" fill="url(#glowGrad)"/>

  <!-- ── OPEN BOOK ── -->
  <!-- Book shadow -->
  <ellipse cx="158" cy="245" rx="100" ry="10" fill="#000" opacity="0.10"/>

  <!-- Left cover -->
  <path d="M 60 145 Q 58 240 62 242 Q 100 248 155 240 L 155 140 Q 110 133 60 145 Z" class="book-body"/>
  <!-- Right cover -->
  <path d="M 256 145 Q 258 240 254 242 Q 216 248 161 240 L 161 140 Q 206 133 256 145 Z" class="book-body"/>

  <!-- Left page -->
  <path d="M 64 147 Q 62 237 66 239 Q 102 244 153 237 L 153 142 Q 112 136 64 147 Z" class="page"/>
  <!-- Right page -->
  <path d="M 252 147 Q 254 237 250 239 Q 214 244 163 237 L 163 142 Q 204 136 252 147 Z" class="page"/>

  <!-- Left page lines -->
  <line x1="80" y1="162" x2="148" y2="160" class="page-line" stroke-width="1.5" stroke-linecap="round"/>
  <line x1="79" y1="173" x2="147" y2="171" class="page-line" stroke-width="1.5" stroke-linecap="round"/>
  <line x1="78" y1="184" x2="146" y2="182" class="page-line" stroke-width="1.5" stroke-linecap="round"/>
  <line x1="78" y1="195" x2="146" y2="193" class="page-line" stroke-width="1.5" stroke-linecap="round"/>
  <line x1="78" y1="206" x2="146" y2="204" class="page-line" stroke-width="1.5" stroke-linecap="round"/>
  <line x1="78" y1="217" x2="145" y2="215" class="page-line" stroke-width="1.5" stroke-linecap="round"/>
  <line x1="78" y1="228" x2="144" y2="226" class="page-line" stroke-width="1.5" stroke-linecap="round"/>

  <!-- Right page lines -->
  <line x1="168" y1="160" x2="236" y2="162" class="page-line" stroke-width="1.5" stroke-linecap="round"/>
  <line x1="169" y1="171" x2="237" y2="173" class="page-line" stroke-width="1.5" stroke-linecap="round"/>
  <line x1="170" y1="182" x2="238" y2="184" class="page-line" stroke-width="1.5" stroke-linecap="round"/>
  <line x1="170" y1="193" x2="238" y2="195" class="page-line" stroke-width="1.5" stroke-linecap="round"/>
  <line x1="170" y1="204" x2="238" y2="206" class="page-line" stroke-width="1.5" stroke-linecap="round"/>
  <line x1="171" y1="215" x2="238" y2="217" class="page-line" stroke-width="1.5" stroke-linecap="round"/>
  <line x1="171" y1="226" x2="238" y2="228" class="page-line" stroke-width="1.5" stroke-linecap="round"/>

  <!-- Spine crease -->
  <path d="M 158 140 Q 156 190 158 242" class="spine" stroke-width="3" stroke="currentColor" fill="none" opacity="0.4"/>
  <rect x="155" y="140" width="6" height="102" class="spine" rx="3" opacity="0.7"/>

  <!-- ── QUILL PEN ── -->
  <!-- Feather vane – left barbs -->
  <path d="     M 158 88     C 148 100, 122 112, 108 138     C 118 130, 132 120, 145 118     C 135 128, 122 140, 118 158     C 128 148, 138 136, 148 132     C 142 144, 136 158, 134 172     C 142 160, 150 148, 154 142     L 158 88 Z   " fill="url(#featherGrad)" opacity="0.92"/>

  <!-- Feather vane – right barbs -->
  <path d="     M 158 88     C 168 100, 194 112, 208 138     C 198 130, 184 120, 171 118     C 181 128, 194 140, 198 158     C 188 148, 178 136, 168 132     C 174 144, 180 158, 182 172     C 174 160, 166 148, 162 142     L 158 88 Z   " fill="url(#featherGrad)" opacity="0.78"/>

  <!-- Quill shaft -->
  <path d="M 158 88 L 154 200" stroke="#b8860b" stroke-width="2" stroke-linecap="round"/>

  <!-- Nib -->
  <path d="M 154 200 L 150 220 L 158 215 L 162 220 L 158 200 Z" class="nib"/>
  <!-- Ink tip -->
  <path d="M 152 218 L 158 232 L 164 218 L 158 215 Z" class="quill-tip"/>
  <!-- Ink drop -->
  <ellipse cx="158" cy="235" rx="3" ry="4.5" class="ink-drop" opacity="0.7"/>

  <!-- Center rachis highlight -->
  <path d="M 158 90 Q 157 140 156 195" stroke="#fff" stroke-width="0.8" stroke-linecap="round" opacity="0.35"/>
</svg>
