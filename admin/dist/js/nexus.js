import { u as M } from "./useAuth.js";
import { U as I } from "./x-compass-layout.js";
import { U as V } from "./u-spark-view.js";
import {
  d as b,
  a as g,
  r as n,
  o as m,
  c as x,
  w as e,
  e as a,
  b as s,
  t as A,
  p as l,
  aH as L,
  k,
  i as O,
  c1 as _,
  a9 as T,
  q as F,
  F as D,
  s as z,
  aa as R
} from "./index.js";
import { V as u } from "./VCol.js";
import { V as v } from "./VRow.js";
import "./VTooltip.js";
import "./useWpTheme.js";
import "./x-theme-snackbar.js";
import "./useSubAppNavigation.js";
import "./VAlert.js";
import "./window.store.js";
import "./VAppBarNavIcon.js";
import "./VAppBarTitle.js";
import "./VLayout.js";
const U = b({ name: "NexusDashboard", props: { navItemsCount: { type: Number, default: 0 } } }),
  B = { class: "user-stats w-100" },
  P = { class: "stat-item mb-4 d-flex justify-space-between" },
  q = { class: "font-weight-bold" },
  j = { class: "settings-content w-100" };
function W(d, o, t, i, r, c) {
  const f = n("x-divider"),
    w = n("u-glass-card"),
    h = n("x-switch"),
    y = n("x-btn");
  return (
    m(),
    x(
      v,
      { class: "fill-height" },
      {
        default: e(() => [
          a(
            u,
            { cols: "12", md: "6" },
            {
              default: e(() => [
                a(
                  w,
                  { class: "pa-6 fill-height align-start" },
                  {
                    default: e(() => [
                      o[3] ||
                        (o[3] = s(
                          "div",
                          { class: "d-flex align-center mb-6" },
                          [
                            s("i", { class: "fal fa-user-circle fa-2x text-primary mr-4" }),
                            s("h2", { class: "text-h5 font-weight-bold" }, "Network Profile")
                          ],
                          -1
                        )),
                      s("div", B, [
                        s("div", P, [
                          o[0] ||
                            (o[0] = s(
                              "span",
                              { class: "opacity-60 text-uppercase text-caption font-weight-bold" },
                              "Connections",
                              -1
                            )),
                          s("span", q, A(d.navItemsCount), 1)
                        ]),
                        o[1] ||
                          (o[1] = s(
                            "div",
                            { class: "stat-item mb-4 d-flex justify-space-between" },
                            [
                              s(
                                "span",
                                {
                                  class: "opacity-60 text-uppercase text-caption font-weight-bold"
                                },
                                "Status"
                              ),
                              s("span", { class: "text-success font-weight-bold" }, "Online")
                            ],
                            -1
                          )),
                        a(f, { class: "my-4" }),
                        o[2] ||
                          (o[2] = s(
                            "p",
                            { class: "text-caption opacity-70" },
                            " Manage your network connections and digital relationships. ",
                            -1
                          ))
                      ])
                    ]),
                    _: 1
                  }
                )
              ]),
              _: 1
            }
          ),
          a(
            u,
            { cols: "12", md: "6" },
            {
              default: e(() => [
                a(
                  w,
                  { class: "pa-6 fill-height align-start" },
                  {
                    default: e(() => [
                      o[5] ||
                        (o[5] = s(
                          "div",
                          { class: "d-flex align-center mb-6" },
                          [
                            s("i", { class: "fal fa-cog fa-2x text-warning mr-4" }),
                            s("h2", { class: "text-h5 font-weight-bold" }, "Network Settings")
                          ],
                          -1
                        )),
                      s("div", j, [
                        a(h, { label: "Auto-Connect", color: "primary", class: "mb-4" }),
                        a(h, {
                          label: "Connection Notifications",
                          color: "secondary",
                          class: "mb-4"
                        }),
                        a(f, { class: "my-4" }),
                        a(
                          y,
                          {
                            block: "",
                            color: "primary",
                            variant: "tonal",
                            "prepend-icon": "fal fa-atom"
                          },
                          {
                            default: e(() => [...(o[4] || (o[4] = [l("Manage Network", -1)]))]),
                            _: 1
                          }
                        )
                      ])
                    ]),
                    _: 1
                  }
                )
              ]),
              _: 1
            }
          )
        ]),
        _: 1
      }
    )
  );
}
const E = g(U, [
    ["render", W],
    ["__scopeId", "data-v-d948ab6f"]
  ]),
  H = { class: "content-area w-100 h-100 placeholder-sub border-dashed" },
  Y = { class: "settings-content w-100" },
  G = b({
    __name: "NexusChannels",
    setup(d) {
      return (o, t) => {
        const i = n("x-btn"),
          r = n("u-glass-card"),
          c = n("x-switch"),
          f = n("x-divider");
        return (
          m(),
          x(
            v,
            { class: "fill-height ma-0 w-100" },
            {
              default: e(() => [
                a(
                  u,
                  { cols: "12", md: "8" },
                  {
                    default: e(() => [
                      a(
                        r,
                        { class: "pa-6 fill-height align-start" },
                        {
                          default: e(() => [
                            t[4] ||
                              (t[4] = s(
                                "div",
                                { class: "d-flex align-center mb-6" },
                                [
                                  s("i", { class: "fal fa-comments fa-2x text-secondary mr-4" }),
                                  s("h2", { class: "text-h5 font-weight-bold" }, "Comm-Link")
                                ],
                                -1
                              )),
                            s("div", H, [
                              t[1] ||
                                (t[1] = s(
                                  "i",
                                  { class: "fal fa-comments fa-3x mb-4 opacity-20 text-secondary" },
                                  null,
                                  -1
                                )),
                              t[2] ||
                                (t[2] = s(
                                  "h3",
                                  { class: "text-h6 opacity-70 mb-2" },
                                  "Initialize Comm-Link",
                                  -1
                                )),
                              t[3] ||
                                (t[3] = s(
                                  "p",
                                  { class: "text-caption text-medium-emphasis px-4 text-center" },
                                  " Open communication nodes and collaborative channels. ",
                                  -1
                                )),
                              a(
                                i,
                                {
                                  class: "mt-6",
                                  color: "secondary",
                                  variant: "tonal",
                                  "prepend-icon": "fal fa-bolt"
                                },
                                {
                                  default: e(() => [
                                    ...(t[0] || (t[0] = [l(" Activate Module ", -1)]))
                                  ]),
                                  _: 1
                                }
                              )
                            ])
                          ]),
                          _: 1
                        }
                      )
                    ]),
                    _: 1
                  }
                ),
                a(
                  u,
                  { cols: "12", md: "4" },
                  {
                    default: e(() => [
                      a(
                        r,
                        { class: "pa-6 fill-height align-start" },
                        {
                          default: e(() => [
                            t[7] ||
                              (t[7] = s(
                                "div",
                                { class: "d-flex align-center mb-6" },
                                [
                                  s("i", { class: "fal fa-sliders-h fa-2x opacity-70 mr-4" }),
                                  s("h3", { class: "text-h6 font-weight-bold" }, "Configuration")
                                ],
                                -1
                              )),
                            s("div", Y, [
                              a(c, {
                                label: "Sync to Network",
                                color: "secondary",
                                class: "mb-4",
                                "model-value": "true"
                              }),
                              a(c, { label: "Diagnostic Mode", color: "warning", class: "mb-4" }),
                              a(f, { class: "my-6" }),
                              t[6] ||
                                (t[6] = s(
                                  "div",
                                  { class: "text-caption opacity-50 mb-4" },
                                  [
                                    l(" Module Status: "),
                                    s("span", { class: "text-success font-weight-bold" }, "Online"),
                                    s("br"),
                                    l(" Latency: "),
                                    s("span", { class: "font-weight-bold" }, "12ms")
                                  ],
                                  -1
                                )),
                              a(
                                i,
                                {
                                  block: "",
                                  color: "surface-variant",
                                  variant: "tonal",
                                  "prepend-icon": "fal fa-cog"
                                },
                                {
                                  default: e(() => [
                                    ...(t[5] || (t[5] = [l(" Advanced Settings ", -1)]))
                                  ]),
                                  _: 1
                                }
                              )
                            ])
                          ]),
                          _: 1
                        }
                      )
                    ]),
                    _: 1
                  }
                )
              ]),
              _: 1
            }
          )
        );
      };
    }
  }),
  J = g(G, [["__scopeId", "data-v-59b4f896"]]),
  K = { class: "content-area w-100 h-100 placeholder-sub border-dashed" },
  Q = { class: "settings-content w-100" },
  X = b({
    __name: "NexusConstellations",
    setup(d) {
      return (o, t) => {
        const i = n("x-btn"),
          r = n("u-glass-card"),
          c = n("x-switch"),
          f = n("x-divider");
        return (
          m(),
          x(
            v,
            { class: "fill-height ma-0 w-100" },
            {
              default: e(() => [
                a(
                  u,
                  { cols: "12", md: "8" },
                  {
                    default: e(() => [
                      a(
                        r,
                        { class: "pa-6 fill-height align-start" },
                        {
                          default: e(() => [
                            t[4] ||
                              (t[4] = s(
                                "div",
                                { class: "d-flex align-center mb-6" },
                                [
                                  s("i", {
                                    class: "fad fa-chart-network fa-2x text-secondary mr-4"
                                  }),
                                  s("h2", { class: "text-h5 font-weight-bold" }, "Constellations")
                                ],
                                -1
                              )),
                            s("div", K, [
                              t[1] ||
                                (t[1] = s(
                                  "i",
                                  {
                                    class:
                                      "fad fa-chart-network fa-3x mb-4 opacity-20 text-secondary"
                                  },
                                  null,
                                  -1
                                )),
                              t[2] ||
                                (t[2] = s(
                                  "h3",
                                  { class: "text-h6 opacity-70 mb-2" },
                                  "Initialize Constellations",
                                  -1
                                )),
                              t[3] ||
                                (t[3] = s(
                                  "p",
                                  { class: "text-caption text-medium-emphasis px-4 text-center" },
                                  " Form groups, alliances, and stellar networks. ",
                                  -1
                                )),
                              a(
                                i,
                                {
                                  class: "mt-6",
                                  color: "secondary",
                                  variant: "tonal",
                                  "prepend-icon": "fal fa-bolt"
                                },
                                {
                                  default: e(() => [
                                    ...(t[0] || (t[0] = [l(" Activate Module ", -1)]))
                                  ]),
                                  _: 1
                                }
                              )
                            ])
                          ]),
                          _: 1
                        }
                      )
                    ]),
                    _: 1
                  }
                ),
                a(
                  u,
                  { cols: "12", md: "4" },
                  {
                    default: e(() => [
                      a(
                        r,
                        { class: "pa-6 fill-height align-start" },
                        {
                          default: e(() => [
                            t[7] ||
                              (t[7] = s(
                                "div",
                                { class: "d-flex align-center mb-6" },
                                [
                                  s("i", { class: "fal fa-sliders-h fa-2x opacity-70 mr-4" }),
                                  s("h3", { class: "text-h6 font-weight-bold" }, "Configuration")
                                ],
                                -1
                              )),
                            s("div", Q, [
                              a(c, {
                                label: "Sync to Network",
                                color: "secondary",
                                class: "mb-4",
                                "model-value": "true"
                              }),
                              a(c, { label: "Diagnostic Mode", color: "warning", class: "mb-4" }),
                              a(f, { class: "my-6" }),
                              t[6] ||
                                (t[6] = s(
                                  "div",
                                  { class: "text-caption opacity-50 mb-4" },
                                  [
                                    l(" Module Status: "),
                                    s("span", { class: "text-success font-weight-bold" }, "Online"),
                                    s("br"),
                                    l(" Latency: "),
                                    s("span", { class: "font-weight-bold" }, "12ms")
                                  ],
                                  -1
                                )),
                              a(
                                i,
                                {
                                  block: "",
                                  color: "surface-variant",
                                  variant: "tonal",
                                  "prepend-icon": "fal fa-cog"
                                },
                                {
                                  default: e(() => [
                                    ...(t[5] || (t[5] = [l(" Advanced Settings ", -1)]))
                                  ]),
                                  _: 1
                                }
                              )
                            ])
                          ]),
                          _: 1
                        }
                      )
                    ]),
                    _: 1
                  }
                )
              ]),
              _: 1
            }
          )
        );
      };
    }
  }),
  Z = g(X, [["__scopeId", "data-v-7a699777"]]),
  tt = { class: "content-area w-100 h-100 placeholder-sub border-dashed" },
  st = { class: "settings-content w-100" },
  at = b({
    __name: "NexusForge",
    setup(d) {
      return (o, t) => {
        const i = n("x-btn"),
          r = n("u-glass-card"),
          c = n("x-switch"),
          f = n("x-divider");
        return (
          m(),
          x(
            v,
            { class: "fill-height ma-0 w-100" },
            {
              default: e(() => [
                a(
                  u,
                  { cols: "12", md: "8" },
                  {
                    default: e(() => [
                      a(
                        r,
                        { class: "pa-6 fill-height align-start" },
                        {
                          default: e(() => [
                            t[4] ||
                              (t[4] = s(
                                "div",
                                { class: "d-flex align-center mb-6" },
                                [
                                  s("i", { class: "fal fa-hammer fa-2x text-warning mr-4" }),
                                  s("h2", { class: "text-h5 font-weight-bold" }, "The Forge")
                                ],
                                -1
                              )),
                            s("div", tt, [
                              t[1] ||
                                (t[1] = s(
                                  "i",
                                  { class: "fal fa-hammer fa-3x mb-4 opacity-20 text-warning" },
                                  null,
                                  -1
                                )),
                              t[2] ||
                                (t[2] = s(
                                  "h3",
                                  { class: "text-h6 opacity-70 mb-2" },
                                  "Initialize The Forge",
                                  -1
                                )),
                              t[3] ||
                                (t[3] = s(
                                  "p",
                                  { class: "text-caption text-medium-emphasis px-4 text-center" },
                                  " Co-creation space for building digital constructs. ",
                                  -1
                                )),
                              a(
                                i,
                                {
                                  class: "mt-6",
                                  color: "warning",
                                  variant: "tonal",
                                  "prepend-icon": "fal fa-bolt"
                                },
                                {
                                  default: e(() => [
                                    ...(t[0] || (t[0] = [l(" Activate Module ", -1)]))
                                  ]),
                                  _: 1
                                }
                              )
                            ])
                          ]),
                          _: 1
                        }
                      )
                    ]),
                    _: 1
                  }
                ),
                a(
                  u,
                  { cols: "12", md: "4" },
                  {
                    default: e(() => [
                      a(
                        r,
                        { class: "pa-6 fill-height align-start" },
                        {
                          default: e(() => [
                            t[7] ||
                              (t[7] = s(
                                "div",
                                { class: "d-flex align-center mb-6" },
                                [
                                  s("i", { class: "fal fa-sliders-h fa-2x opacity-70 mr-4" }),
                                  s("h3", { class: "text-h6 font-weight-bold" }, "Configuration")
                                ],
                                -1
                              )),
                            s("div", st, [
                              a(c, {
                                label: "Sync to Network",
                                color: "warning",
                                class: "mb-4",
                                "model-value": "true"
                              }),
                              a(c, { label: "Diagnostic Mode", color: "warning", class: "mb-4" }),
                              a(f, { class: "my-6" }),
                              t[6] ||
                                (t[6] = s(
                                  "div",
                                  { class: "text-caption opacity-50 mb-4" },
                                  [
                                    l(" Module Status: "),
                                    s("span", { class: "text-success font-weight-bold" }, "Online"),
                                    s("br"),
                                    l(" Latency: "),
                                    s("span", { class: "font-weight-bold" }, "12ms")
                                  ],
                                  -1
                                )),
                              a(
                                i,
                                {
                                  block: "",
                                  color: "surface-variant",
                                  variant: "tonal",
                                  "prepend-icon": "fal fa-cog"
                                },
                                {
                                  default: e(() => [
                                    ...(t[5] || (t[5] = [l(" Advanced Settings ", -1)]))
                                  ]),
                                  _: 1
                                }
                              )
                            ])
                          ]),
                          _: 1
                        }
                      )
                    ]),
                    _: 1
                  }
                )
              ]),
              _: 1
            }
          )
        );
      };
    }
  }),
  et = g(at, [["__scopeId", "data-v-a5592dae"]]),
  nt = { class: "content-area w-100 h-100 placeholder-sub border-dashed" },
  ot = { class: "settings-content w-100" },
  lt = b({
    __name: "NexusProfile",
    setup(d) {
      return (o, t) => {
        const i = n("x-btn"),
          r = n("u-glass-card"),
          c = n("x-switch"),
          f = n("x-divider");
        return (
          m(),
          x(
            v,
            { class: "fill-height ma-0 w-100" },
            {
              default: e(() => [
                a(
                  u,
                  { cols: "12", md: "8" },
                  {
                    default: e(() => [
                      a(
                        r,
                        { class: "pa-6 fill-height align-start" },
                        {
                          default: e(() => [
                            t[4] ||
                              (t[4] = s(
                                "div",
                                { class: "d-flex align-center mb-6" },
                                [
                                  s("i", {
                                    class: "fad fa-broadcast-tower fa-2x text-secondary mr-4"
                                  }),
                                  s("h2", { class: "text-h5 font-weight-bold" }, "My Signal")
                                ],
                                -1
                              )),
                            s("div", nt, [
                              t[1] ||
                                (t[1] = s(
                                  "i",
                                  {
                                    class:
                                      "fad fa-broadcast-tower fa-3x mb-4 opacity-20 text-secondary"
                                  },
                                  null,
                                  -1
                                )),
                              t[2] ||
                                (t[2] = s(
                                  "h3",
                                  { class: "text-h6 opacity-70 mb-2" },
                                  "Initialize My Signal",
                                  -1
                                )),
                              t[3] ||
                                (t[3] = s(
                                  "p",
                                  { class: "text-caption text-medium-emphasis px-4 text-center" },
                                  " Your public identity and broadcast frequency. ",
                                  -1
                                )),
                              a(
                                i,
                                {
                                  class: "mt-6",
                                  color: "secondary",
                                  variant: "tonal",
                                  "prepend-icon": "fal fa-bolt"
                                },
                                {
                                  default: e(() => [
                                    ...(t[0] || (t[0] = [l(" Activate Module ", -1)]))
                                  ]),
                                  _: 1
                                }
                              )
                            ])
                          ]),
                          _: 1
                        }
                      )
                    ]),
                    _: 1
                  }
                ),
                a(
                  u,
                  { cols: "12", md: "4" },
                  {
                    default: e(() => [
                      a(
                        r,
                        { class: "pa-6 fill-height align-start" },
                        {
                          default: e(() => [
                            t[7] ||
                              (t[7] = s(
                                "div",
                                { class: "d-flex align-center mb-6" },
                                [
                                  s("i", { class: "fal fa-sliders-h fa-2x opacity-70 mr-4" }),
                                  s("h3", { class: "text-h6 font-weight-bold" }, "Configuration")
                                ],
                                -1
                              )),
                            s("div", ot, [
                              a(c, {
                                label: "Sync to Network",
                                color: "secondary",
                                class: "mb-4",
                                "model-value": "true"
                              }),
                              a(c, { label: "Diagnostic Mode", color: "warning", class: "mb-4" }),
                              a(f, { class: "my-6" }),
                              t[6] ||
                                (t[6] = s(
                                  "div",
                                  { class: "text-caption opacity-50 mb-4" },
                                  [
                                    l(" Module Status: "),
                                    s("span", { class: "text-success font-weight-bold" }, "Online"),
                                    s("br"),
                                    l(" Latency: "),
                                    s("span", { class: "font-weight-bold" }, "12ms")
                                  ],
                                  -1
                                )),
                              a(
                                i,
                                {
                                  block: "",
                                  color: "surface-variant",
                                  variant: "tonal",
                                  "prepend-icon": "fal fa-cog"
                                },
                                {
                                  default: e(() => [
                                    ...(t[5] || (t[5] = [l(" Advanced Settings ", -1)]))
                                  ]),
                                  _: 1
                                }
                              )
                            ])
                          ]),
                          _: 1
                        }
                      )
                    ]),
                    _: 1
                  }
                )
              ]),
              _: 1
            }
          )
        );
      };
    }
  }),
  it = g(lt, [["__scopeId", "data-v-ecf40569"]]),
  rt = { class: "content-area w-100 h-100 placeholder-sub border-dashed" },
  ct = { class: "settings-content w-100" },
  dt = b({
    __name: "NexusResonance",
    setup(d) {
      return (o, t) => {
        const i = n("x-btn"),
          r = n("u-glass-card"),
          c = n("x-switch"),
          f = n("x-divider");
        return (
          m(),
          x(
            v,
            { class: "fill-height ma-0 w-100" },
            {
              default: e(() => [
                a(
                  u,
                  { cols: "12", md: "8" },
                  {
                    default: e(() => [
                      a(
                        r,
                        { class: "pa-6 fill-height align-start" },
                        {
                          default: e(() => [
                            t[4] ||
                              (t[4] = s(
                                "div",
                                { class: "d-flex align-center mb-6" },
                                [
                                  s("i", { class: "fal fa-wave-sine fa-2x text-secondary mr-4" }),
                                  s("h2", { class: "text-h5 font-weight-bold" }, "Resonance")
                                ],
                                -1
                              )),
                            s("div", rt, [
                              t[1] ||
                                (t[1] = s(
                                  "i",
                                  {
                                    class: "fal fa-wave-sine fa-3x mb-4 opacity-20 text-secondary"
                                  },
                                  null,
                                  -1
                                )),
                              t[2] ||
                                (t[2] = s(
                                  "h3",
                                  { class: "text-h6 opacity-70 mb-2" },
                                  "Initialize Resonance",
                                  -1
                                )),
                              t[3] ||
                                (t[3] = s(
                                  "p",
                                  { class: "text-caption text-medium-emphasis px-4 text-center" },
                                  " Vibrational matching and frequency alignment. ",
                                  -1
                                )),
                              a(
                                i,
                                {
                                  class: "mt-6",
                                  color: "secondary",
                                  variant: "tonal",
                                  "prepend-icon": "fal fa-bolt"
                                },
                                {
                                  default: e(() => [
                                    ...(t[0] || (t[0] = [l(" Activate Module ", -1)]))
                                  ]),
                                  _: 1
                                }
                              )
                            ])
                          ]),
                          _: 1
                        }
                      )
                    ]),
                    _: 1
                  }
                ),
                a(
                  u,
                  { cols: "12", md: "4" },
                  {
                    default: e(() => [
                      a(
                        r,
                        { class: "pa-6 fill-height align-start" },
                        {
                          default: e(() => [
                            t[7] ||
                              (t[7] = s(
                                "div",
                                { class: "d-flex align-center mb-6" },
                                [
                                  s("i", { class: "fal fa-sliders-h fa-2x opacity-70 mr-4" }),
                                  s("h3", { class: "text-h6 font-weight-bold" }, "Configuration")
                                ],
                                -1
                              )),
                            s("div", ct, [
                              a(c, {
                                label: "Sync to Network",
                                color: "secondary",
                                class: "mb-4",
                                "model-value": "true"
                              }),
                              a(c, { label: "Diagnostic Mode", color: "warning", class: "mb-4" }),
                              a(f, { class: "my-6" }),
                              t[6] ||
                                (t[6] = s(
                                  "div",
                                  { class: "text-caption opacity-50 mb-4" },
                                  [
                                    l(" Module Status: "),
                                    s("span", { class: "text-success font-weight-bold" }, "Online"),
                                    s("br"),
                                    l(" Latency: "),
                                    s("span", { class: "font-weight-bold" }, "12ms")
                                  ],
                                  -1
                                )),
                              a(
                                i,
                                {
                                  block: "",
                                  color: "surface-variant",
                                  variant: "tonal",
                                  "prepend-icon": "fal fa-cog"
                                },
                                {
                                  default: e(() => [
                                    ...(t[5] || (t[5] = [l(" Advanced Settings ", -1)]))
                                  ]),
                                  _: 1
                                }
                              )
                            ])
                          ]),
                          _: 1
                        }
                      )
                    ]),
                    _: 1
                  }
                )
              ]),
              _: 1
            }
          )
        );
      };
    }
  }),
  ft = g(dt, [["__scopeId", "data-v-7a3da2b8"]]),
  ut = b({
    name: "NexosSpark",
    components: {
      UWindow: I,
      USparkView: V,
      NexusDashboard: E,
      NexusChannels: J,
      NexusConstellations: Z,
      NexusForge: et,
      NexusProfile: it,
      NexusResonance: ft
    },
    setup() {
      const { user: d } = M(),
        o = k([]),
        t = k("nexus-dashboard"),
        i = O({ get: () => [t.value], set: (r) => (t.value = r[0] || "nexus-dashboard") });
      return (
        L(async () => {
          o.value = [
            {
              name: "My Nexus",
              icon: "fad fa-user-astronaut",
              description: "Nexos overview.",
              route: "nexus-dashboard"
            },
            {
              name: "Comm-Link",
              icon: "fal fa-comments",
              description: "Open communication.",
              route: "nexus-channels"
            },
            {
              name: "Constellations",
              icon: "fad fa-chart-network",
              description: "Form groups.",
              route: "nexus-constellations"
            },
            {
              name: "The Forge",
              icon: "fal fa-hammer",
              description: "Co-creation space.",
              route: "nexus-forge"
            },
            {
              name: "Resonance",
              icon: "fal fa-wave-sine",
              description: "Vibrational matching.",
              route: "nexus-resonance"
            },
            {
              name: "My Signal",
              icon: "fad fa-broadcast-tower",
              description: "Public identity.",
              route: "nexus-profile"
            }
          ];
        }),
        { user: d, navItems: o, activeTab: t, selectedTab: i }
      );
    }
  });
