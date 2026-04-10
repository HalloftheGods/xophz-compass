# Contributing to My Compass

Welcome! We are excited to have you contribute to the My Compass base plugin. This project acts as the "Starship" foundational UI skin for WordPress, replacing the legacy admin panel with a dark-mode, glassmorphic Vue 3 application layer.

To maintain a cohesive, high-quality ecosystem across the My Compass Suite, we require all contributors to follow these guidelines.

## 1. Returning Changes to the Core Ecosystem

In the spirit of the open-source community, if you modify the free GPLv3 portions of the My Compass architecture to fix bugs, optimize performance, or add universal features, **we enthusiastically ask you to submit those changes back to this repository via Pull Request.**

If you improve the hull of the Starship, share the blueprints so the entire fleet benefits.

## 2. Contributor License Agreement (CLA)

Because the base My Compass UI serves as the foundation for the larger commercial YouMeOS ecosystem, we must ensure that the primary developers retain the legal rights to distribute the software safely across all tiers.

By submitting a Pull Request to this repository, you agree to the following **Contributor License Agreement**:

1. **Copyright Assignment:** You grant the maintainers of My Compass a perpetual, worldwide, non-exclusive, no-charge, royalty-free, irrevocable license to reproduce, prepare derivative works of, publicly display, publicly perform, sublicense, and distribute your contributions.
2. **Commercial Use:** You understand and agree that your contributions may be bundled, integrated, or distributed within both the free open-source (GPLv3) version of My Compass AND any future proprietary or commercial modules within the My Compass Suite without any compensation owed to you.
3. **Originality:** You affirm that you are the original author of the code you are submitting, and you have the legal right to grant these permissions.

*Simply opening a Pull Request constitutes your agreement to this CLA.*

## 3. Code Standards & Aesthetics

If you are contributing UI/UX components to the My Compass skin:
- Our visual identity is defined in `Project-Philosophy.md`.
- All interfaces must adhere to the "Starship Aesthetics": Dark Mode by default, Glassmorphism `backdrop-filter: blur()`, and Cyan (`#62c9ff`) neon accents.
- Do not introduce raw HTML or standard `v-*` Vuetify components into views if a custom `x-*` atom wrapper exists in `docs/Design-System-Atoms.md`.

Thank you for helping us push the WordPress interface into the future!
