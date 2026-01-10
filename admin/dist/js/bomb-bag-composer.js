import {
  d as E,
  a6 as L,
  ae as U,
  ce as Y,
  an as d,
  v as x,
  a8 as H,
  a7 as R,
  a as F,
  r as p,
  c as z,
  o as A,
  w as n,
  e as o,
  b as s,
  g as C,
  i as l,
  t as b,
  cu as I,
  W as B,
  l as P,
  bm as G,
  bn as J,
  Y as O
} from "./index.js";
import { u as W } from "./bomb-bag.store.js";
import { V as T } from "./VRow.js";
import { V as y } from "./VCol.js";
import { V as q } from "./VTextarea.js";
import { V as K } from "./VSpacer.js";
import { V as Q } from "./VContainer.js";
/* empty css     */ const X = E({
    name: "BombBagComposer",
    setup() {
      const a = L(),
        e = U(),
        u = W(),
        { currentCampaign: w, currentCampaignLoading: S } = Y(u),
        c = d(""),
        r = d(""),
        V = d("desktop"),
        m = d(!1),
        f = d(!1),
        g = d(!1),
        v = d(!1),
        t = d({ show: !1, text: "", color: "success" }),
        j = x(() => w.value?.status === "draft" && c.value && r.value),
        M = x(() =>
          r.value
            .replace(/\{\{first_name\}\}/g, "John")
            .replace(/\{\{last_name\}\}/g, "Doe")
            .replace(/\{\{email\}\}/g, "john@example.com")
            .replace(/\{\{unsubscribe_url\}\}/g, "#unsubscribe")
        );
      H(async () => {
        const i = Number(e.params.id);
        i && (await u.fetchCampaign(i));
      }),
        R(w, (i) => {
          i && ((c.value = i.subject), (r.value = i.content || h()));
        });
      function h() {
        return `<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
  <h1>Hello {{first_name}}!</h1>
  
  <p>Your content goes here...</p>
  
  <p>Best regards,<br>Your Team</p>
  
  <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">
  
  <p style="font-size: 12px; color: #999;">
    <a href="{{unsubscribe_url}}">Unsubscribe</a>
  </p>
</body>
</html>`;
      }
      async function k() {
        m.value = !0;
        try {
          await u.updateCampaign(Number(e.params.id), { subject: c.value, content: r.value }),
            (t.value = { show: !0, text: "Content saved", color: "success" });
        } catch {
          t.value = { show: !0, text: "Failed to save", color: "error" };
        } finally {
          m.value = !1;
        }
      }
      async function N() {
        f.value = !0;
        try {
          await k();
          const i = await u.sendTestEmail(Number(e.params.id));
          t.value = { show: !0, text: i.message, color: "success" };
        } catch {
          t.value = { show: !0, text: "Failed to send test", color: "error" };
        } finally {
          f.value = !1;
        }
      }
      function D() {
        v.value = !0;
      }
      async function _() {
        g.value = !0;
        try {
          await k();
          const i = await u.sendCampaign(Number(e.params.id));
          (t.value = { show: !0, text: i.message, color: "success" }),
            (v.value = !1),
            a.push({ name: "Bomb Bag Campaigns" });
        } catch {
          t.value = { show: !0, text: "Failed to send", color: "error" };
        } finally {
          g.value = !1;
        }
      }
      function $() {
        a.push({ name: "Bomb Bag Campaigns" });
      }
      return {
        campaign: w,
        subject: c,
        content: r,
        previewMode: V,
        previewContent: M,
        saving: m,
        sendingTest: f,
        sending: g,
        sendDialog: v,
        snackbar: t,
        canSend: j,
        saveContent: k,
        sendTestEmail: N,
        confirmSend: D,
        sendCampaign: _,
        goBack: $
      };
    }
  }),
  Z = { class: "d-flex align-center justify-space-between flex-wrap ga-2" },
  ee = { class: "d-flex align-center" },
  ae = { class: "ms-2" },
  oe = { class: "d-flex ga-2" },
  ne = { class: "editor-container" },
  se = { class: "d-flex align-center justify-space-between mb-4" },
  te = { class: "preview-header" },
  le = ["innerHTML"],
  ie = { class: "my-4 pa-4 bg-surface-variant rounded" };
