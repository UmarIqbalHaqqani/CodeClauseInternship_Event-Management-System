/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
(self["webpackChunktimetics"] = self["webpackChunktimetics"] || []).push([["assets_src_meetingListModal_js"],{

/***/ "./assets/src/meetingListModal.js":
/*!****************************************!*\
  !*** ./assets/src/meetingListModal.js ***!
  \****************************************/
/***/ (() => {

eval("jQuery(document).ready(function ($) {\n  $(document).on(\"click\", \".tt-meeting-list-item .ant-btn\", function () {\n    var id = $(this).data(\"id\");\n    var getSelectedId = new CustomEvent(\"getSelectedId\", {\n      detail: {\n        id: id,\n        modal: true\n      }\n    });\n    // To trigger the Event\n    document.dispatchEvent(getSelectedId);\n  });\n});\n\n//# sourceURL=webpack://timetics/./assets/src/meetingListModal.js?");

/***/ })

}]);