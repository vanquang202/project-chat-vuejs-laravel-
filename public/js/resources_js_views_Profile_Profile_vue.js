"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_views_Profile_Profile_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Profile/Profile.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Profile/Profile.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var vuex__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");


function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) { symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); } keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: "Profile",
  data: function data() {
    return {
      flagEdit: false,
      urlImgProfile: '',
      changeImgProfile: false
    };
  },
  computed: _objectSpread(_objectSpread({}, (0,vuex__WEBPACK_IMPORTED_MODULE_1__.mapGetters)(['getName', 'getEmail', 'getImage', 'getDetails'])), {}, {
    getNameLocal: {
      get: function get() {
        return this.getName;
      },
      set: function set(value) {
        this.$store.dispatch('setName', value);
      }
    },
    getDetailsLocal: {
      get: function get() {
        return this.getDetails;
      },
      set: function set(value) {
        this.$store.dispatch('setDetails', value);
      }
    }
  }),
  methods: {
    saveProfile: function saveProfile() {
      this.saveApiProfile();
    },
    changePreviewImage: function changePreviewImage(e) {
      var file = e.target.files[0];
      this.changeImgProfile = true;
      this.urlImgProfile = URL.createObjectURL(file);
    },
    saveApiProfile: function saveApiProfile() {
      var _this = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee() {
        var formData, file;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                formData = new FormData();
                file = document.getElementById('changeF').files[0];

                if (file) {
                  formData.append('image', file);
                }

                formData.append('name', _this.getNameLocal);
                formData.append('details', _this.getDetailsLocal);
                _context.next = 7;
                return axios.post(_this.uri + 'api/save-me', formData).then(function (res) {
                  _this.flagEdit = false;

                  _this.$store.dispatch('setImage', res.data.imageNew);
                });

              case 7:
                return _context.abrupt("return", _context.sent);

              case 8:
              case "end":
                return _context.stop();
            }
          }
        }, _callee);
      }))();
    }
  }
});

/***/ }),

/***/ "./resources/js/views/Profile/Profile.vue":
/*!************************************************!*\
  !*** ./resources/js/views/Profile/Profile.vue ***!
  \************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Profile_vue_vue_type_template_id_02845e58_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Profile.vue?vue&type=template&id=02845e58&scoped=true& */ "./resources/js/views/Profile/Profile.vue?vue&type=template&id=02845e58&scoped=true&");
