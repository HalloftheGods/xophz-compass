const __vite__mapDeps = (
  i,
  m = __vite__mapDeps,
  d = m.f ||
    (m.f = [
      "js/spark-library.js",
      "js/index.js",
      "css/index.css",
      "js/window.store.js",
      "js/VAlert.js",
      "css/VAlert.css",
      "js/u-spark-view.js",
      "css/u-spark-view.css",
      "css/spark-library.css",
      "js/settings-app.js",
      "js/renderer.factory.js",
      "js/VRow.js",
      "js/VCol.js",
      "js/useAudio.js",
      "css/settings-app.css",
      "js/u-generic-spark.js",
      "js/useQuery.js",
      "js/useSnackbar.js",
      "js/VTooltip.js",
      "css/VTooltip.css",
      "js/useWpTheme.js",
      "js/x-theme-snackbar.js",
      "css/x-theme-snackbar.css",
      "js/useSubAppNavigation.js",
      "js/VAppBarNavIcon.js",
      "js/VAppBarTitle.js",
      "js/VLayout.js",
      "css/VLayout.css",
      "js/debug-console-app.js",
      "css/debug-console-app.css",
      "js/helios.js",
      "js/useAuth.js",
      "css/helios.css",
      "css/u-glass-card.css",
      "js/nexus.js",
      "css/nexus.css",
      "js/noosphere.js",
      "css/noosphere.css"
    ])
) => i.map((i) => d[i]);
import {
  d as le,
  m as Ut,
  u as gt,
  a as ie,
  c as j,
  w as v,
  b as $,
  e as m,
  t as Ue,
  V as Cr,
  f as ut,
  g as We,
  r as D,
  o as L,
  h as Wt,
  i as z,
  j as ci,
  k as Ds,
  X as Or,
  n as Be,
  l as ve,
  p as me,
  q as Te,
  F as et,
  s as yt,
  v as _t,
  x as jn,
  y as qn,
  z as Gn,
  A as Jn,
  B as Ar,
  C as Mr,
  D as Rr,
  E as ui,
  G as wr,
  H as Lr,
  I as Dr,
  J as Pr,
  K as xr,
  L as kr,
  M as Vr,
  N as $r,
  O as Br,
  P as Hr,
  Q as zr,
  R as Lt,
  S as Xr,
  T as Ps,
  U as xs,
  W as en,
  Y as Me,
  Z as kt,
  $ as fi,
  a0 as Fr,
  a1 as ln,
  a2 as Ur,
  a3 as Hn,
  a4 as Re,
  a5 as Wr,
  a6 as di,
  a7 as jr,
  a8 as pi,
  a9 as qr,
  aa as Gr,
  ab as Jr,
  ac as it,
  ad as rt,
  _ as at,
  ae as Yr,
  af as Vt,
  ag as He,
  ah as ne,
  ai as Kt,
  aj as Yn,
  ak as Kr,
  al as hi,
  am as Qe,
  an as Qr,
  ao as mi,
  ap as Zr,
  aq as Ye,
  ar as gi,
  as as ks,
  at as ea,
  au as ta,
  av as na,
  aw as sa,
  ax as ia,
  ay as gn,
  az as ra,
  aA as Kn,
  aB as aa,
  aC as oa,
  aD as la,
  aE as ca,
  aF as _i,
  aG as ua,
  aH as vi,
  aI as Vs,
  aJ as fa,
  aK as da,
  aL as pa
} from "./index.js";
import { V as _n } from "./VTooltip.js";
import { u as ha } from "./useWpTheme.js";
import { X as ma } from "./x-theme-snackbar.js";
import { u as Ei } from "./useSubAppNavigation.js";
import { V as bt } from "./VRow.js";
import { V as ze } from "./VCol.js";
import { V as ga } from "./VAlert.js";
import { u as Si } from "./window.store.js";
import { V as kn } from "./VAppBarNavIcon.js";
import { V as _a } from "./VAppBarTitle.js";
import { V as yi } from "./VLayout.js";
const va = le({
    name: "WpMenuToggle",
    computed: {
      ...Ut(gt),
      isWpMenuOpen: {
        get() {
          return this.compassStore.isWpMenuOpen;
        },
        set(e) {
          this.compassStore.setWpMenu(e);
        }
      },
      wpSwitchLabel() {
        return this.isWpMenuOpen ? "On" : "Off";
      }
    },
    methods: {
      toggleWpMenu() {
        this.isWpMenuOpen = !this.isWpMenuOpen;
      }
    }
  }),
  Ea = { class: "d-none d-sm-inline" };
function Sa(e, t, n, s, i, a) {
  const r = D("x-btn");
  return (
    L(),
    j(
      _n,
      { location: "bottom" },
      {
        activator: v(({ props: l }) => [
          m(
            r,
            We(l, { size: "small", variant: "tonal", onClick: e.toggleWpMenu }),
            {
              prepend: v(() => [
                m(
                  Cr,
                  { mode: "out-in" },
                  {
                    default: v(() => [
                      (L(),
                      j(
                        ut,
                        {
                          key: String(e.isWpMenuOpen),
                          icon: e.isWpMenuOpen ? "fad fa-toggle-on" : "fad fa-toggle-off"
                        },
                        null,
                        8,
                        ["icon"]
                      ))
                    ]),
                    _: 1
                  }
                )
              ]),
              default: v(() => [$("span", Ea, "WP Menu " + Ue(e.wpSwitchLabel), 1)]),
              _: 1
            },
            16,
            ["onClick"]
          )
        ]),
        default: v(() => [t[0] || (t[0] = $("span", null, "Toggle Wordpress Menu", -1))]),
        _: 1
      }
    )
  );
}
const ya = ie(va, [["render", Sa]]),
  ba = le({
    name: "BlogInfoBtn",
    computed: {
      ...Ut(gt),
      blogInfo() {
        return this.compassStore.blogInfo;
      }
    },
    methods: {
      goHome() {
        window.location.href = "/";
      }
    }
  }),
  Ta = { class: "d-none d-sm-inline font-weight-bold" };
function Na(e, t, n, s, i, a) {
  const r = D("x-btn");
  return (
    L(),
    j(
      _n,
      { location: "bottom" },
      {
        activator: v(({ props: l }) => [
          e.blogInfo
            ? (L(),
              j(
                r,
                We({ key: 0, size: "small", variant: "tonal" }, l, {
                  "prepend-icon": "fa fa-home",
                  onClick: e.goHome
                }),
                { default: v(() => [$("span", Ta, Ue(e.blogInfo.name), 1)]), _: 1 },
                16,
                ["onClick"]
              ))
            : Wt("", !0)
        ]),
        default: v(() => [t[0] || (t[0] = $("span", null, "Go to Home Page", -1))]),
        _: 1
      }
    )
  );
}
const Ia = ie(ba, [["render", Na]]);
function Ca() {
  return {
    primary: z(
      () =>
        getComputedStyle(document.documentElement).getPropertyValue("--wp-theme-color").trim() ||
        "#a3b745"
    ),
    active: z(
      () =>
        getComputedStyle(document.documentElement).getPropertyValue("--wp-theme-active").trim() ||
        "#a3b745"
    ),
    secondary: z(
      () =>
        getComputedStyle(document.documentElement)
          .getPropertyValue("--wp-theme-secondary")
          .trim() || "#d46f15"
    ),
    focus: z(
      () =>
        getComputedStyle(document.documentElement).getPropertyValue("--wp-theme-focus").trim() ||
        "#523f6d"
    ),
    base: z(
      () =>
        getComputedStyle(document.documentElement).getPropertyValue("--wp-theme-base").trim() ||
        "#413256"
    )
  };
}
const Oa = { class: "text-overline mb-2 px-2 d-flex align-center" },
  Aa = { class: "scheme-grid" },
  Ma = ["onMouseenter", "onClick"],
  Ra = { class: "mini-colors" },
  wa = { class: "mini-name text-caption text-truncate" },
  La = le({
    __name: "theme-menu-btn",
    setup(e) {
      const {
          orderedSchemes: t,
          currentScheme: n,
          activeColors: s,
          previewScheme: i,
          resetPreview: a,
          saveScheme: r
        } = ha(),
        l = Ca(),
        o = Ds(!1),
        c = Ds({ show: !1, text: "", iconColors: [] }),
        u = async (f) => {
          const h = await r(f);
          h.success
            ? ((c.value = {
                show: !0,
                text: `Color scheme changed to ${h.name}`,
                iconColors: h.colors
              }),
              setTimeout(() => {
                o.value = !1;
              }, 1e3))
            : (c.value = { show: !0, text: `Error: ${h.error}`, iconColors: [] });
        };
      return (f, h) => {
        const g = D("x-btn");
        return (
          L(),
          j(
            ci,
            {
              modelValue: o.value,
              "onUpdate:modelValue": h[2] || (h[2] = (d) => (o.value = d)),
              "close-on-content-click": !1,
              location: "bottom end",
              offset: "10",
              transition: "scale-transition"
            },
            {
              activator: v(({ props: d }) => [
                m(
                  _n,
                  { location: "bottom" },
                  {
                    activator: v(({ props: p }) => [
                      m(
                        g,
                        We(
                          { ...d, ...p },
                          {
                            size: "small",
                            variant: "tonal",
                            class: "theme-btn",
                            style: { "--wp-theme-color": ve(l).primary.value }
                          }
                        ),
                        {
                          prepend: v(() => [
                            $(
                              "i",
                              {
                                class: "fad fa-paint-brush",
                                style: Be({
                                  "--fa-primary-color": ve(s)[2] || "#fff",
                                  "--fa-secondary-color": ve(s)[0] || "#fff",
                                  "--fa-secondary-opacity": 1
                                })
                              },
                              null,
                              4
                            )
                          ]),
                          default: v(() => [
                            h[3] || (h[3] = $("span", { class: "d-none d-md-inline" }, "Theme", -1))
                          ]),
                          _: 1
                        },
                        16,
                        ["style"]
                      )
                    ]),
                    default: v(() => [h[4] || (h[4] = $("span", null, "Change Color Scheme", -1))]),
                    _: 2
                  },
                  1024
                )
              ]),
              default: v(() => [
                m(
                  Or,
                  { class: "theme-menu-card pa-2 rough-glass", "min-width": "350" },
                  {
                    default: v(() => [
                      $("div", Oa, [
                        $(
                          "i",
                          {
                            class: "fad fa-paint-brush float-right mr-2",
                            style: Be({
                              "--fa-primary-color": ve(s)[2] || "#fff",
                              "--fa-secondary-color": ve(s)[0] || "#fff",
                              "--fa-secondary-opacity": 1
                            })
                          },
                          null,
                          4
                        ),
                        h[5] || (h[5] = me(" Color Palettes ", -1))
                      ]),
                      $("div", Aa, [
                        (L(!0),
                        Te(
                          et,
                          null,
                          yt(
                            ve(t),
                            (d) => (
                              L(),
                              Te(
                                "button",
                                {
                                  key: d.key,
                                  class: _t(["mini-scheme-card", { active: ve(n) === d.key }]),
                                  onMouseenter: (p) => ve(i)(d.key),
                                  onMouseleave: h[0] || (h[0] = (...p) => ve(a) && ve(a)(...p)),
                                  onClick: (p) => u(d.key)
                                },
                                [
                                  $("div", Ra, [
                                    (L(!0),
                                    Te(
                                      et,
                                      null,
                                      yt(
                                        d.colors,
                                        (p, E) => (
                                          L(),
                                          Te(
                                            "div",
                                            {
                                              key: E,
                                              class: "mini-color",
                                              style: Be({ backgroundColor: p })
                                            },
                                            null,
                                            4
                                          )
                                        )
                                      ),
                                      128
                                    ))
                                  ]),
                                  $("div", wa, Ue(d.name), 1)
                                ],
                                42,
                                Ma
                              )
                            )
                          ),
                          128
                        ))
                      ]),
                      m(
                        ma,
                        {
                          modelValue: c.value.show,
                          "onUpdate:modelValue": h[1] || (h[1] = (d) => (c.value.show = d)),
                          text: c.value.text,
                          colors: c.value.iconColors || [],
                          absolute: ""
                        },
                        null,
                        8,
                        ["modelValue", "text", "colors"]
                      )
                    ]),
                    _: 1
                  }
                )
              ]),
              _: 1
            },
            8,
            ["modelValue"]
          )
        );
      };
    }
  }),
  Da = ie(La, [["__scopeId", "data-v-5385e7e9"]]),
  Pa = le({
    name: "CompassToggle",
    computed: {
      ...Ut(gt),
      bottomSheet: {
        get() {
          return this.compassStore.bottomSheet;
        },
        set(e) {
          this.compassStore.setBottomSheet(e);
        }
      }
    },
    methods: {
      showBottomSheet() {
        this.bottomSheet = !this.bottomSheet;
      }
    }
  });
function xa(e, t, n, s, i, a) {
  const r = D("x-btn");
  return (
    L(),
    j(
      _n,
      { location: "bottom" },
      {
        activator: v(({ props: l }) => [
          m(
            r,
            We(l, {
              size: "small",
              variant: "tonal",
              "prepend-icon": "fad fa-compass",
              onClick: e.showBottomSheet
            }),
            { default: v(() => [...(t[0] || (t[0] = [me(" COMPASS ", -1)]))]), _: 1 },
            16,
            ["onClick"]
          )
        ]),
        default: v(() => [t[1] || (t[1] = $("span", null, "Navigate COMPASS", -1))]),
        _: 1
      }
    )
  );
}
const ka = ie(Pa, [["render", xa]]),
  Va = le({
    name: "XBreadcrumbTrailMolecule",
    setup() {
      const { breadtrail: e } = Ei();
      return { breadtrail: e };
    }
  }),
  $a = Gn({ divider: [Number, String], ...Jn() }, "VBreadcrumbsDivider"),
  Ba = jn()({
    name: "VBreadcrumbsDivider",
    props: $a(),
    setup(e, t) {
      let { slots: n } = t;
      return (
        qn(() =>
          $(
            "li",
            {
              "aria-hidden": "true",
              class: _t(["v-breadcrumbs-divider", e.class]),
              style: Be(e.style)
            },
            [n?.default?.() ?? e.divider]
          )
        ),
        {}
      );
    }
  }),
  Ha = Gn(
    {
      active: Boolean,
      activeClass: String,
      activeColor: String,
      color: String,
      disabled: Boolean,
      title: String,
      ...Jn(),
      ...Lr(Dr(), ["width", "maxWidth"]),
      ...wr(),
      ...ui({ tag: "li" })
    },
    "VBreadcrumbsItem"
  ),
  za = jn()({
    name: "VBreadcrumbsItem",
    props: Ha(),
    setup(e, t) {
      let { slots: n, attrs: s } = t;
      const i = Ar(e, s),
        a = z(() => e.active || i.isActive?.value),
        { dimensionStyles: r } = Rr(e),
        { textColorClasses: l, textColorStyles: o } = Mr(() => (a.value ? e.activeColor : e.color));
      return (
        qn(() =>
          m(
            e.tag,
            {
              class: _t([
                "v-breadcrumbs-item",
                {
                  "v-breadcrumbs-item--active": a.value,
                  "v-breadcrumbs-item--disabled": e.disabled,
                  [`${e.activeClass}`]: a.value && e.activeClass
                },
                l.value,
                e.class
              ]),
              style: Be([o.value, r.value, e.style]),
              "aria-current": a.value ? "page" : void 0
            },
            {
              default: () => [
                i.isLink.value
                  ? $(
                      "a",
                      We({ class: "v-breadcrumbs-item--link", onClick: i.navigate }, i.linkProps),
                      [n.default?.() ?? e.title]
                    )
                  : (n.default?.() ?? e.title)
              ]
            }
          )
        ),
        {}
      );
    }
  }),
  Xa = Gn(
    {
      activeClass: String,
      activeColor: String,
      bgColor: String,
      color: String,
      disabled: Boolean,
      divider: { type: String, default: "/" },
      icon: Hr,
      items: { type: Array, default: () => [] },
      ...Jn(),
      ...Br(),
      ...$r(),
      ...ui({ tag: "ul" })
    },
    "VBreadcrumbs"
  ),
  Fa = jn()({
    name: "VBreadcrumbs",
    props: Xa(),
    setup(e, t) {
      let { slots: n } = t;
      const { backgroundColorClasses: s, backgroundColorStyles: i } = Pr(() => e.bgColor),
        { densityClasses: a } = xr(e),
        { roundedClasses: r } = kr(e);
      Vr({
        VBreadcrumbsDivider: { divider: Lt(() => e.divider) },
        VBreadcrumbsItem: {
          activeClass: Lt(() => e.activeClass),
          activeColor: Lt(() => e.activeColor),
          color: Lt(() => e.color),
          disabled: Lt(() => e.disabled)
        }
      });
      const l = z(() =>
        e.items.map((o) =>
          typeof o == "string" ? { item: { title: o }, raw: o } : { item: o, raw: o }
        )
      );
      return (
        qn(() => {
          const o = !!(n.prepend || e.icon);
          return m(
            e.tag,
            {
              class: _t(["v-breadcrumbs", s.value, a.value, r.value, e.class]),
              style: Be([i.value, e.style])
            },
            {
              default: () => [
                o &&
                  $("li", { key: "prepend", class: "v-breadcrumbs__prepend" }, [
                    n.prepend
                      ? m(
                          zr,
                          {
                            key: "prepend-defaults",
                            disabled: !e.icon,
                            defaults: { VIcon: { icon: e.icon, start: !0 } }
                          },
                          n.prepend
                        )
                      : m(ut, { key: "prepend-icon", start: !0, icon: e.icon }, null)
                  ]),
                l.value.map((c, u, f) => {
                  let { item: h, raw: g } = c;
                  return $(et, null, [
                    n.item?.({ item: h, index: u }) ??
                      m(
                        za,
                        We(
                          { key: u, disabled: u >= f.length - 1 },
                          typeof h == "string" ? { title: h } : h
                        ),
                        { default: n.title ? () => n.title?.({ item: h, index: u }) : void 0 }
                      ),
                    u < f.length - 1 &&
                      m(Ba, null, {
                        default: n.divider ? () => n.divider?.({ item: g, index: u }) : void 0
                      })
                  ]);
                }),
                n.default?.()
              ]
            }
          );
        }),
        {}
      );
    }
  });
function Ua(e, t, n, s, i, a) {
  return (
    L(),
    j(
      Fa,
      {
        items: e.breadtrail,
        divider: "/",
        class: "pa-0 d-none d-md-flex text-caption text-medium-emphasis align-center h-100"
      },
      null,
      8,
      ["items"]
    )
  );
}
const Wa = ie(Va, [
    ["render", Ua],
    ["__scopeId", "data-v-5dbcc968"]
  ]),
  ja = le({
    name: "XUserAvatarBtnMolecule",
    computed: { ...Ga(), ...Ut(gt) },
    methods: Ja(),
    data: qa
  });
function qa() {
  return { logoutDialog: !1 };
}
function Ga() {
  return {
    wpSwitchLabel() {
      return "";
    },
    editProfileUrl() {
      return "#/profile";
    },
    isWpMenuOpen: {
      get() {
        return this.compassStore.isWpMenuOpen;
      },
      set(e) {
        this.compassStore.setWpMenu(e);
      }
    },
    currentUser() {
      return this.compassStore.currentUser;
    },
    blogInfo() {
      return this.compassStore.blogInfo;
    },
    user() {
      return this.compassStore.currentUser;
    },
    userMask: {
      get() {
        return this.compassStore.userMask;
      },
      set(e) {
        this.compassStore.setUserMask(e);
      }
    }
  };
}
function Ja() {
  return {
    wpmenu() {
      this.isWpMenuOpen = !this.isWpMenuOpen;
    },
    toggleMask() {
      this.userMask = !this.userMask;
    },
    confirmLogout() {
      this.logoutDialog = !0;
    },
    logout() {
      window.location.href = this.blogInfo.logouturl;
    }
  };
}
const Ya = { class: "font-weight-bold mb-1 text-subtitle-2" },
  Ka = { class: "d-flex flex-column text-caption" },
  Qa = ["href"];
