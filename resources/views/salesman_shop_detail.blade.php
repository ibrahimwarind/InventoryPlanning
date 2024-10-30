<!DOCTYPE html>
<!--
 @license
 Copyright 2019 Google LLC. All Rights Reserved.
 SPDX-License-Identifier: Apache-2.0
-->
<html>
  <head>
    <input type="hidden" id="csrf_token" value="{{csrf_token()}}">
    <title>Salesman Day Route - {{$smancode}} - {{$smanname}}</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/51238610b6.js" crossorigin="anonymous"></script>

    <!-- jsFiddle will insert css and js -->
  </head>
  <style type="text/css">
    /**
 * @license
 * Copyright 2019 Google LLC. All Rights Reserved.
 * SPDX-License-Identifier: Apache-2.0
 */
/* Optional: Makes the sample page fill the window. */
html,
body {
  height: 100%;
  margin: 0;
  padding: 0;
}

#container {
  height: 100%;
  display: flex;
}

#sidebar {
  flex-basis: 15rem;
  flex-grow: 1;
  padding: 1rem;
  max-width: 30rem;
  height: 100%;
  box-sizing: border-box;
  overflow: auto;
}

#map {
/*  flex-basis: 0;
  flex-grow: 4;*/
  height: 700px;
}
#map2 {
/*  flex-basis: 0;
  flex-grow: 4;*/
  height: 700px;
  width:300px;
}
#directions-panel {
  margin-top: 10px;
}
 .example-print {
        display: none;
    }

    @media print {
        .example-screen {
            display: none;
        }

        .example-print {
            display: block;
        }

        .maih {
            font-size: 15px;
        }

        

 

    }
    @page { size: auto;  margin: 0mm; }
    .panel-footer {
  padding: 10px 15px;
  background-color: #f5f5f5;
  border-top: 1px solid #ddd;
  border-bottom-right-radius: 3px;
  border-bottom-left-radius: 3px;
}
.panel-green {
  border: 2px dashed #398439;
  font-weight:bold;
  background-color:white !important;
}
a {
  background-color: transparent;
}
.panel {
  margin-bottom: 10px;
  background-color:white !important;
  border-radius: 4px;

}
.green {
  color: #398439;
  font-size:bold;
}

.pull-left {
  float: left !important;
}

