import { Controller } from "@hotwired/stimulus";

/**
 * Copy-to-clipboard controller for code blocks (§4, §72).
 * Progressive enhancement — absent when JS has not booted.
 */
export default class extends Controller {
  static targets = ["label"];
  static values = { successDuration: { type: Number, default: 2000 } };

  connect() {
    this._timeout = null;
  }

  disconnect() {
    if (this._timeout) clearTimeout(this._timeout);
  }

  async copy(event) {
    const code = this.element.closest("[data-code-block]");
    if (!code) return;

    const pre = code.querySelector("pre");
    if (!pre) return;

    const text = pre.textContent;

    try {
      await navigator.clipboard.writeText(text);
      this._showSuccess();
    } catch {
      // Fallback: select the text
      this._fallbackCopy(pre);
    }
  }

  _showSuccess() {
    if (this.hasLabelTarget) {
      this.labelTarget.textContent = "Copied";
    }

    this.element.setAttribute("aria-live", "polite");

    this._timeout = setTimeout(() => {
      if (this.hasLabelTarget) {
        this.labelTarget.textContent = "Copy";
      }
      this.element.removeAttribute("aria-live");
    }, this.successDurationValue);
  }

  _fallbackCopy(pre) {
    const range = document.createRange();
    range.selectNodeContents(pre);
    const selection = window.getSelection();
    selection.removeAllRanges();
    selection.addRange(range);

    try {
      document.execCommand("copy");
      this._showSuccess();
    } catch {
      if (this.hasLabelTarget) {
        this.labelTarget.textContent = "Copy failed";
        setTimeout(() => {
          this.labelTarget.textContent = "Copy";
        }, this.successDurationValue);
      }
    }

    selection.removeAllRanges();
  }
}