/* harmony import */ var _Profile_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Profile.vue?vue&type=script&lang=js& */ "./resources/js/views/Profile/Profile.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Profile_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Profile_vue_vue_type_template_id_02845e58_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _Profile_vue_vue_type_template_id_02845e58_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "02845e58",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/views/Profile/Profile.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/views/Profile/Profile.vue?vue&type=script&lang=js&":
/*!*************************************************************************!*\
  !*** ./resources/js/views/Profile/Profile.vue?vue&type=script&lang=js& ***!
  \*************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Profile_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Profile.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Profile/Profile.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Profile_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/views/Profile/Profile.vue?vue&type=template&id=02845e58&scoped=true&":
/*!*******************************************************************************************!*\
  !*** ./resources/js/views/Profile/Profile.vue?vue&type=template&id=02845e58&scoped=true& ***!
  \*******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Profile_vue_vue_type_template_id_02845e58_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Profile_vue_vue_type_template_id_02845e58_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Profile_vue_vue_type_template_id_02845e58_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Profile.vue?vue&type=template&id=02845e58&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Profile/Profile.vue?vue&type=template&id=02845e58&scoped=true&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Profile/Profile.vue?vue&type=template&id=02845e58&scoped=true&":
/*!**********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/views/Profile/Profile.vue?vue&type=template&id=02845e58&scoped=true& ***!
  \**********************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "container" }, [
    _c("div", { staticClass: "row m-4 p-4" }, [
      !_vm.flagEdit
        ? _c("div", { staticClass: "col-sm-4" }, [
            _c("img", {
              attrs: {
                width: "100%",
                src: _vm.uri + "images/" + _vm.getImage,
                alt: "",
              },
            }),
          ])
        : _vm._e(),
      _vm._v(" "),
      _vm.flagEdit
        ? _c("div", { staticClass: "col-sm-4" }, [
            !_vm.changeImgProfile
              ? _c("img", {
                  attrs: {
                    width: "100%",
                    src: _vm.uri + "images/" + _vm.getImage,
                    alt: "",
                  },
                })
              : _vm._e(),
            _vm._v(" "),
            _vm.changeImgProfile
              ? _c("img", {
                  attrs: { width: "100%", src: _vm.urlImgProfile, alt: "" },
                })
              : _vm._e(),
            _vm._v(" "),
            _c(
              "label",
              { staticClass: "btn btn-info", attrs: { for: "changeF" } },
              [_vm._v(" Change avatar ")]
            ),
            _vm._v(" "),
            _c("input", {
              staticClass: "form-control",
              staticStyle: { display: "none" },
              attrs: { type: "file", id: "changeF" },
              on: { change: _vm.changePreviewImage },
            }),
          ])
        : _vm._e(),
      _vm._v(" "),
      !_vm.flagEdit
        ? _c("div", { staticClass: "col-sm-6" }, [
            _c("div", { staticClass: "p-3" }, [
              _c("h2", [_vm._v(_vm._s(_vm.getNameLocal) + " ")]),
              _c("small", [_vm._v(_vm._s(_vm.getEmail))]),
              _vm._v(" "),
              _c("hr"),
              _vm._v(" "),
              _c("p", { staticStyle: { "white-space": "pre-line" } }, [
                _c("strong", [_vm._v("Tiểu sử : ")]),
                _vm._v(" "),
                _c("br"),
                _vm._v(" " + _vm._s(_vm.getDetailsLocal) + " "),
              ]),
            ]),
          ])
        : _vm._e(),
      _vm._v(" "),
      _vm.flagEdit
        ? _c("div", { staticClass: "col-sm-6" }, [
            _c("div", { staticClass: "p-3" }, [
              _c("h2", [
                _c("input", {
                  directives: [
                    {
                      name: "model",
                      rawName: "v-model",
                      value: _vm.getNameLocal,
                      expression: "getNameLocal",
                    },
                  ],
                  staticClass: " form-control",
                  attrs: { type: "text" },
                  domProps: { value: _vm.getNameLocal },
                  on: {
                    input: function ($event) {
                      if ($event.target.composing) {
                        return
                      }
                      _vm.getNameLocal = $event.target.value
                    },
                  },
                }),
              ]),
              _vm._v(" "),
              _c("small", [_vm._v(_vm._s(_vm.getEmail))]),
              _vm._v(" "),
              _c("hr"),
              _vm._v(" "),
              _c("br"),
              _vm._v(" "),
              _c("textarea", {
                directives: [
                  {
                    name: "model",
                    rawName: "v-model",
                    value: _vm.getDetailsLocal,
                    expression: "getDetailsLocal",
                  },
                ],
                staticClass: "form-control textarea",
                domProps: { value: _vm.getDetailsLocal },
                on: {
                  input: function ($event) {
                    if ($event.target.composing) {
                      return
                    }
                    _vm.getDetailsLocal = $event.target.value
                  },
                },
              }),
            ]),
          ])
        : _vm._e(),
      _vm._v(" "),
      !_vm.flagEdit
        ? _c("div", { staticClass: "col-sm-2 p-1", staticStyle: {} }, [
            _c(
              "button",
              {
                staticClass: "btn btn-primary",
                on: {
                  click: function ($event) {
                    _vm.flagEdit = true
                  },
                },
              },
              [_vm._v("Edit")]
            ),
          ])
        : _vm._e(),
    ]),
    _vm._v(" "),
    _vm.flagEdit
      ? _c("div", { staticClass: "col-sm-2 p-1", staticStyle: {} }, [
          _c(
            "button",
            { staticClass: "btn btn-primary", on: { click: _vm.saveProfile } },
            [_vm._v("Save")]
          ),
        ])
      : _vm._e(),
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ })

}]);