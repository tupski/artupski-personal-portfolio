import { Controller } from "@hotwired/stimulus";

/**
 * Mobile nav controller — hamburger menu with focus trap (§4, §9.6).
 * Closes on Turbo navigation and Escape key.
 */
export default class extends Controller {
  static targets = ["panel", "overlay"];

  connect() {
    this._handleKeydown = this._onKeydown.bind(this);
    this._handleTurboBeforeVisit = this._close.bind(this);

    document.addEventListener("turbo:before-visit", this._handleTurboBeforeVisit);
  }

  disconnect() {
    this._close();
    document.removeEventListener("turbo:before-visit", this._handleTurboBeforeVisit);
    document.removeEventListener("keydown", this._handleKeydown);
  }

  toggle() {
    const isOpen = this.element.getAttribute("aria-expanded") === "true";
    if (isOpen) {
      this._close();
    } else {
      this._open();
    }
  }

  _open() {
    this.element.setAttribute("aria-expanded", "true");
    this.panelTarget.removeAttribute("hidden");

    // Prevent body scroll (§9.6)
    document.body.style.overflow = "hidden";

    // Focus first link in panel
    const firstLink = this.panelTarget.querySelector("a, button");
    if (firstLink) firstLink.focus();

    // Trap focus
    document.addEventListener("keydown", this._handleKeydown);

    // Show overlay
    if (this.hasOverlayTarget) {
      this.overlayTarget.removeAttribute("hidden");
    }
  }

  _close() {
    this.element.setAttribute("aria-expanded", "false");
    this.panelTarget.setAttribute("hidden", "");

    // Restore body scroll
    document.body.style.overflow = "";

    // Remove focus trap
    document.removeEventListener("keydown", this._handleKeydown);

    // Hide overlay
    if (this.hasOverlayTarget) {
      this.overlayTarget.setAttribute("hidden", "");
    }

    // Return focus to toggle button
    this.element.focus();
  }

  _onKeydown(event) {
    if (event.key === "Escape") {
      this._close();
      return;
    }

    // Focus trap
    if (event.key === "Tab") {
      const focusable = this.panelTarget.querySelectorAll(
        'a[href], button:not([disabled]), input:not([disabled]), [tabindex]:not([tabindex="-1"])'
      );
      if (focusable.length === 0) return;

      const first = focusable[0];
      const last = focusable[focusable.length - 1];

      if (event.shiftKey && document.activeElement === first) {
        event.preventDefault();
        last.focus();
      } else if (!event.shiftKey && document.activeElement === last) {
        event.preventDefault();
        first.focus();
      }
    }
  }
}