.pull-right {
  float: right !important;
}
/*#map.fullscreen {
  position: fixed;
  width:100%;
  height: 100%;
}*/
  </style>
  <body>
    <div class="container-fluid example-screen">
    <input type="hidden" value="{{$smancode}}" id="smancode" name="smancode">
        <input type="hidden" value="{{$smanname}}" id="smanname" name="smanname">
        <input type="hidden" value="{{$strtime}}" id="strtime" name="strtime">
        <input type="hidden" value="{{$endtime}}" id="endtime" name="endtime">
        <input type="hidden" value="{{$totalcust}}" id="totalcust" name="totalcust">
        <input type="hidden" value="{{$hour}}" id="hour" name="hour">
        <input type="hidden" value="{{$minute}}" id="minute" name="minute">
        <input type="hidden" value="{{$brcode}}" id="brcode" name="brcode">
        <input type="hidden" value="{{$tripdate}}" id="tripdate" name="tripdate">
        <input type="hidden" value="{{$tripstrlat}}" id="tripstrlat" name="tripstrlat">
        <input type="hidden" value="{{$tripstrlong}}" id="tripstrlong" name="tripstrlong">
        <input type="hidden" value="{{$tripendlat}}" id="tripendlat" name="tripendlat">
        <input type="hidden" value="{{$tripendlong}}" id="tripendlong" name="tripendlong">
        <input type="hidden" value="{{$branchlat}}" id="branchlat" name="branchlat">
        <input type="hidden" value="{{$branchlng}}" id="branchlng" name="branchlng">
        
      <div class="row">
        <div class="col-sm-9 col-12">
            <div id="map"></div>
        </div>

        <div class="col-sm-3 col-12">
           <div id="" class="" style="height: 700px;overflow-y:auto">
        <div>
        <!--   <b>Start:</b>
          <select id="start">
            <option value="Bait-ul-Mukaram Masjid, Block 8 Gulshan-e-Iqbal, Karachi">Bait-ul-Mukaram Masjid, Block 8 Gulshan-e-Iqbal, Karachi</option>
            <option value="Boston, MA">Boston, MA</option>
            <option value="New York, NY">New York, NY</option>
            <option value="Miami, FL">Miami, FL</option>
          </select> -->
          <h4 style="margin-top:10px;tex-align:center;text-decoration:underline;display:inline-block">Salesman Customer Detail</h4>

          <div class="panel panel-green" style="background-color:#fffff !important;margin-top:15px">
            
            <a >
                <div class="panel-footer">
                    <span class="pull-left green">Branch Name<br>{{$brcode}} - {{$brname}}</span>
                    <span class="pull-right green"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div> 
        
        <!-- //2 -->
        <div class="panel panel-green" style="background-color:#fffff !important;margin-top:15px">
            
            <a >
                <div class="panel-footer">
                    <span class="pull-left green">Salesman Name<br> {{$smancode}} - {{$smanname}}</span>
                    <span class="pull-right green"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div> 

         <!-- //2 -->
         <div class="panel panel-green" style="background-color:#fffff !important;margin-top:15px">
            
            <a >
                <div class="panel-footer">
                    <span class="pull-left green">Trip Date<br> {{date("d-M-Y", strtotime($tripdate))}}</span>
                    <span class="pull-right green"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div> 
        <div class="panel panel-green" style="background-color:#fffff !important;margin-top:15px">
            
            <a >
                <div class="panel-footer">
                    <span class="pull-left green">Total Trip Customer<br> {{$totalcust}}</span>
                    <span class="pull-right green"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div> 
         

        

        <div class="panel panel-green" style="background-color:#fffff !important;margin-top:15px">
            
            <a >
                <div class="panel-footer">
                    <span class="pull-left green">Total Travelling Duration<br>{{$hour}} hours {{$minute}} minutes</span>
                    <span class="pull-right green"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div> 
        
       

       

         <!-- //2 -->
       
        
          
          <br />
         
       
        </div>
        <div id="directions-panel"></div>
      </div>

        </div>
      </div>
    
         </div>

    <!-- 
     The `defer` attribute causes the callback to execute after the full HTML
     document has been parsed. For non-blocking uses, avoiding race conditions,
     and consistent behavior across browsers, consider loading using Promises
     with https://www.npmjs.com/package/@googlemaps/js-api-loader.
    -->
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAFkvJ5vPzUed6bCEBWo6UC11RnthpwVdo&callback=initMap&v=weekly"
      defer
    ></script>




  </body>
</html>
<script type="text/javascript">
// const geocoder = new google.maps.Geocoder();
    
</script>
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
<script type="text/javascript">


  /**
 * @license
 * Copyright 2019 Google LLC. All Rights Reserved.
 * SPDX-License-Identifier: Apache-2.0
 */
