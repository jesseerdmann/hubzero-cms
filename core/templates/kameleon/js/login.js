/**
 * @package    hubzero-cms
 * @copyright  Copyright (c) 2005-2020 The Regents of the University of California.
 * @license    http://opensource.org/licenses/MIT MIT
 */

jQuery(document).ready(function($){
	(function worker() {
		$.ajax({
			url: 'index.php',
			complete: function() {
				setTimeout(worker, 3540000);
			}
		});
	})();

	if (document.getElementById('form-login')) {
		document.getElementById('form-login').username.select();
		document.getElementById('form-login').username.focus();
	}
});
