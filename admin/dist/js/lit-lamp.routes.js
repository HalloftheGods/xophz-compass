const __vite__mapDeps = (
  i,
  m = __vite__mapDeps,
  d = m.f ||
    (m.f = [
      "js/lit-lamp.js",
      "js/index.js",
      "css/index.css",
      "js/index6.js",
      "js/sub-app-navigation-drawer.js",
      "js/sub-app-billboard.js",
      "js/useSubAppNavigation.js",
      "js/VLayout.js",
      "css/VLayout.css",
      "js/VRow.js",
      "js/VCol.js",
      "css/sub-app-billboard.css",
      "css/sub-app-navigation-drawer.css",
      "js/billboard-navigation-drawer.js",
      "css/billboard-navigation-drawer.css",
      "js/VAppBarNavIcon.js",
      "js/lit-lamp-dash.js",
      "js/lit-lamp.api.js",
      "js/VAlert.js",
      "css/VAlert.css",
      "css/lit-lamp-dash.css",
      "js/lit-lamp-wp-info.js",
      "css/lit-lamp-wp-info.css",
      "js/lit-lamp-logs.js",
      "css/lit-lamp-logs.css",
      "js/lit-lamp-files.js",
      "css/lit-lamp-files.css",
      "js/lit-lamp-cron.js",
      "css/lit-lamp-cron.css"
    ])
) => i.map((i) => d[i]);
import { _ as o } from "./index.js";
const t = () =>
    o(
      () => import("./lit-lamp.js"),
      __vite__mapDeps([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15])
    ),
  e = () =>
    o(() => import("./lit-lamp-dash.js"), __vite__mapDeps([16, 3, 1, 2, 17, 9, 10, 18, 19, 20])),
  n = () =>
    o(() => import("./lit-lamp-wp-info.js"), __vite__mapDeps([21, 17, 1, 2, 9, 10, 18, 19, 22])),
  a = () =>
    o(() => import("./lit-lamp-logs.js"), __vite__mapDeps([23, 17, 1, 2, 9, 10, 18, 19, 24])),
  m = () => o(() => import("./lit-lamp-files.js"), __vite__mapDeps([25, 17, 1, 2, 9, 10, 26])),
  i = () => o(() => import("./lit-lamp-cron.js"), __vite__mapDeps([27, 17, 1, 2, 9, 10, 28])),
  r = {
    path: "lit-lamp",
    name: "xophz-compass-lit-lamp",
    component: t,
    children: [
      {
        name: "LAMP Dashboard",
        path: "",
        component: e,
        meta: { icon: "lightbulb", color: "#FFC107" }
      },
      {
        name: "WordPress Info",
        path: "wp-info",
        component: n,
        meta: { icon: "wordpress", color: "#21759B" }
      },
      {
        name: "Debug Logs",
        path: "logs",
        component: a,
        meta: { icon: "file-alt", color: "#FF5722" }
      },
      {
        name: "File System",
        path: "files",
        component: m,
        meta: { icon: "folder-tree", color: "#4CAF50" }
      },
      { name: "Cron Jobs", path: "cron", component: i, meta: { icon: "clock", color: "#9C27B0" } }
    ]
  };
export { r as default };