function pt(d, o, t, i, r, c) {
  const f = n("nexus-dashboard"),
    w = n("nexus-channels"),
    h = n("nexus-constellations"),
    y = n("nexus-forge"),
    N = n("nexus-profile"),
    C = n("nexus-resonance"),
    S = n("u-spark-view"),
    $ = n("u-window");
  return (
    m(),
    x(
      $,
      {
        id: "u-nexus",
        icon: "fal fa-atom",
        title: "Nexos",
        variant: "glassmorphic",
        maximized: ""
      },
      {
        "nav-content": e(() => [
          a(
            T,
            {
              selected: d.selectedTab,
              "onUpdate:selected": o[0] || (o[0] = (p) => (d.selectedTab = p)),
              "bg-color": "transparent",
              class: "pa-0",
              mandatory: ""
            },
            {
              default: e(() => [
                (m(!0),
                F(
                  D,
                  null,
                  z(
                    d.navItems,
                    (p) => (
                      m(),
                      x(
                        R,
                        {
                          key: p.route,
                          value: p.route,
                          "prepend-icon": p.icon,
                          title: p.name,
                          onClick: (mt) => (d.activeTab = p.route)
                        },
                        null,
                        8,
                        ["value", "prepend-icon", "title", "onClick"]
                      )
                    )
                  ),
                  128
                ))
              ]),
              _: 1
            },
            8,
            ["selected"]
          )
        ]),
        default: e(() => [
          a(
            S,
            {
              modelValue: d.activeTab,
              "onUpdate:modelValue": o[1] || (o[1] = (p) => (d.activeTab = p))
            },
            {
              default: e(() => [
                a(_, { value: "nexus-dashboard" }, { default: e(() => [a(f)]), _: 1 }),
                a(_, { value: "nexus-channels" }, { default: e(() => [a(w)]), _: 1 }),
                a(_, { value: "nexus-constellations" }, { default: e(() => [a(h)]), _: 1 }),
                a(_, { value: "nexus-forge" }, { default: e(() => [a(y)]), _: 1 }),
                a(_, { value: "nexus-profile" }, { default: e(() => [a(N)]), _: 1 }),
                a(_, { value: "nexus-resonance" }, { default: e(() => [a(C)]), _: 1 })
              ]),
              _: 1
            },
            8,
            ["modelValue"]
          )
        ]),
        _: 1
      }
    )
  );
}
const Vt = g(ut, [["render", pt]]);
export { Vt as default };
