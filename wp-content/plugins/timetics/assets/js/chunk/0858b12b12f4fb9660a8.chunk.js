"use strict";
/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
(self["webpackChunktimetics"] = self["webpackChunktimetics"] || []).push([["assets_src_admin_pages_bookings_Edit_js"],{

/***/ "./assets/src/admin/pages/bookings/Edit.js":
/*!*************************************************!*\
  !*** ./assets/src/admin/pages/bookings/Edit.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"default\": () => (__WEBPACK_DEFAULT_EXPORT__)\n/* harmony export */ });\n/* harmony import */ var react_redux__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react-redux */ \"./node_modules/react-redux/es/index.js\");\n/* harmony import */ var _components_MainPageHeader__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../components/MainPageHeader */ \"./assets/src/admin/components/MainPageHeader.js\");\n/* harmony import */ var _redux_slices_bookingSlice__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../redux/slices/bookingSlice */ \"./assets/src/admin/redux/slices/bookingSlice.js\");\n/* harmony import */ var _BookingUpdateForm__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./BookingUpdateForm */ \"./assets/src/admin/pages/bookings/BookingUpdateForm.js\");\n/* harmony import */ var react_router_dom__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! react-router-dom */ \"./node_modules/react-router/dist/index.js\");\n\n\n\n\n\nvar Button = window.antd.Button;\nvar __ = wp.i18n.__;\nfunction EditBooking() {\n  var _settings$nicheData, _settings$nicheData2;\n  var settings = (0,react_redux__WEBPACK_IMPORTED_MODULE_0__.useSelector)(function (state) {\n    return state.settings;\n  });\n  var dispatch = (0,react_redux__WEBPACK_IMPORTED_MODULE_0__.useDispatch)();\n  var navigate = (0,react_router_dom__WEBPACK_IMPORTED_MODULE_4__.useNavigate)();\n  return /*#__PURE__*/React.createElement(\"div\", {\n    className: \"booking-form-wrapper\"\n  }, /*#__PURE__*/React.createElement(_components_MainPageHeader__WEBPACK_IMPORTED_MODULE_1__[\"default\"], {\n    title: __(\"Edit \" + (settings === null || settings === void 0 || (_settings$nicheData = settings.nicheData) === null || _settings$nicheData === void 0 || (_settings$nicheData = _settings$nicheData.title) === null || _settings$nicheData === void 0 || (_settings$nicheData = _settings$nicheData.event) === null || _settings$nicheData === void 0 ? void 0 : _settings$nicheData.singular), \"timetics\")\n  }), /*#__PURE__*/React.createElement(\"div\", {\n    className: \"tt-container-wrapper\"\n  }, /*#__PURE__*/React.createElement(\"div\", null, /*#__PURE__*/React.createElement(Button, {\n    className: \"tt-mb-30\",\n    size: \"large\",\n    onClick: function onClick() {\n      dispatch((0,_redux_slices_bookingSlice__WEBPACK_IMPORTED_MODULE_2__.setClearBookingData)());\n      navigate(\"/bookings\");\n    }\n  }, __(\"Back to \" + (settings === null || settings === void 0 || (_settings$nicheData2 = settings.nicheData) === null || _settings$nicheData2 === void 0 || (_settings$nicheData2 = _settings$nicheData2.title) === null || _settings$nicheData2 === void 0 || (_settings$nicheData2 = _settings$nicheData2.event) === null || _settings$nicheData2 === void 0 ? void 0 : _settings$nicheData2.plural), \"timetics\"))), /*#__PURE__*/React.createElement(_BookingUpdateForm__WEBPACK_IMPORTED_MODULE_3__[\"default\"], null)));\n}\n/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (EditBooking);\n\n//# sourceURL=webpack://timetics/./assets/src/admin/pages/bookings/Edit.js?");

/***/ })

}]);