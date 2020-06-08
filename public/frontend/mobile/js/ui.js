 
! function(t, e) {
    "object" == typeof exports && "undefined" != typeof module ? module.exports = e(require("popper.js"), require("perfect-scrollbar")) : "function" == typeof define && define.amd ? define(["popper.js", "perfect-scrollbar"], e) : (t = t || self).coreui = e(t.Popper, t.PerfectScrollbar)
}(this, (function(t, e) {
    "use strict";

    function n(t, e) {
        for (var n = 0; n < e.length; n++) {
            var i = e[n];
            i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(t, i.key, i)
        }
    }

    function i(t, e, i) {
        return e && n(t.prototype, e), i && n(t, i), t
    }

    function o(t, e, n) {
        return e in t ? Object.defineProperty(t, e, {
            value: n,
            enumerable: !0,
            configurable: !0,
            writable: !0
        }) : t[e] = n, t
    }

    function r(t, e) {
        var n = Object.keys(t);
        if (Object.getOwnPropertySymbols) {
            var i = Object.getOwnPropertySymbols(t);
            e && (i = i.filter((function(e) {
                return Object.getOwnPropertyDescriptor(t, e).enumerable
            }))), n.push.apply(n, i)
        }
        return n
    }

    function s(t) {
        for (var e = 1; e < arguments.length; e++) {
            var n = null != arguments[e] ? arguments[e] : {};
            e % 2 ? r(Object(n), !0).forEach((function(e) {
                o(t, e, n[e])
            })) : Object.getOwnPropertyDescriptors ? Object.defineProperties(t, Object.getOwnPropertyDescriptors(n)) : r(Object(n)).forEach((function(e) {
                Object.defineProperty(t, e, Object.getOwnPropertyDescriptor(n, e))
            }))
        }
        return t
    }
    t = t && t.hasOwnProperty("default") ? t.default : t, e = e && e.hasOwnProperty("default") ? e.default : e;
    var a, l, c, u = function(t) {
            do {
                t += ~~(1e6 * Math.random())
            } while (document.getElementById(t));
            return t
        },
        f = function(t) {
            var e = t.getAttribute("data-target");
            if (!e || "#" === e) {
                var n = t.getAttribute("href");
                e = n && "#" !== n ? n.trim() : null
            }
            return e
        },
        h = function(t) {
            var e = f(t);
            return e && document.querySelector(e) ? e : null
        },
        d = function(t) {
            var e = f(t);
            return e ? document.querySelector(e) : null
        },
        g = function(t) {
            if (!t) return 0;
            var e = window.getComputedStyle(t),
                n = e.transitionDuration,
                i = e.transitionDelay,
                o = parseFloat(n),
                r = parseFloat(i);
            return o || r ? (n = n.split(",")[0], i = i.split(",")[0], 1e3 * (parseFloat(n) + parseFloat(i))) : 0
        },
        p = function(t) {
            var e = document.createEvent("HTMLEvents");
            e.initEvent("transitionend", !0, !0), t.dispatchEvent(e)
        },
        _ = function(t) {
            return (t[0] || t).nodeType
        },
        m = function(t, e) {
            var n = !1,
                i = e + 5;
            t.addEventListener("transitionend", (function e() {
                n = !0, t.removeEventListener("transitionend", e)
            })), setTimeout((function() {
                n || p(t)
            }), i)
        },
        v = function(t, e, n) {
            Object.keys(n).forEach((function(i) {
                var o, r = n[i],
                    s = e[i],
                    a = s && _(s) ? "element" : (o = s, {}.toString.call(o).match(/\s([a-z]+)/i)[1].toLowerCase());
                if (!new RegExp(r).test(a)) throw new Error(t.toUpperCase() + ': Option "' + i + '" provided type "' + a + '" but expected type "' + r + '".')
            }))
        },
        y = function(t) {
            return t ? [].slice.call(t) : []
        },
        E = function(t) {
            if (!t) return !1;
            if (t.style && t.parentNode && t.parentNode.style) {
                var e = getComputedStyle(t),
                    n = getComputedStyle(t.parentNode);
                return "none" !== e.display && "none" !== n.display && "hidden" !== e.visibility
            }
            return !1
        },
        b = function() {
            return function() {}
        },
        A = function(t) {
            return t.offsetHeight
        },
        w = function() {
            var t = window.jQuery;
            return t && !document.body.hasAttribute("data-no-jquery") ? t : null
        },
        S = (a = {}, l = 1, {
            set: function(t, e, n) {
                "undefined" == typeof t.key && (t.key = {
                    key: e,
                    id: l
                }, l++), a[t.key.id] = n
            },
            get: function(t, e) {
                if (!t || "undefined" == typeof t.key) return null;
                var n = t.key;
                return n.key === e ? a[n.id] : null
            },
            delete: function(t, e) {
                if ("undefined" != typeof t.key) {
                    var n = t.key;
                    n.key === e && (delete a[n.id], delete t.key)
                }
            }
        }),
        I = function(t, e, n) {
            S.set(t, e, n)
        },
        L = function(t, e) {
            return S.get(t, e)
        },
        T = function(t, e) {
            S.delete(t, e)
        },
        D = Element.prototype,
        C = D.matches,
        O = D.closest,
        k = Element.prototype.querySelectorAll,
        N = Element.prototype.querySelector,
        P = function(t, e) {
            return new CustomEvent(t, e)
        };
    if ("function" != typeof window.CustomEvent && (P = function(t, e) {
            e = e || {
                bubbles: !1,
                cancelable: !1,
                detail: null
            };
            var n = document.createEvent("CustomEvent");
            return n.initCustomEvent(t, e.bubbles, e.cancelable, e.detail), n
        }), !((c = document.createEvent("CustomEvent")).initEvent("Bootstrap", !0, !0), c.preventDefault(), c.defaultPrevented)) {
        var H = Event.prototype.preventDefault;
        Event.prototype.preventDefault = function() {
            this.cancelable && (H.call(this), Object.defineProperty(this, "defaultPrevented", {
                get: function() {
                    return !0
                },
                configurable: !0
            }))
        }
    }
    var j = function() {
        var t = P("Bootstrap", {
                cancelable: !0
            }),
            e = document.createElement("div");
        return e.addEventListener("Bootstrap", (function() {
            return null
        })), t.preventDefault(), e.dispatchEvent(t), t.defaultPrevented
    }();
    C || (C = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector), O || (O = function(t) {
        var e = this;
        do {
            if (C.call(e, t)) return e;
            e = e.parentElement || e.parentNode
        } while (null !== e && 1 === e.nodeType);
        return null
    });
    var R = /:scope\b/;
    (function() {
        var t = document.createElement("div");
        try {
            t.querySelectorAll(":scope *")
        } catch (t) {
            return !1
        }
        return !0
    })() || (k = function(t) {
        if (!R.test(t)) return this.querySelectorAll(t);
        var e = Boolean(this.id);
        e || (this.id = u("scope"));
        var n = null;
        try {
            t = t.replace(R, "#" + this.id), n = this.querySelectorAll(t)
        } finally {
            e || this.removeAttribute("id")
        }
        return n
    }, N = function(t) {
        if (!R.test(t)) return this.querySelector(t);
        var e = k.call(this, t);
        return "undefined" != typeof e[0] ? e[0] : null
    });
    var M = w(),
        W = /[^.]*(?=\..*)\.|.*/,
        x = /\..*/,
        U = /^key/,
        B = /::\d+$/,
        K = {},
        V = 1,
        Q = {
            mouseenter: "mouseover",
            mouseleave: "mouseout"
        },
        F = ["click", "dblclick", "mouseup", "mousedown", "contextmenu", "mousewheel", "DOMMouseScroll", "mouseover", "mouseout", "mousemove", "selectstart", "selectend", "keydown", "keypress", "keyup", "orientationchange", "touchstart", "touchmove", "touchend", "touchcancel", "pointerdown", "pointermove", "pointerup", "pointerleave", "pointercancel", "gesturestart", "gesturechange", "gestureend", "focus", "blur", "change", "reset", "select", "submit", "focusin", "focusout", "load", "unload", "beforeunload", "resize", "move", "DOMContentLoaded", "readystatechange", "error", "abort", "scroll"];

    function q(t, e) {
        return e && e + "::" + V++ || t.uidEvent || V++
    }

    function G(t) {
        var e = q(t);
        return t.uidEvent = e, K[e] = K[e] || {}, K[e]
    }

    function Y(t, e) {
        null === t.which && U.test(t.type) && (t.which = null === t.charCode ? t.keyCode : t.charCode), t.delegateTarget = e
    }

    function X(t, e, n) {
        void 0 === n && (n = null);
        for (var i = Object.keys(t), o = 0, r = i.length; o < r; o++) {
            var s = t[i[o]];
            if (s.originalHandler === e && s.delegationSelector === n) return s
        }
        return null
    }

    function z(t, e, n) {
        var i = "string" == typeof e,
            o = i ? n : e,
            r = t.replace(x, ""),
            s = Q[r];
        return s && (r = s), F.indexOf(r) > -1 || (r = t), [i, o, r]
    }

    function Z(t, e, n, i, o) {
        if ("string" == typeof e && t) {
            n || (n = i, i = null);
            var r = z(e, n, i),
                s = r[0],
                a = r[1],
                l = r[2],
                c = G(t),
                u = c[l] || (c[l] = {}),
                f = X(u, a, s ? n : null);
            if (f) f.oneOff = f.oneOff && o;
            else {
                var h = q(a, e.replace(W, "")),
                    d = s ? function(t, e, n) {
                        return function i(o) {
                            for (var r = t.querySelectorAll(e), s = o.target; s && s !== this; s = s.parentNode)
                                for (var a = r.length; a--;)
                                    if (r[a] === s) return Y(o, s), i.oneOff && J.off(t, o.type, n), n.apply(s, [o]);
                            return null
                        }
                    }(t, n, i) : function(t, e) {
                        return function n(i) {
                            return Y(i, t), n.oneOff && J.off(t, i.type, e), e.apply(t, [i])
                        }
                    }(t, n);
                d.delegationSelector = s ? n : null, d.originalHandler = a, d.oneOff = o, d.uidEvent = h, u[h] = d, t.addEventListener(l, d, s)
            }
        }
    }

    function $(t, e, n, i, o) {
        var r = X(e[n], i, o);
        r && (t.removeEventListener(n, r, Boolean(o)), delete e[n][r.uidEvent])
    }
    var J = {
            on: function(t, e, n, i) {
                Z(t, e, n, i, !1)
            },
            one: function(t, e, n, i) {
                Z(t, e, n, i, !0)
            },
            off: function(t, e, n, i) {
                if ("string" == typeof e && t) {
                    var o = z(e, n, i),
                        r = o[0],
                        s = o[1],
                        a = o[2],
                        l = a !== e,
                        c = G(t),
                        u = "." === e.charAt(0);
                    if ("undefined" == typeof s) {
                        u && Object.keys(c).forEach((function(n) {
                            ! function(t, e, n, i) {
                                var o = e[n] || {};
                                Object.keys(o).forEach((function(r) {
                                    if (r.indexOf(i) > -1) {
                                        var s = o[r];
                                        $(t, e, n, s.originalHandler, s.delegationSelector)
                                    }
                                }))
                            }(t, c, n, e.slice(1))
                        }));
                        var f = c[a] || {};
                        Object.keys(f).forEach((function(n) {
                            var i = n.replace(B, "");
                            if (!l || e.indexOf(i) > -1) {
                                var o = f[n];
                                $(t, c, a, o.originalHandler, o.delegationSelector)
                            }
                        }))
                    } else {
                        if (!c || !c[a]) return;
                        $(t, c, a, s, r ? n : null)
                    }
                }
            },
            trigger: function(t, e, n) {
                if ("string" != typeof e || !t) return null;
                var i, o = e.replace(x, ""),
                    r = e !== o,
                    s = F.indexOf(o) > -1,
                    a = !0,
                    l = !0,
                    c = !1,
                    u = null;
                return r && M && (i = M.Event(e, n), M(t).trigger(i), a = !i.isPropagationStopped(), l = !i.isImmediatePropagationStopped(), c = i.isDefaultPrevented()), s ? (u = document.createEvent("HTMLEvents")).initEvent(o, a, !0) : u = P(e, {
                    bubbles: a,
                    cancelable: !0
                }), "undefined" != typeof n && Object.keys(n).forEach((function(t) {
                    Object.defineProperty(u, t, {
                        get: function() {
                            return n[t]
                        }
                    })
                })), c && (u.preventDefault(), j || Object.defineProperty(u, "defaultPrevented", {
                    get: function() {
                        return !0
                    }
                })), l && t.dispatchEvent(u), u.defaultPrevented && "undefined" != typeof i && i.preventDefault(), u
            }
        },
        tt = "asyncLoad",
        et = {
            ACTIVE: "c-active",
            NAV_DROPDOWN_TOGGLE: "c-sidebar-nav-dropdown-toggle",
            SHOW: "c-show",
            VIEW_SCRIPT: "view-script"
        },
        nt = {
            CLICK_DATA_API: "click.coreui.asyncLoad.data-api",
            XHR_STATUS: "xhr"
        },
        it = ".c-sidebar-nav-dropdown",
        ot = ".c-xhr-link, .c-sidebar-nav-link",
        rt = ".c-sidebar-nav-item",
        st = ".view-script",
        at = {
            defaultPage: "main.html",
            errorPage: "404.html",
            subpagesDirectory: "views/"
        },
        lt = function() {
            function t(t, e) {
                this._config = this._getConfig(e), this._element = t;
                var n = location.hash.replace(/^#/, "");
                "" !== n ? this._setUpUrl(n) : this._setUpUrl(this._config.defaultPage), this._addEventListeners()
            }
            var e = t.prototype;
            return e._getConfig = function(t) {
                return t = s({}, at, {}, t)
            }, e._loadPage = function(t) {
                var e = this,
                    n = this._element,
                    i = this._config,
                    o = new XMLHttpRequest;
                o.open("GET", i.subpagesDirectory + t);
                var r = new CustomEvent(nt.XHR_STATUS, {
                    detail: {
                        url: t,
                        status: o.status
                    }
                });
                n.dispatchEvent(r), o.onload = function(s) {
                    if (200 === o.status) {
                        r = new CustomEvent(nt.XHR_STATUS, {
                            detail: {
                                url: t,
                                status: o.status
                            }
                        }), n.dispatchEvent(r);
                        var a = document.createElement("div");
                        a.innerHTML = s.target.response;
                        var l = Array.from(a.querySelectorAll("script")).map((function(t) {
                            return t.attributes.getNamedItem("src").nodeValue
                        }));
                        a.querySelectorAll("script").forEach((function(t) {
                            return t.remove(t)
                        })), window.scrollTo(0, 0), n.innerHTML = "", n.appendChild(a), (c = document.querySelectorAll(st)).length && c.forEach((function(t) {
                            t.remove()
                        })), l.length && function t(n, i) {
                            void 0 === i && (i = 0);
                            var o = document.createElement("script");
                            o.type = "text/javascript", o.src = n[i], o.className = et.VIEW_SCRIPT, o.onload = o.onreadystatechange = function() {
                                e.readyState && "complete" !== e.readyState || n.length > i + 1 && t(n, i + 1)
                            }, document.getElementsByTagName("body")[0].appendChild(o)
                        }(l), window.location.hash = t
                    } else window.location.href = i.errorPage;
                    var c
                }, o.send()
            }, e._setUpUrl = function(t) {
                t = t.replace(/^\//, "").split("?")[0], Array.from(document.querySelectorAll(ot)).forEach((function(t) {
                    t.classList.remove(et.ACTIVE)
                })), Array.from(document.querySelectorAll(ot)).forEach((function(t) {
                    t.classList.remove(et.ACTIVE)
                })), Array.from(document.querySelectorAll(it)).forEach((function(t) {
                    t.classList.remove(et.SHOW)
                })), Array.from(document.querySelectorAll(it)).forEach((function(e) {
                    Array.from(e.querySelectorAll('a[href*="' + t + '"]')).length > 0 && e.classList.add(et.SHOW)
                })), Array.from(document.querySelectorAll(rt + ' a[href*="' + t + '"]')).forEach((function(t) {
                    t.classList.add(et.ACTIVE)
                })), this._loadPage(t)
            }, e._loadBlank = function(t) {
                window.open(t)
            }, e._loadTop = function(t) {
                window.location = t
            }, e._update = function(t) {
                "#" !== t.href && ("undefined" != typeof t.dataset.toggle && "null" !== t.dataset.toggle || ("_top" === t.target ? this._loadTop(t.href) : "_blank" === t.target ? this._loadBlank(t.href) : this._setUpUrl(t.getAttribute("href"))))
            }, e._addEventListeners = function() {
                var t = this;
                J.on(document, nt.CLICK_DATA_API, ot, (function(e) {
                    e.preventDefault();
                    var n = e.target;
                    n.classList.contains(et.NAV_LINK) || (n = n.closest(ot)), n.classList.contains(et.NAV_DROPDOWN_TOGGLE) || "#" === n.getAttribute("href") || t._update(n)
                }))
            }, t._asyncLoadInterface = function(e, n) {
                var i = L(e, "coreui.asyncLoad");
                if (i || (i = new t(e, "object" == typeof n && n)), "string" == typeof n) {
                    if ("undefined" == typeof i[n]) throw new TypeError('No method named "' + n + '"');
                    i[n]()
                }
            }, t.jQueryInterface = function(e) {
                return this.each((function() {
                    t._asyncLoadInterface(this, e)
                }))
            }, i(t, null, [{
                key: "VERSION",
                get: function() {
                    return "3.0.0-rc.0"
                }
            }, {
                key: "Default",
                get: function() {
                    return at
                }
            }]), t
        }(),
        ct = w();
    if (ct) {
        var ut = ct.fn[tt];
        ct.fn[tt] = lt.jQueryInterface, ct.fn[tt].Constructor = lt, ct.fn[tt].noConflict = function() {
            return ct.fn[tt] = ut, lt.jQueryInterface
        }
    }
    var ft = {
            matches: function(t, e) {
                return C.call(t, e)
            },
            find: function(t, e) {
                return void 0 === e && (e = document.documentElement), k.call(e, t)
            },
            findOne: function(t, e) {
                return void 0 === e && (e = document.documentElement), N.call(e, t)
            },
            children: function(t, e) {
                var n = this,
                    i = y(t.children);
                return i.filter((function(t) {
                    return n.matches(t, e)
                }))
            },
            parents: function(t, e) {
                for (var n = [], i = t.parentNode; i && i.nodeType === Node.ELEMENT_NODE && 3 !== i.nodeType;) this.matches(i, e) && n.push(i), i = i.parentNode;
                return n
            },
            closest: function(t, e) {
                return O.call(t, e)
            },
            prev: function(t, e) {
                for (var n = [], i = t.previousSibling; i && i.nodeType === Node.ELEMENT_NODE && 3 !== i.nodeType;) this.matches(i, e) && n.push(i), i = i.previousSibling;
                return n
            }
        },
        ht = {
            CLOSE: "close.coreui.alert",
            CLOSED: "closed.coreui.alert",
            CLICK_DATA_API: "click.coreui.alert.data-api"
        },
        dt = "alert",
        gt = "fade",
        pt = "show",
        _t = function() {
            function t(t) {
                this._element = t, this._element && I(t, "coreui.alert", this)
            }
            var e = t.prototype;
            return e.close = function(t) {
                var e = this._element;
                t && (e = this._getRootElement(t));
                var n = this._triggerCloseEvent(e);
                null === n || n.defaultPrevented || this._removeElement(e)
            }, e.dispose = function() {
                T(this._element, "coreui.alert"), this._element = null
            }, e._getRootElement = function(t) {
                var e = d(t);
                return e || (e = ft.closest(t, "." + dt)), e
            }, e._triggerCloseEvent = function(t) {
                return J.trigger(t, ht.CLOSE)
            }, e._removeElement = function(t) {
                var e = this;
                if (t.classList.remove(pt), t.classList.contains(gt)) {
                    var n = g(t);
                    J.one(t, "transitionend", (function() {
                        return e._destroyElement(t)
                    })), m(t, n)
                } else this._destroyElement(t)
            }, e._destroyElement = function(t) {
                t.parentNode && t.parentNode.removeChild(t), J.trigger(t, ht.CLOSED)
            }, t.jQueryInterface = function(e) {
                return this.each((function() {
                    var n = L(this, "coreui.alert");
                    n || (n = new t(this)), "close" === e && n[e](this)
                }))
            }, t.handleDismiss = function(t) {
                return function(e) {
                    e && e.preventDefault(), t.close(this)
                }
            }, t.getInstance = function(t) {
                return L(t, "coreui.alert")
            }, i(t, null, [{
                key: "VERSION",
                get: function() {
                    return "3.0.0-rc.0"
                }
            }]), t
        }();
    J.on(document, ht.CLICK_DATA_API, '[data-dismiss="alert"]', _t.handleDismiss(new _t));
    var mt = w();
    if (mt) {
        var vt = mt.fn.alert;
        mt.fn.alert = _t.jQueryInterface, mt.fn.alert.Constructor = _t, mt.fn.alert.noConflict = function() {
            return mt.fn.alert = vt, _t.jQueryInterface
        }
    }
    var yt = "coreui.button",
        Et = "active",
        bt = "btn",
        At = "focus",
        wt = '[data-toggle^="button"]',
        St = '[data-toggle="buttons"]',
        It = 'input:not([type="hidden"])',
        Lt = ".active",
        Tt = ".btn",
        Dt = {
            CLICK_DATA_API: "click.coreui.button.data-api",
            FOCUS_DATA_API: "focus.coreui.button.data-api",
            BLUR_DATA_API: "blur.coreui.button.data-api"
        },
        Ct = function() {
            function t(t) {
                this._element = t, I(t, yt, this)
            }
            var e = t.prototype;
            return e.toggle = function() {
                var t = !0,
                    e = !0,
                    n = ft.closest(this._element, St);
                if (n) {
                    var i = ft.findOne(It, this._element);
                    if (i && "radio" === i.type) {
                        if (i.checked && this._element.classList.contains(Et)) t = !1;
                        else {
                            var o = ft.findOne(Lt, n);
                            o && o.classList.remove(Et)
                        }
                        if (t) {
                            if (i.hasAttribute("disabled") || n.hasAttribute("disabled") || i.classList.contains("disabled") || n.classList.contains("disabled")) return;
                            i.checked = !this._element.classList.contains(Et), J.trigger(i, "change")
                        }
                        i.focus(), e = !1
                    }
                }
                e && this._element.setAttribute("aria-pressed", !this._element.classList.contains(Et)), t && this._element.classList.toggle(Et)
            }, e.dispose = function() {
                T(this._element, yt), this._element = null
            }, t.jQueryInterface = function(e) {
                return this.each((function() {
                    var n = L(this, yt);
                    n || (n = new t(this)), "toggle" === e && n[e]()
                }))
            }, t.getInstance = function(t) {
                return L(t, yt)
            }, i(t, null, [{
                key: "VERSION",
                get: function() {
                    return "3.0.0-rc.0"
                }
            }]), t
        }();
    J.on(document, Dt.CLICK_DATA_API, wt, (function(t) {
        t.preventDefault();
        var e = t.target;
        e.classList.contains(bt) || (e = ft.closest(e, Tt));
        var n = L(e, yt);
        n || (n = new Ct(e)), n.toggle()
    })), J.on(document, Dt.FOCUS_DATA_API, wt, (function(t) {
        var e = ft.closest(t.target, Tt);
        e && e.classList.add(At)
    })), J.on(document, Dt.BLUR_DATA_API, wt, (function(t) {
        var e = ft.closest(t.target, Tt);
        e && e.classList.remove(At)
    }));
    var Ot = w();
    if (Ot) {
        var kt = Ot.fn.button;
        Ot.fn.button = Ct.jQueryInterface, Ot.fn.button.Constructor = Ct, Ot.fn.button.noConflict = function() {
            return Ot.fn.button = kt, Ct.jQueryInterface
        }
    }

    function Nt(t) {
        return "true" === t || "false" !== t && (t === Number(t).toString() ? Number(t) : "" === t || "null" === t ? null : t)
    }

    function Pt(t) {
        return t.replace(/[A-Z]/g, (function(t) {
            return "-" + t.toLowerCase()
        }))
    }
    var Ht = {
            setDataAttribute: function(t, e, n) {
                t.setAttribute("data-" + Pt(e), n)
            },
            removeDataAttribute: function(t, e) {
                t.removeAttribute("data-" + Pt(e))
            },
            getDataAttributes: function(t) {
                if (!t) return {};
                var e = s({}, t.dataset);
                return Object.keys(e).forEach((function(t) {
                    e[t] = Nt(e[t])
                })), e
            },
            getDataAttribute: function(t, e) {
                return Nt(t.getAttribute("data-" + Pt(e)))
            },
            offset: function(t) {
                var e = t.getBoundingClientRect();
                return {
                    top: e.top + document.body.scrollTop,
                    left: e.left + document.body.scrollLeft
                }
            },
            position: function(t) {
                return {
                    top: t.offsetTop,
                    left: t.offsetLeft
                }
            },
            toggleClass: function(t, e) {
                t && (t.classList.contains(e) ? t.classList.remove(e) : t.classList.add(e))
            }
        },
        jt = "carousel",
        Rt = "coreui.carousel",
        Mt = "." + Rt,
        Wt = {
            interval: 5e3,
            keyboard: !0,
            slide: !1,
            pause: "hover",
            wrap: !0,
            touch: !0
        },
        xt = {
            interval: "(number|boolean)",
            keyboard: "boolean",
            slide: "(boolean|string)",
            pause: "(string|boolean)",
            wrap: "boolean",
            touch: "boolean"
        },
        Ut = "next",
        Bt = "prev",
        Kt = "left",
        Vt = "right",
        Qt = {
            SLIDE: "slide" + Mt,
            SLID: "slid" + Mt,
            KEYDOWN: "keydown" + Mt,
            MOUSEENTER: "mouseenter" + Mt,
            MOUSELEAVE: "mouseleave" + Mt,
            TOUCHSTART: "touchstart" + Mt,
            TOUCHMOVE: "touchmove" + Mt,
            TOUCHEND: "touchend" + Mt,
            POINTERDOWN: "pointerdown" + Mt,
            POINTERUP: "pointerup" + Mt,
            DRAG_START: "dragstart" + Mt,
            LOAD_DATA_API: "load" + Mt + ".data-api",
            CLICK_DATA_API: "click" + Mt + ".data-api"
        },
        Ft = "carousel",
        qt = "active",
        Gt = "slide",
        Yt = "carousel-item-right",
        Xt = "carousel-item-left",
        zt = "carousel-item-next",
        Zt = "carousel-item-prev",
        $t = "pointer-event",
        Jt = ".active",
        te = ".active.carousel-item",
        ee = ".carousel-item",
        ne = ".carousel-item img",
        ie = ".carousel-item-next, .carousel-item-prev",
        oe = ".carousel-indicators",
        re = "[data-slide], [data-slide-to]",
        se = '[data-ride="carousel"]',
        ae = {
            TOUCH: "touch",
            PEN: "pen"
        },
        le = function() {
            function t(t, e) {
                this._items = null, this._interval = null, this._activeElement = null, this._isPaused = !1, this._isSliding = !1, this.touchTimeout = null, this.touchStartX = 0, this.touchDeltaX = 0, this._config = this._getConfig(e), this._element = t, this._indicatorsElement = ft.findOne(oe, this._element), this._touchSupported = "ontouchstart" in document.documentElement || navigator.maxTouchPoints > 0, this._pointerEvent = Boolean(window.PointerEvent || window.MSPointerEvent), this._addEventListeners(), I(t, Rt, this)
            }
            var e = t.prototype;
            return e.next = function() {
                this._isSliding || this._slide(Ut)
            }, e.nextWhenVisible = function() {
                !document.hidden && E(this._element) && this.next()
            }, e.prev = function() {
                this._isSliding || this._slide(Bt)
            }, e.pause = function(t) {
                t || (this._isPaused = !0), ft.findOne(ie, this._element) && (p(this._element), this.cycle(!0)), clearInterval(this._interval), this._interval = null
            }, e.cycle = function(t) {
                t || (this._isPaused = !1), this._interval && (clearInterval(this._interval), this._interval = null), this._config && this._config.interval && !this._isPaused && (this._interval = setInterval((document.visibilityState ? this.nextWhenVisible : this.next).bind(this), this._config.interval))
            }, e.to = function(t) {
                var e = this;
                this._activeElement = ft.findOne(te, this._element);
                var n = this._getItemIndex(this._activeElement);
                if (!(t > this._items.length - 1 || t < 0))
                    if (this._isSliding) J.one(this._element, Qt.SLID, (function() {
                        return e.to(t)
                    }));
                    else {
                        if (n === t) return this.pause(), void this.cycle();
                        var i = t > n ? Ut : Bt;
                        this._slide(i, this._items[t])
                    }
            }, e.dispose = function() {
                J.off(this._element, Mt), T(this._element, Rt), this._items = null, this._config = null, this._element = null, this._interval = null, this._isPaused = null, this._isSliding = null, this._activeElement = null, this._indicatorsElement = null
            }, e._getConfig = function(t) {
                return t = s({}, Wt, {}, t), v(jt, t, xt), t
            }, e._handleSwipe = function() {
                var t = Math.abs(this.touchDeltaX);
                if (!(t <= 40)) {
                    var e = t / this.touchDeltaX;
                    this.touchDeltaX = 0, e > 0 && this.prev(), e < 0 && this.next()
                }
            }, e._addEventListeners = function() {
                var t = this;
                this._config.keyboard && J.on(this._element, Qt.KEYDOWN, (function(e) {
                    return t._keydown(e)
                })), "hover" === this._config.pause && (J.on(this._element, Qt.MOUSEENTER, (function(e) {
                    return t.pause(e)
                })), J.on(this._element, Qt.MOUSELEAVE, (function(e) {
                    return t.cycle(e)
                }))), this._config.touch && this._touchSupported && this._addTouchEventListeners()
            }, e._addTouchEventListeners = function() {
                var t = this,
                    e = function(e) {
                        t._pointerEvent && ae[e.pointerType.toUpperCase()] ? t.touchStartX = e.clientX : t._pointerEvent || (t.touchStartX = e.touches[0].clientX)
                    },
                    n = function(e) {
                        t._pointerEvent && ae[e.pointerType.toUpperCase()] && (t.touchDeltaX = e.clientX - t.touchStartX), t._handleSwipe(), "hover" === t._config.pause && (t.pause(), t.touchTimeout && clearTimeout(t.touchTimeout), t.touchTimeout = setTimeout((function(e) {
                            return t.cycle(e)
                        }), 500 + t._config.interval))
                    };
                y(ft.find(ne, this._element)).forEach((function(t) {
                    J.on(t, Qt.DRAG_START, (function(t) {
                        return t.preventDefault()
                    }))
                })), this._pointerEvent ? (J.on(this._element, Qt.POINTERDOWN, (function(t) {
                    return e(t)
                })), J.on(this._element, Qt.POINTERUP, (function(t) {
                    return n(t)
                })), this._element.classList.add($t)) : (J.on(this._element, Qt.TOUCHSTART, (function(t) {
                    return e(t)
                })), J.on(this._element, Qt.TOUCHMOVE, (function(e) {
                    return function(e) {
                        e.touches && e.touches.length > 1 ? t.touchDeltaX = 0 : t.touchDeltaX = e.touches[0].clientX - t.touchStartX
                    }(e)
                })), J.on(this._element, Qt.TOUCHEND, (function(t) {
                    return n(t)
                })))
            }, e._keydown = function(t) {
                if (!/input|textarea/i.test(t.target.tagName)) switch (t.which) {
                    case 37:
                        t.preventDefault(), this.prev();
                        break;
                    case 39:
                        t.preventDefault(), this.next()
                }
            }, e._getItemIndex = function(t) {
                return this._items = t && t.parentNode ? y(ft.find(ee, t.parentNode)) : [], this._items.indexOf(t)
            }, e._getItemByDirection = function(t, e) {
                var n = t === Ut,
                    i = t === Bt,
                    o = this._getItemIndex(e),
                    r = this._items.length - 1;
                if ((i && 0 === o || n && o === r) && !this._config.wrap) return e;
                var s = (o + (t === Bt ? -1 : 1)) % this._items.length;
                return -1 === s ? this._items[this._items.length - 1] : this._items[s]
            }, e._triggerSlideEvent = function(t, e) {
                var n = this._getItemIndex(t),
                    i = this._getItemIndex(ft.findOne(te, this._element));
                return J.trigger(this._element, Qt.SLIDE, {
                    relatedTarget: t,
                    direction: e,
                    from: i,
                    to: n
                })
            }, e._setActiveIndicatorElement = function(t) {
                if (this._indicatorsElement) {
                    for (var e = ft.find(Jt, this._indicatorsElement), n = 0; n < e.length; n++) e[n].classList.remove(qt);
                    var i = this._indicatorsElement.children[this._getItemIndex(t)];
                    i && i.classList.add(qt)
                }
            }, e._slide = function(t, e) {
                var n, i, o, r = this,
                    s = ft.findOne(te, this._element),
                    a = this._getItemIndex(s),
                    l = e || s && this._getItemByDirection(t, s),
                    c = this._getItemIndex(l),
                    u = Boolean(this._interval);
                if (t === Ut ? (n = Xt, i = zt, o = Kt) : (n = Yt, i = Zt, o = Vt), l && l.classList.contains(qt)) this._isSliding = !1;
                else if (!this._triggerSlideEvent(l, o).defaultPrevented && s && l) {
                    if (this._isSliding = !0, u && this.pause(), this._setActiveIndicatorElement(l), this._element.classList.contains(Gt)) {
                        l.classList.add(i), A(l), s.classList.add(n), l.classList.add(n);
                        var f = parseInt(l.getAttribute("data-interval"), 10);
                        f ? (this._config.defaultInterval = this._config.defaultInterval || this._config.interval, this._config.interval = f) : this._config.interval = this._config.defaultInterval || this._config.interval;
                        var h = g(s);
                        J.one(s, "transitionend", (function() {
                            l.classList.remove(n), l.classList.remove(i), l.classList.add(qt), s.classList.remove(qt), s.classList.remove(i), s.classList.remove(n), r._isSliding = !1, setTimeout((function() {
                                J.trigger(r._element, Qt.SLID, {
                                    relatedTarget: l,
                                    direction: o,
                                    from: a,
                                    to: c
                                })
                            }), 0)
                        })), m(s, h)
                    } else s.classList.remove(qt), l.classList.add(qt), this._isSliding = !1, J.trigger(this._element, Qt.SLID, {
                        relatedTarget: l,
                        direction: o,
                        from: a,
                        to: c
                    });
                    u && this.cycle()
                }
            }, t.carouselInterface = function(e, n) {
                var i = L(e, Rt),
                    o = s({}, Wt, {}, Ht.getDataAttributes(e));
                "object" == typeof n && (o = s({}, o, {}, n));
                var r = "string" == typeof n ? n : o.slide;
                if (i || (i = new t(e, o)), "number" == typeof n) i.to(n);
                else if ("string" == typeof r) {
                    if ("undefined" == typeof i[r]) throw new TypeError('No method named "' + r + '"');
                    i[r]()
                } else o.interval && o.ride && (i.pause(), i.cycle())
            }, t.jQueryInterface = function(e) {
                return this.each((function() {
                    t.carouselInterface(this, e)
                }))
            }, t.dataApiClickHandler = function(e) {
                var n = d(this);
                if (n && n.classList.contains(Ft)) {
                    var i = s({}, Ht.getDataAttributes(n), {}, Ht.getDataAttributes(this)),
                        o = this.getAttribute("data-slide-to");
                    o && (i.interval = !1), t.carouselInterface(n, i), o && L(n, Rt).to(o), e.preventDefault()
                }
            }, t.getInstance = function(t) {
                return L(t, Rt)
            }, i(t, null, [{
                key: "VERSION",
                get: function() {
                    return "3.0.0-rc.0"
                }
            }, {
                key: "Default",
                get: function() {
                    return Wt
                }
            }]), t
        }();
    J.on(document, Qt.CLICK_DATA_API, re, le.dataApiClickHandler), J.on(window, Qt.LOAD_DATA_API, (function() {
        for (var t = y(ft.find(se)), e = 0, n = t.length; e < n; e++) le.carouselInterface(t[e], L(t[e], Rt))
    }));
    var ce = w();
    if (ce) {
        var ue = ce.fn[jt];
        ce.fn[jt] = le.jQueryInterface, ce.fn[jt].Constructor = le, ce.fn[jt].noConflict = function() {
            return ce.fn[jt] = ue, le.jQueryInterface
        }
    }
    var fe = "class-toggler",
        he = "-sm,-md,-lg,-xl",
        de = "-show",
        ge = !1,
        pe = "body",
        _e = "c-class-toggler",
        me = {
            CLASS_TOGGLE: "classtoggle",
            CLICK_DATA_API: "click.coreui.class-toggler.data-api"
        },
        ve = ".c-class-toggler",
        ye = function() {
            function t(t) {
                this._element = t
            }
            var e = t.prototype;
            return e.toggle = function() {
                var t = this;
                this._getElementDataAttributes(this._element).forEach((function(e) {
                    var n, i = e.target,
                        o = e.toggle;
                    n = "_parent" === i || "parent" === i ? t._element.parentNode : document.querySelector(i), o.forEach((function(e) {
                        var o = e.className,
                            r = e.responsive,
                            s = e.postfix,
                            a = "undefined" == typeof e.breakpoints || null === e.breakpoints ? null : t._arrayFromString(e.breakpoints);
                        if (r) {
                            var l;
                            a.forEach((function(t) {
                                o.includes(t) && (l = t)
                            }));
                            var c = [];
                            "undefined" == typeof l ? c.push(o) : (c.push(o.replace("" + l + s, s)), a.splice(0, a.indexOf(l) + 1).forEach((function(t) {
                                c.push(o.replace("" + l + s, "" + t + s))
                            })));
                            var u = !1;
                            if (c.forEach((function(t) {
                                    n.classList.contains(t) && (u = !0)
                                })), u) c.forEach((function(t) {
                                n.classList.remove(t);
                                var e = new CustomEvent(me.CLASS_TOGGLE, {
                                    detail: {
                                        target: i,
                                        className: t
                                    }
                                });
                                n.dispatchEvent(e)
                            }));
                            else {
                                n.classList.add(o);
                                var f = new CustomEvent(me.CLASS_TOGGLE, {
                                    detail: {
                                        target: i,
                                        className: o
                                    }
                                });
                                n.dispatchEvent(f)
                            }
                        } else {
                            n.classList.toggle(o);
                            var h = new CustomEvent(me.CLASS_TOGGLE, {
                                detail: {
                                    target: i,
                                    className: o
                                }
                            });
                            n.dispatchEvent(h)
                        }
                    }))
                }))
            }, e._arrayFromString = function(t) {
                return t.replace(/ /g, "").split(",")
            }, e._isArray = function(t) {
                try {
                    return JSON.parse(t.replace(/'/g, '"')), !0
                } catch (t) {
                    return !1
                }
            }, e._convertToArray = function(t) {
                return JSON.parse(t.replace(/'/g, '"'))
            }, e._getDataAttributes = function(t, e) {
                var n = t[e];
                return this._isArray(n) ? this._convertToArray(n) : n
            }, e._getToggleDetails = function(t, e, n, i) {
                var o = function(t, e, n, i) {
                        void 0 === e && (e = ge), this.className = t, this.responsive = e, this.breakpoints = n, this.postfix = i
                    },
                    r = [];
                return Array.isArray(t) ? t.forEach((function(t, s) {
                    e = Array.isArray(e) ? e[s] : e, n = e ? Array.isArray(n) ? n[s] : n : null, i = e ? Array.isArray(i) ? i[s] : i : null, r.push(new o(t, e, n, i))
                })) : (n = e ? n : null, i = e ? i : null, r.push(new o(t, e, n, i))), r
            }, e._ifArray = function(t, e) {
                return Array.isArray(t) ? t[e] : t
            }, e._getElementDataAttributes = function(t) {
                var e = this,
                    n = t.dataset,
                    i = "undefined" == typeof n.target ? pe : this._getDataAttributes(n, "target"),
                    o = "undefined" == typeof n.class ? "undefined" : this._getDataAttributes(n, "class"),
                    r = "undefined" == typeof n.responsive ? ge : this._getDataAttributes(n, "responsive"),
                    s = "undefined" == typeof n.breakpoints ? he : this._getDataAttributes(n, "breakpoints"),
                    a = "undefined" == typeof n.postfix ? de : this._getDataAttributes(n, "postfix"),
                    l = [],
                    c = function(t, e) {
                        this.target = t, this.toggle = e
                    };
                return Array.isArray(i) ? i.forEach((function(t, n) {
                    l.push(new c(t, e._getToggleDetails(e._ifArray(o, n), e._ifArray(r, n), e._ifArray(s, n), e._ifArray(a, n))))
                })) : l.push(new c(i, this._getToggleDetails(o, r, s, a))), l
            }, t._classTogglerInterface = function(e, n) {
                var i = L(e, "coreui.class-toggler");
                if (i || (i = new t(e, "object" == typeof n && n)), "string" == typeof n) {
                    if ("undefined" == typeof i[n]) throw new TypeError('No method named "' + n + '"');
                    i[n]()
                }
            }, t.jQueryInterface = function(e) {
                return this.each((function() {
                    t._classTogglerInterface(this, e)
                }))
            }, i(t, null, [{
                key: "VERSION",
                get: function() {
                    return "3.0.0-rc.0"
                }
            }]), t
        }();
    J.on(document, me.CLICK_DATA_API, ve, (function(t) {
        t.preventDefault();
        var e = t.target;
        e.classList.contains(_e) || (e = e.closest(ve)), ye._classTogglerInterface(e, "toggle")
    }));
    var Ee = w();
    if (Ee) {
        var be = Ee.fn[fe];
        Ee.fn[fe] = ye.jQueryInterface, Ee.fn[fe].Constructor = ye, Ee.fn[fe].noConflict = function() {
            return Ee.fn[fe] = be, ye.jQueryInterface
        }
    }
    var Ae = "collapse",
        we = "coreui.collapse",
        Se = "." + we,
        Ie = {
            toggle: !0,
            parent: ""
        },
        Le = {
            toggle: "boolean",
            parent: "(string|element)"
        },
        Te = {
            SHOW: "show" + Se,
            SHOWN: "shown" + Se,
            HIDE: "hide" + Se,
            HIDDEN: "hidden" + Se,
            CLICK_DATA_API: "click" + Se + ".data-api"
        },
        De = "show",
        Ce = "collapse",
        Oe = "collapsing",
        ke = "collapsed",
        Ne = "width",
        Pe = "height",
        He = ".show, .collapsing",
        je = '[data-toggle="collapse"]',
        Re = function() {
            function t(t, e) {
                this._isTransitioning = !1, this._element = t, this._config = this._getConfig(e), this._triggerArray = y(ft.find('[data-toggle="collapse"][href="#' + t.id + '"],[data-toggle="collapse"][data-target="#' + t.id + '"]'));
                for (var n = y(ft.find(je)), i = 0, o = n.length; i < o; i++) {
                    var r = n[i],
                        s = h(r),
                        a = y(ft.find(s)).filter((function(e) {
                            return e === t
                        }));
                    null !== s && a.length && (this._selector = s, this._triggerArray.push(r))
                }
                this._parent = this._config.parent ? this._getParent() : null, this._config.parent || this._addAriaAndCollapsedClass(this._element, this._triggerArray), this._config.toggle && this.toggle(), I(t, we, this)
            }
            var e = t.prototype;
            return e.toggle = function() {
                this._element.classList.contains(De) ? this.hide() : this.show()
            }, e.show = function() {
                var e = this;
                if (!this._isTransitioning && !this._element.classList.contains(De)) {
                    var n, i;
                    this._parent && 0 === (n = y(ft.find(He, this._parent)).filter((function(t) {
                        return "string" == typeof e._config.parent ? t.getAttribute("data-parent") === e._config.parent : t.classList.contains(Ce)
                    }))).length && (n = null);
                    var o = ft.findOne(this._selector);
                    if (n) {
                        var r = n.filter((function(t) {
                            return o !== t
                        }));
                        if ((i = r[0] ? L(r[0], we) : null) && i._isTransitioning) return
                    }
                    if (!J.trigger(this._element, Te.SHOW).defaultPrevented) {
                        n && n.forEach((function(e) {
                            o !== e && t.collapseInterface(e, "hide"), i || I(e, we, null)
                        }));
                        var s = this._getDimension();
                        this._element.classList.remove(Ce), this._element.classList.add(Oe), this._element.style[s] = 0, this._triggerArray.length && this._triggerArray.forEach((function(t) {
                            t.classList.remove(ke), t.setAttribute("aria-expanded", !0)
                        })), this.setTransitioning(!0);
                        var a = "scroll" + (s[0].toUpperCase() + s.slice(1)),
                            l = g(this._element);
                        J.one(this._element, "transitionend", (function() {
                            e._element.classList.remove(Oe), e._element.classList.add(Ce), e._element.classList.add(De), e._element.style[s] = "", e.setTransitioning(!1), J.trigger(e._element, Te.SHOWN)
                        })), m(this._element, l), this._element.style[s] = this._element[a] + "px"
                    }
                }
            }, e.hide = function() {
                var t = this;
                if (!this._isTransitioning && this._element.classList.contains(De) && !J.trigger(this._element, Te.HIDE).defaultPrevented) {
                    var e = this._getDimension();
                    this._element.style[e] = this._element.getBoundingClientRect()[e] + "px", A(this._element), this._element.classList.add(Oe), this._element.classList.remove(Ce), this._element.classList.remove(De);
                    var n = this._triggerArray.length;
                    if (n > 0)
                        for (var i = 0; i < n; i++) {
                            var o = this._triggerArray[i],
                                r = d(o);
                            r && !r.classList.contains(De) && (o.classList.add(ke), o.setAttribute("aria-expanded", !1))
                        }
                    this.setTransitioning(!0);
                    this._element.style[e] = "";
                    var s = g(this._element);
                    J.one(this._element, "transitionend", (function() {
                        t.setTransitioning(!1), t._element.classList.remove(Oe), t._element.classList.add(Ce), J.trigger(t._element, Te.HIDDEN)
                    })), m(this._element, s)
                }
            }, e.setTransitioning = function(t) {
                this._isTransitioning = t
            }, e.dispose = function() {
                T(this._element, we), this._config = null, this._parent = null, this._element = null, this._triggerArray = null, this._isTransitioning = null
            }, e._getConfig = function(t) {
                return (t = s({}, Ie, {}, t)).toggle = Boolean(t.toggle), v(Ae, t, Le), t
            }, e._getDimension = function() {
                return this._element.classList.contains(Ne) ? Ne : Pe
            }, e._getParent = function() {
                var t = this,
                    e = this._config.parent;
                _(e) ? "undefined" == typeof e.jquery && "undefined" == typeof e[0] || (e = e[0]) : e = ft.findOne(e);
                var n = '[data-toggle="collapse"][data-parent="' + e + '"]';
                return y(ft.find(n, e)).forEach((function(e) {
                    var n = d(e);
                    t._addAriaAndCollapsedClass(n, [e])
                })), e
            }, e._addAriaAndCollapsedClass = function(t, e) {
                if (t) {
                    var n = t.classList.contains(De);
                    e.length && e.forEach((function(t) {
                        n ? t.classList.remove(ke) : t.classList.add(ke), t.setAttribute("aria-expanded", n)
                    }))
                }
            }, t.collapseInterface = function(e, n) {
                var i = L(e, we),
                    o = s({}, Ie, {}, Ht.getDataAttributes(e), {}, "object" == typeof n && n ? n : {});
                if (!i && o.toggle && /show|hide/.test(n) && (o.toggle = !1), i || (i = new t(e, o)), "string" == typeof n) {
                    if ("undefined" == typeof i[n]) throw new TypeError('No method named "' + n + '"');
                    i[n]()
                }
            }, t.jQueryInterface = function(e) {
                return this.each((function() {
                    t.collapseInterface(this, e)
                }))
            }, t.getInstance = function(t) {
                return L(t, we)
            }, i(t, null, [{
                key: "VERSION",
                get: function() {
                    return "3.0.0-rc.0"
                }
            }, {
                key: "Default",
                get: function() {
                    return Ie
                }
            }]), t
        }();
    J.on(document, Te.CLICK_DATA_API, je, (function(t) {
        "A" === t.target.tagName && t.preventDefault();
        var e = Ht.getDataAttributes(this),
            n = h(this);
        y(ft.find(n)).forEach((function(t) {
            var n, i = L(t, we);
            i ? (null === i._parent && "string" == typeof e.parent && (i._config.parent = e.parent, i._parent = i._getParent()), n = "toggle") : n = e, Re.collapseInterface(t, n)
        }))
    }));
    var Me = w();
    if (Me) {
        var We = Me.fn[Ae];
        Me.fn[Ae] = Re.jQueryInterface, Me.fn[Ae].Constructor = Re, Me.fn[Ae].noConflict = function() {
            return Me.fn[Ae] = We, Re.jQueryInterface
        }
    }
    var xe = "dropdown",
        Ue = "coreui.dropdown",
        Be = "." + Ue,
        Ke = new RegExp("38|40|27"),
        Ve = {
            HIDE: "hide" + Be,
            HIDDEN: "hidden" + Be,
            SHOW: "show" + Be,
            SHOWN: "shown" + Be,
            CLICK: "click" + Be,
            CLICK_DATA_API: "click" + Be + ".data-api",
            KEYDOWN_DATA_API: "keydown" + Be + ".data-api",
            KEYUP_DATA_API: "keyup" + Be + ".data-api"
        },
        Qe = "disabled",
        Fe = "show",
        qe = "dropup",
        Ge = "dropright",
        Ye = "dropleft",
        Xe = "dropdown-menu-right",
        ze = "position-static",
        Ze = '[data-toggle="dropdown"]',
        $e = ".dropdown form",
        Je = ".dropdown-menu",
        tn = ".navbar-nav",
        en = ".c-header-nav",
        nn = ".dropdown-menu .dropdown-item:not(.disabled):not(:disabled)",
        on = "top-start",
        rn = "top-end",
        sn = "bottom-start",
        an = "bottom-end",
        ln = "right-start",
        cn = "left-start",
        un = {
            offset: 0,
            flip: !0,
            boundary: "scrollParent",
            reference: "toggle",
            display: "dynamic",
            popperConfig: null
        },
        fn = {
            offset: "(number|string|function)",
            flip: "boolean",
            boundary: "(string|element)",
            reference: "(string|element)",
            display: "string",
            popperConfig: "(null|object)"
        },
        hn = function() {
            function e(t, e) {
                this._element = t, this._popper = null, this._config = this._getConfig(e), this._menu = this._getMenuElement(), this._inNavbar = this._detectNavbar(), this._inHeader = this._detectHeader(), this._addEventListeners(), I(t, Ue, this)
            }
            var n = e.prototype;
            return n.toggle = function() {
                if (!this._element.disabled && !this._element.classList.contains(Qe)) {
                    var t = this._menu.classList.contains(Fe);
                    e.clearMenus(), t || this.show()
                }
            }, n.show = function() {
                if (!(this._element.disabled || this._element.classList.contains(Qe) || this._menu.classList.contains(Fe))) {
                    var n = e.getParentFromElement(this._element),
                        i = {
                            relatedTarget: this._element
                        };
                    if (!J.trigger(n, Ve.SHOW, i).defaultPrevented) {
                        if (!this._inNavbar && !this._inHeader) {
                            if ("undefined" == typeof t) throw new TypeError("Bootstrap's dropdowns require Popper.js (https://popper.js.org)");
                            var o = this._element;
                            "parent" === this._config.reference ? o = n : _(this._config.reference) && (o = this._config.reference, "undefined" != typeof this._config.reference.jquery && (o = this._config.reference[0])), "scrollParent" !== this._config.boundary && n.classList.add(ze), this._popper = new t(o, this._menu, this._getPopperConfig())
                        }
                        "ontouchstart" in document.documentElement && !y(ft.closest(n, tn)).length && y(document.body.children).forEach((function(t) {
                            return J.on(t, "mouseover", null, (function() {}))
                        })), "ontouchstart" in document.documentElement && !y(ft.closest(n, en)).length && y(document.body.children).forEach((function(t) {
                            return J.on(t, "mouseover", null, (function() {}))
                        })), this._element.focus(), this._element.setAttribute("aria-expanded", !0), Ht.toggleClass(this._menu, Fe), Ht.toggleClass(n, Fe), J.trigger(n, Ve.SHOWN, i)
                    }
                }
            }, n.hide = function() {
                if (!this._element.disabled && !this._element.classList.contains(Qe) && this._menu.classList.contains(Fe)) {
                    var t = e.getParentFromElement(this._element),
                        n = {
                            relatedTarget: this._element
                        };
                    J.trigger(t, Ve.HIDE, n).defaultPrevented || (this._popper && this._popper.destroy(), Ht.toggleClass(this._menu, Fe), Ht.toggleClass(t, Fe), J.trigger(t, Ve.HIDDEN, n))
                }
            }, n.dispose = function() {
                T(this._element, Ue), J.off(this._element, Be), this._element = null, this._menu = null, this._popper && (this._popper.destroy(), this._popper = null)
            }, n.update = function() {
                this._inNavbar = this._detectNavbar(), this._inHeader = this._detectHeader(), this._popper && this._popper.scheduleUpdate()
            }, n._addEventListeners = function() {
                var t = this;
                J.on(this._element, Ve.CLICK, (function(e) {
                    e.preventDefault(), e.stopPropagation(), t.toggle()
                }))
            }, n._getConfig = function(t) {
                return t = s({}, this.constructor.Default, {}, Ht.getDataAttributes(this._element), {}, t), v(xe, t, this.constructor.DefaultType), t
            }, n._getMenuElement = function() {
                var t = e.getParentFromElement(this._element);
                return ft.findOne(Je, t)
            }, n._getPlacement = function() {
                var t = this._element.parentNode,
                    e = sn;
                return t.classList.contains(qe) ? (e = on, this._menu.classList.contains(Xe) && (e = rn)) : t.classList.contains(Ge) ? e = ln : t.classList.contains(Ye) ? e = cn : this._menu.classList.contains(Xe) && (e = an), e
            }, n._detectNavbar = function() {
                return Boolean(ft.closest(this._element, ".navbar"))
            }, n._detectHeader = function() {
                return Boolean(ft.closest(this._element, ".c-header"))
            }, n._getOffset = function() {
                var t = this,
                    e = {};
                return "function" == typeof this._config.offset ? e.fn = function(e) {
                    return e.offsets = s({}, e.offsets, {}, t._config.offset(e.offsets, t._element) || {}), e
                } : e.offset = this._config.offset, e
            }, n._getPopperConfig = function() {
                var t = {
                    placement: this._getPlacement(),
                    modifiers: {
                        offset: this._getOffset(),
                        flip: {
                            enabled: this._config.flip
                        },
                        preventOverflow: {
                            boundariesElement: this._config.boundary
                        }
                    }
                };
                return "static" === this._config.display && (t.modifiers.applyStyle = {
                    enabled: !1
                }), s({}, t, {}, this._config.popperConfig)
            }, e.dropdownInterface = function(t, n) {
                var i = L(t, Ue);
                if (i || (i = new e(t, "object" == typeof n ? n : null)), "string" == typeof n) {
                    if ("undefined" == typeof i[n]) throw new TypeError('No method named "' + n + '"');
                    i[n]()
                }
            }, e.jQueryInterface = function(t) {
                return this.each((function() {
                    e.dropdownInterface(this, t)
                }))
            }, e.clearMenus = function(t) {
                if (!t || 3 !== t.which && ("keyup" !== t.type || 9 === t.which))
                    for (var n = y(ft.find(Ze)), i = 0, o = n.length; i < o; i++) {
                        var r = e.getParentFromElement(n[i]),
                            s = L(n[i], Ue),
                            a = {
                                relatedTarget: n[i]
                            };
                        if (t && "click" === t.type && (a.clickEvent = t), s) {
                            var l = s._menu;
                            if (r.classList.contains(Fe))
                                if (!(t && ("click" === t.type && /input|textarea/i.test(t.target.tagName) || "keyup" === t.type && 9 === t.which) && r.contains(t.target))) J.trigger(r, Ve.HIDE, a).defaultPrevented || ("ontouchstart" in document.documentElement && y(document.body.children).forEach((function(t) {
                                    return J.off(t, "mouseover", null, (function() {}))
                                })), n[i].setAttribute("aria-expanded", "false"), s._popper && s._popper.destroy(), l.classList.remove(Fe), r.classList.remove(Fe), J.trigger(r, Ve.HIDDEN, a))
                        }
                    }
            }, e.getParentFromElement = function(t) {
                return d(t) || t.parentNode
            }, e.dataApiKeydownHandler = function(t) {
                if ((/input|textarea/i.test(t.target.tagName) ? !(32 === t.which || 27 !== t.which && (40 !== t.which && 38 !== t.which || ft.closest(t.target, Je))) : Ke.test(t.which)) && (t.preventDefault(), t.stopPropagation(), !this.disabled && !this.classList.contains(Qe))) {
                    var n = e.getParentFromElement(this),
                        i = n.classList.contains(Fe);
                    if (!i || i && (27 === t.which || 32 === t.which)) return 27 === t.which && ft.findOne(Ze, n).focus(), void e.clearMenus();
                    var o = y(ft.find(nn, n)).filter(E);
                    if (o.length) {
                        var r = o.indexOf(t.target);
                        38 === t.which && r > 0 && r--, 40 === t.which && r < o.length - 1 && r++, r < 0 && (r = 0), o[r].focus()
                    }
                }
            }, e.getInstance = function(t) {
                return L(t, Ue)
            }, i(e, null, [{
                key: "VERSION",
                get: function() {
                    return "3.0.0-rc.0"
                }
            }, {
                key: "Default",
                get: function() {
                    return un
                }
            }, {
                key: "DefaultType",
                get: function() {
                    return fn
                }
            }]), e
        }();
    J.on(document, Ve.KEYDOWN_DATA_API, Ze, hn.dataApiKeydownHandler), J.on(document, Ve.KEYDOWN_DATA_API, Je, hn.dataApiKeydownHandler), J.on(document, Ve.CLICK_DATA_API, hn.clearMenus), J.on(document, Ve.KEYUP_DATA_API, hn.clearMenus), J.on(document, Ve.CLICK_DATA_API, Ze, (function(t) {
        t.preventDefault(), t.stopPropagation(), hn.dropdownInterface(this, "toggle")
    })), J.on(document, Ve.CLICK_DATA_API, $e, (function(t) {
        return t.stopPropagation()
    }));
    var dn = w();
    if (dn) {
        var gn = dn.fn[xe];
        dn.fn[xe] = hn.jQueryInterface, dn.fn[xe].Constructor = hn, dn.fn[xe].noConflict = function() {
            return dn.fn[xe] = gn, hn.jQueryInterface
        }
    }
    var pn = ".coreui.modal",
        _n = {
            backdrop: !0,
            keyboard: !0,
            focus: !0,
            show: !0
        },
        mn = {
            backdrop: "(boolean|string)",
            keyboard: "boolean",
            focus: "boolean",
            show: "boolean"
        },
        vn = {
            HIDE: "hide" + pn,
            HIDE_PREVENTED: "hidePrevented" + pn,
            HIDDEN: "hidden" + pn,
            SHOW: "show" + pn,
            SHOWN: "shown" + pn,
            FOCUSIN: "focusin" + pn,
            RESIZE: "resize" + pn,
            CLICK_DISMISS: "click.dismiss" + pn,
            KEYDOWN_DISMISS: "keydown.dismiss" + pn,
            MOUSEUP_DISMISS: "mouseup.dismiss" + pn,
            MOUSEDOWN_DISMISS: "mousedown.dismiss" + pn,
            CLICK_DATA_API: "click.coreui.modal.data-api"
        },
        yn = "modal-dialog-scrollable",
        En = "modal-scrollbar-measure",
        bn = "modal-backdrop",
        An = "modal-open",
        wn = "fade",
        Sn = "show",
        In = "modal-static",
        Ln = ".modal-dialog",
        Tn = ".modal-body",
        Dn = '[data-toggle="modal"]',
        Cn = '[data-dismiss="modal"]',
        On = ".fixed-top, .fixed-bottom, .is-fixed, .sticky-top",
        kn = ".sticky-top",
        Nn = function() {
            function t(t, e) {
                this._config = this._getConfig(e), this._element = t, this._dialog = ft.findOne(Ln, t), this._backdrop = null, this._isShown = !1, this._isBodyOverflowing = !1, this._ignoreBackdropClick = !1, this._isTransitioning = !1, this._scrollbarWidth = 0, I(t, "coreui.modal", this)
            }
            var e = t.prototype;
            return e.toggle = function(t) {
                return this._isShown ? this.hide() : this.show(t)
            }, e.show = function(t) {
                var e = this;
                if (!this._isShown && !this._isTransitioning) {
                    this._element.classList.contains(wn) && (this._isTransitioning = !0);
                    var n = J.trigger(this._element, vn.SHOW, {
                        relatedTarget: t
                    });
                    this._isShown || n.defaultPrevented || (this._isShown = !0, this._checkScrollbar(), this._setScrollbar(), this._adjustDialog(), this._setEscapeEvent(), this._setResizeEvent(), J.on(this._element, vn.CLICK_DISMISS, Cn, (function(t) {
                        return e.hide(t)
                    })), J.on(this._dialog, vn.MOUSEDOWN_DISMISS, (function() {
                        J.one(e._element, vn.MOUSEUP_DISMISS, (function(t) {
                            t.target === e._element && (e._ignoreBackdropClick = !0)
                        }))
                    })), this._showBackdrop((function() {
                        return e._showElement(t)
                    })))
                }
            }, e.hide = function(t) {
                var e = this;
                if ((t && t.preventDefault(), this._isShown && !this._isTransitioning) && !J.trigger(this._element, vn.HIDE).defaultPrevented) {
                    this._isShown = !1;
                    var n = this._element.classList.contains(wn);
                    if (n && (this._isTransitioning = !0), this._setEscapeEvent(), this._setResizeEvent(), J.off(document, vn.FOCUSIN), this._element.classList.remove(Sn), J.off(this._element, vn.CLICK_DISMISS), J.off(this._dialog, vn.MOUSEDOWN_DISMISS), n) {
                        var i = g(this._element);
                        J.one(this._element, "transitionend", (function(t) {
                            return e._hideModal(t)
                        })), m(this._element, i)
                    } else this._hideModal()
                }
            }, e.dispose = function() {
                [window, this._element, this._dialog].forEach((function(t) {
                    return J.off(t, pn)
                })), J.off(document, vn.FOCUSIN), T(this._element, "coreui.modal"), this._config = null, this._element = null, this._dialog = null, this._backdrop = null, this._isShown = null, this._isBodyOverflowing = null, this._ignoreBackdropClick = null, this._isTransitioning = null, this._scrollbarWidth = null
            }, e.handleUpdate = function() {
                this._adjustDialog()
            }, e._getConfig = function(t) {
                return t = s({}, _n, {}, t), v("modal", t, mn), t
            }, e._showElement = function(t) {
                var e = this,
                    n = this._element.classList.contains(wn),
                    i = ft.findOne(Tn, this._dialog);
                this._element.parentNode && this._element.parentNode.nodeType === Node.ELEMENT_NODE || document.body.appendChild(this._element), this._element.style.display = "block", this._element.removeAttribute("aria-hidden"), this._element.setAttribute("aria-modal", !0), this._dialog.classList.contains(yn) && i ? i.scrollTop = 0 : this._element.scrollTop = 0, n && A(this._element), this._element.classList.add(Sn), this._config.focus && this._enforceFocus();
                var o = function() {
                    e._config.focus && e._element.focus(), e._isTransitioning = !1, J.trigger(e._element, vn.SHOWN, {
                        relatedTarget: t
                    })
                };
                if (n) {
                    var r = g(this._dialog);
                    J.one(this._dialog, "transitionend", o), m(this._dialog, r)
                } else o()
            }, e._enforceFocus = function() {
                var t = this;
                J.off(document, vn.FOCUSIN), J.on(document, vn.FOCUSIN, (function(e) {
                    document === e.target || t._element === e.target || t._element.contains(e.target) || t._element.focus()
                }))
            }, e._setEscapeEvent = function() {
                var t = this;
                this._isShown && this._config.keyboard ? J.on(this._element, vn.KEYDOWN_DISMISS, (function(e) {
                    27 === e.which && t._triggerBackdropTransition()
                })) : J.off(this._element, vn.KEYDOWN_DISMISS)
            }, e._setResizeEvent = function() {
                var t = this;
                this._isShown ? J.on(window, vn.RESIZE, (function() {
                    return t._adjustDialog()
                })) : J.off(window, vn.RESIZE)
            }, e._hideModal = function() {
                var t = this;
                this._element.style.display = "none", this._element.setAttribute("aria-hidden", !0), this._element.removeAttribute("aria-modal"), this._isTransitioning = !1, this._showBackdrop((function() {
                    document.body.classList.remove(An), t._resetAdjustments(), t._resetScrollbar(), J.trigger(t._element, vn.HIDDEN)
                }))
            }, e._removeBackdrop = function() {
                this._backdrop.parentNode.removeChild(this._backdrop), this._backdrop = null
            }, e._showBackdrop = function(t) {
                var e = this,
                    n = this._element.classList.contains(wn) ? wn : "";
                if (this._isShown && this._config.backdrop) {
                    if (this._backdrop = document.createElement("div"), this._backdrop.className = bn, n && this._backdrop.classList.add(n), document.body.appendChild(this._backdrop), J.on(this._element, vn.CLICK_DISMISS, (function(t) {
                            e._ignoreBackdropClick ? e._ignoreBackdropClick = !1 : t.target === t.currentTarget && e._triggerBackdropTransition()
                        })), n && A(this._backdrop), this._backdrop.classList.add(Sn), !n) return void t();
                    var i = g(this._backdrop);
                    J.one(this._backdrop, "transitionend", t), m(this._backdrop, i)
                } else if (!this._isShown && this._backdrop) {
                    this._backdrop.classList.remove(Sn);
                    var o = function() {
                        e._removeBackdrop(), t()
                    };
                    if (this._element.classList.contains(wn)) {
                        var r = g(this._backdrop);
                        J.one(this._backdrop, "transitionend", o), m(this._backdrop, r)
                    } else o()
                } else t()
            }, e._triggerBackdropTransition = function() {
                var t = this;
                if ("static" === this._config.backdrop) {
                    if (J.trigger(this._element, vn.HIDE_PREVENTED).defaultPrevented) return;
                    this._element.classList.add(In);
                    var e = g(this._element);
                    J.one(this._element, "transitionend", (function() {
                        t._element.classList.remove(In)
                    })), m(this._element, e), this._element.focus()
                } else this.hide()
            }, e._adjustDialog = function() {
                var t = this._element.scrollHeight > document.documentElement.clientHeight;
                !this._isBodyOverflowing && t && (this._element.style.paddingLeft = this._scrollbarWidth + "px"), this._isBodyOverflowing && !t && (this._element.style.paddingRight = this._scrollbarWidth + "px")
            }, e._resetAdjustments = function() {
                this._element.style.paddingLeft = "", this._element.style.paddingRight = ""
            }, e._checkScrollbar = function() {
                var t = document.body.getBoundingClientRect();
                this._isBodyOverflowing = t.left + t.right < window.innerWidth, this._scrollbarWidth = this._getScrollbarWidth()
            }, e._setScrollbar = function() {
                var t = this;
                if (this._isBodyOverflowing) {
                    y(ft.find(On)).forEach((function(e) {
                        var n = e.style.paddingRight,
                            i = window.getComputedStyle(e)["padding-right"];
                        Ht.setDataAttribute(e, "padding-right", n), e.style.paddingRight = parseFloat(i) + t._scrollbarWidth + "px"
                    })), y(ft.find(kn)).forEach((function(e) {
                        var n = e.style.marginRight,
                            i = window.getComputedStyle(e)["margin-right"];
                        Ht.setDataAttribute(e, "margin-right", n), e.style.marginRight = parseFloat(i) - t._scrollbarWidth + "px"
                    }));
                    var e = document.body.style.paddingRight,
                        n = window.getComputedStyle(document.body)["padding-right"];
                    Ht.setDataAttribute(document.body, "padding-right", e), document.body.style.paddingRight = parseFloat(n) + this._scrollbarWidth + "px"
                }
                document.body.classList.add(An)
            }, e._resetScrollbar = function() {
                y(ft.find(On)).forEach((function(t) {
                    var e = Ht.getDataAttribute(t, "padding-right");
                    "undefined" != typeof e && (Ht.removeDataAttribute(t, "padding-right"), t.style.paddingRight = e)
                })), y(ft.find("" + kn)).forEach((function(t) {
                    var e = Ht.getDataAttribute(t, "margin-right");
                    "undefined" != typeof e && (Ht.removeDataAttribute(t, "margin-right"), t.style.marginRight = e)
                }));
                var t = Ht.getDataAttribute(document.body, "padding-right");
                "undefined" == typeof t ? document.body.style.paddingRight = "" : (Ht.removeDataAttribute(document.body, "padding-right"), document.body.style.paddingRight = t)
            }, e._getScrollbarWidth = function() {
                var t = document.createElement("div");
                t.className = En, document.body.appendChild(t);
                var e = t.getBoundingClientRect().width - t.clientWidth;
                return document.body.removeChild(t), e
            }, t.jQueryInterface = function(e, n) {
                return this.each((function() {
                    var i = L(this, "coreui.modal"),
                        o = s({}, _n, {}, Ht.getDataAttributes(this), {}, "object" == typeof e && e ? e : {});
                    if (i || (i = new t(this, o)), "string" == typeof e) {
                        if ("undefined" == typeof i[e]) throw new TypeError('No method named "' + e + '"');
                        i[e](n)
                    } else o.show && i.show(n)
                }))
            }, t.getInstance = function(t) {
                return L(t, "coreui.modal")
            }, i(t, null, [{
                key: "VERSION",
                get: function() {
                    return "3.0.0-rc.0"
                }
            }, {
                key: "Default",
                get: function() {
                    return _n
                }
            }]), t
        }();
    J.on(document, vn.CLICK_DATA_API, Dn, (function(t) {
        var e = this,
            n = d(this);
        "A" !== this.tagName && "AREA" !== this.tagName || t.preventDefault(), J.one(n, vn.SHOW, (function(t) {
            t.defaultPrevented || J.one(n, vn.HIDDEN, (function() {
                E(e) && e.focus()
            }))
        }));
        var i = L(n, "coreui.modal");
        if (!i) {
            var o = s({}, Ht.getDataAttributes(n), {}, Ht.getDataAttributes(this));
            i = new Nn(n, o)
        }
        i.show(this)
    }));
    var Pn = w();
    if (Pn) {
        var Hn = Pn.fn.modal;
        Pn.fn.modal = Nn.jQueryInterface, Pn.fn.modal.Constructor = Nn, Pn.fn.modal.noConflict = function() {
            return Pn.fn.modal = Hn, Nn.jQueryInterface
        }
    }
    var jn = ["background", "cite", "href", "itemtype", "longdesc", "poster", "src", "xlink:href"],
        Rn = /^(?:(?:https?|mailto|ftp|tel|file):|[^&:/?#]*(?:[/?#]|$))/gi,
        Mn = /^data:(?:image\/(?:bmp|gif|jpeg|jpg|png|tiff|webp)|video\/(?:mpeg|mp4|ogg|webm)|audio\/(?:mp3|oga|ogg|opus));base64,[a-z0-9+/]+=*$/i,
        Wn = {
            "*": ["class", "dir", "id", "lang", "role", /^aria-[\w-]*$/i],
            a: ["target", "href", "title", "rel"],
            area: [],
            b: [],
            br: [],
            col: [],
            code: [],
            div: [],
            em: [],
            hr: [],
            h1: [],
            h2: [],
            h3: [],
            h4: [],
            h5: [],
            h6: [],
            i: [],
            img: ["src", "alt", "title", "width", "height"],
            li: [],
            ol: [],
            p: [],
            pre: [],
            s: [],
            small: [],
            span: [],
            sub: [],
            sup: [],
            strong: [],
            u: [],
            ul: []
        };

    function xn(t, e, n) {
        if (!t.length) return t;
        if (n && "function" == typeof n) return n(t);
        for (var i = (new window.DOMParser).parseFromString(t, "text/html"), o = Object.keys(e), r = y(i.body.querySelectorAll("*")), s = function(t, n) {
                var i = r[t],
                    s = i.nodeName.toLowerCase();
                if (-1 === o.indexOf(s)) return i.parentNode.removeChild(i), "continue";
                var a = y(i.attributes),
                    l = [].concat(e["*"] || [], e[s] || []);
                a.forEach((function(t) {
                    (function(t, e) {
                        var n = t.nodeName.toLowerCase();
                        if (-1 !== e.indexOf(n)) return -1 === jn.indexOf(n) || Boolean(t.nodeValue.match(Rn) || t.nodeValue.match(Mn));
                        for (var i = e.filter((function(t) {
                                return t instanceof RegExp
                            })), o = 0, r = i.length; o < r; o++)
                            if (n.match(i[o])) return !0;
                        return !1
                    })(t, l) || i.removeAttribute(t.nodeName)
                }))
            }, a = 0, l = r.length; a < l; a++) s(a);
        return i.body.innerHTML
    }
    var Un = "tooltip",
        Bn = ".coreui.tooltip",
        Kn = new RegExp("(^|\\s)bs-tooltip\\S+", "g"),
        Vn = ["sanitize", "whiteList", "sanitizeFn"],
        Qn = {
            animation: "boolean",
            template: "string",
            title: "(string|element|function)",
            trigger: "string",
            delay: "(number|object)",
            html: "boolean",
            selector: "(string|boolean)",
            placement: "(string|function)",
            offset: "(number|string|function)",
            container: "(string|element|boolean)",
            fallbackPlacement: "(string|array)",
            boundary: "(string|element)",
            sanitize: "boolean",
            sanitizeFn: "(null|function)",
            whiteList: "object",
            popperConfig: "(null|object)"
        },
        Fn = {
            AUTO: "auto",
            TOP: "top",
            RIGHT: "right",
            BOTTOM: "bottom",
            LEFT: "left"
        },
        qn = {
            animation: !0,
            template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
            trigger: "hover focus",
            title: "",
            delay: 0,
            html: !1,
            selector: !1,
            placement: "top",
            offset: 0,
            container: !1,
            fallbackPlacement: "flip",
            boundary: "scrollParent",
            sanitize: !0,
            sanitizeFn: null,
            whiteList: Wn,
            popperConfig: null
        },
        Gn = "show",
        Yn = "out",
        Xn = {
            HIDE: "hide" + Bn,
            HIDDEN: "hidden" + Bn,
            SHOW: "show" + Bn,
            SHOWN: "shown" + Bn,
            INSERTED: "inserted" + Bn,
            CLICK: "click" + Bn,
            FOCUSIN: "focusin" + Bn,
            FOCUSOUT: "focusout" + Bn,
            MOUSEENTER: "mouseenter" + Bn,
            MOUSELEAVE: "mouseleave" + Bn
        },
        zn = "fade",
        Zn = "show",
        $n = ".tooltip-inner",
        Jn = "hover",
        ti = "focus",
        ei = "click",
        ni = "manual",
        ii = function() {
            function e(e, n) {
                if ("undefined" == typeof t) throw new TypeError("Bootstrap's tooltips require Popper.js (https://popper.js.org)");
                this._isEnabled = !0, this._timeout = 0, this._hoverState = "", this._activeTrigger = {}, this._popper = null, this.element = e, this.config = this._getConfig(n), this.tip = null, this._setListeners(), I(e, this.constructor.DATA_KEY, this)
            }
            var n = e.prototype;
            return n.enable = function() {
                this._isEnabled = !0
            }, n.disable = function() {
                this._isEnabled = !1
            }, n.toggleEnabled = function() {
                this._isEnabled = !this._isEnabled
            }, n.toggle = function(t) {
                if (this._isEnabled)
                    if (t) {
                        var e = this.constructor.DATA_KEY,
                            n = L(t.delegateTarget, e);
                        n || (n = new this.constructor(t.delegateTarget, this._getDelegateConfig()), I(t.delegateTarget, e, n)), n._activeTrigger.click = !n._activeTrigger.click, n._isWithActiveTrigger() ? n._enter(null, n) : n._leave(null, n)
                    } else {
                        if (this.getTipElement().classList.contains(Zn)) return void this._leave(null, this);
                        this._enter(null, this)
                    }
            }, n.dispose = function() {
                clearTimeout(this._timeout), T(this.element, this.constructor.DATA_KEY), J.off(this.element, this.constructor.EVENT_KEY), J.off(ft.closest(this.element, ".modal"), "hide.bs.modal", this._hideModalHandler), this.tip && this.tip.parentNode.removeChild(this.tip), this._isEnabled = null, this._timeout = null, this._hoverState = null, this._activeTrigger = null, this._popper && this._popper.destroy(), this._popper = null, this.element = null, this.config = null, this.tip = null
            }, n.show = function() {
                var e = this;
                if ("none" === this.element.style.display) throw new Error("Please use show on visible elements");
                if (this.isWithContent() && this._isEnabled) {
                    var n = J.trigger(this.element, this.constructor.Event.SHOW),
                        i = function t(e) {
                            if (!document.documentElement.attachShadow) return null;
                            if ("function" == typeof e.getRootNode) {
                                var n = e.getRootNode();
                                return n instanceof ShadowRoot ? n : null
                            }
                            return e instanceof ShadowRoot ? e : e.parentNode ? t(e.parentNode) : null
                        }(this.element),
                        o = null === i ? this.element.ownerDocument.documentElement.contains(this.element) : i.contains(this.element);
                    if (n.defaultPrevented || !o) return;
                    var r = this.getTipElement(),
                        s = u(this.constructor.NAME);
                    r.setAttribute("id", s), this.element.setAttribute("aria-describedby", s), this.setContent(), this.config.animation && r.classList.add(zn);
                    var a = "function" == typeof this.config.placement ? this.config.placement.call(this, r, this.element) : this.config.placement,
                        l = this._getAttachment(a);
                    this._addAttachmentClass(l);
                    var c = this._getContainer();
                    I(r, this.constructor.DATA_KEY, this), this.element.ownerDocument.documentElement.contains(this.tip) || c.appendChild(r), J.trigger(this.element, this.constructor.Event.INSERTED), this._popper = new t(this.element, r, this._getPopperConfig(l)), r.classList.add(Zn), "ontouchstart" in document.documentElement && y(document.body.children).forEach((function(t) {
                        J.on(t, "mouseover", (function() {}))
                    }));
                    var f = function() {
                        e.config.animation && e._fixTransition();
                        var t = e._hoverState;
                        e._hoverState = null, J.trigger(e.element, e.constructor.Event.SHOWN), t === Yn && e._leave(null, e)
                    };
                    if (this.tip.classList.contains(zn)) {
                        var h = g(this.tip);
                        J.one(this.tip, "transitionend", f), m(this.tip, h)
                    } else f()
                }
            }, n.hide = function() {
                var t = this,
                    e = this.getTipElement(),
                    n = function() {
                        t._hoverState !== Gn && e.parentNode && e.parentNode.removeChild(e), t._cleanTipClass(), t.element.removeAttribute("aria-describedby"), J.trigger(t.element, t.constructor.Event.HIDDEN), t._popper.destroy()
                    };
                if (!J.trigger(this.element, this.constructor.Event.HIDE).defaultPrevented) {
                    if (e.classList.remove(Zn), "ontouchstart" in document.documentElement && y(document.body.children).forEach((function(t) {
                            return J.off(t, "mouseover", b)
                        })), this._activeTrigger[ei] = !1, this._activeTrigger[ti] = !1, this._activeTrigger[Jn] = !1, this.tip.classList.contains(zn)) {
                        var i = g(e);
                        J.one(e, "transitionend", n), m(e, i)
                    } else n();
                    this._hoverState = ""
                }
            }, n.update = function() {
                null !== this._popper && this._popper.scheduleUpdate()
            }, n.isWithContent = function() {
                return Boolean(this.getTitle())
            }, n.getTipElement = function() {
                if (this.tip) return this.tip;
                var t = document.createElement("div");
                return t.innerHTML = this.config.template, this.tip = t.children[0], this.tip
            }, n.setContent = function() {
                var t = this.getTipElement();
                this.setElementContent(ft.findOne($n, t), this.getTitle()), t.classList.remove(zn), t.classList.remove(Zn)
            }, n.setElementContent = function(t, e) {
                if (null !== t) return "object" == typeof e && _(e) ? (e.jquery && (e = e[0]), void(this.config.html ? e.parentNode !== t && (t.innerHTML = "", t.appendChild(e)) : t.innerText = e.textContent)) : void(this.config.html ? (this.config.sanitize && (e = xn(e, this.config.whiteList, this.config.sanitizeFn)), t.innerHTML = e) : t.innerText = e)
            }, n.getTitle = function() {
                var t = this.element.getAttribute("data-original-title");
                return t || (t = "function" == typeof this.config.title ? this.config.title.call(this.element) : this.config.title), t
            }, n._getPopperConfig = function(t) {
                var e = this;
                return s({}, {
                    placement: t,
                    modifiers: {
                        offset: this._getOffset(),
                        flip: {
                            behavior: this.config.fallbackPlacement
                        },
                        arrow: {
                            element: "." + this.constructor.NAME + "-arrow"
                        },
                        preventOverflow: {
                            boundariesElement: this.config.boundary
                        }
                    },
                    onCreate: function(t) {
                        t.originalPlacement !== t.placement && e._handlePopperPlacementChange(t)
                    },
                    onUpdate: function(t) {
                        return e._handlePopperPlacementChange(t)
                    }
                }, {}, this.config.popperConfig)
            }, n._addAttachmentClass = function(t) {
                this.getTipElement().classList.add("bs-tooltip-" + t)
            }, n._getOffset = function() {
                var t = this,
                    e = {};
                return "function" == typeof this.config.offset ? e.fn = function(e) {
                    return e.offsets = s({}, e.offsets, {}, t.config.offset(e.offsets, t.element) || {}), e
                } : e.offset = this.config.offset, e
            }, n._getContainer = function() {
                return !1 === this.config.container ? document.body : _(this.config.container) ? this.config.container : ft.findOne(this.config.container)
            }, n._getAttachment = function(t) {
                return Fn[t.toUpperCase()]
            }, n._setListeners = function() {
                var t = this;
                this.config.trigger.split(" ").forEach((function(e) {
                    if ("click" === e) J.on(t.element, t.constructor.Event.CLICK, t.config.selector, (function(e) {
                        return t.toggle(e)
                    }));
                    else if (e !== ni) {
                        var n = e === Jn ? t.constructor.Event.MOUSEENTER : t.constructor.Event.FOCUSIN,
                            i = e === Jn ? t.constructor.Event.MOUSELEAVE : t.constructor.Event.FOCUSOUT;
                        J.on(t.element, n, t.config.selector, (function(e) {
                            return t._enter(e)
                        })), J.on(t.element, i, t.config.selector, (function(e) {
                            return t._leave(e)
                        }))
                    }
                })), this._hideModalHandler = function() {
                    t.element && t.hide()
                }, J.on(ft.closest(this.element, ".modal"), "hide.bs.modal", this._hideModalHandler), this.config.selector ? this.config = s({}, this.config, {
                    trigger: "manual",
                    selector: ""
                }) : this._fixTitle()
            }, n._fixTitle = function() {
                var t = typeof this.element.getAttribute("data-original-title");
                (this.element.getAttribute("title") || "string" !== t) && (this.element.setAttribute("data-original-title", this.element.getAttribute("title") || ""), this.element.setAttribute("title", ""))
            }, n._enter = function(t, e) {
                var n = this.constructor.DATA_KEY;
                (e = e || L(t.delegateTarget, n)) || (e = new this.constructor(t.delegateTarget, this._getDelegateConfig()), I(t.delegateTarget, n, e)), t && (e._activeTrigger["focusin" === t.type ? ti : Jn] = !0), e.getTipElement().classList.contains(Zn) || e._hoverState === Gn ? e._hoverState = Gn : (clearTimeout(e._timeout), e._hoverState = Gn, e.config.delay && e.config.delay.show ? e._timeout = setTimeout((function() {
                    e._hoverState === Gn && e.show()
                }), e.config.delay.show) : e.show())
            }, n._leave = function(t, e) {
                var n = this.constructor.DATA_KEY;
                (e = e || L(t.delegateTarget, n)) || (e = new this.constructor(t.delegateTarget, this._getDelegateConfig()), I(t.delegateTarget, n, e)), t && (e._activeTrigger["focusout" === t.type ? ti : Jn] = !1), e._isWithActiveTrigger() || (clearTimeout(e._timeout), e._hoverState = Yn, e.config.delay && e.config.delay.hide ? e._timeout = setTimeout((function() {
                    e._hoverState === Yn && e.hide()
                }), e.config.delay.hide) : e.hide())
            }, n._isWithActiveTrigger = function() {
                for (var t in this._activeTrigger)
                    if (this._activeTrigger[t]) return !0;
                return !1
            }, n._getConfig = function(t) {
                var e = Ht.getDataAttributes(this.element);
                return Object.keys(e).forEach((function(t) {
                    -1 !== Vn.indexOf(t) && delete e[t]
                })), t && "object" == typeof t.container && t.container.jquery && (t.container = t.container[0]), "number" == typeof(t = s({}, this.constructor.Default, {}, e, {}, "object" == typeof t && t ? t : {})).delay && (t.delay = {
                    show: t.delay,
                    hide: t.delay
                }), "number" == typeof t.title && (t.title = t.title.toString()), "number" == typeof t.content && (t.content = t.content.toString()), v(Un, t, this.constructor.DefaultType), t.sanitize && (t.template = xn(t.template, t.whiteList, t.sanitizeFn)), t
            }, n._getDelegateConfig = function() {
                var t = {};
                if (this.config)
                    for (var e in this.config) this.constructor.Default[e] !== this.config[e] && (t[e] = this.config[e]);
                return t
            }, n._cleanTipClass = function() {
                var t = this.getTipElement(),
                    e = t.getAttribute("class").match(Kn);
                null !== e && e.length && e.map((function(t) {
                    return t.trim()
                })).forEach((function(e) {
                    return t.classList.remove(e)
                }))
            }, n._handlePopperPlacementChange = function(t) {
                var e = t.instance;
                this.tip = e.popper, this._cleanTipClass(), this._addAttachmentClass(this._getAttachment(t.placement))
            }, n._fixTransition = function() {
                var t = this.getTipElement(),
                    e = this.config.animation;
                null === t.getAttribute("x-placement") && (t.classList.remove(zn), this.config.animation = !1, this.hide(), this.show(), this.config.animation = e)
            }, e.jQueryInterface = function(t) {
                return this.each((function() {
                    var n = L(this, "coreui.tooltip"),
                        i = "object" == typeof t && t;
                    if ((n || !/dispose|hide/.test(t)) && (n || (n = new e(this, i)), "string" == typeof t)) {
                        if ("undefined" == typeof n[t]) throw new TypeError('No method named "' + t + '"');
                        n[t]()
                    }
                }))
            }, e.getInstance = function(t) {
                return L(t, "coreui.tooltip")
            }, i(e, null, [{
                key: "VERSION",
                get: function() {
                    return "3.0.0-rc.0"
                }
            }, {
                key: "Default",
                get: function() {
                    return qn
                }
            }, {
                key: "NAME",
                get: function() {
                    return Un
                }
            }, {
                key: "DATA_KEY",
                get: function() {
                    return "coreui.tooltip"
                }
            }, {
                key: "Event",
                get: function() {
                    return Xn
                }
            }, {
                key: "EVENT_KEY",
                get: function() {
                    return Bn
                }
            }, {
                key: "DefaultType",
                get: function() {
                    return Qn
                }
            }]), e
        }(),
        oi = w();
    if (oi) {
        var ri = oi.fn.tooltip;
        oi.fn.tooltip = ii.jQueryInterface, oi.fn.tooltip.Constructor = ii, oi.fn.tooltip.noConflict = function() {
            return oi.fn.tooltip = ri, ii.jQueryInterface
        }
    }
    var si = "popover",
        ai = "coreui.popover",
        li = "." + ai,
        ci = new RegExp("(^|\\s)bs-popover\\S+", "g"),
        ui = s({}, ii.Default, {
            placement: "right",
            trigger: "click",
            content: "",
            template: '<div class="popover" role="tooltip"><div class="popover-arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
        }),
        fi = s({}, ii.DefaultType, {
            content: "(string|element|function)"
        }),
        hi = "fade",
        di = "show",
        gi = ".popover-header",
        pi = ".popover-body",
        _i = {
            HIDE: "hide" + li,
            HIDDEN: "hidden" + li,
            SHOW: "show" + li,
            SHOWN: "shown" + li,
            INSERTED: "inserted" + li,
            CLICK: "click" + li,
            FOCUSIN: "focusin" + li,
            FOCUSOUT: "focusout" + li,
            MOUSEENTER: "mouseenter" + li,
            MOUSELEAVE: "mouseleave" + li
        },
        mi = function(t) {
            var e, n;

            function o() {
                return t.apply(this, arguments) || this
            }
            n = t, (e = o).prototype = Object.create(n.prototype), e.prototype.constructor = e, e.__proto__ = n;
            var r = o.prototype;
            return r.isWithContent = function() {
                return this.getTitle() || this._getContent()
            }, r.setContent = function() {
                var t = this.getTipElement();
                this.setElementContent(ft.findOne(gi, t), this.getTitle());
                var e = this._getContent();
                "function" == typeof e && (e = e.call(this.element)), this.setElementContent(ft.findOne(pi, t), e), t.classList.remove(hi), t.classList.remove(di)
            }, r._addAttachmentClass = function(t) {
                this.getTipElement().classList.add("bs-popover-" + t)
            }, r._getContent = function() {
                return this.element.getAttribute("data-content") || this.config.content
            }, r._cleanTipClass = function() {
                var t = this.getTipElement(),
                    e = t.getAttribute("class").match(ci);
                null !== e && e.length > 0 && e.map((function(t) {
                    return t.trim()
                })).forEach((function(e) {
                    return t.classList.remove(e)
                }))
            }, o.jQueryInterface = function(t) {
                return this.each((function() {
                    var e = L(this, ai),
                        n = "object" == typeof t ? t : null;
                    if ((e || !/dispose|hide/.test(t)) && (e || (e = new o(this, n), I(this, ai, e)), "string" == typeof t)) {
                        if ("undefined" == typeof e[t]) throw new TypeError('No method named "' + t + '"');
                        e[t]()
                    }
                }))
            }, o.getInstance = function(t) {
                return L(t, ai)
            }, i(o, null, [{
                key: "VERSION",
                get: function() {
                    return "3.0.0-rc.0"
                }
            }, {
                key: "Default",
                get: function() {
                    return ui
                }
            }, {
                key: "NAME",
                get: function() {
                    return si
                }
            }, {
                key: "DATA_KEY",
                get: function() {
                    return ai
                }
            }, {
                key: "Event",
                get: function() {
                    return _i
                }
            }, {
                key: "EVENT_KEY",
                get: function() {
                    return li
                }
            }, {
                key: "DefaultType",
                get: function() {
                    return fi
                }
            }]), o
        }(ii),
        vi = w();
    if (vi) {
        var yi = vi.fn.popover;
        vi.fn.popover = mi.jQueryInterface, vi.fn.popover.Constructor = mi, vi.fn.popover.noConflict = function() {
            return vi.fn.popover = yi, mi.jQueryInterface
        }
    }
    var Ei = "scrollspy",
        bi = "coreui.scrollspy",
        Ai = {
            offset: 10,
            method: "auto",
            target: ""
        },
        wi = {
            offset: "number",
            method: "string",
            target: "(string|element)"
        },
        Si = {
            ACTIVATE: "activate.coreui.scrollspy",
            SCROLL: "scroll.coreui.scrollspy",
            LOAD_DATA_API: "load.coreui.scrollspy.data-api"
        },
        Ii = "dropdown-item",
        Li = "active",
        Ti = '[data-spy="scroll"]',
        Di = ".nav, .list-group",
        Ci = ".nav-link",
        Oi = ".nav-item",
        ki = ".list-group-item",
        Ni = ".dropdown",
        Pi = ".dropdown-toggle",
        Hi = "offset",
        ji = "position",
        Ri = function() {
            function t(t, e) {
                var n = this;
                this._element = t, this._scrollElement = "BODY" === t.tagName ? window : t, this._config = this._getConfig(e), this._selector = this._config.target + " " + Ci + "," + this._config.target + " " + ki + "," + this._config.target + " ." + Ii, this._offsets = [], this._targets = [], this._activeTarget = null, this._scrollHeight = 0, J.on(this._scrollElement, Si.SCROLL, (function(t) {
                    return n._process(t)
                })), this.refresh(), this._process(), I(t, bi, this)
            }
            var e = t.prototype;
            return e.refresh = function() {
                var t = this,
                    e = this._scrollElement === this._scrollElement.window ? Hi : ji,
                    n = "auto" === this._config.method ? e : this._config.method,
                    i = n === ji ? this._getScrollTop() : 0;
                this._offsets = [], this._targets = [], this._scrollHeight = this._getScrollHeight(), y(ft.find(this._selector)).map((function(t) {
                    var e, o = h(t);
                    if (o && (e = ft.findOne(o)), e) {
                        var r = e.getBoundingClientRect();
                        if (r.width || r.height) return [Ht[n](e).top + i, o]
                    }
                    return null
                })).filter((function(t) {
                    return t
                })).sort((function(t, e) {
                    return t[0] - e[0]
                })).forEach((function(e) {
                    t._offsets.push(e[0]), t._targets.push(e[1])
                }))
            }, e.dispose = function() {
                T(this._element, bi), J.off(this._scrollElement, ".coreui.scrollspy"), this._element = null, this._scrollElement = null, this._config = null, this._selector = null, this._offsets = null, this._targets = null, this._activeTarget = null, this._scrollHeight = null
            }, e._getConfig = function(t) {
                if ("string" != typeof(t = s({}, Ai, {}, "object" == typeof t && t ? t : {})).target) {
                    var e = t.target.id;
                    e || (e = u(Ei), t.target.id = e), t.target = "#" + e
                }
                return v(Ei, t, wi), t
            }, e._getScrollTop = function() {
                return this._scrollElement === window ? this._scrollElement.pageYOffset : this._scrollElement.scrollTop
            }, e._getScrollHeight = function() {
                return this._scrollElement.scrollHeight || Math.max(document.body.scrollHeight, document.documentElement.scrollHeight)
            }, e._getOffsetHeight = function() {
                return this._scrollElement === window ? window.innerHeight : this._scrollElement.getBoundingClientRect().height
            }, e._process = function() {
                var t = this._getScrollTop() + this._config.offset,
                    e = this._getScrollHeight(),
                    n = this._config.offset + e - this._getOffsetHeight();
                if (this._scrollHeight !== e && this.refresh(), t >= n) {
                    var i = this._targets[this._targets.length - 1];
                    this._activeTarget !== i && this._activate(i)
                } else {
                    if (this._activeTarget && t < this._offsets[0] && this._offsets[0] > 0) return this._activeTarget = null, void this._clear();
                    for (var o = this._offsets.length; o--;) {
                        this._activeTarget !== this._targets[o] && t >= this._offsets[o] && ("undefined" == typeof this._offsets[o + 1] || t < this._offsets[o + 1]) && this._activate(this._targets[o])
                    }
                }
            }, e._activate = function(t) {
                this._activeTarget = t, this._clear();
                var e = this._selector.split(",").map((function(e) {
                        return e + '[data-target="' + t + '"],' + e + '[href="' + t + '"]'
                    })),
                    n = ft.findOne(e.join(","));
                n.classList.contains(Ii) ? (ft.findOne(Pi, ft.closest(n, Ni)).classList.add(Li), n.classList.add(Li)) : (n.classList.add(Li), ft.parents(n, Di).forEach((function(t) {
                    ft.prev(t, Ci + ", " + ki).forEach((function(t) {
                        return t.classList.add(Li)
                    })), ft.prev(t, Oi).forEach((function(t) {
                        ft.children(t, Ci).forEach((function(t) {
                            return t.classList.add(Li)
                        }))
                    }))
                }))), J.trigger(this._scrollElement, Si.ACTIVATE, {
                    relatedTarget: t
                })
            }, e._clear = function() {
                y(ft.find(this._selector)).filter((function(t) {
                    return t.classList.contains(Li)
                })).forEach((function(t) {
                    return t.classList.remove(Li)
                }))
            }, t.jQueryInterface = function(e) {
                return this.each((function() {
                    var n = L(this, bi);
                    if (n || (n = new t(this, "object" == typeof e && e)), "string" == typeof e) {
                        if ("undefined" == typeof n[e]) throw new TypeError('No method named "' + e + '"');
                        n[e]()
                    }
                }))
            }, t.getInstance = function(t) {
                return L(t, bi)
            }, i(t, null, [{
                key: "VERSION",
                get: function() {
                    return "3.0.0-rc.0"
                }
            }, {
                key: "Default",
                get: function() {
                    return Ai
                }
            }]), t
        }();
    J.on(window, Si.LOAD_DATA_API, (function() {
        y(ft.find(Ti)).forEach((function(t) {
            return new Ri(t, Ht.getDataAttributes(t))
        }))
    }));
    var Mi = w();
    if (Mi) {
        var Wi = Mi.fn[Ei];
        Mi.fn[Ei] = Ri.jQueryInterface, Mi.fn[Ei].Constructor = Ri, Mi.fn[Ei].noConflict = function() {
            return Mi.fn[Ei] = Wi, Ri.jQueryInterface
        }
    }
    var xi = {
            dropdownAccordion: "boolean"
        },
        Ui = 400,
        Bi = {
            ACTIVE: "c-active",
            BACKDROP: "c-sidebar-backdrop",
            FADE: "c-fade",
            NAV_DROPDOWN: "c-sidebar-nav-dropdown",
            NAV_DROPDOWN_TOGGLE: "c-sidebar-nav-dropdown-toggle",
            SHOW: "c-show",
            SIDEBAR_MINIMIZED: "c-sidebar-minimized",
            SIDEBAR_OVERLAID: "c-sidebar-overlaid",
            SIDEBAR_SHOW: "c-sidebar-show"
        },
        Ki = {
            CLASS_TOGGLE: "classtoggle",
            CLICK: "click",
            CLICK_DATA_API: "click.coreui.sidebar.data-api",
            DESTROY: "destroy",
            INIT: "init",
            LOAD_DATA_API: "load.coreui.sidebar.data-api",
            TOGGLE: "toggle",
            UPDATE: "update"
        },
        Vi = ".c-sidebar-nav-dropdown-toggle",
        Qi = ".c-sidebar-nav-dropdown",
        Fi = ".c-sidebar-nav-link",
        qi = ".c-sidebar-nav",
        Gi = ".c-sidebar",
        Yi = function() {
            function t(t) {
                this._element = t, this.mobile = this._isMobile.bind(this), this.ps = null, this._backdrop = null, this._perfectScrollbar(Ki.INIT), this._setActiveLink(), this._toggleClickOut(), this._clickOutListener = this._clickOutListener.bind(this), this._addEventListeners()
            }
            var n = t.prototype;
            return n._getAllSiblings = function(t, e) {
                var n = [];
                t = t.parentNode.firstChild;
                do {
                    3 !== t.nodeType && (e && !e(t) || n.push(t))
                } while (t = t.nextSibling);
                return n
            }, n._toggleDropdown = function(t) {
                var e = t.target;
                e.classList.contains(Bi.NAV_DROPDOWN_TOGGLE) || (e = e.closest(Vi)), e.closest(qi).dataset.drodpownAccordion && this._getAllSiblings(e.parentElement).forEach((function(t) {
                    t !== e.parentNode && t.classList.contains(Bi.NAV_DROPDOWN) && t.classList.remove(Bi.SHOW)
                })), e.parentNode.classList.toggle(Bi.SHOW), this._perfectScrollbar(Ki.UPDATE)
            }, n._closeSidebar = function(t) {
                var e = t.target;
                e.classList.contains(Bi.NAV_LINK) || (e = e.closest(Fi)), this.mobile && !e.classList.contains(Bi.NAV_DROPDOWN_TOGGLE) && (this._removeClickOut(), this._element.classList.remove(Bi.SIDEBAR_SHOW))
            }, n._perfectScrollbar = function(t) {
                var n = this;
                "undefined" != typeof e && (t !== Ki.INIT || this._element.classList.contains(Bi.SIDEBAR_MINIMIZED) || (this.ps = this._makeScrollbar()), t === Ki.DESTROY && this._destroyScrollbar(), t === Ki.TOGGLE && (this._element.classList.contains(Bi.SIDEBAR_MINIMIZED) ? this._destroyScrollbar() : (this._destroyScrollbar(), this.ps = this._makeScrollbar())), t !== Ki.UPDATE || this._element.classList.contains(Bi.SIDEBAR_MINIMIZED) || setTimeout((function() {
                    n._destroyScrollbar(), n.ps = n._makeScrollbar()
                }), Ui))
            }, n._makeScrollbar = function(t) {
                if (void 0 === t && (t = qi), this._element.querySelector(t)) return new e(this._element.querySelector(t), {
                    suppressScrollX: !0
                })
            }, n._destroyScrollbar = function() {
                this.ps && (this.ps.destroy(), this.ps = null)
            }, n._getParents = function(t, e) {
                for (var n = []; t && t !== document; t = t.parentNode) e ? t.matches(e) && n.push(t) : n.push(t);
                return n
            }, n._setActiveLink = function() {
                var t = this;
                Array.from(this._element.querySelectorAll(Fi)).forEach((function(e) {
                    var n;
                    "#" === (n = /\\?.*=/.test(String(window.location)) || /\\?./.test(String(window.location)) ? String(window.location).split("?")[0] : /#./.test(String(window.location)) ? String(window.location).split("#")[0] : String(window.location)).slice(n.length - 1) && (n = n.slice(0, -1)), e.href === n && (e.classList.add(Bi.ACTIVE), Array.from(t._getParents(e, Qi)).forEach((function(t) {
                        t.classList.add(Bi.SHOW)
                    })))
                }))
            }, n._isMobile = function(t) {
                return Boolean(window.getComputedStyle(t.target, null).getPropertyValue("--is-mobile"))
            }, n._clickOutListener = function(t) {
                this._element.contains(t.target) || (t.preventDefault(), t.stopPropagation(), this._removeClickOut(), this._element.classList.remove(Bi.SIDEBAR_SHOW))
            }, n._addClickOut = function() {
                document.addEventListener(Ki.CLICK, this._clickOutListener, !0)
            }, n._removeClickOut = function() {
                document.removeEventListener(Ki.CLICK, this._clickOutListener, !0), this._removeBackdrop()
            }, n._toggleClickOut = function() {
                this.mobile && this._element.classList.contains(Bi.SIDEBAR_SHOW) ? (this._addClickOut(), this._showBackdrop()) : this._element.classList.contains(Bi.SIDEBAR_OVERLAID) && this._element.classList.contains(Bi.SIDEBAR_SHOW) ? this._addClickOut() : this._removeClickOut()
            }, n._removeBackdrop = function() {
                this._backdrop && (this._backdrop.parentNode.removeChild(this._backdrop), this._backdrop = null)
            }, n._showBackdrop = function() {
                this._backdrop || (this._backdrop = document.createElement("div"), this._backdrop.className = Bi.BACKDROP, this._backdrop.classList.add(Bi.FADE), document.body.appendChild(this._backdrop), A(this._backdrop), this._backdrop.classList.add(Bi.SHOW))
            }, n._addEventListeners = function() {
                var t = this;
                J.on(this._element, Ki.CLASS_TOGGLE, (function(e) {
                    e.detail.className === Bi.SIDEBAR_MINIMIZED && t._perfectScrollbar(Ki.TOGGLE), e.detail.className === Bi.SIDEBAR_SHOW && t._toggleClickOut()
                })), J.on(this._element, Ki.CLICK_DATA_API, Vi, (function(e) {
                    e.preventDefault(), t._toggleDropdown(e)
                })), J.on(this._element, Ki.CLICK_DATA_API, Fi, (function(e) {
                    t._closeSidebar(e)
                }))
            }, t._sidebarInterface = function(e, n) {
                var i = L(e, "coreui.sidebar");
                if (i || (i = new t(e, "object" == typeof n && n)), "string" == typeof n) {
                    if ("undefined" == typeof i[n]) throw new TypeError('No method named "' + n + '"');
                    i[n]()
                }
            }, t.jQueryInterface = function(e) {
                return this.each((function() {
                    t._sidebarInterface(this, e)
                }))
            }, i(t, null, [{
                key: "VERSION",
                get: function() {
                    return "3.0.0-rc.0"
                }
            }, {
                key: "DefaultType",
                get: function() {
                    return xi
                }
            }]), t
        }();
    J.on(window, Ki.LOAD_DATA_API, (function() {
        Array.from(document.querySelectorAll(Gi)).forEach((function(t) {
            Yi._sidebarInterface(t)
        }))
    }));
    var Xi = w();
    if (Xi) {
        var zi = Xi.fn.sidebar;
        Xi.fn.sidebar = Yi.jQueryInterface, Xi.fn.sidebar.Constructor = Yi, Xi.fn.sidebar.noConflict = function() {
            return Xi.fn.sidebar = zi, Yi.jQueryInterface
        }
    }
    var Zi = {
            HIDE: "hide.coreui.tab",
            HIDDEN: "hidden.coreui.tab",
            SHOW: "show.coreui.tab",
            SHOWN: "shown.coreui.tab",
            CLICK_DATA_API: "click.coreui.tab.data-api"
        },
        $i = "dropdown-menu",
        Ji = "active",
        to = "disabled",
        eo = "fade",
        no = "show",
        io = ".dropdown",
        oo = ".nav, .list-group",
        ro = ".active",
        so = ":scope > li > .active",
        ao = '[data-toggle="tab"], [data-toggle="pill"], [data-toggle="list"]',
        lo = ".dropdown-toggle",
        co = ":scope > .dropdown-menu .active",
        uo = function() {
            function t(t) {
                this._element = t, I(this._element, "coreui.tab", this)
            }
            var e = t.prototype;
            return e.show = function() {
                var t = this;
                if (!(this._element.parentNode && this._element.parentNode.nodeType === Node.ELEMENT_NODE && this._element.classList.contains(Ji) || this._element.classList.contains(to))) {
                    var e, n = d(this._element),
                        i = ft.closest(this._element, oo);
                    if (i) {
                        var o = "UL" === i.nodeName || "OL" === i.nodeName ? so : ro;
                        e = (e = y(ft.find(o, i)))[e.length - 1]
                    }
                    var r = null;
                    if (e && (r = J.trigger(e, Zi.HIDE, {
                            relatedTarget: this._element
                        })), !(J.trigger(this._element, Zi.SHOW, {
                            relatedTarget: e
                        }).defaultPrevented || null !== r && r.defaultPrevented)) {
                        this._activate(this._element, i);
                        var s = function() {
                            J.trigger(e, Zi.HIDDEN, {
                                relatedTarget: t._element
                            }), J.trigger(t._element, Zi.SHOWN, {
                                relatedTarget: e
                            })
                        };
                        n ? this._activate(n, n.parentNode, s) : s()
                    }
                }
            }, e.dispose = function() {
                T(this._element, "coreui.tab"), this._element = null
            }, e._activate = function(t, e, n) {
                var i = this,
                    o = (!e || "UL" !== e.nodeName && "OL" !== e.nodeName ? ft.children(e, ro) : ft.find(so, e))[0],
                    r = n && o && o.classList.contains(eo),
                    s = function() {
                        return i._transitionComplete(t, o, n)
                    };
                if (o && r) {
                    var a = g(o);
                    o.classList.remove(no), J.one(o, "transitionend", s), m(o, a)
                } else s()
            }, e._transitionComplete = function(t, e, n) {
                if (e) {
                    e.classList.remove(Ji);
                    var i = ft.findOne(co, e.parentNode);
                    i && i.classList.remove(Ji), "tab" === e.getAttribute("role") && e.setAttribute("aria-selected", !1)
                }(t.classList.add(Ji), "tab" === t.getAttribute("role") && t.setAttribute("aria-selected", !0), A(t), t.classList.contains(eo) && t.classList.add(no), t.parentNode && t.parentNode.classList.contains($i)) && (ft.closest(t, io) && y(ft.find(lo)).forEach((function(t) {
                    return t.classList.add(Ji)
                })), t.setAttribute("aria-expanded", !0));
                n && n()
            }, t.jQueryInterface = function(e) {
                return this.each((function() {
                    var n = L(this, "coreui.tab") || new t(this);
                    if ("string" == typeof e) {
                        if ("undefined" == typeof n[e]) throw new TypeError('No method named "' + e + '"');
                        n[e]()
                    }
                }))
            }, t.getInstance = function(t) {
                return L(t, "coreui.tab")
            }, i(t, null, [{
                key: "VERSION",
                get: function() {
                    return "3.0.0-rc.0"
                }
            }]), t
        }();
    J.on(document, Zi.CLICK_DATA_API, ao, (function(t) {
        t.preventDefault(), (L(this, "coreui.tab") || new uo(this)).show()
    }));
    var fo = w();
    if (fo) {
        var ho = fo.fn.tab;
        fo.fn.tab = uo.jQueryInterface, fo.fn.tab.Constructor = uo, fo.fn.tab.noConflict = function() {
            return fo.fn.tab = ho, uo.jQueryInterface
        }
    }
    var go, po, _o, mo, vo = {
            CLICK_DISMISS: "click.dismiss.coreui.toast",
            HIDE: "hide.coreui.toast",
            HIDDEN: "hidden.coreui.toast",
            SHOW: "show.coreui.toast",
            SHOWN: "shown.coreui.toast"
        },
        yo = "fade",
        Eo = "hide",
        bo = "show",
        Ao = "showing",
        wo = {
            animation: "boolean",
            autohide: "boolean",
            delay: "number"
        },
        So = {
            animation: !0,
            autohide: !0,
            delay: 500
        },
        Io = '[data-dismiss="toast"]',
        Lo = function() {
            function t(t, e) {
                this._element = t, this._config = this._getConfig(e), this._timeout = null, this._setListeners(), I(t, "coreui.toast", this)
            }
            var e = t.prototype;
            return e.show = function() {
                var t = this;
                if (!J.trigger(this._element, vo.SHOW).defaultPrevented) {
                    this._config.animation && this._element.classList.add(yo);
                    var e = function() {
                        t._element.classList.remove(Ao), t._element.classList.add(bo), J.trigger(t._element, vo.SHOWN), t._config.autohide && (t._timeout = setTimeout((function() {
                            t.hide()
                        }), t._config.delay))
                    };
                    if (this._element.classList.remove(Eo), A(this._element), this._element.classList.add(Ao), this._config.animation) {
                        var n = g(this._element);
                        J.one(this._element, "transitionend", e), m(this._element, n)
                    } else e()
                }
            }, e.hide = function() {
                var t = this;
                if (this._element.classList.contains(bo) && !J.trigger(this._element, vo.HIDE).defaultPrevented) {
                    var e = function() {
                        t._element.classList.add(Eo), J.trigger(t._element, vo.HIDDEN)
                    };
                    if (this._element.classList.remove(bo), this._config.animation) {
                        var n = g(this._element);
                        J.one(this._element, "transitionend", e), m(this._element, n)
                    } else e()
                }
            }, e.dispose = function() {
                clearTimeout(this._timeout), this._timeout = null, this._element.classList.contains(bo) && this._element.classList.remove(bo), J.off(this._element, vo.CLICK_DISMISS), T(this._element, "coreui.toast"), this._element = null, this._config = null
            }, e._getConfig = function(t) {
                return t = s({}, So, {}, Ht.getDataAttributes(this._element), {}, "object" == typeof t && t ? t : {}), v("toast", t, this.constructor.DefaultType), t
            }, e._setListeners = function() {
                var t = this;
                J.on(this._element, vo.CLICK_DISMISS, Io, (function() {
                    return t.hide()
                }))
            }, t.jQueryInterface = function(e) {
                return this.each((function() {
                    var n = L(this, "coreui.toast");
                    if (n || (n = new t(this, "object" == typeof e && e)), "string" == typeof e) {
                        if ("undefined" == typeof n[e]) throw new TypeError('No method named "' + e + '"');
                        n[e](this)
                    }
                }))
            }, t.getInstance = function(t) {
                return L(t, "coreui.toast")
            }, i(t, null, [{
                key: "VERSION",
                get: function() {
                    return "3.0.0-rc.0"
                }
            }, {
                key: "DefaultType",
                get: function() {
                    return wo
                }
            }, {
                key: "Default",
                get: function() {
                    return So
                }
            }]), t
        }(),
        To = w();
    if (To) {
        var Do = To.fn.toast;
        To.fn.toast = Lo.jQueryInterface, To.fn.toast.Constructor = Lo, To.fn.toast.noConflict = function() {
            return To.fn.toast = Do, Lo.jQueryInterface
        }
    }
    return Array.from || (Array.from = (go = Object.prototype.toString, po = function(t) {
            return "function" == typeof t || "[object Function]" === go.call(t)
        }, _o = Math.pow(2, 53) - 1, mo = function(t) {
            var e = function(t) {
                var e = Number(t);
                return isNaN(e) ? 0 : 0 !== e && isFinite(e) ? (e > 0 ? 1 : -1) * Math.floor(Math.abs(e)) : e
            }(t);
            return Math.min(Math.max(e, 0), _o)
        }, function(t) {
            var e = this,
                n = Object(t);
            if (null == t) throw new TypeError("Array.from requires an array-like object - not null or undefined");
            var i, o = arguments.length > 1 ? arguments[1] : void 0;
            if ("undefined" != typeof o) {
                if (!po(o)) throw new TypeError("Array.from: when provided, the second argument must be a function");
                arguments.length > 2 && (i = arguments[2])
            }
            for (var r, s = mo(n.length), a = po(e) ? Object(new e(s)) : new Array(s), l = 0; l < s;) r = n[l], a[l] = o ? "undefined" == typeof i ? o(r, l) : o.call(i, r, l) : r, l += 1;
            return a.length = s, a
        })), Element.prototype.matches || (Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector), Element.prototype.closest || (Element.prototype.closest = function(t) {
            var e = this;
            do {
                if (e.matches(t)) return e;
                e = e.parentElement || e.parentNode
            } while (null !== e && 1 === e.nodeType);
            return null
        }),
        function() {
            if ("function" == typeof window.CustomEvent) return !1;
            window.CustomEvent = function(t, e) {
                e = e || {
                    bubbles: !1,
                    cancelable: !1,
                    detail: null
                };
                var n = document.createEvent("CustomEvent");
                return n.initCustomEvent(t, e.bubbles, e.cancelable, e.detail), n
            }
        }(), Element.prototype.matches || (Element.prototype.matches = Element.prototype.matchesSelector || Element.prototype.mozMatchesSelector || Element.prototype.msMatchesSelector || Element.prototype.oMatchesSelector || Element.prototype.webkitMatchesSelector || function(t) {
            for (var e = (this.document || this.ownerDocument).querySelectorAll(t), n = e.length; --n >= 0 && e.item(n) !== this;);
            return n > -1
        }), {
            AsyncLoad: lt,
            Alert: _t,
            Button: Ct,
            Carousel: le,
            ClassToggler: ye,
            Collapse: Re,
            Dropdown: hn,
            Modal: Nn,
            Popover: mi,
            Scrollspy: Ri,
            Sidebar: Yi,
            Tab: uo,
            Toast: Lo,
            Tooltip: ii
        }
}));
//# sourceMappingURL=coreui.min.js.map