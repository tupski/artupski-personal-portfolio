import { Controller } from "@hotwired/stimulus";

/**
 * Theme controller — light / dark / system toggle (§8).
 * Persists to localStorage key "theme".
 * Reads from document.documentElement.dataset.theme.
 */
export default class extends Controller {
  static targets = ["label"];
  static values = { order: { type: Array, default: ["light", "dark", "system"] } };

  // Register OS preference listener once at module level (§8.3)
  static _mediaQuery = null;
  static _mediaListener = null;

  connect() {
    this.render();

    // Register the system preference listener once
    if (!constructor._mediaQuery) {
      constructor._mediaQuery = window.matchMedia("(prefers-color-scheme: dark)");
      constructor._mediaListener = () => {
        if (this.current === "system") this.apply("system");
      };
      constructor._mediaQuery.addEventListener("change", constructor._mediaListener);
    }
  }

  disconnect() {
    // No cleanup needed — listener is module-level to survive Turbo
  }

  toggle() {
    const order = this.orderValue;
    const next = order[(order.indexOf(this.current) + 1) % order.length];
    if (next === "system") {
      localStorage.removeItem("theme");
    } else {
      localStorage.setItem("theme", next);
    }
    this.apply(next);
    this.render();
  }

  /** @returns {"light"|"dark"|"system"} */
  get current() {
    return document.documentElement.dataset.theme || "system";
  }

  apply(choice) {
    const root = document.documentElement;
    const dark =
      choice === "dark" ||
      (choice === "system" &&
        window.matchMedia("(prefers-color-scheme: dark)").matches);

    root.classList.toggle("dark", dark);
    root.dataset.theme = choice;
    root.style.colorScheme = dark ? "dark" : "light";

    const meta = document.querySelector('meta[name="theme-color"]');
    if (meta) meta.setAttribute("content", dark ? "#0C0A09" : "#FFFFFF");
  }

  render() {
    if (!this.hasLabelTarget) return;
    const current = this.current;
    const labels = { light: "Dark", dark: "System", system: "Light" };
    this.labelTarget.textContent = labels[current] || "Toggle theme";

    // aria-label names the NEXT state (§4)
    this.element.setAttribute("aria-label", `Switch to ${labels[current]}`);
  }
}
