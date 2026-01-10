import { b as D } from "./lit-lamp.api.js";
import {
  d as w,
  an as n,
  a8 as _,
  a as T,
  c as u,
  o as i,
  w as e,
  e as a,
  bo as F,
  bd as b,
  h,
  W as U,
  g as B,
  aB as L,
  i as d,
  t as c,
  R as z,
  bn as E,
  cx as I,
  aE as N,
  l as S,
  a4 as $,
  O as A
} from "./index.js";
import { V } from "./VRow.js";
import { V as r } from "./VCol.js";
import { V as Q, a as W } from "./VAlert.js";
import { V as G } from "./VContainer.js";
/* empty css     */ const O = w({
    name: "LitLampLogs",
    setup() {
      const l = n(null),
        t = n(!1),
        v = n(null),
        f = n(""),
        m = n("all"),
        p = n(100),
        o = [
          { title: "All Levels", value: "all" },
          { title: "Fatal", value: "fatal" },
          { title: "Error", value: "error" },
          { title: "Warning", value: "warning" },
          { title: "Notice", value: "notice" },
          { title: "Info", value: "info" }
        ];
      let y = null;
      const g = async () => {
          t.value = !0;
          try {
            const s = await D({ lines: p.value, search: f.value, level: m.value });
            s.data.success && (l.value = s.data.data);
          } catch (s) {
            console.error(s);
          } finally {
            t.value = !1;
          }
        },
        C = () => {
          clearTimeout(y), (y = setTimeout(g, 300));
        },
        k = (s) =>
          ({
            fatal: "red-darken-2",
            error: "red",
            warning: "orange",
            notice: "yellow-darken-2",
            info: "blue"
          }[s] || "grey");
      return (
        _(g),
        {
          logData: l,
          isLoading: t,
          error: v,
          searchQuery: f,
          levelFilter: m,
          lineCount: p,
          levels: o,
          fetchData: g,
          debouncedFetch: C,
          getLevelColor: k
        }
      );
    }
  }),
  R = { key: 0, class: "text-caption text-grey mr-2" };
