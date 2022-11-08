<html>
<?php
  //ini_set("display_errors", true);
  require_once('includes/header.php');
?>
<title>Dashboard</title>
  <div class="content">
    <div class="mainPage2">
      <div>
        <h1 class="centered">Welcome to Intuitive</h1>
      </div>
      <p style="text-align: center;">
        Weekly Summary
      </p>
      <div style="width: 900px; margin-left: 220px;">
        <canvas id="myChart"></canvas>
      </div>
    </div>
  </div>
</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js" integrity="sha512-TW5s0IT/IppJtu76UbysrBH9Hy/5X41OTAbQuffZFU6lQ1rdcLHzpU5BzVvr/YFykoiMYZVWlr/PX1mDcfM9Qg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>

var weekData = [0];

$.ajax({
 url: "calls/get_weekly_summary.php",
 type: "POST",
 success: function(data){

   let returnData = JSON.parse(data);

   if(returnData["errors"] == null) {
    var weekData = returnData["data"];
   } else {
    var weekData = [0];
   }

   var maxValue = Math.round((Math.max.apply(null, weekData) * 1.5));

   const ctx = document.getElementById('myChart').getContext('2d');
   const myChart = new Chart(ctx, {
       type: 'bar',
       data: {
           labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
           datasets: [{
               label: '# of Shipments',
               data: weekData,
               backgroundColor: [
                   'rgba(255, 99, 132, 0.2)',
                   'rgba(54, 162, 235, 0.2)',
                   'rgba(255, 206, 86, 0.2)',
                   'rgba(75, 192, 192, 0.2)',
                   'rgba(153, 102, 255, 0.2)',
                   'rgba(255, 159, 64, 0.2)',
                   'rgba(0, 255, 255, 0.1)'
               ],
               borderColor: [
                   'rgba(255, 99, 132, 1)',
                   'rgba(54, 162, 235, 1)',
                   'rgba(255, 206, 86, 1)',
                   'rgba(75, 192, 192, 1)',
                   'rgba(153, 102, 255, 1)',
                   'rgba(255, 159, 64, 1)',
                   'rgba(0, 255, 255, 1)'
               ],
               borderWidth: 1
           }]
       },
       options: {
           scales: {
               y: {
                   beginAtZero: true,
                   max: maxValue
               }
           }
       }
   });
 }
});
</script>
