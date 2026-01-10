import {
  d as j,
  a6 as E,
  ce as L,
  an as C,
  a8 as I,
  a as M,
  r as f,
  c as v,
  o as d,
  w as t,
  e as l,
  b as c,
  i as o,
  h as y,
  W as V,
  g as b,
  t as m,
  O as T,
  aB as W,
  bm as Y,
  bn as q,
  Y as x
} from "./index.js";
import { u as G } from "./bomb-bag.store.js";
import { V as _ } from "./VRow.js";
import { V as B } from "./VCol.js";
import { V as H } from "./VDataTable.js";
import { V as J } from "./VPagination.js";
import { V as K } from "./VSpacer.js";
import { V as Q } from "./VContainer.js";
/* empty css     */ const X = j({
    name: "BombBagCampaigns",
    setup() {
      const e = E(),
        a = G(),
        { campaigns: D, campaignsLoading: w, campaignsPerPage: S, campaignsTotalPages: P } = L(a),
        r = C(null),
        g = C(1),
        p = C(!1),
        i = C(null),
        k = [
          { title: "All", value: null },
          { title: "Draft", value: "draft" },
          { title: "Scheduled", value: "scheduled" },
          { title: "Sending", value: "sending" },
          { title: "Sent", value: "sent" },
          { title: "Paused", value: "paused" }
        ],
        n = [
          { title: "Name", key: "name" },
          { title: "Subject", key: "subject" },
          { title: "Status", key: "status" },
          { title: "Performance", key: "performance", sortable: !1 },
          { title: "Sent", key: "sent_at" },
          { title: "Actions", key: "actions", sortable: !1, align: "end" }
        ];
      I(() => {
        u();
      });
      async function u() {
        await a.fetchCampaigns({ page: g.value, per_page: 20, status: r.value || void 0 });
      }
      function $(s) {
        return (
          {
            draft: "grey",
            scheduled: "info",
            sending: "warning",
            sent: "success",
            paused: "error"
          }[s] || "grey"
        );
      }
      function h(s) {
        return s.total_sent === 0 ? 0 : Math.round((s.total_opened / s.total_sent) * 100);
      }
      function N(s) {
        return new Date(s).toLocaleDateString("en-US", {
          month: "short",
          day: "numeric",
          year: "numeric"
        });
      }
      function A() {
        e.push({ name: "Bomb Bag New Campaign" });
      }
      function U(s) {
        e.push({ name: "Bomb Bag Edit Campaign", params: { id: s } });
      }
      function z(s) {
        e.push({ name: "Bomb Bag Composer", params: { id: s } });
      }
      function O(s) {
        e.push({ name: "Bomb Bag Edit Campaign", params: { id: s } });
      }
      function R(s) {
        (i.value = s), (p.value = !0);
      }
      async function F() {
        i.value && (await a.deleteCampaign(i.value.id), (p.value = !1), (i.value = null));
      }
      return {
        campaigns: D,
        campaignsLoading: w,
        campaignsPerPage: S,
        campaignsTotalPages: P,
        statusFilter: r,
        statusOptions: k,
        currentPage: g,
        headers: n,
        deleteDialog: p,
        campaignToDelete: i,
        fetchCampaigns: u,
        getStatusColor: $,
        getOpenRate: h,
        formatDate: N,
        createCampaign: A,
        editCampaign: U,
        openComposer: z,
        viewAnalytics: O,
        confirmDelete: R,
        deleteCampaign: F
      };
    }
  }),
  Z = { class: "d-flex align-center justify-space-between" },
  ee = { key: 0, class: "performance-stats" },
  ae = { class: "performance-rate" },
  te = { key: 1, class: "text-medium-emphasis" },
  ne = { class: "d-flex ga-1" };