function re(a, e, u, w, S, c) {
  const r = p("x-btn"),
    V = p("x-text-field"),
    m = p("x-glass-card"),
    f = p("x-card"),
    g = p("x-dialog"),
    v = p("x-snackbar");
  return (
    A(),
    z(
      Q,
      { fluid: "", class: "bomb-bag-composer pa-6" },
      {
        default: n(() => [
          o(
            T,
            { class: "mb-6" },
            {
              default: n(() => [
                o(y, null, {
                  default: n(() => [
                    s("div", Z, [
                      s("div", ee, [
                        o(
                          r,
                          { icon: "", variant: "text", onClick: a.goBack },
                          {
                            default: n(() => [
                              o(C, null, {
                                default: n(() => [...(e[7] || (e[7] = [l("mdi-arrow-left", -1)]))]),
                                _: 1
                              })
                            ]),
                            _: 1
                          },
                          8,
                          ["onClick"]
                        ),
                        s("h2", ae, b(a.campaign?.name || "Loading..."), 1)
                      ]),
                      s("div", oe, [
                        o(
                          r,
                          {
                            variant: "outlined",
                            "prepend-icon": "mdi-send",
                            loading: a.sendingTest,
                            onClick: a.sendTestEmail
                          },
                          {
                            default: n(() => [...(e[8] || (e[8] = [l(" Send Test ", -1)]))]),
                            _: 1
                          },
                          8,
                          ["loading", "onClick"]
                        ),
                        o(
                          r,
                          {
                            color: "primary",
                            "prepend-icon": "mdi-content-save",
                            loading: a.saving,
                            onClick: a.saveContent
                          },
                          { default: n(() => [...(e[9] || (e[9] = [l(" Save ", -1)]))]), _: 1 },
                          8,
                          ["loading", "onClick"]
                        ),
                        o(
                          r,
                          {
                            color: "success",
                            "prepend-icon": "mdi-rocket-launch",
                            disabled: !a.canSend,
                            onClick: a.confirmSend
                          },
                          {
                            default: n(() => [...(e[10] || (e[10] = [l(" Send Campaign ", -1)]))]),
                            _: 1
                          },
                          8,
                          ["disabled", "onClick"]
                        )
                      ])
                    ])
                  ]),
                  _: 1
                })
              ]),
              _: 1
            }
          ),
          o(T, null, {
            default: n(() => [
              o(
                y,
                { cols: "12", md: "7" },
                {
                  default: n(() => [
                    o(
                      m,
                      { class: "pa-4" },
                      {
                        default: n(() => [
                          e[11] || (e[11] = s("h4", { class: "mb-4" }, "Email Content", -1)),
                          o(
                            V,
                            {
                              modelValue: a.subject,
                              "onUpdate:modelValue": e[0] || (e[0] = (t) => (a.subject = t)),
                              label: "Subject Line",
                              class: "mb-4"
                            },
                            null,
                            8,
                            ["modelValue"]
                          ),
                          s("div", ne, [
                            o(
                              q,
                              {
                                modelValue: a.content,
                                "onUpdate:modelValue": e[1] || (e[1] = (t) => (a.content = t)),
                                label: "Email Body (HTML)",
                                rows: "20",
                                class: "code-editor"
                              },
                              null,
                              8,
                              ["modelValue"]
                            )
                          ]),
                          e[12] ||
                            (e[12] = s(
                              "div",
                              { class: "mt-4" },
                              [
                                s("p", { class: "text-caption text-medium-emphasis" }, [
                                  l(" Available variables: "),
                                  s("code", null, " {{ first_name }} "),
                                  s("code", null, " {{ last_name }} "),
                                  s("code", null, " {{ email }} "),
                                  s("code", null, " {{ unsubscribe_url }} ")
                                ])
                              ],
                              -1
                            ))
                        ]),
                        _: 1
                      }
                    )
                  ]),
                  _: 1
                }
              ),
              o(
                y,
                { cols: "12", md: "5" },
                {
                  default: n(() => [
                    o(
                      m,
                      { class: "pa-4 preview-card" },
                      {
                        default: n(() => [
                          s("div", se, [
                            e[15] || (e[15] = s("h4", null, "Preview", -1)),
                            o(
                              I,
                              { density: "compact", variant: "outlined" },
                              {
                                default: n(() => [
                                  o(
                                    B,
                                    {
                                      color: a.previewMode === "desktop" ? "primary" : void 0,
                                      onClick: e[2] || (e[2] = (t) => (a.previewMode = "desktop"))
                                    },
                                    {
                                      default: n(() => [
                                        o(C, null, {
                                          default: n(() => [
                                            ...(e[13] || (e[13] = [l("mdi-monitor", -1)]))
                                          ]),
                                          _: 1
                                        })
                                      ]),
                                      _: 1
                                    },
                                    8,
                                    ["color"]
                                  ),
                                  o(
                                    B,
                                    {
                                      color: a.previewMode === "mobile" ? "primary" : void 0,
                                      onClick: e[3] || (e[3] = (t) => (a.previewMode = "mobile"))
                                    },
                                    {
                                      default: n(() => [
                                        o(C, null, {
                                          default: n(() => [
                                            ...(e[14] || (e[14] = [l("mdi-cellphone", -1)]))
                                          ]),
                                          _: 1
                                        })
                                      ]),
                                      _: 1
                                    },
                                    8,
                                    ["color"]
                                  )
                                ]),
                                _: 1
                              }
                            )
                          ]),
                          s(
                            "div",
                            { class: P(["preview-frame", a.previewMode]) },
                            [
                              s("div", te, [
                                e[16] || (e[16] = s("strong", null, "Subject:", -1)),
                                l(" " + b(a.subject || "(No subject)"), 1)
                              ]),
                              s(
                                "div",
                                { class: "preview-content", innerHTML: a.previewContent },
                                null,
                                8,
                                le
                              )
                            ],
                            2
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
          }),
          o(
            g,
            {
              modelValue: a.sendDialog,
              "onUpdate:modelValue": e[5] || (e[5] = (t) => (a.sendDialog = t)),
              "max-width": "500"
            },
            {
              default: n(() => [
                o(f, null, {
                  default: n(() => [
                    o(
                      G,
                      { class: "d-flex align-center" },
                      {
                        default: n(() => [
                          o(
                            C,
                            { color: "warning", class: "me-2" },
                            {
                              default: n(() => [...(e[17] || (e[17] = [l("mdi-alert", -1)]))]),
                              _: 1
                            }
                          ),
                          e[18] || (e[18] = l(" Send Campaign ", -1))
                        ]),
                        _: 1
                      }
                    ),
                    o(J, null, {
                      default: n(() => [
                        s("p", null, `You're about to send "` + b(a.campaign?.name) + '" to:', 1),
                        s("div", ie, [
                          s("strong", null, b(a.campaign?.total_recipients || "Unknown"), 1),
                          e[19] || (e[19] = l(" subscribers ", -1))
                        ]),
                        e[20] ||
                          (e[20] = s(
                            "p",
                            { class: "text-medium-emphasis" },
                            "This action cannot be undone.",
                            -1
                          ))
                      ]),
                      _: 1
                    }),
                    o(O, null, {
                      default: n(() => [
                        o(K),
                        o(
                          r,
                          { variant: "text", onClick: e[4] || (e[4] = (t) => (a.sendDialog = !1)) },
                          { default: n(() => [...(e[21] || (e[21] = [l(" Cancel ", -1)]))]), _: 1 }
                        ),
                        o(
                          r,
                          { color: "success", loading: a.sending, onClick: a.sendCampaign },
                          {
                            default: n(() => [...(e[22] || (e[22] = [l(" Send Now ", -1)]))]),
                            _: 1
                          },
                          8,
                          ["loading", "onClick"]
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
          ),
          o(
            v,
            {
              modelValue: a.snackbar.show,
              "onUpdate:modelValue": e[6] || (e[6] = (t) => (a.snackbar.show = t)),
              color: a.snackbar.color
            },
            { default: n(() => [l(b(a.snackbar.text), 1)]), _: 1 },
            8,
            ["modelValue", "color"]
          )
        ]),
        _: 1
      }
    )
  );
}
const be = F(X, [
  ["render", re],
  ["__scopeId", "data-v-4863e9ff"]
]);
export { be as default };
