<?php
//OpenLayers free api for maps and routing
// if(!EMPTY($_GET)) {
//   die(var_dump($_GET));
// }
?>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no" />
    <title>Map</title>
    <style>
      html,
      body,
      #map {
        padding: 0;
        margin: 0;
        height: 100%;
        width: 100%;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 14px;
        color: #323232;
      }

      #directions {
        position: absolute;
        width: 30%;
        max-height: 50%;
        right: 20px;
        top: 20px;
        overflow-y: auto; /* Show a scrollbar if needed */
        background: white;
        font-family: Arial, Helvetica, Verdana;
        line-height: 1.5;
        font-size: 14px;
        padding: 10px;
      }
    </style>
    <!--CDNs that work for now but could fail in the future -->
    <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v7.1.0/ol.css">
    <script src="https://cdn.jsdelivr.net/npm/ol@v7.1.0/dist/ol.js"></script>-->

    <!-- Downloaded openlayers package -->
    <link rel="stylesheet" href="./v7.1.0-package/ol.css" type="text/css" />
    <script src="./v7.1.0-package/dist/ol.js"></script>

    <!-- Our original openlayers cdn that has since expired -->
    <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.7.0/css/ol.css" type="text/css" />
    <script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.7.0/build/ol.js"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/ol-mapbox-style@6.1.4/dist/olms.js" type="text/javascript"></script>
    <script src="https://unpkg.com/@esri/arcgis-rest-request@3.0.0/dist/umd/request.umd.js"></script>
    <script src="https://unpkg.com/@esri/arcgis-rest-routing@3.0.0/dist/umd/routing.umd.js"></script>
    <script src="https://unpkg.com/@esri/arcgis-rest-auth@3.0.0/dist/umd/auth.umd.js"></script>


  </head>
  <body>
    <div id="map"></div>
    <div id="directions">Click on the map to create a start and end for the route.</div>
    <script>
      const apiKey = "AAPK6b9710ea6bb14ec0a482426bfb31e847aYMdmxNkt-WTH0DBaH00qe7YzrAyvDMVcSlfaVoydDwcGBmssQj1TIATlWYgA7Z8";

      const map = new ol.Map({
        target: "map"
      });

      const view = new ol.View({

        center: ol.proj.fromLonLat([-95.805, 35.027]),

        zoom: 5
      });
      map.setView(view);

      let startLayer, endLayer, routeLayer;
      function addCircleLayers() {

        startLayer = new ol.layer.Vector({
          style: new ol.style.Style({
            image: new ol.style.Circle({
              radius: 6,
              fill: new ol.style.Fill({ color: "white" }),
              stroke: new ol.style.Stroke({ color: "black", width: 2 })
            })
          })
        });
        map.addLayer(startLayer);
        endLayer = new ol.layer.Vector({
          style: new ol.style.Style({
            image: new ol.style.Circle({
              radius: 7,
              fill: new ol.style.Fill({ color: "black" }),
              stroke: new ol.style.Stroke({ color: "white", width: 2 })
            })
          })
        });

        map.addLayer(endLayer);

      }

      let currentStep = "start";
      let startCoords, endCoords;

      const geojson = new ol.format.GeoJSON({
        defaultDataProjection: "EPSG:4326",
        featureProjection: "EPSG:3857"
      });

      map.on("click", (e) => {

        const coordinates = ol.proj.transform(e.coordinate, "EPSG:3857", "EPSG:4326");
        const point = {
          type: "Point",
          coordinates
        };

        if (currentStep === "start") {

          startLayer.setSource(
            new ol.source.Vector({
              features: geojson.readFeatures(point)
            })
          );
          startCoords = coordinates;

          // clear endCoords and route if they were already set
          if (endCoords) {
            endCoords = null;
            endLayer.getSource().clear();

            routeLayer.getSource().clear();

            document.getElementById("directions").innerHTML = "";
            document.getElementById("directions").style.display = "none";

          }

          currentStep = "end";
        } else {

          endLayer.setSource(
            new ol.source.Vector({
              features: geojson.readFeatures(point)
            })
          );
          endCoords = coordinates;
          currentStep = "start";

          updateRoute(startCoords, endCoords);

        }

      });

      function setStartingPoint(x, y) {
        let coordinatesData = [];
        coordinatesData[0]= Number(y);
        coordinatesData[1]= Number(x);

        let coordinatesDataConverted = ol.proj.fromLonLat(coordinatesData);

        const coordinates = ol.proj.transform(coordinatesDataConverted, "EPSG:3857", "EPSG:4326");
        const point = {
          type: "Point",
          coordinates
        };
          startLayer.setSource(
            new ol.source.Vector({
              features: geojson.readFeatures(point)
            })
          );
          startCoords = coordinates;
      }

      function setEndPoint(x, y) {
        let coordinatesData = [];
        coordinatesData[0]= Number(y);
        coordinatesData[1]= Number(x);

        let coordinatesDataConverted = ol.proj.fromLonLat(coordinatesData);

        const coordinates = ol.proj.transform(coordinatesDataConverted, "EPSG:3857", "EPSG:4326");
        const point = {
          type: "Point",
          coordinates
        };

        endLayer.setSource(
          new ol.source.Vector({
            features: geojson.readFeatures(point)
          })
        );
        endCoords = coordinates;
        currentStep = "start";

        updateRoute(startCoords, endCoords);
      }

      function addRouteLayer() {
        routeLayer = new ol.layer.Vector({
          style: new ol.style.Style({
            stroke: new ol.style.Stroke({ color: "hsl(205, 100%, 50%)", width: 4, opacity: 0.6 })
          })
        });

        map.addLayer(routeLayer);
      }

      function updateRoute() {
        const authentication = new arcgisRest.ApiKey({
          key: apiKey
        });
        arcgisRest

          .solveRoute({
            stops: [startCoords, endCoords],
            authentication
          })

          .then((response) => {

            routeLayer.setSource(
              new ol.source.Vector({
                features: geojson.readFeatures(response.routes.geoJson)
              })
            );

            const directionsHTML = response.directions[0].features.map((f) => f.attributes.text).join("<br/>");
            document.getElementById("directions").innerHTML = directionsHTML;
            document.getElementById("directions").style.display = "block";

          })

          .catch((error) => {
            alert("There was a problem using the geocoder. See the console for details.");
            console.error(error);
          });

      }

      const basemapId = "ArcGIS:Navigation";

      const basemapURL = "https://basemaps-api.arcgis.com/arcgis/rest/services/styles/" + basemapId + "?type=style&token=" + apiKey;

      olms(map, basemapURL)

        .then(function (map) {

          addCircleLayers();
          addRouteLayer();
          setStartingPoint('<?=$_GET['ship_from_x']?>','<?=$_GET['ship_from_y']?>');
          setEndPoint('<?=$_GET['ship_to_x']?>', '<?=$_GET['ship_to_y']?>');

        });
    </script>
  </body>
</html>
