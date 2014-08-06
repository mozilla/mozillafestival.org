(function (overview) {

	overview.className += ' enabled';

	var items = overview.getElementsByTagName('li'),
	    pages = [],
	    dates = {};

	// function for highlighting the appropriate tab
	var select = function (index) {
		var before = true;
		for (var i = 0, l = pages.length; i < l; ++i) {
			if (i === index) {
				pages[i].className = 'active';
				before = false;
			} else {
				pages[i].className = (before ? 'before' : 'after');
			}
		}
	}

	// Extract 'pages' from list items
	// No guarantee that all <li>s are actually pages
	// Could have used querySelector, but for a tiny performance hit, might as
	//   well not rule out browsers
	for (var i = 0, l = items.length; i < l; ++i) {
		if (items[i].parentNode === overview) {
			pages.push(items[i]);
		}
	}

	// Precalculate size of tabs - no point in doing that in the loop
	var tabSize = 100 / pages.length;

	for (var i = 0, l = pages.length; i < l; ++i) {
		(function(index) {
			var tab = pages[index].getElementsByTagName('h3')[0],
			    label = (tab.innerText || tab.textContent).replace(/\s+/, ' '),
			    date = new Date(label + ' November, 2012');

			// Store index against date for reference later
			dates[date.toDateString()] = index;

			// Style tab appropriately
			tab.style.width = tabSize + '%';
			tab.style.left = (tabSize * index) + '%';

			// Handle it being clicked/focused
			tab.setAttribute('tabindex', '0');
			tab.onfocus = function () { select(index); };
		})(i);
	}

	// Attempt to highlight the current day, if a tab exists for it
	var today = (new Date()).toDateString(),
	    index = dates[today] || 0;

	select(index);

})(document.getElementById('overview-ator'));