<script>
	import LoadScript from '../helpers/loadScript.svelte';
	const dependencies = [
		"https://api.mapbox.com/mapbox-gl-js/v1.2.0/mapbox-gl.js"
	];

	export let view;
	export let classname;

	/*
	* mapbox api
	* https://docs.mapbox.com/mapbox-gl-js/api/
	* someone mixed up lat <-> lon, i dont know, just try, error, fix
	*/

	var map;

	let mapPositions = {
		lat: 25,
		lon: 30,
		zoom: 1
	};

	const mapstyles = {
		dark: 'mapbox://styles/moriwaan/cjzqsxone04y21ctdxp1dgogb',
		light: 'mapbox://styles/moriwaan/ck3njz1ds2lgm1cqp2poywa3i'
	};

	let loaded = false;

	let popups = {
		visible: false,
		threshold: 16,
		elements: [],
		show: function(){

			console.log('show()');

			let els = this.elements;

			view.content.forEach(function(marker) {

				let item = marker.properties;
				let html = '<li class="card">'+
					'<a onclick="navi(event)" href="'+item.url+'" data-template="'+item.template+'">';
						if(item.thumbnail){
							html +='<figure>' + item.thumbnail + '</figure>';
						}
						html += '<div class="title">';
							html += '<span class="count">' + ( item.count || 1 ) + '</span>';
							html += '<h4>'+item.title+'</h4>';
						html += '</div>';
					html += '</a>';
				html += '</li>';

				var popup = new mapboxgl.Popup({ closeOnClick: false, closeButton: false, anchor: 'bottom-left' })
					.setLngLat( marker.geometry.coordinates )
					.setHTML( html )
					.addTo( map );
				els.push( popup );

			});
			this.visible = true;

		},
		hide: function(){
			for (const popup of this.elements) {
				popup.remove();
			}
			this.elements = [];
			this.visible = false;
		}
	};

	function mapInit(){

		console.log('mapInit');

		mapboxgl.accessToken = 'pk.eyJ1IjoibW9yaXdhYW4iLCJhIjoiY2l4cnIxNTFvMDAzZjJ3cGJ6MmpiY2ZmciJ9.KnmjmhWCBzMm-D30JdnnXg';

		map = new mapboxgl.Map({
			container: 'map',
			style: mapstyles.light,
			center: [ mapPositions.lon, mapPositions.lat ],
			zoom: mapPositions.zoom
		});

		map.on('load', function() {

			map.addSource("buildings", {
				"type": "geojson",
				"data": {
					"type": "FeatureCollection",
					"features": view.content
				},
				cluster: true,
				clusterMaxZoom: 7, // Max zoom to cluster points on
				clusterRadius: 24 // Radius of each cluster when clustering points
			});

			map.addLayer({
				"id": "dots",
				"type": "circle",
				"source": "buildings",
				"filter": ["==", "$type", "Point"],
				"paint": {
					'circle-radius': {
						'base': 5,
						// adjust radius to zoom level [[zoom, radius],...]
						'stops': [ [2, 18], [6, 12], [8, 5], [10, 4], [13, 4], [16, 8], [22, 180] ]
					},
					'circle-color': '#00f'
				},
			});

			loaded = true;

		});

		map.on('move', function (e) {
			var center = map.getCenter();
			mapPositions.lat = round( center.lat );
			mapPositions.lon = round( center.lng );
		});

		map.on('zoom', function (e) {
			var zoom = map.getZoom();
			mapPositions.zoom = round( zoom, 0 );

			if( zoom > popups.threshold && popups.visible === false ){
				popups.show();
			} else if( zoom < popups.threshold && popups.visible === true ){
				popups.hide();
			}

		});

		map.on('mouseenter', 'dots', function () {
			map.getCanvas().style.cursor = 'pointer';
		});
		map.on('mouseleave', 'dots', function () {
			map.getCanvas().style.cursor = '';
		});

		map.on('click', 'dots', function (e) {
			var features = map.queryRenderedFeatures(e.point, { layers: ['dots'] });
			console.log( features[0] );
			var cluster_id = features[0].properties.cluster_id;
			if( cluster_id !== undefined ){
				map.getSource('buildings').getClusterExpansionZoom(cluster_id, function (err, zoom) {
						if (err){ return; }
					map.easeTo({
						center: features[0].geometry.coordinates,
						zoom: zoom
					});
				});
			} else {
				let zoomTo = map.getZoom() + 3;
				if( zoomTo > 10 ){
					zoomTo = popups.threshold + 0.2;
				}
				map.easeTo({
					center: features[0].geometry.coordinates,
					zoom: zoomTo
				});
			}
		});

	};

	function round( f, d = 2 ){
		// round coords
		return f.toFixed(d);
	}

</script>

<style>
	#map {
		height: 100%;
	}

	/* Marker tweaks */

	#map :global(.mapboxgl-popup-content) {
		background-color: transparent;
		border-radius: 0;
		padding: 0;
	}

	#map :global(.mapboxgl-popup > .mapboxgl-popup-tip) {
		display: none;
	}

</style>

<LoadScript on:loaded={mapInit} dependencies={dependencies}/>
<svelte:head>
	<link href="https://api.mapbox.com/mapbox-gl-js/v1.2.0/mapbox-gl.css" rel="stylesheet">
</svelte:head>

<section class="{classname} {view.type}">

	<div class="section--content">
		<div id='map' style='width: 100%; height: 100%;'></div>
	</div>

	<div class="section--controls bar controls" id="map-controls">
		{#if loaded === false }
			<span class="message">Loading...</span>
		{:else}
			<span class="right">
				<span class="map-position">{mapPositions.lat}, {mapPositions.lon}, {mapPositions.zoom}</span>
			</span>
		{/if}
	</div>

</section>
