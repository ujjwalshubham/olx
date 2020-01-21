/**
 * lodash 3.0.0 (Custom Build) <https://lodash.com/>
 * Build: `lodash modern modularize exports="npm" -o ./`
 * Copyright 2012-2015 The Dojo Foundation <http://dojofoundation.org/>
 * Based on Underscore.js 1.7.0 <http://underscorejs.org/LICENSE>
 * Copyright 2009-2015 Jeremy Ashkenas, DocumentCloud and Investigative Reporters & Editors
 * Available under MIT license <https://lodash.com/license>
 */
var baseCallback = require('lodash._basecallback');

/**
 * This method is like `_.findIndex` except that it iterates over elements
 * of `collection` from right to left.
 *
 * If a property name is provided for `predicate` the created `_.property`
 * style callback returns the property value of the given element.
 *
 * If a value is also provided for `thisArg` the created `_.matchesProperty`
 * style callback returns `true` for elements that have a matching property
 * value, else `false`.
 *
 * If an object is provided for `predicate` the created `_.matches` style
 * callback returns `true` for elements that have the properties of the given
 * object, else `false`.
 *
 * @static
 * @memberOf _
 * @category Array
 * @param {Array} array The array to search.
 * @param {Function|Object|string} [predicate=_.identity] The function invoked
 *  per iteration.
 * @param {*} [thisArg] The `this` binding of `predicate`.
 * @returns {number} Returns the index of the found element, else `-1`.
 * @example
 *
 * var users = [
 *   { 'user': 'barney',  'active': true },
 *   { 'user': 'fred',    'active': false },
 *   { 'user': 'pebbles', 'active': false }
 * ];
 *
 * _.findLastIndex(users, function(chr) {
 *   return chr.user == 'pebbles';
 * });
 * // => 2
 *
 * // using the `_.matches` callback shorthand
 * _.findLastIndex(users, { 'user': 'barney', 'active': true });
 * // => 0
 *
 * // using the `_.matchesProperty` callback shorthand
 * _.findLastIndex(users, 'active', false);
 * // => 2
 *
 * // using the `_.property` callback shorthand
 * _.findLastIndex(users, 'active');
 * // => 0
 */
function findLastIndex(array, predicate, thisArg) {
  var length = array ? array.length : 0;
  predicate = baseCallback(predicate, thisArg, 3);
  while (length--) {
    if (predicate(array[length], length, array)) {
      return length;
    }
  }
  return -1;
}

module.exports = findLastIndex;