function initMap() {
  const directionsService = new google.maps.DirectionsService();

  //  const directionsRenderer = new google.maps.DirectionsRenderer({
  //   draggable: true,
  //   panel: document.getElementById("map"),
  // });

  var branchlat=parseFloat($('#branchlat').val());
  var branchlng=parseFloat($('#branchlng').val());
   const directionsRenderer = new google.maps.DirectionsRenderer();
  
  const map = new google.maps.Map(document.getElementById("map"), {
    zoom: 10,
    center: { lat: branchlat, lng: branchlng },
  });

   var smancode=$('#smancode').val();
   var tripdate=$('#tripdate').val();
   var brcode=$('#brcode').val();
     
   // Fetch markers from Laravel controller
   const queryParams = new URLSearchParams({ tripdate, smancode, brcode }).toString();


   fetch(`/get-markers2?${queryParams}`)
  .then(response => response.json())
  .then(markers => {
    
    const infoWindow = new google.maps.InfoWindow(); // Create a single InfoWindow instance
  
    markers.forEach(markerInfo => {
             
      const marker = new google.maps.Marker({
        position: markerInfo.position,
        map: map,
        title: markerInfo.title,
        icon: markerInfo.icon.url
      });
console.log(marker);
      // Add a click listener to the marker
      marker.addListener('click', () => {
        infoWindow.setContent(markerInfo.title); // Set the content of the InfoWindow
        infoWindow.open(map, marker); // Open the InfoWindow on the marker
      });
    });
  })
  .catch(error => console.error('Error fetching markers:', error));
  
  // const markers = [
  //   {
  //     position: { lat: 24.9353513, lng: 67.0971389 },
  //     title: 'Marker 1',
  //     icon: {
  //       url: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png",
  //     }
  //   },
  //   {
  //     position: { lat: 24.938795, lng: 67.112889 },
  //     title: 'Marker 2',
  //     icon: {
  //       url: "http://maps.google.com/mapfiles/ms/icons/green-dot.png",
  //     }
  //   }
  //   // Add more markers as needed
  // ];

  // Add the markers to the map
  // markers.forEach(markerInfo => {
  //   new google.maps.Marker({
  //     position: markerInfo.position,
  //     map: map,
  //     title: markerInfo.title,
  //     icon: markerInfo.icon
  //   });
  // });
  //srf ek marker add krna hoto
  // const additionalMarker = new google.maps.Marker({
  //   position: { lat: 24.9353513, lng: 67.0971389 }, // Replace with your desired latitude and longitude
  //   map: map,
  //   title: 'Additional Marker',
  //   icon: {
  //     url: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png", // URL to the custom icon
  //   }
  // });


//   document.getElementById("submit").addEventListener("click", () => {
//     $('#submit').prop('disabled',false);
    
//   });


  //ye lat long ke name ka he


// LocationsForMap.push([24.9067719,67.0811753]);
// LocationsForMap.push([24.8869297,67.1261643]);
// LocationsForMap.push([24.8738209,67.1922701]);
// for (i = 0; i <= LocationsForMap.length; i++) {
//   alert(LocationsForMap[i][0]);
// var latlng = new google.maps.LatLng(LocationsForMap[i][0],LocationsForMap[i][1]);
//     var geocoder = geocoder = new google.maps.Geocoder();
//     geocoder.geocode({ 'latLng': latlng }, function (results, status) {
//       //  alert('asdas');
//       document.getElementById("waypoints").innerHTML+="<option>"+results[1].formatted_address+"</option>";
//     });
// }


// var LocationsForMap = [];
//  LocationsForMap.push([24.9067719,67.0811753]);
// LocationsForMap.push([24.8869297,67.1261643]);
// LocationsForMap.push([24.8738209,67.1922701]);
// LocationsForMap.push([24.9067719,67.0811753]);
// LocationsForMap.push([24.8869297,67.1261643]);
// LocationsForMap.push([24.8738209,67.1922701]);
// LocationsForMap.push([24.8738209,67.1922701]);
//  for (i = 0; i <= LocationsForMap.length; i++) {

//                  var latlng = new google.maps.LatLng(LocationsForMap[i][0],LocationsForMap[i][1]);
//     var geocoder = geocoder = new google.maps.Geocoder();
//     // var orderid=res[i].order_id;
//     // var dfname=res[i].delivery_fname;

   
//     geocoder.geocode({ 'latLng': latlng }, function (results, status) {
//       //  alert('asdas');
//      // alert(orderid);
//       document.getElementById("waypoints").innerHTML+="<option value='"+results[1].formatted_address+"'>"+results[1].formatted_address+"</option>";
//     });

//               }

  
  //  directionsRenderer.addListener("directions_changed", () => {
  //   const directions = directionsRenderer.getDirections();

  //   if (directions) {
  //     calculateAndDisplayRoute(directionsService,directionsRenderer);
  //   }
  // });
}



window.initMap = initMap;









</script>