function Za(e, t, n, s, i, a) {
  return e.user
    ? (L(),
      Te(
        "div",
        { key: 0, class: _t(e.$options.name) },
        [
          m(
            ci,
            { "open-on-hover": "", location: "bottom end" },
            {
              activator: v(({ props: r }) => [
                m(
                  kt,
                  We(
                    {
                      id: "user-avatar-btn",
                      size: "small",
                      class: "header-btn user-avatar-btn mx-1",
                      variant: "tonal"
                    },
                    r
                  ),
                  {
                    default: v(() => [
                      me(" Howdy, " + Ue(e.user.data.display_name) + " ", 1),
                      m(
                        xs,
                        { size: "24px", class: "ms-2" },
                        {
                          default: v(() => [m(en, { src: e.user.avatar }, null, 8, ["src"])]),
                          _: 1
                        }
                      )
                    ]),
                    _: 1
                  },
                  16
                )
              ]),
              default: v(() => [
                m(
                  Ps,
                  { "min-width": "250", class: "pa-3", color: "grey-darken-3" },
                  {
                    default: v(() => [
                      m(
                        bt,
                        { "no-gutters": "", align: "center" },
                        {
                          default: v(() => [
                            m(
                              ze,
                              { cols: "auto", class: "mr-3" },
                              {
                                default: v(() => [
                                  m(
                                    xs,
                                    { size: "64", rounded: "0" },
                                    {
                                      default: v(() => [
                                        m(en, { src: e.user.avatar }, null, 8, ["src"])
                                      ]),
                                      _: 1
                                    }
                                  )
                                ]),
                                _: 1
                              }
                            ),
                            m(ze, null, {
                              default: v(() => [
                                $("div", Ya, Ue(e.user.data.user_login), 1),
                                $("div", Ka, [
                                  $(
                                    "a",
                                    {
                                      href: e.editProfileUrl,
                                      class: "text-decoration-none text-primary mb-1"
                                    },
                                    "Edit Profile",
                                    8,
                                    Qa
                                  ),
                                  $(
                                    "a",
                                    {
                                      href: "#",
                                      onClick:
                                        t[0] ||
                                        (t[0] = Me(
                                          (...r) => e.confirmLogout && e.confirmLogout(...r),
                                          ["prevent"]
                                        )),
                                      class: "text-decoration-none text-red-accent-2"
                                    },
                                    "Log Out"
                                  )
                                ])
                              ]),
                              _: 1
                            })
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
          ),
          m(
            Xr,
            {
              modelValue: e.logoutDialog,
              "onUpdate:modelValue": t[2] || (t[2] = (r) => (e.logoutDialog = r)),
              width: "500"
            },
            {
              default: v(() => [
                m(Ps, null, {
                  default: v(() => [
                    m(fi, { class: "headline grey-darken-4", title: "Locking Up?" }),
                    m(
                      bt,
                      { justify: "center", align: "center", class: "ma-5" },
                      {
                        default: v(() => [
                          m(
                            ze,
                            { cols: "7" },
                            {
                              default: v(() => [m(en, { src: "@/assets/images/logout.svg" })]),
                              _: 1
                            }
                          )
                        ]),
                        _: 1
                      }
                    ),
                    m(Fr, null, {
                      default: v(() => [
                        m(
                          kt,
                          {
                            color: "green",
                            variant: "text",
                            onClick: t[1] || (t[1] = (r) => (e.logoutDialog = !1))
                          },
                          {
                            default: v(() => [
                              m(ut, { start: "", icon: "fad fa-unlock" }),
                              t[3] || (t[3] = me(" Stay Logged In ", -1))
                            ]),
                            _: 1
                          }
                        ),
                        m(ln),
                        m(
                          kt,
                          { color: "primary", variant: "elevated", onClick: e.logout },
                          {
                            default: v(() => [
                              t[4] || (t[4] = me(" Log Off ", -1)),
                              m(ut, { end: "", icon: "fad fa-lock" })
                            ]),
                            _: 1
                          },
                          8,
                          ["onClick"]
                        )
                      ]),
                      _: 1
                    })
                  ]),
                  _: 1
                })
              ]),
              _: 1
            },
            8,
            ["modelValue"]
          )
        ],
        2
      ))
    : Wt("", !0);
}
const bi = ie(ja, [["render", Za]]),
  eo = le({
    name: "SystemBar",
    components: {
      WpMenuToggle: ya,
      BlogInfoBtn: Ia,
      ThemeMenuBtn: Da,
      CompassToggle: ka,
      XBreadcrumbTrail: Wa,
      UserAvatarBtn: bi
    }
  });
function to(e, t, n, s, i, a) {
  const r = D("wp-menu-toggle"),
    l = D("blog-info-btn"),
    o = D("compass-toggle"),
    c = D("x-breadcrumb-trail"),
    u = D("theme-menu-btn"),
    f = D("user-avatar-btn"),
    h = D("x-system-bar");
  return (
    L(),
    j(
      h,
      { height: "40", class: "system-bar" },
      {
        default: v(() => [
          m(
            Ur,
            { fluid: "", class: "pa-0" },
            {
              default: v(() => [
                m(
                  bt,
                  { justify: "space-between", class: "ma-0" },
                  {
                    default: v(() => [
                      m(
                        ze,
                        { cols: "auto", class: "d-flex align-center ga-2" },
                        { default: v(() => [m(r), m(l), m(o)]), _: 1 }
                      ),
                      m(
                        ze,
                        { class: "d-flex align-center justify-center" },
                        { default: v(() => [m(c)]), _: 1 }
                      ),
                      m(
                        ze,
                        { cols: "auto", class: "d-flex align-center ga-2" },
                        {
                          default: v(() => [
                            m(kt, {
                              icon: "fab fa-patreon",
                              variant: "text",
                              size: "small",
                              color: "primary",
                              class: "mr-2",
                              title: "Become a Backer",
                              href: "https://www.patreon.com/your-patreon-link",
                              target: "_blank"
                            }),
                            m(u),
                            m(f)
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
    )
  );
}
const no = ie(eo, [
    ["render", to],
    ["__scopeId", "data-v-d5c63636"]
  ]),
  so = {},
  io = { class: "trinity-rings-spinner" };
function ro(e, t) {
  return (
    L(),
    Te("div", io, [
      ...(t[0] ||
        (t[0] = [
          $("div", { class: "circle" }, null, -1),
          $("div", { class: "circle" }, null, -1),
          $("div", { class: "circle" }, null, -1)
        ]))
    ])
  );
}
const ao = ie(so, [
    ["render", ro],
    ["__scopeId", "data-v-ad2d5cce"]
  ]),
  oo = le({
    name: "XAppBarMolecule",
    props: { title: { type: String, default: "" }, pluginIcon: { type: String, default: "" } },
    emits: ["toggle-nav"],
    setup(e, { emit: t }) {
      return {
        toggleNav: () => {
          t("toggle-nav");
        }
      };
    }
  });
function lo(e, t, n, s, i, a) {
  const r = D("x-icon"),
    l = D("x-btn"),
    o = D("x-avatar"),
    c = D("x-app-bar");
  return (
    L(),
    j(
      c,
      { density: "compact", flat: "", class: "x-app-bar-molecule" },
      {
        default: v(() => [
          m(
            l,
            { icon: "", variant: "text", class: "ml-2", onClick: e.toggleNav },
            {
              default: v(() => [
                m(
                  r,
                  { color: "primary" },
                  { default: v(() => [...(t[0] || (t[0] = [me("fad fa-bars", -1)]))]), _: 1 }
                )
              ]),
              _: 1
            },
            8,
            ["onClick"]
          ),
          e.pluginIcon
            ? (L(),
              j(
                o,
                { key: 0, size: "28", class: "mx-3 rounded-lg border border-white/10" },
                {
                  default: v(() => [m(en, { src: e.pluginIcon, cover: "" }, null, 8, ["src"])]),
                  _: 1
                }
              ))
            : Wt("", !0),
          m(
            Hn,
            { class: "text-sm font-black tracking-widest text-white/90 uppercase" },
            { default: v(() => [me(Ue(e.title), 1)]), _: 1 }
          ),
          m(ln),
          Re(e.$slots, "actions")
        ]),
        _: 3
      }
    )
  );
}
const co = ie(oo, [["render", lo]]),
  uo = le({ name: "CompassFooter" });
function fo(e, t, n, s, i, a) {
  const r = D("x-icon"),
    l = D("x-btn"),
    o = D("x-footer");
  return (
    L(),
    j(
      o,
      { app: "", class: "pa-2 text-body-2 copyright", theme: "dark", absolute: "" },
      {
        default: v(() => [
          m(
            bt,
            { justify: "center", align: "center", class: "ma-0" },
            {
              default: v(() => [
                m(
                  ze,
                  { class: "text-center" },
                  {
                    default: v(() => [
                      m(Wr),
                      t[1] || (t[1] = me("   ", -1)),
                      m(
                        l,
                        { variant: "outlined", color: "#E151AA", size: "small" },
                        {
                          default: v(() => [
                            t[0] || (t[0] = me(" Support COMPASS  ", -1)),
                            m(r, { size: "small", icon: "fa fa-heart" })
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
    )
  );
}
const po = ie(uo, [["render", fo]]),
  ho = le({ name: "PluginSkeleton" }),
  mo = { class: "plugin-skeleton d-flex align-center pa-2" },
  go = { class: "flex-grow-1" };
function _o(e, t, n, s, i, a) {
  const r = D("x-skeleton-loader");
  return (
    L(),
    Te("div", mo, [
      m(r, { shape: "circle", width: "64", height: "64", class: "mr-4 d-none d-xl-flex" }),
      m(r, { shape: "circle", width: "48", height: "48", class: "mr-4 d-flex d-xl-none" }),
      $("div", go, [
        m(r, { width: "80%", height: "20px", class: "mb-2" }),
        m(r, { width: "40%", height: "12px" })
      ])
    ])
  );
}
const vo = ie(ho, [
    ["render", _o],
    ["__scopeId", "data-v-7d7eeaeb"]
  ]),
  Eo = le({
    name: "XCompassPluginSheet",
    components: { PluginSkeleton: vo },
    computed: {
      ...Ut(gt),
      bottomSheet: {
        get() {
          return this.compassStore.bottomSheet;
        },
        set(e) {
          this.compassStore.setBottomSheet(e);
        }
      },
      loading() {
        return this.compassStore.loading || this.plugins.length === 0;
      },
      plugins() {
        const e = this.compassStore.pluginList || [];
        return di.filter(e, (t) => t.isActivated);
      },
      routes() {
        return this.$router.getRoutes();
      }
    },
    methods: {
      navigateToPlugin(e) {
        this.bottomSheet = !1;
        const t = this.getTextDomainPath(e.TextDomain);
        this.$router.push({ path: t });
      },
      getTextDomainPath(e) {
        if (!e) return "/";
        const t = `/${e.replace("xophz-compass-", "")}`,
          s = this.routes.find((i) => i.path === t);
        return s ? s.path : "/";
      }
    }
  }),
  So = { class: "d-none d-xl-flex mr-4", style: { width: "64px", height: "64px" } },
  yo = { class: "d-flex d-xl-none mr-2", style: { width: "48px", height: "48px" } };
function bo(e, t, n, s, i, a) {
  const r = D("x-icon"),
    l = D("x-chip"),
    o = D("x-avatar"),
    c = D("x-btn"),
    u = D("plugin-skeleton"),
    f = D("x-plugin-image"),
    h = D("x-list-item"),
    g = D("x-list"),
    d = D("x-bottom-sheet");
  return (
    L(),
    j(
      d,
      {
        modelValue: e.bottomSheet,
        "onUpdate:modelValue": t[1] || (t[1] = (p) => (e.bottomSheet = p)),
        scrim: !1
      },
      {
        default: v(() => [
          m(
            fi,
            { theme: "dark" },
            {
              default: v(() => [
                m(r, { start: "", class: "ml-4", icon: "fad fa-compass" }),
                m(
                  l,
                  { class: "ml-4", color: "green" },
                  {
                    default: v(() => [
                      t[2] || (t[2] = $("span", null, "Premium", -1)),
                      m(r, { end: "", icon: "fa fa-star" })
                    ]),
                    _: 1
                  }
                ),
                m(ln),
                m(
                  Hn,
                  { class: "headline d-flex d-sm-none" },
                  {
                    default: v(() => [
                      t[3] ||
                        (t[3] = me(
                          " Cybernetic Ops Master Platform for Autonomous Site Synchronization ",
                          -1
                        )),
                      m(o, { size: "32", rounded: "0" }),
                      m(
                        l,
                        { color: "green" },
                        { default: v(() => [m(r, { icon: "fa fa-star" })]), _: 1 }
                      )
                    ]),
                    _: 1
                  }
                ),
                m(
                  Hn,
                  { class: "d-none d-sm-flex" },
                  { default: v(() => [...(t[4] || (t[4] = [me(" COMPASS ", -1)]))]), _: 1 }
                ),
                t[5] || (t[5] = me("   ", -1)),
                m(ln),
                m(
                  c,
                  {
                    icon: "",
                    variant: "text",
                    onClick: t[0] || (t[0] = (p) => (e.bottomSheet = !1))
                  },
                  { default: v(() => [m(r, { icon: "fa fa-times" })]), _: 1 }
                )
              ]),
              _: 1
            }
          ),
          m(
            g,
            { class: "plugin-sheet-list pa-4" },
            {
              default: v(() => [
                e.loading
                  ? (L(),
                    j(
                      bt,
                      { key: 0, class: "ma-0" },
                      {
                        default: v(() => [
                          (L(),
                          Te(
                            et,
                            null,
                            yt(12, (p) =>
                              m(
                                ze,
                                { key: p, cols: "3", lg: "2" },
                                { default: v(() => [m(u)]), _: 1 }
                              )
                            ),
                            64
                          ))
                        ]),
                        _: 1
                      }
                    ))
                  : (L(),
                    j(
                      bt,
                      { key: 1, class: "ma-0" },
                      {
                        default: v(() => [
                          m(
                            jr,
                            { name: "staggered-entry" },
                            {
                              default: v(() => [
                                (L(!0),
                                Te(
                                  et,
                                  null,
                                  yt(
                                    e.plugins,
                                    (p, E) => (
                                      L(),
                                      j(
                                        ze,
                                        {
                                          key: p.TextDomain,
                                          cols: "3",
                                          lg: "2",
                                          style: Be({ "--index": E }),
                                          class: "plugin-col"
                                        },
                                        {
                                          default: v(() => [
                                            m(
                                              h,
                                              {
                                                onClick: (S) => e.navigateToPlugin(p),
                                                class: "plugin-item pa-2"
                                              },
                                              {
                                                prepend: v(() => [
                                                  $("div", So, [
                                                    m(
                                                      f,
                                                      { src: p.icon, "fallback-size": "32" },
                                                      null,
                                                      8,
                                                      ["src"]
                                                    )
                                                  ]),
                                                  $("div", yo, [
                                                    m(
                                                      f,
                                                      { src: p.icon, "fallback-size": "24" },
                                                      null,
                                                      8,
                                                      ["src"]
                                                    )
                                                  ])
                                                ]),
                                                default: v(() => [
                                                  m(
                                                    pi,
                                                    { class: "text-left font-weight-bold" },
                                                    { default: v(() => [me(Ue(p.Name), 1)]), _: 2 },
                                                    1024
                                                  )
                                                ]),
                                                _: 2
                                              },
                                              1032,
                                              ["onClick"]
                                            )
                                          ]),
                                          _: 2
                                        },
                                        1032,
                                        ["style"]
                                      )
                                    )
                                  ),
                                  128
                                ))
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
        ]),
        _: 1
      },
      8,
      ["modelValue"]
    )
  );
}
const To = ie(Eo, [
    ["render", bo],
    ["__scopeId", "data-v-1bdfa85d"]
  ]),
  No = le({
    name: "ContextNavigationDrawer",
    setup() {
      const e = gt(),
        t = z({ get: () => e.isContextDrawerOpen, set: (i) => e.toggleContextDrawer(i) }),
        n = z(() => e.contextDrawerContent);
      return {
        drawerModel: t,
        contentMode: n,
        closeDrawer: () => {
          e.toggleContextDrawer(!1);
        }
      };
    }
  }),
  Io = { class: "pa-4" },
  Co = { class: "text-caption text-medium-emphasis mb-2" };
function Oo(e, t, n, s, i, a) {
  const r = D("x-navigation-drawer");
  return (
    L(),
    j(
      r,
      {
        modelValue: e.drawerModel,
        "onUpdate:modelValue": t[0] || (t[0] = (l) => (e.drawerModel = l)),
        location: "right",
        floating: !0,
        theme: "dark",
        width: "300",
        class: "context-navigation-drawer"
      },
      {
        default: v(() => [
          m(qr, null, {
            default: v(() => [
              m(Gr, null, {
                append: v(() => [
                  m(
                    kt,
                    {
                      icon: "fal fa-times",
                      variant: "text",
                      density: "compact",
                      onClick: e.closeDrawer
                    },
                    null,
                    8,
                    ["onClick"]
                  )
                ]),
                default: v(() => [
                  m(
                    pi,
                    { class: "text-h6" },
                    { default: v(() => [...(t[1] || (t[1] = [me(" Context ", -1)]))]), _: 1 }
                  )
                ]),
                _: 1
              })
            ]),
            _: 1
          }),
          m(Jr),
          $("div", Io, [
            $("p", Co, "Active Content: " + Ue(e.contentMode), 1),
            m(ga, {
              icon: "fal fa-info-circle",
              text: "Contextual widgets will appear here based on your activity.",
              variant: "tonal",
              color: "primary",
              class: "mb-4"
            })
          ])
        ]),
        _: 1
      },
      8,
      ["modelValue"]
    )
  );
}
const Ao = ie(No, [
    ["render", Oo],
    ["__scopeId", "data-v-88ff2179"]
  ]),
  Mo = [
    {
      id: "spark-library",
      name: "You Me Sparks",
      component: it(
        rt(() =>
          at(() => import("./spark-library.js"), __vite__mapDeps([0, 1, 2, 3, 4, 5, 6, 7, 8]))
        )
      )
    },
    {
      id: "settings",
      name: "Settings",
      component: it(
        rt(() =>
          at(() => import("./settings-app.js"), __vite__mapDeps([9, 3, 1, 2, 10, 11, 12, 13, 14]))
        )
      )
    },
    {
      id: "xophz-bomb-bag",
      name: "Bomb Bag Newsblast",
      component: it({
        name: "BombBagLoader",
        setup() {
          return () => {
            const e = rt(() =>
                at(
                  () => import("./u-generic-spark.js"),
                  __vite__mapDeps([
                    15, 1, 2, 12, 11, 16, 13, 17, 18, 19, 20, 21, 22, 23, 4, 5, 3, 24, 25, 26, 27
                  ])
                )
              ),
              { sparkRegistryService: t } = require("../services/spark-registry.service"),
              n = t.getManifest("xophz-bomb-bag");
            return n ? Yr(e, { manifest: n }) : null;
          };
        }
      })
    },
    {
      id: "debug-console",
      name: "Debug Console",
      component: it(
        rt(() => at(() => import("./debug-console-app.js"), __vite__mapDeps([28, 1, 2, 29])))
      )
    },
    {
      id: "u-helios",
      name: "Helios",
      component: it(
        rt(() =>
          at(
            () => import("./helios.js"),
            __vite__mapDeps([
              30, 31, 1, 2, 6, 7, 10, 12, 11, 18, 19, 20, 21, 22, 23, 4, 5, 3, 24, 25, 26, 27, 32,
              33
            ])
          )
        )
      )
    },
    {
      id: "u-nexus",
      name: "Nexos",
      component: it(
        rt(() =>
          at(
            () => import("./nexus.js"),
            __vite__mapDeps([
              34, 31, 1, 2, 6, 7, 12, 11, 18, 19, 20, 21, 22, 23, 4, 5, 3, 24, 25, 26, 27, 35
            ])
          )
        )
      )
    },
    {
      id: "u-noosphere",
      name: "Noosphere",
      component: it(
        rt(() =>
          at(
            () => import("./noosphere.js"),
            __vite__mapDeps([
              36, 31, 1, 2, 6, 7, 12, 11, 18, 19, 20, 21, 22, 23, 4, 5, 3, 24, 25, 26, 27, 37
            ])
          )
        )
      )
    }
  ];
var Dt = {},
  ot = {},
  Vn = { exports: {} },
  $n = {};
const Tt = Symbol(""),
  St = Symbol(""),
  vn = Symbol(""),
  $t = Symbol(""),
  Qn = Symbol(""),
  tt = Symbol(""),
  Zn = Symbol(""),
  es = Symbol(""),
  En = Symbol(""),
  Sn = Symbol(""),
  Mt = Symbol(""),
  yn = Symbol(""),
  ts = Symbol(""),
  bn = Symbol(""),
  Tn = Symbol(""),
  Nn = Symbol(""),
  In = Symbol(""),
  Cn = Symbol(""),
  On = Symbol(""),
  ns = Symbol(""),
  ss = Symbol(""),
  jt = Symbol(""),
  Bt = Symbol(""),
  An = Symbol(""),
  Mn = Symbol(""),
  Nt = Symbol(""),
  Rt = Symbol(""),
  Rn = Symbol(""),
  cn = Symbol(""),
  Ti = Symbol(""),
  un = Symbol(""),
  Ht = Symbol(""),
  Ni = Symbol(""),
  Ii = Symbol(""),
  wn = Symbol(""),
  Ci = Symbol(""),
  Oi = Symbol(""),
  Ln = Symbol(""),
  is = Symbol(""),
  dt = {
    [Tt]: "Fragment",
    [St]: "Teleport",
    [vn]: "Suspense",
    [$t]: "KeepAlive",
    [Qn]: "BaseTransition",
    [tt]: "openBlock",
    [Zn]: "createBlock",
    [es]: "createElementBlock",
    [En]: "createVNode",
    [Sn]: "createElementVNode",
    [Mt]: "createCommentVNode",
    [yn]: "createTextVNode",
    [ts]: "createStaticVNode",
    [bn]: "resolveComponent",
    [Tn]: "resolveDynamicComponent",
    [Nn]: "resolveDirective",
    [In]: "resolveFilter",
    [Cn]: "withDirectives",
    [On]: "renderList",
    [ns]: "renderSlot",
    [ss]: "createSlots",
    [jt]: "toDisplayString",
    [Bt]: "mergeProps",
    [An]: "normalizeClass",
    [Mn]: "normalizeStyle",
    [Nt]: "normalizeProps",
    [Rt]: "guardReactiveProps",
    [Rn]: "toHandlers",
    [cn]: "camelize",
    [Ti]: "capitalize",
    [un]: "toHandlerKey",
    [Ht]: "setBlockTracking",
    [Ni]: "pushScopeId",
    [Ii]: "popScopeId",
    [wn]: "withCtx",
    [Ci]: "unref",
    [Oi]: "isRef",
    [Ln]: "withMemo",
    [is]: "isMemoSame"
  };
function Ai(e) {
  Object.getOwnPropertySymbols(e).forEach((t) => {
    dt[t] = e[t];
  });
}
const Ro = { HTML: 0, 0: "HTML", SVG: 1, 1: "SVG", MATH_ML: 2, 2: "MATH_ML" },
  wo = {
    ROOT: 0,
    0: "ROOT",
    ELEMENT: 1,
    1: "ELEMENT",
    TEXT: 2,
    2: "TEXT",
    COMMENT: 3,
    3: "COMMENT",
    SIMPLE_EXPRESSION: 4,
    4: "SIMPLE_EXPRESSION",
    INTERPOLATION: 5,
    5: "INTERPOLATION",
    ATTRIBUTE: 6,
    6: "ATTRIBUTE",
    DIRECTIVE: 7,
    7: "DIRECTIVE",
    COMPOUND_EXPRESSION: 8,
    8: "COMPOUND_EXPRESSION",
    IF: 9,
    9: "IF",
    IF_BRANCH: 10,
    10: "IF_BRANCH",
    FOR: 11,
    11: "FOR",
    TEXT_CALL: 12,
    12: "TEXT_CALL",
    VNODE_CALL: 13,
    13: "VNODE_CALL",
    JS_CALL_EXPRESSION: 14,
    14: "JS_CALL_EXPRESSION",
    JS_OBJECT_EXPRESSION: 15,
    15: "JS_OBJECT_EXPRESSION",
    JS_PROPERTY: 16,
    16: "JS_PROPERTY",
    JS_ARRAY_EXPRESSION: 17,
    17: "JS_ARRAY_EXPRESSION",
    JS_FUNCTION_EXPRESSION: 18,
    18: "JS_FUNCTION_EXPRESSION",
    JS_CONDITIONAL_EXPRESSION: 19,
    19: "JS_CONDITIONAL_EXPRESSION",
    JS_CACHE_EXPRESSION: 20,
    20: "JS_CACHE_EXPRESSION",
    JS_BLOCK_STATEMENT: 21,
    21: "JS_BLOCK_STATEMENT",
    JS_TEMPLATE_LITERAL: 22,
    22: "JS_TEMPLATE_LITERAL",
    JS_IF_STATEMENT: 23,
    23: "JS_IF_STATEMENT",
    JS_ASSIGNMENT_EXPRESSION: 24,
    24: "JS_ASSIGNMENT_EXPRESSION",
    JS_SEQUENCE_EXPRESSION: 25,
    25: "JS_SEQUENCE_EXPRESSION",
    JS_RETURN_STATEMENT: 26,
    26: "JS_RETURN_STATEMENT"
  },
  Lo = {
    ELEMENT: 0,
    0: "ELEMENT",
    COMPONENT: 1,
    1: "COMPONENT",
    SLOT: 2,
    2: "SLOT",
    TEMPLATE: 3,
    3: "TEMPLATE"
  },
  Do = {
    NOT_CONSTANT: 0,
    0: "NOT_CONSTANT",
    CAN_SKIP_PATCH: 1,
    1: "CAN_SKIP_PATCH",
    CAN_CACHE: 2,
    2: "CAN_CACHE",
    CAN_STRINGIFY: 3,
    3: "CAN_STRINGIFY"
  },
  se = {
    start: { line: 1, column: 1, offset: 0 },
    end: { line: 1, column: 1, offset: 0 },
    source: ""
  };
function Mi(e, t = "") {
  return {
    type: 0,
    source: t,
    children: e,
    helpers: new Set(),
    components: [],
    directives: [],
    hoists: [],
    imports: [],
    cached: [],
    temps: 0,
    codegenNode: void 0,
    loc: se
  };
}
function It(e, t, n, s, i, a, r, l = !1, o = !1, c = !1, u = se) {
  return (
    e &&
      (l ? (e.helper(tt), e.helper(mt(e.inSSR, c))) : e.helper(ht(e.inSSR, c)), r && e.helper(Cn)),
    {
      type: 13,
      tag: t,
      props: n,
      children: s,
      patchFlag: i,
      dynamicProps: a,
      directives: r,
      isBlock: l,
      disableTracking: o,
      isComponent: c,
      loc: u
    }
  );
}
function Ze(e, t = se) {
  return { type: 17, loc: t, elements: e };
}
function Ne(e, t = se) {
  return { type: 15, loc: t, properties: e };
}
function K(e, t) {
  return { type: 16, loc: se, key: ne(e) ? O(e, !0) : e, value: t };
}
function O(e, t = !1, n = se, s = 0) {
  return { type: 4, loc: n, content: e, isStatic: t, constType: t ? 3 : s };
}
function Po(e, t) {
  return { type: 5, loc: t, content: ne(e) ? O(e, !1, t) : e };
}
function we(e, t = se) {
  return { type: 8, loc: t, children: e };
}
function Q(e, t = [], n = se) {
  return { type: 14, loc: n, callee: e, arguments: t };
}
function pt(e, t = void 0, n = !1, s = !1, i = se) {
  return { type: 18, params: e, returns: t, newline: n, isSlot: s, loc: i };
}
function fn(e, t, n, s = !0) {
  return { type: 19, test: e, consequent: t, alternate: n, newline: s, loc: se };
}
function Ri(e, t, n = !1, s = !1) {
  return {
    type: 20,
    index: e,
    value: t,
    needPauseTracking: n,
    inVOnce: s,
    needArraySpread: !1,
    loc: se
  };
}
function wi(e) {
  return { type: 21, body: e, loc: se };
}
function xo(e) {
  return { type: 22, elements: e, loc: se };
}
function ko(e, t, n) {
  return { type: 23, test: e, consequent: t, alternate: n, loc: se };
}
function Vo(e, t) {
  return { type: 24, left: e, right: t, loc: se };
}
function $o(e) {
  return { type: 25, expressions: e, loc: se };
}
function Bo(e) {
  return { type: 26, returns: e, loc: se };
}
function ht(e, t) {
  return e || t ? En : Sn;
}
function mt(e, t) {
  return e || t ? Zn : es;
}
function Dn(e, { helper: t, removeHelper: n, inSSR: s }) {
  e.isBlock || ((e.isBlock = !0), n(ht(s, e.isComponent)), t(tt), t(mt(s, e.isComponent)));
}
const $s = new Uint8Array([123, 123]),
  Bs = new Uint8Array([125, 125]);
function Hs(e) {
  return (e >= 97 && e <= 122) || (e >= 65 && e <= 90);
}
function be(e) {
  return e === 32 || e === 10 || e === 9 || e === 12 || e === 13;
}
function Ge(e) {
  return e === 47 || e === 62 || be(e);
}
function dn(e) {
  const t = new Uint8Array(e.length);
  for (let n = 0; n < e.length; n++) t[n] = e.charCodeAt(n);
  return t;
}
const ce = {
  Cdata: new Uint8Array([67, 68, 65, 84, 65, 91]),
  CdataEnd: new Uint8Array([93, 93, 62]),
  CommentEnd: new Uint8Array([45, 45, 62]),
  ScriptEnd: new Uint8Array([60, 47, 115, 99, 114, 105, 112, 116]),
  StyleEnd: new Uint8Array([60, 47, 115, 116, 121, 108, 101]),
  TitleEnd: new Uint8Array([60, 47, 116, 105, 116, 108, 101]),
  TextareaEnd: new Uint8Array([60, 47, 116, 101, 120, 116, 97, 114, 101, 97])
};
class Ho {
  constructor(t, n) {
    ((this.stack = t),
      (this.cbs = n),
      (this.state = 1),
      (this.buffer = ""),
      (this.sectionStart = 0),
      (this.index = 0),
      (this.entityStart = 0),
      (this.baseState = 1),
      (this.inRCDATA = !1),
      (this.inXML = !1),
      (this.inVPre = !1),
      (this.newlines = []),
      (this.mode = 0),
      (this.delimiterOpen = $s),
      (this.delimiterClose = Bs),
      (this.delimiterIndex = -1),
      (this.currentSequence = void 0),
      (this.sequenceIndex = 0));
  }
  get inSFCRoot() {
    return this.mode === 2 && this.stack.length === 0;
  }
  reset() {
    ((this.state = 1),
      (this.mode = 0),
      (this.buffer = ""),
      (this.sectionStart = 0),
      (this.index = 0),
      (this.baseState = 1),
      (this.inRCDATA = !1),
      (this.currentSequence = void 0),
      (this.newlines.length = 0),
      (this.delimiterOpen = $s),
      (this.delimiterClose = Bs));
  }
  getPos(t) {
    let n = 1,
      s = t + 1;
    const i = this.newlines.length;
    let a = -1;
    if (i > 100) {
      let r = -1,
        l = i;
      for (; r + 1 < l; ) {
        const o = (r + l) >>> 1;
        this.newlines[o] < t ? (r = o) : (l = o);
      }
      a = r;
    } else
      for (let r = i - 1; r >= 0; r--)
        if (t > this.newlines[r]) {
          a = r;
          break;
        }
    return (a >= 0 && ((n = a + 2), (s = t - this.newlines[a])), { column: s, line: n, offset: t });
  }
  peek() {
    return this.buffer.charCodeAt(this.index + 1);
  }
  stateText(t) {
    t === 60
      ? (this.index > this.sectionStart && this.cbs.ontext(this.sectionStart, this.index),
        (this.state = 5),
        (this.sectionStart = this.index))
      : !this.inVPre &&
        t === this.delimiterOpen[0] &&
        ((this.state = 2), (this.delimiterIndex = 0), this.stateInterpolationOpen(t));
  }
  stateInterpolationOpen(t) {
    if (t === this.delimiterOpen[this.delimiterIndex])
      if (this.delimiterIndex === this.delimiterOpen.length - 1) {
        const n = this.index + 1 - this.delimiterOpen.length;
        (n > this.sectionStart && this.cbs.ontext(this.sectionStart, n),
          (this.state = 3),
          (this.sectionStart = n));
      } else this.delimiterIndex++;
    else
      this.inRCDATA
        ? ((this.state = 32), this.stateInRCDATA(t))
        : ((this.state = 1), this.stateText(t));
  }
  stateInterpolation(t) {
    t === this.delimiterClose[0] &&
      ((this.state = 4), (this.delimiterIndex = 0), this.stateInterpolationClose(t));
  }
  stateInterpolationClose(t) {
    t === this.delimiterClose[this.delimiterIndex]
      ? this.delimiterIndex === this.delimiterClose.length - 1
        ? (this.cbs.oninterpolation(this.sectionStart, this.index + 1),
          this.inRCDATA ? (this.state = 32) : (this.state = 1),
          (this.sectionStart = this.index + 1))
        : this.delimiterIndex++
      : ((this.state = 3), this.stateInterpolation(t));
  }
  stateSpecialStartSequence(t) {
    const n = this.sequenceIndex === this.currentSequence.length;
    if (!(n ? Ge(t) : (t | 32) === this.currentSequence[this.sequenceIndex])) this.inRCDATA = !1;
    else if (!n) {
      this.sequenceIndex++;
      return;
    }
    ((this.sequenceIndex = 0), (this.state = 6), this.stateInTagName(t));
  }
  stateInRCDATA(t) {
    if (this.sequenceIndex === this.currentSequence.length) {
      if (t === 62 || be(t)) {
        const n = this.index - this.currentSequence.length;
        if (this.sectionStart < n) {
          const s = this.index;
          ((this.index = n), this.cbs.ontext(this.sectionStart, n), (this.index = s));
        }
        ((this.sectionStart = n + 2), this.stateInClosingTagName(t), (this.inRCDATA = !1));
        return;
      }
      this.sequenceIndex = 0;
    }
    (t | 32) === this.currentSequence[this.sequenceIndex]
      ? (this.sequenceIndex += 1)
      : this.sequenceIndex === 0
        ? this.currentSequence === ce.TitleEnd ||
          (this.currentSequence === ce.TextareaEnd && !this.inSFCRoot)
          ? !this.inVPre &&
            t === this.delimiterOpen[0] &&
            ((this.state = 2), (this.delimiterIndex = 0), this.stateInterpolationOpen(t))
          : this.fastForwardTo(60) && (this.sequenceIndex = 1)
        : (this.sequenceIndex = +(t === 60));
  }
  stateCDATASequence(t) {
    t === ce.Cdata[this.sequenceIndex]
      ? ++this.sequenceIndex === ce.Cdata.length &&
        ((this.state = 28),
        (this.currentSequence = ce.CdataEnd),
        (this.sequenceIndex = 0),
        (this.sectionStart = this.index + 1))
      : ((this.sequenceIndex = 0), (this.state = 23), this.stateInDeclaration(t));
  }
  fastForwardTo(t) {
    for (; ++this.index < this.buffer.length; ) {
      const n = this.buffer.charCodeAt(this.index);
      if ((n === 10 && this.newlines.push(this.index), n === t)) return !0;
    }
    return ((this.index = this.buffer.length - 1), !1);
  }
  stateInCommentLike(t) {
    t === this.currentSequence[this.sequenceIndex]
      ? ++this.sequenceIndex === this.currentSequence.length &&
        (this.currentSequence === ce.CdataEnd
          ? this.cbs.oncdata(this.sectionStart, this.index - 2)
          : this.cbs.oncomment(this.sectionStart, this.index - 2),
        (this.sequenceIndex = 0),
        (this.sectionStart = this.index + 1),
        (this.state = 1))
      : this.sequenceIndex === 0
        ? this.fastForwardTo(this.currentSequence[0]) && (this.sequenceIndex = 1)
        : t !== this.currentSequence[this.sequenceIndex - 1] && (this.sequenceIndex = 0);
  }
  startSpecial(t, n) {
    (this.enterRCDATA(t, n), (this.state = 31));
  }
  enterRCDATA(t, n) {
    ((this.inRCDATA = !0), (this.currentSequence = t), (this.sequenceIndex = n));
  }
  stateBeforeTagName(t) {
    t === 33
      ? ((this.state = 22), (this.sectionStart = this.index + 1))
      : t === 63
        ? ((this.state = 24), (this.sectionStart = this.index + 1))
        : Hs(t)
          ? ((this.sectionStart = this.index),
            this.mode === 0
              ? (this.state = 6)
              : this.inSFCRoot
                ? (this.state = 34)
                : this.inXML
                  ? (this.state = 6)
                  : t === 116
                    ? (this.state = 30)
                    : (this.state = t === 115 ? 29 : 6))
          : t === 47
            ? (this.state = 8)
            : ((this.state = 1), this.stateText(t));
  }
  stateInTagName(t) {
    Ge(t) && this.handleTagName(t);
  }
  stateInSFCRootTagName(t) {
    if (Ge(t)) {
      const n = this.buffer.slice(this.sectionStart, this.index);
      (n !== "template" && this.enterRCDATA(dn("</" + n), 0), this.handleTagName(t));
    }
  }
  handleTagName(t) {
    (this.cbs.onopentagname(this.sectionStart, this.index),
      (this.sectionStart = -1),
      (this.state = 11),
      this.stateBeforeAttrName(t));
  }
  stateBeforeClosingTagName(t) {
    be(t) ||
      (t === 62
        ? ((this.state = 1), (this.sectionStart = this.index + 1))
        : ((this.state = Hs(t) ? 9 : 27), (this.sectionStart = this.index)));
  }
  stateInClosingTagName(t) {
    (t === 62 || be(t)) &&
      (this.cbs.onclosetag(this.sectionStart, this.index),
      (this.sectionStart = -1),
      (this.state = 10),
      this.stateAfterClosingTagName(t));
  }
  stateAfterClosingTagName(t) {
    t === 62 && ((this.state = 1), (this.sectionStart = this.index + 1));
  }
  stateBeforeAttrName(t) {
    t === 62
      ? (this.cbs.onopentagend(this.index),
        this.inRCDATA ? (this.state = 32) : (this.state = 1),
        (this.sectionStart = this.index + 1))
      : t === 47
        ? (this.state = 7)
        : t === 60 && this.peek() === 47
          ? (this.cbs.onopentagend(this.index), (this.state = 5), (this.sectionStart = this.index))
          : be(t) || this.handleAttrStart(t);
  }
  handleAttrStart(t) {
    t === 118 && this.peek() === 45
      ? ((this.state = 13), (this.sectionStart = this.index))
      : t === 46 || t === 58 || t === 64 || t === 35
        ? (this.cbs.ondirname(this.index, this.index + 1),
          (this.state = 14),
          (this.sectionStart = this.index + 1))
        : ((this.state = 12), (this.sectionStart = this.index));
  }
  stateInSelfClosingTag(t) {
    t === 62
      ? (this.cbs.onselfclosingtag(this.index),
        (this.state = 1),
        (this.sectionStart = this.index + 1),
        (this.inRCDATA = !1))
      : be(t) || ((this.state = 11), this.stateBeforeAttrName(t));
  }
  stateInAttrName(t) {
    (t === 61 || Ge(t)) &&
      (this.cbs.onattribname(this.sectionStart, this.index), this.handleAttrNameEnd(t));
  }
  stateInDirName(t) {
    t === 61 || Ge(t)
      ? (this.cbs.ondirname(this.sectionStart, this.index), this.handleAttrNameEnd(t))
      : t === 58
        ? (this.cbs.ondirname(this.sectionStart, this.index),
          (this.state = 14),
          (this.sectionStart = this.index + 1))
        : t === 46 &&
          (this.cbs.ondirname(this.sectionStart, this.index),
          (this.state = 16),
          (this.sectionStart = this.index + 1));
  }
  stateInDirArg(t) {
    t === 61 || Ge(t)
      ? (this.cbs.ondirarg(this.sectionStart, this.index), this.handleAttrNameEnd(t))
      : t === 91
        ? (this.state = 15)
        : t === 46 &&
          (this.cbs.ondirarg(this.sectionStart, this.index),
          (this.state = 16),
          (this.sectionStart = this.index + 1));
  }
  stateInDynamicDirArg(t) {
    t === 93
      ? (this.state = 14)
      : (t === 61 || Ge(t)) &&
        (this.cbs.ondirarg(this.sectionStart, this.index + 1), this.handleAttrNameEnd(t));
  }
  stateInDirModifier(t) {
    t === 61 || Ge(t)
      ? (this.cbs.ondirmodifier(this.sectionStart, this.index), this.handleAttrNameEnd(t))
      : t === 46 &&
        (this.cbs.ondirmodifier(this.sectionStart, this.index),
        (this.sectionStart = this.index + 1));
  }
  handleAttrNameEnd(t) {
    ((this.sectionStart = this.index),
      (this.state = 17),
      this.cbs.onattribnameend(this.index),
      this.stateAfterAttrName(t));
  }
  stateAfterAttrName(t) {
    t === 61
      ? (this.state = 18)
      : t === 47 || t === 62
        ? (this.cbs.onattribend(0, this.sectionStart),
          (this.sectionStart = -1),
          (this.state = 11),
          this.stateBeforeAttrName(t))
        : be(t) || (this.cbs.onattribend(0, this.sectionStart), this.handleAttrStart(t));
  }
  stateBeforeAttrValue(t) {
    t === 34
      ? ((this.state = 19), (this.sectionStart = this.index + 1))
      : t === 39
        ? ((this.state = 20), (this.sectionStart = this.index + 1))
        : be(t) ||
          ((this.sectionStart = this.index), (this.state = 21), this.stateInAttrValueNoQuotes(t));
  }
  handleInAttrValue(t, n) {
    (t === n || this.fastForwardTo(n)) &&
      (this.cbs.onattribdata(this.sectionStart, this.index),
      (this.sectionStart = -1),
      this.cbs.onattribend(n === 34 ? 3 : 2, this.index + 1),
      (this.state = 11));
  }
  stateInAttrValueDoubleQuotes(t) {
    this.handleInAttrValue(t, 34);
  }
  stateInAttrValueSingleQuotes(t) {
    this.handleInAttrValue(t, 39);
  }
  stateInAttrValueNoQuotes(t) {
    be(t) || t === 62
      ? (this.cbs.onattribdata(this.sectionStart, this.index),
        (this.sectionStart = -1),
        this.cbs.onattribend(1, this.index),
        (this.state = 11),
        this.stateBeforeAttrName(t))
      : (t === 39 || t === 60 || t === 61 || t === 96) && this.cbs.onerr(18, this.index);
  }
  stateBeforeDeclaration(t) {
    t === 91 ? ((this.state = 26), (this.sequenceIndex = 0)) : (this.state = t === 45 ? 25 : 23);
  }
  stateInDeclaration(t) {
    (t === 62 || this.fastForwardTo(62)) &&
      ((this.state = 1), (this.sectionStart = this.index + 1));
  }
  stateInProcessingInstruction(t) {
    (t === 62 || this.fastForwardTo(62)) &&
      (this.cbs.onprocessinginstruction(this.sectionStart, this.index),
      (this.state = 1),
      (this.sectionStart = this.index + 1));
  }
  stateBeforeComment(t) {
    t === 45
      ? ((this.state = 28),
        (this.currentSequence = ce.CommentEnd),
        (this.sequenceIndex = 2),
        (this.sectionStart = this.index + 1))
      : (this.state = 23);
  }
  stateInSpecialComment(t) {
    (t === 62 || this.fastForwardTo(62)) &&
      (this.cbs.oncomment(this.sectionStart, this.index),
      (this.state = 1),
      (this.sectionStart = this.index + 1));
  }
  stateBeforeSpecialS(t) {
    t === ce.ScriptEnd[3]
      ? this.startSpecial(ce.ScriptEnd, 4)
      : t === ce.StyleEnd[3]
        ? this.startSpecial(ce.StyleEnd, 4)
        : ((this.state = 6), this.stateInTagName(t));
  }
  stateBeforeSpecialT(t) {
    t === ce.TitleEnd[3]
      ? this.startSpecial(ce.TitleEnd, 4)
      : t === ce.TextareaEnd[3]
        ? this.startSpecial(ce.TextareaEnd, 4)
        : ((this.state = 6), this.stateInTagName(t));
  }
  startEntity() {}
  stateInEntity() {}
  parse(t) {
    for (this.buffer = t; this.index < this.buffer.length; ) {
      const n = this.buffer.charCodeAt(this.index);
      switch ((n === 10 && this.state !== 33 && this.newlines.push(this.index), this.state)) {
        case 1: {
          this.stateText(n);
          break;
        }
        case 2: {
          this.stateInterpolationOpen(n);
          break;
        }
        case 3: {
          this.stateInterpolation(n);
          break;
        }
        case 4: {
          this.stateInterpolationClose(n);
          break;
        }
        case 31: {
          this.stateSpecialStartSequence(n);
          break;
        }
        case 32: {
          this.stateInRCDATA(n);
          break;
        }
        case 26: {
          this.stateCDATASequence(n);
          break;
        }
        case 19: {
          this.stateInAttrValueDoubleQuotes(n);
          break;
        }
        case 12: {
          this.stateInAttrName(n);
          break;
        }
        case 13: {
          this.stateInDirName(n);
          break;
        }
        case 14: {
          this.stateInDirArg(n);
          break;
        }
        case 15: {
          this.stateInDynamicDirArg(n);
          break;
        }
        case 16: {
          this.stateInDirModifier(n);
          break;
        }
        case 28: {
          this.stateInCommentLike(n);
          break;
        }
        case 27: {
          this.stateInSpecialComment(n);
          break;
        }
        case 11: {
          this.stateBeforeAttrName(n);
          break;
        }
        case 6: {
          this.stateInTagName(n);
          break;
        }
        case 34: {
          this.stateInSFCRootTagName(n);
          break;
        }
        case 9: {
          this.stateInClosingTagName(n);
          break;
        }
        case 5: {
          this.stateBeforeTagName(n);
          break;
        }
        case 17: {
          this.stateAfterAttrName(n);
          break;
        }
        case 20: {
          this.stateInAttrValueSingleQuotes(n);
          break;
        }
        case 18: {
          this.stateBeforeAttrValue(n);
          break;
        }
        case 8: {
          this.stateBeforeClosingTagName(n);
          break;
        }
        case 10: {
          this.stateAfterClosingTagName(n);
          break;
        }
        case 29: {
          this.stateBeforeSpecialS(n);
          break;
        }
        case 30: {
          this.stateBeforeSpecialT(n);
          break;
        }
        case 21: {
          this.stateInAttrValueNoQuotes(n);
          break;
        }
        case 7: {
          this.stateInSelfClosingTag(n);
          break;
        }
        case 23: {
          this.stateInDeclaration(n);
          break;
        }
        case 22: {
          this.stateBeforeDeclaration(n);
          break;
        }
        case 25: {
          this.stateBeforeComment(n);
          break;
        }
        case 24: {
          this.stateInProcessingInstruction(n);
          break;
        }
        case 33: {
          this.stateInEntity();
          break;
        }
      }
      this.index++;
    }
    (this.cleanup(), this.finish());
  }
  cleanup() {
    this.sectionStart !== this.index &&
      (this.state === 1 || (this.state === 32 && this.sequenceIndex === 0)
        ? (this.cbs.ontext(this.sectionStart, this.index), (this.sectionStart = this.index))
        : (this.state === 19 || this.state === 20 || this.state === 21) &&
          (this.cbs.onattribdata(this.sectionStart, this.index), (this.sectionStart = this.index)));
  }
  finish() {
    (this.handleTrailingData(), this.cbs.onend());
  }
  handleTrailingData() {
    const t = this.buffer.length;
    this.sectionStart >= t ||
      (this.state === 28
        ? this.currentSequence === ce.CdataEnd
          ? this.cbs.oncdata(this.sectionStart, t)
          : this.cbs.oncomment(this.sectionStart, t)
        : this.state === 6 ||
          this.state === 11 ||
          this.state === 18 ||
          this.state === 17 ||
          this.state === 12 ||
          this.state === 13 ||
          this.state === 14 ||
          this.state === 15 ||
          this.state === 16 ||
          this.state === 20 ||
          this.state === 19 ||
          this.state === 21 ||
          this.state === 9 ||
          this.cbs.ontext(this.sectionStart, t));
  }
  emitCodePoint(t, n) {}
}
const zo = {
    COMPILER_IS_ON_ELEMENT: "COMPILER_IS_ON_ELEMENT",
    COMPILER_V_BIND_SYNC: "COMPILER_V_BIND_SYNC",
    COMPILER_V_BIND_OBJECT_ORDER: "COMPILER_V_BIND_OBJECT_ORDER",
    COMPILER_V_ON_NATIVE: "COMPILER_V_ON_NATIVE",
    COMPILER_V_IF_V_FOR_PRECEDENCE: "COMPILER_V_IF_V_FOR_PRECEDENCE",
    COMPILER_NATIVE_TEMPLATE: "COMPILER_NATIVE_TEMPLATE",
    COMPILER_INLINE_TEMPLATE: "COMPILER_INLINE_TEMPLATE",
    COMPILER_FILTERS: "COMPILER_FILTERS"
  },
  Xo = {
    COMPILER_IS_ON_ELEMENT: {
      message:
        'Platform-native elements with "is" prop will no longer be treated as components in Vue 3 unless the "is" value is explicitly prefixed with "vue:".',
      link: "https://v3-migration.vuejs.org/breaking-changes/custom-elements-interop.html"
    },
    COMPILER_V_BIND_SYNC: {
      message: (e) =>
        `.sync modifier for v-bind has been removed. Use v-model with argument instead. \`v-bind:${e}.sync\` should be changed to \`v-model:${e}\`.`,
      link: "https://v3-migration.vuejs.org/breaking-changes/v-model.html"
    },
    COMPILER_V_BIND_OBJECT_ORDER: {
      message:
        'v-bind="obj" usage is now order sensitive and behaves like JavaScript object spread: it will now overwrite an existing non-mergeable attribute that appears before v-bind in the case of conflict. To retain 2.x behavior, move v-bind to make it the first attribute. You can also suppress this warning if the usage is intended.',
      link: "https://v3-migration.vuejs.org/breaking-changes/v-bind.html"
    },
    COMPILER_V_ON_NATIVE: {
      message: ".native modifier for v-on has been removed as is no longer necessary.",
      link: "https://v3-migration.vuejs.org/breaking-changes/v-on-native-modifier-removed.html"
    },
    COMPILER_V_IF_V_FOR_PRECEDENCE: {
      message:
        "v-if / v-for precedence when used on the same element has changed in Vue 3: v-if now takes higher precedence and will no longer have access to v-for scope variables. It is best to avoid the ambiguity with <template> tags or use a computed property that filters v-for data source.",
      link: "https://v3-migration.vuejs.org/breaking-changes/v-if-v-for.html"
    },
    COMPILER_NATIVE_TEMPLATE: {
      message:
        "<template> with no special directives will render as a native template element instead of its inner content in Vue 3."
    },
    COMPILER_INLINE_TEMPLATE: {
      message: '"inline-template" has been removed in Vue 3.',
      link: "https://v3-migration.vuejs.org/breaking-changes/inline-template-attribute.html"
    },
    COMPILER_FILTERS: {
      message:
        'filters have been removed in Vue 3. The "|" symbol will be treated as native JavaScript bitwise OR operator. Use method calls or computed properties instead.',
      link: "https://v3-migration.vuejs.org/breaking-changes/filters.html"
    }
  };
function zn(e, { compatConfig: t }) {
  const n = t && t[e];
  return e === "MODE" ? n || 3 : n;
}
function ft(e, t) {
  const n = zn("MODE", t),
    s = zn(e, t);
  return n === 3 ? s === !0 : s !== !1;
}
function Ct(e, t, n, ...s) {
  return ft(e, t);
}
function Fo(e, t, n, ...s) {
  if (zn(e, t) === "suppress-warning") return;
  const { message: a, link: r } = Xo[e],
    l = `(deprecation ${e}) ${typeof a == "function" ? a(...s) : a}${
      r
        ? `
  Details: ${r}`
        : ""
    }`,
    o = new SyntaxError(l);
  ((o.code = e), n && (o.loc = n), t.onWarn(o));
}
function rs(e) {
  throw e;
}
function Li(e) {}
function U(e, t, n, s) {
  const i = `https://vuejs.org/error-reference/#compiler-${e}`,
    a = new SyntaxError(String(i));
  return ((a.code = e), (a.loc = t), a);
}
const Uo = {
    ABRUPT_CLOSING_OF_EMPTY_COMMENT: 0,
    0: "ABRUPT_CLOSING_OF_EMPTY_COMMENT",
    CDATA_IN_HTML_CONTENT: 1,
    1: "CDATA_IN_HTML_CONTENT",
    DUPLICATE_ATTRIBUTE: 2,
    2: "DUPLICATE_ATTRIBUTE",
    END_TAG_WITH_ATTRIBUTES: 3,
    3: "END_TAG_WITH_ATTRIBUTES",
    END_TAG_WITH_TRAILING_SOLIDUS: 4,
    4: "END_TAG_WITH_TRAILING_SOLIDUS",
    EOF_BEFORE_TAG_NAME: 5,
    5: "EOF_BEFORE_TAG_NAME",
    EOF_IN_CDATA: 6,
    6: "EOF_IN_CDATA",
    EOF_IN_COMMENT: 7,
    7: "EOF_IN_COMMENT",
    EOF_IN_SCRIPT_HTML_COMMENT_LIKE_TEXT: 8,
    8: "EOF_IN_SCRIPT_HTML_COMMENT_LIKE_TEXT",
    EOF_IN_TAG: 9,
    9: "EOF_IN_TAG",
    INCORRECTLY_CLOSED_COMMENT: 10,
    10: "INCORRECTLY_CLOSED_COMMENT",
    INCORRECTLY_OPENED_COMMENT: 11,
    11: "INCORRECTLY_OPENED_COMMENT",
    INVALID_FIRST_CHARACTER_OF_TAG_NAME: 12,
    12: "INVALID_FIRST_CHARACTER_OF_TAG_NAME",
    MISSING_ATTRIBUTE_VALUE: 13,
    13: "MISSING_ATTRIBUTE_VALUE",
    MISSING_END_TAG_NAME: 14,
    14: "MISSING_END_TAG_NAME",
    MISSING_WHITESPACE_BETWEEN_ATTRIBUTES: 15,
    15: "MISSING_WHITESPACE_BETWEEN_ATTRIBUTES",
    NESTED_COMMENT: 16,
    16: "NESTED_COMMENT",
    UNEXPECTED_CHARACTER_IN_ATTRIBUTE_NAME: 17,
    17: "UNEXPECTED_CHARACTER_IN_ATTRIBUTE_NAME",
    UNEXPECTED_CHARACTER_IN_UNQUOTED_ATTRIBUTE_VALUE: 18,
    18: "UNEXPECTED_CHARACTER_IN_UNQUOTED_ATTRIBUTE_VALUE",
    UNEXPECTED_EQUALS_SIGN_BEFORE_ATTRIBUTE_NAME: 19,
    19: "UNEXPECTED_EQUALS_SIGN_BEFORE_ATTRIBUTE_NAME",
    UNEXPECTED_NULL_CHARACTER: 20,
    20: "UNEXPECTED_NULL_CHARACTER",
    UNEXPECTED_QUESTION_MARK_INSTEAD_OF_TAG_NAME: 21,
    21: "UNEXPECTED_QUESTION_MARK_INSTEAD_OF_TAG_NAME",
    UNEXPECTED_SOLIDUS_IN_TAG: 22,
    22: "UNEXPECTED_SOLIDUS_IN_TAG",
    X_INVALID_END_TAG: 23,
    23: "X_INVALID_END_TAG",
    X_MISSING_END_TAG: 24,
    24: "X_MISSING_END_TAG",
    X_MISSING_INTERPOLATION_END: 25,
    25: "X_MISSING_INTERPOLATION_END",
    X_MISSING_DIRECTIVE_NAME: 26,
    26: "X_MISSING_DIRECTIVE_NAME",
    X_MISSING_DYNAMIC_DIRECTIVE_ARGUMENT_END: 27,
    27: "X_MISSING_DYNAMIC_DIRECTIVE_ARGUMENT_END",
    X_V_IF_NO_EXPRESSION: 28,
    28: "X_V_IF_NO_EXPRESSION",
    X_V_IF_SAME_KEY: 29,
    29: "X_V_IF_SAME_KEY",
    X_V_ELSE_NO_ADJACENT_IF: 30,
    30: "X_V_ELSE_NO_ADJACENT_IF",
    X_V_FOR_NO_EXPRESSION: 31,
    31: "X_V_FOR_NO_EXPRESSION",
    X_V_FOR_MALFORMED_EXPRESSION: 32,
    32: "X_V_FOR_MALFORMED_EXPRESSION",
    X_V_FOR_TEMPLATE_KEY_PLACEMENT: 33,
    33: "X_V_FOR_TEMPLATE_KEY_PLACEMENT",
    X_V_BIND_NO_EXPRESSION: 34,
    34: "X_V_BIND_NO_EXPRESSION",
    X_V_ON_NO_EXPRESSION: 35,
    35: "X_V_ON_NO_EXPRESSION",
    X_V_SLOT_UNEXPECTED_DIRECTIVE_ON_SLOT_OUTLET: 36,
    36: "X_V_SLOT_UNEXPECTED_DIRECTIVE_ON_SLOT_OUTLET",
    X_V_SLOT_MIXED_SLOT_USAGE: 37,
    37: "X_V_SLOT_MIXED_SLOT_USAGE",
    X_V_SLOT_DUPLICATE_SLOT_NAMES: 38,
    38: "X_V_SLOT_DUPLICATE_SLOT_NAMES",
    X_V_SLOT_EXTRANEOUS_DEFAULT_SLOT_CHILDREN: 39,
    39: "X_V_SLOT_EXTRANEOUS_DEFAULT_SLOT_CHILDREN",
    X_V_SLOT_MISPLACED: 40,
    40: "X_V_SLOT_MISPLACED",
    X_V_MODEL_NO_EXPRESSION: 41,
    41: "X_V_MODEL_NO_EXPRESSION",
    X_V_MODEL_MALFORMED_EXPRESSION: 42,
    42: "X_V_MODEL_MALFORMED_EXPRESSION",
    X_V_MODEL_ON_SCOPE_VARIABLE: 43,
    43: "X_V_MODEL_ON_SCOPE_VARIABLE",
    X_V_MODEL_ON_PROPS: 44,
    44: "X_V_MODEL_ON_PROPS",
    X_V_MODEL_ON_CONST: 45,
    45: "X_V_MODEL_ON_CONST",
    X_INVALID_EXPRESSION: 46,
    46: "X_INVALID_EXPRESSION",
    X_KEEP_ALIVE_INVALID_CHILDREN: 47,
    47: "X_KEEP_ALIVE_INVALID_CHILDREN",
    X_PREFIX_ID_NOT_SUPPORTED: 48,
    48: "X_PREFIX_ID_NOT_SUPPORTED",
    X_MODULE_MODE_NOT_SUPPORTED: 49,
    49: "X_MODULE_MODE_NOT_SUPPORTED",
    X_CACHE_HANDLER_NOT_SUPPORTED: 50,
    50: "X_CACHE_HANDLER_NOT_SUPPORTED",
    X_SCOPE_ID_NOT_SUPPORTED: 51,
    51: "X_SCOPE_ID_NOT_SUPPORTED",
    X_VNODE_HOOKS: 52,
    52: "X_VNODE_HOOKS",
    X_V_BIND_INVALID_SAME_NAME_ARGUMENT: 53,
    53: "X_V_BIND_INVALID_SAME_NAME_ARGUMENT",
    __EXTEND_POINT__: 54,
    54: "__EXTEND_POINT__"
  },
  Wo = {
    0: "Illegal comment.",
    1: "CDATA section is allowed only in XML context.",
    2: "Duplicate attribute.",
    3: "End tag cannot have attributes.",
    4: "Illegal '/' in tags.",
    5: "Unexpected EOF in tag.",
    6: "Unexpected EOF in CDATA section.",
    7: "Unexpected EOF in comment.",
    8: "Unexpected EOF in script.",
    9: "Unexpected EOF in tag.",
    10: "Incorrectly closed comment.",
    11: "Incorrectly opened comment.",
    12: "Illegal tag name. Use '&lt;' to print '<'.",
    13: "Attribute value was expected.",
    14: "End tag name was expected.",
    15: "Whitespace was expected.",
    16: "Unexpected '<!--' in comment.",
    17: `Attribute name cannot contain U+0022 ("), U+0027 ('), and U+003C (<).`,
    18: "Unquoted attribute value cannot contain U+0022 (\"), U+0027 ('), U+003C (<), U+003D (=), and U+0060 (`).",
    19: "Attribute name cannot start with '='.",
    21: "'<?' is allowed only in XML context.",
    20: "Unexpected null character.",
    22: "Illegal '/' in tags.",
    23: "Invalid end tag.",
    24: "Element is missing end tag.",
    25: "Interpolation end sign was not found.",
    27: "End bracket for dynamic directive argument was not found. Note that dynamic directive argument cannot contain spaces.",
    26: "Legal directive name was expected.",
    28: "v-if/v-else-if is missing expression.",
    29: "v-if/else branches must use unique keys.",
    30: "v-else/v-else-if has no adjacent v-if or v-else-if.",
    31: "v-for is missing expression.",
    32: "v-for has invalid expression.",
    33: "<template v-for> key should be placed on the <template> tag.",
    34: "v-bind is missing expression.",
    53: "v-bind with same-name shorthand only allows static argument.",
    35: "v-on is missing expression.",
    36: "Unexpected custom directive on <slot> outlet.",
    37: "Mixed v-slot usage on both the component and nested <template>. When there are multiple named slots, all slots should use <template> syntax to avoid scope ambiguity.",
    38: "Duplicate slot names found. ",
    39: "Extraneous children found when component already has explicitly named default slot. These children will be ignored.",
    40: "v-slot can only be used on components or <template> tags.",
    41: "v-model is missing expression.",
    42: "v-model value must be a valid JavaScript member expression.",
    43: "v-model cannot be used on v-for or v-slot scope variables because they are not writable.",
    44: `v-model cannot be used on a prop, because local prop bindings are not writable.
Use a v-bind binding combined with a v-on listener that emits update:x event instead.`,
    45: "v-model cannot be used on a const binding because it is not writable.",
    46: "Error parsing JavaScript expression: ",
    47: "<KeepAlive> expects exactly one child component.",
    52: "@vnode-* hooks in templates are no longer supported. Use the vue: prefix instead. For example, @vnode-mounted should be changed to @vue:mounted. @vnode-* hooks support has been removed in 3.4.",
    48: '"prefixIdentifiers" option is not supported in this build of compiler.',
    49: "ES module mode is not supported in this build of compiler.",
    50: '"cacheHandlers" option is only supported when the "prefixIdentifiers" option is enabled.',
    51: '"scopeId" option is only supported in module mode.',
    54: ""
  };
function jo(e, t, n = !1, s = [], i = Object.create(null)) {}
function qo(e, t, n) {
  return !1;
}
function Go(e, t) {
  if (e && (e.type === "ObjectProperty" || e.type === "ArrayPattern")) {
    let n = t.length;
    for (; n--; ) {
      const s = t[n];
      if (s.type === "AssignmentExpression") return !0;
      if (s.type !== "ObjectProperty" && !s.type.endsWith("Pattern")) break;
    }
  }
  return !1;
}
function Jo(e) {
  let t = e.length;
  for (; t--; ) {
    const n = e[t];
    if (n.type === "NewExpression") return !0;
    if (n.type !== "MemberExpression") break;
  }
  return !1;
}
function Yo(e, t) {
  for (const n of e.params) for (const s of Pe(n)) t(s);
}
function Di(e, t) {
  const n = e.type === "SwitchCase" ? e.consequent : e.body;
  for (const s of n)
    if (s.type === "VariableDeclaration") {
      if (s.declare) continue;
      for (const i of s.declarations) for (const a of Pe(i.id)) t(a);
    } else if (s.type === "FunctionDeclaration" || s.type === "ClassDeclaration") {
      if (s.declare || !s.id) continue;
      t(s.id);
    } else Ko(s) ? Qo(s, !0, t) : s.type === "SwitchStatement" && Zo(s, !0, t);
}
function Ko(e) {
  return e.type === "ForOfStatement" || e.type === "ForInStatement" || e.type === "ForStatement";
}
function Qo(e, t, n) {
  const s = e.type === "ForStatement" ? e.init : e.left;
  if (s && s.type === "VariableDeclaration" && (s.kind === "var" ? t : !t))
    for (const i of s.declarations) for (const a of Pe(i.id)) n(a);
}
function Zo(e, t, n) {
  for (const s of e.cases) {
    for (const i of s.consequent)
      if (i.type === "VariableDeclaration" && (i.kind === "var" ? t : !t))
        for (const a of i.declarations) for (const r of Pe(a.id)) n(r);
    Di(s, n);
  }
}
function Pe(e, t = []) {
  switch (e.type) {
    case "Identifier":
      t.push(e);
      break;
    case "MemberExpression":
      let n = e;
      for (; n.type === "MemberExpression"; ) n = n.object;
      t.push(n);
      break;
    case "ObjectPattern":
      for (const s of e.properties) s.type === "RestElement" ? Pe(s.argument, t) : Pe(s.value, t);
      break;
    case "ArrayPattern":
      e.elements.forEach((s) => {
        s && Pe(s, t);
      });
      break;
    case "RestElement":
      Pe(e.argument, t);
      break;
    case "AssignmentPattern":
      Pe(e.left, t);
      break;
  }
  return t;
}
const el = (e) => /Function(?:Expression|Declaration)$|Method$/.test(e.type),
  Pi = (e) => e && (e.type === "ObjectProperty" || e.type === "ObjectMethod") && !e.computed,
  tl = (e, t) => Pi(t) && t.key === e,
  xi = [
    "TSAsExpression",
    "TSTypeAssertion",
    "TSNonNullExpression",
    "TSInstantiationExpression",
    "TSSatisfiesExpression"
  ];
function ki(e) {
  return xi.includes(e.type) ? ki(e.expression) : e;
}
const ge = (e) => e.type === 4 && e.isStatic;
function as(e) {
  switch (e) {
    case "Teleport":
    case "teleport":
      return St;
    case "Suspense":
    case "suspense":
      return vn;
    case "KeepAlive":
    case "keep-alive":
      return $t;
    case "BaseTransition":
    case "base-transition":
      return Qn;
  }
}
const nl = /^$|^\d|[^\$\w\xA0-\uFFFF]/,
  qt = (e) => !nl.test(e),
  os = /[A-Za-z_$\xA0-\uFFFF]/,
  sl = /[\.\?\w$\xA0-\uFFFF]/,
  il = /\s+[.[]\s*|\s*[.[]\s+/g,
  Vi = (e) => (e.type === 4 ? e.content : e.loc.source),
  $i = (e) => {
    const t = Vi(e)
      .trim()
      .replace(il, (l) => l.trim());
    let n = 0,
      s = [],
      i = 0,
      a = 0,
      r = null;
    for (let l = 0; l < t.length; l++) {
      const o = t.charAt(l);
      switch (n) {
        case 0:
          if (o === "[") (s.push(n), (n = 1), i++);
          else if (o === "(") (s.push(n), (n = 2), a++);
          else if (!(l === 0 ? os : sl).test(o)) return !1;
          break;
        case 1:
          o === "'" || o === '"' || o === "`"
            ? (s.push(n), (n = 3), (r = o))
            : o === "["
              ? i++
              : o === "]" && (--i || (n = s.pop()));
          break;
        case 2:
          if (o === "'" || o === '"' || o === "`") (s.push(n), (n = 3), (r = o));
          else if (o === "(") a++;
          else if (o === ")") {
            if (l === t.length - 1) return !1;
            --a || (n = s.pop());
          }
          break;
        case 3:
          o === r && ((n = s.pop()), (r = null));
          break;
      }
    }
    return !i && !a;
  },
  rl = Vt,
  ls = $i,
  al =
    /^\s*(?:async\s*)?(?:\([^)]*?\)|[\w$_]+)\s*(?::[^=]+)?=>|^\s*(?:async\s+)?function(?:\s+[\w$]+)?\s*\(/,
  Bi = (e) => al.test(Vi(e)),
  ol = Vt,
  Hi = Bi;
function ll(e, t, n = t.length) {
  return zi({ offset: e.offset, line: e.line, column: e.column }, t, n);
}
function zi(e, t, n = t.length) {
  let s = 0,
    i = -1;
  for (let a = 0; a < n; a++) t.charCodeAt(a) === 10 && (s++, (i = a));
  return ((e.offset += n), (e.line += s), (e.column = i === -1 ? e.column + n : n - i), e);
}
function cl(e, t) {
  if (!e) throw new Error(t || "unexpected compiler condition");
}
function he(e, t, n = !1) {
  for (let s = 0; s < e.props.length; s++) {
    const i = e.props[s];
    if (i.type === 7 && (n || i.exp) && (ne(t) ? i.name === t : t.test(i.name))) return i;
  }
}
function Gt(e, t, n = !1, s = !1) {
  for (let i = 0; i < e.props.length; i++) {
    const a = e.props[i];
    if (a.type === 6) {
      if (n) continue;
      if (a.name === t && (a.value || s)) return a;
    } else if (a.name === "bind" && (a.exp || s) && Ke(a.arg, t)) return a;
  }
}
function Ke(e, t) {
  return !!(e && ge(e) && e.content === t);
}
function Xi(e) {
  return e.props.some(
    (t) => t.type === 7 && t.name === "bind" && (!t.arg || t.arg.type !== 4 || !t.arg.isStatic)
  );
}
function tn(e) {
  return e.type === 5 || e.type === 2;
}
function Xn(e) {
  return e.type === 7 && e.name === "pre";
}
function cs(e) {
  return e.type === 7 && e.name === "slot";
}
function Ot(e) {
  return e.type === 1 && e.tagType === 3;
}
function zt(e) {
  return e.type === 1 && e.tagType === 2;
}
const ul = new Set([Nt, Rt]);
function Fi(e, t = []) {
  if (e && !ne(e) && e.type === 14) {
    const n = e.callee;
    if (!ne(n) && ul.has(n)) return Fi(e.arguments[0], t.concat(e));
  }
  return [e, t];
}
function Xt(e, t, n) {
  let s,
    i = e.type === 13 ? e.props : e.arguments[2],
    a = [],
    r;
  if (i && !ne(i) && i.type === 14) {
    const l = Fi(i);
    ((i = l[0]), (a = l[1]), (r = a[a.length - 1]));
  }
  if (i == null || ne(i)) s = Ne([t]);
  else if (i.type === 14) {
    const l = i.arguments[0];
    (!ne(l) && l.type === 15
      ? zs(t, l) || l.properties.unshift(t)
      : i.callee === Rn
        ? (s = Q(n.helper(Bt), [Ne([t]), i]))
        : i.arguments.unshift(Ne([t])),
      !s && (s = i));
  } else
    i.type === 15
      ? (zs(t, i) || i.properties.unshift(t), (s = i))
      : ((s = Q(n.helper(Bt), [Ne([t]), i])), r && r.callee === Rt && (r = a[a.length - 2]));
  e.type === 13
    ? r
      ? (r.arguments[0] = s)
      : (e.props = s)
    : r
      ? (r.arguments[0] = s)
      : (e.arguments[2] = s);
}
function zs(e, t) {
  let n = !1;
  if (e.key.type === 4) {
    const s = e.key.content;
    n = t.properties.some((i) => i.key.type === 4 && i.key.content === s);
  }
  return n;
}
function At(e, t) {
  return `_${t}_${e.replace(/[^\w]/g, (n, s) => (n === "-" ? "_" : e.charCodeAt(s).toString()))}`;
}
function Le(e, t) {
  if (!e || Object.keys(t).length === 0) return !1;
  switch (e.type) {
    case 1:
      for (let n = 0; n < e.props.length; n++) {
        const s = e.props[n];
        if (s.type === 7 && (Le(s.arg, t) || Le(s.exp, t))) return !0;
      }
      return e.children.some((n) => Le(n, t));
    case 11:
      return Le(e.source, t) ? !0 : e.children.some((n) => Le(n, t));
    case 9:
      return e.branches.some((n) => Le(n, t));
    case 10:
      return Le(e.condition, t) ? !0 : e.children.some((n) => Le(n, t));
    case 4:
      return !e.isStatic && qt(e.content) && !!t[e.content];
    case 8:
      return e.children.some((n) => mi(n) && Le(n, t));
    case 5:
    case 12:
      return Le(e.content, t);
    case 2:
    case 3:
    case 20:
      return !1;
    default:
      return !1;
  }
}
function Ui(e) {
  return e.type === 14 && e.callee === Ln ? e.arguments[1].returns : e;
}
const Wi = /([\s\S]*?)\s+(?:in|of)\s+(\S[\s\S]*)/;
function us(e) {
  for (let t = 0; t < e.length; t++) if (!be(e.charCodeAt(t))) return !1;
  return !0;
}
function Pn(e) {
  return (e.type === 2 && us(e.content)) || (e.type === 12 && Pn(e.content));
}
function fs(e) {
  return e.type === 3 || Pn(e);
}
const ji = {
  parseMode: "base",
  ns: 0,
  delimiters: ["{{", "}}"],
  getNamespace: () => 0,
  isVoidTag: Kt,
  isPreTag: Kt,
  isIgnoreNewlineTag: Kt,
  isCustomElement: Kt,
  onError: rs,
  onWarn: Li,
  comments: !1,
  prefixIdentifiers: !1
};
let V = ji,
  Ft = null,
  Xe = "",
  ue = null,
  P = null,
  _e = "",
  $e = -1,
  lt = -1,
  ds = 0,
  Je = !1,
  Fn = null;
const G = [],
  J = new Ho(G, {
    onerr: Ve,
    ontext(e, t) {
      Qt(oe(e, t), e, t);
    },
    ontextentity(e, t, n) {
      Qt(e, t, n);
    },
    oninterpolation(e, t) {
      if (Je) return Qt(oe(e, t), e, t);
      let n = e + J.delimiterOpen.length,
        s = t - J.delimiterClose.length;
      for (; be(Xe.charCodeAt(n)); ) n++;
      for (; be(Xe.charCodeAt(s - 1)); ) s--;
      let i = oe(n, s);
      (i.includes("&") && (i = V.decodeEntities(i, !1)),
        Un({ type: 5, content: sn(i, !1, Y(n, s)), loc: Y(e, t) }));
    },
    onopentagname(e, t) {
      const n = oe(e, t);
      ue = {
        type: 1,
        tag: n,
        ns: V.getNamespace(n, G[0], V.ns),
        tagType: 0,
        props: [],
        children: [],
        loc: Y(e - 1, t),
        codegenNode: void 0
      };
    },
    onopentagend(e) {
      Fs(e);
    },
    onclosetag(e, t) {
      const n = oe(e, t);
      if (!V.isVoidTag(n)) {
        let s = !1;
        for (let i = 0; i < G.length; i++)
          if (G[i].tag.toLowerCase() === n.toLowerCase()) {
            ((s = !0), i > 0 && Ve(24, G[0].loc.start.offset));
            for (let r = 0; r <= i; r++) {
              const l = G.shift();
              nn(l, t, r < i);
            }
            break;
          }
        s || Ve(23, qi(e, 60));
      }
    },
    onselfclosingtag(e) {
      const t = ue.tag;
      ((ue.isSelfClosing = !0), Fs(e), G[0] && G[0].tag === t && nn(G.shift(), e));
    },
    onattribname(e, t) {
      P = { type: 6, name: oe(e, t), nameLoc: Y(e, t), value: void 0, loc: Y(e) };
    },
    ondirname(e, t) {
      const n = oe(e, t),
        s = n === "." || n === ":" ? "bind" : n === "@" ? "on" : n === "#" ? "slot" : n.slice(2);
      if ((!Je && s === "" && Ve(26, e), Je || s === ""))
        P = { type: 6, name: n, nameLoc: Y(e, t), value: void 0, loc: Y(e) };
      else if (
        ((P = {
          type: 7,
          name: s,
          rawName: n,
          exp: void 0,
          arg: void 0,
          modifiers: n === "." ? [O("prop")] : [],
          loc: Y(e)
        }),
        s === "pre")
      ) {
        ((Je = J.inVPre = !0), (Fn = ue));
        const i = ue.props;
        for (let a = 0; a < i.length; a++) i[a].type === 7 && (i[a] = Sl(i[a]));
      }
    },
    ondirarg(e, t) {
      if (e === t) return;
      const n = oe(e, t);
      if (Je && !Xn(P)) ((P.name += n), ct(P.nameLoc, t));
      else {
        const s = n[0] !== "[";
        P.arg = sn(s ? n : n.slice(1, -1), s, Y(e, t), s ? 3 : 0);
      }
    },
    ondirmodifier(e, t) {
      const n = oe(e, t);
      if (Je && !Xn(P)) ((P.name += "." + n), ct(P.nameLoc, t));
      else if (P.name === "slot") {
        const s = P.arg;
        s && ((s.content += "." + n), ct(s.loc, t));
      } else {
        const s = O(n, !0, Y(e, t));
        P.modifiers.push(s);
      }
    },
    onattribdata(e, t) {
      ((_e += oe(e, t)), $e < 0 && ($e = e), (lt = t));
    },
    onattribentity(e, t, n) {
      ((_e += e), $e < 0 && ($e = t), (lt = n));
    },
    onattribnameend(e) {
      const t = P.loc.start.offset,
        n = oe(t, e);
      (P.type === 7 && (P.rawName = n),
        ue.props.some((s) => (s.type === 7 ? s.rawName : s.name) === n) && Ve(2, t));
    },
    onattribend(e, t) {
      if (ue && P) {
        if ((ct(P.loc, t), e !== 0))
          if ((_e.includes("&") && (_e = V.decodeEntities(_e, !0)), P.type === 6))
            (P.name === "class" && (_e = Ji(_e).trim()),
              e === 1 && !_e && Ve(13, t),
              (P.value = { type: 2, content: _e, loc: e === 1 ? Y($e, lt) : Y($e - 1, lt + 1) }),
              J.inSFCRoot &&
                ue.tag === "template" &&
                P.name === "lang" &&
                _e &&
                _e !== "html" &&
                J.enterRCDATA(dn("</template"), 0));
          else {
            let n = 0;
            ((P.exp = sn(_e, !1, Y($e, lt), 0, n)),
              P.name === "for" && (P.forParseResult = dl(P.exp)));
            let s = -1;
            P.name === "bind" &&
              (s = P.modifiers.findIndex((i) => i.content === "sync")) > -1 &&
              Ct("COMPILER_V_BIND_SYNC", V, P.loc, P.arg.loc.source) &&
              ((P.name = "model"), P.modifiers.splice(s, 1));
          }
        (P.type !== 7 || P.name !== "pre") && ue.props.push(P);
      }
      ((_e = ""), ($e = lt = -1));
    },
    oncomment(e, t) {
      V.comments && Un({ type: 3, content: oe(e, t), loc: Y(e - 4, t + 3) });
    },
    onend() {
      const e = Xe.length;
      for (let t = 0; t < G.length; t++) (nn(G[t], e - 1), Ve(24, G[t].loc.start.offset));
    },
    oncdata(e, t) {
      G[0].ns !== 0 ? Qt(oe(e, t), e, t) : Ve(1, e - 9);
    },
    onprocessinginstruction(e) {
      (G[0] ? G[0].ns : V.ns) === 0 && Ve(21, e - 1);
    }
  }),
  Xs = /,([^,\}\]]*)(?:,([^,\}\]]*))?$/,
  fl = /^\(|\)$/g;
function dl(e) {
  const t = e.loc,
    n = e.content,
    s = n.match(Wi);
  if (!s) return;
  const [, i, a] = s,
    r = (f, h, g = !1) => {
      const d = t.start.offset + h,
        p = d + f.length;
      return sn(f, !1, Y(d, p), 0, g ? 1 : 0);
    },
    l = {
      source: r(a.trim(), n.indexOf(a, i.length)),
      value: void 0,
      key: void 0,
      index: void 0,
      finalized: !1
    };
  let o = i.trim().replace(fl, "").trim();
  const c = i.indexOf(o),
    u = o.match(Xs);
  if (u) {
    o = o.replace(Xs, "").trim();
    const f = u[1].trim();
    let h;
    if ((f && ((h = n.indexOf(f, c + o.length)), (l.key = r(f, h, !0))), u[2])) {
      const g = u[2].trim();
      g && (l.index = r(g, n.indexOf(g, l.key ? h + f.length : c + o.length), !0));
    }
  }
  return (o && (l.value = r(o, c, !0)), l);
}
function oe(e, t) {
  return Xe.slice(e, t);
}
function Fs(e) {
  (J.inSFCRoot && (ue.innerLoc = Y(e + 1, e + 1)), Un(ue));
  const { tag: t, ns: n } = ue;
  (n === 0 && V.isPreTag(t) && ds++,
    V.isVoidTag(t) ? nn(ue, e) : (G.unshift(ue), (n === 1 || n === 2) && (J.inXML = !0)),
    (ue = null));
}
function Qt(e, t, n) {
  {
    const a = G[0] && G[0].tag;
    a !== "script" && a !== "style" && e.includes("&") && (e = V.decodeEntities(e, !1));
  }
  const s = G[0] || Ft,
    i = s.children[s.children.length - 1];
  i && i.type === 2
    ? ((i.content += e), ct(i.loc, n))
    : s.children.push({ type: 2, content: e, loc: Y(t, n) });
}
function nn(e, t, n = !1) {
  (n ? ct(e.loc, qi(t, 60)) : ct(e.loc, pl(t, 62) + 1),
    J.inSFCRoot &&
      (e.children.length
        ? (e.innerLoc.end = He({}, e.children[e.children.length - 1].loc.end))
        : (e.innerLoc.end = He({}, e.innerLoc.start)),
      (e.innerLoc.source = oe(e.innerLoc.start.offset, e.innerLoc.end.offset))));
  const { tag: s, ns: i, children: a } = e;
  if (
    (Je || (s === "slot" ? (e.tagType = 2) : Us(e) ? (e.tagType = 3) : ml(e) && (e.tagType = 1)),
    J.inRCDATA || (e.children = Gi(a)),
    i === 0 && V.isIgnoreNewlineTag(s))
  ) {
    const r = a[0];
    r && r.type === 2 && (r.content = r.content.replace(/^\r?\n/, ""));
  }
  (i === 0 && V.isPreTag(s) && ds--,
    Fn === e && ((Je = J.inVPre = !1), (Fn = null)),
    J.inXML && (G[0] ? G[0].ns : V.ns) === 0 && (J.inXML = !1));
  {
    const r = e.props;
    if (!J.inSFCRoot && ft("COMPILER_NATIVE_TEMPLATE", V) && e.tag === "template" && !Us(e)) {
      const o = G[0] || Ft,
        c = o.children.indexOf(e);
      o.children.splice(c, 1, ...e.children);
    }
    const l = r.find((o) => o.type === 6 && o.name === "inline-template");
    l &&
      Ct("COMPILER_INLINE_TEMPLATE", V, l.loc) &&
      e.children.length &&
      (l.value = {
        type: 2,
        content: oe(
          e.children[0].loc.start.offset,
          e.children[e.children.length - 1].loc.end.offset
        ),
        loc: l.loc
      });
  }
}
function pl(e, t) {
  let n = e;
  for (; Xe.charCodeAt(n) !== t && n < Xe.length - 1; ) n++;
  return n;
}
function qi(e, t) {
  let n = e;
  for (; Xe.charCodeAt(n) !== t && n >= 0; ) n--;
  return n;
}
const hl = new Set(["if", "else", "else-if", "for", "slot"]);
function Us({ tag: e, props: t }) {
  if (e === "template") {
    for (let n = 0; n < t.length; n++) if (t[n].type === 7 && hl.has(t[n].name)) return !0;
  }
  return !1;
}
function ml({ tag: e, props: t }) {
  if (V.isCustomElement(e)) return !1;
  if (
    e === "component" ||
    gl(e.charCodeAt(0)) ||
    as(e) ||
    (V.isBuiltInComponent && V.isBuiltInComponent(e)) ||
    (V.isNativeTag && !V.isNativeTag(e))
  )
    return !0;
  for (let n = 0; n < t.length; n++) {
    const s = t[n];
    if (s.type === 6) {
      if (s.name === "is" && s.value) {
        if (s.value.content.startsWith("vue:")) return !0;
        if (Ct("COMPILER_IS_ON_ELEMENT", V, s.loc)) return !0;
      }
    } else if (s.name === "bind" && Ke(s.arg, "is") && Ct("COMPILER_IS_ON_ELEMENT", V, s.loc))
      return !0;
  }
  return !1;
}
function gl(e) {
  return e > 64 && e < 91;
}
const _l = /\r\n/g;
function Gi(e) {
  const t = V.whitespace !== "preserve";
  let n = !1;
  for (let s = 0; s < e.length; s++) {
    const i = e[s];
    if (i.type === 2)
      if (ds)
        i.content = i.content.replace(
          _l,
          `
`
        );
      else if (us(i.content)) {
        const a = e[s - 1] && e[s - 1].type,
          r = e[s + 1] && e[s + 1].type;
        !a ||
        !r ||
        (t &&
          ((a === 3 && (r === 3 || r === 1)) ||
            (a === 1 && (r === 3 || (r === 1 && vl(i.content))))))
          ? ((n = !0), (e[s] = null))
          : (i.content = " ");
      } else t && (i.content = Ji(i.content));
  }
  return n ? e.filter(Boolean) : e;
}
function vl(e) {
  for (let t = 0; t < e.length; t++) {
    const n = e.charCodeAt(t);
    if (n === 10 || n === 13) return !0;
  }
  return !1;
}
function Ji(e) {
  let t = "",
    n = !1;
  for (let s = 0; s < e.length; s++)
    be(e.charCodeAt(s)) ? n || ((t += " "), (n = !0)) : ((t += e[s]), (n = !1));
  return t;
}
function Un(e) {
  (G[0] || Ft).children.push(e);
}
function Y(e, t) {
  return { start: J.getPos(e), end: t == null ? t : J.getPos(t), source: t == null ? t : oe(e, t) };
}
function El(e) {
  return Y(e.start.offset, e.end.offset);
}
function ct(e, t) {
  ((e.end = J.getPos(t)), (e.source = oe(e.start.offset, t)));
}
function Sl(e) {
  const t = {
    type: 6,
    name: e.rawName,
    nameLoc: Y(e.loc.start.offset, e.loc.start.offset + e.rawName.length),
    value: void 0,
    loc: e.loc
  };
  if (e.exp) {
    const n = e.exp.loc;
    (n.end.offset < e.loc.end.offset &&
      (n.start.offset--, n.start.column--, n.end.offset++, n.end.column++),
      (t.value = { type: 2, content: e.exp.content, loc: n }));
  }
  return t;
}
function sn(e, t = !1, n, s = 0, i = 0) {
  return O(e, t, n, s);
}
function Ve(e, t, n) {
  V.onError(U(e, Y(t, t)));
}
function yl() {
  (J.reset(), (ue = null), (P = null), (_e = ""), ($e = -1), (lt = -1), (G.length = 0));
}
function ps(e, t) {
  if ((yl(), (Xe = e), (V = He({}, ji)), t)) {
    let i;
    for (i in t) t[i] != null && (V[i] = t[i]);
  }
  ((J.mode = V.parseMode === "html" ? 1 : V.parseMode === "sfc" ? 2 : 0),
    (J.inXML = V.ns === 1 || V.ns === 2));
  const n = t && t.delimiters;
  n && ((J.delimiterOpen = dn(n[0])), (J.delimiterClose = dn(n[1])));
  const s = (Ft = Mi([], e));
  return (J.parse(Xe), (s.loc = Y(0, e.length)), (s.children = Gi(s.children)), (Ft = null), s);
}
function bl(e, t) {
  rn(e, void 0, t, !!Yi(e));
}
function Yi(e) {
  const t = e.children.filter((n) => n.type !== 3);
  return t.length === 1 && t[0].type === 1 && !zt(t[0]) ? t[0] : null;
}
function rn(e, t, n, s = !1, i = !1) {
  const { children: a } = e,
    r = [];
  for (let u = 0; u < a.length; u++) {
    const f = a[u];
    if (f.type === 1 && f.tagType === 0) {
      const h = s ? 0 : Ee(f, n);
      if (h > 0) {
        if (h >= 2) {
          ((f.codegenNode.patchFlag = -1), r.push(f));
          continue;
        }
      } else {
        const g = f.codegenNode;
        if (g.type === 13) {
          const d = g.patchFlag;
          if ((d === void 0 || d === 512 || d === 1) && Qi(f, n) >= 2) {
            const p = Zi(f);
            p && (g.props = n.hoist(p));
          }
          g.dynamicProps && (g.dynamicProps = n.hoist(g.dynamicProps));
        }
      }
    } else if (f.type === 12 && (s ? 0 : Ee(f, n)) >= 2) {
      (f.codegenNode.type === 14 &&
        f.codegenNode.arguments.length > 0 &&
        f.codegenNode.arguments.push("-1"),
        r.push(f));
      continue;
    }
    if (f.type === 1) {
      const h = f.tagType === 1;
      (h && n.scopes.vSlot++, rn(f, e, n, !1, i), h && n.scopes.vSlot--);
    } else if (f.type === 11) rn(f, e, n, f.children.length === 1, !0);
    else if (f.type === 9)
      for (let h = 0; h < f.branches.length; h++)
        rn(f.branches[h], e, n, f.branches[h].children.length === 1, i);
  }
  let l = !1;
  if (r.length === a.length && e.type === 1) {
    if (e.tagType === 0 && e.codegenNode && e.codegenNode.type === 13 && Ye(e.codegenNode.children))
      ((e.codegenNode.children = o(Ze(e.codegenNode.children))), (l = !0));
    else if (
      e.tagType === 1 &&
      e.codegenNode &&
      e.codegenNode.type === 13 &&
      e.codegenNode.children &&
      !Ye(e.codegenNode.children) &&
      e.codegenNode.children.type === 15
    ) {
      const u = c(e.codegenNode, "default");
      u && ((u.returns = o(Ze(u.returns))), (l = !0));
    } else if (
      e.tagType === 3 &&
      t &&
      t.type === 1 &&
      t.tagType === 1 &&
      t.codegenNode &&
      t.codegenNode.type === 13 &&
      t.codegenNode.children &&
      !Ye(t.codegenNode.children) &&
      t.codegenNode.children.type === 15
    ) {
      const u = he(e, "slot", !0),
        f = u && u.arg && c(t.codegenNode, u.arg);
      f && ((f.returns = o(Ze(f.returns))), (l = !0));
    }
  }
  if (!l) for (const u of r) u.codegenNode = n.cache(u.codegenNode);
  function o(u) {
    const f = n.cache(u);
    return ((f.needArraySpread = !0), f);
  }
  function c(u, f) {
    if (u.children && !Ye(u.children) && u.children.type === 15) {
      const h = u.children.properties.find((g) => g.key === f || g.key.content === f);
      return h && h.value;
    }
  }
  r.length && n.transformHoist && n.transformHoist(a, n, e);
}
function Ee(e, t) {
  const { constantCache: n } = t;
  switch (e.type) {
    case 1:
      if (e.tagType !== 0) return 0;
      const s = n.get(e);
      if (s !== void 0) return s;
      const i = e.codegenNode;
      if (
        i.type !== 13 ||
        (i.isBlock && e.tag !== "svg" && e.tag !== "foreignObject" && e.tag !== "math")
      )
        return 0;
      if (i.patchFlag === void 0) {
        let r = 3;
        const l = Qi(e, t);
        if (l === 0) return (n.set(e, 0), 0);
        l < r && (r = l);
        for (let o = 0; o < e.children.length; o++) {
          const c = Ee(e.children[o], t);
          if (c === 0) return (n.set(e, 0), 0);
          c < r && (r = c);
        }
        if (r > 1)
          for (let o = 0; o < e.props.length; o++) {
            const c = e.props[o];
            if (c.type === 7 && c.name === "bind" && c.exp) {
              const u = Ee(c.exp, t);
              if (u === 0) return (n.set(e, 0), 0);
              u < r && (r = u);
            }
          }
        if (i.isBlock) {
          for (let o = 0; o < e.props.length; o++)
            if (e.props[o].type === 7) return (n.set(e, 0), 0);
          (t.removeHelper(tt),
            t.removeHelper(mt(t.inSSR, i.isComponent)),
            (i.isBlock = !1),
            t.helper(ht(t.inSSR, i.isComponent)));
        }
        return (n.set(e, r), r);
      } else return (n.set(e, 0), 0);
    case 2:
    case 3:
      return 3;
    case 9:
    case 11:
    case 10:
      return 0;
    case 5:
    case 12:
      return Ee(e.content, t);
    case 4:
      return e.constType;
    case 8:
      let a = 3;
      for (let r = 0; r < e.children.length; r++) {
        const l = e.children[r];
        if (ne(l) || Yn(l)) continue;
        const o = Ee(l, t);
        if (o === 0) return 0;
        o < a && (a = o);
      }
      return a;
    case 20:
      return 2;
    default:
      return 0;
  }
}
const Tl = new Set([An, Mn, Nt, Rt]);
function Ki(e, t) {
  if (e.type === 14 && !ne(e.callee) && Tl.has(e.callee)) {
    const n = e.arguments[0];
    if (n.type === 4) return Ee(n, t);
    if (n.type === 14) return Ki(n, t);
  }
  return 0;
}
function Qi(e, t) {
  let n = 3;
  const s = Zi(e);
  if (s && s.type === 15) {
    const { properties: i } = s;
    for (let a = 0; a < i.length; a++) {
      const { key: r, value: l } = i[a],
        o = Ee(r, t);
      if (o === 0) return o;
      o < n && (n = o);
      let c;
      if ((l.type === 4 ? (c = Ee(l, t)) : l.type === 14 ? (c = Ki(l, t)) : (c = 0), c === 0))
        return c;
      c < n && (n = c);
    }
  }
  return n;
}
function Zi(e) {
  const t = e.codegenNode;
  if (t.type === 13) return t.props;
}
function er(
  e,
  {
    filename: t = "",
    prefixIdentifiers: n = !1,
    hoistStatic: s = !1,
    hmr: i = !1,
    cacheHandlers: a = !1,
    nodeTransforms: r = [],
    directiveTransforms: l = {},
    transformHoist: o = null,
    isBuiltInComponent: c = Vt,
    isCustomElement: u = Vt,
    expressionPlugins: f = [],
    scopeId: h = null,
    slotted: g = !0,
    ssr: d = !1,
    inSSR: p = !1,
    ssrCssVars: E = "",
    bindingMetadata: S = Qr,
    inline: _ = !1,
    isTS: b = !1,
    onError: A = rs,
    onWarn: x = Li,
    compatConfig: q
  }
) {
  const H = t.replace(/\?.*$/, "").match(/([^/\\]+)\.\w+$/),
    T = {
      filename: t,
      selfName: H && hi(Qe(H[1])),
      prefixIdentifiers: n,
      hoistStatic: s,
      hmr: i,
      cacheHandlers: a,
      nodeTransforms: r,
      directiveTransforms: l,
      transformHoist: o,
      isBuiltInComponent: c,
      isCustomElement: u,
      expressionPlugins: f,
      scopeId: h,
      slotted: g,
      ssr: d,
      inSSR: p,
      ssrCssVars: E,
      bindingMetadata: S,
      inline: _,
      isTS: b,
      onError: A,
      onWarn: x,
      compatConfig: q,
      root: e,
      helpers: new Map(),
      components: new Set(),
      directives: new Set(),
      hoists: [],
      imports: [],
      cached: [],
      constantCache: new WeakMap(),
      temps: 0,
      identifiers: Object.create(null),
      scopes: { vFor: 0, vSlot: 0, vPre: 0, vOnce: 0 },
      parent: null,
      grandParent: null,
      currentNode: e,
      childIndex: 0,
      inVOnce: !1,
      helper(y) {
        const C = T.helpers.get(y) || 0;
        return (T.helpers.set(y, C + 1), y);
      },
      removeHelper(y) {
        const C = T.helpers.get(y);
        if (C) {
          const N = C - 1;
          N ? T.helpers.set(y, N) : T.helpers.delete(y);
        }
      },
      helperString(y) {
        return `_${dt[T.helper(y)]}`;
      },
      replaceNode(y) {
        T.parent.children[T.childIndex] = T.currentNode = y;
      },
      removeNode(y) {
        const C = T.parent.children,
          N = y ? C.indexOf(y) : T.currentNode ? T.childIndex : -1;
        (!y || y === T.currentNode
          ? ((T.currentNode = null), T.onNodeRemoved())
          : T.childIndex > N && (T.childIndex--, T.onNodeRemoved()),
          T.parent.children.splice(N, 1));
      },
      onNodeRemoved: Vt,
      addIdentifiers(y) {},
      removeIdentifiers(y) {},
      hoist(y) {
        (ne(y) && (y = O(y)), T.hoists.push(y));
        const C = O(`_hoisted_${T.hoists.length}`, !1, y.loc, 2);
        return ((C.hoisted = y), C);
      },
      cache(y, C = !1, N = !1) {
        const I = Ri(T.cached.length, y, C, N);
        return (T.cached.push(I), I);
      }
    };
  return ((T.filters = new Set()), T);
}
function tr(e, t) {
  const n = er(e, t);
  (Jt(e, n),
    t.hoistStatic && bl(e, n),
    t.ssr || Nl(e, n),
    (e.helpers = new Set([...n.helpers.keys()])),
    (e.components = [...n.components]),
    (e.directives = [...n.directives]),
    (e.imports = n.imports),
    (e.hoists = n.hoists),
    (e.temps = n.temps),
    (e.cached = n.cached),
    (e.transformed = !0),
    (e.filters = [...n.filters]));
}
function Nl(e, t) {
  const { helper: n } = t,
    { children: s } = e;
  if (s.length === 1) {
    const i = Yi(e);
    if (i && i.codegenNode) {
      const a = i.codegenNode;
      (a.type === 13 && Dn(a, t), (e.codegenNode = a));
    } else e.codegenNode = s[0];
  } else if (s.length > 1) {
    let i = 64;
    e.codegenNode = It(t, n(Tt), void 0, e.children, i, void 0, void 0, !0, void 0, !1);
  }
}
function Il(e, t) {
  let n = 0;
  const s = () => {
    n--;
  };
  for (; n < e.children.length; n++) {
    const i = e.children[n];
    ne(i) ||
      ((t.grandParent = t.parent),
      (t.parent = e),
      (t.childIndex = n),
      (t.onNodeRemoved = s),
      Jt(i, t));
  }
}
function Jt(e, t) {
  t.currentNode = e;
  const { nodeTransforms: n } = t,
    s = [];
  for (let a = 0; a < n.length; a++) {
    const r = n[a](e, t);
    if ((r && (Ye(r) ? s.push(...r) : s.push(r)), t.currentNode)) e = t.currentNode;
    else return;
  }
  switch (e.type) {
    case 3:
      t.ssr || t.helper(Mt);
      break;
    case 5:
      t.ssr || t.helper(jt);
      break;
    case 9:
      for (let a = 0; a < e.branches.length; a++) Jt(e.branches[a], t);
      break;
    case 10:
    case 11:
    case 1:
    case 0:
      Il(e, t);
      break;
  }
  t.currentNode = e;
  let i = s.length;
  for (; i--; ) s[i]();
}
function hs(e, t) {
  const n = ne(e) ? (s) => s === e : (s) => e.test(s);
  return (s, i) => {
    if (s.type === 1) {
      const { props: a } = s;
      if (s.tagType === 3 && a.some(cs)) return;
      const r = [];
      for (let l = 0; l < a.length; l++) {
        const o = a[l];
        if (o.type === 7 && n(o.name)) {
          (a.splice(l, 1), l--);
          const c = t(s, o, i);
          c && r.push(c);
        }
      }
      return r;
    }
  };
}
const xn = "/*@__PURE__*/",
  nr = (e) => `${dt[e]}: _${dt[e]}`;
function Cl(
  e,
  {
    mode: t = "function",
    prefixIdentifiers: n = t === "module",
    sourceMap: s = !1,
    filename: i = "template.vue.html",
    scopeId: a = null,
    optimizeImports: r = !1,
    runtimeGlobalName: l = "Vue",
    runtimeModuleName: o = "vue",
    ssrRuntimeModuleName: c = "vue/server-renderer",
    ssr: u = !1,
    isTS: f = !1,
    inSSR: h = !1
  }
) {
  const g = {
    mode: t,
    prefixIdentifiers: n,
    sourceMap: s,
    filename: i,
    scopeId: a,
    optimizeImports: r,
    runtimeGlobalName: l,
    runtimeModuleName: o,
    ssrRuntimeModuleName: c,
    ssr: u,
    isTS: f,
    inSSR: h,
    source: e.source,
    code: "",
    column: 1,
    line: 1,
    offset: 0,
    indentLevel: 0,
    pure: !1,
    map: void 0,
    helper(p) {
      return `_${dt[p]}`;
    },
    push(p, E = -2, S) {
      g.code += p;
    },
    indent() {
      d(++g.indentLevel);
    },
    deindent(p = !1) {
      p ? --g.indentLevel : d(--g.indentLevel);
    },
    newline() {
      d(g.indentLevel);
    }
  };
  function d(p) {
    g.push(
      `
` + "  ".repeat(p),
      0
    );
  }
  return g;
}
function sr(e, t = {}) {
  const n = Cl(e, t);
  t.onContextCreated && t.onContextCreated(n);
  const {
      mode: s,
      push: i,
      prefixIdentifiers: a,
      indent: r,
      deindent: l,
      newline: o,
      scopeId: c,
      ssr: u
    } = n,
    f = Array.from(e.helpers),
    h = f.length > 0,
    g = !a && s !== "module";
  Ol(e, n);
  const p = u ? "ssrRender" : "render",
    S = (u ? ["_ctx", "_push", "_parent", "_attrs"] : ["_ctx", "_cache"]).join(", ");
  if (
    (i(`function ${p}(${S}) {`),
    r(),
    g &&
      (i("with (_ctx) {"),
      r(),
      h &&
        (i(
          `const { ${f.map(nr).join(", ")} } = _Vue
`,
          -1
        ),
        o())),
    e.components.length &&
      (Bn(e.components, "component", n), (e.directives.length || e.temps > 0) && o()),
    e.directives.length && (Bn(e.directives, "directive", n), e.temps > 0 && o()),
    e.filters && e.filters.length && (o(), Bn(e.filters, "filter", n), o()),
    e.temps > 0)
  ) {
    i("let ");
    for (let _ = 0; _ < e.temps; _++) i(`${_ > 0 ? ", " : ""}_temp${_}`);
  }
  return (
    (e.components.length || e.directives.length || e.temps) &&
      (i(
        `
`,
        0
      ),
      o()),
    u || i("return "),
    e.codegenNode ? fe(e.codegenNode, n) : i("null"),
    g && (l(), i("}")),
    l(),
    i("}"),
    { ast: e, code: n.code, preamble: "", map: n.map ? n.map.toJSON() : void 0 }
  );
}
function Ol(e, t) {
  const {
      ssr: n,
      prefixIdentifiers: s,
      push: i,
      newline: a,
      runtimeModuleName: r,
      runtimeGlobalName: l,
      ssrRuntimeModuleName: o
    } = t,
    c = l,
    u = Array.from(e.helpers);
  if (
    u.length > 0 &&
    (i(
      `const _Vue = ${c}
`,
      -1
    ),
    e.hoists.length)
  ) {
    const f = [En, Sn, Mt, yn, ts]
      .filter((h) => u.includes(h))
      .map(nr)
      .join(", ");
    i(
      `const { ${f} } = _Vue
`,
      -1
    );
  }
  (Al(e.hoists, t), a(), i("return "));
}
function Bn(e, t, { helper: n, push: s, newline: i, isTS: a }) {
  const r = n(t === "filter" ? In : t === "component" ? bn : Nn);
  for (let l = 0; l < e.length; l++) {
    let o = e[l];
    const c = o.endsWith("__self");
    (c && (o = o.slice(0, -6)),
      s(`const ${At(o, t)} = ${r}(${JSON.stringify(o)}${c ? ", true" : ""})${a ? "!" : ""}`),
      l < e.length - 1 && i());
  }
}
function Al(e, t) {
  if (!e.length) return;
  t.pure = !0;
  const { push: n, newline: s } = t;
  s();
  for (let i = 0; i < e.length; i++) {
    const a = e[i];
    a && (n(`const _hoisted_${i + 1} = `), fe(a, t), s());
  }
  t.pure = !1;
}
function ms(e, t) {
  const n = e.length > 3 || !1;
  (t.push("["), n && t.indent(), Yt(e, t, n), n && t.deindent(), t.push("]"));
}
function Yt(e, t, n = !1, s = !0) {
  const { push: i, newline: a } = t;
  for (let r = 0; r < e.length; r++) {
    const l = e[r];
    (ne(l) ? i(l, -3) : Ye(l) ? ms(l, t) : fe(l, t),
      r < e.length - 1 && (n ? (s && i(","), a()) : s && i(", ")));
  }
}
function fe(e, t) {
  if (ne(e)) {
    t.push(e, -3);
    return;
  }
  if (Yn(e)) {
    t.push(t.helper(e));
    return;
  }
  switch (e.type) {
    case 1:
    case 9:
    case 11:
      fe(e.codegenNode, t);
      break;
    case 2:
      Ml(e, t);
      break;
    case 4:
      ir(e, t);
      break;
    case 5:
      Rl(e, t);
      break;
    case 12:
      fe(e.codegenNode, t);
      break;
    case 8:
      rr(e, t);
      break;
    case 3:
      Ll(e, t);
      break;
    case 13:
      Dl(e, t);
      break;
    case 14:
      xl(e, t);
      break;
    case 15:
      kl(e, t);
      break;
    case 17:
      Vl(e, t);
      break;
    case 18:
      $l(e, t);
      break;
    case 19:
      Bl(e, t);
      break;
    case 20:
      Hl(e, t);
      break;
    case 21:
      Yt(e.body, t, !0, !1);
      break;
  }
}
function Ml(e, t) {
  t.push(JSON.stringify(e.content), -3, e);
}
function ir(e, t) {
  const { content: n, isStatic: s } = e;
  t.push(s ? JSON.stringify(n) : n, -3, e);
}
function Rl(e, t) {
  const { push: n, helper: s, pure: i } = t;
  (i && n(xn), n(`${s(jt)}(`), fe(e.content, t), n(")"));
}
function rr(e, t) {
  for (let n = 0; n < e.children.length; n++) {
    const s = e.children[n];
    ne(s) ? t.push(s, -3) : fe(s, t);
  }
}
function wl(e, t) {
  const { push: n } = t;
  if (e.type === 8) (n("["), rr(e, t), n("]"));
  else if (e.isStatic) {
    const s = qt(e.content) ? e.content : JSON.stringify(e.content);
    n(s, -2, e);
  } else n(`[${e.content}]`, -3, e);
}
function Ll(e, t) {
  const { push: n, helper: s, pure: i } = t;
  (i && n(xn), n(`${s(Mt)}(${JSON.stringify(e.content)})`, -3, e));
}
function Dl(e, t) {
  const { push: n, helper: s, pure: i } = t,
    {
      tag: a,
      props: r,
      children: l,
      patchFlag: o,
      dynamicProps: c,
      directives: u,
      isBlock: f,
      disableTracking: h,
      isComponent: g
    } = e;
  let d;
  (o && (d = String(o)),
    u && n(s(Cn) + "("),
    f && n(`(${s(tt)}(${h ? "true" : ""}), `),
    i && n(xn));
  const p = f ? mt(t.inSSR, g) : ht(t.inSSR, g);
  (n(s(p) + "(", -2, e),
    Yt(Pl([a, r, l, d, c]), t),
    n(")"),
    f && n(")"),
    u && (n(", "), fe(u, t), n(")")));
}
function Pl(e) {
  let t = e.length;
  for (; t-- && e[t] == null; );
  return e.slice(0, t + 1).map((n) => n || "null");
}
function xl(e, t) {
  const { push: n, helper: s, pure: i } = t,
    a = ne(e.callee) ? e.callee : s(e.callee);
  (i && n(xn), n(a + "(", -2, e), Yt(e.arguments, t), n(")"));
}
function kl(e, t) {
  const { push: n, indent: s, deindent: i, newline: a } = t,
    { properties: r } = e;
  if (!r.length) {
    n("{}", -2, e);
    return;
  }
  const l = r.length > 1 || !1;
  (n(l ? "{" : "{ "), l && s());
  for (let o = 0; o < r.length; o++) {
    const { key: c, value: u } = r[o];
    (wl(c, t), n(": "), fe(u, t), o < r.length - 1 && (n(","), a()));
  }
  (l && i(), n(l ? "}" : " }"));
}
function Vl(e, t) {
  ms(e.elements, t);
}
function $l(e, t) {
  const { push: n, indent: s, deindent: i } = t,
    { params: a, returns: r, body: l, newline: o, isSlot: c } = e;
  (c && n(`_${dt[wn]}(`),
    n("(", -2, e),
    Ye(a) ? Yt(a, t) : a && fe(a, t),
    n(") => "),
    (o || l) && (n("{"), s()),
    r ? (o && n("return "), Ye(r) ? ms(r, t) : fe(r, t)) : l && fe(l, t),
    (o || l) && (i(), n("}")),
    c && (e.isNonScopedSlot && n(", undefined, true"), n(")")));
}
function Bl(e, t) {
  const { test: n, consequent: s, alternate: i, newline: a } = e,
    { push: r, indent: l, deindent: o, newline: c } = t;
  if (n.type === 4) {
    const f = !qt(n.content);
    (f && r("("), ir(n, t), f && r(")"));
  } else (r("("), fe(n, t), r(")"));
  (a && l(),
    t.indentLevel++,
    a || r(" "),
    r("? "),
    fe(s, t),
    t.indentLevel--,
    a && c(),
    a || r(" "),
    r(": "));
  const u = i.type === 19;
  (u || t.indentLevel++, fe(i, t), u || t.indentLevel--, a && o(!0));
}
function Hl(e, t) {
  const { push: n, helper: s, indent: i, deindent: a, newline: r } = t,
    { needPauseTracking: l, needArraySpread: o } = e;
  (o && n("[...("),
    n(`_cache[${e.index}] || (`),
    l && (i(), n(`${s(Ht)}(-1`), e.inVOnce && n(", true"), n("),"), r(), n("(")),
    n(`_cache[${e.index}] = `),
    fe(e.value, t),
    l &&
      (n(`).cacheIndex = ${e.index},`), r(), n(`${s(Ht)}(1),`), r(), n(`_cache[${e.index}]`), a()),
    n(")"),
    o && n(")]"));
}
new RegExp(
  "\\b" +
    "arguments,await,break,case,catch,class,const,continue,debugger,default,delete,do,else,export,extends,finally,for,function,if,import,let,new,return,super,switch,throw,try,var,void,while,with,yield"
      .split(",")
      .join("\\b|\\b") +
    "\\b"
);
const zl = (e, t) => {
  if (e.type === 5) e.content = an(e.content, t);
  else if (e.type === 1) {
    const n = he(e, "memo");
    for (let s = 0; s < e.props.length; s++) {
      const i = e.props[s];
      if (i.type === 7 && i.name !== "for") {
        const a = i.exp,
          r = i.arg;
        (a &&
          a.type === 4 &&
          !(i.name === "on" && r) &&
          !(n && r && r.type === 4 && r.content === "key") &&
          (i.exp = an(a, t, i.name === "slot")),
          r && r.type === 4 && !r.isStatic && (i.arg = an(r, t)));
      }
    }
  }
};
function an(e, t, n = !1, s = !1, i = Object.create(t.identifiers)) {
  return e;
}
function ar(e) {
  return ne(e) ? e : e.type === 4 ? e.content : e.children.map(ar).join("");
}
const Xl = hs(/^(?:if|else|else-if)$/, (e, t, n) =>
  or(e, t, n, (s, i, a) => {
    const r = n.parent.children;
    let l = r.indexOf(s),
      o = 0;
    for (; l-- >= 0; ) {
      const c = r[l];
      c && c.type === 9 && (o += c.branches.length);
    }
    return () => {
      if (a) s.codegenNode = js(i, o, n);
      else {
        const c = Fl(s.codegenNode);
        c.alternate = js(i, o + s.branches.length - 1, n);
      }
    };
  })
);
function or(e, t, n, s) {
  if (t.name !== "else" && (!t.exp || !t.exp.content.trim())) {
    const i = t.exp ? t.exp.loc : e.loc;
    (n.onError(U(28, t.loc)), (t.exp = O("true", !1, i)));
  }
  if (t.name === "if") {
    const i = Ws(e, t),
      a = { type: 9, loc: El(e.loc), branches: [i] };
    if ((n.replaceNode(a), s)) return s(a, i, !0);
  } else {
    const i = n.parent.children;
    let a = i.indexOf(e);
    for (; a-- >= -1; ) {
      const r = i[a];
      if (r && fs(r)) {
        n.removeNode(r);
        continue;
      }
      if (r && r.type === 9) {
        ((t.name === "else-if" || t.name === "else") &&
          r.branches[r.branches.length - 1].condition === void 0 &&
          n.onError(U(30, e.loc)),
          n.removeNode());
        const l = Ws(e, t);
        r.branches.push(l);
        const o = s && s(r, l, !1);
        (Jt(l, n), o && o(), (n.currentNode = null));
      } else n.onError(U(30, e.loc));
      break;
    }
  }
}
function Ws(e, t) {
  const n = e.tagType === 3;
  return {
    type: 10,
    loc: e.loc,
    condition: t.name === "else" ? void 0 : t.exp,
    children: n && !he(e, "for") ? e.children : [e],
    userKey: Gt(e, "key"),
    isTemplateIf: n
  };
}
function js(e, t, n) {
  return e.condition ? fn(e.condition, qs(e, t, n), Q(n.helper(Mt), ['""', "true"])) : qs(e, t, n);
}
function qs(e, t, n) {
  const { helper: s } = n,
    i = K("key", O(`${t}`, !1, se, 2)),
    { children: a } = e,
    r = a[0];
  if (a.length !== 1 || r.type !== 1)
    if (a.length === 1 && r.type === 11) {
      const o = r.codegenNode;
      return (Xt(o, i, n), o);
    } else return It(n, s(Tt), Ne([i]), a, 64, void 0, void 0, !0, !1, !1, e.loc);
  else {
    const o = r.codegenNode,
      c = Ui(o);
    return (c.type === 13 && Dn(c, n), Xt(c, i, n), o);
  }
}
function Fl(e) {
  for (;;)
    if (e.type === 19)
      if (e.alternate.type === 19) e = e.alternate;
      else return e;
    else e.type === 20 && (e = e.value);
}
const Ul = hs("for", (e, t, n) => {
  const { helper: s, removeHelper: i } = n;
  return lr(e, t, n, (a) => {
    const r = Q(s(On), [a.source]),
      l = Ot(e),
      o = he(e, "memo"),
      c = Gt(e, "key", !1, !0);
    c && c.type;
    let u = c && (c.type === 6 ? (c.value ? O(c.value.content, !0) : void 0) : c.exp);
    const f = c && u ? K("key", u) : null,
      h = a.source.type === 4 && a.source.constType > 0,
      g = h ? 64 : c ? 128 : 256;
    return (
      (a.codegenNode = It(n, s(Tt), void 0, r, g, void 0, void 0, !0, !h, !1, e.loc)),
      () => {
        let d;
        const { children: p } = a,
          E = p.length !== 1 || p[0].type !== 1,
          S = zt(e) ? e : l && e.children.length === 1 && zt(e.children[0]) ? e.children[0] : null;
        if (
          (S
            ? ((d = S.codegenNode), l && f && Xt(d, f, n))
            : E
              ? (d = It(
                  n,
                  s(Tt),
                  f ? Ne([f]) : void 0,
                  e.children,
                  64,
                  void 0,
                  void 0,
                  !0,
                  void 0,
                  !1
                ))
              : ((d = p[0].codegenNode),
                l && f && Xt(d, f, n),
                d.isBlock !== !h &&
                  (d.isBlock
                    ? (i(tt), i(mt(n.inSSR, d.isComponent)))
                    : i(ht(n.inSSR, d.isComponent))),
                (d.isBlock = !h),
                d.isBlock ? (s(tt), s(mt(n.inSSR, d.isComponent))) : s(ht(n.inSSR, d.isComponent))),
          o)
        ) {
          const _ = pt(pn(a.parseResult, [O("_cached")]));
          ((_.body = wi([
            we(["const _memo = (", o.exp, ")"]),
            we([
              "if (_cached",
              ...(u ? [" && _cached.key === ", u] : []),
              ` && ${n.helperString(is)}(_cached, _memo)) return _cached`
            ]),
            we(["const _item = ", d]),
            O("_item.memo = _memo"),
            O("return _item")
          ])),
            r.arguments.push(_, O("_cache"), O(String(n.cached.length))),
            n.cached.push(null));
        } else r.arguments.push(pt(pn(a.parseResult), d, !0));
      }
    );
  });
});
function lr(e, t, n, s) {
  if (!t.exp) {
    n.onError(U(31, t.loc));
    return;
  }
  const i = t.forParseResult;
  if (!i) {
    n.onError(U(32, t.loc));
    return;
  }
  gs(i);
  const { addIdentifiers: a, removeIdentifiers: r, scopes: l } = n,
    { source: o, value: c, key: u, index: f } = i,
    h = {
      type: 11,
      loc: t.loc,
      source: o,
      valueAlias: c,
      keyAlias: u,
      objectIndexAlias: f,
      parseResult: i,
      children: Ot(e) ? e.children : [e]
    };
  (n.replaceNode(h), l.vFor++);
  const g = s && s(h);
  return () => {
    (l.vFor--, g && g());
  };
}
function gs(e, t) {
  e.finalized || (e.finalized = !0);
}
function pn({ value: e, key: t, index: n }, s = []) {
  return Wl([e, t, n, ...s]);
}
function Wl(e) {
  let t = e.length;
  for (; t-- && !e[t]; );
  return e.slice(0, t + 1).map((n, s) => n || O("_".repeat(s + 1), !1));
}
const Gs = O("undefined", !1),
  cr = (e, t) => {
    if (e.type === 1 && (e.tagType === 1 || e.tagType === 3)) {
      const n = he(e, "slot");
      if (n)
        return (
          n.exp,
          t.scopes.vSlot++,
          () => {
            t.scopes.vSlot--;
          }
        );
    }
  },
  jl = (e, t) => {
    let n;
    if (Ot(e) && e.props.some(cs) && (n = he(e, "for"))) {
      const s = n.forParseResult;
      if (s) {
        gs(s);
        const { value: i, key: a, index: r } = s,
          { addIdentifiers: l, removeIdentifiers: o } = t;
        return (
          i && l(i),
          a && l(a),
          r && l(r),
          () => {
            (i && o(i), a && o(a), r && o(r));
          }
        );
      }
    }
  },
  ql = (e, t, n, s) => pt(e, n, !1, !0, n.length ? n[0].loc : s);
function ur(e, t, n = ql) {
  t.helper(wn);
  const { children: s, loc: i } = e,
    a = [],
    r = [];
  let l = t.scopes.vSlot > 0 || t.scopes.vFor > 0;
  const o = he(e, "slot", !0);
  if (o) {
    const { arg: E, exp: S } = o;
    (E && !ge(E) && (l = !0), a.push(K(E || O("default", !0), n(S, void 0, s, i))));
  }
  let c = !1,
    u = !1;
  const f = [],
    h = new Set();
  let g = 0;
  for (let E = 0; E < s.length; E++) {
    const S = s[E];
    let _;
    if (!Ot(S) || !(_ = he(S, "slot", !0))) {
      S.type !== 3 && f.push(S);
      continue;
    }
    if (o) {
      t.onError(U(37, _.loc));
      break;
    }
    c = !0;
    const { children: b, loc: A } = S,
      { arg: x = O("default", !0), exp: q, loc: H } = _;
    let T;
    ge(x) ? (T = x ? x.content : "default") : (l = !0);
    const y = he(S, "for"),
      C = n(q, y, b, A);
    let N, I;
    if ((N = he(S, "if"))) ((l = !0), r.push(fn(N.exp, Zt(x, C, g++), Gs)));
    else if ((I = he(S, /^else(?:-if)?$/, !0))) {
      let M = E,
        k;
      for (; M-- && ((k = s[M]), !!fs(k)); );
      if (k && Ot(k) && he(k, /^(?:else-)?if$/)) {
        let R = r[r.length - 1];
        for (; R.alternate.type === 19; ) R = R.alternate;
        R.alternate = I.exp ? fn(I.exp, Zt(x, C, g++), Gs) : Zt(x, C, g++);
      } else t.onError(U(30, I.loc));
    } else if (y) {
      l = !0;
      const M = y.forParseResult;
      M
        ? (gs(M), r.push(Q(t.helper(On), [M.source, pt(pn(M), Zt(x, C), !0)])))
        : t.onError(U(32, y.loc));
    } else {
      if (T) {
        if (h.has(T)) {
          t.onError(U(38, H));
          continue;
        }
        (h.add(T), T === "default" && (u = !0));
      }
      a.push(K(x, C));
    }
  }
  if (!o) {
    const E = (S, _) => {
      const b = n(S, void 0, _, i);
      return (t.compatConfig && (b.isNonScopedSlot = !0), K("default", b));
    };
    c
      ? f.length && !f.every(Pn) && (u ? t.onError(U(39, f[0].loc)) : a.push(E(void 0, f)))
      : a.push(E(void 0, s));
  }
  const d = l ? 2 : on(e.children) ? 3 : 1;
  let p = Ne(a.concat(K("_", O(d + "", !1))), i);
  return (r.length && (p = Q(t.helper(ss), [p, Ze(r)])), { slots: p, hasDynamicSlots: l });
}
function Zt(e, t, n) {
  const s = [K("name", e), K("fn", t)];
  return (n != null && s.push(K("key", O(String(n), !0))), Ne(s));
}
function on(e) {
  for (let t = 0; t < e.length; t++) {
    const n = e[t];
    switch (n.type) {
      case 1:
        if (n.tagType === 2 || on(n.children)) return !0;
        break;
      case 9:
        if (on(n.branches)) return !0;
        break;
      case 10:
      case 11:
        if (on(n.children)) return !0;
        break;
    }
  }
  return !1;
}
const fr = new WeakMap(),
  dr = (e, t) =>
    function () {
      if (((e = t.currentNode), !(e.type === 1 && (e.tagType === 0 || e.tagType === 1)))) return;
      const { tag: s, props: i } = e,
        a = e.tagType === 1;
      let r = a ? pr(e, t) : `"${s}"`;
      const l = mi(r) && r.callee === Tn;
      let o,
        c,
        u = 0,
        f,
        h,
        g,
        d =
          l ||
          r === St ||
          r === vn ||
          (!a && (s === "svg" || s === "foreignObject" || s === "math"));
      if (i.length > 0) {
        const p = _s(e, t, void 0, a, l);
        ((o = p.props), (u = p.patchFlag), (h = p.dynamicPropNames));
        const E = p.directives;
        ((g = E && E.length ? Ze(E.map((S) => hr(S, t))) : void 0), p.shouldUseBlock && (d = !0));
      }
      if (e.children.length > 0)
        if ((r === $t && ((d = !0), (u |= 1024)), a && r !== St && r !== $t)) {
          const { slots: E, hasDynamicSlots: S } = ur(e, t);
          ((c = E), S && (u |= 1024));
        } else if (e.children.length === 1 && r !== St) {
          const E = e.children[0],
            S = E.type,
            _ = S === 5 || S === 8;
          (_ && Ee(E, t) === 0 && (u |= 1), _ || S === 2 ? (c = E) : (c = e.children));
        } else c = e.children;
      (h && h.length && (f = Jl(h)),
        (e.codegenNode = It(t, r, o, c, u === 0 ? void 0 : u, f, g, !!d, !1, a, e.loc)));
    };
function pr(e, t, n = !1) {
  let { tag: s } = e;
  const i = Wn(s),
    a = Gt(e, "is", !1, !0);
  if (a)
    if (i || ft("COMPILER_IS_ON_ELEMENT", t)) {
      let l;
      if (
        (a.type === 6
          ? (l = a.value && O(a.value.content, !0))
          : ((l = a.exp), l || (l = O("is", !1, a.arg.loc))),
        l)
      )
        return Q(t.helper(Tn), [l]);
    } else a.type === 6 && a.value.content.startsWith("vue:") && (s = a.value.content.slice(4));
  const r = as(s) || t.isBuiltInComponent(s);
  return r ? (n || t.helper(r), r) : (t.helper(bn), t.components.add(s), At(s, "component"));
}
function _s(e, t, n = e.props, s, i, a = !1) {
  const { tag: r, loc: l, children: o } = e;
  let c = [];
  const u = [],
    f = [],
    h = o.length > 0;
  let g = !1,
    d = 0,
    p = !1,
    E = !1,
    S = !1,
    _ = !1,
    b = !1,
    A = !1;
  const x = [],
    q = (C) => {
      (c.length && (u.push(Ne(Js(c), l)), (c = [])), C && u.push(C));
    },
    H = () => {
      t.scopes.vFor > 0 && c.push(K(O("ref_for", !0), O("true")));
    },
    T = ({ key: C, value: N }) => {
      if (ge(C)) {
        const I = C.content,
          M = gi(I);
        if (
          (M &&
            (!s || i) &&
            I.toLowerCase() !== "onclick" &&
            I !== "onUpdate:modelValue" &&
            !ks(I) &&
            (_ = !0),
          M && ks(I) && (A = !0),
          M && N.type === 14 && (N = N.arguments[0]),
          N.type === 20 || ((N.type === 4 || N.type === 8) && Ee(N, t) > 0))
        )
          return;
        (I === "ref"
          ? (p = !0)
          : I === "class"
            ? (E = !0)
            : I === "style"
              ? (S = !0)
              : I !== "key" && !x.includes(I) && x.push(I),
          s && (I === "class" || I === "style") && !x.includes(I) && x.push(I));
      } else b = !0;
    };
  for (let C = 0; C < n.length; C++) {
    const N = n[C];
    if (N.type === 6) {
      const { loc: I, name: M, nameLoc: k, value: R } = N;
      let re = !0;
      if (
        (M === "ref" && ((p = !0), H()),
        M === "is" &&
          (Wn(r) || (R && R.content.startsWith("vue:")) || ft("COMPILER_IS_ON_ELEMENT", t)))
      )
        continue;
      c.push(K(O(M, !0, k), O(R ? R.content : "", re, R ? R.loc : I)));
    } else {
      const { name: I, arg: M, exp: k, loc: R, modifiers: re } = N,
        B = I === "bind",
        Ie = I === "on";
      if (I === "slot") {
        s || t.onError(U(40, R));
        continue;
      }
      if (
        I === "once" ||
        I === "memo" ||
        I === "is" ||
        (B && Ke(M, "is") && (Wn(r) || ft("COMPILER_IS_ON_ELEMENT", t))) ||
        (Ie && a)
      )
        continue;
      if (
        (((B && Ke(M, "key")) || (Ie && h && Ke(M, "vue:before-update"))) && (g = !0),
        B && Ke(M, "ref") && H(),
        !M && (B || Ie))
      ) {
        if (((b = !0), k))
          if (B) {
            if ((q(), ft("COMPILER_V_BIND_OBJECT_ORDER", t))) {
              u.unshift(k);
              continue;
            }
            (H(), q(), u.push(k));
          } else q({ type: 14, loc: R, callee: t.helper(Rn), arguments: s ? [k] : [k, "true"] });
        else t.onError(U(B ? 34 : 35, R));
        continue;
      }
      B && re.some((de) => de.content === "prop") && (d |= 32);
      const xe = t.directiveTransforms[I];
      if (xe) {
        const { props: de, needRuntime: ae } = xe(N, e, t);
        (!a && de.forEach(T),
          Ie && M && !ge(M) ? q(Ne(de, l)) : c.push(...de),
          ae && (f.push(N), Yn(ae) && fr.set(N, ae)));
      } else Kr(I) || (f.push(N), h && (g = !0));
    }
  }
  let y;
  if (
    (u.length
      ? (q(), u.length > 1 ? (y = Q(t.helper(Bt), u, l)) : (y = u[0]))
      : c.length && (y = Ne(Js(c), l)),
    b
      ? (d |= 16)
      : (E && !s && (d |= 2), S && !s && (d |= 4), x.length && (d |= 8), _ && (d |= 32)),
    !g && (d === 0 || d === 32) && (p || A || f.length > 0) && (d |= 512),
    !t.inSSR && y)
  )
    switch (y.type) {
      case 15:
        let C = -1,
          N = -1,
          I = !1;
        for (let R = 0; R < y.properties.length; R++) {
          const re = y.properties[R].key;
          ge(re)
            ? re.content === "class"
              ? (C = R)
              : re.content === "style" && (N = R)
            : re.isHandlerKey || (I = !0);
        }
        const M = y.properties[C],
          k = y.properties[N];
        I
          ? (y = Q(t.helper(Nt), [y]))
          : (M && !ge(M.value) && (M.value = Q(t.helper(An), [M.value])),
            k &&
              (S ||
                (k.value.type === 4 && k.value.content.trim()[0] === "[") ||
                k.value.type === 17) &&
              (k.value = Q(t.helper(Mn), [k.value])));
        break;
      case 14:
        break;
      default:
        y = Q(t.helper(Nt), [Q(t.helper(Rt), [y])]);
        break;
    }
  return { props: y, directives: f, patchFlag: d, dynamicPropNames: x, shouldUseBlock: g };
}
function Js(e) {
  const t = new Map(),
    n = [];
  for (let s = 0; s < e.length; s++) {
    const i = e[s];
    if (i.key.type === 8 || !i.key.isStatic) {
      n.push(i);
      continue;
    }
    const a = i.key.content,
      r = t.get(a);
    r ? (a === "style" || a === "class" || gi(a)) && Gl(r, i) : (t.set(a, i), n.push(i));
  }
  return n;
}
function Gl(e, t) {
  e.value.type === 17 ? e.value.elements.push(t.value) : (e.value = Ze([e.value, t.value], e.loc));
}
function hr(e, t) {
  const n = [],
    s = fr.get(e);
  s
    ? n.push(t.helperString(s))
    : (t.helper(Nn), t.directives.add(e.name), n.push(At(e.name, "directive")));
  const { loc: i } = e;
  if (
    (e.exp && n.push(e.exp),
    e.arg && (e.exp || n.push("void 0"), n.push(e.arg)),
    Object.keys(e.modifiers).length)
  ) {
    e.arg || (e.exp || n.push("void 0"), n.push("void 0"));
    const a = O("true", !1, i);
    n.push(
      Ne(
        e.modifiers.map((r) => K(r, a)),
        i
      )
    );
  }
  return Ze(n, e.loc);
}
function Jl(e) {
  let t = "[";
  for (let n = 0, s = e.length; n < s; n++) ((t += JSON.stringify(e[n])), n < s - 1 && (t += ", "));
  return t + "]";
}
function Wn(e) {
  return e === "component" || e === "Component";
}
const Yl = (e, t) => {
  if (zt(e)) {
    const { children: n, loc: s } = e,
      { slotName: i, slotProps: a } = mr(e, t),
      r = [t.prefixIdentifiers ? "_ctx.$slots" : "$slots", i, "{}", "undefined", "true"];
    let l = 2;
    (a && ((r[2] = a), (l = 3)),
      n.length && ((r[3] = pt([], n, !1, !1, s)), (l = 4)),
      t.scopeId && !t.slotted && (l = 5),
      r.splice(l),
      (e.codegenNode = Q(t.helper(ns), r, s)));
  }
};
function mr(e, t) {
  let n = '"default"',
    s;
  const i = [];
  for (let a = 0; a < e.props.length; a++) {
    const r = e.props[a];
    if (r.type === 6)
      r.value &&
        (r.name === "name"
          ? (n = JSON.stringify(r.value.content))
          : ((r.name = Qe(r.name)), i.push(r)));
    else if (r.name === "bind" && Ke(r.arg, "name")) {
      if (r.exp) n = r.exp;
      else if (r.arg && r.arg.type === 4) {
        const l = Qe(r.arg.content);
        n = r.exp = O(l, !1, r.arg.loc);
      }
    } else
      (r.name === "bind" && r.arg && ge(r.arg) && (r.arg.content = Qe(r.arg.content)), i.push(r));
  }
  if (i.length > 0) {
    const { props: a, directives: r } = _s(e, t, i, !1, !1);
    ((s = a), r.length && t.onError(U(36, r[0].loc)));
  }
  return { slotName: n, slotProps: s };
}
const vs = (e, t, n, s) => {
    const { loc: i, modifiers: a, arg: r } = e;
    !e.exp && !a.length && n.onError(U(35, i));
    let l;
    if (r.type === 4)
      if (r.isStatic) {
        let f = r.content;
        f.startsWith("vue:") && (f = `vnode-${f.slice(4)}`);
        const h =
          t.tagType !== 0 || f.startsWith("vnode") || !/[A-Z]/.test(f) ? Zr(Qe(f)) : `on:${f}`;
        l = O(h, !0, r.loc);
      } else l = we([`${n.helperString(un)}(`, r, ")"]);
    else ((l = r), l.children.unshift(`${n.helperString(un)}(`), l.children.push(")"));
    let o = e.exp;
    o && !o.content.trim() && (o = void 0);
    let c = n.cacheHandlers && !o && !n.inVOnce;
    if (o) {
      const f = ls(o),
        h = !(f || Hi(o)),
        g = o.content.includes(";");
      (h || (c && f)) &&
        (o = we([`${h ? "$event" : "(...args)"} => ${g ? "{" : "("}`, o, g ? "}" : ")"]));
    }
    let u = { props: [K(l, o || O("() => {}", !1, i))] };
    return (
      s && (u = s(u)),
      c && (u.props[0].value = n.cache(u.props[0].value)),
      u.props.forEach((f) => (f.key.isHandlerKey = !0)),
      u
    );
  },
  gr = (e, t, n) => {
    const { modifiers: s, loc: i } = e,
      a = e.arg;
    let { exp: r } = e;
    return (
      r && r.type === 4 && !r.content.trim() && (r = void 0),
      a.type !== 4
        ? (a.children.unshift("("), a.children.push(') || ""'))
        : a.isStatic || (a.content = a.content ? `${a.content} || ""` : '""'),
      s.some((l) => l.content === "camel") &&
        (a.type === 4
          ? a.isStatic
            ? (a.content = Qe(a.content))
            : (a.content = `${n.helperString(cn)}(${a.content})`)
          : (a.children.unshift(`${n.helperString(cn)}(`), a.children.push(")"))),
      n.inSSR ||
        (s.some((l) => l.content === "prop") && Ys(a, "."),
        s.some((l) => l.content === "attr") && Ys(a, "^")),
      { props: [K(a, r)] }
    );
  },
  Ys = (e, t) => {
    e.type === 4
      ? e.isStatic
        ? (e.content = t + e.content)
        : (e.content = `\`${t}\${${e.content}}\``)
      : (e.children.unshift(`'${t}' + (`), e.children.push(")"));
  },
  Kl = (e, t) => {
    if (e.type === 0 || e.type === 1 || e.type === 11 || e.type === 10)
      return () => {
        const n = e.children;
        let s,
          i = !1;
        for (let a = 0; a < n.length; a++) {
          const r = n[a];
          if (tn(r)) {
            i = !0;
            for (let l = a + 1; l < n.length; l++) {
              const o = n[l];
              if (tn(o))
                (s || (s = n[a] = we([r], r.loc)), s.children.push(" + ", o), n.splice(l, 1), l--);
              else {
                s = void 0;
                break;
              }
            }
          }
        }
        if (
          !(
            !i ||
            (n.length === 1 &&
              (e.type === 0 ||
                (e.type === 1 &&
                  e.tagType === 0 &&
                  !e.props.find((a) => a.type === 7 && !t.directiveTransforms[a.name]) &&
                  e.tag !== "template")))
          )
        )
          for (let a = 0; a < n.length; a++) {
            const r = n[a];
            if (tn(r) || r.type === 8) {
              const l = [];
              ((r.type !== 2 || r.content !== " ") && l.push(r),
                !t.ssr && Ee(r, t) === 0 && l.push("1"),
                (n[a] = { type: 12, content: r, loc: r.loc, codegenNode: Q(t.helper(yn), l) }));
            }
          }
      };
  },
  Ks = new WeakSet(),
  Ql = (e, t) => {
    if (e.type === 1 && he(e, "once", !0))
      return Ks.has(e) || t.inVOnce || t.inSSR
        ? void 0
        : (Ks.add(e),
          (t.inVOnce = !0),
          t.helper(Ht),
          () => {
            t.inVOnce = !1;
            const n = t.currentNode;
            n.codegenNode && (n.codegenNode = t.cache(n.codegenNode, !0, !0));
          });
  },
  Es = (e, t, n) => {
    const { exp: s, arg: i } = e;
    if (!s) return (n.onError(U(41, e.loc)), Pt());
    const a = s.loc.source.trim(),
      r = s.type === 4 ? s.content : a,
      l = n.bindingMetadata[a];
    if (l === "props" || l === "props-aliased") return (n.onError(U(44, s.loc)), Pt());
    if (l === "literal-const" || l === "setup-const") return (n.onError(U(45, s.loc)), Pt());
    if (!r.trim() || !ls(s)) return (n.onError(U(42, s.loc)), Pt());
    const o = i || O("modelValue", !0),
      c = i
        ? ge(i)
          ? `onUpdate:${Qe(i.content)}`
          : we(['"onUpdate:" + ', i])
        : "onUpdate:modelValue";
    let u;
    const f = n.isTS ? "($event: any)" : "$event";
    u = we([`${f} => ((`, s, ") = $event)"]);
    const h = [K(o, e.exp), K(c, u)];
    if (e.modifiers.length && t.tagType === 1) {
      const g = e.modifiers
          .map((p) => p.content)
          .map((p) => (qt(p) ? p : JSON.stringify(p)) + ": true")
          .join(", "),
        d = i ? (ge(i) ? `${i.content}Modifiers` : we([i, ' + "Modifiers"'])) : "modelModifiers";
      h.push(K(d, O(`{ ${g} }`, !1, e.loc, 2)));
    }
    return Pt(h);
  };
function Pt(e = []) {
  return { props: e };
}
const Zl = /[\w).+\-_$\]]/,
  ec = (e, t) => {
    ft("COMPILER_FILTERS", t) &&
      (e.type === 5
        ? hn(e.content, t)
        : e.type === 1 &&
          e.props.forEach((n) => {
            n.type === 7 && n.name !== "for" && n.exp && hn(n.exp, t);
          }));
  };
function hn(e, t) {
  if (e.type === 4) Qs(e, t);
  else
    for (let n = 0; n < e.children.length; n++) {
      const s = e.children[n];
      typeof s == "object" &&
        (s.type === 4 ? Qs(s, t) : s.type === 8 ? hn(e, t) : s.type === 5 && hn(s.content, t));
    }
}
function Qs(e, t) {
  const n = e.content;
  let s = !1,
    i = !1,
    a = !1,
    r = !1,
    l = 0,
    o = 0,
    c = 0,
    u = 0,
    f,
    h,
    g,
    d,
    p = [];
  for (g = 0; g < n.length; g++)
    if (((h = f), (f = n.charCodeAt(g)), s)) f === 39 && h !== 92 && (s = !1);
    else if (i) f === 34 && h !== 92 && (i = !1);
    else if (a) f === 96 && h !== 92 && (a = !1);
    else if (r) f === 47 && h !== 92 && (r = !1);
    else if (
      f === 124 &&
      n.charCodeAt(g + 1) !== 124 &&
      n.charCodeAt(g - 1) !== 124 &&
      !l &&
      !o &&
      !c
    )
      d === void 0 ? ((u = g + 1), (d = n.slice(0, g).trim())) : E();
    else {
      switch (f) {
        case 34:
          i = !0;
          break;
        case 39:
          s = !0;
          break;
        case 96:
          a = !0;
          break;
        case 40:
          c++;
          break;
        case 41:
          c--;
          break;
        case 91:
          o++;
          break;
        case 93:
          o--;
          break;
        case 123:
          l++;
          break;
        case 125:
          l--;
          break;
      }
      if (f === 47) {
        let S = g - 1,
          _;
        for (; S >= 0 && ((_ = n.charAt(S)), _ === " "); S--);
        (!_ || !Zl.test(_)) && (r = !0);
      }
    }
  d === void 0 ? (d = n.slice(0, g).trim()) : u !== 0 && E();
  function E() {
    (p.push(n.slice(u, g).trim()), (u = g + 1));
  }
  if (p.length) {
    for (g = 0; g < p.length; g++) d = tc(d, p[g], t);
    ((e.content = d), (e.ast = void 0));
  }
}
function tc(e, t, n) {
  n.helper(In);
  const s = t.indexOf("(");
  if (s < 0) return (n.filters.add(t), `${At(t, "filter")}(${e})`);
  {
    const i = t.slice(0, s),
      a = t.slice(s + 1);
    return (n.filters.add(i), `${At(i, "filter")}(${e}${a !== ")" ? "," + a : a}`);
  }
}
const Zs = new WeakSet(),
  nc = (e, t) => {
    if (e.type === 1) {
      const n = he(e, "memo");
      return !n || Zs.has(e) || t.inSSR
        ? void 0
        : (Zs.add(e),
          () => {
            const s = e.codegenNode || t.currentNode.codegenNode;
            s &&
              s.type === 13 &&
              (e.tagType !== 1 && Dn(s, t),
              (e.codegenNode = Q(t.helper(Ln), [
                n.exp,
                pt(void 0, s),
                "_cache",
                String(t.cached.length)
              ])),
              t.cached.push(null));
          });
    }
  },
  _r = (e, t) => {
    if (e.type === 1) {
      for (const n of e.props)
        if (
          n.type === 7 &&
          n.name === "bind" &&
          (!n.exp || (n.exp.type === 4 && !n.exp.content.trim())) &&
          n.arg
        ) {
          const s = n.arg;
          if (s.type !== 4 || !s.isStatic) (t.onError(U(53, s.loc)), (n.exp = O("", !0, s.loc)));
          else {
            const i = Qe(s.content);
            (os.test(i[0]) || i[0] === "-") && (n.exp = O(i, !1, s.loc));
          }
        }
    }
  };
function vr(e) {
  return [[_r, Ql, Xl, nc, Ul, ec, Yl, dr, cr, Kl], { on: vs, bind: gr, model: Es }];
}
function Er(e, t = {}) {
  const n = t.onError || rs,
    s = t.mode === "module";
  t.prefixIdentifiers === !0 ? n(U(48)) : s && n(U(49));
  const i = !1;
  (t.cacheHandlers && n(U(50)), t.scopeId && !s && n(U(51)));
  const a = He({}, t, { prefixIdentifiers: i }),
    r = ne(e) ? ps(e, a) : e,
    [l, o] = vr();
  return (
    tr(
      r,
      He({}, a, {
        nodeTransforms: [...l, ...(t.nodeTransforms || [])],
        directiveTransforms: He({}, o, t.directiveTransforms || {})
      })
    ),
    sr(r, a)
  );
}
const sc = {
    DATA: "data",
    PROPS: "props",
    PROPS_ALIASED: "props-aliased",
    SETUP_LET: "setup-let",
    SETUP_CONST: "setup-const",
    SETUP_REACTIVE_CONST: "setup-reactive-const",
    SETUP_MAYBE_REF: "setup-maybe-ref",
    SETUP_REF: "setup-ref",
    OPTIONS: "options",
    LITERAL_CONST: "literal-const"
  },
  Sr = () => ({ props: [] });
const Ss = Symbol(""),
  ys = Symbol(""),
  bs = Symbol(""),
  Ts = Symbol(""),
  mn = Symbol(""),
  Ns = Symbol(""),
  Is = Symbol(""),
  Cs = Symbol(""),
  Os = Symbol(""),
  As = Symbol("");
Ai({
  [Ss]: "vModelRadio",
  [ys]: "vModelCheckbox",
  [bs]: "vModelText",
  [Ts]: "vModelSelect",
  [mn]: "vModelDynamic",
  [Ns]: "withModifiers",
  [Is]: "withKeys",
  [Cs]: "vShow",
  [Os]: "Transition",
  [As]: "TransitionGroup"
});
let Et;
function ic(e, t = !1) {
  return (
    Et || (Et = document.createElement("div")),
    t
      ? ((Et.innerHTML = `<div foo="${e.replace(/"/g, "&quot;")}">`),
        Et.children[0].getAttribute("foo"))
      : ((Et.innerHTML = e), Et.textContent)
  );
}
const Ms = {
    parseMode: "html",
    isVoidTag: ea,
    isNativeTag: (e) => ta(e) || na(e) || sa(e),
    isPreTag: (e) => e === "pre",
    isIgnoreNewlineTag: (e) => e === "pre" || e === "textarea",
    decodeEntities: ic,
    isBuiltInComponent: (e) => {
      if (e === "Transition" || e === "transition") return Os;
      if (e === "TransitionGroup" || e === "transition-group") return As;
    },
    getNamespace(e, t, n) {
      let s = t ? t.ns : n;
      if (t && s === 2)
        if (t.tag === "annotation-xml") {
          if (e === "svg") return 1;
          t.props.some(
            (i) =>
              i.type === 6 &&
              i.name === "encoding" &&
              i.value != null &&
              (i.value.content === "text/html" || i.value.content === "application/xhtml+xml")
          ) && (s = 0);
        } else /^m(?:[ions]|text)$/.test(t.tag) && e !== "mglyph" && e !== "malignmark" && (s = 0);
      else
        t &&
          s === 1 &&
          (t.tag === "foreignObject" || t.tag === "desc" || t.tag === "title") &&
          (s = 0);
      if (s === 0) {
        if (e === "svg") return 1;
        if (e === "math") return 2;
      }
      return s;
    }
  },
  yr = (e) => {
    e.type === 1 &&
      e.props.forEach((t, n) => {
        t.type === 6 &&
          t.name === "style" &&
          t.value &&
          (e.props[n] = {
            type: 7,
            name: "bind",
            arg: O("style", !0, t.loc),
            exp: rc(t.value.content, t.loc),
            modifiers: [],
            loc: t.loc
          });
      });
  },
  rc = (e, t) => {
    const n = ia(e);
    return O(JSON.stringify(n), !1, t, 3);
  };
function Fe(e, t) {
  return U(e, t);
}
const ac = {
    X_V_HTML_NO_EXPRESSION: 54,
    54: "X_V_HTML_NO_EXPRESSION",
    X_V_HTML_WITH_CHILDREN: 55,
    55: "X_V_HTML_WITH_CHILDREN",
    X_V_TEXT_NO_EXPRESSION: 56,
    56: "X_V_TEXT_NO_EXPRESSION",
    X_V_TEXT_WITH_CHILDREN: 57,
    57: "X_V_TEXT_WITH_CHILDREN",
    X_V_MODEL_ON_INVALID_ELEMENT: 58,
    58: "X_V_MODEL_ON_INVALID_ELEMENT",
    X_V_MODEL_ARG_ON_ELEMENT: 59,
    59: "X_V_MODEL_ARG_ON_ELEMENT",
    X_V_MODEL_ON_FILE_INPUT_ELEMENT: 60,
    60: "X_V_MODEL_ON_FILE_INPUT_ELEMENT",
    X_V_MODEL_UNNECESSARY_VALUE: 61,
    61: "X_V_MODEL_UNNECESSARY_VALUE",
    X_V_SHOW_NO_EXPRESSION: 62,
    62: "X_V_SHOW_NO_EXPRESSION",
    X_TRANSITION_INVALID_CHILDREN: 63,
    63: "X_TRANSITION_INVALID_CHILDREN",
    X_IGNORED_SIDE_EFFECT_TAG: 64,
    64: "X_IGNORED_SIDE_EFFECT_TAG",
    __EXTEND_POINT__: 65,
    65: "__EXTEND_POINT__"
  },
  oc = {
    54: "v-html is missing expression.",
    55: "v-html will override element children.",
    56: "v-text is missing expression.",
    57: "v-text will override element children.",
    58: "v-model can only be used on <input>, <textarea> and <select> elements.",
    59: "v-model argument is not supported on plain elements.",
    60: "v-model cannot be used on file inputs since they are read-only. Use a v-on:change listener instead.",
    61: "Unnecessary value binding used alongside v-model. It will interfere with v-model's behavior.",
    62: "v-show is missing expression.",
    63: "<Transition> expects exactly one child element or component.",
    64: "Tags with side effect (<script> and <style>) are ignored in client component templates."
  },
  lc = (e, t, n) => {
    const { exp: s, loc: i } = e;
    return (
      s || n.onError(Fe(54, i)),
      t.children.length && (n.onError(Fe(55, i)), (t.children.length = 0)),
      { props: [K(O("innerHTML", !0, i), s || O("", !0))] }
    );
  },
  cc = (e, t, n) => {
    const { exp: s, loc: i } = e;
    return (
      s || n.onError(Fe(56, i)),
      t.children.length && (n.onError(Fe(57, i)), (t.children.length = 0)),
      {
        props: [
          K(
            O("textContent", !0),
            s ? (Ee(s, n) > 0 ? s : Q(n.helperString(jt), [s], i)) : O("", !0)
          )
        ]
      }
    );
  },
  uc = (e, t, n) => {
    const s = Es(e, t, n);
    if (!s.props.length || t.tagType === 1) return s;
    e.arg && n.onError(Fe(59, e.arg.loc));
    const { tag: i } = t,
      a = n.isCustomElement(i);
    if (i === "input" || i === "textarea" || i === "select" || a) {
      let r = bs,
        l = !1;
      if (i === "input" || a) {
        const o = Gt(t, "type");
        if (o) {
          if (o.type === 7) r = mn;
          else if (o.value)
            switch (o.value.content) {
              case "radio":
                r = Ss;
                break;
              case "checkbox":
                r = ys;
                break;
              case "file":
                ((l = !0), n.onError(Fe(60, e.loc)));
                break;
            }
        } else Xi(t) && (r = mn);
      } else i === "select" && (r = Ts);
      l || (s.needRuntime = n.helper(r));
    } else n.onError(Fe(58, e.loc));
    return (
      (s.props = s.props.filter((r) => !(r.key.type === 4 && r.key.content === "modelValue"))),
      s
    );
  },
  fc = gn("passive,once,capture"),
  dc = gn("stop,prevent,self,ctrl,shift,alt,meta,exact,middle"),
  pc = gn("left,right"),
  br = gn("onkeyup,onkeydown,onkeypress"),
  hc = (e, t, n, s) => {
    const i = [],
      a = [],
      r = [];
    for (let l = 0; l < t.length; l++) {
      const o = t[l].content;
      (o === "native" && Ct("COMPILER_V_ON_NATIVE", n)) || fc(o)
        ? r.push(o)
        : pc(o)
          ? ge(e)
            ? br(e.content.toLowerCase())
              ? i.push(o)
              : a.push(o)
            : (i.push(o), a.push(o))
          : dc(o)
            ? a.push(o)
            : i.push(o);
    }
    return { keyModifiers: i, nonKeyModifiers: a, eventOptionModifiers: r };
  },
  ei = (e, t) =>
    ge(e) && e.content.toLowerCase() === "onclick"
      ? O(t, !0)
      : e.type !== 4
        ? we(["(", e, `) === "onClick" ? "${t}" : (`, e, ")"])
        : e,
  mc = (e, t, n) =>
    vs(e, t, n, (s) => {
      const { modifiers: i } = e;
      if (!i.length) return s;
      let { key: a, value: r } = s.props[0];
      const { keyModifiers: l, nonKeyModifiers: o, eventOptionModifiers: c } = hc(a, i, n, e.loc);
      if (
        (o.includes("right") && (a = ei(a, "onContextmenu")),
        o.includes("middle") && (a = ei(a, "onMouseup")),
        o.length && (r = Q(n.helper(Ns), [r, JSON.stringify(o)])),
        l.length &&
          (!ge(a) || br(a.content.toLowerCase())) &&
          (r = Q(n.helper(Is), [r, JSON.stringify(l)])),
        c.length)
      ) {
        const u = c.map(hi).join("");
        a = ge(a) ? O(`${a.content}${u}`, !0) : we(["(", a, `) + "${u}"`]);
      }
      return { props: [K(a, r)] };
    }),
  gc = (e, t, n) => {
    const { exp: s, loc: i } = e;
    return (s || n.onError(Fe(62, i)), { props: [], needRuntime: n.helper(Cs) });
  },
  _c = (e, t) => {
    e.type === 1 && e.tagType === 0 && (e.tag === "script" || e.tag === "style") && t.removeNode();
  },
  Tr = [yr],
  Nr = { cloak: Sr, html: lc, text: cc, model: uc, on: mc, show: gc };
function vc(e, t = {}) {
  return Er(
    e,
    He({}, Ms, t, {
      nodeTransforms: [_c, ...Tr, ...(t.nodeTransforms || [])],
      directiveTransforms: He({}, Nr, t.directiveTransforms || {}),
      transformHoist: null
    })
  );
}
function Ec(e, t = {}) {
  return ps(e, He({}, Ms, t));
}
const Sc = Object.freeze(
    Object.defineProperty(
      {
        __proto__: null,
        BASE_TRANSITION: Qn,
        BindingTypes: sc,
        CAMELIZE: cn,
        CAPITALIZE: Ti,
        CREATE_BLOCK: Zn,
        CREATE_COMMENT: Mt,
        CREATE_ELEMENT_BLOCK: es,
        CREATE_ELEMENT_VNODE: Sn,
        CREATE_SLOTS: ss,
        CREATE_STATIC: ts,
        CREATE_TEXT: yn,
        CREATE_VNODE: En,
        CompilerDeprecationTypes: zo,
        ConstantTypes: Do,
        DOMDirectiveTransforms: Nr,
        DOMErrorCodes: ac,
        DOMErrorMessages: oc,
        DOMNodeTransforms: Tr,
        ElementTypes: Lo,
        ErrorCodes: Uo,
        FRAGMENT: Tt,
        GUARD_REACTIVE_PROPS: Rt,
        IS_MEMO_SAME: is,
        IS_REF: Oi,
        KEEP_ALIVE: $t,
        MERGE_PROPS: Bt,
        NORMALIZE_CLASS: An,
        NORMALIZE_PROPS: Nt,
        NORMALIZE_STYLE: Mn,
        Namespaces: Ro,
        NodeTypes: wo,
        OPEN_BLOCK: tt,
        POP_SCOPE_ID: Ii,
        PUSH_SCOPE_ID: Ni,
        RENDER_LIST: On,
        RENDER_SLOT: ns,
        RESOLVE_COMPONENT: bn,
        RESOLVE_DIRECTIVE: Nn,
        RESOLVE_DYNAMIC_COMPONENT: Tn,
        RESOLVE_FILTER: In,
        SET_BLOCK_TRACKING: Ht,
        SUSPENSE: vn,
        TELEPORT: St,
        TO_DISPLAY_STRING: jt,
        TO_HANDLERS: Rn,
        TO_HANDLER_KEY: un,
        TRANSITION: Os,
        TRANSITION_GROUP: As,
        TS_NODE_TYPES: xi,
        UNREF: Ci,
        V_MODEL_CHECKBOX: ys,
        V_MODEL_DYNAMIC: mn,
        V_MODEL_RADIO: Ss,
        V_MODEL_SELECT: Ts,
        V_MODEL_TEXT: bs,
        V_ON_WITH_KEYS: Is,
        V_ON_WITH_MODIFIERS: Ns,
        V_SHOW: Cs,
        WITH_CTX: wn,
        WITH_DIRECTIVES: Cn,
        WITH_MEMO: Ln,
        advancePositionWithClone: ll,
        advancePositionWithMutation: zi,
        assert: cl,
        baseCompile: Er,
        baseParse: ps,
        buildDirectiveArgs: hr,
        buildProps: _s,
        buildSlots: ur,
        checkCompatEnabled: Ct,
        compile: vc,
        convertToBlock: Dn,
        createArrayExpression: Ze,
        createAssignmentExpression: Vo,
        createBlockStatement: wi,
        createCacheExpression: Ri,
        createCallExpression: Q,
        createCompilerError: U,
        createCompoundExpression: we,
        createConditionalExpression: fn,
        createDOMCompilerError: Fe,
        createForLoopParams: pn,
        createFunctionExpression: pt,
        createIfStatement: ko,
        createInterpolation: Po,
        createObjectExpression: Ne,
        createObjectProperty: K,
        createReturnStatement: Bo,
        createRoot: Mi,
        createSequenceExpression: $o,
        createSimpleExpression: O,
        createStructuralDirectiveTransform: hs,
        createTemplateLiteral: xo,
        createTransformContext: er,
        createVNodeCall: It,
        errorMessages: Wo,
        extractIdentifiers: Pe,
        findDir: he,
        findProp: Gt,
        forAliasRE: Wi,
        generate: sr,
        generateCodeFrame: ra,
        getBaseTransformPreset: vr,
        getConstantType: Ee,
        getMemoedVNodeCall: Ui,
        getVNodeBlockHelper: mt,
        getVNodeHelper: ht,
        hasDynamicKeyVBind: Xi,
        hasScopeRef: Le,
        helperNameMap: dt,
        injectProp: Xt,
        isAllWhitespace: us,
        isCommentOrWhitespace: fs,
        isCoreComponent: as,
        isFnExpression: Hi,
        isFnExpressionBrowser: Bi,
        isFnExpressionNode: ol,
        isFunctionType: el,
        isInDestructureAssignment: Go,
        isInNewExpression: Jo,
        isMemberExpression: ls,
        isMemberExpressionBrowser: $i,
        isMemberExpressionNode: rl,
        isReferencedIdentifier: qo,
        isSimpleIdentifier: qt,
        isSlotOutlet: zt,
        isStaticArgOf: Ke,
        isStaticExp: ge,
        isStaticProperty: Pi,
        isStaticPropertyKey: tl,
        isTemplateNode: Ot,
        isText: tn,
        isVPre: Xn,
        isVSlot: cs,
        isWhitespaceText: Pn,
        locStub: se,
        noopDirectiveTransform: Sr,
        parse: Ec,
        parserOptions: Ms,
        processExpression: an,
        processFor: lr,
        processIf: or,
        processSlotOutlet: mr,
        registerRuntimeHelpers: Ai,
        resolveComponentType: pr,
        stringifyExpression: ar,
        toValidAssetId: At,
        trackSlotScopes: cr,
        trackVForSlotScopes: jl,
        transform: tr,
        transformBind: gr,
        transformElement: dr,
        transformExpression: zl,
        transformModel: Es,
        transformOn: vs,
        transformStyle: yr,
        transformVBindShorthand: _r,
        traverseNode: Jt,
        unwrapTSNode: ki,
        validFirstIdentCharRE: os,
        walkBlockDeclarations: Di,
        walkFunctionParams: Yo,
        walkIdentifiers: jo,
        warnDeprecation: Fo
      },
      Symbol.toStringTag,
      { value: "Module" }
    )
  ),
  yc = Kn(Sc),
  bc = Kn(aa),
  Tc = Kn(oa);
var ti;
function Nc() {
  return (
    ti ||
      ((ti = 1),
      (function (e) {
        Object.defineProperty(e, "__esModule", { value: !0 });
        var t = yc,
          n = bc,
          s = Tc;
        function i(o) {
          var c = Object.create(null);
          if (o) for (var u in o) c[u] = o[u];
          return ((c.default = o), Object.freeze(c));
        }
        var a = i(n);
        const r = Object.create(null);
        function l(o, c) {
          if (!s.isString(o))
            if (o.nodeType) o = o.innerHTML;
            else return s.NOOP;
          const u = s.genCacheKey(o, c),
            f = r[u];
          if (f) return f;
          if (o[0] === "#") {
            const p = document.querySelector(o);
            o = p ? p.innerHTML : "";
          }
          const h = s.extend({ hoistStatic: !0, onError: void 0, onWarn: s.NOOP }, c);
          !h.isCustomElement &&
            typeof customElements < "u" &&
            (h.isCustomElement = (p) => !!customElements.get(p));
          const { code: g } = t.compile(o, h),
            d = new Function("Vue", g)(a);
          return ((d._rc = !0), (r[u] = d));
        }
        (n.registerRuntimeCompiler(l),
          (e.compile = l),
          Object.keys(n).forEach(function (o) {
            o !== "default" && !Object.prototype.hasOwnProperty.call(e, o) && (e[o] = n[o]);
          }));
      })($n)),
    $n
  );
}
var ni;
function Rs() {
  return (ni || ((ni = 1), (Vn.exports = Nc())), Vn.exports);
}
var ee = {},
  te = {},
  si;
function ws() {
  if (si) return te;
  si = 1;
  var e =
    (te && te.__assign) ||
    function () {
      return (
        (e =
          Object.assign ||
          function (l) {
            for (var o, c = 1, u = arguments.length; c < u; c++) {
              o = arguments[c];
              for (var f in o) Object.prototype.hasOwnProperty.call(o, f) && (l[f] = o[f]);
            }
            return l;
          }),
        e.apply(this, arguments)
      );
    };
  ((te.__esModule = !0),
    (te.getReferenceLineMap =
      te.getId =
      te.filterHandles =
      te.removeEvent =
      te.addEvent =
      te.getElSize =
      te.IDENTITY =
        void 0));
  var t = Ir();
  te.IDENTITY = Symbol("Vue3DraggableResizable");
  function n(l) {
    var o = window.getComputedStyle(l);
    return {
      width: parseFloat(o.getPropertyValue("width")),
      height: parseFloat(o.getPropertyValue("height"))
    };
  }
  te.getElSize = n;
  function s(l) {
    return function (o, c, u) {
      o &&
        (typeof c == "string" && (c = [c]),
        c.forEach(function (f) {
          return o[l](f, u, { passive: !1 });
        }));
    };
  }
  ((te.addEvent = s("addEventListener")), (te.removeEvent = s("removeEventListener")));
  function i(l) {
    if (l && l.length > 0) {
      var o = [];
      return (
        l.forEach(function (c) {
          t.ALL_HANDLES.includes(c) && !o.includes(c) && o.push(c);
        }),
        o
      );
    } else return [];
  }
  te.filterHandles = i;
  function a() {
    return String(Math.random()).substr(2) + String(Date.now());
  }
  te.getId = a;
  function r(l, o, c) {
    var u, f;
    if (l.disabled.value) return null;
    var h = { row: [], col: [] },
      g = o.parentWidth,
      d = o.parentHeight;
    ((u = h.row).push.apply(u, l.adsorbRows),
      (f = h.col).push.apply(f, l.adsorbCols),
      l.adsorbParent.value &&
        (h.row.push(0, d.value, d.value / 2), h.col.push(0, g.value, g.value / 2)));
    var p = l.getPositionStore(c);
    Object.values(p).forEach(function (S) {
      var _ = S.x,
        b = S.y,
        A = S.w,
        x = S.h;
      (h.row.push(b, b + x, b + x / 2), h.col.push(_, _ + A, _ + A / 2));
    });
    var E = {
      row: h.row.reduce(function (S, _) {
        var b;
        return e(e({}, S), ((b = {}), (b[_] = { min: _ - 5, max: _ + 5, value: _ }), b));
      }, {}),
      col: h.col.reduce(function (S, _) {
        var b;
        return e(e({}, S), ((b = {}), (b[_] = { min: _ - 5, max: _ + 5, value: _ }), b));
      }, {})
    };
    return E;
  }
  return ((te.getReferenceLineMap = r), te);
}
var ii;
function Ic() {
  if (ii) return ee;
  ii = 1;
  var e =
    (ee && ee.__assign) ||
    function () {
      return (
        (e =
          Object.assign ||
          function (d) {
            for (var p, E = 1, S = arguments.length; E < S; E++) {
              p = arguments[E];
              for (var _ in p) Object.prototype.hasOwnProperty.call(p, _) && (d[_] = p[_]);
            }
            return d;
          }),
        e.apply(this, arguments)
      );
    };
  ((ee.__esModule = !0),
    (ee.watchProps =
      ee.initResizeHandle =
      ee.initDraggableContainer =
      ee.initLimitSizeAndMethods =
      ee.initParent =
      ee.initState =
      ee.useState =
        void 0));
  var t = Rs(),
    n = ws();
  function s(d) {
    var p = t.ref(d),
      E = function (S) {
        return ((p.value = S), S);
      };
    return [p, E];
  }
  ee.useState = s;
  function i(d, p) {
    var E = s(d.initW),
      S = E[0],
      _ = E[1],
      b = s(d.initH),
      A = b[0],
      x = b[1],
      q = s(d.x),
      H = q[0],
      T = q[1],
      y = s(d.y),
      C = y[0],
      N = y[1],
      I = s(d.active),
      M = I[0],
      k = I[1],
      R = s(!1),
      re = R[0],
      B = R[1],
      Ie = s(!1),
      xe = Ie[0],
      de = Ie[1],
      ae = s(""),
      Se = ae[0],
      nt = ae[1],
      ke = s(1 / 0),
      je = ke[0],
      vt = ke[1],
      W = s(1 / 0),
      Z = W[0],
      Ce = W[1],
      qe = s(d.minW),
      st = qe[0],
      wt = qe[1],
      Oe = s(d.minH),
      pe = Oe[0],
      Ae = Oe[1],
      De = t.computed(function () {
        return A.value / S.value;
      });
    return (
      t.watch(
        S,
        function (w) {
          p("update:w", w);
        },
        { immediate: !0 }
      ),
      t.watch(
        A,
        function (w) {
          p("update:h", w);
        },
        { immediate: !0 }
      ),
      t.watch(C, function (w) {
        p("update:y", w);
      }),
      t.watch(H, function (w) {
        p("update:x", w);
      }),
      t.watch(M, function (w, F) {
        (p("update:active", w), !F && w ? p("activated") : F && !w && p("deactivated"));
      }),
      t.watch(
        function () {
          return d.active;
        },
        function (w) {
          k(w);
        }
      ),
      {
        id: n.getId(),
        width: S,
        height: A,
        top: C,
        left: H,
        enable: M,
        dragging: re,
        resizing: xe,
        resizingHandle: Se,
        resizingMaxHeight: Z,
        resizingMaxWidth: je,
        resizingMinWidth: st,
        resizingMinHeight: pe,
        aspectRatio: De,
        setEnable: k,
        setDragging: B,
        setResizing: de,
        setResizingHandle: nt,
        setResizingMaxHeight: Ce,
        setResizingMaxWidth: vt,
        setResizingMinWidth: wt,
        setResizingMinHeight: Ae,
        setWidth: function (w) {
          return _(Math.floor(w));
        },
        setHeight: function (w) {
          return x(Math.floor(w));
        },
        setTop: function (w) {
          return N(Math.floor(w));
        },
        setLeft: function (w) {
          return T(Math.floor(w));
        }
      }
    );
  }
  ee.initState = i;
  function a(d) {
    var p = t.ref(0),
      E = t.ref(0);
    return (
      t.onMounted(function () {
        if (d.value && d.value.parentElement) {
          var S = n.getElSize(d.value.parentElement),
            _ = S.width,
            b = S.height;
          ((p.value = _), (E.value = b));
        }
      }),
      { parentWidth: p, parentHeight: E }
    );
  }
  ee.initParent = a;
  function r(d, p, E) {
    var S = E.width,
      _ = E.height,
      b = E.left,
      A = E.top,
      x = E.resizingMaxWidth,
      q = E.resizingMaxHeight,
      H = E.resizingMinWidth,
      T = E.resizingMinHeight,
      y = E.setWidth,
      C = E.setHeight,
      N = E.setTop,
      I = E.setLeft,
      M = p.parentWidth,
      k = p.parentHeight,
      R = {
        minWidth: t.computed(function () {
          return H.value;
        }),
        minHeight: t.computed(function () {
          return T.value;
        }),
        maxWidth: t.computed(function () {
          var B = 1 / 0;
          return (d.parent && (B = Math.min(M.value, x.value)), B);
        }),
        maxHeight: t.computed(function () {
          var B = 1 / 0;
          return (d.parent && (B = Math.min(k.value, q.value)), B);
        }),
        minLeft: t.computed(function () {
          return d.parent ? 0 : -1 / 0;
        }),
        minTop: t.computed(function () {
          return d.parent ? 0 : -1 / 0;
        }),
        maxLeft: t.computed(function () {
          return d.parent ? M.value - S.value : 1 / 0;
        }),
        maxTop: t.computed(function () {
          return d.parent ? k.value - _.value : 1 / 0;
        })
      },
      re = {
        setWidth: function (B) {
          return d.disabledW
            ? S.value
            : y(Math.min(R.maxWidth.value, Math.max(R.minWidth.value, B)));
        },
        setHeight: function (B) {
          return d.disabledH
            ? _.value
            : C(Math.min(R.maxHeight.value, Math.max(R.minHeight.value, B)));
        },
        setTop: function (B) {
          return d.disabledY ? A.value : N(Math.min(R.maxTop.value, Math.max(R.minTop.value, B)));
        },
        setLeft: function (B) {
          return d.disabledX ? b.value : I(Math.min(R.maxLeft.value, Math.max(R.minLeft.value, B)));
        }
      };
    return e(e({}, R), re);
  }
  ee.initLimitSizeAndMethods = r;
  var l = ["mousedown", "touchstart"],
    o = ["mouseup", "touchend"],
    c = ["mousemove", "touchmove"];
  function u(d) {
    return "touches" in d ? [d.touches[0].pageX, d.touches[0].pageY] : [d.pageX, d.pageY];
  }
  function f(d, p, E, S, _, b, A) {
    var x = p.left,
      q = p.top,
      H = p.width,
      T = p.height,
      y = p.dragging,
      C = p.id,
      N = p.setDragging,
      I = p.setEnable,
      M = p.setResizing,
      k = p.setResizingHandle,
      R = E.setTop,
      re = E.setLeft,
      B = 0,
      Ie = 0,
      xe = 0,
      de = 0,
      ae = null,
      Se = document.documentElement,
      nt = function (W) {
        var Z,
          Ce = W.target;
        (!((Z = d.value) === null || Z === void 0) && Z.contains(Ce)) ||
          (I(!1), N(!1), M(!1), k(""));
      },
      ke = function () {
        (N(!1),
          n.removeEvent(Se, o, ke),
          n.removeEvent(Se, c, je),
          (ae = null),
          b &&
            (b.updatePosition(C, { x: x.value, y: q.value, w: H.value, h: T.value }),
            b.setMatchedLine(null)));
      },
      je = function (W) {
        if ((W.preventDefault(), !!(y.value && d.value))) {
          var Z = u(W),
            Ce = Z[0],
            qe = Z[1],
            st = Ce - xe,
            wt = qe - de,
            Oe = B + st,
            pe = Ie + wt;
          if (ae !== null) {
            var Ae = {
                col: [Oe, Oe + H.value / 2, Oe + H.value],
                row: [pe, pe + T.value / 2, pe + T.value]
              },
              De = {
                row: Ae.row
                  .map(function (w, F) {
                    var X = null;
                    return (
                      Object.values(ae.row).forEach(function (ye) {
                        w >= ye.min && w <= ye.max && (X = ye.value);
                      }),
                      X !== null &&
                        (F === 0
                          ? (pe = X)
                          : F === 1
                            ? (pe = Math.floor(X - T.value / 2))
                            : F === 2 && (pe = Math.floor(X - T.value))),
                      X
                    );
                  })
                  .filter(function (w) {
                    return w !== null;
                  }),
                col: Ae.col
                  .map(function (w, F) {
                    var X = null;
                    return (
                      Object.values(ae.col).forEach(function (ye) {
                        w >= ye.min && w <= ye.max && (X = ye.value);
                      }),
                      X !== null &&
                        (F === 0
                          ? (Oe = X)
                          : F === 1
                            ? (Oe = Math.floor(X - H.value / 2))
                            : F === 2 && (Oe = Math.floor(X - H.value))),
                      X
                    );
                  })
                  .filter(function (w) {
                    return w !== null;
                  })
              };
            b.setMatchedLine(De);
          }
          _("dragging", { x: re(Oe), y: R(pe) });
        }
      },
      vt = function (W) {
        S.value &&
          (N(!0),
          (B = x.value),
          (Ie = q.value),
          (xe = u(W)[0]),
          (de = u(W)[1]),
          n.addEvent(Se, c, je),
          n.addEvent(Se, o, ke),
          b && !b.disabled.value && (ae = n.getReferenceLineMap(b, A, C)));
      };
    return (
      t.watch(y, function (W, Z) {
        !Z && W
          ? (_("drag-start", { x: x.value, y: q.value }), I(!0), N(!0))
          : (_("drag-end", { x: x.value, y: q.value }), N(!1));
      }),
      t.onMounted(function () {
        var W = d.value;
        W &&
          ((W.style.left = x + "px"),
          (W.style.top = q + "px"),
          n.addEvent(Se, l, nt),
          n.addEvent(W, l, vt));
      }),
      t.onUnmounted(function () {
        d.value && (n.removeEvent(Se, l, nt), n.removeEvent(Se, o, ke), n.removeEvent(Se, c, je));
      }),
      { containerRef: d }
    );
  }
  ee.initDraggableContainer = f;
  function h(d, p, E, S, _) {
    var b = p.setWidth,
      A = p.setHeight,
      x = p.setLeft,
      q = p.setTop,
      H = d.width,
      T = d.height,
      y = d.left,
      C = d.top,
      N = d.aspectRatio,
      I = d.setResizing,
      M = d.setResizingHandle,
      k = d.setResizingMaxWidth,
      R = d.setResizingMaxHeight,
      re = d.setResizingMinWidth,
      B = d.setResizingMinHeight,
      Ie = E.parentWidth,
      xe = E.parentHeight,
      de = 0,
      ae = 0,
      Se = 0,
      nt = 0,
      ke = 0,
      je = 0,
      vt = 1,
      W = "",
      Z = "",
      Ce = document.documentElement,
      qe = function (pe) {
        pe.preventDefault();
        var Ae = u(pe),
          De = Ae[0],
          w = Ae[1],
          F = De - ke,
          X = w - je,
          ye = F,
          Ls = X;
        (S.lockAspectRatio &&
          ((F = Math.abs(F)),
          (X = F * vt),
          (ye < 0 || (Z === "m" && Ls < 0)) && ((F = -F), (X = -X))),
          W === "t" ? (A(ae - X), q(nt - (T.value - ae))) : W === "b" && A(ae + X),
          Z === "l" ? (b(de - F), x(Se - (H.value - de))) : Z === "r" && b(de + F),
          _("resizing", { x: y.value, y: C.value, w: H.value, h: T.value }));
      },
      st = function () {
        (_("resize-end", { x: y.value, y: C.value, w: H.value, h: T.value }),
          M(""),
          I(!1),
          k(1 / 0),
          R(1 / 0),
          re(S.minW),
          B(S.minH),
          n.removeEvent(Ce, c, qe),
          n.removeEvent(Ce, o, st));
      },
      wt = function (pe, Ae) {
        if (S.resizable) {
          (pe.stopPropagation(),
            M(Ae),
            I(!0),
            (W = Ae[0]),
            (Z = Ae[1]),
            S.lockAspectRatio &&
              (["tl", "tm", "ml", "bl"].includes(Ae)
                ? ((W = "t"), (Z = "l"))
                : ((W = "b"), (Z = "r"))));
          var De = S.minH,
            w = S.minW;
          if (
            (S.lockAspectRatio && (De / w > N.value ? (w = De / N.value) : (De = w * N.value)),
            re(w),
            B(De),
            S.parent)
          ) {
            var F = W === "t" ? C.value + T.value : xe.value - C.value,
              X = Z === "l" ? y.value + H.value : Ie.value - y.value;
            (S.lockAspectRatio && (F / X < N.value ? (X = F / N.value) : (F = X * N.value)),
              R(F),
              k(X));
          }
          ((de = H.value), (ae = T.value), (Se = y.value), (nt = C.value));
          var ye = u(pe);
          ((ke = ye[0]),
            (je = ye[1]),
            (vt = N.value),
            _("resize-start", { x: y.value, y: C.value, w: H.value, h: T.value }),
            n.addEvent(Ce, c, qe),
            n.addEvent(Ce, o, st));
        }
      };
    t.onUnmounted(function () {
      (n.removeEvent(Ce, o, st), n.removeEvent(Ce, c, qe));
    });
    var Oe = t.computed(function () {
      return S.resizable ? n.filterHandles(S.handles) : [];
    });
    return { handlesFiltered: Oe, resizeHandleDown: wt };
  }
  ee.initResizeHandle = h;
  function g(d, p) {
    var E = p.setWidth,
      S = p.setHeight,
      _ = p.setLeft,
      b = p.setTop;
    (t.watch(
      function () {
        return d.w;
      },
      function (A) {
        E(A);
      }
    ),
      t.watch(
        function () {
          return d.h;
        },
        function (A) {
          S(A);
        }
      ),
      t.watch(
        function () {
          return d.x;
        },
        function (A) {
          _(A);
        }
      ),
      t.watch(
        function () {
          return d.y;
        },
        function (A) {
          b(A);
        }
      ));
  }
  return ((ee.watchProps = g), ee);
}
var ri;
function Ir() {
  return (
    ri ||
      ((ri = 1),
      (function (e) {
        var t =
            (ot && ot.__assign) ||
            function () {
              return (
                (t =
                  Object.assign ||
                  function (c) {
                    for (var u, f = 1, h = arguments.length; f < h; f++) {
                      u = arguments[f];
                      for (var g in u) Object.prototype.hasOwnProperty.call(u, g) && (c[g] = u[g]);
                    }
                    return c;
                  }),
                t.apply(this, arguments)
              );
            },
          n =
            (ot && ot.__spreadArrays) ||
            function () {
              for (var c = 0, u = 0, f = arguments.length; u < f; u++) c += arguments[u].length;
              for (var h = Array(c), g = 0, u = 0; u < f; u++)
                for (var d = arguments[u], p = 0, E = d.length; p < E; p++, g++) h[g] = d[p];
              return h;
            };
        ((e.__esModule = !0), (e.ALL_HANDLES = void 0));
        var s = Rs(),
          i = Ic(),
          a = ws();
        e.ALL_HANDLES = ["tl", "tm", "tr", "ml", "mr", "bl", "bm", "br"];
        var r = {
            initW: { type: Number, default: null },
            initH: { type: Number, default: null },
            w: { type: Number, default: 0 },
            h: { type: Number, default: 0 },
            x: { type: Number, default: 0 },
            y: { type: Number, default: 0 },
            draggable: { type: Boolean, default: !0 },
            resizable: { type: Boolean, default: !0 },
            disabledX: { type: Boolean, default: !1 },
            disabledY: { type: Boolean, default: !1 },
            disabledW: { type: Boolean, default: !1 },
            disabledH: { type: Boolean, default: !1 },
            minW: { type: Number, default: 20 },
            minH: { type: Number, default: 20 },
            active: { type: Boolean, default: !1 },
            parent: { type: Boolean, default: !1 },
            handles: {
              type: Array,
              default: e.ALL_HANDLES,
              validator: function (c) {
                return a.filterHandles(c).length === c.length;
              }
            },
            classNameDraggable: { type: String, default: "draggable" },
            classNameResizable: { type: String, default: "resizable" },
            classNameDragging: { type: String, default: "dragging" },
            classNameResizing: { type: String, default: "resizing" },
            classNameActive: { type: String, default: "active" },
            classNameHandle: { type: String, default: "handle" },
            lockAspectRatio: { type: Boolean, default: !1 }
          },
          l = [
            "activated",
            "deactivated",
            "drag-start",
            "resize-start",
            "dragging",
            "resizing",
            "drag-end",
            "resize-end",
            "update:w",
            "update:h",
            "update:x",
            "update:y",
            "update:active"
          ],
          o = s.defineComponent({
            name: "Vue3DraggableResizable",
            props: r,
            emits: l,
            setup: function (c, u) {
              var f = u.emit,
                h = i.initState(c, f),
                g = s.inject("identity", Symbol()),
                d = null;
              g === a.IDENTITY &&
                (d = {
                  updatePosition: s.inject("updatePosition"),
                  getPositionStore: s.inject("getPositionStore"),
                  disabled: s.inject("disabled"),
                  adsorbParent: s.inject("adsorbParent"),
                  adsorbCols: s.inject("adsorbCols"),
                  adsorbRows: s.inject("adsorbRows"),
                  setMatchedLine: s.inject("setMatchedLine")
                });
              var p = s.ref(),
                E = i.initParent(p),
                S = i.initLimitSizeAndMethods(c, E, h);
              i.initDraggableContainer(p, h, S, s.toRef(c, "draggable"), f, d, E);
              var _ = i.initResizeHandle(h, S, E, c, f);
              return (
                i.watchProps(c, S),
                t(t(t(t({ containerRef: p, containerProvider: d }, h), E), S), _)
              );
            },
            computed: {
              style: function () {
                return {
                  width: this.width + "px",
                  height: this.height + "px",
                  top: this.top + "px",
                  left: this.left + "px"
                };
              },
              klass: function () {
                var c;
                return (
                  (c = {}),
                  (c[this.classNameActive] = this.enable),
                  (c[this.classNameDragging] = this.dragging),
                  (c[this.classNameResizing] = this.resizing),
                  (c[this.classNameDraggable] = this.draggable),
                  (c[this.classNameResizable] = this.resizable),
                  c
                );
              }
            },
            mounted: function () {
              if (this.containerRef) {
                this.containerRef.ondragstart = function () {
                  return !1;
                };
                var c = a.getElSize(this.containerRef),
                  u = c.width,
                  f = c.height;
                (this.setWidth(this.initW === null ? this.w || u : this.initW),
                  this.setHeight(this.initH === null ? this.h || f : this.initH),
                  this.containerProvider &&
                    this.containerProvider.updatePosition(this.id, {
                      x: this.left,
                      y: this.top,
                      w: this.width,
                      h: this.height
                    }));
              }
            },
            render: function () {
              var c = this;
              return s.h(
                "div",
                { ref: "containerRef", class: ["vdr-container", this.klass], style: this.style },
                n(
                  [this.$slots.default && this.$slots.default()],
                  this.handlesFiltered.map(function (u) {
                    return s.h("div", {
                      class: [
                        "vdr-handle",
                        "vdr-handle-" + u,
                        c.classNameHandle,
                        c.classNameHandle + "-" + u
                      ],
                      style: { display: c.enable ? "block" : "none" },
                      onMousedown: function (f) {
                        return c.resizeHandleDown(f, u);
                      },
                      onTouchstart: function (f) {
                        return c.resizeHandleDown(f, u);
                      }
                    });
                  })
                )
              );
            }
          });
        e.default = o;
      })(ot)),
    ot
  );
}
var xt = {},
  ai;
function oi() {
  return (
    ai ||
      ((ai = 1),
      (function (e) {
        var t =
          (xt && xt.__spreadArrays) ||
          function () {
            for (var i = 0, a = 0, r = arguments.length; a < r; a++) i += arguments[a].length;
            for (var l = Array(i), o = 0, a = 0; a < r; a++)
              for (var c = arguments[a], u = 0, f = c.length; u < f; u++, o++) l[o] = c[u];
            return l;
          };
        e.__esModule = !0;
        var n = Rs(),
          s = ws();
        e.default = n.defineComponent({
          name: "DraggableContainer",
          props: {
            disabled: { type: Boolean, default: !1 },
            adsorbParent: { type: Boolean, default: !0 },
            adsorbCols: { type: Array, default: null },
            adsorbRows: { type: Array, default: null },
            referenceLineVisible: { type: Boolean, default: !0 },
            referenceLineColor: { type: String, default: "#f00" }
          },
          setup: function (i) {
            var a = n.reactive({}),
              r = function (h, g) {
                a[h] = g;
              },
              l = function (h) {
                var g = Object.assign({}, a);
                return (h && delete g[h], g);
              },
              o = n.reactive({ matchedLine: null }),
              c = n.computed(function () {
                return (o.matchedLine && o.matchedLine.row) || [];
              }),
              u = n.computed(function () {
                return (o.matchedLine && o.matchedLine.col) || [];
              }),
              f = function (h) {
                o.matchedLine = h;
              };
            return (
              n.provide("identity", s.IDENTITY),
              n.provide("updatePosition", r),
              n.provide("getPositionStore", l),
              n.provide("setMatchedLine", f),
              n.provide("disabled", n.toRef(i, "disabled")),
              n.provide("adsorbParent", n.toRef(i, "adsorbParent")),
              n.provide("adsorbCols", i.adsorbCols || []),
              n.provide("adsorbRows", i.adsorbRows || []),
              { matchedRows: c, matchedCols: u }
            );
          },
          methods: {
            renderReferenceLine: function () {
              var i = this;
              return this.referenceLineVisible
                ? t(
                    this.matchedCols.map(function (a) {
                      return n.h("div", {
                        style: {
                          width: "0",
                          height: "100%",
                          top: "0",
                          left: a + "px",
                          borderLeft: "1px dashed " + i.referenceLineColor,
                          position: "absolute"
                        }
                      });
                    }),
                    this.matchedRows.map(function (a) {
                      return n.h("div", {
                        style: {
                          width: "100%",
                          height: "0",
                          left: "0",
                          top: a + "px",
                          borderTop: "1px dashed " + i.referenceLineColor,
                          position: "absolute"
                        }
                      });
                    })
                  )
                : [];
            }
          },
          render: function () {
            return n.h(
              "div",
              { style: { width: "100%", height: "100%", position: "relative" } },
              t([this.$slots.default && this.$slots.default()], this.renderReferenceLine())
            );
          }
        });
      })(xt)),
    xt
  );
}
var li;
function Cc() {
  return (
    li ||
      ((li = 1),
      (function (e) {
        var t =
          (Dt && Dt.__createBinding) ||
          (Object.create
            ? function (a, r, l, o) {
                (o === void 0 && (o = l),
                  Object.defineProperty(a, o, {
                    enumerable: !0,
                    get: function () {
                      return r[l];
                    }
                  }));
              }
            : function (a, r, l, o) {
                (o === void 0 && (o = l), (a[o] = r[l]));
              });
        e.__esModule = !0;
        var n = Ir(),
          s = oi();
        n.default.install = function (a) {
          return (
            a.component(n.default.name, n.default),
            a.component(s.default.name, s.default),
            a
          );
        };
        var i = oi();
        (t(e, i, "default", "DraggableContainer"), (e.default = n.default));
      })(Dt)),
    Dt
  );
}
var Oc = Cc();
const Ac = la(Oc),
  Mc = le({
    name: "UWindowBar",
    __name: "u-window-bar",
    props: {
      title: { type: String, default: "Application" },
      isMaximized: { type: Boolean, default: !1 }
    },
    emits: ["close", "minimize", "maximize"],
    setup(e) {
      return (t, n) => (
        L(),
        j(
          ca,
          {
            density: "compact",
            flat: "",
            color: "transparent",
            class: "u-window-drag-handle border-b-thin glass-panel",
            onDblclick: n[6] || (n[6] = Me((s) => t.$emit("maximize"), ["stop"]))
          },
          {
            prepend: v(() => [
              m(
                kn,
                {
                  icon: "",
                  onClick: n[0] || (n[0] = Me((s) => t.$emit("minimize"), ["stop"])),
                  onMousedown: n[1] || (n[1] = Me(() => {}, ["stop"])),
                  size: "small"
                },
                { default: v(() => [m(ut, { icon: "fal fa-minus" })]), _: 1 }
              )
            ]),
            append: v(() => [
              m(
                kn,
                {
                  icon: "",
                  size: "small",
                  color: "error",
                  onClick: n[4] || (n[4] = Me((s) => t.$emit("close"), ["stop"])),
                  onMousedown: n[5] || (n[5] = Me(() => {}, ["stop"]))
                },
                { default: v(() => [m(ut, { icon: "fal fa-times" })]), _: 1 }
              )
            ]),
            default: v(() => [
              m(
                _a,
                { class: "text-subtitle-2 text-center pl-0" },
                {
                  default: v(() => [
                    me(Ue(e.title) + " ", 1),
                    m(
                      kn,
                      {
                        icon: "",
                        size: "small",
                        onClick: n[2] || (n[2] = Me((s) => t.$emit("maximize"), ["stop"])),
                        onMousedown: n[3] || (n[3] = Me(() => {}, ["stop"]))
                      },
                      {
                        default: v(() => [
                          m(
                            ut,
                            { icon: e.isMaximized ? "fal fa-compress" : "fal fa-expand" },
                            null,
                            8,
                            ["icon"]
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
      );
    }
  }),
  Rc = ie(Mc, [["__scopeId", "data-v-757a3607"]]),
  wc = le({
    name: "UWindowShell",
    __name: "u-window-shell",
    props: {
      title: { type: String, default: "Application" },
      icon: { type: String, default: "" },
      variant: { type: String, default: "glassmorphic" },
      isMaximized: { type: Boolean, default: !1 },
      blur: { type: Number, default: 20 }
    },
    emits: ["close", "minimize", "maximize"],
    setup(e) {
      return (t, n) => {
        const s = D("u-rail");
        return (
          L(),
          j(
            yi,
            {
              class: _t(["u-window", [`u-window--${e.variant}`]]),
              style: Be({ "--window-blur": `${e.blur}px` })
            },
            {
              default: v(() => [
                Re(
                  t.$slots,
                  "app-bar",
                  {},
                  () => [
                    m(
                      Rc,
                      {
                        title: e.title,
                        "is-maximized": e.isMaximized,
                        onMinimize: n[0] || (n[0] = (i) => t.$emit("minimize")),
                        onMaximize: n[1] || (n[1] = (i) => t.$emit("maximize")),
                        onClose: n[2] || (n[2] = (i) => t.$emit("close"))
                      },
                      null,
                      8,
                      ["title", "is-maximized"]
                    )
                  ],
                  !0
                ),
                Re(
                  t.$slots,
                  "navigation",
                  {},
                  () => [
                    t.$slots["nav-content"]
                      ? (L(),
                        j(
                          s,
                          {
                            key: 0,
                            class: "u-window-drawer",
                            permanent: "",
                            "expand-on-hover": "",
                            rail: ""
                          },
                          { default: v(() => [Re(t.$slots, "nav-content", {}, void 0, !0)]), _: 3 }
                        ))
                      : Wt("", !0)
                  ],
                  !0
                ),
                m(
                  _i,
                  { class: "u-window__body", onWheel: n[3] || (n[3] = Me(() => {}, ["stop"])) },
                  { default: v(() => [Re(t.$slots, "default", {}, void 0, !0)]), _: 3 }
                )
              ]),
              _: 3
            },
            8,
            ["class", "style"]
          )
        );
      };
    }
  }),
  Lc = ie(wc, [["__scopeId", "data-v-c9dc2154"]]),
  Dc = le({
    __name: "u-window",
    props: {
      id: { type: String, required: !0 },
      title: { type: String, default: "Application" },
      icon: { type: String, default: "fal fa-window-maximize" },
      width: { type: Number, default: 800 },
      height: { type: Number, default: 600 },
      x: { type: Number, default: 100 },
      y: { type: Number, default: 100 },
      variant: { type: String, default: "glassmorphic" },
      maximized: { type: Boolean, default: !1 }
    },
    setup(e) {
      const t = e,
        n = Si(),
        { mainRect: s } = ua();
      vi(() => {
        n.registerWindow(t.id, {
          title: t.title,
          icon: t.icon,
          width: t.width,
          height: t.height,
          x: t.x,
          y: t.y,
          variant: t.variant,
          isMaximized: t.maximized
        });
      });
      const i = z(() => n.windows[t.id]),
        a = z({
          get: () => (i.value?.isMaximized ? 0 : i.value?.x || t.x),
          set: (_) => {
            i.value?.isMaximized || u({ x: _ });
          }
        }),
        r = z({
          get: () => (i.value?.isMaximized ? 0 : i.value?.y || t.y),
          set: (_) => {
            i.value?.isMaximized || u({ y: _ });
          }
        }),
        l = z({
          get: () =>
            i.value?.isMaximized
              ? s.value?.width || (typeof window < "u" ? window.innerWidth : 600)
              : i.value?.width || t.width,
          set: (_) => {
            const b = Number(_);
            !b || isNaN(b) || i.value?.isMaximized || u({ width: b });
          }
        }),
        o = z({
          get: () =>
            i.value?.isMaximized
              ? s.value?.height || (typeof window < "u" ? window.innerHeight : 400)
              : i.value?.height || t.height,
          set: (_) => {
            const b = Number(_);
            !b || isNaN(b) || i.value?.isMaximized || u({ height: b });
          }
        }),
        c = z({
          get: () => n.activeWindowId === t.id,
          set: (_) => {
            _ && d();
          }
        }),
        u = (_) => {
          n.updateWindow(t.id, _);
        },
        f = () => n.closeWindow(t.id),
        h = () => n.toggleMinimize(t.id),
        g = () => n.toggleMaximize(t.id),
        d = () => n.focusWindow(t.id);
      let p = !1;
      const E = () => {
          if (!p) {
            p = !0;
            const _ = s.value;
            (_ &&
              n.setLayoutBounds({
                left: _.left || 0,
                top: _.top || 0,
                width: _.width || window.innerWidth,
                height: _.height || window.innerHeight
              }),
              n.startDrag(t.id));
          }
        },
        S = () => {
          p = !1;
        };
      return (_, b) =>
        i.value
          ? (L(),
            j(
              ve(Ac),
              {
                key: 0,
                x: a.value,
                "onUpdate:x": b[0] || (b[0] = (A) => (a.value = A)),
                y: r.value,
                "onUpdate:y": b[1] || (b[1] = (A) => (r.value = A)),
                w: l.value,
                "onUpdate:w": b[2] || (b[2] = (A) => (l.value = A)),
                h: o.value,
                "onUpdate:h": b[3] || (b[3] = (A) => (o.value = A)),
                active: c.value,
                "onUpdate:active": b[4] || (b[4] = (A) => (c.value = A)),
                z: i.value.zIndex,
                "min-width": 200,
                "min-height": 100,
                parent: !1,
                draggable: !i.value.isMaximized,
                resizable: !i.value.isMaximized,
                "disable-user-select": !0,
                "class-name-dragging": "u-window--dragging",
                "class-name-resizing": "u-window--resizing",
                handle: ".u-window-drag-handle",
                onMousedown: Me(d, ["stop"]),
                onTouchstart: Me(d, ["stop"]),
                onPointerdown: Me(d, ["stop"]),
                onDragging: E,
                onDragStop: S,
                class: _t([
                  "u-window-wrapper",
                  {
                    "u-window-wrapper--maximized": i.value.isMaximized,
                    "u-window-wrapper--minimized": i.value.isMinimized,
                    "u-window--pulsed": !ve(n).isDesktopVisible
                  }
                ]),
                style: Be({
                  display: i.value.isMinimized ? "none" : "block",
                  "--window-blur": `${ve(n).globalBlur}px`,
                  zIndex: i.value?.zIndex || 500
                })
              },
              {
                default: v(() => [
                  m(
                    Lc,
                    {
                      title: i.value.title,
                      icon: i.value.icon,
                      variant: i.value.variant,
                      "is-maximized": i.value.isMaximized,
                      blur: ve(n).globalBlur,
                      onMinimize: h,
                      onMaximize: g,
                      onClose: f
                    },
                    {
                      "app-bar": v(() => [Re(_.$slots, "app-bar", {}, void 0, !0)]),
                      "app-bar-title": v(() => [Re(_.$slots, "app-bar-title", {}, void 0, !0)]),
                      "app-bar-actions": v(() => [Re(_.$slots, "app-bar-actions", {}, void 0, !0)]),
                      navigation: v(() => [Re(_.$slots, "navigation", {}, void 0, !0)]),
                      "nav-content": v(() => [Re(_.$slots, "nav-content", {}, void 0, !0)]),
                      default: v(() => [Re(_.$slots, "default", {}, void 0, !0)]),
                      _: 3
                    },
                    8,
                    ["title", "icon", "variant", "is-maximized", "blur"]
                  )
                ]),
                _: 3
              },
              8,
              ["x", "y", "w", "h", "active", "z", "draggable", "resizable", "class", "style"]
            ))
          : Wt("", !0);
    }
  }),
  Pc = ie(Dc, [["__scopeId", "data-v-0a51d7a9"]]),
  xc = le({
    name: "UWebtop",
    components: { UWindow: Pc },
    setup() {
      const e = Si(),
        t = z(() => Mo.filter((s) => e.runningApps?.has(s.id))),
        n = z(() => (e.openWindows || []).filter((s) => !!s.component));
      return { store: e, activeApps: t, managedWindows: n };
    }
  }),
  kc = { class: "u-webtop ui-layer fill-height position-relative" };
function Vc(e, t, n, s, i, a) {
  const r = D("u-window");
  return (
    L(),
    Te("div", kc, [
      (L(!0),
      Te(
        et,
        null,
        yt(
          e.managedWindows,
          (l) => (
            L(),
            j(
              r,
              We({ key: l.id }, { ref_for: !0 }, l, {
                onClose: (o) => e.store.closeWindow(l.id),
                onMinimize: (o) => e.store.toggleMinimize(l.id),
                onMaximize: (o) => e.store.toggleMaximize(l.id),
                onFocus: (o) => e.store.focusWindow(l.id)
              }),
              {
                default: v(() => [
                  (L(), j(Vs(l.component), We({ ref_for: !0 }, l.props), null, 16))
                ]),
                _: 2
              },
              1040,
              ["onClose", "onMinimize", "onMaximize", "onFocus"]
            )
          )
        ),
        128
      )),
      (L(!0),
      Te(
        et,
        null,
        yt(e.activeApps, (l) => (L(), j(Vs(l.component), { key: l.id }))),
        128
      )),
      Re(e.$slots, "default", {}, void 0, !0)
    ])
  );
}
const $c = ie(xc, [
    ["render", Vc],
    ["__scopeId", "data-v-4b3ff0ab"]
  ]),
  Bc = le({
    name: "XCompassLayout",
    components: {
      userAvatarBtn: bi,
      systemBar: no,
      xAppBarMolecule: co,
      trinityRingsSpinner: ao,
      xBackgroundSmoke: fa,
      xCompassFooter: po,
      xCompassPluginSheet: To,
      ContextNavigationDrawer: Ao,
      UWebtop: $c
    },
    setup() {
      const e = gt(),
        t = pa(),
        { breadtrail: n } = Ei(),
        s = !1,
        i = "wpwrap",
        a = "wp-responsive-open",
        r = ["Off", "On"],
        l = document.body,
        o = z(() => e.blogInfo),
        c = z(() => e.isBillboardOff),
        u = z(() => e.isAppBarOff),
        f = z(() => e.currentUser),
        h = z(() => {
          const y = e.pluginList || [];
          return di.filter(y, (C) => C.isActivated);
        }),
        g = z(() => t.getRoutes()),
        d = z({ get: () => e.activePlugin, set: (y) => e.setActivePlugin(y) }),
        p = z({ get: () => e.bottomSheet, set: (y) => e.setBottomSheet(y) }),
        E = z({ get: () => e.isWpMenuOpen, set: (y) => e.setWpMenu(y) }),
        S = z(() => r[E.value ? 1 : 0]),
        _ = z({ get: () => e.appBarOrder, set: () => e.toggleAppBarOrder() }),
        b = () => {
          l &&
            (E.value ? l.classList.add(a) : l.classList.remove(a),
            l.classList.toggle("compass-menu-closed", !E.value));
        },
        A = () => {
          window.location.href = "/";
        },
        x = () => {
          p.value = !p.value;
        },
        q = () => {
          E.value = !E.value;
        },
        H = () => {
          e.toggleAppBarOrder();
        },
        T = (y) => {
          if (!y) return "/";
          const C = `/${y.replace("xophz-compass-", "")}`,
            N = g.value.find((I) => I.path === C);
          return N ? N.path : "/";
        };
      return (
        da(E, (y) => {
          l && (l.classList.toggle(a, y), l.classList.toggle("compass-menu-closed", !y));
        }),
        vi(() => {
          b();
        }),
        {
          spinner: s,
          wpMenuId: i,
          wpOpenClass: a,
          wpSwitchLabels: r,
          blogInfo: o,
          isBillboardOff: c,
          isAppBarOff: u,
          currentUser: f,
          plugins: h,
          routes: g,
          activePlugin: d,
          bottomSheet: p,
          isWpMenuOpen: E,
          wpSwitchLabel: S,
          appBarOrder: _,
          goHome: A,
          showBottomSheet: x,
          wpmenu: q,
          toggleAppBarOrder: H,
          getTextDomainPath: T,
          breadtrail: n,
          compassStore: e
        }
      );
    }
  });
function Hc(e, t, n, s, i, a) {
  const r = D("x-background-smoke"),
    l = D("context-navigation-drawer"),
    o = D("trinity-rings-spinner"),
    c = D("system-bar"),
    u = D("router-view"),
    f = D("x-compass-plugin-sheet");
  return (
    L(),
    j(
      yi,
      { class: "fill-height w-100 youmeos-public-app" },
      {
        default: v(() => [
          m(r),
          m(l),
          e.spinner
            ? (L(), j(o, { key: 0 }))
            : (L(),
              Te(
                et,
                { key: 1 },
                [m(c), m(_i, { class: "fill-height" }, { default: v(() => [m(u)]), _: 1 }), m(f)],
                64
              ))
        ]),
        _: 1
      }
    )
  );
}
const zc = ie(Bc, [["render", Hc]]),
  eu = Object.freeze(
    Object.defineProperty({ __proto__: null, default: zc }, Symbol.toStringTag, { value: "Module" })
  );
export { Pc as U, eu as x };
