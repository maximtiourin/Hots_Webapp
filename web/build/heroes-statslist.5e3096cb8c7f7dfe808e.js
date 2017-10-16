/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/hots_webapp/web/build/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./assets/js/heroes-statslist.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/js/heroes-statslist.js":
/*!***************************************!*\
  !*** ./assets/js/heroes-statslist.js ***!
  \***************************************/
/*! no static exports found */
/*! all exports used */
/***/ (function(module, exports) {

var heroes_statslist = {};

heroes_statslist.columns = [{ "width": "10%", "sClass": "hsl-table-portrait-td", "bSortable": false, "searchable": false, "responsivePriority": 1 }, { "title": 'Hero', "width": "18%", "sClass": "sortIcon_Text", "iDataSort": 2, "orderSequence": ['asc', 'desc'], "responsivePriority": 2 }, //iDataSort tells which column should be used as the sort value, in this case Hero_Sort
{ "title": 'Hero_Sort', "visible": false }, { "title": 'Role', "visible": false }, { "title": 'Role_Specific', "visible": false }, { "title": 'Games Played', "width": "18%", "sClass": "sortIcon_Number", "searchable": false, "orderSequence": ['desc', 'asc'], "responsivePriority": 5 }, { "title": 'Games Banned', "width": "18%", "sClass": "sortIcon_Number", "searchable": false, "orderSequence": ['desc', 'asc'], "responsivePriority": 6 }, { "title": 'Win Percent', "width": "18%", "sClass": "sortIcon_Number", "searchable": false, "orderSequence": ['desc', 'asc'], "responsivePriority": 3 }, { "title": '% Δ', "width": "5%", "sClass": "sortIcon_Number", "searchable": false, "orderSequence": ['desc', 'asc'], "responsivePriority": 4 }];

heroes_statslist.order = [[7, 'desc']]; //The default ordering of the table on load => column 8 at index 7 descending
heroes_statslist.processing = false; //Displays an indicator whenever the table is processing data
heroes_statslist.deferRender = true; //Defers rendering the table until data starts coming in
heroes_statslist.ajax = herodata_heroes_path; //Requests data from the path
//heroes_statslist.pageLength = 25; //Controls how many rows per page
heroes_statslist.paging = false; //Controls whether or not the table is allowed to paginate data by page length
heroes_statslist.responsive = true; //Controls whether or not the table collapses responsively as need
heroes_statslist.scrollX = false; //Controls whether or not the table can create a horizontal scroll bar
heroes_statslist.scrollY = false; //Controls whether or not the table can create a vertical scroll bar
heroes_statslist.dom = "<'row'<'col-sm-12'tr>>"; //Remove the search bar from the dom by modifying bootstraps default datatable dom styling (so i can implement custom search bar later)
heroes_statslist.info = false; //Controls displaying table control information, such as if filtering displaying what results are viewed out of how many

$(document).ready(function () {
    $('#hsl-table').DataTable(heroes_statslist);

    $('#heroes-statslist-toolbar-search').on("propertychange change click keyup input paste", function () {
        $('#hsl-table').DataTable().search($(this).val()).draw();
    });

    $('.hsl-rolebutton').click(function () {
        $('#hsl-table').DataTable().search($(this).attr("value")).draw();
    });
});

/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAgZDA3Y2ZhNTlmYmFlMGVjYTRkOTIiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2hlcm9lcy1zdGF0c2xpc3QuanMiXSwibmFtZXMiOlsiaGVyb2VzX3N0YXRzbGlzdCIsImNvbHVtbnMiLCJvcmRlciIsInByb2Nlc3NpbmciLCJkZWZlclJlbmRlciIsImFqYXgiLCJoZXJvZGF0YV9oZXJvZXNfcGF0aCIsInBhZ2luZyIsInJlc3BvbnNpdmUiLCJzY3JvbGxYIiwic2Nyb2xsWSIsImRvbSIsImluZm8iLCIkIiwiZG9jdW1lbnQiLCJyZWFkeSIsIkRhdGFUYWJsZSIsIm9uIiwic2VhcmNoIiwidmFsIiwiZHJhdyIsImNsaWNrIiwiYXR0ciJdLCJtYXBwaW5ncyI6IjtBQUFBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOzs7QUFHQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFLO0FBQ0w7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxtQ0FBMkIsMEJBQTBCLEVBQUU7QUFDdkQseUNBQWlDLGVBQWU7QUFDaEQ7QUFDQTtBQUNBOztBQUVBO0FBQ0EsOERBQXNELCtEQUErRDs7QUFFckg7QUFDQTs7QUFFQTtBQUNBOzs7Ozs7Ozs7Ozs7O0FDN0RBLElBQUlBLG1CQUFtQixFQUF2Qjs7QUFFQUEsaUJBQWlCQyxPQUFqQixHQUEyQixDQUN2QixFQUFDLFNBQVMsS0FBVixFQUFpQixVQUFVLHVCQUEzQixFQUFvRCxhQUFhLEtBQWpFLEVBQXdFLGNBQWMsS0FBdEYsRUFBNkYsc0JBQXNCLENBQW5ILEVBRHVCLEVBRXZCLEVBQUMsU0FBUyxNQUFWLEVBQWtCLFNBQVMsS0FBM0IsRUFBa0MsVUFBVSxlQUE1QyxFQUE2RCxhQUFhLENBQTFFLEVBQTZFLGlCQUFpQixDQUFDLEtBQUQsRUFBUSxNQUFSLENBQTlGLEVBQStHLHNCQUFzQixDQUFySSxFQUZ1QixFQUVrSDtBQUN6SSxFQUFDLFNBQVMsV0FBVixFQUF1QixXQUFXLEtBQWxDLEVBSHVCLEVBSXZCLEVBQUMsU0FBUyxNQUFWLEVBQWtCLFdBQVcsS0FBN0IsRUFKdUIsRUFLdkIsRUFBQyxTQUFTLGVBQVYsRUFBMkIsV0FBVyxLQUF0QyxFQUx1QixFQU12QixFQUFDLFNBQVMsY0FBVixFQUEwQixTQUFTLEtBQW5DLEVBQTBDLFVBQVUsaUJBQXBELEVBQXVFLGNBQWMsS0FBckYsRUFBNEYsaUJBQWlCLENBQUMsTUFBRCxFQUFTLEtBQVQsQ0FBN0csRUFBOEgsc0JBQXNCLENBQXBKLEVBTnVCLEVBT3ZCLEVBQUMsU0FBUyxjQUFWLEVBQTBCLFNBQVMsS0FBbkMsRUFBMEMsVUFBVSxpQkFBcEQsRUFBdUUsY0FBYyxLQUFyRixFQUE0RixpQkFBaUIsQ0FBQyxNQUFELEVBQVMsS0FBVCxDQUE3RyxFQUE4SCxzQkFBc0IsQ0FBcEosRUFQdUIsRUFRdkIsRUFBQyxTQUFTLGFBQVYsRUFBeUIsU0FBUyxLQUFsQyxFQUF5QyxVQUFVLGlCQUFuRCxFQUFzRSxjQUFjLEtBQXBGLEVBQTJGLGlCQUFpQixDQUFDLE1BQUQsRUFBUyxLQUFULENBQTVHLEVBQTZILHNCQUFzQixDQUFuSixFQVJ1QixFQVN2QixFQUFDLFNBQVMsS0FBVixFQUFpQixTQUFTLElBQTFCLEVBQWdDLFVBQVUsaUJBQTFDLEVBQTZELGNBQWMsS0FBM0UsRUFBa0YsaUJBQWlCLENBQUMsTUFBRCxFQUFTLEtBQVQsQ0FBbkcsRUFBb0gsc0JBQXNCLENBQTFJLEVBVHVCLENBQTNCOztBQVlBRCxpQkFBaUJFLEtBQWpCLEdBQXlCLENBQUMsQ0FBQyxDQUFELEVBQUksTUFBSixDQUFELENBQXpCLEMsQ0FBd0M7QUFDeENGLGlCQUFpQkcsVUFBakIsR0FBOEIsS0FBOUIsQyxDQUFxQztBQUNyQ0gsaUJBQWlCSSxXQUFqQixHQUErQixJQUEvQixDLENBQXFDO0FBQ3JDSixpQkFBaUJLLElBQWpCLEdBQXdCQyxvQkFBeEIsQyxDQUE4QztBQUM5QztBQUNBTixpQkFBaUJPLE1BQWpCLEdBQTBCLEtBQTFCLEMsQ0FBaUM7QUFDakNQLGlCQUFpQlEsVUFBakIsR0FBOEIsSUFBOUIsQyxDQUFvQztBQUNwQ1IsaUJBQWlCUyxPQUFqQixHQUEyQixLQUEzQixDLENBQWtDO0FBQ2xDVCxpQkFBaUJVLE9BQWpCLEdBQTJCLEtBQTNCLEMsQ0FBa0M7QUFDbENWLGlCQUFpQlcsR0FBakIsR0FBd0Isd0JBQXhCLEMsQ0FBa0Q7QUFDbERYLGlCQUFpQlksSUFBakIsR0FBd0IsS0FBeEIsQyxDQUErQjs7QUFFL0JDLEVBQUVDLFFBQUYsRUFBWUMsS0FBWixDQUFrQixZQUFXO0FBQ3pCRixNQUFFLFlBQUYsRUFBZ0JHLFNBQWhCLENBQTBCaEIsZ0JBQTFCOztBQUVBYSxNQUFFLGtDQUFGLEVBQXNDSSxFQUF0QyxDQUF5QywrQ0FBekMsRUFBMEYsWUFBVztBQUNqR0osVUFBRSxZQUFGLEVBQWdCRyxTQUFoQixHQUE0QkUsTUFBNUIsQ0FBbUNMLEVBQUUsSUFBRixFQUFRTSxHQUFSLEVBQW5DLEVBQWtEQyxJQUFsRDtBQUNILEtBRkQ7O0FBSUFQLE1BQUUsaUJBQUYsRUFBcUJRLEtBQXJCLENBQTJCLFlBQVk7QUFDbkNSLFVBQUUsWUFBRixFQUFnQkcsU0FBaEIsR0FBNEJFLE1BQTVCLENBQW1DTCxFQUFFLElBQUYsRUFBUVMsSUFBUixDQUFhLE9BQWIsQ0FBbkMsRUFBMERGLElBQTFEO0FBQ0gsS0FGRDtBQUdILENBVkQsRSIsImZpbGUiOiJoZXJvZXMtc3RhdHNsaXN0LjVlMzA5NmNiOGM3ZjdkZmU4MDhlLmpzIiwic291cmNlc0NvbnRlbnQiOlsiIFx0Ly8gVGhlIG1vZHVsZSBjYWNoZVxuIFx0dmFyIGluc3RhbGxlZE1vZHVsZXMgPSB7fTtcblxuIFx0Ly8gVGhlIHJlcXVpcmUgZnVuY3Rpb25cbiBcdGZ1bmN0aW9uIF9fd2VicGFja19yZXF1aXJlX18obW9kdWxlSWQpIHtcblxuIFx0XHQvLyBDaGVjayBpZiBtb2R1bGUgaXMgaW4gY2FjaGVcbiBcdFx0aWYoaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0pIHtcbiBcdFx0XHRyZXR1cm4gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0uZXhwb3J0cztcbiBcdFx0fVxuIFx0XHQvLyBDcmVhdGUgYSBuZXcgbW9kdWxlIChhbmQgcHV0IGl0IGludG8gdGhlIGNhY2hlKVxuIFx0XHR2YXIgbW9kdWxlID0gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0gPSB7XG4gXHRcdFx0aTogbW9kdWxlSWQsXG4gXHRcdFx0bDogZmFsc2UsXG4gXHRcdFx0ZXhwb3J0czoge31cbiBcdFx0fTtcblxuIFx0XHQvLyBFeGVjdXRlIHRoZSBtb2R1bGUgZnVuY3Rpb25cbiBcdFx0bW9kdWxlc1ttb2R1bGVJZF0uY2FsbChtb2R1bGUuZXhwb3J0cywgbW9kdWxlLCBtb2R1bGUuZXhwb3J0cywgX193ZWJwYWNrX3JlcXVpcmVfXyk7XG5cbiBcdFx0Ly8gRmxhZyB0aGUgbW9kdWxlIGFzIGxvYWRlZFxuIFx0XHRtb2R1bGUubCA9IHRydWU7XG5cbiBcdFx0Ly8gUmV0dXJuIHRoZSBleHBvcnRzIG9mIHRoZSBtb2R1bGVcbiBcdFx0cmV0dXJuIG1vZHVsZS5leHBvcnRzO1xuIFx0fVxuXG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlcyBvYmplY3QgKF9fd2VicGFja19tb2R1bGVzX18pXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm0gPSBtb2R1bGVzO1xuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZSBjYWNoZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5jID0gaW5zdGFsbGVkTW9kdWxlcztcblxuIFx0Ly8gZGVmaW5lIGdldHRlciBmdW5jdGlvbiBmb3IgaGFybW9ueSBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQgPSBmdW5jdGlvbihleHBvcnRzLCBuYW1lLCBnZXR0ZXIpIHtcbiBcdFx0aWYoIV9fd2VicGFja19yZXF1aXJlX18ubyhleHBvcnRzLCBuYW1lKSkge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBuYW1lLCB7XG4gXHRcdFx0XHRjb25maWd1cmFibGU6IGZhbHNlLFxuIFx0XHRcdFx0ZW51bWVyYWJsZTogdHJ1ZSxcbiBcdFx0XHRcdGdldDogZ2V0dGVyXG4gXHRcdFx0fSk7XG4gXHRcdH1cbiBcdH07XG5cbiBcdC8vIGdldERlZmF1bHRFeHBvcnQgZnVuY3Rpb24gZm9yIGNvbXBhdGliaWxpdHkgd2l0aCBub24taGFybW9ueSBtb2R1bGVzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm4gPSBmdW5jdGlvbihtb2R1bGUpIHtcbiBcdFx0dmFyIGdldHRlciA9IG1vZHVsZSAmJiBtb2R1bGUuX19lc01vZHVsZSA/XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0RGVmYXVsdCgpIHsgcmV0dXJuIG1vZHVsZVsnZGVmYXVsdCddOyB9IDpcbiBcdFx0XHRmdW5jdGlvbiBnZXRNb2R1bGVFeHBvcnRzKCkgeyByZXR1cm4gbW9kdWxlOyB9O1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQoZ2V0dGVyLCAnYScsIGdldHRlcik7XG4gXHRcdHJldHVybiBnZXR0ZXI7XG4gXHR9O1xuXG4gXHQvLyBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGxcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubyA9IGZ1bmN0aW9uKG9iamVjdCwgcHJvcGVydHkpIHsgcmV0dXJuIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbChvYmplY3QsIHByb3BlcnR5KTsgfTtcblxuIFx0Ly8gX193ZWJwYWNrX3B1YmxpY19wYXRoX19cbiBcdF9fd2VicGFja19yZXF1aXJlX18ucCA9IFwiL2hvdHNfd2ViYXBwL3dlYi9idWlsZC9cIjtcblxuIFx0Ly8gTG9hZCBlbnRyeSBtb2R1bGUgYW5kIHJldHVybiBleHBvcnRzXG4gXHRyZXR1cm4gX193ZWJwYWNrX3JlcXVpcmVfXyhfX3dlYnBhY2tfcmVxdWlyZV9fLnMgPSBcIi4vYXNzZXRzL2pzL2hlcm9lcy1zdGF0c2xpc3QuanNcIik7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gd2VicGFjay9ib290c3RyYXAgZDA3Y2ZhNTlmYmFlMGVjYTRkOTIiLCJ2YXIgaGVyb2VzX3N0YXRzbGlzdCA9IHt9O1xyXG5cclxuaGVyb2VzX3N0YXRzbGlzdC5jb2x1bW5zID0gW1xyXG4gICAge1wid2lkdGhcIjogXCIxMCVcIiwgXCJzQ2xhc3NcIjogXCJoc2wtdGFibGUtcG9ydHJhaXQtdGRcIiwgXCJiU29ydGFibGVcIjogZmFsc2UsIFwic2VhcmNoYWJsZVwiOiBmYWxzZSwgXCJyZXNwb25zaXZlUHJpb3JpdHlcIjogMX0sXHJcbiAgICB7XCJ0aXRsZVwiOiAnSGVybycsIFwid2lkdGhcIjogXCIxOCVcIiwgXCJzQ2xhc3NcIjogXCJzb3J0SWNvbl9UZXh0XCIsIFwiaURhdGFTb3J0XCI6IDIsIFwib3JkZXJTZXF1ZW5jZVwiOiBbJ2FzYycsICdkZXNjJ10sIFwicmVzcG9uc2l2ZVByaW9yaXR5XCI6IDJ9LCAvL2lEYXRhU29ydCB0ZWxscyB3aGljaCBjb2x1bW4gc2hvdWxkIGJlIHVzZWQgYXMgdGhlIHNvcnQgdmFsdWUsIGluIHRoaXMgY2FzZSBIZXJvX1NvcnRcclxuICAgIHtcInRpdGxlXCI6ICdIZXJvX1NvcnQnLCBcInZpc2libGVcIjogZmFsc2V9LFxyXG4gICAge1widGl0bGVcIjogJ1JvbGUnLCBcInZpc2libGVcIjogZmFsc2V9LFxyXG4gICAge1widGl0bGVcIjogJ1JvbGVfU3BlY2lmaWMnLCBcInZpc2libGVcIjogZmFsc2V9LFxyXG4gICAge1widGl0bGVcIjogJ0dhbWVzIFBsYXllZCcsIFwid2lkdGhcIjogXCIxOCVcIiwgXCJzQ2xhc3NcIjogXCJzb3J0SWNvbl9OdW1iZXJcIiwgXCJzZWFyY2hhYmxlXCI6IGZhbHNlLCBcIm9yZGVyU2VxdWVuY2VcIjogWydkZXNjJywgJ2FzYyddLCBcInJlc3BvbnNpdmVQcmlvcml0eVwiOiA1fSxcclxuICAgIHtcInRpdGxlXCI6ICdHYW1lcyBCYW5uZWQnLCBcIndpZHRoXCI6IFwiMTglXCIsIFwic0NsYXNzXCI6IFwic29ydEljb25fTnVtYmVyXCIsIFwic2VhcmNoYWJsZVwiOiBmYWxzZSwgXCJvcmRlclNlcXVlbmNlXCI6IFsnZGVzYycsICdhc2MnXSwgXCJyZXNwb25zaXZlUHJpb3JpdHlcIjogNn0sXHJcbiAgICB7XCJ0aXRsZVwiOiAnV2luIFBlcmNlbnQnLCBcIndpZHRoXCI6IFwiMTglXCIsIFwic0NsYXNzXCI6IFwic29ydEljb25fTnVtYmVyXCIsIFwic2VhcmNoYWJsZVwiOiBmYWxzZSwgXCJvcmRlclNlcXVlbmNlXCI6IFsnZGVzYycsICdhc2MnXSwgXCJyZXNwb25zaXZlUHJpb3JpdHlcIjogM30sXHJcbiAgICB7XCJ0aXRsZVwiOiAnJSDOlCcsIFwid2lkdGhcIjogXCI1JVwiLCBcInNDbGFzc1wiOiBcInNvcnRJY29uX051bWJlclwiLCBcInNlYXJjaGFibGVcIjogZmFsc2UsIFwib3JkZXJTZXF1ZW5jZVwiOiBbJ2Rlc2MnLCAnYXNjJ10sIFwicmVzcG9uc2l2ZVByaW9yaXR5XCI6IDR9XHJcbl07XHJcblxyXG5oZXJvZXNfc3RhdHNsaXN0Lm9yZGVyID0gW1s3LCAnZGVzYyddXTsgLy9UaGUgZGVmYXVsdCBvcmRlcmluZyBvZiB0aGUgdGFibGUgb24gbG9hZCA9PiBjb2x1bW4gOCBhdCBpbmRleCA3IGRlc2NlbmRpbmdcclxuaGVyb2VzX3N0YXRzbGlzdC5wcm9jZXNzaW5nID0gZmFsc2U7IC8vRGlzcGxheXMgYW4gaW5kaWNhdG9yIHdoZW5ldmVyIHRoZSB0YWJsZSBpcyBwcm9jZXNzaW5nIGRhdGFcclxuaGVyb2VzX3N0YXRzbGlzdC5kZWZlclJlbmRlciA9IHRydWU7IC8vRGVmZXJzIHJlbmRlcmluZyB0aGUgdGFibGUgdW50aWwgZGF0YSBzdGFydHMgY29taW5nIGluXHJcbmhlcm9lc19zdGF0c2xpc3QuYWpheCA9IGhlcm9kYXRhX2hlcm9lc19wYXRoOyAvL1JlcXVlc3RzIGRhdGEgZnJvbSB0aGUgcGF0aFxyXG4vL2hlcm9lc19zdGF0c2xpc3QucGFnZUxlbmd0aCA9IDI1OyAvL0NvbnRyb2xzIGhvdyBtYW55IHJvd3MgcGVyIHBhZ2VcclxuaGVyb2VzX3N0YXRzbGlzdC5wYWdpbmcgPSBmYWxzZTsgLy9Db250cm9scyB3aGV0aGVyIG9yIG5vdCB0aGUgdGFibGUgaXMgYWxsb3dlZCB0byBwYWdpbmF0ZSBkYXRhIGJ5IHBhZ2UgbGVuZ3RoXHJcbmhlcm9lc19zdGF0c2xpc3QucmVzcG9uc2l2ZSA9IHRydWU7IC8vQ29udHJvbHMgd2hldGhlciBvciBub3QgdGhlIHRhYmxlIGNvbGxhcHNlcyByZXNwb25zaXZlbHkgYXMgbmVlZFxyXG5oZXJvZXNfc3RhdHNsaXN0LnNjcm9sbFggPSBmYWxzZTsgLy9Db250cm9scyB3aGV0aGVyIG9yIG5vdCB0aGUgdGFibGUgY2FuIGNyZWF0ZSBhIGhvcml6b250YWwgc2Nyb2xsIGJhclxyXG5oZXJvZXNfc3RhdHNsaXN0LnNjcm9sbFkgPSBmYWxzZTsgLy9Db250cm9scyB3aGV0aGVyIG9yIG5vdCB0aGUgdGFibGUgY2FuIGNyZWF0ZSBhIHZlcnRpY2FsIHNjcm9sbCBiYXJcclxuaGVyb2VzX3N0YXRzbGlzdC5kb20gPSAgXCI8J3Jvdyc8J2NvbC1zbS0xMid0cj4+XCI7IC8vUmVtb3ZlIHRoZSBzZWFyY2ggYmFyIGZyb20gdGhlIGRvbSBieSBtb2RpZnlpbmcgYm9vdHN0cmFwcyBkZWZhdWx0IGRhdGF0YWJsZSBkb20gc3R5bGluZyAoc28gaSBjYW4gaW1wbGVtZW50IGN1c3RvbSBzZWFyY2ggYmFyIGxhdGVyKVxyXG5oZXJvZXNfc3RhdHNsaXN0LmluZm8gPSBmYWxzZTsgLy9Db250cm9scyBkaXNwbGF5aW5nIHRhYmxlIGNvbnRyb2wgaW5mb3JtYXRpb24sIHN1Y2ggYXMgaWYgZmlsdGVyaW5nIGRpc3BsYXlpbmcgd2hhdCByZXN1bHRzIGFyZSB2aWV3ZWQgb3V0IG9mIGhvdyBtYW55XHJcblxyXG4kKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbigpIHtcclxuICAgICQoJyNoc2wtdGFibGUnKS5EYXRhVGFibGUoaGVyb2VzX3N0YXRzbGlzdCk7XHJcblxyXG4gICAgJCgnI2hlcm9lcy1zdGF0c2xpc3QtdG9vbGJhci1zZWFyY2gnKS5vbihcInByb3BlcnR5Y2hhbmdlIGNoYW5nZSBjbGljayBrZXl1cCBpbnB1dCBwYXN0ZVwiLCBmdW5jdGlvbigpIHtcclxuICAgICAgICAkKCcjaHNsLXRhYmxlJykuRGF0YVRhYmxlKCkuc2VhcmNoKCQodGhpcykudmFsKCkpLmRyYXcoKTtcclxuICAgIH0pO1xyXG5cclxuICAgICQoJy5oc2wtcm9sZWJ1dHRvbicpLmNsaWNrKGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAkKCcjaHNsLXRhYmxlJykuRGF0YVRhYmxlKCkuc2VhcmNoKCQodGhpcykuYXR0cihcInZhbHVlXCIpKS5kcmF3KCk7XHJcbiAgICB9KTtcclxufSk7XG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL2pzL2hlcm9lcy1zdGF0c2xpc3QuanMiXSwic291cmNlUm9vdCI6IiJ9