function le(e, a, D, w, S, P) {
  const r = f("x-btn"),
    g = f("x-select"),
    p = f("x-glass-card"),
    i = f("x-card"),
    k = f("x-dialog");
  return (
    d(),
    v(
      Q,
      { fluid: "", class: "bomb-bag-campaigns pa-6" },
      {
        default: t(() => [
          l(
            _,
            { class: "mb-6" },
            {
              default: t(() => [
                l(B, null, {
                  default: t(() => [
                    c("div", Z, [
                      a[5] || (a[5] = c("h2", null, "Campaigns", -1)),
                      l(
                        r,
                        { color: "primary", "prepend-icon": "mdi-plus", onClick: e.createCampaign },
                        {
                          default: t(() => [...(a[4] || (a[4] = [o(" New Campaign ", -1)]))]),
                          _: 1
                        },
                        8,
                        ["onClick"]
                      )
                    ])
                  ]),
                  _: 1
                })
              ]),
              _: 1
            }
          ),
          l(
            _,
            { class: "mb-4" },
            {
              default: t(() => [
                l(
                  B,
                  { cols: "12", md: "4" },
                  {
                    default: t(() => [
                      l(
                        g,
                        {
                          modelValue: e.statusFilter,
                          "onUpdate:modelValue": [
                            a[0] || (a[0] = (n) => (e.statusFilter = n)),
                            e.fetchCampaigns
                          ],
                          items: e.statusOptions,
                          label: "Filter by Status",
                          clearable: ""
                        },
                        null,
                        8,
                        ["modelValue", "items", "onUpdate:modelValue"]
                      )
                    ]),
                    _: 1
                  }
                )
              ]),
              _: 1
            }
          ),
          l(_, null, {
            default: t(() => [
              l(
                B,
                { cols: "12" },
                {
                  default: t(() => [
                    l(p, null, {
                      default: t(() => [
                        l(
                          H,
                          {
                            headers: e.headers,
                            items: e.campaigns,
                            loading: e.campaignsLoading,
                            "items-per-page": e.campaignsPerPage,
                            class: "transparent-table"
                          },
                          {
                            "item.status": t(({ item: n }) => [
                              l(
                                W,
                                {
                                  size: "small",
                                  color: e.getStatusColor(n.status),
                                  variant: "flat"
                                },
                                { default: t(() => [o(m(n.status), 1)]), _: 2 },
                                1032,
                                ["color"]
                              )
                            ]),
                            "item.performance": t(({ item: n }) => [
                              n.status === "sent"
                                ? (d(),
                                  T("div", ee, [
                                    c("span", null, m(n.total_opened) + "/" + m(n.total_sent), 1),
                                    c("span", ae, " (" + m(e.getOpenRate(n)) + "%) ", 1)
                                  ]))
                                : (d(), T("span", te, "—"))
                            ]),
                            "item.sent_at": t(({ item: n }) => [
                              o(m(n.sent_at ? e.formatDate(n.sent_at) : "—"), 1)
                            ]),
                            "item.actions": t(({ item: n }) => [
                              c("div", ne, [
                                n.status === "draft"
                                  ? (d(),
                                    v(
                                      V,
                                      {
                                        key: 0,
                                        icon: "",
                                        size: "small",
                                        variant: "text",
                                        color: "primary",
                                        onClick: (u) => e.editCampaign(n.id)
                                      },
                                      {
                                        default: t(() => [
                                          l(b, null, {
                                            default: t(() => [
                                              ...(a[6] || (a[6] = [o("mdi-pencil", -1)]))
                                            ]),
                                            _: 1
                                          })
                                        ]),
                                        _: 1
                                      },
                                      8,
                                      ["onClick"]
                                    ))
                                  : y("", !0),
                                n.status === "draft"
                                  ? (d(),
                                    v(
                                      V,
                                      {
                                        key: 1,
                                        icon: "",
                                        size: "small",
                                        variant: "text",
                                        color: "success",
                                        onClick: (u) => e.openComposer(n.id)
                                      },
                                      {
                                        default: t(() => [
                                          l(b, null, {
                                            default: t(() => [
                                              ...(a[7] || (a[7] = [o("mdi-email-edit", -1)]))
                                            ]),
                                            _: 1
                                          })
                                        ]),
                                        _: 1
                                      },
                                      8,
                                      ["onClick"]
                                    ))
                                  : y("", !0),
                                n.status === "sent"
                                  ? (d(),
                                    v(
                                      V,
                                      {
                                        key: 2,
                                        icon: "",
                                        size: "small",
                                        variant: "text",
                                        color: "info",
                                        onClick: (u) => e.viewAnalytics(n.id)
                                      },
                                      {
                                        default: t(() => [
                                          l(b, null, {
                                            default: t(() => [
                                              ...(a[8] || (a[8] = [o("mdi-chart-bar", -1)]))
                                            ]),
                                            _: 1
                                          })
                                        ]),
                                        _: 1
                                      },
                                      8,
                                      ["onClick"]
                                    ))
                                  : y("", !0),
                                l(
                                  V,
                                  {
                                    icon: "",
                                    size: "small",
                                    variant: "text",
                                    color: "error",
                                    onClick: (u) => e.confirmDelete(n)
                                  },
                                  {
                                    default: t(() => [
                                      l(b, null, {
                                        default: t(() => [
                                          ...(a[9] || (a[9] = [o("mdi-delete", -1)]))
                                        ]),
                                        _: 1
                                      })
                                    ]),
                                    _: 1
                                  },
                                  8,
                                  ["onClick"]
                                )
                              ])
                            ]),
                            bottom: t(() => [
                              l(
                                J,
                                {
                                  modelValue: e.currentPage,
                                  "onUpdate:modelValue": [
                                    a[1] || (a[1] = (n) => (e.currentPage = n)),
                                    e.fetchCampaigns
                                  ],
                                  length: e.campaignsTotalPages
                                },
                                null,
                                8,
                                ["modelValue", "length", "onUpdate:modelValue"]
                              )
                            ]),
                            _: 1
                          },
                          8,
                          ["headers", "items", "loading", "items-per-page"]
                        )
                      ]),
                      _: 1
                    })
                  ]),
                  _: 1
                }
              )
            ]),
            _: 1
          }),
          l(
            k,
            {
              modelValue: e.deleteDialog,
              "onUpdate:modelValue": a[3] || (a[3] = (n) => (e.deleteDialog = n)),
              "max-width": "400"
            },
            {
              default: t(() => [
                l(i, null, {
                  default: t(() => [
                    l(Y, null, {
                      default: t(() => [...(a[10] || (a[10] = [o("Delete Campaign", -1)]))]),
                      _: 1
                    }),
                    l(q, null, {
                      default: t(() => [
                        o(
                          ' Are you sure you want to delete "' +
                            m(e.campaignToDelete?.name) +
                            '"? This action cannot be undone. ',
                          1
                        )
                      ]),
                      _: 1
                    }),
                    l(x, null, {
                      default: t(() => [
                        l(K),
                        l(
                          r,
                          {
                            variant: "text",
                            onClick: a[2] || (a[2] = (n) => (e.deleteDialog = !1))
                          },
                          { default: t(() => [...(a[11] || (a[11] = [o(" Cancel ", -1)]))]), _: 1 }
                        ),
                        l(
                          r,
                          { color: "error", onClick: e.deleteCampaign },
                          { default: t(() => [...(a[12] || (a[12] = [o(" Delete ", -1)]))]), _: 1 },
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
        ]),
        _: 1
      }
    )
  );
}
const ge = M(X, [
  ["render", le],
  ["__scopeId", "data-v-c73d1b16"]
]);
export { ge as default };
