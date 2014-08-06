(function (map) {
	function parse (query, splitter, separator) {
		if (query.indexOf('?') !== -1) {
			query = query.substr(query.indexOf('?')+1);
		}

		if (!splitter) splitter = '&';
		if (!separator) separator = '=';

		var dict = {},
		    parts = query.split(splitter);

		for (var i = 0, l = parts.length; i < l; ++i) {
			var part = parts[i].split(separator),
			    key = part.shift(),
			    value = part.join(separator);
			if (dict.hasOwnProperty(key)) {
				if (!(dict[key] instanceof Array)) {
					dict[key] = [dict[key]];
				}
				dict[key].push(value);
			} else {
				dict[key] = value;
			}
		}

		return dict;
	}

	function init (params, container, callback) {
		var callback = 'cb' + (''+Math.random()).substr(2),
		    script = document.createElement('script');

		window[callback] = function () {
			delete window[callback];

			if (typeof container === 'function') {
				container = container();
			}

			var ll = params.center.split(','),
			    center = new google.maps.LatLng(parseFloat(ll[0]), parseFloat(ll[1]));

			if (container && container.nodeName) {
				var map = new google.maps.Map(container, {
					center: center,
					zoom: parseInt(params.zoom) || 14,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				});

				var markers = [];

				if (params.markers) {
					for (var i = 0, l = params.markers.length; i < l; ++i) {
						var data = parse(params.markers[i], '%7C', ':'),
						    locations = [];

						for (var p in data) {
							if (data.hasOwnProperty(p) && !data[p]) {
								if (p.match(/^-?\d+(\.\d+)?,-?\d+(\.\d+)?$/)) {
									var ll = p.split(',');
									locations.push(new google.maps.LatLng(parseFloat(ll[0]), parseFloat(ll[1])));
								}
								delete data[p];
							}
						}

						for (var j = 0, m = locations.length; j < m; ++j) {
							markers.push(new google.maps.Marker({
								position: locations[j],
								map: map
							}));
						}

						console.log(locations, markers);
					}
				}

				if (typeof callback === 'function') {
					callback(map, markers);
				}
			}
		}

		script.src = '//maps.googleapis.com/maps/api/js?key=' + params.key + '&sensor=' + (params.sensor||false).toString() + '&callback=' + callback;

		document.body.appendChild(script);
	}

	if (map && map.nodeName == 'IMG') {
		var query = parse(map.getAttribute('src'));
		if (query.key && query.center) {
			if (query.markers && !(query.markers instanceof Array)) {
				query.markers = [query.markers];
			}
			init(query, function() {
				var wrapper = document.createElement('div'),
				    container = map.parentNode;
				wrapper.style.height = map.offsetHeight + 'px';
				container.insertBefore(wrapper, map);
				container.removeChild(map);
				wrapper.id = map.id;

				return wrapper;
			});
		}
	}
})(document.getElementById('map'));