function j(l, t, v, f, m, p) {
  return (
    i(),
    u(
      G,
      { fluid: "", class: "lit-lamp-logs pa-4" },
      {
        default: e(() => [
          a(
            V,
            { class: "mb-4" },
            {
              default: e(() => [
                a(
                  r,
                  { cols: "12", md: "4" },
                  {
                    default: e(() => [
                      a(
                        F,
                        {
                          modelValue: l.searchQuery,
                          "onUpdate:modelValue": [
                            t[0] || (t[0] = (o) => (l.searchQuery = o)),
                            l.debouncedFetch
                          ],
                          "prepend-inner-icon": "fas fa-search",
                          label: "Search logs...",
                          variant: "solo-filled",
                          flat: "",
                          density: "compact",
                          "hide-details": "",
                          clearable: "",
                          "bg-color": "rgba(255, 255, 255, 0.05)"
                        },
                        null,
                        8,
                        ["modelValue", "onUpdate:modelValue"]
                      )
                    ]),
                    _: 1
                  }
                ),
                a(
                  r,
                  { cols: "12", md: "3" },
                  {
                    default: e(() => [
                      a(
                        b,
                        {
                          modelValue: l.levelFilter,
                          "onUpdate:modelValue": [
                            t[1] || (t[1] = (o) => (l.levelFilter = o)),
                            l.fetchData
                          ],
                          items: l.levels,
                          label: "Log Level",
                          variant: "solo-filled",
                          flat: "",
                          density: "compact",
                          "hide-details": "",
                          "bg-color": "rgba(255, 255, 255, 0.05)"
                        },
                        null,
                        8,
                        ["modelValue", "items", "onUpdate:modelValue"]
                      )
                    ]),
                    _: 1
                  }
                ),
                a(
                  r,
                  { cols: "12", md: "2" },
                  {
                    default: e(() => [
                      a(
                        b,
                        {
                          modelValue: l.lineCount,
                          "onUpdate:modelValue": [
                            t[2] || (t[2] = (o) => (l.lineCount = o)),
                            l.fetchData
                          ],
                          items: [50, 100, 200, 500],
                          label: "Lines",
                          variant: "solo-filled",
                          flat: "",
                          density: "compact",
                          "hide-details": "",
                          "bg-color": "rgba(255, 255, 255, 0.05)"
                        },
                        null,
                        8,
                        ["modelValue", "onUpdate:modelValue"]
                      )
                    ]),
                    _: 1
                  }
                ),
                a(
                  r,
                  { cols: "12", md: "3", class: "d-flex align-center ga-2" },
                  {
                    default: e(() => [
                      a(
                        U,
                        { icon: "", variant: "text", onClick: l.fetchData, loading: l.isLoading },
                        { default: e(() => [a(B, { icon: "fas fa-sync-alt" })]), _: 1 },
                        8,
                        ["onClick", "loading"]
                      ),
                      l.logData?.size_human
                        ? (i(),
                          u(
                            L,
                            { key: 0, color: "primary", size: "small" },
                            { default: e(() => [d(c(l.logData.size_human), 1)]), _: 1 }
                          ))
                        : h("", !0)
                    ]),
                    _: 1
                  }
                )
              ]),
              _: 1
            }
          ),
          l.logData?.exists
            ? (i(),
              u(
                V,
                { key: 1 },
                {
                  default: e(() => [
                    a(
                      r,
                      { cols: "12" },
                      {
                        default: e(() => [
                          a(
                            z,
                            { class: "glass-card", elevation: "0" },
                            {
                              default: e(() => [
                                a(
                                  E,
                                  { class: "pa-0" },
                                  {
                                    default: e(() => [
                                      a(
                                        I,
                                        {
                                          items: l.logData?.entries || [],
                                          height: "600",
                                          "item-height": "48"
                                        },
                                        {
                                          default: e(({ item: o }) => [
                                            a(
                                              N,
                                              {
                                                class: S("log-entry log-" + o.level),
                                                density: "compact"
                                              },
                                              {
                                                prepend: e(() => [
                                                  a(
                                                    L,
                                                    {
                                                      color: l.getLevelColor(o.level),
                                                      size: "x-small",
                                                      class: "mr-2"
                                                    },
                                                    { default: e(() => [d(c(o.level), 1)]), _: 2 },
                                                    1032,
                                                    ["color"]
                                                  ),
                                                  o.time
                                                    ? (i(), A("span", R, c(o.time), 1))
                                                    : h("", !0)
                                                ]),
                                                default: e(() => [
                                                  a(
                                                    $,
                                                    {
                                                      class:
                                                        "log-text text-body-2 font-weight-regular"
                                                    },
                                                    { default: e(() => [d(c(o.raw), 1)]), _: 2 },
                                                    1024
                                                  )
                                                ]),
                                                _: 2
                                              },
                                              1032,
                                              ["class"]
                                            )
                                          ]),
                                          _: 1
                                        },
                                        8,
                                        ["items"]
                                      )
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
                  ]),
                  _: 1
                }
              ))
            : (i(),
              u(
                V,
                { key: 0, justify: "center" },
                {
                  default: e(() => [
                    a(
                      r,
                      { cols: "12", md: "6" },
                      {
                        default: e(() => [
                          a(
                            Q,
                            { type: "info", variant: "tonal" },
                            {
                              default: e(() => [
                                a(W, null, {
                                  default: e(() => [
                                    ...(t[3] || (t[3] = [d("No debug.log file found", -1)]))
                                  ]),
                                  _: 1
                                }),
                                t[4] ||
                                  (t[4] = d(
                                    " Enable WP_DEBUG_LOG in wp-config.php to capture logs. ",
                                    -1
                                  ))
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
              ))
        ]),
        _: 1
      }
    )
  );
}
const Z = T(O, [
  ["render", j],
  ["__scopeId", "data-v-905de129"]
]);
export { Z as default };
