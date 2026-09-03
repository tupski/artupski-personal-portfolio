import "@hotwired/turbo";
import { Application } from "@hotwired/stimulus";
import ThemeController from "./controllers/theme_controller";
import MobileNavController from "./controllers/mobile_nav_controller";
import CopyController from "./controllers/copy_controller";

const application = Application.start();
application.register("theme", ThemeController);
application.register("mobile-nav", MobileNavController);
application.register("copy", CopyController);

// Turbo: re-apply theme-color after navigation (§8.3)
document.addEventListener("turbo:load", () => {
  const root = document.documentElement;
  const dark = root.classList.contains("dark");
  const meta = document.querySelector('meta[name="theme-color"]');
  if (meta) meta.setAttribute("content", dark ? "#0C0A09" : "#FFFFFF");

  // Move focus to main for screen readers (§9.1)
  const main = document.getElementById("main");
  if (main) main.focus({ preventScroll: true });
});
