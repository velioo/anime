$(document).ready(function() {
	
	var scores = $('#ratings_chart_div').data('scores');	
	var split_scores = scores.split(",");
	
	google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Element", "Num ratings", { role: "style" } ],
        ["5", parseInt(split_scores[9]), "#D93D48"],
        ["4.5", parseInt(split_scores[8]), "#E0613F"],
        ["4", parseInt(split_scores[7]), "#E3733A"],
        ["3.5", parseInt(split_scores[6]), "#E78536"],
        ["3", parseInt(split_scores[5]), "#EB9732"],
        ["2.5", parseInt(split_scores[4]), "#EEA92D"],
        ["2", parseInt(split_scores[3]), "#F2BB29"],
        ["1.5", parseInt(split_scores[2]), "#F5CD24"],
        ["1", parseInt(split_scores[1]), "#F9DF20"],
        ["0.5", parseInt(split_scores[0]), "#FDF21C"]
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);
      
      var options = {
        title: split_scores[10] + " total",
        titleTextStyle: {
        	  color: "#4F5155",
        	  fontName: "sans-serif",
        	  fontSize: 25,
        	  bold: false},
        width: 550,
        height: 250,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },  
        backgroundColor: 'transparent',
       /* enableInteractivity: false,*/
        tooltip: {
            showTitle: false
            /*trigger: "none"*/
        }
   /*      vAxis:{
            baselineColor: '#fff',
            gridlineColor: '#fff',
            textPosition: 'none'
          }*/
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("ratings_chart_div"));
      chart.draw(view, options);
    }
});